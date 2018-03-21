<?php
namespace App\Kernel;

use PHPUnit\Framework\TestCase;
use Twig_Environment;

class KernelTest extends \PHPUnit\Framework\TestCase
{
    public function testRedirect()
    {
      $kernel = new Kernel();

      $response = $kernel->redirect('http://test.url');
      $this->assertInstanceOf('App\Kernel\Http\HtmlResponse', $response);
      $this->assertAttributeEquals(302, 'httpResponseCode', $response);
      $this->assertAttributeEquals(array('Location: http://test.url'), 'headers', $response);

      $response = $kernel->redirect('http://test.url', 308);
      $this->assertInstanceOf('App\Kernel\Http\HtmlResponse', $response);
      $this->assertAttributeEquals(308, 'httpResponseCode', $response);
      $this->assertAttributeEquals(array('Location: http://test.url'), 'headers', $response);

//      $this->setExpectedException(\Exception::class, 'Can not redirect with non-redirect code: 999');
      $this->expectException(\Exception::class, 'Can not redirect with non-redirect code: 999');
      $kernel->redirect('http://test.url', 999); 
    }
       
    public function testGetBasePath()
    {
        $kernel = new Kernel();

        $path = $kernel->getBasePath();
        $this->assertEquals(realpath(__DIR__.'/../../..'), $path);
    }
    
    public function testInit()
    {
        $kernel = new Kernel();
        $kernel->init();
        
        $kernelReflection = new \ReflectionClass('App\Kernel\Kernel');
        
        $templateEngine = $kernelReflection->getProperty('templateEngine');
        $templateEngine->setAccessible(true);
        $this->assertInstanceOf('Twig_Environment', $templateEngine->getValue($kernel));

        $config = $kernelReflection->getProperty('config');
        $config->setAccessible(true);
        $this->assertEquals($config->getValue($kernel), include($kernel->getBasePath() . '/Resources/config/config.php'));
    }
    
//    public function testLoadConfig()
//    {
//        $kernel = new Kernel();
//        $kernel->loadConfig();
//        
//        $kernelReflection = new \ReflectionClass('App\Kernel\Kernel');
//
//        $config = $kernelReflection->getProperty('config');
//        $config->setAccessible(true);
//        $this->assertEquals($config->getValue($kernel), include($kernel->getBasePath() . '/Resources/config/config.php'));
//    }
    
    public function testGetTemplateEngine()
    {
        $kernel = new Kernel();
        $templateEngine = $kernel->getTemplateEngine();
        
        $this->assertAttributeEquals($templateEngine, 'templateEngine', $kernel);
    }
}
