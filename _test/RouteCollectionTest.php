<?php
namespace asbamboo\router\test;

use asbamboo\router\exception\NotFoundRouteException;
use PHPUnit\Framework\TestCase;
use asbamboo\router\RouteCollection;
use asbamboo\router\Route;
use asbamboo\http\ServerRequest;
/**
 * test RouterCollection
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class RouteCollectionTest extends TestCase
{
    public function testGetNotFoundException()
    {
        $this->expectException(NotFoundRouteException::class);
        $RouterCollection   = new RouteCollection();
        $RouterCollection->get('route_id');
    }

    public function testMain()
    {
        $id                 = 'test_id';
        $path               = '/path/{p1}/{p2}/{p3}/';
        $callback           = function($p1, $p2, $p3){};
        $default_params     = ['p1' => '1', 'p2' => '2', 'p3' => '3'];
        $options            = ['custom' => true];
        $Route              = new Route($id, $path, $callback, $default_params, $options);
        $RouterCollection   = new RouteCollection();
        $RouterCollection->add($Route);

        $this->assertCount(1, $RouterCollection->getIterator());
        $this->assertEquals(1, $RouterCollection->count());
        $this->assertEquals($Route, $RouterCollection->get($id));
    }
}