<?php
namespace asbamboo\router\exception;

/**
 * 当一个路由单元在路由集合中找不到时抛出异常
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月14日
 */
class NotFoundRouteException extends \InvalidArgumentException implements RouterExceptionInterface
{

}