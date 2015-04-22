
<?php
namespace App\Kernel\Repository;

// Класс репозитория является базовым для всех репозиториев и реализует шаблон проектирования Singleton

class Repository
{
    private $instance;
    
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
        if(is_null(self::$instance) {
            self::$instance = new self();            
        }
        return self::$instance;
    }
}
