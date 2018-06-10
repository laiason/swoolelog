<?php
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/mongodb.php');

// 查询日志数据
$mongodb = new mongodb('log');
$result = $mongodb->query('test');
var_dump($result);