<?php
namespace asbamboo\router\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\router\Route;
use asbamboo\router\RouteCollection;
use asbamboo\router\Router;
use asbamboo\http\ServerRequest;
use asbamboo\http\Response;
use asbamboo\http\Stream;
use asbamboo\router\RouteInterface;

/**
 * test 路由管理器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class RouterTest extends TestCase
{
    public function setUp()
    {
        $_SERVER['REQUEST_URI']   = '/path/param1/2/3/';
    }

    public function testGenerateUrl()
    {
        $id                 = 'test_id';
        $path               = '/path/{p1}/{p2}/{p3}/';
        $callback           = function($p1, $p2, $p3){
            $Body       = new Stream('php://temp', 'w+b');
            $Body->write('testRouter.');
            return new Response($Body);
        };
        $default_params     = ['p1' => '1', 'p2' => '2', 'p3' => '3'];
        $options            = ['custom' => true];
        $Route              = new Route($id, $path, $callback, $default_params, $options);
        $RouteCollection   = new RouteCollection();
        $RouteCollection->add($Route);

        $Router             = new Router($RouteCollection);
        $url                = $Router->generateUrl($id,  ['p1' => 'param1', 'query1' => 'query1', 'query2' => 'query2']);

        $this->assertEquals('/path/param1/2/3?query1=query1&query2=query2', $url);
        return $Router;
    }

    /**
     * @depends testGenerateUrl
     */
    public function testMatch(Router $Router)
    {
        $Request    = new ServerRequest();
        $Route      = $Router->match($Request);
        $this->assertInstanceOf(RouteInterface::class, $Route);
        return $Router;
    }

    /**
     * @depends testMatch
     */
    public function testGetCurrentMatchedRouteId(Router $Router)
    {
        $this->assertEquals("test_id", $Router->getCurrentMatchedRouteId());
    }

    /**
     * @depends testGenerateUrl
     */
    public function testCall(Router $Router)
    {
        $Request    = new ServerRequest();
        $Route      = $Router->match($Request);
        $Response   = $Router->call($Route, $Request);
        $this->assertEquals('testRouter.', $Response->getBody());
    }
}