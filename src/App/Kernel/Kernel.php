<?php
namespace App\Kernel;
use Twig_Loader_Filesystem;
use Twig_Environment;

class Kernel {

  private $templateEngine;
  private $config;

  public function init()
  {
    $this->initTemplateEngine();
    $this->loadConfig();
  }

  public function render($template, $parameters = array())
  {
    $twig = $this->getTemplateEngine();
    $template = $twig->loadTemplate($template);
    echo $template->render($parameters);
  }
  
  public function getBasePath()
  {
    // realpath() возвращает сокращенный путь без ".."
    // например realpath('/var/www/site1/src/App/Kernel/../../web/download') вернет '/var/www/site1/web/download'
    return realpath(__DIR__.'/../../..');
  }
  
  private function initTemplateEngine()
  {
    // Это loader будет искать шаблоны там, где мы указали при его создании
    $loader = new Twig_Loader_Filesystem($this->getBasePath() . '/Resources/view');
    
    // создаем экземпляр шаблонизатора, передаем ему загрузчик шаблонов и массив параметров
    // пока там один параметр - место для кеша
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
  private function getTemplateEngine()
  {
    return $this->templateEngine;
  }

  public function route($requestString) {
    $parser = new Parser($this->config['routing']);
    $route = $parser->parse($requestString);
    if($route) {
      $action = 'App\\Controller\\' . $route['controller'] . '::' . $route['action'];
      if(is_callable($action)) {
        call_user_func($action, $this, $route['parameters']);
      }else {
       $this->render('error.html.twig', array('message' => "Routing error!!! Controller or action '$action' is not exist."));
      }
    } else {
      $this->render('error.html.twig', array('message' => 'Error 404. Page not found.'));
    }
  }

  // возвращает информацию о сущности из кoнфигурации (Resources/config/config.php)
  // распознает передан ли в качестве параметра экземпляр сущности или строка, содержащая имя сущности
  public function getEntityInfo($entity)
  {
    if(is_object($entity)) {
      return $this->config['entities'][get_class($entity)];
    } elseif (is_string($entity)) {
      return $this->config['entities'][$entity];
    }
  }
  
  public function getEntityRepository($entity)
  {
    $getInstance = $this->getEntityInfo($entity)['repository'] . '::getInstance';
    var_dump($getInstance);
    if(is_callable($getInstance)) {
      return call_user_func($getInstance)->setKernel($this);
    }
  }
}
