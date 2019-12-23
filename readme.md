# 图形化显示MySQL慢日志工具

## 一、percona toolkit安装

[下载地址](https://www.percona.com/downloads/percona-toolkit/LATEST/)

![1576919154402](C:\Users\Administrator\AppData\Roaming\Typora\typora-user-images\1576919154402.png)

安装依赖以及常见安装过程中出现的问题：

大多数工具需要：

- Perl v5.8或更高版本
- Bash v3或更新版本

* 核心Perl模块，如Time :: HiRes

连接到MySQL的工具需要：

- Perl模块DBI和DBD :: mysql
- MySQL 5.0或更新版本

常见安装报错：

```shell
[root@vipstone percona-toolkit-3.0.13]# perl Makefile.PL PREFIX=/usr/local/percona-toolkit
Warning: prerequisite DBD::mysql 3 not found.
Warning: prerequisite DBI 1.46 not found
[root@vipstone percona-toolkit-3.0.13]# yum install perl-DBD-MySQL
[root@vipstone percona-toolkit-3.0.13]# yum load-transaction /tmp/yum_save_tx.2019-06-24.00-47.2waZWK.yumtx
root@vipstone bin]# pt-query-digest /usr/local/mysql/data/slow.log
Can't locate Digest/MD5.pm in @INC (@INC contains: /usr/local/lib64/perl5 /usr/local/share/perl5 /usr/lib64/perl5/vendor_perl /usr/share/perl5/vendor_perl /usr/lib64/perl5 /usr/share/perl5 .) at /usr/local/bin/pt-query-digest line 2470.
BEGIN failed--compilation aborted at /usr/local/bin/pt-query-digest line 2470.
[root@vipstone percona-toolkit-3.0.13]# yum -y install perl-Digest-MD5
```

### 1、常用语法

慢查询日志分析统计

```
pt-query-digest /usr/local/mysql/data/slow.log
```

服务器摘要

```
pt-summary
```

服务器磁盘监测

```
pt-diskstats
```

mysql服务状态摘要

```
pt-mysql-summary -- --user=root --password=root
```

### 2、pt-query-digest语法及重要选项

`pt-query-digest [OPTIONS] [FILES] [DSN]`

| 参数                   | 说明                                                         |
| ---------------------- | ------------------------------------------------------------ |
| --host                 | mysql服务器地址                                              |
| --user                 | mysql用户名                                                  |
| --password             | mysql用户密码                                                |
| --review               | 将分析结果保存到表中，这个分析只是对查询条件进行参数化，一个类型的查询一条记录，比较简单。当下次使用--review时，如果存在相同的语句分析，就不会记录到数据表中 |
| --create-history-table | 当使用--history参数把分析结果输出到表中时，如果没有表就自动创建 |
| --create-review-table  | 当使用--review参数把分析结果输出到表中时，如果没有表就自动创建 |
| --history              | 将分析结果保存到表中，分析结果比较详细，下次再使用--history时，如果存在相同的语句，且查询所在的时间区间和历史表中的不同，则会记录到数据表中，可以通过查询同一CHECKSUM来比较某类型查询的历史变化 |
| --output               | 分析结果输出类型，值可以是report(标准分析报告)、slowlog(Mysql slow log)、json、json-anon，一般使用report，以便于阅读 |
| --filter               | 对输入的慢查询按指定的字符串进行匹配过滤后再进行分析         |
| --limit                | 限制输出结果百分比或数量，默认值是20,即将最慢的20条语句输出，如果是50%则按总响应时间占比从大到小排序，输出到总和达到50%位置截止 |
| --since                | 从什么时间开始分析，值为字符串，可以是指定的某个”yyyy-mm-dd [hh:mm:ss]”格式的时间点，也可以是简单的一个时间值：s(秒)、h(小时)、m(分钟)、d(天)，如12h就表示从12小时前开始统计 |
| --until                | 截止时间，配合—since可以分析一段时间内的慢查询               |

使用事例：

```shell
# 直接分析慢查询文件
pt-query-digest slow.log > slow_report.log

# 分析最近12小时内的查询
pt-query-digest --since=12h slow.log > slow_report2.log

# 分析指定时间范围内的查询
pt-query-digest slow.log --since '2017-01-07 09:30:00' --until '2017-01-07 10:00:00'> > slow_report3.log

# 分析指含有select语句的慢查询
pt-query-digest --filter '$event->{fingerprint} =~ m/^select/i' slow.log> slow_report4.log

# 针对某个用户的慢查询
pt-query-digest --filter '($event->{user} || "") =~ m/^root/i' slow.log> slow_report5.log

# 查询所有所有的全表扫描或full join的慢查询
pt-query-digest --filter '(($event->{Full_scan} || "") eq "yes") ||(($event->{Full_join} || "") eq "yes")' slow.log> slow_report6.log

# 把查询保存到query_review表
pt-query-digest --user=root –password=abc123 --review h=localhost,D=test,t=query_review--create-review-table slow.log

# 把查询保存到query_history表
pt-query-digest --user=root –password=abc123 --review h=localhost,D=test,t=query_history--create-review-table slow.log_0001pt-query-digest --user=root –password=abc123 --review h=localhost,D=test,t=query_history--create-review-table slow.log_0002

# 通过tcpdump抓取mysql的tcp协议数据，然后再分析
tcpdump -s 65535 -x -nn -q -tttt -i any -c 1000 port 3306 > mysql.tcp.txt
pt-query-digest --type tcpdump mysql.tcp.txt> slow_report9.log
# 分析binlog
mysqlbinlog mysql-bin.000093 > mysql-bin000093.sql
pt-query-digest --type=binlog mysql-bin000093.sql > slow_report10.log
# 分析general log
pt-query-digest --type=genlog localhost.log > slow_report11.log
```

## 二、soar安装

```shell
wget https://github.com/XiaoMi/soar/releases/download/${tag}/soar.${OS}-amd64 -O soar
chmod a+x soar
如：
wget https://github.com/XiaoMi/soar/releases/download/0.9.0/soar.linux-amd64 -O soar
chmod a+x soar
```

## 三、MySQL安装

[下载地址](https://dev.mysql.com/downloads/mysql/)

![1576936530224](C:\Users\Administrator\AppData\Roaming\Typora\typora-user-images\1576936530224.png)

```shell
# 创建普通用户，不能登录系统  useradd -s /sbin/nologin -M mysql
shell> groupadd mysql
shell> useradd -r -g mysql -s /bin/false mysql
shell> cd /usr/local
shell> tar xvf /path/to/mysql-VERSION-OS.tar.xz
# 可选，选择自己希望存放的路径
shell> ln -s full-path-to-mysql-VERSION-OS mysql
shell> cd mysql
shell> mkdir mysql-files
shell> chown mysql:mysql mysql-files
shell> chmod 750 mysql-files
# 帮助说明
shell> bin/mysqld --verbose --help
shell> bin/mysqld --initialize --user=mysql
shell> bin/mysqld --initialize --user=mysql --datadir=./data
# 启用ssl
shell> bin/mysql_ssl_rsa_setup --datadir=./data 
# 注意查看/etc/my.cnf的权限和内容，不对的进行修改
shell> bin/mysqld_safe --user=mysql &
# Next command is optional
shell> cp support-files/mysql.server /etc/init.d/mysql.server
# 清除多余用户
select user,host from mysql.user;
drop user ''@'localhost';
drop user 'root'@'%';
# 数据库创建规范和用户权限设置
CREATE DATABASE IF NOT EXISTS test  CHARACTER SET utf8 COLLATE utf8_general_ci;
# 用户名为test密码为admin
create user 'test'@'192.168.3.70' identified by 'admin';
# test库下面的所有表
grant insert,delete,update,select,create,drop on test.* to klion@'192.168.3.70' identified by 'admin';
# test库下面的admin表
grant insert,delete,update,select,create,drop on test.admin to sec@'192.168.3.70' identified by 'admin';
flush  privileges;
# 查询指定数据库用户的系统权限
show grants for 'klion'@'192.168.3.70';
# 撤销指定用户的指定权限
revoke select on test.* from 'sec'@'192.168.3.70';
# 严禁用语句对root重新授权
grant all on *.* to 'root'@'%' identified by 'admin' with grant option;flush privileges;
# 删掉mysql操作历史
rm -fr .mysql_history
# 先把当前所有的历史记录写到命令历史文件中去
history -w		  
# 然后编辑该文件,把里面所有的关于mysql的操作全部删除
vi .bash_history
# 最后,再更新文件,看看刚刚删掉的那些记录还在不在
history -w && history
select concat('D:\soft\mysql-5.7.25-winx64\data\ASUSPC-slow.log','slowquery_',date_format(now(),'%Y%m%d%H'),'.log')

set global slow_query_log_file ='D:\\soft\\mysql-5.7.25-winx64\\data\\ASUSPC-slow.log'
show GLOBAL VARIABLES like 'slow_query_log_file';

set global slow_query_log_file = '/tmp/mysql_slow.log'
```

## 四、合体

### 1、修改配置文件对应的参数

```php
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
```

### 2、设置定时任务

![](.\static\crond.png)

```shell
crontab -e
1 23 * * * /Data/apps/php/bin/php /Data/apps/wwwroot/mysqlSlowQueryMonitor/command/collectLog.php
sudo systemctl restart crond
```

### 3、运行测试

`check.php`为环境检测文件，可在浏览器中访问该路径，查看当前服务器环境是否已满足运行要求。

### 4、