<?php
// 注意执行权限
require_once(__DIR__ .DIRECTORY_SEPARATOR .'..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'db.php');

// 1、获取当前慢日志存放路径
$sql = 'show variables like \'slow_query_log_file\'';
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$slowquery_log_file = $row['Value'];

// 2、搜集信息到统计表中
// $pt_query_digest = __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'percona'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'pt-query-digest';

$command = $pt_query_digest.' --user='.$dbconfig['user'].' --password='.$dbconfig['pass'].' --port='.$dbconfig['port'];
// 将分析结果保存到表中，这个分析只是对查询条件进行参数化，一个类型的查询一条记录，比较简单。当下次使用--review时，如果存在相同的语句分析，就不会记录到数据表中
$command .= ' --review h='.$dbconfig['host'].',D='.$dbconfig['dbname'].',t=mysql_slow_query_review';
// 将分析结果保存到表中，分析结果比较详细，下次再使用--history时，如果存在相同的语句，且查询所在的时间区间和历史表中的不同，则会记录到数据表中，可以通过查询同一CHECKSUM来比较某类型查询的历史变化
$command .= ' --history h='.$dbconfig['host'].',D='.$dbconfig['dbname'].',t=mysql_slow_query_review_history';
$command .= ' --no-report';
$command .= ' --limit=100% ';
$command .= $slowquery_log_file;
// echo $command;

exec($command,$output,$return_var);

if ($return_var !== 0) {
	print_r($output);
	die()；
}

// 3、设置新的慢日志文件
$pos = strripos($slowquery_log_file, '.');
$new_slow_log_file = substr($slowquery_log_file, 0,$pos).date('YmdHis',time()).'.log';
// set global slow_query_log_file ='D:\\soft\\mysql-5.7.25-winx64\\data\\ASUSPC-slow.log'
$sql = 'set global slow_query_log_file = \''. $new_slow_log_file.'\'';
$sql = str_replace('\\', '\\\\', $sql);
$pdo->exec($sql);

// 注意新的日志文件权限
exec('sudo chmod 777 '.$new_slow_log_file);
// 4、定时清除过期的日志文件