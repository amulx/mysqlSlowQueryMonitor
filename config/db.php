<?php
// 1、数据库相关参数
$dbconfig = [
	'host' => '127.0.0.1',
	'user' => 'root',
	'pass' => 'root',
	'port' => '3306',
	'dbname' => 'sql_db',
	'charset' => 'utf8'
];
// 2、Percona Toolkit 可执行文件路径
$pt_query_digest = '';

try {
	$dsn = "mysql:host={$dbconfig['host']};port={$dbconfig['port']};dbname={$dbconfig['dbname']};charset={$dbconfig['charset']}";
	$pdo = new PDO($dsn, $dbconfig['user'],  $dbconfig['pass']);
} catch (Exception $e) {
    echo iconv("UTF-8","GB2312//IGNORE",$e->getMessage());
}