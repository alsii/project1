<?php
namespace App\Kernel\Http;

use PHPUnit_Framework_TestCase;

class HtmlResponseTest extends PHPUnit_Framework_TestCase
{
    public function testMutators()
    {
      $response = new HtmlResponse();
      $text = 'text';
      $response->setText($text);
      $this->assertAttributeEquals($text, 'text', $response);
      
      $text2 = $response->getText();
      $this->assertEquals($text, $text2);

      $this->assertAttributeEquals(200, 'httpResponseCode', $response);

      $code2 = $response->getHttpResponseCode();
      $this->assertEquals(200, $code2);

      $code = 500;
      $response->setHttpResponseCode($code);
      $this->assertAttributeEquals($code, 'httpResponseCode', $response);

      $code2 = $response->getHttpResponseCode();
      $this->assertEquals($code, $code2);

      $headers = ['Content-Type: text/html'];
      $response->setHeaders($headers);
      $this->assertAttributeEquals($headers, 'headers', $response);
      
      $headers2 = $response->getHeaders();
      $this->assertEquals($headers, $headers2);
    }
}