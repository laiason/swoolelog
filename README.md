# swoolelog
swoolelog是基于swoole的日志框架。使用swoole的异步任务task记录日志，将接受的日志数据插入mongodb中。这只是一个简单的示例，读者可以对齐进行扩展，比如日志表的分类和日志查询客户端。

# 环境依赖

* 安装php7
* 安装mongodb、php-mongodb扩展
* 安装swoole（版本：2.2.0）


# 如何使用swoolelog

1. 启动swoolelog服务

    php server.php

2. 发送日志数据

    php test.php

3. 查看日志数据

    php client.php

