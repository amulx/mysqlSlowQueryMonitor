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
<script language="javascript">
function TestBlack(TagName){
 	var obj = document.getElementById(TagName);
 	if(obj.style.display=="block"){
  		obj.style.display = "none";
 	}else{
  		obj.style.display = "block";
 	}
}

</script>
</head>

<body>
<div class="card">
<div class="card-header bg-light">
    <h1><a href="slowquery.php?action=logout">MySQL 慢查询分析</a></h1>
</div>

<div class="card-body">
<div class="table-responsive">

<table class="table table-hover">                                    
<thead>                                   
	<tr>                                    
		<th>抽象语句</th>
		<th>最近时间</th>
		<th>次数</th>
		<th>平均时间</th>
		<th>最小时间</th>
		<th>最大时间</th>
	</tr>
</thead>
<tbody>

<?php
    $sql = <<<EOF
SELECT
	r. CHECKSUM,
	r.fingerprint,
	r.last_seen,
	SUM(h.ts_cnt) AS ts_cnt,
	ROUND(MIN(h.Query_time_min), 3) AS Query_time_min,
	ROUND(MAX(h.Query_time_max), 3) AS Query_time_max,
	ROUND(
		SUM(h.Query_time_sum) / SUM(h.ts_cnt),
		3
	) AS Query_time_avg,
	r.sample
FROM
	mysql_slow_query_review AS r
JOIN mysql_slow_query_review_history AS h ON r. CHECKSUM = h. CHECKSUM
WHERE
	r.last_seen >= SUBDATE(NOW(), INTERVAL 31 DAY)
GROUP BY
	r. CHECKSUM
ORDER BY
	r.last_seen DESC,
	ts_cnt DESC
EOF;

	$stmt = $pdo->query($sql);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<br> 慢查询日志agent采集阀值是每10分钟/次，SQL执行时间（单位：秒）</br>";

    foreach ($result as $key => $row) {
    	echo "<tr>";
		echo "<td width='100px' onclick=\"TestBlack('${row['CHECKSUM']}')\">✚  &nbsp;" .substr("{$row['fingerprint']}",0,50)  
	     ."<div id='${row['CHECKSUM']}' style='display:none;'><a href='explain.php?checksum={$row['CHECKSUM']}'>" .SqlFormatter::format($row['fingerprint']) ."</br></div></a></td>";
		echo "<td>{$row['last_seen']}</td>";
		echo "<td>{$row['ts_cnt']}</td>";
		echo "<td>{$row['Query_time_min']}</td>";
		echo "<td>{$row['Query_time_max']}</td>";
		echo "<td>{$row['Query_time_avg']}</td>";
		// echo "<td>{$row['sample']}</td>";
		echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
?>