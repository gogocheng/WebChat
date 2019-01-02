<?php

use Workerman\worker;

require_once 'Autoloader.php';


$ws_worker = new Worker("websocket://0.0.0.0:2000");

// 启动4个进程对外提供服务
$ws_worker->count = 4;

//记录客户端数量
$uid = 0;

//客户端连接触发
$ws_worker->onConnect = function($connection){
	global $uid;
	$connection->uid = ++$uid;
};

// 当收到客户端发来的数据后返回$data给客户端
$ws_worker->onMessage = function($connection, $data)
{
	//定义全局变量
	global $ws_worker;
	//找到所有客户端循环发送
	foreach ($ws_worker->connections as $connection) {
		// 向客户端发送$data
    	$connection->send($data);
	}
    
};

// 运行worker
Worker::runAll();