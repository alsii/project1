<?php
namespace App\Controller;
use App\Kerne\Kernel;
use App\Entity\Message;

class GuestbookController
{
  public function addAction($kernel, $param)
  {
    $msg = new Message;
    $msgRepo = $kernel->getEntityRepository($msg);
    
    $msg
        ->setDatetime(new \DateTime())
        ->setUser($_POST['user'])
        ->setText($_POST['text'])
    ;
    
    $msgRepo->save($msg);
      
    $kernel->render('guestbook.html.twig');
  }

  public function listAction($kernel, $param)
  {
    $kernel->render('guestbook.html.twig');
  }
  
  
  
  
}
