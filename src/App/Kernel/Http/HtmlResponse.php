<?php
namespace App\Kernel\Http;

class HtmlResponse extends AbstractResponse
{
  public function render()
  {
    http_response_code($this->getHttpResponseCode());
    
    header('Content-Type: text/HTML');
    foreach($this->getHeaders() as $header) {
      header($header);
    }
    
    echo $this->getText();
  }
}