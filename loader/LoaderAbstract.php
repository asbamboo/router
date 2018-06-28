<?php
namespace asbamboo\router\loader;

use asbamboo\router\LoaderInterface;
use asbamboo\router\RouteCollectionInterface;
use asbamboo\router\exception\NotSupportException;

/**
 * 路由加载器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
abstract class LoaderAbstract implements LoaderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\LoaderInterface::load()
     */
    public function load($resource): RouteCollectionInterface
    {
        if(!$this->isSupport($resource)){
            throw new NotSupportException(sprintf('正在使用的路由加载器不支持[%s]', var_export($resource, true)));
        }

        return $this->parse($resource);
    }

    /**
     * 解析resource并且返回路由集合
     *
     * @param mixed $resource
     * @return RouteCollectionInterface
     */
    abstract public function parse($resource) : RouteCollectionInterface;
}
