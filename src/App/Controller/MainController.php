<?php
namespace App\Controller;

use App\Kerne\Kernel;

class MainController
{

  public function indexAction($kernel, $param)
  {
    $kernel->render('index.html.twig', array('name' => 'Anna'));
  }
  
}