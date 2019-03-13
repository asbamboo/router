<?php
namespace asbamboo\router;

use asbamboo\http\ServerRequestInterface;

/**
 * 路由单元的集合[RouterCollection]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
interface RouteCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * 添加一个路由单元到路由集合
     *
     * @param RouteInterface $route
     *
     * @return self
     */
    public function add(RouteInterface $Route) : self;

    /**
     * 获取唯一标识符为[$id]的路由单元
     *
     * @param string $id
     * @return RouteInterface
     */
    public function get(string $id) : RouteInterface;

    /**
     * 获取唯一标识符为[$id]的路由单元是否存在于路由集合。
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id) : bool;
}