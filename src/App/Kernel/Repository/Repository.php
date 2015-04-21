
<?php
namespace App\Kernel\Repository;

// Класс репозитория является базовым для всех репозиториев и реализует шаблон проектирования Singleton

class Repository
{
    private $instance;
    
    private function __construct() 
    {
    }
    
    private function __clone()
    {
    }
    
    public static function getInstance()
    {
        if(is_null(self::$instance) {
            self::$instance = new self();            
        }
        return self::$instance;
    }
}
