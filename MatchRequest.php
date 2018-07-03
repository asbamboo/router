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
    public function __construct(RouteInterface $Route, ServerRequestInterface $Request)
    {
        $this->Route    = $Route;
        $this->Request  = $Request;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\MatchInterface::getRoute()
     */
    public function getRoute(): RouteInterface
    {
        return $this->Route;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\MatchInterface::execute()
     */
    public function execute(): ResponseInterface
    {
        /*
         * 路由path中的参数
         */
        $param_parse_by_route_paths = [];
        $route_path                 = $this->Route->getPath();
        $request_path               = $this->Request->getUri()->getPath();
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
        $default_params = $this->Route->getDefaultParams();

        /*
         * 真实的用于路由的回调函数的参数
         */
        $callback       = $this->Route->getCallback();
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
            }else if($this->Request->getRequestParam($n) !== null){
                $v                  = $this->Request->getRequestParam($n);
            }

            $call_params[$n]    = $v;
        }

        /*
         * 执行路由对应的回调函数
         */
        return call_user_func_array($callback, $call_params);
    }
}