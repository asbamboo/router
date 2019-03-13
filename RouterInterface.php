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
     * 使用路由id生成url (url不带scheme 和 host部分)
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
     * 使用路由id生成url (应该在generateUrl基础上，添加scheme和host)
     *
     * @return string
     */
    public function generateAbsoluteUrl(string $route_id, array $params = null) : string;

    /**
     * 通过 $Request 参数匹配一个 route
     *
     * @param ServerRequestInterface $request
     * @return RouteInterface
     * @throws \asbamboo\router\exception\NotFoundRouteException
     */
    public function match(ServerRequestInterface $request): RouteInterface;

    /**
     * 返回最后一次使用match方法匹配到的route的id
     *
     * @return string
     */
    public function getCurrentMatchedRouteId() : ?string;

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