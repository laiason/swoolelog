<?php

function http_post($url, $post_data) {
    if(is_array($post_data)) {
        $o = "";
        foreach ($post_data as $k => $v) {
            $o.= "$k=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$url = 'http://127.0.0.1/';
$post_data = [ 
    'system' => '系统',
    'type' => '类别',
    'title' => '标题',
    'ip' => '请求IP',
    'user' => '用户',
    'write_time' => '请求时间',
    'request' => '请求串',
    'response' => '返回串',
    'time_consume' => '耗时',
];

$rtn = http_post($url, json_encode($post_data));
var_dump($rtn);