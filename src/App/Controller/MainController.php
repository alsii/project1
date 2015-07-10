<?php
namespace App\Controller;

use App\Kerne\Kernel;

class MainController
{

  public function indexAction($kernel, $param)
  {
    return $kernel->render('index.html.twig');
  }
  
  public function betonAction($kernel, $param)
  {
    return $kernel->render('beton.html.twig');
  }
  
  public function contactsAction($kernel, $param)
  {
    return $kernel->render('contacts.html.twig');
  }
  
  public function aboutAction($kernel, $param)
  {
    return $kernel->render('about.html.twig');
  }

  public function priceAction($kernel, $param)
  {
    return $kernel->render('price.html.twig');
  }
}