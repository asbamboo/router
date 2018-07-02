<?php
namespace asbamboo\router;

use asbamboo\http\ResponseInterface;

/**
 * 匹配到的路由结果
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月2日
 */
interface MatchInterface
{
    /**
     * 获取路由
     *
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface;

    /**
     * 执行匹配到route的callback
     *
     * @param mixed ...$params callback方法接受的参数
     * @return ResponseInterface
     */
    public function execute(...$params): ResponseInterface;
}

