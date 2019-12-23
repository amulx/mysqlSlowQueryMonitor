下载地址：https://www.percona.com/doc/percona-toolkit/2.2/installation.html
		  https://www.percona.com/doc/percona-toolkit/3.0/installation.html
		  
帮助手册：https://www.percona.com/doc/percona-toolkit/3.0/pt-query-digest.html		  
		  https://www.percona.com/doc/percona-toolkit/3.0/index.html


=======  perl的模块 ==============
yum install -y perl-CPAN perl-Time-HiRes



===================== 方法一：二进制安装 =======================
# 安装依赖
sudo yum -y install perl-Digest-MD5

# 下载percona-toolkit的二进制版
wget https://www.percona.com/downloads/percona-toolkit/3.1.0/binary/tarball/percona-toolkit-3.1.0_x86_64.tar.gz

# 解压
tar -zxvf percona-toolkit-3.1.0_x86_64.tar.gz

cd percona-toolkit-3.1.0

# 查看帮助文档
./pt-query-digest --help



=============  方法二：rpm安装  ================================
cd /usr/local/src
wget percona.com/get/percona-toolkit.rpm
yum install -y percona-toolkit.rpm



=============  方法三：源码安装 ================================
cd /usr/local/src
wget percona.com/get/percona-toolkit.tar.gz
tar zxf percona-toolkit.tar.gz
cd percona-toolkit-2.2.19
perl Makefile.PL PREFIX=/usr/local/percona-toolkit
make && make install


======  我安装的依赖 =====
sudo yum install perl-DBI
sudo yum install perl-DBD-MySQL
sudo yum -y install perl-Digest-MD5