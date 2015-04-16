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
    return __DIR__.'/../../..';
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
      //TODO Check if matched controller (class) and action (method) exist. If not render 'error.html.twig' template.
      //TODO Create 'error.html.twig' template.
      //TODO Use Reflection class instead of call_user_func.
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
}