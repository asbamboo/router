版本 1.0.* 修改记录
=====================

* 1.0.1 (2018-10-03 ~ 现在 )

1. RouteCollection matchRequest方法中路径的可变变量的正则匹配从 \{\w+\} 修改为\{[^/]+\}
2. MatchRequest 中传递给controller的值 做一次urldecode。