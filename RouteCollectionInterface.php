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
     * 通过request请求信息去匹配并且获取路由单元
     * 返回匹配结果
     *
     * @deprecated 将在2.0版本中删除，这个方法原本被Router::matchRequest方法调用，现在不在使用了。
     * @param ServerRequestInterface $request
     * @return MatchInterface
     */
    public function matchRequest(ServerRequestInterface $request) : MatchInterface;

    /**
     * 返回与客户端请求匹配的路由
     *  - 在执行matchRequest方法后getMatchedRoute有值，在此之前返回null
     *
     * @deprecated 将在2.0版本中删除，这个方法原本是为了asbamboo/framework中获取当前匹配到的route。现在修改为Router::matchRequest会返回匹配的route
     * @return RouteInterface
     */
    public function getMatchedRoute() : ?RouteInterface;

    /**
     * 获取唯一标识符为[$id]的路由单元是否存在于路由集合。
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id) : bool;
}