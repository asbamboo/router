<?php
namespace asbamboo\router;

/**
 * 通过路由配置信息加载路由集合
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
interface LoaderInterface
{
    /**
     * 通过路由配置加载路由集合
     *
     * @param mixed $resource
     * @return RouteCollectionInterface
     */
    public function load($resource) : RouteCollectionInterface;


    /**
     * 返回当前的加载器是否支持[$resource]的解析。
     * 当isSupport返回false，仍然调用load方法时，load方法应该抛出异常
     *
     * @param mixed $resource 一个资源
     *
     * @return bool 如果该类支持给定的资源，否者为false。
     */
    public function isSupport($resource) : bool;
}