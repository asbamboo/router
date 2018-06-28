<?php
namespace asbamboo\router;

use asbamboo\router\exception\NotFoundRouteException;

class RouteCollection implements RouteCollectionInterface
{
    private $routes = [];

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
    public function getByPath(string $path) : RouteInterface
    {
        $id = $this->matchPath($path);
        if($id === null){
            throw new NotFoundRouteException(sprintf("没有找到与路径[%s]匹配的路由", $path));
        }

        return $this->routes[$id];
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

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteCollectionInterface::hasByPath()
     */
    public function hasByPath(string $path) : bool
    {
        return $this->matchPath($path) !== null;
    }

    /**
     * 传入请求路径，通过正则匹配路由的id
     *
     * @param string $path
     * @return string|NULL
     */
    private function matchPath(string $path) : ?string
    {
        foreach($this->routes AS $id => $Route){
            $test_ereg  = '@^' . preg_replace('@{\w+}@u', '\w+', $Route->getPath()) . '$@u';
            $path       = rtrim($path, '/');
            if(preg_match($test_ereg, $path)){
                return $id;
            }
        }
        return null;
    }
}