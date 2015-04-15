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
	$this->kernel->render();
    }
}
