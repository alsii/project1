<?php
namespace App\Kernel\Http;

abstract class AbstractResponse
{
  protected $text = '';
  protected $httpResponseCode = 200;
  protected $headers = array();
  
  public function setText($text)
  {
    $this->text = $text;
    return $this;
  }
  
  public function getText()
  {
    return $this->text;
  }

  public function setHttpResponseCode($httpResponseCode)
  {
    $this->httpResponseCode = $httpResponseCode;
    return $this;
  }
  
  public function getHttpResponSeCode()
  {
    return $this->httpResponseCode;
  }

  public function setHeaders($headers)
  {
    $this->headers = $headers;
    return $this;
  }
  
  public function getHeaders()
  {
    return $this->headers;
  }
  
  abstract public function render();
  
}