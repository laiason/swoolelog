<?php

define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/mongodb.php');

$http = new swoole_http_server("0.0.0.0", 80);

$http->set([
    'worker_num' => 2,
    'task_worker_num' => 2,
]);

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
        $http->task($data);
        $response->end(json_encode(array('success' => true, 'data' => 'ok')));
    }catch(Exception $e) {
        $response->end(json_encode(array('success' => false, 'data' => $e->getMessage())));    
    }
});

$http->on('task', function($serv, $task_id, $src_worker_id, $data){
    $mongodb = new mongodb('log');
    $mongodb->insert('test', $data);
    return $task_id;
});

$http->on('finish', function($serv, $task_id, $data){
    echo 'task finish';   
});

$http->start();