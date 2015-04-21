<?php
namespace App\Controller;
use App\Kerne\Kernel;

class GuestbookController
{
  public function addAction($kernel, $param)
  {
    $kernel->render('guestbook.html.twig');
  }
  
}
