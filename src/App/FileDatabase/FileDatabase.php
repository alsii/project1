<?php
namespace App/FileDatabase;

class FileDatabase inplements DatabaseInterface
{
    /**
     * @var \App\Kernel\Kernel
     * /
    private $kernel;
    
    /**
     * Save $entity into file
     * @param mixed $entity
     * /
    public function save($entity);
    {
        $filename = sha1(microtime());
        $filedata = serialize($entity);
        $class = get_class_name($entity);
        
        $filepath = $kernel->getBasePath() . 'Database/' . $class . '/'; 
    }
}
