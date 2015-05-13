<?php
namespace App\Kernel\Repository;

// Класс репозитория является базовым для всех репозиториев и реализует шаблон проектирования Singleton

class Repository
{
    private static $instance;

    /**
     * @var string
     */
    private $entityName;
     
    /**
     * @var \App\Kernel\Kernel
     */
    protected $kernel;
    
    /**
     * Внедряет Kernel
     * @param \App\Kernel\Kernel $kernel
     */
    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }

    private function __construct() 
    {
    }
    
    private function __clone()
    {
    }
    
    /**
     * Реализует паттерн Singleton
     * @return $this
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new static;            
        }
        return self::$instance;
    }
    
    /**
     * Привязывает репозиторий к управляемой им сущности
     * @param string $entityName
     * @return $this
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * Возвращает имя управляемой репозиторием сущности
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Создает и возвращает экземпляр управляемой сущности
     */    
    public function createEntity() {
        return new $this->entityName;
    }
}
