<?php
namespace App\Controller;

use App\Kernel\Kernel;

class ReqController
{
  public function printAction($kernel,$param)
    {
        $kernel->render('req.html.twig', array('message'=>$param['text']));
    }
}
          