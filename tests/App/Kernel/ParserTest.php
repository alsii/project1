<?php
namespace App\Kernel;

use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
   /**
    * @dataProvider tokenizeProvider
    */
    public function testTokenize($data)
    {
        $method = (new \ReflectionClass(Parser::class))->getMethod('tokenize');
        $method->setAccessible(true);
           
        $parser = new Parser([]);
        $pattern = implode('/', array_keys($data));
        $result = $method->invokeArgs($parser, [$pattern]);

        $this->assertEquals(count($data), count($result));

        foreach ($data as $token => $type) {
            $rvalue = current($result);
            if ($type === 'value') {
                $this->assertEquals($rvalue['value'], $token);
                $this->assertEmpty($rvalue['name']);
            } else {
                $this->assertEquals($rvalue['name'], substr($token, 1));
                $this->assertEmpty($rvalue['value']);
            }

            next($result);
        }
    }
    
    public function tokenizeProvider()
    {
        return [
            [['admin' => 'value', 'dashboard' => 'value']],
            [['admin' => 'value', ':period' => 'name']],
            [['admin' => 'value', 'dashboard' => 'value', ':period' => 'name']],
            [['order' => 'value', ':id' => 'name', 'delete' => 'value']],
            [['order' => 'value', ':id' => 'name', ':action' => 'name']],
        ];
    }
}
