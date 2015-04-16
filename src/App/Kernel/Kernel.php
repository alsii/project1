<?php
namespace App\kernel;
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

  public function render()
  {
    $twig = $this->getTemplateEngine();
    $template = $twig->loadTemplate('index.html.twig');
    echo $template->render(array('name' => 'Anna'));
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
    $this->config = include($this->getBasePath . 'Resources/config/config.php');
  }
  
  // Возвращает экземпляр шаблонизатора. Вдруг нам он снаружи понадобится ;)
  private function getTemplateEngine()
  {
    return $this->templateEngine;
  }

  private function route($requestString) {
    $parser = new Parser($this->config['routing']);
    $route = $parser->parse($_SERVER['PATH_INFO']);
    call_user_func(array('App/Controller/' . $route['constructor'] . '::' . $route['action'], $route['parameters']));
  }
}