<?php
require __DIR__.'/../vendor/autoload.php';

$kernel = new \App\Kernel\Kernel();

echo "Privet! \n";
$kernel->init();
