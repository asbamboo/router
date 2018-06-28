<?php
namespace asbamboo\router\_test\loader;

use asbamboo\router\RouteCollection;
use PHPUnit\Framework\TestCase;
use asbamboo\router\exception\NotSupportException;
use asbamboo\router\loader\LoaderByArray;

/**
 * test 通过array加载路由集合
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class LoaderByArrayTest extends TestCase
{
    public function testLoadException()
    {
        $this->expectException(NotSupportException::class);
        $resource   = __CLASS__;
        $Loader     = new LoaderByArray();
        $Loader->load($resource);
    }

    public function testLoad()
    {
        $resource           = [
            ['id' => 'test_id', 'path' => '/', 'callback' => function(){}, 'default_params' => null, 'options' => null ],
        ];
        $Loader             = new LoaderByArray();
        $RouteCollection    = $Loader->load($resource);
        $this->assertInstanceOf(RouteCollection::class, $RouteCollection);
    }
}
