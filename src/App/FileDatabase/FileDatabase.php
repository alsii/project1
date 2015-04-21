<?php
namespace App\FileDatabase;
use App\Kernel\DatabaseInterface

class FileDatabase inplements DatabaseInterface
{
    /**
     * @var \App\Kernel\Kernel
     */
    private $kernel;
    
    /**
     * Injects Kernel
     * @param \App\Kernel\Kernel $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }
    
    /**
     * Save $entity into file
     * @param mixed $entity
     */
    public function save($entity);
    {
        $filename = sha1(microtime());
        $filedata = serialize($entity);
        $class = get_class_name($entity);
        $filepath = $this->kernel->getBasePath() . '/Database/' . $class . '/';
        
        $fp = fopen($filepath . $filename, 'w');
        fwrite($fp, $filedata);
        fclose($fp);
    }
}
