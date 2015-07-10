<?php
require __DIR__.'/../vendor/autoload.php';
use App\Kernel\Kernel;

$kernel = new Kernel();
$kernel->init();
$kernel->route(isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'/');
                        