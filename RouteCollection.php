<?php
namespace asbamboo\router;

use asbamboo\router\exception\NotFoundRouteException;
use asbamboo\http\ServerRequestInterface;

/**
 * 路由集合
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月3日
 */
class RouteCollection implements RouteCollectionInterface
{
    /**
     * 路由集合
     *
     * @var array
     */
    private $routes         = [];

    /**
     * 当前匹配到的路由
     *
     * @var Route
     */
    private $MatchedRoute   = null;

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteCollectionInterface::add()
     */
    public function add(RouteInterface $Route) : RouteCollectionInterface
    {
        $this->routes[$Route->getId()]  = $Route;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteCollectionInterface::getByPath()
     */
    public function matchRequest(ServerRequestInterface $Request) : MatchInterface
    {
        $path           = $Request->getUri()->getPath();
        foreach($this->routes AS $id => $Route){
            $test_ereg  = '@^' . preg_replace('@\{\w+\}@u', '\w+', $Route->getPath()) . '$@u';
            $path       = rtrim($path, '/');
            if(preg_match($test_ereg, $path)){
                $this->MatchedRoute = $Route;
            }
        }

        if($this->MatchedRoute === null){
            throw new NotFoundRouteException(sprintf("没有找到与路径[%s]匹配的路由", $path));
        }

        return new MatchRequest($this->MatchedRoute, $Request);
    }

    /**
     * 返回匹配到的路由
     *  - 当返回结果是null的时候，可能是应为没有执行matchRequest方法
     *
     * @return \asbamboo\router\Route
     */
    public function getMatchedRoute()
    {
        return $this->MatchedRoute;
    }

    /**
     * {@inheritDoc}
     * @see \asbamboo\router\RouteCollectionInterface::get()
     */
    public function get(string $id): RouteInterface
    {
        if(!$this->has($id)){
            throw new NotFoundRouteException(sprintf("没有找到与唯一标识符是[%s]的路由", $id));
        }
        return $this->routes[$id];
    }

    /**
     *
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count()
    {
        return count($this->routes);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteCollectionInterface::has()
     */
    public function has(string $id): bool
    {
        return isset($this->routes[$id]);
    }
}