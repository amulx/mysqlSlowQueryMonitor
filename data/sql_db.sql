/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50725
Source Host           : localhost:3306
Source Database       : sql_db

Target Server Type    : MYSQL
Target Server Version : 50725
File Encoding         : 65001

Date: 2019-12-23 15:04:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `mysql_slow_query_review`
-- ----------------------------
DROP TABLE IF EXISTS `mysql_slow_query_review`;
CREATE TABLE `mysql_slow_query_review` (
  `checksum` char(32) NOT NULL,
  `fingerprint` text NOT NULL,
  `sample` text NOT NULL,
  `first_seen` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `reviewed_by` varchar(20) DEFAULT NULL,
  `reviewed_on` datetime DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mysql_slow_query_review
-- ----------------------------
INSERT INTO `mysql_slow_query_review` VALUES ('59A74D08D407B5EDF9A57DD5A41825CA', 'select sleep(?)', 'select sleep(2)', '2019-12-20 08:16:16', '2019-12-20 08:16:16', null, null, null);
INSERT INTO `mysql_slow_query_review` VALUES ('A53E33D0147563186CC916E057A9DCBA', 'select i.id, idcode, i.name, familymaster, yhzgx, reservoirs_name reservoirsname, bqsj, yjzd, sex, birthday, mz, ffqsnd, ffbz, khyh, whcd, sfyq, hdlx, hzhjsj, hzhjyy, ymdm, shzt, hzcode, insert_year insertyear,a.fullname reservoirarea,r.reservoirs_type reservoirstype,a?ullname areaname, i.reservoirs_ennmcd reservoirsennmcd,i.area_code areacode from immigration i left join reservoirs r on r.reservoirs_code=i.reservoirs_ennmcd left join area a on a.area_code=r.areacode left join area a? on a?rea_code=i.area_code where i.is_valid=? and shzt = ? and is_back = ? and substr(i.area_code,?,?) = substr(?,?,?)', 'SELECT  i.id,  idcode,    i.name,  familymaster,  yhzgx,  reservoirs_name  reservoirsName,  bqsj,  yjzd,  sex,  \r\n              birthday,  mz,  ffqsnd,  ffbz,  khyh,  whcd,  sfyq,  hdlx,  hzhjsj,  hzhjyy,  ymdm,  shzt,  hzcode,\r\n              insert_year  insertYear,a.fullName  reservoirArea,r.reservoirs_type  reservoirsType,a1.fullName  areaName,\r\n              i.reservoirs_ennmcd  reservoirsEnnmcd,i.area_code  areaCode\r\n        FROM  immigration  i    \r\n        left  join  reservoirs  r  on  r.reservoirs_code=i.reservoirs_ennmcd\r\n        left  join  area  a  on  a.area_code=r.areaCode\r\n        left  join  area  a1  on  a1.area_code=i.area_code\r\n        WHERE  i.is_valid=1  \r\n        and  shzt  =  \'0\'\r\n        and  is_back  =  \'0\'\r\n        AND  SUBSTR(i.area_code,1,\'2\')  =  SUBSTR(\'44\',1,\'2\')', '2019-12-22 05:59:56', '2019-12-22 05:59:56', null, null, null);

-- ----------------------------
-- Table structure for `mysql_slow_query_review_history`
-- ----------------------------
DROP TABLE IF EXISTS `mysql_slow_query_review_history`;
CREATE TABLE `mysql_slow_query_review_history` (
  `checksum` char(32) NOT NULL,
  `sample` text NOT NULL,
  `ts_min` datetime NOT NULL,
  `ts_max` datetime NOT NULL,
  `ts_cnt` float DEFAULT NULL,
  `Query_time_sum` float DEFAULT NULL,
  `Query_time_min` float DEFAULT NULL,
  `Query_time_max` float DEFAULT NULL,
  `Query_time_pct_95` float DEFAULT NULL,
  `Query_time_stddev` float DEFAULT NULL,
  `Query_time_median` float DEFAULT NULL,
  `Lock_time_sum` float DEFAULT NULL,
  `Lock_time_min` float DEFAULT NULL,
  `Lock_time_max` float DEFAULT NULL,
  `Lock_time_pct_95` float DEFAULT NULL,
  `Lock_time_stddev` float DEFAULT NULL,
  `Lock_time_median` float DEFAULT NULL,
  `Rows_sent_sum` float DEFAULT NULL,
  `Rows_sent_min` float DEFAULT NULL,
  `Rows_sent_max` float DEFAULT NULL,
  `Rows_sent_pct_95` float DEFAULT NULL,
  `Rows_sent_stddev` float DEFAULT NULL,
  `Rows_sent_median` float DEFAULT NULL,
  `Rows_examined_sum` float DEFAULT NULL,
  `Rows_examined_min` float DEFAULT NULL,
  `Rows_examined_max` float DEFAULT NULL,
  `Rows_examined_pct_95` float DEFAULT NULL,
  `Rows_examined_stddev` float DEFAULT NULL,
  `Rows_examined_median` float DEFAULT NULL,
  `Rows_affected_sum` float DEFAULT NULL,
  `Rows_affected_min` float DEFAULT NULL,
  `Rows_affected_max` float DEFAULT NULL,
  `Rows_affected_pct_95` float DEFAULT NULL,
  `Rows_affected_stddev` float DEFAULT NULL,
  `Rows_affected_median` float DEFAULT NULL,
  `Rows_read_sum` float DEFAULT NULL,
  `Rows_read_min` float DEFAULT NULL,
  `Rows_read_max` float DEFAULT NULL,
  `Rows_read_pct_95` float DEFAULT NULL,
  `Rows_read_stddev` float DEFAULT NULL,
  `Rows_read_median` float DEFAULT NULL,
  `Merge_passes_sum` float DEFAULT NULL,
  `Merge_passes_min` float DEFAULT NULL,
  `Merge_passes_max` float DEFAULT NULL,
  `Merge_passes_pct_95` float DEFAULT NULL,
  `Merge_passes_stddev` float DEFAULT NULL,
  `Merge_passes_median` float DEFAULT NULL,
  `InnoDB_IO_r_ops_min` float DEFAULT NULL,
  `InnoDB_IO_r_ops_max` float DEFAULT NULL,
  `InnoDB_IO_r_ops_pct_95` float DEFAULT NULL,
  `InnoDB_IO_r_ops_stddev` float DEFAULT NULL,
  `InnoDB_IO_r_ops_median` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_min` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_max` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_pct_95` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_stddev` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_median` float DEFAULT NULL,
  `InnoDB_IO_r_wait_min` float DEFAULT NULL,
  `InnoDB_IO_r_wait_max` float DEFAULT NULL,
  `InnoDB_IO_r_wait_pct_95` float DEFAULT NULL,
  `InnoDB_IO_r_wait_stddev` float DEFAULT NULL,
  `InnoDB_IO_r_wait_median` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_min` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_max` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_pct_95` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_stddev` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_median` float DEFAULT NULL,
  `InnoDB_queue_wait_min` float DEFAULT NULL,
  `InnoDB_queue_wait_max` float DEFAULT NULL,
  `InnoDB_queue_wait_pct_95` float DEFAULT NULL,
  `InnoDB_queue_wait_stddev` float DEFAULT NULL,
  `InnoDB_queue_wait_median` float DEFAULT NULL,
  `InnoDB_pages_distinct_min` float DEFAULT NULL,
  `InnoDB_pages_distinct_max` float DEFAULT NULL,
  `InnoDB_pages_distinct_pct_95` float DEFAULT NULL,
  `InnoDB_pages_distinct_stddev` float DEFAULT NULL,
  `InnoDB_pages_distinct_median` float DEFAULT NULL,
  `QC_Hit_cnt` float DEFAULT NULL,
  `QC_Hit_sum` float DEFAULT NULL,
  `Full_scan_cnt` float DEFAULT NULL,
  `Full_scan_sum` float DEFAULT NULL,
  `Full_join_cnt` float DEFAULT NULL,
  `Full_join_sum` float DEFAULT NULL,
  `Tmp_table_cnt` float DEFAULT NULL,
  `Tmp_table_sum` float DEFAULT NULL,
  `Tmp_table_on_disk_cnt` float DEFAULT NULL,
  `Tmp_table_on_disk_sum` float DEFAULT NULL,
  `Filesort_cnt` float DEFAULT NULL,
  `Filesort_sum` float DEFAULT NULL,
  `Filesort_on_disk_cnt` float DEFAULT NULL,
  `Filesort_on_disk_sum` float DEFAULT NULL,
  PRIMARY KEY (`checksum`,`ts_min`,`ts_max`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mysql_slow_query_review_history
-- ----------------------------
INSERT INTO `mysql_slow_query_review_history` VALUES ('59A74D08D407B5EDF9A57DD5A41825CA', 'select sleep(2)', '2019-12-20 08:16:16', '2019-12-20 08:16:16', '1', '2.00045', '2.00045', '2.00045', '2.00045', '0', '2.00045', '0', '0', '0', '0', '0', '0', '1', '1', '1', '1', '0', '1', '0', '0', '0', '0', '0', '0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `mysql_slow_query_review_history` VALUES ('A30B40AB156113439F88EB9BEBB61C45', 'SELECT\r\n	count(1) sum\r\nFROM\r\n	(\r\n		SELECT\r\n			i.id,\r\n			idcode,\r\n			i. NAME,\r\n			familymaster,\r\n			yhzgx,\r\n			reservoirs_name reservoirsName,\r\n			bqsj,\r\n			yjzd,\r\n			sex,\r\n			birthday,\r\n			mz,\r\n			ffqsnd,\r\n			ffbz,\r\n			khyh,\r\n			whcd,\r\n			sfyq,\r\n			hdlx,\r\n			hzhjsj,\r\n			hzhjyy,\r\n			ymdm,\r\n			shzt,\r\n			hzcode,\r\n			insert_year insertYear,\r\n			a.fullName reservoirArea,\r\n			r.reservoirs_type reservoirsType,\r\n			a1.fullName areaName,\r\n			i.reservoirs_ennmcd reservoirsEnnmcd,\r\n			i.area_code areaCode\r\n		FROM\r\n			immigration i\r\n		LEFT JOIN reservoirs r ON r.reservoirs_code = i.reservoirs_ennmcd\r\n		LEFT JOIN area a ON a.area_code = r.areaCode\r\n		LEFT JOIN area a1 ON a1.area_code = i.area_code\r\n		WHERE\r\n			i.is_valid = 1\r\n		AND shzt = \'0\'\r\n		AND is_back = \'0\'\r\n		AND SUBSTR(i.area_code, 1, \'2\') = SUBSTR(\'44\', 1, \'2\')\r\n	) a\r\nWHERE\r\n	1 = 1', '2019-12-23 01:09:17', '2019-12-23 01:09:17', '1', '2.00531', '2.00531', '2.00531', '2.00531', '0', '2.00531', '0.001007', '0.001007', '0.001007', '0.001007', '0', '0.001007', '1', '1', '1', '1', '0', '1', '1139910', '1139910', '1139910', '1139910', '0', '1139910', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `mysql_slow_query_review_history` VALUES ('A53E33D0147563186CC916E057A9DCBA', 'SELECT  i.id,  idcode,    i.name,  familymaster,  yhzgx,  reservoirs_name  reservoirsName,  bqsj,  yjzd,  sex,  \r\n              birthday,  mz,  ffqsnd,  ffbz,  khyh,  whcd,  sfyq,  hdlx,  hzhjsj,  hzhjyy,  ymdm,  shzt,  hzcode,\r\n              insert_year  insertYear,a.fullName  reservoirArea,r.reservoirs_type  reservoirsType,a1.fullName  areaName,\r\n              i.reservoirs_ennmcd  reservoirsEnnmcd,i.area_code  areaCode\r\n        FROM  immigration  i    \r\n        left  join  reservoirs  r  on  r.reservoirs_code=i.reservoirs_ennmcd\r\n        left  join  area  a  on  a.area_code=r.areaCode\r\n        left  join  area  a1  on  a1.area_code=i.area_code\r\n        WHERE  i.is_valid=1  \r\n        and  shzt  =  \'0\'\r\n        and  is_back  =  \'0\'\r\n        AND  SUBSTR(i.area_code,1,\'2\')  =  SUBSTR(\'44\',1,\'2\')', '2019-12-22 05:59:56', '2019-12-22 05:59:56', '1', '6.47742', '6.47742', '6.47742', '6.47742', '0', '6.47742', '0.034409', '0.034409', '0.034409', '0.034409', '0', '0.034409', '284978', '284978', '284978', '284978', '0', '284978', '1139910', '1139910', '1139910', '1139910', '0', '1139910', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
