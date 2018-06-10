<?php

define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/mongodb.php');

// 设置http服务器，监听80端口
$http = new swoole_http_server("0.0.0.0", 80);

// 设置worker_num和task_worker_num
$http->set([
    'worker_num' => 2,
    'task_worker_num' => 2,
]);

// 监听http请求
$http->on('request', function ($request, $response) use ($http) {
    //请求过滤
    if($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico'){
        return $response->end();
    }   

    try{
        $raw_content = $request->rawContent();
        if (empty($raw_content)) {
            throw new Exception('数据为空');
        }
        $data = json_decode($raw_content, true);

        // 将日志数据添加到task任务中心，进行异步处理
        $http->task($data);

        // 返回
        $response->end(json_encode(array('success' => true, 'data' => 'ok')));
    }catch(Exception $e) {
        $response->end(json_encode(array('success' => false, 'data' => $e->getMessage())));    
    }
});

// 监听task任务
$http->on('task', function($serv, $task_id, $src_worker_id, $data){
    // 将日志数据插入mongodb
    $mongodb = new mongodb('log');
    $mongodb->insert('test', $data);

    return 'finish';
});

$http->on('finish', function($serv, $task_id, $data){
    echo 'task finish';   
});

$http->start();