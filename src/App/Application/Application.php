<?php
namespace App\Application;
use App\Kernel\Kernel;

class Application
{
    private $kernel;

    public function run()
    {
        $this->kernel = new Kernel();
        $this->kernel->init();
//	$this->kernel->render('index.html.twig', array('name' => 'Anna'));
//        echo "<code>";
//        var_dump($_SERVER);
//        echo "</code>";
//        die();
        $this->kernel->route(isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'/');
    }
}
