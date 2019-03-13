<?php
namespace asbamboo\router;

use asbamboo\http\ServerRequestInterface;
use asbamboo\http\ResponseInterface;
use asbamboo\router\exception\NotFoundRouteException;

/**
 * 路由管理器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class Router implements RouterInterface
{
    /**
     *
     * @var RouteCollectionInterface
     */
    private $RouteCollection;

    /**
     * 最后匹配的route id
     *
     * @var string
     */
    private $last_matched_route_id;

    /**
     *
     * @param RouteCollectionInterface $RouteCollection
     */
    public function __construct(RouteCollectionInterface $RouteCollection)
    {
        $this->RouteCollection    = $RouteCollection;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouterInterface::getRouteCollection()
     */
    public function getRouteCollection() : RouteCollectionInterface
    {
        return $this->RouteCollection;
    }

    /**
     * {@inheritDoc}
     * @see \asbamboo\router\RouterInterface::generateUrl()
     */
    public function generateUrl(string $route_id, array $params = null) : string
    {
        /*
         * 参数
         */
        $Route          = $this->RouteCollection->get($route_id);
        $path           = $Route->getPath();
        $default_params = (array)$Route->getDefaultParams();
        $params         = array_merge($default_params, (array)$params);

        /*
         * 将url路径中的变量替换
         */
        $explode_path   = explode('/', $path);
        foreach($explode_path AS $key => $item){
            $is_param   = false;
            $param_name = null;
            if(preg_match('@^\{(\w+)\}$@u', $item, $match)){
                $param_name = $match[1];
                $is_param   = true;
            }
            if(!$is_param){
                continue;
            }

            if(isset($params[$param_name])){
                $explode_path[$key] = $params[$param_name];
                unset($params[$param_name]);
                continue;
            }
        }
        $path           = implode('/', $explode_path)?:'/';

        /*
         * 生成url
         */
        $query_string   = http_build_query($params);
        $url            = $path . ( $query_string ? '?' . $query_string : '');

        /*
         * 返回
         */
        return $url;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouterInterface::generateAbsoluteUrl()
     */
    public function generateAbsoluteUrl(string $route_id, array $params = null) : string
    {
        $Route          = $this->RouteCollection->get($route_id);
        $scheme         = $Route->getScheme();
        $host           = $Route->getHost();
        $url            = implode('://', [$scheme, $host]) . $this->generateUrl($route_id, $params);
        return $url;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouterInterface::match()
     */
    public function match(ServerRequestInterface $Request): RouteInterface
    {
        $script_name    = $Request->getServerParams()['SCRIPT_NAME'] ?? "";
        $script_path    = dirname($script_name);
        $path           = $Request->getUri()->getPath();
        if($script_path != '/' && strpos($path, $script_name) === 0){
            $path   = substr($path, strlen($script_name));
        }else if($script_path != '/' && strpos($path, $script_path) === 0){
            $path   = substr($path, strlen($script_path));
        }

        foreach($this->RouteCollection->getIterator() AS $id => $Route){
            $test_ereg  = '@^' . preg_replace('@\{[^/]+\}@u', '[^/]+', $Route->getPath()) . '$@u';
            $path       = rtrim($path, '/');
            if(preg_match($test_ereg, $path)){
                $this->last_matched_route_id    = $Route->getId();
                return $Route;
            }
        }

        throw new NotFoundRouteException(sprintf("没有找到与路径[%s]匹配的路由", $path));
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouterInterface::getCurrentMatchedRouteId()
     */
    public function getCurrentMatchedRouteId() : ?string
    {
        return $this->last_matched_route_id;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouterInterface::call()
     */
    public function call(RouteInterface $Route, ServerRequestInterface $Request) : ResponseInterface
    {
        /*
         * 路由path中的参数
         */
        $param_parse_by_route_paths = [];
        $route_path                 = $Route->getPath();
        $request_path               = $Request->getUri()->getPath();
        $explode_route_path         = explode('/', $route_path);
        $explode_request_path       = explode('/', $request_path);
        foreach($explode_route_path AS $index => $item){
            if(preg_match('@^\{(\w+)\}$@u', $item, $match)){
                $param_name                                 = $match[1];
                $param_value                                = isset($explode_request_path[$index]) ? $explode_request_path[$index] : null;
                $param_parse_by_route_paths[$param_name]    = $param_value;
            }
        }

        /*
         * 默认的参数
         */
        $default_params = $Route->getDefaultParams();

        /*
         * 真实的用于路由的回调函数的参数
         */
        $callback       = $Route->getCallback();
        $call_params    = [];
        if(is_array($callback)){
            $r  = new \ReflectionMethod(implode('::', [get_class($callback[0]), $callback[1]]));
        }else{
            $r  = new \ReflectionFunction($callback);
        }
        $ref_params    = $r->getParameters();
        foreach($ref_params AS $ref_param){
            $n                  = $ref_param->getName();
            $v                  = $ref_param->isDefaultValueAvailable() ? $ref_param->getDefaultValue() : null;
            $v                  = isset( $default_params[$n] ) ? $default_params[$n] : $v;

            if(isset( $param_parse_by_route_paths[$n] )){
                $v                  = $param_parse_by_route_paths[$n];
            }else if($Request->getRequestParam($n) !== null){
                $v                  = $Request->getRequestParam($n);
            }

            $call_params[$n]    = is_string($v) ? urldecode($v) : $v;
        }

        /*
         * 执行路由对应的回调函数
         */
        return call_user_func_array($callback, $call_params);
    }
}