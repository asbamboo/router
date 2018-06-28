<?php
namespace asbamboo\router\exception;

/**
 * 当一个加载器[loader]通过不能被支持的[$resource]调用加载方法[load]抛出这个异常 [asbamboo\router\LoderInterface::load]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月14日
 */
class NotSupportException extends \InvalidArgumentException implements RouterExceptionInterface
{

}