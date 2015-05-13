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
//        $class = str_replace('\\', DIRECTORY_SEPARATOR, get_class($entity));
//        $filepath = $this->kernel->getBasePath() . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . $class;
        $filepath = $this->getEntityDirectory();
//        echo $filepath;
        if(!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $fp = fopen($filepath . DIRECTORY_SEPARATOR . $filename, 'w');
        fwrite($fp, $filedata);
        fclose($fp);
    }
    
    public function getAll()
    {
        $entityDirectory = $this->getEntityDirectory();        
        if(!$dir = opendir($entityDirectory)) {
            throw new \Exception("Directory '{$entityDirectory}' for entity '{$this->getEntityName()}' does not exist.");
        };
    
        $entities = array();
        while(false !== ($fileName = readdir($dir))) {
            $fileName = $entityDirectory . DIRECTORY_SEPARATOR . $fileName;
            if(is_file($fileName)) {
                $data = file_get_contents($fileName);
                $entities[] = unserialize($data);
            }
        }
        
        return $entities;
    }
    
    protected function getEntityDirectory()
    {
        $directoryName = str_replace('\\', DIRECTORY_SEPARATOR, $this->getEntityName());
        $filepath = $this->kernel->getBasePath() . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . $directoryName;
        return $filepath;
    }
}
