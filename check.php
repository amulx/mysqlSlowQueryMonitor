<?php
/*
show global variables like 'slow_query_log';
set global slow_query_log=ON;

show global variables like 'long_query_time';
set global long_query_time=1;
*/

// 设置分隔符
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
// 1、检查pdo扩展是否已安装
	// 获取所有编译并加载模块名，其中get_extension_funcs('pdo') =>返回模块函数名称的数组 
$allExtension = get_loaded_extensions();
if (!in_array('pdo_mysql', $allExtension)) {
	echo "pdo_mysql扩展未安装，请先安装PDO扩展".EOL;
	die();
}

require_once(__DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'db.php');

/*
MySQL慢查询日志是MySQL提供的一种日志记录，用来记录执行时长超过指定时长的查询语句，具体指运行时间超过 long_query_time 值的 SQL 语句，则会被记录到慢查询日志中。

long_query_time 默认值是 10 ，单位是 s，即默认是 10秒 。默认情况下，MySQL数据库并不会开启慢查询日志，需要手动设置这个参数。

通过慢查询日志，可以查找出哪些查询语句的执行效率很低，以便进行优化。一般建议开启，它对服务器性能的影响微乎其微，但是可以记录MySQL服务器上执行了很长时间的查询语句。慢查询日志可以帮助我们定位mysql性能问题所在。
*/
// 2、判断当前MySQL是否开启了慢日志功能
$sql = 'show global variables like \'slow_query_log\'';
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (strtolower($row['Value']) == 'off') {
	echo "未开启慢日志功能".EOL;
$warnInfo = <<<EOF
<h2>慢查询日志相关参数</h2>
<p>
slow_query_log : 是否启用慢查询日志，[1 | 0] 或者 [ON | OFF]
</p>
<p>
slow_query_log_file : MySQL数据库（5.6及以上版本）慢查询日志存储路径。
                    可以不设置该参数，系统则会默认给一个缺省的文件 HOST_NAME-slow.log
</p>
long_query_time : 慢查询的阈值，当查询时间超过设定的阈值时，记录该SQL语句到慢查询日志。
</p>
</p>
log_queries_not_using_indexes ：设置为 ON ，可以捕获到所有未使用索引的SQL语句(不建议启用)
</p>
</p>
log_output : 日志存储方式。<br>
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; log_output='FILE'，表示将日志存入文件，默认值是'FILE'。      <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;log_output='TABLE'，表示将日志存入数据库，这样日志信息就会被写入到 mysql.slow_log 表中。<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MySQL数据库支持同时两种日志存储方式，配置的时候以逗号隔开即可，如：log_output='FILE,TABLE'。<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日志记录到系统的专用日志表中，要比记录到文件耗费更多的系统资源，因此对于需要启用慢查询日志，又需要能够获得更高的系统性能，那么建议优先记录到文件。<br>
            </p>
      <hr>
<h2>开启日志</h2>    
<h3>方式一：修改配置文件，永久生效</h3>
<code>
修改 my.cnf ： <br> <br>

[mysqld] <br>
slow_query_log           = 1 <br>
slow_query_log_file      = /xxx/mysql-slow.log <br>
long_query_time          = 1 <br>

# 也可以写成这种形式 <br>
slow-query-log           = 1 <br>
slow-query-log-file      = /xxx/mysql-slow.log <br>
long-query-time          = 1 <br>
</code>
<h2>方式二：参数设置，立即生效，重启失效</h2>
<code>
set global slow_query_log=ON;<br>
set global slow_query_log_file='/xxx/mysql-slow.log';<br>
</code>
EOF;
echo $warnInfo;
	die();
}


$sql = 'show global variables like \'long_query_time\'';
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$pdo = null;

// 3、判断 pt_query_digest 是否可执行
if (empty($pt_query_digest)) {
    echo '请先配置 pt_query_digest 路径';
    die();
}
if (!is_executable($pt_query_digest)) {
    echo '请赋予 '.$pt_query_digest.' 可执行权限';
    die();
}

// 4、判断 soar 是否可执行
if (strtoupper(substr(PHP_OS,0,3))==='WIN') {

} else {
    if (!is_executable(__DIR__ .DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'soar'.DIRECTORY_SEPARATOR.'soar')) {
        echo '请赋予 '.__DIR__ .DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'soar'.DIRECTORY_SEPARATOR.'soar'.' 可执行权限';
        die();
    }
}

echo "所有条件已经满足，当前慢日志的临界值为：".$row['Value'];