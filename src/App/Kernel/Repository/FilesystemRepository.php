<?php
namespace App\Kernel\Repository;

class FilesystemRepository extends Repository
{
    /**
     * Save $entity into file
     * @param mixed $entity
     */
    public function save($entity)
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
