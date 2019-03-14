<?php
namespace asbamboo\router;

/**
 * 一个路由单元[route]
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
interface RouteInterface
{
    /**
     * uri scheme
     *
     * @see https://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml
     */
    public function getScheme() : string;

    /**
     * url host 端口号也应该一起被返回
     */
    public function getHost() : string;

    /**
     * 路由的唯一标识符
     * @return string
     */
    public function getId() : string;

    /**
     * 路由的请求路径
     * 路径中大括号包起来的部分表示参数 如[/path/{params1}/{param2}/path]
     * 其中参数名只能由字母+数字+下划线组成，参数名第一个字符不能是数字.
     *
     * @return
     */
    public function getPath() : string;

    /**
     * 路由的执行方法
     * @return callable
     */
    public function getCallback() : callable;

    /**
     * 路由的执行方法默认的参数
     *
     * @return array|NULL 数组的键值[key]是参数的名称，数组的值[value]是参数的值
     */
    public function getDefaultParams() : ? array;

    /**
     * 路由可选的一些选项信息，根据实际需要配置。
     *
     * @return array|NULL
     */
    public function getOptions() : ? array;
}