<?php
namespace App\Kernel;

use PHPUnit_Framework_TestCase;

class KernelTest extends PHPUnit_Framework_TestCase
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

      $this->setExpectedException('Exception', 'Can not redirect with non-redirect code: 999');
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
        
        $kernelReflection = new \ReflectionClass('Kernel');
        
        $templateEngine = $kernelReflection->getProperty('templateEngine');
        $templateEngine->setAccessible(true);
        $this->assertInstanceOf('Twig_Environment', $temlpateEngine->getValue());

        $templateEngine = $kernelReflection->getProperty('config');
        $templateEngine->setAccessible(true);
        $this->assertEqual($temlpateEngine->getValue(), include($kernel->getBasePath() . '/Resources/config/config.php'));
    }
}
