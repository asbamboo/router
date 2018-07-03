<?php
namespace asbamboo\router\loader;

use asbamboo\router\RouteCollectionInterface;
use asbamboo\router\RouteCollection;
use asbamboo\router\Route;

/**
 * 通过array加载路由集合
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
class LoaderByArray extends LoaderAbstract
{
    /**
     * 解析资源 生成路由集合
     *
     * @param array $resource
     * @return RouteCollectionInterface
     */
    public function parse(/*array*/ $resource): RouteCollectionInterface
    {
        $RouteCollection    = new RouteCollection();
        foreach($resource AS $item){
            
            // 如果路由是一个calss的method，那么路由可以定义成 class_name:method_name
            if(is_string( $item['callback'] )){
                $callback           = explode(':', $item['callback']);
                $item['callback']   = [new $callback[0], $callback[1]];
            }
            
            $Route  = new Route($item['id'], $item['path'], $item['callback'], $item['default_params'] ?? null, $item['options'] ?? null);
            $RouteCollection->add($Route);
        }
        return $RouteCollection;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\LoaderInterface::isSupport()
     */
    public function isSupport($resource): bool
    {
        if(!is_array($resource)){
            return false;
        }
        foreach($resource AS $item){
            if(!isset($item['id']) || !is_string($item['id'])){
                return false;
            }
            if(!isset($item['path']) || !is_string($item['path'])){
                return false;
            }
            if(!isset($item['callback']) || !is_callable($item['callback'])){
                return false;
            }
        }
        return true;
    }
}
