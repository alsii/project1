<?php
namespace App\Kernel;

/**
 * Parser parses a request string by matching it with the set of patterns stored in an array.
 * Each pattern is an array of the following structure:
 * 
 * array(
     'name' => string // Route name
 *   'pattern' => string // Pattern string
 *   'controller' => string // Controller name without 'Controller' suffix
 *   'action' => string // Action name without 'Action' suffix
 * )
 */
class Parser
{

  const TOKEN_PARAMETER = 0;
  const TOKEN_PATH = 1;

  /**
   * @var array An array of the patterns
   */
  private $patterns;

  public function __construct($patterns)
  {
    $this->patterns = $patterns;
  }
  
  public function parse($req)
  {
    $fields = explode('/',$req );
    $fields_number = count($fields);

    foreach($this->patterns as $pattern) {
      $result = $this->checkPattern($pattern, $fields, $fields_number);
      if ($result) {
        break;
      }
    }
    
    return $result;
  }

  private function checkPattern($pattern, $fields, $fields_number)
  {
    $tokens = $this->tokenize($pattern['pattern']);
    if(count($tokens) != $fields_number) { // Count of the request fields do not match the tokens count of the current pattern
      return false;
    }
    
    $parameters = $this->checkTokens($tokens, $fields);
    if($parameters !== false) {
      return [
        'controller' => $pattern['controller'].'Controller',
        'action' => $pattern['action'].'Action',
        'parameters' => $parameters,
      ];
    }
    
    return false;
  }

  private function checkTokens($tokens, $fields)
  {
    $parameters = [];

    reset($fields);
    foreach($tokens as $token) {
      $field = current($fields);
      $match = $this->matchToken($field, $token);
      if($match === false) {
          return false;
      }
      
      if($match !== true) {
        $parameters[$token['value']] = $match;
      }
     
      next($fields);
    }
    
    return $parameters;
  }
  
  /**
   * Формирует URL по заданному маршруту и параметрам
   */
  public function buildUrl($routeName, $parameters)
  {
    $route = $this->findRouteByName($routeName);
    $url = $route['pattern'];

    $tokens = $this->tokenize($url);
    foreach($tokens as $token) {
      if($token['type'] === self::TOKEN_PARAMETER) {
        $name = $token['value'];
        $this->isParameterSet($parameters, $name, $routeName);
        $url = str_replace(":$name", $parameters[$name], $url);
      }
    }    
    
    return $url;
  }

  private function isParameterSet($parameters, $name, $routeName)
  {
    if(!isset($parameters[$name])) {
      throw new \Exception("Parameter '{$name}' is not set for route '{$routeName}'");
    }
  }

  private function findRouteByName($routeName)
  {
    foreach($this->patterns as $pattern) {
      if(isset($pattern['name']) && $pattern['name'] === $routeName) {
        return $pattern;
      }
    }

    throw new \Exception("Route {$routeName} not found.");
  }
  
  protected function matchToken($field, $token)
  {
    if($token['type'] === self::TOKEN_PATH) {
      return $token['value'] === $field;
    } else {
      return $field;
    }
  }

 /**
  * tokenize() function split the pattern string into the array of tokens.
  * There are two kinds of tokens: Parameter-Token and Path-Token.
  *
  * Parameter-Token represents the /:parameter/ part of the pattern string
  *   where 'parameter' is a word not starts with a semicolon ':'.
  *
  * Path-Token repersent the /path/ part of the pattern string, 
  *   where 'path' is a word not starts with a semicolon ':'.
  *
  * Each token is an array of two elements:
  *
  * array(
  *   'type' => string // 'parameter' or 'path'
  *   'value' => string // value of Parameter-Token or Path-Token 
  * )
  *
  * Example:
  *  /guestbook/:id/delete
  *
  *  'guestbook' -> array('type' => self::TOKEN_PARAMETER, 'value' => 'guestbook')
  *  ':id'       -> array('type' => self::TOKEN_PATH, value' => 'id')
  *
  *
  * @return array Tokenized pattern
  * @param string $pattern Pattern as a string
  */
  
  protected function tokenize($pattern)
  {
    $raw_tokens = explode('/', $pattern);
    $tokens = array();
    
    foreach ($raw_tokens as $raw_token) {
      $first_char = substr($raw_token, 0, 1);
      
      if(':' == $first_char) {
        $tokens[] = array('type' => self::TOKEN_PARAMETER, 'value' => substr($raw_token,1));
      } else {
        $tokens[] = array('type' => self::TOKEN_PATH, 'value' => $raw_token);
      }
    }
    
    return $tokens;
  }

}
