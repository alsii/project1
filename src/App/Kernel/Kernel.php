<?php
namespace App\Kernel;
use Twig_Loader_Filesystem;
use Twig_Environment;
use App\Kernel\Http\AbstractResponse;
use App\Kernel\Http\HtmlResponse;

class Kernel {

  private $templateEngine;
  private $config;

  public function init()
  {
    $this->initTemplateEngine();
    $this->loadConfig();
  }

  public function redirect($url, $httpResponseCode = 302)
  {
    if(!in_array($httpResponseCode, array(201, 301, 302, 303, 307, 308))) {
      throw new \Exception("Can not redirect with non-redirect code: $httpResponseCode");
    }

    $response = (new HtmlResponse())
      ->setHeaders(array(htmlspecialchars("Location: $url", ENT_QUOTES, 'UTF-8')))
      ->setHttpResponseCode($httpResponseCode)
    ;

    return $response;
  }

  public function render($template, $parameters = array(), $httpResponseCode = 200, $headers = array())
  {
    return $this->renderHtml($template, $parameters, $httpResponseCode, $headers);
  }

  public function renderHtml($template, $parameters = array(), $httpResponseCode = 200, $headers = array())
  {
    $response = (new HtmlResponse())
      ->setText($this->getTemplateEngine()->loadTemplate($template)->render($parameters))
      ->setHttpResponseCode($httpResponseCode)
      ->setHeaders($headers)
    ;
    return $response;
  }
  
  public function getBasePath()
  {
    // realpath() возвращает сокращенный путь без ".."
    // например realpath('/var/www/site1/src/App/Kernel/../../web/download') вернет '/var/www/site1/web/download'
    return realpath(__DIR__.'/../../..');
  }
  
  private function initTemplateEngine()
  {
    // Этот loader будет искать шаблоны там, где мы указали при его создании
    $loader = new Twig_Loader_Filesystem($this->getBasePath() . '/Resources/view');
    
    // создаем экземпляр шаблонизатора, передаем ему загрузчик шаблонов и массив параметров
    // пока там один важный параметр - место для кеша
    // сохраняем этот экземпляр в приватное свойство templateEngine
    $this->templateEngine = new Twig_Environment($loader, array(
        'cache' => $this->getBasePath() . '/cache/twig',
        'debug' => true,
    ));
  }
  
  private function loadConfig()
  {
    $this->config = include($this->getBasePath() . '/Resources/config/config.php');
  }
  
  // Возвращает экземпляр шаблонизатора. Вдруг нам он снаружи понадобится ;)
  public function getTemplateEngine()
  {
    return $this->templateEngine;
  }

  public function route($requestString) {
    $parser = new Parser($this->config['routing']);
    $route = $parser->parse($requestString);

    if($route) {
      $action = 'App\\Controller\\' . $route['controller'] . '::' . $route['action'];
      
      if(is_callable($action)) {
        $output = call_user_func($action, $this, $route['parameters']);

        if(! ($this->renderText($output) || $this->renderResponse($output))) {
          throw new \Exception("Unxepected return type " . gettype($output) . " from $action. Expected: string or App\\Kernel\\Response.");
        }
        
      } else {
        $this->renderResponse($this->render(
          'error.html.twig', array('message' => "Routing error!!! Controller or action '$action' is not exist."), 500));
      }
    } else {
      $this->renderResponse($this->render('error.html.twig', array('message' => 'Error 404. Page not found.', 404)));
    }
  }

  public function buildUrl($routeName, $parameters = array())
  {
    $parser = new Parser($this->config['routing']);
    $url = $parser->buildUrl($routeName, $parameters);
    return $url;
  }

  /**
   * Возвращает информацию о сущности из кoнфигурации (Resources/config/config.php)
   * Распознает передан ли в качестве параметра экземпляр сущности или строка, содержащая имя сущности
   */
  public function getEntityInfo($entity)
  {
    if(is_object($entity)) {
      $entity = get_class($entity);
    }
    $info = $this->config['entities'][$entity];
    $info['name'] = $entity;
    return $info;
  }
  
  public function getEntityRepository($entity)
  {
    $entityInfo = $this->getEntityInfo($entity);
    if(is_callable(array($entityInfo['repository'], 'getInstance'), false, $getInstance)) {
      $repo = call_user_func($getInstance);
      $repo
        ->setKernel($this)
        ->setEntityName($entityInfo['name']);
      return $repo;   
    }
  }
  
  private function renderText($text)
  {
    if(!is_string($text)) {
      return false;
    }
    echo $text;
    return true;
  }
  
  private function renderResponse($response)
  {
    if(! $response instanceof AbstractResponse) {
      return false;
    }
    $response->render();
    return true;
  }

}
