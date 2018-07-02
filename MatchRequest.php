<?php
namespace asbamboo\router;

use asbamboo\http\ResponseInterface;
use asbamboo\http\ServerRequestInterface;

/**
 * 通过服务端收到的http请求，匹配路由集合。
 *  - 可以获取当前匹配到的路由
 *  - 可以执行当前匹配，得到一个http response结果
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月2日
 */
class MatchRequest implements MatchInterface
{
    /**
     *
     * @var RouteCollectionInterface
     */
    private $RouteCollection;

    /**
     *
     * @var ServerRequestInterface
     */
    private $Request;

    /**
     *
     * @var RouteInterface
     */
    private $Route;

    /**
     *
     * @param RouteCollectionInterface $routeCollection
     * @param ServerRequestInterface $request
     */
    public function __construct(RouteCollectionInterface $RouteCollection, ServerRequestInterface $Request)
    {
        $this->RouteCollection  = $RouteCollection;
        $this->Request          = $Request;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\MatchInterface::getRoute()
     */
    public function getRoute(): RouteInterface
    {
        if(!$this->Route){
            $path               = $this->Request->getUri()->getPath();
            $this->RouteCollection->getByPath($path);
        }
        return $this->Route;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\MatchInterface::execute()
     */
    public function execute(...$params): ResponseInterface
    {
        $callback       = $this->Route->getCallback();
        $default_params = $this->Route->getDefaultParams();
        if(is_array($callback)){
            $r  = new \ReflectionMethod(implode('::', [get_class($callback[0]), $callback[1]]));
        }else{
            $r  = new \ReflectionFunction($callback);
        }
        $call_params    = $r->getParameters();
    }
}