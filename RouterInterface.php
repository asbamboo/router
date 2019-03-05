<?php
namespace asbamboo\router;

use asbamboo\http\ServerRequestInterface;
use asbamboo\http\ResponseInterface;

/**
 * 路由管理器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
interface RouterInterface
{
    /**
     * 获取路由集合
     *
     * @return RouteCollectionInterface
     */
    public function getRouteCollection() : RouteCollectionInterface;

    /**
     * 使用路由id生成url
     * $params如果在url路径中[asbamboo\routerRouteInterface::getPath()]存在的参数配置，那么因该与路径上面相应的参数做替换。
     * $params如果不是在url路径中[asbamboo\routerRouteInterface::getPath()]存在的参数配置，那么$params做未query_string生成url。
     *
     * @param string $route_id 路由id[asbamboo\routerRouteInterface::getId()]
     * @param array $params 生成路由的参数
     *
     * @return string
     */
    public function generateUrl(string $route_id, array $params = null) : string;

    /**
     * 匹配一个request请求, 并且执行路由的callback方法后，返回一个Response信息。
     *
     * @deprecated 本方法将在2.0版本删除， 使用match+call方法替代
     * @param ServerRequestInterface $request
     */
    public function matchRequest(ServerRequestInterface $Request): ResponseInterface;

    /**
     * 通过 $Request 参数匹配一个 route
     *
     * @param ServerRequestInterface $request
     * @return RouteInterface
     * @throws \asbamboo\router\exception\NotFoundRouteException
     */
    public function match(ServerRequestInterface $request): RouteInterface;

    /**
     * 回调route
     *  - 应该通过 call_user_func_array($Route, $Request) 返回一个Response
     *
     * @param RouteInterface $route
     * @param ServerRequestInterface $Request
     * @return ResponseInterface
     */
    public function call(RouteInterface $Route, ServerRequestInterface $Request) : ResponseInterface;
}