<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR .'config'.DIRECTORY_SEPARATOR.'db.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR .'lib'.DIRECTORY_SEPARATOR.'SqlFormatter.php');
?>
<html>
<head>
    <meta http-equiv="Content-Type"  content="text/html;  charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>慢查询日志</title>
    <link rel="stylesheet" href="./static/css/simple-line-icons.css">
    <link rel="stylesheet" href="./static/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./static/css/styles.css">
</head>

<body>

<div class="card">
    <div class="card-header bg-light">
        详细的慢SQL语句是：
    </div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover">
<?php
	$checksum=isset($_GET['checksum'])?$_GET['checksum']:'';
	if (empty($checksum)) {
		echo "非法访问";die();
	}
	$get_sql = "select sample from mysql_slow_query_review_history where checksum='${checksum}' limit 1";
	$stmt = $pdo->query($get_sql);
	$sample_sql = $stmt->fetch(PDO::FETCH_ASSOC)['sample'];

	echo "<tr><td>" .SqlFormatter::format($sample_sql) ."</tr></td>";
?>
</table>

    <div class="card-header bg-light">
        执行计划：
    </div>
    <table class="table table-hover">
    <tr>
	    <th>id</th>      
	    <th>select_type</th>
	    <th>table</th>
	    <th>type</th>
	    <th>possible_keys</th>
	    <th>key</th>
	    <th>key_len</th>
	    <th>ref</th>
	    <th>rows</th>
	    <th>Extra</th>
    </tr>

<?php
    $get_sql_explain = 'EXPLAIN '.$sample_sql;

    $stmt = $pdo->query($get_sql_explain);
    $explain = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($explain as $key => $row) {
		echo '<tr>';
			echo '<td>'.$row['id'].'</td>';
			echo '<td>'.$row['select_type'].'</td>';
			echo '<td>'.$row['table'].'</td>';
			echo '<td>'.$row['type'].'</td>';
			echo '<td>'.$row['possible_keys'].'</td>';
			echo '<td>'.$row['key'].'</td>';
			echo '<td>'.$row['key_len'].'</td>';
			echo '<td>'.$row['ref'].'</td>';
			echo '<td>'.$row['rows'].'</td>';
			echo '<td>'.$row['Extra'].'</td>';
		echo '</tr>';
	}

?>

</table>
</div>
</div>
</div>

<?php
	$pre_command = 'echo "' . $sample_sql.'"';

	if (strtoupper(substr(PHP_OS,0,3))==='WIN') {
		$fix_command = __DIR__ .DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'soar'.DIRECTORY_SEPARATOR.'soar.windows-amd64';
	} else {
		$fix_command = __DIR__ .DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'soar'.DIRECTORY_SEPARATOR.'soar';
	}

	$fix_command .= ' -online-dsn='.$dbconfig['user'].':'.$dbconfig['pass'].'@'.$dbconfig['host'].':'.$dbconfig['port'].'/'.$dbconfig['dbname'];
	$fix_command .= ' -test-dsn='.$dbconfig['user'].':'.$dbconfig['pass'].'@'.$dbconfig['host'].':'.$dbconfig['port'].'/'.$dbconfig['dbname'];
	$fix_command .= ' -report-type=html -explain=true -log-output=./soar.log';

	$command = $pre_command . ' | ' . $fix_command;
	// echo $command;
	$html_str = system($command);
	echo $html_str;
	echo '<br><h3><a href="javascript:history.back(-1);">点击此处返回</a></h3></br>'; 
?>

</body>
</html>


