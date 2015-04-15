<?php
namespace App\kernel;
use Twig_Loader_Filesystem;
use Twig_Environment;

class Kernel {

  private $templateEngine;

  public function init()
  {
    $this->initTemplateEngine();
  }

  public function render()
  {
    echo "Hello, world!";
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
    ));
  }
  
  // Возвращает экземпляр шаблонизатора. Вдруг нам он снаружи понадобится ;)
  private function getTemplateEngine()
  {
    return $this->templateEngine();
  }
}