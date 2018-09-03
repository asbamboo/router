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
class Router implements RouterInterface
{
    /**
     *
     * @var RouteCollectionInterface
     */
    private $RouteCollection;

    /**
     *
     * @param RouteCollectionInterface $RouteCollection
     */
    public function __construct(RouteCollectionInterface $RouteCollection)
    {
        $this->RouteCollection    = $RouteCollection;
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
     * @see \asbamboo\router\RouterInterface::matchRequest()
     */
    public function matchRequest(ServerRequestInterface $request): ResponseInterface
    {
        $matchRequest   = $this->RouteCollection->matchRequest($request);
        return $matchRequest->execute();
    }
}