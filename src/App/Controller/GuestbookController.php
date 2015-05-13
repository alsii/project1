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
    if(!$msgRepo) {
        throw new \Exception('Repository for ' . get_class($msg) . ' entity not found.');
    }
    
    $msg
        ->setDatetime(new \DateTime())
        ->setUser($_POST['user'])
        ->setText($_POST['message'])
    ;
    
    $msgRepo->save($msg);
      
    $kernel->render('guestbook.html.twig');
  }

  public function listAction($kernel, $param)
  {
    $messageRepo = $kernel->getEntityRepository("App\\Entity\\Message");
    $messages = $messageRepo->getAll();
    $kernel->render('guestbook.html.twig', array('messages' => $messages, 'error' => 'none'));
  }
  
}
