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
    
   /**
    * @dataProvider parseProvider
    */
    public function testParse($pattern, $result)
    {
        $parser = new Parser(self::ROUTES);
        $r = $parser->parse($pattern);
        $this->assertEquals($r, $result);
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
}
/*
        ['name' => 'order-remove', 'pattern' => '/order/:id/remove', 'controller' => 'Order', 'action' => 'remove'],
        ['name' => 'order-item-list', 'pattern' => '/order/:id/item', 'controller' => 'Order', 'action' => 'listItem'],
        ['name' => 'order-item-show', 'pattern' => '/order/:order-id/item/:item-id', 'controller' => 'Order', 'action' => 'showItem'],
*/

