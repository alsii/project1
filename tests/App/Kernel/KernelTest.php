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
      
    }

}