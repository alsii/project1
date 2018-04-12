<?php
namespace App\Kernel;

use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    const ROUTES = [
        ['name' => 'test1', 'pattern' => '/test1', 'controller' => 'Test', 'action' => 'test1'],
        ['name' => 'test2', 'pattern' => '/test2', 'controller' => 'Test', 'action' => 'test2'],
        ['name' => 'order-list', 'pattern' => '/order', 'controller' => 'Order', 'action' => 'list'],
        ['name' => 'order-new', 'pattern' => '/order/new', 'controller' => 'Order', 'action' => 'create'],
        ['name' => 'order-show', 'pattern' => '/order/:id', 'controller' => 'Order', 'action' => 'show'],
        ['name' => 'order-edit', 'pattern' => '/order/:id/edit', 'controller' => 'Order', 'action' => 'edit'],
        ['name' => 'order-remove', 'pattern' => '/order/:id/remove', 'controller' => 'Order', 'action' => 'remove'],
        ['name' => 'order-item-list', 'pattern' => '/order/:id/item', 'controller' => 'Order', 'action' => 'listItem'],
        ['name' => 'order-item-show', 'pattern' => '/order/:order-id/item/:item-id', 'controller' => 'Order', 'action' => 'showItem'],
    ];

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

        reset($result);
        foreach ($data as $token => $type) {
            $rvalue = current($result);

            $this->assertEquals($type, $rvalue['type']);
            $this->assertEquals($type === Parser::TOKEN_PATH ? $token : substr($token, 1), $rvalue['value']);

            next($result);
        }
    }
    
    public function tokenizeProvider()
    {
        return [
            [['admin' => Parser::TOKEN_PATH, 'dashboard' => Parser::TOKEN_PATH]],
            [['admin' => Parser::TOKEN_PATH, ':period' => Parser::TOKEN_PARAMETER]],
            [['admin' => Parser::TOKEN_PATH, 'dashboard' => Parser::TOKEN_PATH, ':period' => Parser::TOKEN_PARAMETER]],
            [['order' => Parser::TOKEN_PATH, ':id' => Parser::TOKEN_PARAMETER, 'delete' => Parser::TOKEN_PATH]],
            [['order' => Parser::TOKEN_PATH, ':id' => Parser::TOKEN_PARAMETER, ':action' => Parser::TOKEN_PARAMETER]],
        ];
    }
    
   /**
    * @dataProvider parseProvider
    */
    public function testParse($pattern, $result)
    {
        $parser = new Parser(self::ROUTES);
        $r = $parser->parse($pattern);
        $this->assertEquals($result, $r);
    }
    
    public function parseProvider()
    {
        return [
            ['/test', false],
            ['/test1', ['controller' => 'TestController', 'action' => 'test1Action', 'parameters' =>[]]],
            ['/test2', ['controller' => 'TestController', 'action' => 'test2Action', 'parameters' =>[]]],
            ['/order', ['controller' => 'OrderController', 'action' => 'listAction', 'parameters' =>[]]],
            ['/order/new', ['controller' => 'OrderController', 'action' => 'createAction', 'parameters' =>[]]],
            ['/order/42', ['controller' => 'OrderController', 'action' => 'showAction', 'parameters' =>['id' => 42]]],
            ['/order/42/remove', ['controller' => 'OrderController', 'action' => 'removeAction', 'parameters' =>['id' => 42]]],
            ['/order/42/item', ['controller' => 'OrderController', 'action' => 'listItemAction', 'parameters' =>['id' => 42]]],
            ['/order/42/item-list', false],
            ['/order/42/item/21', ['controller' => 'OrderController', 'action' => 'showItemAction', 'parameters' =>['order-id' => 42, 'item-id' => 21]]],
            ['/order/42/item/21/delete', false],
        ];
    }
    
   /**
    * @dataProvider buildUrlProvider
    */
    public function testBuildUrl($routeName, $parameters, $url)
    {
        $parser = new Parser(self::ROUTES);
        if ($url === false) {
            $this->expectException(\Exception::class);
            $parser->buildUrl($routeName, $parameters);
        } else {
            $r = $parser->buildUrl($routeName, $parameters);
            $this->assertEquals($url, $r);
        }
    }
    
    public function buildUrlProvider()
    {
        return [
            ['test1', [], '/test1'],
            ['test2', [], '/test2'],
            ['order-list', [], '/order'],
            ['order-new', [], '/order/new'],
            ['order-show', ['id' => 42], '/order/42'],
            ['order-show', [], false],
            ['order-edit', ['id' => 42], '/order/42/edit'],
            ['order-edit', ['order-id' => 42], false],
            ['order-remove', ['id' => 42], '/order/42/remove'],
            ['order-item-list', ['id' => 42], '/order/42/item'],
            ['order-item-show', ['order-id' => 42, 'item-id' => 21], '/order/42/item/21'],
            ['order-item-show', ['order-id' => 42, 'id' => 21], false],
            ['order-item-show', ['order-id' => 42], false],
            ['order-item-show', ['order-id' => 43], true],
        ];
    }
}

