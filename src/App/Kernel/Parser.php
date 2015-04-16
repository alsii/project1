<?php
namespace App\Kernel;

/**
 * Parser parses a request string by matching it with the set of patterns stored in an array.
 * Each pattern is an array of the following structure:
 * 
 * array(
 *   'pattern' => string // Pattern string
 *   'controller' => string // Controller name without 'Controller' suffix
 *   'action' => string // Action name without 'Action' suffix
 * )
 */
class Parser
{
  /**
   * @var array An array of the patterns
   */
  private $patterns;

  public function __construct($patterns)
  {
    $this->patterns = $patterns;
  }
  
  //TODO This method to be refactored. Extract the internal loop into a separate method.
  public function parse($req)
  {
    $fields = explode('/',$req );
    $fields_number = count($fields);

    foreach($this->patterns as $pattern) {
      $parameters = array();
      $tokens = $this->tokenize($pattern['pattern']);
      
      if(count($tokens) != $fields_number) { // Count of the request fields do not match the tokens count of the current pattern
        continue; // skip current pattern
      }
      reset($fields);
      
      foreach($tokens as $token) {
        
        $match = $this->matchToken(current($fields), $token);
        if($match) {
          if(is_array($match)) {
            $parameters[$match['name']] = $match['value']; 
          }
        } else {
          break;
        }
        next($fields);
      }
      
      if($match) {
        return array(
          'controller' => $pattern['controller'].'Controller',
          'action' => $pattern['action'].'Action',
          'parameters' => $parameters,
        );
      }
    }
    return false;
  }
  
  protected function matchToken($field, $token)
  {
    if(empty($token['name'])) {
      if($token['value'] == $field) {
        return true;
      }
    } else {
      $token['value'] = $field;
      return $token;
    }
    return false;
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
  *   'name' => string // name of Parameter-Token or empty string for Path-Token
  *   'value' => string // empty string for Parameter-Token or 'path' value of Path-Token 
  * )
  *
  * @return array Tokenized pattern
  * @param string $pattern Pattern as a string
  */ 
  
  
  /*
   *  /guestbook/:id/delete
   *
   *  'guestbook' -> array('name' => '', 'value' => 'guestbook')
   *  ':id'       -> array('name' => 'id', 'value' => '')
   *
   */
  
  protected function tokenize($pattern)
  {
    $raw_tokens = explode('/', $pattern);
    $tokens = array();
    
    foreach ($raw_tokens as $raw_token) {
      $first_char = substr($raw_token, 0, 1);
      
      if(':' == $first_char) {
        $tokens[] = array('name' => substr($raw_token,1), 'value' => '');
      } else {
        $tokens[] = array('name' => '', 'value' => $raw_token);
      }
    }
    
    return $tokens;
  }

}
