<?php
namespace asbamboo\router\_test;

use PHPUnit\Framework\TestCase;
use asbamboo\router\Route;

/**
 * test
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class RouteTest extends TestCase
{
    public function getMainData()
    {
        yield ['id' => 'test_id', 'path' => '/', 'callback' => function($p1){}, 'default_params' => ['a'=>11], 'options' => null ];
    }

    /**
     * @dataProvider getMainData
     */
    public function testMain($id, $path, $callback, $default_params, $options)
    {
        $Route  = new Route($id, $path, $callback, $default_params, $options);

        $this->assertEquals($id, $Route->getId());
        $this->assertEquals(rtrim($path, '/'), $Route->getPath());
        $this->assertEquals($callback, $Route->getCallback());
        $this->assertEquals($default_params, $Route->getDefaultParams());
        $this->assertEquals($options, $Route->getOptions());
    }
}