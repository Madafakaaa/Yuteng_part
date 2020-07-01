/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 80012
Source Host           : localhost:3306
Source Database       : yuteng

Target Server Type    : MYSQL
Target Server Version : 80012
File Encoding         : 65001

Date: 2020-06-29 16:16:59
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `archive`
-- ----------------------------
DROP TABLE IF EXISTS `archive`;
CREATE TABLE `archive` (
  `archive_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '档案id',
  `archive_user` char(8) NOT NULL COMMENT '档案用户',
  `archive_name` varchar(255) NOT NULL COMMENT '档案名称',
  `archive_file_name` varchar(255) NOT NULL COMMENT '档案文件名',
  `archive_file_size` decimal(4,2) NOT NULL COMMENT '档案文件大小',
  `archive_path` varchar(40) NOT NULL COMMENT '档案路径',
  `archive_createuser` char(8) NOT NULL COMMENT '档案创建用户',
  `archive_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '档案创建时间',
  PRIMARY KEY (`archive_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of archive
-- ----------------------------

-- ----------------------------
-- Table structure for `class`
-- ----------------------------
DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `class_id` char(10) NOT NULL COMMENT '班级id',
  `class_name` varchar(20) NOT NULL COMMENT '班级名称',
  `class_department` int(10) unsigned NOT NULL COMMENT '班级校区',
  `class_grade` int(10) unsigned NOT NULL COMMENT '班级年级',
  `class_subject` int(10) unsigned NOT NULL COMMENT '班级科目',
  `class_teacher` char(8) NOT NULL COMMENT '负责教师',
  `class_max_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '班级最大人数',
  `class_current_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '班级当前人数',
  `class_schedule_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '班级课程安排数量',
  `class_attended_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '班级上课记录数量',
  `class_remark` varchar(140) NOT NULL COMMENT '班级备注',
  `class_last_lesson_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '班级上次上课日期',
  `class_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '班级可用状态(0：删除，1：可用)',
  `class_createuser` char(8) NOT NULL COMMENT '班级创建用户',
  `class_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '班级创建时间',
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of class
-- ----------------------------

-- ----------------------------
-- Table structure for `classroom`
-- ----------------------------
DROP TABLE IF EXISTS `classroom`;
CREATE TABLE `classroom` (
  `classroom_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '教室id',
  `classroom_name` varchar(10) NOT NULL COMMENT '教室名称',
  `classroom_department` int(10) unsigned NOT NULL COMMENT '教室校区',
  `classroom_student_num` int(10) unsigned NOT NULL COMMENT '教室容纳人数',
  `classroom_type` varchar(5) NOT NULL COMMENT '教室类型',
  `classroom_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '教室可用状态(0：删除，1：可用)',
  `classroom_createuser` char(8) NOT NULL COMMENT '教室创建用户',
  `classroom_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '教室创建时间',
  PRIMARY KEY (`classroom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of classroom
-- ----------------------------
INSERT INTO `classroom` VALUES ('1', '教室1', '1', '9', '小教室', '0', 'yuto2018', '2020-02-27 13:03:59');
INSERT INTO `classroom` VALUES ('2', '教室二', '1', '10', '中教室', '0', 'yuto2018', '2020-02-27 13:04:14');
INSERT INTO `classroom` VALUES ('3', '教室三', '1', '10', '中教室', '0', 'yuto2018', '2020-02-27 13:05:28');
INSERT INTO `classroom` VALUES ('4', '教室四', '1', '10', '中教室', '0', 'yuto2018', '2020-02-27 13:05:43');
INSERT INTO `classroom` VALUES ('5', '教室五', '1', '12', '大教室', '0', 'yuto2018', '2020-02-27 13:06:23');
INSERT INTO `classroom` VALUES ('6', '教室六', '1', '12', '中教室', '0', 'yuto2018', '2020-02-27 13:06:51');
INSERT INTO `classroom` VALUES ('7', '教室七', '1', '12', '中教室', '0', 'yuto2018', '2020-02-27 13:07:05');
INSERT INTO `classroom` VALUES ('8', '教室八', '1', '12', '中教室', '0', 'yuto2018', '2020-02-27 13:07:33');
INSERT INTO `classroom` VALUES ('9', '教室九', '1', '12', '中教室', '0', 'yuto2018', '2020-02-27 13:07:48');
INSERT INTO `classroom` VALUES ('10', '教室十', '1', '12', '中教室', '0', 'yuto2018', '2020-02-27 13:08:07');
INSERT INTO `classroom` VALUES ('12', '教室2', '2', '9', '小教室', '0', 'yuto2018', '2020-02-27 13:09:34');
INSERT INTO `classroom` VALUES ('13', '教室3', '2', '9', '小教室', '0', 'yuto2018', '2020-02-27 13:09:59');
INSERT INTO `classroom` VALUES ('15', '教室4', '2', '6', '小教室', '0', 'yuto2018', '2020-02-27 13:10:42');
INSERT INTO `classroom` VALUES ('16', '教室5', '2', '6', '小教室', '0', 'yuto2018', '2020-02-27 13:10:54');
INSERT INTO `classroom` VALUES ('17', '教室7', '2', '6', '小教室', '0', 'yuto2018', '2020-02-27 13:11:07');
INSERT INTO `classroom` VALUES ('18', '教室6', '2', '6', '小教室', '0', 'yuto2018', '2020-02-27 13:11:21');
INSERT INTO `classroom` VALUES ('19', '教室8', '2', '6', '小教室', '0', 'yuto2018', '2020-02-27 13:11:43');
INSERT INTO `classroom` VALUES ('20', '教室9', '2', '6', '小教室', '0', 'yuto2018', '2020-02-27 13:11:56');
INSERT INTO `classroom` VALUES ('21', '教室10', '2', '12', '中教室', '0', 'yuto2018', '2020-02-27 13:12:12');
INSERT INTO `classroom` VALUES ('22', '教室一', '3', '12', '大教室', '1', 'erkf2002', '2020-05-29 10:10:30');
INSERT INTO `classroom` VALUES ('23', '教室二', '3', '6', '小教室', '1', 'erkf2002', '2020-05-29 10:10:49');
INSERT INTO `classroom` VALUES ('24', '教室三', '3', '4', '小教室', '1', 'erkf2002', '2020-05-29 10:11:56');
INSERT INTO `classroom` VALUES ('25', '教室四', '3', '4', '小教室', '1', 'erkf2002', '2020-05-29 10:12:20');
INSERT INTO `classroom` VALUES ('26', '教室五', '3', '8', '中教室', '1', 'erkf2002', '2020-05-29 10:12:43');
INSERT INTO `classroom` VALUES ('27', '教室六', '3', '4', '小教室', '1', 'erkf2002', '2020-05-29 10:13:08');
INSERT INTO `classroom` VALUES ('28', '教室七', '3', '8', '中教室', '1', 'erkf2002', '2020-05-29 10:13:28');
INSERT INTO `classroom` VALUES ('29', '教室八', '3', '8', '中教室', '1', 'erkf2002', '2020-05-29 10:13:47');

-- ----------------------------
-- Table structure for `contract`
-- ----------------------------
DROP TABLE IF EXISTS `contract`;
CREATE TABLE `contract` (
  `contract_id` char(12) NOT NULL COMMENT '购课合同id',
  `contract_department` int(10) unsigned NOT NULL COMMENT '购课合同校区',
  `contract_student` char(10) NOT NULL COMMENT '学生id',
  `contract_course_num` int(10) unsigned NOT NULL COMMENT '课程数量',
  `contract_original_hour` decimal(10,1) unsigned NOT NULL COMMENT '购买课时总数',
  `contract_free_hour` decimal(10,1) unsigned NOT NULL COMMENT '赠送课时总数',
  `contract_total_hour` decimal(10,1) unsigned NOT NULL COMMENT '合计课时总数',
  `contract_original_price` decimal(10,2) NOT NULL COMMENT '购课合同原金额',
  `contract_discount_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '共计优惠',
  `contract_total_price` decimal(10,2) NOT NULL COMMENT '购课合同实付金额',
  `contract_date` date NOT NULL COMMENT '购课合同日期',
  `contract_payment_method` varchar(5) NOT NULL COMMENT '购课付款方式',
  `contract_remark` varchar(255) NOT NULL COMMENT '购课合同备注',
  `contract_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '签约类型(0：首次签约，1：续约)',
  `contract_extra_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '额外费用(手续费)',
  `contract_checked` tinyint(4) NOT NULL DEFAULT '0' COMMENT '购课合同复核状态(0：未复核，1：已复核)',
  `contract_checked_user` char(8) NOT NULL DEFAULT '' COMMENT '购课合同复核用户',
  `contract_createuser` char(8) NOT NULL COMMENT '添加用户',
  `contract_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `contract_section` int(10) unsigned NOT NULL COMMENT '签约部门(0：招生部门，1：运营部门)',
  `contract_paid_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购课合同实付金额',
  PRIMARY KEY (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contract
-- ----------------------------

-- ----------------------------
-- Table structure for `contract_course`
-- ----------------------------
DROP TABLE IF EXISTS `contract_course`;
CREATE TABLE `contract_course` (
  `contract_course_contract` char(12) NOT NULL COMMENT '购课合同id',
  `contract_course_course` int(10) unsigned NOT NULL COMMENT '课程id',
  `contract_course_original_hour` decimal(10,1) unsigned NOT NULL COMMENT '购买课时数量',
  `contract_course_free_hour` decimal(10,1) unsigned NOT NULL COMMENT '赠送课时数量',
  `contract_course_total_hour` decimal(10,1) unsigned NOT NULL COMMENT '合计课时数量',
  `contract_course_discount_rate` decimal(4,2) NOT NULL DEFAULT '1.00' COMMENT '折扣优惠',
  `contract_course_discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额优惠',
  `contract_course_discount_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '共计优惠',
  `contract_course_original_unit_price` decimal(10,2) NOT NULL COMMENT '课程原单价',
  `contract_course_actual_unit_price` decimal(10,2) NOT NULL COMMENT '课程现单价',
  `contract_course_original_price` decimal(10,2) NOT NULL COMMENT '购课原金额',
  `contract_course_total_price` decimal(10,2) NOT NULL COMMENT '购课实付金额',
  `contract_course_createuser` char(8) NOT NULL COMMENT '添加用户',
  `contract_course_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`contract_course_contract`,`contract_course_course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contract_course
-- ----------------------------

-- ----------------------------
-- Table structure for `course`
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `course_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程id',
  `course_name` varchar(20) NOT NULL COMMENT '课程名称',
  `course_department` int(10) unsigned NOT NULL COMMENT '课程校区',
  `course_quarter` char(2) NOT NULL COMMENT '课程季度',
  `course_grade` int(10) unsigned NOT NULL COMMENT '课程年级',
  `course_subject` int(10) unsigned NOT NULL COMMENT '课程科目',
  `course_type` varchar(10) NOT NULL COMMENT '课程分类',
  `course_unit_price` decimal(10,2) NOT NULL COMMENT '课程单价',
  `course_time` int(10) unsigned NOT NULL COMMENT '课程时间',
  `course_remark` varchar(255) NOT NULL DEFAULT '无' COMMENT '课程备注',
  `course_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '课程可用状态(0：删除，1：可用)',
  `course_createuser` char(8) NOT NULL COMMENT '课程创建用户',
  `course_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程创建时间',
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_name` (`course_name`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES ('2', '五年级1V1', '0', '全年', '5', '0', '一对一', '165.00', '40', '0', '1', 'yuto2018', '2020-01-03 10:55:32');
INSERT INTO `course` VALUES ('3', '四年级1V1', '0', '全年', '4', '0', '一对一', '160.00', '40', '0', '1', 'yuto2018', '2020-01-03 10:55:53');
INSERT INTO `course` VALUES ('4', '六年级1V1', '0', '全年', '6', '0', '一对一', '175.00', '40', '', '1', 'yuto2018', '2020-02-06 17:34:00');
INSERT INTO `course` VALUES ('5', '七年级1V1', '0', '全年', '7', '0', '一对一', '180.00', '40', '', '1', 'yuto2018', '2020-02-06 17:34:52');
INSERT INTO `course` VALUES ('6', '八年级1V1', '0', '全年', '8', '0', '一对一', '190.00', '40', '', '1', 'yuto2018', '2020-02-06 17:35:29');
INSERT INTO `course` VALUES ('7', '九年级1V1', '0', '全年', '9', '0', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-02-06 17:36:02');
INSERT INTO `course` VALUES ('8', '高一1V1', '0', '全年', '10', '0', '一对一', '220.00', '40', '', '1', 'yuto2018', '2020-02-06 17:36:34');
INSERT INTO `course` VALUES ('9', '高二1V1', '0', '全年', '11', '0', '一对一', '230.00', '40', '', '1', 'yuto2018', '2020-02-06 17:37:05');
INSERT INTO `course` VALUES ('10', '高三1V1', '0', '全年', '12', '0', '一对一', '250.00', '40', '', '1', 'yuto2018', '2020-02-06 17:38:11');
INSERT INTO `course` VALUES ('11', '三年级1V6', '0', '全年', '3', '0', '一对六', '85.00', '40', '', '1', 'yuto2018', '2020-02-06 17:39:12');
INSERT INTO `course` VALUES ('12', '四年级1V6', '0', '全年', '4', '0', '一对六', '90.00', '40', '', '1', 'yuto2018', '2020-02-06 17:41:43');
INSERT INTO `course` VALUES ('13', '五年级1V6', '0', '全年', '5', '0', '一对六', '100.00', '40', '', '1', 'yuto2018', '2020-02-06 17:42:33');
INSERT INTO `course` VALUES ('14', '六年级1V6', '0', '全年', '6', '0', '一对六', '100.00', '40', '', '1', 'yuto2018', '2020-02-06 17:43:08');
INSERT INTO `course` VALUES ('15', '七年级1V6', '0', '全年', '7', '0', '一对六', '105.00', '40', '', '1', 'yuto2018', '2020-02-06 17:43:37');
INSERT INTO `course` VALUES ('16', '八年级1V6', '0', '全年', '8', '0', '一对六', '110.00', '40', '', '1', 'yuto2018', '2020-02-06 17:44:05');
INSERT INTO `course` VALUES ('17', '九年级1V6', '0', '全年', '9', '0', '一对六', '120.00', '40', '', '1', 'yuto2018', '2020-02-06 17:44:43');
INSERT INTO `course` VALUES ('18', '高一1V3', '0', '全年', '10', '0', '一对六', '150.00', '40', '', '1', 'yuto2018', '2020-02-06 17:49:05');
INSERT INTO `course` VALUES ('19', '高二1V3', '0', '全年', '11', '0', '一对六', '165.00', '40', '', '1', 'yuto2018', '2020-02-06 17:49:46');
INSERT INTO `course` VALUES ('20', '高三1V3', '0', '全年', '12', '0', '一对六', '180.00', '40', '', '1', 'yuto2018', '2020-02-06 17:50:21');
INSERT INTO `course` VALUES ('21', '三年级189体验课', '0', '全年', '3', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-02-25 16:42:00');
INSERT INTO `course` VALUES ('23', '四年级189体验课', '0', '全年', '4', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-02-25 16:43:00');
INSERT INTO `course` VALUES ('24', '五年级189体验课', '0', '全年', '5', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-02-25 16:43:42');
INSERT INTO `course` VALUES ('25', '六年级189体验课', '0', '全年', '6', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-02-25 16:44:08');
INSERT INTO `course` VALUES ('26', '七年级189体验课', '0', '全年', '7', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-02-25 16:44:33');
INSERT INTO `course` VALUES ('27', '八年级189体验课', '0', '全年', '8', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-02-25 16:45:04');
INSERT INTO `course` VALUES ('28', '九年级189体验课', '0', '全年', '9', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-02-25 16:45:27');
INSERT INTO `course` VALUES ('29', '八年级699理化活动课', '0', '全年', '8', '0', '班课', '35.00', '40', '', '1', 'yuto2018', '2020-03-20 15:36:38');
INSERT INTO `course` VALUES ('32', '九年级699理化活动课', '0', '全年', '9', '0', '班课', '35.00', '40', '', '1', 'yuto2018', '2020-03-20 15:38:28');
INSERT INTO `course` VALUES ('33', '七年级699活动课', '0', '全年', '7', '0', '班课', '35.00', '40', '', '1', 'yuto2018', '2020-03-20 17:16:50');
INSERT INTO `course` VALUES ('34', '二年级1V1', '0', '全年', '2', '0', '一对一', '155.00', '40', '', '1', 'yuto2018', '2020-03-21 11:26:04');
INSERT INTO `course` VALUES ('35', '二年级1V6', '0', '全年', '2', '0', '一对六', '85.00', '40', '', '1', 'yuto2018', '2020-03-21 11:26:57');
INSERT INTO `course` VALUES ('36', '三年级1V3', '0', '全年', '3', '0', '一对六', '120.00', '40', '', '0', 'yuto2018', '2020-03-21 11:34:36');
INSERT INTO `course` VALUES ('38', '高一189体验课', '0', '全年', '10', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-04-08 13:36:35');
INSERT INTO `course` VALUES ('39', '高二189体验课', '0', '全年', '11', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-04-08 13:37:10');
INSERT INTO `course` VALUES ('40', '高三189体验课', '0', '全年', '12', '0', '班课', '63.00', '40', '', '1', 'yuto2018', '2020-04-08 13:37:37');
INSERT INTO `course` VALUES ('41', '三年级语文6999', '0', '全年', '3', '0', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:40:26');
INSERT INTO `course` VALUES ('42', '四年级语文6999', '0', '全年', '4', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:41:47');
INSERT INTO `course` VALUES ('43', '五年级语文6999', '0', '全年', '5', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:43:20');
INSERT INTO `course` VALUES ('44', '六年级语文6999', '0', '全年', '6', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:43:47');
INSERT INTO `course` VALUES ('45', '初一语文6999', '0', '全年', '7', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:44:16');
INSERT INTO `course` VALUES ('46', '初二语文6999', '0', '全年', '8', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:45:08');
INSERT INTO `course` VALUES ('47', '初三语文6999', '0', '全年', '9', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:45:44');
INSERT INTO `course` VALUES ('48', '高一语文6999', '0', '全年', '10', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:46:35');
INSERT INTO `course` VALUES ('49', '高二语文6999', '0', '全年', '11', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:47:01');
INSERT INTO `course` VALUES ('50', '高三语文6999', '0', '全年', '12', '1', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:47:28');
INSERT INTO `course` VALUES ('51', '高三数学6999', '0', '全年', '12', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:48:12');
INSERT INTO `course` VALUES ('52', '高三英语6999', '0', '全年', '12', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:50:05');
INSERT INTO `course` VALUES ('53', '高二数学6999', '0', '全年', '11', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:50:50');
INSERT INTO `course` VALUES ('54', '高二英语6999', '0', '全年', '11', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:51:33');
INSERT INTO `course` VALUES ('55', '高一数学6999', '0', '全年', '10', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:51:57');
INSERT INTO `course` VALUES ('56', '高一英语6999', '0', '全年', '10', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:52:22');
INSERT INTO `course` VALUES ('57', '初三数学6999', '0', '全年', '9', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:54:06');
INSERT INTO `course` VALUES ('58', '初三英语6999', '0', '全年', '9', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:54:37');
INSERT INTO `course` VALUES ('59', '初三物理6999', '0', '全年', '9', '4', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:55:07');
INSERT INTO `course` VALUES ('60', '初三化学6999', '0', '全年', '9', '5', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:55:33');
INSERT INTO `course` VALUES ('61', '初二数学6999', '0', '全年', '8', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:56:27');
INSERT INTO `course` VALUES ('62', '初二英语6999', '0', '全年', '8', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:56:55');
INSERT INTO `course` VALUES ('63', '初二物理6999', '0', '全年', '8', '4', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:57:22');
INSERT INTO `course` VALUES ('64', '初一数学6999', '0', '全年', '7', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 13:59:58');
INSERT INTO `course` VALUES ('65', '初一英语6999', '0', '全年', '7', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:00:21');
INSERT INTO `course` VALUES ('66', '预备班数学6999', '0', '全年', '6', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:00:47');
INSERT INTO `course` VALUES ('67', '预备班英语6999', '0', '全年', '6', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:01:10');
INSERT INTO `course` VALUES ('68', '五年级数学6999', '0', '全年', '5', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:01:40');
INSERT INTO `course` VALUES ('69', '五年级英语6999', '0', '全年', '5', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:02:03');
INSERT INTO `course` VALUES ('70', '四年级数学6999', '0', '全年', '4', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:03:02');
INSERT INTO `course` VALUES ('71', '四年级英语6999', '0', '全年', '4', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:03:32');
INSERT INTO `course` VALUES ('72', '三年级数学6999', '0', '全年', '3', '2', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:04:01');
INSERT INTO `course` VALUES ('73', '三年级英语6999', '0', '全年', '3', '3', '一对一', '200.00', '40', '', '1', 'yuto2018', '2020-04-08 14:04:31');
INSERT INTO `course` VALUES ('76', '六年级1V3', '1', '全年', '6', '0', '班课', '125.00', '40', '', '1', 'yuto2018', '2020-04-14 18:19:35');
INSERT INTO `course` VALUES ('78', '五年级1v3', '0', '全年', '5', '0', '班课', '120.00', '40', '', '1', 'yuto2018', '2020-04-14 18:27:53');
INSERT INTO `course` VALUES ('79', '九年级1V3', '0', '全年', '9', '0', '班课', '140.00', '40', '', '1', 'yuto2018', '2020-04-14 18:28:43');
INSERT INTO `course` VALUES ('80', '新八699物理（预学6）', '3', '暑假', '6', '4', '班课', '30.00', '90', '物理大班课，12次课', '1', 'yuto2018', '2020-05-20 13:25:54');
INSERT INTO `course` VALUES ('81', '新八物理', '3', '暑假', '7', '4', '班课', '30.00', '90', '', '1', 'yuto2018', '2020-05-20 13:32:46');
INSERT INTO `course` VALUES ('82', '暑-小升初衔接课', '3', '暑假', '5', '0', '班课', '83.00', '90', '', '1', 'yuto2018', '2020-06-05 16:48:33');
INSERT INTO `course` VALUES ('83', '暑假奥数班', '3', '暑假', '2', '2', '班课', '80.00', '90', '小学阶段通用价格', '1', 'wwwp2002', '2020-06-14 10:33:01');
INSERT INTO `course` VALUES ('84', '数学699活动课', '3', '暑假', '4', '2', '班课', '44.00', '90', '', '1', 'eafc2004', '2020-06-14 10:33:14');
INSERT INTO `course` VALUES ('85', '三年级语文699活动', '3', '暑假', '3', '1', '班课', '44.00', '90', '', '1', 'eafc2004', '2020-06-14 16:32:18');
INSERT INTO `course` VALUES ('87', '三年级暑假奥数班', '3', '暑假', '3', '2', '班课', '80.00', '90', '', '1', 'eafc2004', '2020-06-17 15:26:09');
INSERT INTO `course` VALUES ('89', '四年级语文699活动课', '3', '暑假', '4', '1', '班课', '44.00', '90', '', '1', 'eafc2004', '2020-06-26 13:36:06');

-- ----------------------------
-- Table structure for `course_type`
-- ----------------------------
DROP TABLE IF EXISTS `course_type`;
CREATE TABLE `course_type` (
  `course_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程类型id',
  `course_type_name` varchar(10) NOT NULL COMMENT '课程类型名称',
  `course_type_icon_path` varchar(40) NOT NULL COMMENT '课程类型图标路径',
  `course_type_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '课程类型可用状态(0：删除，1：可用)',
  `course_type_createuser` char(8) NOT NULL COMMENT '课程类型创建用户',
  `course_typecreatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程类型创建时间',
  PRIMARY KEY (`course_type_id`),
  UNIQUE KEY `course_type_name` (`course_type_name`),
  UNIQUE KEY `course_type_icon_path` (`course_type_icon_path`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course_type
-- ----------------------------
INSERT INTO `course_type` VALUES ('1', '一对一', '/img/icons/class_types/course_type_1.png', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `course_type` VALUES ('2', '一对六', '/img/icons/class_types/course_type_2.png', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `course_type` VALUES ('3', '班课', '/img/icons/class_types/course_type_3.png', '1', 'yuto2018', '2020-01-02 10:58:04');

-- ----------------------------
-- Table structure for `department`
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `department_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '校区id',
  `department_name` varchar(5) NOT NULL COMMENT '校区名称',
  `department_phone1` varchar(11) NOT NULL DEFAULT '' COMMENT '校区电话1',
  `department_phone2` varchar(11) NOT NULL DEFAULT '' COMMENT '校区电话2',
  `department_location` varchar(30) NOT NULL COMMENT '校区地址',
  `department_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '校区可用状态(0：删除，1：可用)(0：删除，1：可用)',
  `department_createuser` char(8) NOT NULL COMMENT '校区创建用户',
  `department_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '校区创建时间',
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `department_name` (`department_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('1', '惠南一校', '02158011172', '18817598931', '上海市惠南镇城南路1号博佳大楼3楼', '0', 'yuto2018', '2020-01-02 10:45:29');
INSERT INTO `department` VALUES ('2', '惠南二校', '02158011172', '18817598931', '上海市惠南镇拱极路3031号4楼', '0', 'yuto2018', '2020-01-02 10:46:30');
INSERT INTO `department` VALUES ('3', '周浦校区', '02158075681', '18017764285', '周浦镇关岳西路136弄君领国际306室', '1', 'yuto2018', '2020-02-06 16:55:38');
INSERT INTO `department` VALUES ('4', '育藤总部', '02158011172', '18817598931', '上海市拱极路3031号4楼', '0', 'yuto2018', '2020-02-23 20:29:08');
INSERT INTO `department` VALUES ('5', '乔思学堂', '02112345678', '', '周浦镇康沈路1997号64幢3楼', '1', 'yuto2018', '2020-03-11 18:30:39');

-- ----------------------------
-- Table structure for `document`
-- ----------------------------
DROP TABLE IF EXISTS `document`;
CREATE TABLE `document` (
  `document_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '教案id',
  `document_name` varchar(30) NOT NULL COMMENT '教案名称',
  `document_department` int(10) unsigned NOT NULL COMMENT '教案校区',
  `document_subject` int(10) unsigned NOT NULL COMMENT '教案科目',
  `document_grade` int(10) unsigned NOT NULL COMMENT '教案年级',
  `document_semester` varchar(5) NOT NULL COMMENT '教案学期',
  `document_file_name` varchar(255) NOT NULL COMMENT '教案文件名',
  `document_file_size` decimal(4,2) NOT NULL COMMENT '教案文件大小',
  `document_path` varchar(40) NOT NULL COMMENT '教案路径',
  `document_createuser` char(8) NOT NULL COMMENT '教案创建用户',
  `document_download_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教案下载次数',
  `document_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '教案创建时间',
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of document
-- ----------------------------

-- ----------------------------
-- Table structure for `grade`
-- ----------------------------
DROP TABLE IF EXISTS `grade`;
CREATE TABLE `grade` (
  `grade_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '年级id',
  `grade_name` varchar(255) NOT NULL COMMENT '年级名称',
  `grade_type` varchar(2) NOT NULL COMMENT '年级类型',
  `grade_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '年级可用状态(0：删除，1：可用)',
  `grade_createuser` char(8) NOT NULL COMMENT '年级创建用户',
  `grade_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '年级创建时间',
  PRIMARY KEY (`grade_id`),
  UNIQUE KEY `grade_name` (`grade_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of grade
-- ----------------------------
INSERT INTO `grade` VALUES ('1', '小一', '小学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('2', '小二', '小学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('3', '小三', '小学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('4', '小四', '小学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('5', '小五', '小学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('6', '预备', '初中', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('7', '初一', '初中', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('8', '初二', '初中', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('9', '初三', '初中', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('10', '高一', '高中', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('11', '高二', '高中', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('12', '高三', '高中', '1', 'yuto2018', '2020-01-02 10:58:04');

-- ----------------------------
-- Table structure for `hour`
-- ----------------------------
DROP TABLE IF EXISTS `hour`;
CREATE TABLE `hour` (
  `hour_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `hour_course` int(10) unsigned NOT NULL COMMENT '课程id',
  `hour_remain` decimal(10,1) unsigned NOT NULL COMMENT '剩余课时',
  `hour_used` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '已用课时',
  `hour_cleaned` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '已清理课时',
  `hour_average_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '平均单价',
  PRIMARY KEY (`hour_student`,`hour_course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hour
-- ----------------------------

-- ----------------------------
-- Table structure for `hour_cleaned_record`
-- ----------------------------
DROP TABLE IF EXISTS `hour_cleaned_record`;
CREATE TABLE `hour_cleaned_record` (
  `hour_cleaned_record_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '清理课时记录id',
  `hour_cleaned_record_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `hour_cleaned_record_course` int(10) unsigned NOT NULL COMMENT '课程id',
  `hour_cleaned_record_amount` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '扣除课时',
  `hour_cleaned_record_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '清理备注',
  `hour_cleaned_record_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加用户',
  `hour_cleaned_record_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`hour_cleaned_record_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of hour_cleaned_record
-- ----------------------------

-- ----------------------------
-- Table structure for `member`
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_class` char(10) NOT NULL COMMENT '班级id',
  `member_student` char(10) NOT NULL COMMENT '学生id',
  `member_course` char(10) NOT NULL COMMENT '课程id',
  `member_createuser` char(8) NOT NULL COMMENT '添加用户',
  `member_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`member_class`,`member_student`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of member
-- ----------------------------

-- ----------------------------
-- Table structure for `page`
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `page_id` varchar(40) NOT NULL COMMENT '页面id',
  `page_name` varchar(15) NOT NULL COMMENT '页面名称',
  `page_category` varchar(10) NOT NULL DEFAULT '公司管理' COMMENT '页面类别',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of page
-- ----------------------------
INSERT INTO `page` VALUES ('companyClassroom', '公司管理-教室设置', '公司管理');
INSERT INTO `page` VALUES ('companyCourse', '公司管理-课程设置', '公司管理');
INSERT INTO `page` VALUES ('companyDepartment', '公司管理-校区设置', '公司管理');
INSERT INTO `page` VALUES ('companySchool', '公司管理-公立学校', '公司管理');
INSERT INTO `page` VALUES ('companySection', '公司管理-部门架构', '公司管理');
INSERT INTO `page` VALUES ('companyUser', '公司管理-用户管理', '公司管理');
INSERT INTO `page` VALUES ('educationAttendedSchedule', '教学中心-上课记录管理', '教学中心');
INSERT INTO `page` VALUES ('educationClass', '教学中心-班级管理', '教学中心');
INSERT INTO `page` VALUES ('educationDocument', '教学中心-教案中心', '教学中心');
INSERT INTO `page` VALUES ('educationDocumentCreate', '教学中心-上传教案', '教学中心');
INSERT INTO `page` VALUES ('educationMyAttendedSchedule', '教学中心-我的上课记录', '教学中心');
INSERT INTO `page` VALUES ('educationMyClass', '教学中心-我的班级', '教学中心');
INSERT INTO `page` VALUES ('educationMySchedule', '教学中心-我的课程安排', '教学中心');
INSERT INTO `page` VALUES ('educationMyStudent', '教学中心-我的学生', '教学中心');
INSERT INTO `page` VALUES ('educationSchedule', '教学中心-课程安排管理', '教学中心');
INSERT INTO `page` VALUES ('educationStudent', '教学中心-学生管理', '教学中心');
INSERT INTO `page` VALUES ('marketContract', '招生中心-签约管理', '招生中心');
INSERT INTO `page` VALUES ('marketCustomer', '招生中心-客户管理', '招生中心');
INSERT INTO `page` VALUES ('marketMyContract', '招生中心-我的签约', '招生中心');
INSERT INTO `page` VALUES ('marketMyCustomer', '招生中心-我的客户', '招生中心');
INSERT INTO `page` VALUES ('marketMyStudent', '招生中心-我的学生', '招生中心');
INSERT INTO `page` VALUES ('marketStudent', '招生中心-学生管理', '招生中心');
INSERT INTO `page` VALUES ('marketStudentDeleted', '招生中心-离校学生', '招生中心');
INSERT INTO `page` VALUES ('operationAttendedSchedule', '运营中心-上课记录', '运营中心');
INSERT INTO `page` VALUES ('operationClass', '运营中心-班级管理', '运营中心');
INSERT INTO `page` VALUES ('operationContract', '运营中心-签约管理', '运营中心');
INSERT INTO `page` VALUES ('operationHour', '运营中心-学生课时', '运营中心');
INSERT INTO `page` VALUES ('operationMyAttendedSchedule', '运营中心-我的学生上课记录', '运营中心');
INSERT INTO `page` VALUES ('operationMyContract', '运营中心-我的签约', '运营中心');
INSERT INTO `page` VALUES ('operationMyHour', '运营中心-我的学生课时', '运营中心');
INSERT INTO `page` VALUES ('operationMyRefund', '运营中心-我的退费', '运营中心');
INSERT INTO `page` VALUES ('operationMySchedule', '运营中心-我的学生课程安排', '运营中心');
INSERT INTO `page` VALUES ('operationMyStudent', '运营中心-我的学生', '运营中心');
INSERT INTO `page` VALUES ('operationRefund', '运营中心-退费管理', '运营中心');
INSERT INTO `page` VALUES ('operationSchedule', '运营中心-课程安排', '运营中心');
INSERT INTO `page` VALUES ('operationStudent', '运营中心-学生管理', '运营中心');
INSERT INTO `page` VALUES ('operationStudentDeleted', '运营中心-离校学生', '运营中心');

-- ----------------------------
-- Table structure for `participant`
-- ----------------------------
DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
  `participant_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '上课成员记录id',
  `participant_schedule` int(10) unsigned NOT NULL COMMENT '课程安排id',
  `participant_student` char(10) NOT NULL COMMENT '学生成员id',
  `participant_attend_status` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '学生考勤状态(1：正常，2：请假，3：旷课)',
  `participant_course` int(10) unsigned NOT NULL COMMENT '扣除课程id',
  `participant_amount` decimal(10,1) unsigned NOT NULL COMMENT '扣除课程课时数量',
  `participant_checked` tinyint(4) NOT NULL DEFAULT '0' COMMENT '上课记录复核状态(0：待审核，1：已审核)',
  `participant_checked_user` char(8) NOT NULL DEFAULT '' COMMENT '上课记录复核用户',
  `participant_createuser` char(8) NOT NULL COMMENT '课程成员添加用户',
  `participant_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程成员添加时间',
  PRIMARY KEY (`participant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of participant
-- ----------------------------

-- ----------------------------
-- Table structure for `payment_method`
-- ----------------------------
DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE `payment_method` (
  `payment_method_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付方式id',
  `payment_method_name` varchar(5) NOT NULL COMMENT '支付方式名称',
  `payment_method_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '支付方式可用状态(0：删除，1：可用)',
  `payment_method_createuser` char(8) NOT NULL COMMENT '支付方式创建用户',
  `payment_method_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '支付方式创建时间',
  PRIMARY KEY (`payment_method_id`),
  UNIQUE KEY `payment_method_name` (`payment_method_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of payment_method
-- ----------------------------
INSERT INTO `payment_method` VALUES ('1', '现金', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('2', '银行', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('3', '微信', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('4', '支付宝', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('5', '其它', '1', 'yuto2018', '2020-01-02 10:58:04');

-- ----------------------------
-- Table structure for `position`
-- ----------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `position_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '岗位id',
  `position_name` varchar(10) NOT NULL COMMENT '岗位名称',
  `position_section` int(10) unsigned NOT NULL COMMENT '岗位所属部门',
  `position_level` int(10) unsigned NOT NULL COMMENT '岗位等级',
  `position_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '岗位可用状态(0：删除，1：可用)',
  `position_createuser` char(8) NOT NULL COMMENT '岗位创建用户',
  `position_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '岗位创建时间',
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO `position` VALUES ('1', '育藤总经理', '1', '1', '1', 'yuto2018', '2020-01-01 21:06:19');
INSERT INTO `position` VALUES ('2', '咨询经理', '3', '3', '1', 'yuto2018', '2020-02-23 20:18:28');
INSERT INTO `position` VALUES ('3', '运营经理', '3', '3', '1', 'yuto2018', '2020-02-23 20:18:41');
INSERT INTO `position` VALUES ('4', '教学经理', '3', '3', '1', 'yuto2018', '2020-02-23 20:18:55');
INSERT INTO `position` VALUES ('5', '个性化总经理', '1', '2', '1', 'yuto2018', '2020-02-23 20:19:10');
INSERT INTO `position` VALUES ('6', '咨询主管', '3', '6', '1', 'yuto2018', '2020-02-23 20:19:27');
INSERT INTO `position` VALUES ('7', '课程顾问', '3', '8', '1', 'yuto2018', '2020-02-23 20:21:58');
INSERT INTO `position` VALUES ('8', '见习顾问', '3', '8', '1', 'yuto2018', '2020-02-23 20:22:15');
INSERT INTO `position` VALUES ('9', '运营主管', '3', '6', '1', 'yuto2018', '2020-02-23 20:22:37');
INSERT INTO `position` VALUES ('10', '班主任', '3', '8', '1', 'yuto2018', '2020-02-23 20:22:54');
INSERT INTO `position` VALUES ('11', '见习班主任', '3', '9', '1', 'yuto2018', '2020-02-23 20:23:12');
INSERT INTO `position` VALUES ('12', '教学主管', '3', '6', '1', 'yuto2018', '2020-02-23 20:23:35');
INSERT INTO `position` VALUES ('13', '语文老师', '3', '8', '1', 'yuto2018', '2020-02-23 20:23:48');
INSERT INTO `position` VALUES ('14', '数学老师', '3', '8', '1', 'yuto2018', '2020-02-23 20:24:02');
INSERT INTO `position` VALUES ('15', '英语老师', '3', '8', '1', 'yuto2018', '2020-02-23 20:24:17');
INSERT INTO `position` VALUES ('16', '物理老师', '3', '8', '1', 'yuto2018', '2020-02-23 20:24:31');
INSERT INTO `position` VALUES ('17', '化学老师', '3', '8', '1', 'yuto2018', '2020-02-23 20:24:47');
INSERT INTO `position` VALUES ('18', '教研员', '5', '6', '1', 'yuto2018', '2020-02-23 20:25:18');
INSERT INTO `position` VALUES ('19', '人事经理', '1', '3', '1', 'yuto2018', '2020-02-23 20:26:44');
INSERT INTO `position` VALUES ('20', '行政主管', '1', '6', '1', 'yuto2018', '2020-02-23 20:27:01');
INSERT INTO `position` VALUES ('21', '财务专员', '1', '6', '1', 'yuto2018', '2020-02-23 20:27:15');
INSERT INTO `position` VALUES ('22', '惠南大区经理', '1', '2', '0', 'yuto2018', '2020-02-23 20:35:46');
INSERT INTO `position` VALUES ('23', '教研院院长', '5', '3', '1', 'yuto2018', '2020-02-23 20:42:11');
INSERT INTO `position` VALUES ('24', '周浦校长', '3', '3', '1', 'yuto2018', '2020-02-23 20:51:10');
INSERT INTO `position` VALUES ('25', '教务老师', '3', '8', '1', 'yuto2018', '2020-02-23 20:52:17');
INSERT INTO `position` VALUES ('26', '乔思总经理', '7', '2', '1', 'yuto2018', '2020-02-27 13:00:04');

-- ----------------------------
-- Table structure for `refund`
-- ----------------------------
DROP TABLE IF EXISTS `refund`;
CREATE TABLE `refund` (
  `refund_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '退款原因id',
  `refund_student` char(10) NOT NULL COMMENT '学生id',
  `refund_course` int(10) unsigned NOT NULL COMMENT '课程id',
  `refund_remain` decimal(10,1) unsigned NOT NULL COMMENT '退款剩余课时数量',
  `refund_used` decimal(10,1) unsigned NOT NULL COMMENT '退款前已使用课时数量',
  `refund_cleaned` decimal(10,1) unsigned NOT NULL COMMENT '退款前清理课时数量',
  `refund_unit_price` decimal(10,2) NOT NULL COMMENT '购课原单价',
  `refund_amount` decimal(10,2) NOT NULL COMMENT '实际退款金额',
  `refund_reason` varchar(10) NOT NULL COMMENT '退费原因',
  `refund_payment_method` varchar(5) NOT NULL COMMENT '退费付款方式',
  `refund_date` date NOT NULL COMMENT '退费日期',
  `refund_remark` varchar(255) NOT NULL COMMENT '退费备注',
  `refund_checked` tinyint(4) NOT NULL DEFAULT '0' COMMENT '退费审核状态(0：未审核，1：已审核)',
  `refund_checked_user` char(8) NOT NULL DEFAULT '' COMMENT '退费复核用户',
  `refund_createuser` char(8) NOT NULL COMMENT '添加用户',
  `refund_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`refund_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of refund
-- ----------------------------

-- ----------------------------
-- Table structure for `refund_reason`
-- ----------------------------
DROP TABLE IF EXISTS `refund_reason`;
CREATE TABLE `refund_reason` (
  `refund_reason_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '退款原因id',
  `refund_reason_name` varchar(10) NOT NULL COMMENT '退款原因名称',
  `refund_reason_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '退款原因可用状态(0：删除，1：可用)',
  `refund_reason_createuser` char(8) NOT NULL COMMENT '退款原因创建用户',
  `refund_reason_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '退款原因创建时间',
  PRIMARY KEY (`refund_reason_id`),
  UNIQUE KEY `refund_reason_name` (`refund_reason_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of refund_reason
-- ----------------------------
INSERT INTO `refund_reason` VALUES ('1', '教学质量', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('2', '学生转课', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('3', '学生转学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('4', '购课过多', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('5', '收费价格', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('6', '学生纪律', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('7', '其它原因', '1', 'yuto2018', '2020-01-02 10:58:04');

-- ----------------------------
-- Table structure for `schedule`
-- ----------------------------
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `schedule_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程安排id',
  `schedule_department` int(10) unsigned NOT NULL COMMENT '课程安排校区',
  `schedule_participant` char(10) NOT NULL COMMENT '学生或班级id',
  `schedule_participant_type` tinyint(4) NOT NULL COMMENT '上课成员类型(0：学生，1：班级)',
  `schedule_teacher` char(8) NOT NULL COMMENT '教师id',
  `schedule_course` int(10) unsigned NOT NULL COMMENT '课程id',
  `schedule_subject` int(10) unsigned NOT NULL COMMENT '课程科目',
  `schedule_grade` int(10) unsigned NOT NULL COMMENT '课程年级',
  `schedule_classroom` int(10) unsigned NOT NULL COMMENT '课程教室',
  `schedule_date` date NOT NULL COMMENT '课程安排日期',
  `schedule_start` time NOT NULL COMMENT '课程安排上课时间',
  `schedule_end` time NOT NULL COMMENT '课程安排下课时间',
  `schedule_time` int(10) unsigned NOT NULL COMMENT '课程时长',
  `schedule_student_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '课程人数',
  `schedule_attended_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '正常上课人数',
  `schedule_leave_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '请假人数',
  `schedule_absence_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '旷课人数',
  `schedule_attended` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课程安排考勤状态(0：待考勤，1：已上课)',
  `schedule_attended_user` char(8) NOT NULL DEFAULT '' COMMENT '课程安排考勤用户',
  `schedule_createuser` char(8) NOT NULL COMMENT '课程安排添加用户',
  `schedule_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程安排添加时间',
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1576 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of schedule
-- ----------------------------

-- ----------------------------
-- Table structure for `school`
-- ----------------------------
DROP TABLE IF EXISTS `school`;
CREATE TABLE `school` (
  `school_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '学校id',
  `school_name` varchar(10) NOT NULL COMMENT '学校名称',
  `school_department` int(10) unsigned NOT NULL COMMENT '学校校区',
  `school_location` varchar(40) NOT NULL COMMENT '学校地址',
  `school_type` varchar(5) NOT NULL COMMENT '学校类型',
  `school_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '学校可用状态(0：删除，1：可用)',
  `school_createuser` char(8) NOT NULL COMMENT '学校创建用户',
  `school_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '学校创建时间',
  PRIMARY KEY (`school_id`),
  UNIQUE KEY `school_name` (`school_name`),
  UNIQUE KEY `school_location` (`school_location`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of school
-- ----------------------------
INSERT INTO `school` VALUES ('1', '惠南大区', '1', '123', '其它', '0', 'yuto2018', '2020-02-27 17:52:26');
INSERT INTO `school` VALUES ('2', '周浦大区', '5', '321', '其它', '1', 'yuto2018', '2020-02-27 17:52:44');

-- ----------------------------
-- Table structure for `section`
-- ----------------------------
DROP TABLE IF EXISTS `section`;
CREATE TABLE `section` (
  `section_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门id',
  `section_name` varchar(10) NOT NULL COMMENT '部门名称',
  `section_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '部门可用状态(0：删除，1：可用)',
  `section_createuser` char(8) NOT NULL COMMENT '部门创建用户',
  `section_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '部门创建时间',
  PRIMARY KEY (`section_id`),
  UNIQUE KEY `section_name` (`section_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of section
-- ----------------------------
INSERT INTO `section` VALUES ('1', '总经办', '1', 'yuto2018', '2020-01-01 21:06:19');
INSERT INTO `section` VALUES ('2', '个性化咨询部', '0', 'yuto2018', '2020-02-23 20:08:02');
INSERT INTO `section` VALUES ('3', '个性化事业部', '1', 'yuto2018', '2020-02-23 20:08:13');
INSERT INTO `section` VALUES ('4', '个性化教学部', '0', 'yuto2018', '2020-02-23 20:08:32');
INSERT INTO `section` VALUES ('5', '育藤教研院', '1', 'yuto2018', '2020-02-23 20:08:40');
INSERT INTO `section` VALUES ('6', '班课事业部', '1', 'yuto2018', '2020-02-23 20:09:09');
INSERT INTO `section` VALUES ('7', '育藤-乔思学堂', '1', 'yuto2018', '2020-02-23 20:09:34');
INSERT INTO `section` VALUES ('8', '周浦校区', '0', 'yuto2018', '2020-02-23 20:50:50');

-- ----------------------------
-- Table structure for `source`
-- ----------------------------
DROP TABLE IF EXISTS `source`;
CREATE TABLE `source` (
  `source_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '来源id',
  `source_name` varchar(10) NOT NULL COMMENT '来源名称',
  `source_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '来源可用状态(0：删除，1：可用)',
  `source_createuser` char(8) NOT NULL COMMENT '来源创建用户',
  `source_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '来源创建时间',
  PRIMARY KEY (`source_id`),
  UNIQUE KEY `source_name` (`source_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of source
-- ----------------------------
INSERT INTO `source` VALUES ('1', '学生转介绍', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('2', '客户转介绍', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('3', '员工转介绍', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('4', '短信', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('5', '广告', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('6', '传单', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('7', '网络', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('8', '地推', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('9', '其它', '1', 'yuto2018', '2020-01-02 10:58:04');

-- ----------------------------
-- Table structure for `student`
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `student_id` char(10) NOT NULL COMMENT '学生id',
  `student_name` varchar(5) NOT NULL COMMENT '学生姓名',
  `student_department` int(10) unsigned NOT NULL COMMENT '学生校区',
  `student_grade` int(10) unsigned NOT NULL COMMENT '学生年级',
  `student_gender` char(1) NOT NULL DEFAULT '男' COMMENT '学生性别',
  `student_birthday` date NOT NULL COMMENT '学生生日',
  `student_school` int(10) unsigned NOT NULL COMMENT '学生学校',
  `student_guardian` varchar(5) NOT NULL COMMENT '学生监护人姓名',
  `student_guardian_relationship` varchar(5) NOT NULL COMMENT '学生监护人关系',
  `student_phone` char(11) NOT NULL COMMENT '学生电话',
  `student_wechat` varchar(20) NOT NULL COMMENT '学生微信',
  `student_source` varchar(10) NOT NULL COMMENT '学生来源',
  `student_remark` varchar(140) NOT NULL COMMENT '学生备注',
  `student_consultant` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '学生课程顾问',
  `student_class_adviser` char(8) NOT NULL DEFAULT '' COMMENT '学生班主任',
  `student_follow_level` int(10) NOT NULL DEFAULT '1' COMMENT '学生跟进优先级',
  `student_follow_num` int(10) NOT NULL DEFAULT '0' COMMENT '学生跟进次数',
  `student_contract_num` int(10) NOT NULL DEFAULT '0' COMMENT '学生签约次数',
  `student_last_follow_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生上次跟进日期',
  `student_last_contract_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生上次签约日期',
  `student_last_lesson_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生上次上课日期',
  `student_photo` varchar(20) NOT NULL DEFAULT '' COMMENT '学生照片路径',
  `student_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '学生可用状态(0：删除，1：可用)',
  `student_createuser` char(8) NOT NULL COMMENT '学生创建用户',
  `student_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '学生创建时间',
  `student_first_contract_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生首次签约日期',
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `student_name` (`student_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of student
-- ----------------------------
INSERT INTO `student` VALUES ('S200401001', '俞依文', '1', '4', '女', '2020-04-02', '0', '俞某某', '爸爸', '17725834598', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-02', '2000-01-01', '2000-01-01', '', '1', 'zdji2002', '2020-04-02 14:59:38', '2020-04-14');
INSERT INTO `student` VALUES ('S200401002', '程宇航', '1', '9', '男', '2020-04-02', '0', '程某某', '爸爸', '15575892405', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-02', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-02 15:15:33', '2020-04-15');
INSERT INTO `student` VALUES ('S200401003', '黄诗绮', '1', '8', '女', '2020-04-02', '0', '黄某某', '爸爸', '17725898784', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-02', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-02 15:25:25', '2020-04-14');
INSERT INTO `student` VALUES ('S200401004', '杨帆', '1', '10', '男', '2020-04-05', '0', '杨帆爸爸', '爸爸', '15525542525', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-05', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 11:51:55', '2020-04-15');
INSERT INTO `student` VALUES ('S200401005', '陈思可', '1', '7', '女', '2020-04-05', '0', '思可妈妈', '妈妈', '18825497366', '无', '其它', '无', 'zdji2002', 'pfdn2002', '1', '0', '0', '2020-04-05', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 12:11:21', '2020-04-14');
INSERT INTO `student` VALUES ('S200401006', '徐英姿', '1', '9', '女', '2020-04-05', '0', '徐英姿妈妈', '妈妈', '15562544875', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-05', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 12:36:58', '2020-04-14');
INSERT INTO `student` VALUES ('S200401008', '朱加晔', '1', '9', '女', '2020-04-05', '0', '朱加晔妈妈', '妈妈', '13358478775', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-05', '2020-04-17', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 14:06:50', '2020-04-17');
INSERT INTO `student` VALUES ('S200401010', '王思绎', '1', '9', '男', '2020-04-05', '0', '王思绎妈妈', '妈妈', '15587120000', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-05', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 16:09:37', '2020-04-14');
INSERT INTO `student` VALUES ('S200401011', '奚航宇', '1', '9', '男', '2020-04-05', '0', '奚航宇妈妈', '妈妈', '12235986598', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-05', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 16:14:06', '2020-04-14');
INSERT INTO `student` VALUES ('S200401012', '徐敏佳', '1', '6', '男', '2020-04-05', '0', '徐敏佳妈妈', '妈妈', '18855465746', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-05', '2020-04-12', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 16:28:39', '2020-04-12');
INSERT INTO `student` VALUES ('S200401013', '徐心怡', '1', '7', '女', '2020-04-05', '0', '徐心怡妈妈', '妈妈', '17785875445', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 16:41:46', '2020-04-05');
INSERT INTO `student` VALUES ('S200401014', '苏熠坤', '1', '10', '男', '2020-04-05', '0', '苏熠坤爸爸', '爸爸', '16656465567', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-05', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 16:44:11', '2020-04-15');
INSERT INTO `student` VALUES ('S200401016', '黄琴', '1', '11', '女', '2020-04-05', '0', '黄琴妈妈', '妈妈', '18217192069', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-05', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 17:12:27', '2020-04-15');
INSERT INTO `student` VALUES ('S200401017', '金盈盈', '1', '10', '女', '2020-04-05', '0', '金盈盈爸爸', '爸爸', '17785544547', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-05', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-05 17:14:46', '2020-04-15');
INSERT INTO `student` VALUES ('S200401018', '郭韬', '1', '6', '男', '2020-04-08', '0', '郭韬爸爸', '爸爸', '15525487963', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-08', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-08 11:59:47', '2020-04-14');
INSERT INTO `student` VALUES ('S200401019', '周秉文', '1', '9', '男', '2020-04-08', '0', '周秉文妈妈', '妈妈', '18825647851', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-08', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-08 12:19:02', '2020-04-15');
INSERT INTO `student` VALUES ('S200401021', '张雨欣', '1', '7', '女', '2020-04-08', '0', '张雨欣爸爸', '爸爸', '15584729542', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-08', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-08 12:30:27', '2020-04-14');
INSERT INTO `student` VALUES ('S200401022', '李紫盈', '1', '12', '女', '2020-04-08', '0', '李紫盈爸爸', '爸爸', '17789546213', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-08', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-08 12:34:12', '2020-04-15');
INSERT INTO `student` VALUES ('S200401023', '乔若辉', '1', '10', '男', '2020-04-08', '0', '乔若辉妈妈', '妈妈', '17785421796', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-08', '2020-04-15', '2000-01-01', '', '1', 'zdji2002', '2020-04-08 12:56:59', '2020-04-15');
INSERT INTO `student` VALUES ('S200401024', '瞿晨曦', '1', '5', '男', '2020-04-08', '0', '瞿晨曦妈妈', '妈妈', '18898752483', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-08', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-08 13:48:30', '2020-04-14');
INSERT INTO `student` VALUES ('S200401025', '顾王硕', '1', '4', '男', '2020-04-08', '0', '顾王硕妈妈', '妈妈', '13167042579', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-08', '2020-04-14', '2000-01-01', '', '1', 'zdji2002', '2020-04-08 13:54:15', '2020-04-14');
INSERT INTO `student` VALUES ('S200401026', '李明泽', '1', '6', '女', '2020-04-10', '1', '李明泽妈妈', '妈妈', '13190976572', '无', '其它', '无', 'zdji2002', 'pfdn2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 16:02:13', '2020-04-14');
INSERT INTO `student` VALUES ('S200401027', '储天霖', '1', '10', '男', '2020-04-10', '1', '天霖妈妈', '妈妈', '13818098436', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 16:35:03', '2020-04-15');
INSERT INTO `student` VALUES ('S200401028', '陶紫来', '1', '9', '男', '2020-04-10', '1', '紫来妈妈', '妈妈', '15678909865', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 17:32:47', '2020-04-15');
INSERT INTO `student` VALUES ('S200401029', '王嘉倪', '1', '6', '女', '2020-04-10', '1', '嘉倪妈妈', '妈妈', '15201833770', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 17:41:23', '2020-04-14');
INSERT INTO `student` VALUES ('S200401030', '金吴轩', '1', '3', '女', '2020-04-10', '0', '吴轩妈妈', '妈妈', '13311941451', '无', '其它', '无', 'zdji2002', 'pfdn2002', '1', '0', '0', '2020-04-10', '2020-04-12', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 17:42:39', '2020-04-12');
INSERT INTO `student` VALUES ('S200401031', '黄圆圆', '1', '8', '男', '2020-04-10', '1', '圆圆妈妈', '妈妈', '18049920038', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 17:44:01', '2020-04-14');
INSERT INTO `student` VALUES ('S200401032', '庄晨婷', '1', '9', '女', '2020-04-10', '1', '晨婷妈妈', '妈妈', '17721102624', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 17:46:18', '2020-04-15');
INSERT INTO `student` VALUES ('S200401033', '端汇堰', '1', '8', '男', '2020-04-10', '1', '汇堰妈妈', '妈妈', '18001839209', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 17:57:40', '2020-04-14');
INSERT INTO `student` VALUES ('S200401034', '张维韬', '1', '6', '男', '2020-04-10', '1', '维韬妈妈', '妈妈', '13816006783', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:40:33', '2020-04-14');
INSERT INTO `student` VALUES ('S200401035', '刘铭萱', '1', '6', '女', '2020-04-10', '1', '铭萱妈妈', '妈妈', '13917567697', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-10', '2020-04-17', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:42:33', '2020-04-17');
INSERT INTO `student` VALUES ('S200401036', '王奕涵', '1', '12', '男', '2020-04-10', '1', '奕涵爸爸', '爸爸', '13567890654', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:43:39', '2020-04-15');
INSERT INTO `student` VALUES ('S200401037', '许思伟', '1', '12', '男', '2020-04-10', '1', '思伟爸爸', '爸爸', '13902921231', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:44:42', '2020-04-15');
INSERT INTO `student` VALUES ('S200401038', '奚元夏', '1', '8', '男', '2020-04-10', '1', '元夏妈妈', '妈妈', '13512103886', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:46:28', '2020-04-14');
INSERT INTO `student` VALUES ('S200401039', '唐东恒', '1', '9', '男', '2020-04-10', '1', '东恒妈妈', '妈妈', '15921929716', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:47:38', '2020-04-15');
INSERT INTO `student` VALUES ('S200401040', '张美琳', '1', '9', '女', '2020-04-10', '1', '美琳爸爸', '爸爸', '17720977188', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:48:57', '2020-04-15');
INSERT INTO `student` VALUES ('S200401041', '黄思远', '1', '4', '男', '2020-04-10', '1', '思远妈妈', '妈妈', '13457234321', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:54:21', '2020-04-14');
INSERT INTO `student` VALUES ('S200401042', '鲍宇庭', '1', '12', '男', '2020-04-10', '1', '宇庭妈妈', '妈妈', '13681905994', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:55:33', '2020-04-15');
INSERT INTO `student` VALUES ('S200401043', '徐良宇', '1', '11', '男', '2020-04-10', '1', '良宇爸爸', '爸爸', '13817773151', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:56:48', '2020-04-15');
INSERT INTO `student` VALUES ('S200401044', '高子浩', '1', '6', '男', '2020-04-10', '1', '子浩妈妈', '妈妈', '18621007395', '无', '其它', '无', 'zdji2002', 'pfdn2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:57:51', '2020-04-14');
INSERT INTO `student` VALUES ('S200401045', '徐依雯', '1', '12', '女', '2020-04-10', '1', '依雯妈妈', '妈妈', '13671901450', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 18:59:58', '2020-04-15');
INSERT INTO `student` VALUES ('S200401046', '张宇航', '1', '4', '男', '2020-04-10', '1', '宇航妈妈', '妈妈', '13917268236', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:05:30', '2020-04-14');
INSERT INTO `student` VALUES ('S200401047', '宋臻程', '1', '10', '男', '2020-04-10', '1', '臻程妈妈', '妈妈', '15678908752', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:08:11', '2020-04-15');
INSERT INTO `student` VALUES ('S200401048', '吴浩衍', '1', '4', '男', '2020-04-10', '1', '浩衍妈妈', '妈妈', '17348742222', '无', '其它', '无', 'zdji2002', 'pfdn2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:09:45', '2020-04-14');
INSERT INTO `student` VALUES ('S200401049', '董嘉成', '1', '8', '男', '2020-04-10', '1', '嘉成妈妈', '妈妈', '15021963898', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:21:23', '2020-04-14');
INSERT INTO `student` VALUES ('S200401050', '秦洁莹', '1', '8', '女', '2020-04-10', '1', '洁莹妈妈', '妈妈', '13918655413', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:24:20', '2020-04-14');
INSERT INTO `student` VALUES ('S200401051', '沈逸恒', '1', '8', '男', '2020-04-10', '1', '逸恒妈妈', '妈妈', '13702334343', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:26:44', '2020-04-15');
INSERT INTO `student` VALUES ('S200401052', '陶苏颖', '1', '8', '女', '2020-04-10', '1', '苏颖妈妈', '妈妈', '13311782912', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:27:50', '2020-04-15');
INSERT INTO `student` VALUES ('S200401053', '祝嘉鑫', '1', '8', '女', '2020-04-10', '1', '嘉鑫妈妈', '妈妈', '15821333213', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:29:23', '2020-04-15');
INSERT INTO `student` VALUES ('S200401054', '董茅旭', '1', '8', '男', '2020-04-10', '1', '茅旭妈妈', '妈妈', '18718954542', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:31:42', '2020-04-15');
INSERT INTO `student` VALUES ('S200401055', '刘奕昊', '1', '7', '男', '2020-04-10', '1', '奕昊妈妈', '妈妈', '13501618021', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:32:54', '2020-04-14');
INSERT INTO `student` VALUES ('S200401056', '奚闿祺', '1', '5', '男', '2020-04-10', '1', '闿琪妈妈', '妈妈', '13918478161', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:35:21', '2020-04-14');
INSERT INTO `student` VALUES ('S200401057', '夏梦圆', '1', '5', '女', '2020-04-10', '1', '梦圆妈妈', '妈妈', '13890432123', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:36:50', '2020-04-14');
INSERT INTO `student` VALUES ('S200401058', '闵希璞', '1', '5', '男', '2020-04-10', '1', '希璞妈妈', '妈妈', '15618963031', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:39:03', '2020-04-14');
INSERT INTO `student` VALUES ('S200401059', '苏适', '1', '8', '男', '2020-04-10', '1', '苏适妈妈', '妈妈', '18918355212', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-30', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:40:13', '2020-04-15');
INSERT INTO `student` VALUES ('S200401060', '徐清吟', '1', '8', '女', '2020-04-10', '1', '清吟爸爸', '爸爸', '13671969055', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:41:28', '2020-04-15');
INSERT INTO `student` VALUES ('S200401061', '邬智杰', '1', '8', '男', '2020-04-10', '1', '智杰妈妈', '妈妈', '13120098765', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:42:30', '2020-04-15');
INSERT INTO `student` VALUES ('S200401062', '池凌赟', '1', '8', '男', '2020-04-10', '1', '凌赟妈妈', '妈妈', '15221755325', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:44:00', '2020-04-15');
INSERT INTO `student` VALUES ('S200401063', '蔡唐沁', '1', '8', '女', '2020-04-10', '1', '唐沁妈妈', '妈妈', '13323456732', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:45:18', '2020-04-15');
INSERT INTO `student` VALUES ('S200401064', '项思婕', '1', '7', '女', '2020-04-10', '1', '思婕妈妈', '妈妈', '18908763624', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-10', '2020-04-17', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:46:20', '2020-04-17');
INSERT INTO `student` VALUES ('S200401065', '朱沁怡', '1', '7', '女', '2020-04-10', '1', '沁怡妈妈', '妈妈', '15821705193', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:48:21', '2020-04-15');
INSERT INTO `student` VALUES ('S200401066', '朱欣愉', '1', '7', '女', '2020-04-10', '1', '欣愉妈妈', '妈妈', '123', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:56:40', '2020-04-15');
INSERT INTO `student` VALUES ('S200401067', '方情宇', '1', '7', '男', '2020-04-10', '1', '情宇妈妈', '妈妈', '15021985623', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 19:59:16', '2020-04-15');
INSERT INTO `student` VALUES ('S200401068', '刘朱怡然', '1', '6', '女', '2020-04-10', '1', '怡然妈妈', '妈妈', '13472455634', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:02:49', '2020-04-14');
INSERT INTO `student` VALUES ('S200401069', '孙愉舒', '1', '6', '女', '2020-04-10', '1', '愉舒妈妈', '妈妈', '123', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:04:05', '2020-04-14');
INSERT INTO `student` VALUES ('S200401070', '张晓宇', '1', '5', '男', '2020-04-10', '1', '晓宇妈妈', '妈妈', '123', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:05:17', '2020-04-14');
INSERT INTO `student` VALUES ('S200401071', '金秋宇', '1', '4', '男', '2020-04-10', '1', '秋宇妈妈', '妈妈', '123', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:06:18', '2020-04-14');
INSERT INTO `student` VALUES ('S200401072', '杨雯馨', '1', '4', '女', '2020-04-10', '1', '雯馨妈妈', '妈妈', '18502111328', '无', '其它', '无', 'zdji2002', 'pfdn2002', '1', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:07:51', '2020-04-14');
INSERT INTO `student` VALUES ('S200401073', '张怡静', '1', '9', '女', '2020-04-10', '1', '怡静妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-30', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:08:57', '2020-04-15');
INSERT INTO `student` VALUES ('S200401074', '张宸璐', '1', '9', '女', '2020-04-10', '1', '宸璐妈妈', '妈妈', '13501980631', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:10:46', '2020-04-15');
INSERT INTO `student` VALUES ('S200401075', '周佳雯', '1', '9', '女', '2020-04-10', '1', '佳雯妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:14:20', '2020-04-15');
INSERT INTO `student` VALUES ('S200401076', '张蓓蓓', '1', '9', '女', '2020-04-10', '1', '蓓蓓妈妈', '妈妈', '13818442787', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:18:33', '2020-04-15');
INSERT INTO `student` VALUES ('S200401077', '倪佳乐', '1', '9', '男', '2020-04-10', '1', '佳乐妈妈', '妈妈', '13564725748', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:19:55', '2020-04-15');
INSERT INTO `student` VALUES ('S200401078', '陈晨', '1', '9', '男', '2020-04-10', '1', '陈晨爸爸', '爸爸', '15821655626', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:49:04', '2020-04-15');
INSERT INTO `student` VALUES ('S200401079', '谢佳颖', '1', '9', '女', '2020-04-10', '1', '佳颖妈妈', '妈妈', '18221035742', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:50:04', '2020-04-15');
INSERT INTO `student` VALUES ('S200401080', '倪展艺', '1', '9', '女', '2020-04-10', '1', '展艺妈妈', '妈妈', '13052188558', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:51:05', '2020-04-15');
INSERT INTO `student` VALUES ('S200401081', '宋莹莱', '1', '9', '女', '2020-04-10', '1', '莹莱妈妈', '妈妈', '13052188558', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:54:47', '2020-04-15');
INSERT INTO `student` VALUES ('S200401082', '汪雅婷', '1', '9', '女', '2020-04-10', '1', '雅婷妈妈', '妈妈', '13524376126', '无', '其它', '无', 'zdji2002', 'pfdn2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 20:56:49', '2020-04-15');
INSERT INTO `student` VALUES ('S200401083', '瞿倪轩', '1', '9', '男', '2020-04-10', '1', '倪轩妈妈', '妈妈', '15287878773', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 21:00:36', '2020-04-15');
INSERT INTO `student` VALUES ('S200401084', '徐乐洋', '1', '9', '男', '2020-04-10', '1', '乐洋妈妈', '妈妈', '13578909865', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 21:03:32', '2020-04-15');
INSERT INTO `student` VALUES ('S200401085', '范郁淼', '1', '8', '男', '2020-04-10', '1', '郁淼爸爸', '爸爸', '13120574567', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-10', '2020-04-15', '2000-01-01', '', '1', 'omeo2002', '2020-04-10 21:07:25', '2020-04-15');
INSERT INTO `student` VALUES ('S200401086', '顾婉馨', '1', '8', '女', '2020-04-11', '1', '婉馨妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:24:58', '2020-04-12');
INSERT INTO `student` VALUES ('S200401087', '杨璐菡', '1', '6', '女', '2020-04-11', '1', '璐菡妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:26:23', '2020-04-12');
INSERT INTO `student` VALUES ('S200401088', '陈钰婷', '1', '8', '女', '2020-04-11', '1', '钰婷妈妈', '妈妈', '123', '无', '其它', '无', 'zdji2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-15', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:33:34', '2020-04-15');
INSERT INTO `student` VALUES ('S200401089', '戚佳忆', '1', '8', '女', '2020-04-11', '1', '佳忆妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:35:41', '2020-04-12');
INSERT INTO `student` VALUES ('S200401090', '张笑涛', '1', '6', '男', '2020-04-11', '1', '笑涛爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:37:26', '2020-04-12');
INSERT INTO `student` VALUES ('S200401091', '孙天宇', '1', '5', '男', '2020-04-11', '1', '天宇爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:38:43', '2020-04-11');
INSERT INTO `student` VALUES ('S200401092', '施逸沁', '1', '5', '女', '2020-04-11', '1', '逸沁妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:40:07', '2020-04-11');
INSERT INTO `student` VALUES ('S200401093', '吴陆艾', '1', '5', '女', '2020-04-11', '1', '陆艾妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:41:29', '2020-04-11');
INSERT INTO `student` VALUES ('S200401094', '乔子宸', '1', '5', '男', '2020-04-11', '1', '子宸爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:42:45', '2020-04-12');
INSERT INTO `student` VALUES ('S200401095', '吴米加', '1', '5', '男', '2020-04-11', '1', '米加爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:43:51', '2020-04-12');
INSERT INTO `student` VALUES ('S200401096', '邱震宇', '1', '4', '男', '2020-04-11', '1', '震宇爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:45:04', '2020-04-11');
INSERT INTO `student` VALUES ('S200401097', '徐杨袆', '1', '4', '女', '2020-04-11', '1', '杨袆妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:46:54', '2020-04-11');
INSERT INTO `student` VALUES ('S200401098', '闫墨涵', '1', '4', '女', '2020-04-11', '1', '墨涵妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:48:21', '2020-04-11');
INSERT INTO `student` VALUES ('S200401099', '洪浩哲', '1', '4', '男', '2020-04-11', '1', '浩哲爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:49:22', '2020-04-11');
INSERT INTO `student` VALUES ('S200401100', '朱语墨', '1', '3', '女', '2020-04-11', '1', '语墨妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:51:08', '2020-04-11');
INSERT INTO `student` VALUES ('S200401101', '张诸城', '1', '5', '男', '2020-04-11', '1', '诸城爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:52:13', '2020-04-12');
INSERT INTO `student` VALUES ('S200401102', '谈昊泽', '1', '5', '男', '2020-04-11', '1', '昊泽爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:53:44', '2020-04-12');
INSERT INTO `student` VALUES ('S200401103', '唐毓泽', '1', '5', '男', '2020-04-11', '1', '毓泽爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:55:02', '2020-04-12');
INSERT INTO `student` VALUES ('S200401104', '凌择徐', '1', '5', '男', '2020-04-11', '1', '择徐爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:56:31', '2020-04-12');
INSERT INTO `student` VALUES ('S200401105', '毛浚帆', '1', '5', '男', '2020-04-11', '1', '浚帆爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:58:04', '2020-04-11');
INSERT INTO `student` VALUES ('S200401106', '陈赵佳', '1', '9', '女', '2020-04-11', '1', '赵佳妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 09:59:12', '2020-04-12');
INSERT INTO `student` VALUES ('S200401107', '徐胜', '1', '9', '男', '2020-04-11', '1', '徐胜爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2020-04-13', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:00:24', '2020-04-13');
INSERT INTO `student` VALUES ('S200401108', '董欣颐', '1', '9', '女', '2020-04-11', '1', '欣颐妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:01:24', '2020-04-12');
INSERT INTO `student` VALUES ('S200401109', '周忆炜', '1', '9', '男', '2020-04-11', '1', '忆炜爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2020-04-13', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:03:09', '2020-04-13');
INSERT INTO `student` VALUES ('S200401110', '苏薏婷', '1', '10', '女', '2020-04-11', '1', '薏婷妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-13', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:05:42', '2020-04-13');
INSERT INTO `student` VALUES ('S200401111', '沈亦晨', '1', '6', '男', '2020-04-11', '1', '亦晨爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:07:09', '2020-04-12');
INSERT INTO `student` VALUES ('S200401113', '顾馨', '1', '5', '女', '2020-04-11', '1', '顾馨妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:10:00', '2020-04-11');
INSERT INTO `student` VALUES ('S200401114', '徐祖恩', '1', '4', '男', '2020-04-11', '1', '祖恩爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:11:25', '2020-04-11');
INSERT INTO `student` VALUES ('S200401115', '徐雨晨', '1', '9', '女', '2020-04-11', '1', '雨晨妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:12:42', '2020-04-12');
INSERT INTO `student` VALUES ('S200401116', '朱颜', '1', '9', '女', '2020-04-11', '1', '朱颜妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:17:06', '2020-04-12');
INSERT INTO `student` VALUES ('S200401117', '吴逸婷', '1', '9', '女', '2020-04-11', '1', '逸婷妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:18:21', '2020-04-12');
INSERT INTO `student` VALUES ('S200401118', '柏小', '1', '7', '男', '2020-04-11', '1', '柏小爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'pfdn2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:21:24', '2020-04-14');
INSERT INTO `student` VALUES ('S200401119', '刘雨安梓', '1', '5', '女', '2020-04-11', '1', '安梓妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:23:10', '2020-04-11');
INSERT INTO `student` VALUES ('S200401120', '王薪', '1', '4', '女', '2020-04-11', '1', '王薪妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:24:14', '2000-01-01');
INSERT INTO `student` VALUES ('S200401121', '徐子墨', '1', '3', '男', '2020-04-11', '1', '子墨爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:25:25', '2020-04-11');
INSERT INTO `student` VALUES ('S200401122', '朱宇豪', '1', '8', '男', '2020-04-11', '1', '宇豪爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:26:32', '2020-04-12');
INSERT INTO `student` VALUES ('S200401123', '季天晨', '1', '8', '男', '2020-04-11', '1', '天晨爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:28:42', '2020-04-12');
INSERT INTO `student` VALUES ('S200401124', '孔攀晨', '1', '10', '女', '2020-04-11', '1', '攀晨妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:30:58', '2020-04-14');
INSERT INTO `student` VALUES ('S200401125', '胡跃', '1', '8', '女', '2020-04-11', '1', '胡跃妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:33:22', '2020-04-12');
INSERT INTO `student` VALUES ('S200401126', '胡淳', '1', '5', '女', '2020-04-11', '1', '胡淳妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:34:50', '2020-04-12');
INSERT INTO `student` VALUES ('S200401127', '秦思恬', '1', '6', '女', '2020-04-11', '1', '思恬妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:38:32', '2020-04-12');
INSERT INTO `student` VALUES ('S200401128', '华邵辰', '1', '6', '男', '2020-04-11', '1', '邵辰爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:40:15', '2020-04-12');
INSERT INTO `student` VALUES ('S200401129', '徐季惟', '1', '10', '女', '2020-04-11', '1', '季惟妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:43:17', '2020-04-14');
INSERT INTO `student` VALUES ('S200401130', '姜意楠', '1', '9', '女', '2020-04-11', '0', '意楠妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-13', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:44:32', '2020-04-13');
INSERT INTO `student` VALUES ('S200401131', '康恺', '1', '8', '男', '2020-04-11', '1', '康恺爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:45:36', '2020-04-12');
INSERT INTO `student` VALUES ('S200401132', '王浩羽', '1', '6', '男', '2020-04-11', '1', '浩羽爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:46:42', '2020-04-12');
INSERT INTO `student` VALUES ('S200401133', '周毅婷', '1', '10', '女', '2020-04-11', '1', '毅婷妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:48:51', '2020-04-14');
INSERT INTO `student` VALUES ('S200401134', '瞿乐贤', '1', '10', '男', '2020-04-11', '1', '乐贤爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:50:09', '2020-04-14');
INSERT INTO `student` VALUES ('S200401135', '黄宇遥', '1', '12', '男', '2020-04-11', '1', '宇遥爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:51:48', '2020-04-14');
INSERT INTO `student` VALUES ('S200401136', '瞿佳雯', '1', '11', '女', '2020-04-11', '1', '佳雯妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:52:58', '2020-04-14');
INSERT INTO `student` VALUES ('S200401137', '唐赵芸', '1', '10', '女', '2020-04-11', '1', '赵芸妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:54:30', '2020-04-14');
INSERT INTO `student` VALUES ('S200401138', '唐致远', '1', '4', '男', '2020-04-11', '1', '致远爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', 'omeo2002', '1', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'pfdn2002', '2020-04-11 10:56:26', '2020-04-12');
INSERT INTO `student` VALUES ('S200401139', '严思辰', '1', '9', '男', '2020-04-17', '0', '思辰妈妈', '妈妈', '123', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'zdji2002', '2020-04-17 10:43:04', '2020-04-17');
INSERT INTO `student` VALUES ('S200401140', '叶忻辰', '1', '9', '男', '2020-04-17', '1', '忻辰妈妈', '妈妈', '15921183056', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'zdji2002', '2020-04-17 13:50:20', '2020-04-17');
INSERT INTO `student` VALUES ('S200401141', '童星昊', '1', '6', '男', '2020-04-17', '1', '星昊妈妈', '妈妈', '15921184260', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'zdji2002', '2020-04-17 15:16:59', '2020-04-17');
INSERT INTO `student` VALUES ('S200401142', '邓赵杰', '1', '3', '男', '2020-04-17', '1', '赵杰爸爸', '爸爸', '13564396939', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:06:00', '2020-04-17');
INSERT INTO `student` VALUES ('S200401143', '施文杰', '1', '7', '男', '2020-04-17', '1', '文杰爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:09:54', '2020-04-17');
INSERT INTO `student` VALUES ('S200401144', '徐玉凤', '1', '4', '女', '2020-04-17', '1', '玉凤妈妈', '妈妈', '123', '无', '其它', '无', '王改亮', '', '1', '0', '0', '2020-04-17', '2000-01-01', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:11:16', '2000-01-01');
INSERT INTO `student` VALUES ('S200401145', '王闵芝', '1', '3', '女', '2020-04-17', '1', '闵芝妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:12:30', '2020-04-17');
INSERT INTO `student` VALUES ('S200401146', '陈怡笑', '1', '4', '女', '2020-04-17', '1', '怡笑妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:15:23', '2020-04-17');
INSERT INTO `student` VALUES ('S200401147', '陆毅', '1', '9', '男', '2020-04-17', '1', '陆毅爸爸', '爸爸', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:16:23', '2020-04-17');
INSERT INTO `student` VALUES ('S200401148', '李悦菡', '1', '6', '女', '2020-04-17', '1', '悦菡妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:18:27', '2020-04-17');
INSERT INTO `student` VALUES ('S200401149', '潘一唯', '1', '12', '女', '2020-04-17', '1', '一唯妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:19:21', '2020-04-17');
INSERT INTO `student` VALUES ('S200401150', '徐馨', '1', '9', '女', '2020-04-17', '1', '徐馨妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 17:20:06', '2020-04-17');
INSERT INTO `student` VALUES ('S200401151', '唐琤一', '1', '5', '女', '2020-04-17', '1', '琤一妈妈', '妈妈', '123', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-17', '2020-04-17', '2000-01-01', '', '1', 'acqd2002', '2020-04-17 18:05:55', '2020-04-17');
INSERT INTO `student` VALUES ('S200401152', '邵天豪', '1', '9', '男', '2020-04-18', '1', '天豪妈妈', '妈妈', '13611808834', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-18', '2000-01-01', '2000-01-01', '', '1', 'zdji2002', '2020-04-18 15:47:17', '2000-01-01');
INSERT INTO `student` VALUES ('S200401153', '王雨昕', '1', '11', '女', '2020-04-23', '0', '王雨昕妈妈', '妈妈', '123', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-04-23', '2020-04-23', '2000-01-01', '', '1', 'zdji2002', '2020-04-23 10:36:25', '2020-04-23');
INSERT INTO `student` VALUES ('S200401154', '张智谦', '1', '5', '男', '2009-03-09', '1', '李女士', '妈妈', '13917836151', '无', '其它', '无', 'acqd2002', '', '1', '0', '0', '2020-04-23', '2020-04-23', '2000-01-01', '', '1', 'acqd2002', '2020-04-23 16:10:42', '2020-04-23');
INSERT INTO `student` VALUES ('S200401155', '马凯文', '1', '9', '男', '2020-04-23', '1', '马凯文妈吗', '妈妈', '15901867578', '无', '学生转介绍', '吴天浩妈妈转介绍 未收综合服务费', 'dtfn2002', '', '1', '0', '0', '2020-04-23', '2020-04-23', '2000-01-01', '', '1', 'zdji2002', '2020-04-23 16:32:31', '2020-04-23');
INSERT INTO `student` VALUES ('S200401156', '吴天昊', '1', '9', '男', '2020-04-23', '1', '吴天昊妈妈', '妈妈', '15021085642', '无', '其它', '无', 'dtfn2002', '', '1', '0', '0', '2020-04-23', '2020-04-23', '2000-01-01', '', '1', 'dtfn2002', '2020-04-23 16:39:56', '2020-04-23');
INSERT INTO `student` VALUES ('S200402001', '张佳怡', '2', '8', '女', '2020-04-01', '0', '张佳怡妈妈', '妈妈', '15026626881', '无', '客户转介绍', '无', 'fsmp2002', 'bdnn2002', '3', '0', '0', '2020-04-02', '2000-01-01', '2000-01-01', '', '1', 'fsmp2002', '2020-04-02 10:18:45', '2020-04-02');
INSERT INTO `student` VALUES ('S200402002', '吴奕林', '2', '4', '男', '2020-04-04', '1', '吴妈妈', '妈妈', '18616360580', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-04', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-04 14:55:53', '2020-04-04');
INSERT INTO `student` VALUES ('S200402003', '吴奕森', '2', '4', '男', '2020-04-04', '0', '吴妈妈', '妈妈', '18616360580', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-04', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-04 14:59:08', '2020-04-04');
INSERT INTO `student` VALUES ('S200402004', '邬伟璐', '2', '12', '女', '2020-04-04', '0', '伟璐妈妈', '妈妈', '13917963656', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-04', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-04 16:33:36', '2020-04-04');
INSERT INTO `student` VALUES ('S200402005', '龚剑云', '2', '10', '男', '2020-04-05', '0', '龚妈妈', '妈妈', '18616369612', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 08:58:40', '2020-04-05');
INSERT INTO `student` VALUES ('S200402006', '黄雨晨', '2', '8', '女', '2020-04-05', '0', '黄妈妈', '妈妈', '15800485946', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 09:02:35', '2020-04-05');
INSERT INTO `student` VALUES ('S200402007', '熊筱絮', '2', '6', '女', '2020-04-05', '0', '熊妈妈', '妈妈', '18616360586', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 09:16:48', '2020-04-05');
INSERT INTO `student` VALUES ('S200402008', '乔鑫宇', '2', '8', '男', '2020-04-05', '0', '乔妈妈', '妈妈', '13472441117', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '2', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 09:19:26', '2020-04-05');
INSERT INTO `student` VALUES ('S200402009', '周智妮', '2', '8', '女', '2020-04-05', '0', '周妈妈', '妈妈', '13917963687', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 09:26:19', '2020-04-05');
INSERT INTO `student` VALUES ('S200402010', '徐艺蓓', '2', '11', '女', '2020-04-05', '0', '徐妈妈', '妈妈', '13472441980', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 09:29:54', '2020-04-05');
INSERT INTO `student` VALUES ('S200402011', '陆家业', '2', '8', '男', '2020-04-05', '0', '陆妈妈', '妈妈', '15000595787', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 09:40:38', '2020-04-05');
INSERT INTO `student` VALUES ('S200402012', '杨嘉怡', '2', '11', '女', '2020-04-05', '0', '杨妈妈', '妈妈', '18616360589', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 09:51:57', '2020-04-05');
INSERT INTO `student` VALUES ('S200402013', '樊益峰', '2', '3', '男', '2020-04-05', '0', '樊妈妈', '妈妈', '13661839246', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 12:00:08', '2020-04-05');
INSERT INTO `student` VALUES ('S200402014', '闻燕翔', '2', '12', '女', '2020-04-05', '0', '闻妈妈', '妈妈', '18116121131', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '1', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 15:40:29', '2020-04-05');
INSERT INTO `student` VALUES ('S200402015', '金磊', '2', '9', '男', '2020-04-05', '0', '金妈妈', '妈妈', '13817654077', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 16:05:04', '2020-04-08');
INSERT INTO `student` VALUES ('S200402016', '黄泳峰', '2', '11', '男', '2020-04-05', '0', '黄妈妈', '妈妈', '13381669212', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '1', '0', '0', '2020-04-05', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-05 20:28:53', '2020-04-08');
INSERT INTO `student` VALUES ('S200402017', '杨袁', '2', '8', '男', '2020-04-08', '0', '妈妈', '妈妈', '15821986940', '无', '学生转介绍', '6999活动课 共计120课时 另陆家业妈妈转介绍赠送3课时 杨袁妈妈特批赠送9课时', 'fsmp2002', 'bdnn2002', '3', '0', '0', '2020-04-08', '2000-01-01', '2000-01-01', '', '1', 'fsmp2002', '2020-04-08 12:49:50', '2020-04-08');
INSERT INTO `student` VALUES ('S200402018', '胡锦豪', '2', '8', '男', '2020-04-08', '0', '妈妈', '妈妈', '13585917283', '无', '学生转介绍', '无', 'fsmp2002', 'bdnn2002', '3', '0', '0', '2020-04-08', '2000-01-01', '2000-01-01', '', '1', 'fsmp2002', '2020-04-08 13:38:00', '2020-04-08');
INSERT INTO `student` VALUES ('S200402019', '蔡丰盛', '2', '11', '男', '2020-04-08', '0', '蔡妈妈', '妈妈', '13564872766', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-08', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-08 14:20:34', '2020-04-08');
INSERT INTO `student` VALUES ('S200402020', '杨丁凯', '2', '8', '女', '2020-04-08', '0', '妈妈', '妈妈', '15618849308', '无', '地推', '无', 'fsmp2002', 'bdnn2002', '3', '0', '0', '2020-04-08', '2000-01-01', '2000-01-01', '', '1', 'fsmp2002', '2020-04-08 17:04:45', '2020-04-08');
INSERT INTO `student` VALUES ('S200402021', '倪洁', '2', '9', '女', '2020-04-08', '0', '倪洁妈妈', '妈妈', '18317146388', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-08', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-08 18:19:50', '2020-04-08');
INSERT INTO `student` VALUES ('S200402022', '於诗佳', '2', '10', '女', '2020-04-09', '0', '妈妈', '妈妈', '18616017331', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 12:55:41', '2020-04-09');
INSERT INTO `student` VALUES ('S200402023', '储嫣然', '2', '9', '女', '2020-04-09', '0', '妈妈', '妈妈', '13761526564', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 13:06:59', '2020-04-09');
INSERT INTO `student` VALUES ('S200402024', '王宇凡', '2', '8', '男', '2020-04-09', '0', '爸爸', '爸爸', '18800208182', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 13:10:56', '2020-04-09');
INSERT INTO `student` VALUES ('S200402025', '朱玺文', '2', '5', '男', '2020-04-09', '0', '妈妈', '妈妈', '13917963679', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 13:15:58', '2020-04-09');
INSERT INTO `student` VALUES ('S200402026', '孙泽华', '2', '10', '男', '2020-04-09', '0', '妈妈', '妈妈', '13661839238', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 13:19:36', '2020-04-09');
INSERT INTO `student` VALUES ('S200402027', '宋朱点贝', '2', '11', '男', '2020-04-09', '0', '妈妈', '妈妈', '15800775828', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 13:28:59', '2020-04-09');
INSERT INTO `student` VALUES ('S200402028', '陈颖', '2', '12', '女', '2020-04-09', '0', '爸爸', '爸爸', '13671909283', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 13:49:01', '2020-04-14');
INSERT INTO `student` VALUES ('S200402029', '唐张昊', '2', '9', '男', '2020-04-09', '0', '妈妈', '妈妈', '18917030052', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 14:02:24', '2020-04-11');
INSERT INTO `student` VALUES ('S200402030', '马圣浩', '2', '10', '男', '2020-04-09', '0', '妈妈', '妈妈', '13764631114', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 14:04:11', '2020-04-09');
INSERT INTO `student` VALUES ('S200402031', '黄尹杰', '2', '8', '男', '2020-04-09', '0', '妈妈', '妈妈', '18930062703', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 14:04:57', '2020-04-11');
INSERT INTO `student` VALUES ('S200402032', '周思怡', '2', '8', '女', '2020-04-09', '0', '妈妈', '妈妈', '15021824855', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 14:05:53', '2020-04-14');
INSERT INTO `student` VALUES ('S200402033', '熊可欣', '2', '6', '女', '2020-04-09', '0', '妈妈', '妈妈', '13661927997', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 14:51:09', '2020-04-12');
INSERT INTO `student` VALUES ('S200402034', '谈晓岚', '2', '9', '女', '2020-04-09', '0', '妈妈', '妈妈', '15317233156', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 16:49:39', '2020-04-09');
INSERT INTO `student` VALUES ('S200402035', '冯乐怡', '2', '7', '女', '2020-04-09', '0', '爸爸', '爸爸', '13918870006', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 16:50:39', '2020-04-11');
INSERT INTO `student` VALUES ('S200402036', '杨佳宁', '2', '7', '女', '2020-04-09', '0', '妈妈', '妈妈', '15901761805', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 16:52:03', '2020-04-11');
INSERT INTO `student` VALUES ('S200402037', '付天宇', '2', '8', '男', '2020-04-09', '0', '妈妈', '妈妈', '15801955372', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 16:54:07', '2020-04-11');
INSERT INTO `student` VALUES ('S200402038', '李梓恒', '2', '4', '男', '2020-04-09', '0', '妈妈', '妈妈', '13818335209', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 18:37:27', '2020-04-14');
INSERT INTO `student` VALUES ('S200402039', '黄佳浩林', '2', '9', '男', '2020-04-09', '0', '妈妈', '妈妈', '18217076091', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:14:20', '2020-04-11');
INSERT INTO `student` VALUES ('S200402040', '李妍', '2', '11', '女', '2020-04-09', '0', '妈妈', '妈妈', '15821052130', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:15:22', '2020-04-09');
INSERT INTO `student` VALUES ('S200402041', '黄彦宸', '2', '6', '男', '2020-04-09', '0', '妈妈', '妈妈', '13512191432', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:18:13', '2020-04-14');
INSERT INTO `student` VALUES ('S200402042', '沈皓', '2', '8', '男', '2020-04-09', '0', '妈妈', '妈妈', '13611722581', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:19:04', '2020-04-11');
INSERT INTO `student` VALUES ('S200402043', '王润涛', '2', '8', '男', '2020-04-09', '0', '妈妈', '妈妈', '18017684530', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:33:55', '2020-04-11');
INSERT INTO `student` VALUES ('S200402044', '冯麟', '2', '6', '男', '2020-04-09', '0', '妈妈', '妈妈', '13917421303', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:35:27', '2020-04-12');
INSERT INTO `student` VALUES ('S200402045', '姚王宇', '2', '12', '女', '2020-04-09', '0', '妈妈', '妈妈', '13918626486', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:36:11', '2020-04-11');
INSERT INTO `student` VALUES ('S200402046', '闵晟俨', '2', '9', '男', '2020-04-09', '0', '妈妈', '妈妈', '13671919220', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:36:53', '2020-04-11');
INSERT INTO `student` VALUES ('S200402047', '陶宇辰', '2', '9', '男', '2020-04-09', '0', '妈妈', '妈妈', '18930364693', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:39:02', '2020-04-11');
INSERT INTO `student` VALUES ('S200402048', '杨雨萱', '2', '5', '女', '2020-04-09', '0', '妈妈', '妈妈', '13817820795', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-15', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:41:06', '2020-04-15');
INSERT INTO `student` VALUES ('S200402049', '张圣依', '2', '10', '女', '2020-04-09', '0', '妈妈', '妈妈', '15821732831', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-09 19:42:03', '2020-04-11');
INSERT INTO `student` VALUES ('S200402050', '陆晨榆', '2', '11', '女', '2020-04-10', '0', '妈妈', '妈妈', '15202188302', '无', '其它', '无', 'eekc2002', 'bdnn2002', '2', '0', '0', '2020-04-10', '2000-01-01', '2000-01-01', '', '1', 'eekc2002', '2020-04-10 15:31:18', '2020-04-10');
INSERT INTO `student` VALUES ('S200402051', '宇杰', '2', '7', '男', '2020-04-10', '0', '妈妈', '妈妈', '13764127008', '无', '学生转介绍', '无', 'eekc2002', 'bdnn2002', '3', '0', '0', '2020-04-10', '2020-04-11', '2000-01-01', '', '1', 'eekc2002', '2020-04-10 15:35:53', '2020-04-11');
INSERT INTO `student` VALUES ('S200402052', '曹志翔', '2', '7', '男', '2020-04-10', '0', '妈妈', '妈妈', '15000803126', '无', '其它', '无', 'eekc2002', 'bdnn2002', '3', '0', '0', '2020-04-10', '2000-01-01', '2000-01-01', '', '1', 'eekc2002', '2020-04-10 15:47:13', '2020-04-10');
INSERT INTO `student` VALUES ('S200402053', '张心怡', '2', '11', '女', '2020-04-10', '0', '妈妈', '妈妈', '15021774870', '无', '其它', '无', 'eekc2002', 'bdnn2002', '3', '0', '0', '2020-04-10', '2000-01-01', '2000-01-01', '', '1', 'eekc2002', '2020-04-10 15:50:47', '2020-04-10');
INSERT INTO `student` VALUES ('S200402054', '章梦琪', '2', '7', '女', '2020-04-10', '0', '妈妈', '妈妈', '13122301618', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-10', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-10 20:51:13', '2000-01-01');
INSERT INTO `student` VALUES ('S200402055', '程雨琪', '2', '9', '男', '2020-04-10', '0', '妈妈', '妈妈', '13127582911', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-10', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-10 20:57:33', '2020-04-11');
INSERT INTO `student` VALUES ('S200402056', '王雪晨', '2', '6', '男', '2020-04-10', '0', '妈妈', '妈妈', '13681988010', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-10', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-10 20:58:47', '2020-04-14');
INSERT INTO `student` VALUES ('S200402057', '朱雨泽', '2', '4', '男', '2020-04-10', '0', '妈妈', '妈妈', '15002115495', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-10', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-10 20:59:58', '2000-01-01');
INSERT INTO `student` VALUES ('S200402058', '周陶慧', '2', '9', '女', '2020-04-10', '0', '妈妈', '妈妈', '17317046069', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-10', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-10 21:01:51', '2020-04-11');
INSERT INTO `student` VALUES ('S200402059', '王敏杰', '2', '6', '男', '2020-04-10', '0', '妈妈', '妈妈', '13564356350', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-10', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-10 21:02:47', '2020-04-12');
INSERT INTO `student` VALUES ('S200402060', '张杰', '2', '11', '男', '2020-04-11', '0', '妈妈', '妈妈', '15601710918', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 08:33:38', '2020-04-11');
INSERT INTO `student` VALUES ('S200402061', '陆俊杰', '2', '9', '男', '2020-04-11', '0', '妈妈', '妈妈', '13764123148', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 08:51:53', '2020-04-11');
INSERT INTO `student` VALUES ('S200402062', '唐伽尧', '2', '9', '男', '2020-04-11', '0', '妈妈', '妈妈', '13585543792', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 08:52:51', '2020-04-11');
INSERT INTO `student` VALUES ('S200402063', '倪苏婕', '2', '11', '女', '2020-04-11', '0', '妈妈', '妈妈', '13636402911', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 08:53:34', '2020-04-11');
INSERT INTO `student` VALUES ('S200402064', '凌诺晖', '2', '7', '男', '2020-04-11', '0', '妈妈', '妈妈', '13585913924', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 08:55:28', '2020-04-12');
INSERT INTO `student` VALUES ('S200402065', '胡晋远', '2', '5', '男', '2020-04-11', '0', '妈妈', '妈妈', '13501952404', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 08:56:42', '2000-01-01');
INSERT INTO `student` VALUES ('S200402066', '吴尹良品', '2', '8', '男', '2020-04-11', '0', '爸爸', '爸爸', '13761510302', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:07:23', '2020-04-11');
INSERT INTO `student` VALUES ('S200402067', '朱浩天', '2', '10', '男', '2020-04-11', '0', '妈妈', '妈妈', '13166219616', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:08:13', '2020-04-11');
INSERT INTO `student` VALUES ('S200402068', '徐子轩', '2', '5', '男', '2020-04-11', '0', '妈妈', '妈妈', '18516275982', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:09:23', '2020-04-14');
INSERT INTO `student` VALUES ('S200402069', '刘军', '2', '7', '男', '2020-04-11', '0', '爸爸', '爸爸', '13611796048', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:10:16', '2020-04-12');
INSERT INTO `student` VALUES ('S200402070', '吴添元', '2', '7', '男', '2020-04-11', '0', '妈妈', '妈妈', '13816991556', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:10:55', '2000-01-01');
INSERT INTO `student` VALUES ('S200402071', '吴柄颉', '2', '4', '男', '2020-04-11', '0', '妈妈', '妈妈', '13857811502', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:11:40', '2000-01-01');
INSERT INTO `student` VALUES ('S200402072', '陈佳缘', '2', '9', '男', '2020-04-11', '0', '爸爸', '爸爸', '13916972651', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:12:55', '2020-04-11');
INSERT INTO `student` VALUES ('S200402073', '王宇轩', '2', '9', '男', '2020-04-11', '0', '爸爸', '爸爸', '15801906860', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:13:59', '2020-04-11');
INSERT INTO `student` VALUES ('S200402074', '王俊毅', '2', '8', '男', '2020-04-11', '0', '爸爸', '爸爸', '13917461932', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:17:53', '2020-04-11');
INSERT INTO `student` VALUES ('S200402075', '李明伟', '2', '10', '男', '2020-04-11', '0', '妈妈', '妈妈', '15601866926', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:18:37', '2020-04-11');
INSERT INTO `student` VALUES ('S200402076', '陈萱羽', '2', '9', '女', '2020-04-11', '0', '妈妈', '妈妈', '13310086676', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:19:51', '2020-04-11');
INSERT INTO `student` VALUES ('S200402077', '朱英杰', '2', '12', '男', '2020-04-11', '0', '妈妈', '妈妈', '15705275621', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:26:05', '2020-04-11');
INSERT INTO `student` VALUES ('S200402078', '黄圣吉', '2', '9', '男', '2020-04-11', '0', '爸爸', '爸爸', '13052240502', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:30:25', '2020-04-11');
INSERT INTO `student` VALUES ('S200402079', '顾泽钦', '2', '12', '男', '2020-04-11', '0', '妈妈', '妈妈', '13391295816', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:31:20', '2020-04-11');
INSERT INTO `student` VALUES ('S200402080', '谌美琪', '2', '10', '女', '2020-04-11', '0', '妈妈', '妈妈', '13764672133', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:32:03', '2020-04-14');
INSERT INTO `student` VALUES ('S200402081', '张逸臣', '2', '9', '男', '2020-04-11', '0', '妈妈', '妈妈', '18964109313', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:33:14', '2000-01-01');
INSERT INTO `student` VALUES ('S200402082', '严祎恺', '2', '9', '男', '2020-04-11', '0', '妈妈', '妈妈', '13818374176', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:34:13', '2020-04-11');
INSERT INTO `student` VALUES ('S200402083', '黄铱洋', '2', '8', '男', '2020-04-11', '0', '妈妈', '妈妈', '13122931901', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:35:52', '2020-04-12');
INSERT INTO `student` VALUES ('S200402084', '瞿贝妮', '2', '9', '女', '2020-04-11', '0', '妈妈', '妈妈', '18930419669', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-16', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:36:39', '2020-04-16');
INSERT INTO `student` VALUES ('S200402085', '张耀文', '2', '5', '女', '2020-04-11', '0', '妈妈', '妈妈', '13601884911', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:39:24', '2020-04-14');
INSERT INTO `student` VALUES ('S200402086', '刘祎程', '2', '11', '女', '2020-04-11', '0', '妈妈', '妈妈', '15618112348', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:40:05', '2000-01-01');
INSERT INTO `student` VALUES ('S200402087', '谈心', '2', '6', '女', '2020-04-11', '0', '妈妈', '妈妈', '18818093798', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:42:22', '2020-04-14');
INSERT INTO `student` VALUES ('S200402088', '黄语妍', '2', '7', '女', '2020-04-11', '0', '妈妈', '妈妈', '13641850045', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:43:36', '2020-04-12');
INSERT INTO `student` VALUES ('S200402089', '马义钦', '2', '8', '男', '2020-04-11', '0', '妈妈', '妈妈', '13391295816', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:45:03', '2020-04-11');
INSERT INTO `student` VALUES ('S200402090', '赵艺玮', '2', '8', '男', '2020-04-11', '0', '妈妈', '妈妈', '15221950515', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '1', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:46:05', '2020-04-14');
INSERT INTO `student` VALUES ('S200402091', '李子涵', '2', '8', '女', '2020-04-11', '0', '妈妈', '妈妈', '15716737378', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:47:21', '2020-04-11');
INSERT INTO `student` VALUES ('S200402092', '倪春杰', '2', '6', '男', '2020-04-11', '0', '妈妈', '妈妈', '18516341981', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:48:22', '2000-01-01');
INSERT INTO `student` VALUES ('S200402093', '周婉婕', '2', '6', '女', '2020-04-11', '0', '妈妈', '妈妈', '13524939152', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:49:01', '2020-04-14');
INSERT INTO `student` VALUES ('S200402094', '瞿佳园', '2', '9', '女', '2020-04-11', '0', '妈妈', '妈妈', '13917060920', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 09:49:30', '2020-04-11');
INSERT INTO `student` VALUES ('S200402095', '姚一鸣', '2', '8', '男', '2020-04-11', '0', '妈妈', '妈妈', '13918397026', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:08:02', '2000-01-01');
INSERT INTO `student` VALUES ('S200402096', '徐宇晨', '2', '8', '男', '2020-04-11', '0', '妈妈', '妈妈', '18101647081', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:08:40', '2020-04-12');
INSERT INTO `student` VALUES ('S200402097', '王思佳', '2', '3', '女', '2020-04-11', '0', '妈妈', '妈妈', '13501919220', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:09:51', '2020-04-14');
INSERT INTO `student` VALUES ('S200402098', '周汇惠', '2', '4', '女', '2020-04-11', '0', '妈妈', '妈妈', '15021321108', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:13:56', '2020-04-14');
INSERT INTO `student` VALUES ('S200402099', '顾杰文', '2', '4', '男', '2020-04-11', '0', '妈妈', '妈妈', '18049964907', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:15:11', '2020-04-12');
INSERT INTO `student` VALUES ('S200402100', '闵嘉蓓', '2', '8', '女', '2020-04-11', '0', '妈妈', '妈妈', '18001866300', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:20:23', '2020-04-11');
INSERT INTO `student` VALUES ('S200402101', '陆逸凡', '2', '7', '男', '2020-04-11', '0', '妈妈', '妈妈', '13626959844', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:21:12', '2020-04-11');
INSERT INTO `student` VALUES ('S200402102', '张宇晨', '2', '7', '男', '2020-04-11', '0', '妈妈', '妈妈', '13564267362', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:21:52', '2020-04-12');
INSERT INTO `student` VALUES ('S200402103', '王欣语', '2', '10', '女', '2020-04-11', '0', '妈妈', '妈妈', '13701616945', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:22:39', '2020-04-11');
INSERT INTO `student` VALUES ('S200402104', '叶与时', '2', '11', '男', '2020-04-11', '0', '妈妈', '妈妈', '13761348284', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2020-05-02', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:23:17', '2020-04-16');
INSERT INTO `student` VALUES ('S200402105', '王谨涵', '2', '3', '女', '2020-04-11', '0', '妈妈', '妈妈', '15317713696', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 10:26:04', '2000-01-01');
INSERT INTO `student` VALUES ('S200402106', '杨博文', '2', '4', '男', '2020-04-11', '0', '妈妈', '妈妈', '18149784026', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:16:12', '2020-04-14');
INSERT INTO `student` VALUES ('S200402107', '吴璘斐', '2', '5', '女', '2020-04-11', '0', '爸爸', '爸爸', '18049838089', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:18:32', '2020-04-14');
INSERT INTO `student` VALUES ('S200402108', '苏禹铭', '2', '5', '男', '2020-04-11', '0', '妈妈', '妈妈', '13311859968', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:19:15', '2020-04-12');
INSERT INTO `student` VALUES ('S200402109', '沈鑫云', '2', '5', '男', '2020-04-11', '0', '爸爸', '爸爸', '13512124237', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:20:06', '2020-04-14');
INSERT INTO `student` VALUES ('S200402110', '张爱文', '2', '7', '女', '2020-04-11', '0', '妈妈', '妈妈', '18117041018', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-12', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:21:26', '2020-04-12');
INSERT INTO `student` VALUES ('S200402111', '张彦', '2', '7', '女', '2020-04-11', '0', '妈妈', '妈妈', '17717332080', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:22:41', '2000-01-01');
INSERT INTO `student` VALUES ('S200402112', '朱玺诺', '2', '8', '女', '2020-04-11', '0', '妈妈', '妈妈', '13917963679', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:26:58', '2020-04-14');
INSERT INTO `student` VALUES ('S200402113', '周甜丁', '2', '8', '女', '2020-04-11', '0', '妈妈', '妈妈', '13661463837', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:27:49', '2020-04-11');
INSERT INTO `student` VALUES ('S200402114', '张司渝', '2', '8', '女', '2020-04-11', '0', '妈妈', '妈妈', '13671642410', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:28:49', '2020-04-11');
INSERT INTO `student` VALUES ('S200402115', '赵轶凡', '2', '8', '男', '2020-04-11', '0', '妈妈', '妈妈', '18201887829', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:29:36', '2020-04-11');
INSERT INTO `student` VALUES ('S200402116', '朱诗怡', '2', '8', '女', '2020-04-11', '0', '妈妈', '妈妈', '18930681628', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:30:08', '2020-04-14');
INSERT INTO `student` VALUES ('S200402117', '桂湘怡', '2', '8', '女', '2020-04-11', '0', '妈妈', '妈妈', '15001904631', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:30:45', '2020-04-11');
INSERT INTO `student` VALUES ('S200402118', '刘宇轩', '2', '12', '男', '2020-04-11', '0', '妈妈', '妈妈', '15900406281', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-16', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:32:11', '2020-04-16');
INSERT INTO `student` VALUES ('S200402119', '连宸灏', '2', '3', '男', '2020-04-11', '0', '妈妈', '妈妈', '18964858179', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:36:10', '2020-04-14');
INSERT INTO `student` VALUES ('S200402120', '刘家齐', '2', '3', '男', '2020-04-11', '0', '妈妈', '妈妈', '13512161265', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:36:52', '2020-04-14');
INSERT INTO `student` VALUES ('S200402121', '孟毓涵', '2', '3', '女', '2020-04-11', '0', '妈妈', '妈妈', '13764457866', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:37:46', '2020-04-14');
INSERT INTO `student` VALUES ('S200402122', '曹昱晟', '2', '3', '男', '2020-04-11', '0', '爸爸', '爸爸', '13818940608', '无', '其它', '无', 'hlfy2002', '', '2', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:38:49', '2020-04-14');
INSERT INTO `student` VALUES ('S200402123', '潘佳雯', '2', '8', '女', '2020-04-11', '0', '爸爸', '爸爸', '18930264978', '无', '其它', '无', 'eekc2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'eekc2002', '2020-04-11 11:40:25', '2020-04-11');
INSERT INTO `student` VALUES ('S200402124', '李婉怡', '2', '9', '女', '2020-04-11', '0', '妈妈', '妈妈', '15800775737', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:44:06', '2000-01-01');
INSERT INTO `student` VALUES ('S200402125', '朱懿姿', '2', '12', '女', '2020-04-11', '0', '妈妈', '妈妈', '13166312454', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:49:01', '2000-01-01');
INSERT INTO `student` VALUES ('S200402126', '吴思怡', '2', '11', '女', '2020-04-11', '0', '妈妈', '妈妈', '13916809535', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2020-04-18', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 11:50:21', '2020-04-18');
INSERT INTO `student` VALUES ('S200402127', '王子怡', '2', '11', '女', '2020-04-11', '0', '妈妈', '妈妈', '13761320618', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 13:18:40', '2020-04-11');
INSERT INTO `student` VALUES ('S200402128', '董依雯', '2', '11', '女', '2020-04-11', '0', '妈妈', '妈妈', '13524452867', '无', '其它', '无', 'hlfy2002', '', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 13:19:20', '2020-04-14');
INSERT INTO `student` VALUES ('S200402129', '姚怡炜', '2', '12', '女', '2020-04-11', '0', '爸爸', '爸爸', '13472441116', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 13:21:08', '2020-04-14');
INSERT INTO `student` VALUES ('S200402130', '沈子淇', '2', '3', '女', '2020-04-11', '0', '妈妈', '妈妈', '13636477845', '无', '其它', '无', 'hlfy2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-14', '2000-01-01', '', '1', 'hlfy2002', '2020-04-11 13:31:34', '2020-04-14');
INSERT INTO `student` VALUES ('S200402131', '刘颖歆', '2', '4', '女', '2020-04-11', '0', '奶奶', '奶奶', '15900457082', '无', '其它', '无', 'eekc2002', 'bdnn2002', '3', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'eekc2002', '2020-04-11 17:08:14', '2020-04-11');
INSERT INTO `student` VALUES ('S200402132', '王佳怡', '2', '9', '女', '2020-04-15', '0', '妈妈', '妈妈', '13774440500', '无', '其它', '无', 'eekc2002', 'bdnn2002', '3', '0', '0', '2020-04-15', '2020-04-25', '2000-01-01', '', '1', 'eekc2002', '2020-04-15 15:39:18', '2020-04-15');
INSERT INTO `student` VALUES ('S200402133', '邢雪珂', '2', '5', '女', '2020-04-16', '0', '爸爸', '爸爸', '13818662869', '无', '员工转介绍', '无', 'eekc2002', 'bdnn2002', '3', '0', '0', '2020-04-16', '2020-04-16', '2000-01-01', '', '1', 'eekc2002', '2020-04-16 10:09:09', '2020-04-16');
INSERT INTO `student` VALUES ('S200402134', '朱晓斌', '2', '9', '男', '2020-04-19', '0', '爸爸', '爸爸', '13391155018', '无', '其它', '无', 'fsmp2002', '', '3', '0', '0', '2020-04-19', '2020-04-19', '2000-01-01', '', '1', 'fsmp2002', '2020-04-19 16:42:43', '2020-04-19');
INSERT INTO `student` VALUES ('S200402135', '倪梓洁', '2', '10', '女', '2020-04-22', '0', '叶女士', '妈妈', '18017580801', '无', '学生转介绍', '无', 'eekc2002', '', '3', '0', '0', '2020-04-22', '2020-04-22', '2000-01-01', '', '1', 'eekc2002', '2020-04-22 15:08:47', '2020-04-22');
INSERT INTO `student` VALUES ('S200402136', '张刘奕轩', '2', '7', '男', '2020-04-25', '0', '妈妈', '妈妈', '13701840636', '无', '其它', '无', 'fsmp2002', '', '3', '0', '0', '2020-04-25', '2020-04-25', '2000-01-01', '', '1', 'fsmp2002', '2020-04-25 11:50:15', '2020-04-25');
INSERT INTO `student` VALUES ('S200402137', '瞿晨书', '2', '10', '女', '2020-04-30', '0', '妈妈', '妈妈', '18721568229', '无', '其它', '无', 'fsmp2002', '', '3', '0', '0', '2020-04-30', '2020-05-02', '2000-01-01', '', '1', 'fsmp2002', '2020-04-30 15:05:09', '2020-05-02');
INSERT INTO `student` VALUES ('S200402138', '徐卉', '2', '4', '女', '2020-04-30', '0', '妈妈', '妈妈', '13918606886', '无', '员工转介绍', '无', 'fsmp2002', '', '1', '0', '0', '2020-04-30', '2000-01-01', '2000-01-01', '', '1', 'fsmp2002', '2020-04-30 15:06:01', '2000-01-01');
INSERT INTO `student` VALUES ('S200403001', '赵依琳', '3', '6', '女', '2020-04-09', '2', '赵依琳妈妈', '妈妈', '13795415949', '无', '客户转介绍', '无', 'wwwp2002', 'wwwp2002', '3', '0', '0', '2020-04-09', '2020-05-20', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 10:45:43', '2020-04-11');
INSERT INTO `student` VALUES ('S200403002', '奚浩然', '3', '4', '男', '2020-04-09', '2', '奚爸爸', '爸爸', '18221355889', '无', '其它', '无', 'jjre2002', 'wwwp2002', '3', '0', '0', '2020-04-09', '2020-04-11', '2000-01-01', '', '1', 'jjre2002', '2020-04-09 13:42:33', '2020-04-09');
INSERT INTO `student` VALUES ('S200403005', '陆卓远', '3', '5', '男', '2020-04-09', '0', '宋鹤丽', '妈妈', '13817138081', '无', '其它', '无', 'wwwp2002', '', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 15:18:17', '2020-04-09');
INSERT INTO `student` VALUES ('S200403006', '陆诗盈', '3', '5', '女', '2020-04-09', '2', '宋妈妈', '妈妈', '13817138081', '无', '其它', '无', 'wwwp2002', '', '3', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 15:27:15', '2020-04-09');
INSERT INTO `student` VALUES ('S200403007', '顾清澜', '3', '5', '女', '2020-04-09', '2', '陆燕清', '妈妈', '13774486841', '无', '客户转介绍', '无', 'wwwp2002', '', '2', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 15:30:23', '2020-04-09');
INSERT INTO `student` VALUES ('S200403009', '姚自立', '3', '9', '男', '2020-04-09', '2', '曹志芳', '妈妈', '15000507764', '无', '其它', '无', 'wwwp2002', 'eafc2004', '3', '0', '0', '2020-04-09', '2020-06-17', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 15:41:21', '2020-06-05');
INSERT INTO `student` VALUES ('S200403010', '顾文灏', '3', '5', '男', '2020-04-09', '2', '顾晓华', '爸爸', '13817243382', '无', '学生转介绍', '无', 'wwwp2002', '', '1', '0', '0', '2020-04-09', '2000-01-01', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 15:53:26', '2020-04-09');
INSERT INTO `student` VALUES ('S200403011', '顾浩冉', '3', '6', '男', '2020-04-09', '2', '顾余贤', '爷爷', '13817790571', '无', '广告', '无', 'wwwp2002', 'eafc2004', '3', '0', '0', '2020-04-09', '2020-06-12', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 15:56:57', '2020-04-09');
INSERT INTO `student` VALUES ('S200403012', '倪浩然', '3', '7', '男', '2020-04-09', '2', '倪妈妈', '妈妈', '13801969651', '无', '其它', '无', 'wwwp2002', 'eafc2004', '1', '0', '0', '2020-04-09', '2020-06-12', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 16:11:30', '2020-06-12');
INSERT INTO `student` VALUES ('S200403013', '单欣怡', '3', '7', '女', '2020-04-09', '2', '单妈妈', '妈妈', '15001922377', '无', '地推', '无', 'wwwp2002', 'wwwp2002', '3', '0', '0', '2020-04-09', '2020-05-23', '2000-01-01', '', '1', 'wwwp2002', '2020-04-09 17:09:59', '2020-04-09');
INSERT INTO `student` VALUES ('S200403014', '储源宏', '3', '7', '男', '2020-04-10', '2', '储妈妈', '妈妈', '18616894762', '无', '短信', '无', 'wwwp2002', '', '2', '0', '0', '2020-04-10', '2000-01-01', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 08:49:45', '2020-04-10');
INSERT INTO `student` VALUES ('S200403015', '周华天', '3', '2', '男', '2020-04-10', '2', '华怡青', '妈妈', '17717301116', '无', '其它', '无', 'wwwp2002', '', '1', '0', '0', '2020-04-10', '2000-01-01', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 08:53:25', '2020-04-10');
INSERT INTO `student` VALUES ('S200403016', '施恩洁', '3', '3', '女', '2020-04-10', '2', '施妈妈', '妈妈', '18017390645', '无', '地推', '无', 'eafc2004', 'eafc2004', '1', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 08:56:01', '2020-04-10');
INSERT INTO `student` VALUES ('S200403017', '奚梦瑶', '3', '3', '女', '2020-04-10', '2', '奚妈妈', '妈妈', '15618596602', '无', '地推', '无', 'eafc2004', 'eafc2004', '2', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 08:58:03', '2020-04-10');
INSERT INTO `student` VALUES ('S200403018', '苗雪茹', '3', '4', '女', '2020-04-10', '2', '林会中', '妈妈', '15021122469', '无', '地推', '无', 'wwwp2002', '', '1', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 09:00:28', '2020-04-10');
INSERT INTO `student` VALUES ('S200403019', '杨宸杰', '3', '5', '男', '2020-04-10', '2', '杨', '妈妈', '13311626333', '无', '学生转介绍', '无', 'wwwp2002', '', '1', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 09:32:01', '2020-04-10');
INSERT INTO `student` VALUES ('S200403020', '郑欣怡', '3', '7', '女', '2020-04-10', '2', '郑女士', '妈妈', '18018617777', '无', '学生转介绍', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 09:34:52', '2020-04-10');
INSERT INTO `student` VALUES ('S200403021', '顾俊楚玉', '3', '7', '女', '2020-04-10', '2', '刘俊玲', '妈妈', '18964685503', '无', '学生转介绍', '无', 'wwwp2002', 'wwwp2002', '3', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 09:37:23', '2020-04-10');
INSERT INTO `student` VALUES ('S200403022', '储云飞', '3', '7', '男', '2020-04-10', '2', '杨娅', '妈妈', '13601817427', '无', '学生转介绍', '无', 'wwwp2002', 'wwwp2002', '2', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 13:36:20', '2020-04-10');
INSERT INTO `student` VALUES ('S200403023', '刘语彤', '3', '6', '女', '2020-04-10', '2', '刘女士', '妈妈', '18116136181', '无', '网络', '无', 'wwwp2002', 'eafc2004', '2', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 13:39:28', '2020-04-10');
INSERT INTO `student` VALUES ('S200403024', '赵思辰', '3', '2', '女', '2020-04-10', '2', '赵冬冬', '爸爸', '13301893689', '无', '地推', '无', 'eafc2004', 'eafc2004', '3', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 13:50:49', '2020-04-10');
INSERT INTO `student` VALUES ('S200403025', '胡芷萱', '3', '6', '女', '2020-04-10', '2', '胡先生', '爸爸', '13817386162', '无', '地推', '无', 'wwwp2002', '', '2', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 13:54:08', '2020-04-10');
INSERT INTO `student` VALUES ('S200403026', '黄睿天', '3', '6', '男', '2020-04-10', '0', '江惠莲', '妈妈', '18621939331', '无', '网络', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-10', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 13:56:55', '2020-04-10');
INSERT INTO `student` VALUES ('S200403027', '沈家豪', '3', '6', '男', '2020-04-10', '2', '张丹丹', '妈妈', '15821988184', '无', '地推', '无', 'wwwp2002', 'wwwp2002', '2', '0', '0', '2020-04-10', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 14:00:29', '2020-04-10');
INSERT INTO `student` VALUES ('S200403028', '马志磊', '3', '9', '男', '2020-04-10', '2', '马传海', '爸爸', '13321836139', '无', '网络', '无', 'wwwp2002', 'eafc2004', '2', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 14:05:14', '2020-04-10');
INSERT INTO `student` VALUES ('S200403029', '奚欣悦', '3', '9', '女', '2020-04-10', '2', '奚女士', '妈妈', '13681680506', '无', '网络', '无', 'wwwp2002', 'eafc2004', '3', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 14:17:00', '2020-04-10');
INSERT INTO `student` VALUES ('S200403030', '范季嘉', '3', '3', '女', '2020-04-10', '2', '范妈妈', '妈妈', '13585888816', '无', '学生转介绍', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-10', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 14:32:42', '2020-04-11');
INSERT INTO `student` VALUES ('S200403031', '桂馨怡', '3', '7', '女', '2020-04-10', '2', '孙之凤', '妈妈', '18521367510', '无', '学生转介绍', '无', 'wwwp2002', 'eafc2004', '2', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 14:58:49', '2020-04-10');
INSERT INTO `student` VALUES ('S200403032', '韩硕', '3', '8', '女', '2020-04-10', '2', '宋亚凤', '妈妈', '15921065518', '无', '学生转介绍', '无', 'wwwp2002', 'eafc2004', '2', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 15:18:59', '2020-04-10');
INSERT INTO `student` VALUES ('S200403033', '赵宇钦', '3', '10', '男', '2020-04-10', '2', '赵洪坤', '爸爸', '13701761586', '无', '员工转介绍', '无', 'wwwp2002', '', '1', '0', '0', '2020-04-10', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 15:54:11', '2020-04-11');
INSERT INTO `student` VALUES ('S200403034', '卫天烨', '3', '6', '女', '2020-04-10', '2', '卫妈妈', '妈妈', '13918604718', '无', '学生转介绍', '无', 'wwwp2002', 'wwwp2002', '3', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 15:56:16', '2020-04-10');
INSERT INTO `student` VALUES ('S200403035', '顾静雯', '3', '3', '女', '2020-04-10', '2', '陈贵媚', '妈妈', '15201984212', '无', '其它', '无', 'wwwp2002', 'eafc2004', '3', '0', '0', '2020-04-10', '2020-04-10', '2000-01-01', '', '1', 'wwwp2002', '2020-04-10 16:04:14', '2020-04-10');
INSERT INTO `student` VALUES ('S200403036', '冯思瑶', '3', '6', '女', '2020-04-11', '2', '冯思瑶妈妈', '妈妈', '15921446335', '无', '其它', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-11 08:54:57', '2020-04-11');
INSERT INTO `student` VALUES ('S200403037', '卫钰伶', '3', '8', '女', '2020-04-11', '2', '卫钰伶妈妈', '妈妈', '15902166571', '无', '其它', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-11 09:22:41', '2020-04-11');
INSERT INTO `student` VALUES ('S200403038', '陆岳岑', '3', '8', '女', '2020-04-11', '2', '路岳岑妈妈', '妈妈', '15021300013', '无', '其它', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-11 10:24:33', '2020-04-11');
INSERT INTO `student` VALUES ('S200403039', '庄亦哲', '3', '6', '男', '2020-04-11', '2', '庄亦哲家长', '妈妈', '13918596841', '无', '其它', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-11 10:54:44', '2020-04-11');
INSERT INTO `student` VALUES ('S200403040', '徐志浩', '3', '6', '男', '2020-04-11', '2', '徐志浩家长', '妈妈', '13816780355', '无', '其它', '无', 'wwwp2002', 'wwwp2002', '1', '0', '0', '2020-04-11', '2020-04-11', '2000-01-01', '', '1', 'wwwp2002', '2020-04-11 11:09:13', '2020-04-11');
INSERT INTO `student` VALUES ('S200403041', '邸云辉', '3', '3', '男', '2020-04-11', '2', '邸云辉妈妈', '妈妈', '15921729077', '无', '其它', '无', 'jjre2002', 'eafc2004', '1', '0', '0', '2020-04-11', '2020-06-19', '2000-01-01', '', '1', 'wwwp2002', '2020-04-11 11:25:43', '2020-06-19');
INSERT INTO `student` VALUES ('S200406001', '111', '6', '3', '男', '2020-04-04', '0', '112', '妈妈', '12121212121', '无', '客户转介绍', '无', 'yuto2018', '', '1', '0', '0', '2020-04-04', '2020-04-16', '2000-01-01', '', '1', 'yuto2018', '2020-04-04 10:38:14', '2020-04-04');
INSERT INTO `student` VALUES ('S200406002', '112', '6', '3', '男', '2020-04-08', '2', '1', '奶奶', '12121212121', '无', '学生转介绍', '无', 'yuto2018', '', '2', '0', '0', '2020-04-08', '2000-01-01', '2000-01-01', '', '1', 'yuto2018', '2020-04-08 12:32:34', '2020-04-16');
INSERT INTO `student` VALUES ('S200406003', '12132', '6', '6', '男', '2020-04-11', '0', '1111', '奶奶', '22222222222', '无', '员工转介绍', '无', 'yuto2018', '', '1', '0', '0', '2020-04-11', '2000-01-01', '2000-01-01', '', '1', 'yuto2018', '2020-04-11 11:53:41', '2000-01-01');
INSERT INTO `student` VALUES ('S200501001', '李欣彦', '1', '9', '女', '2020-05-10', '1', '妈妈', '妈妈', '13818259380', '无', '其它', '奖励给了李燕凤', 'zdji2002', '', '1', '0', '0', '2020-05-10', '2020-05-10', '2000-01-01', '', '1', 'zdji2002', '2020-05-10 17:13:35', '2020-05-10');
INSERT INTO `student` VALUES ('S200501002', '李梦阳', '1', '3', '男', '2020-05-10', '1', '妈妈', '妈妈', '18001606035', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-05-10', '2020-05-10', '2000-01-01', '', '1', 'zdji2002', '2020-05-10 21:13:51', '2020-05-10');
INSERT INTO `student` VALUES ('S200501003', '李许子诺', '1', '5', '女', '2020-05-10', '1', '妈妈', '妈妈', '19927383642', '无', '其它', '无', 'zdji2002', '', '1', '0', '0', '2020-05-10', '2020-05-10', '2000-01-01', '', '1', 'zdji2002', '2020-05-10 21:14:31', '2020-05-10');
INSERT INTO `student` VALUES ('S200502001', '朱诗语', '2', '11', '女', '2020-05-04', '0', '爸爸', '爸爸', '13651633096', '无', '其它', '无', 'eekc2002', '', '3', '0', '0', '2020-05-04', '2020-05-04', '2000-01-01', '', '1', 'eekc2002', '2020-05-04 10:50:09', '2020-05-04');
INSERT INTO `student` VALUES ('S200502002', '严子杰', '2', '5', '男', '2020-05-05', '0', '妈妈', '妈妈', '15021808650', '无', '其它', '无', 'fsmp2002', '', '3', '0', '0', '2020-05-05', '2020-05-05', '2000-01-01', '', '1', 'fsmp2002', '2020-05-05 17:49:46', '2020-05-05');
INSERT INTO `student` VALUES ('S200502003', '吴雨帆', '2', '9', '男', '2020-05-10', '0', '妈妈', '妈妈', '13621795607', '无', '其它', '无', 'eekc2002', '', '3', '0', '0', '2020-05-10', '2020-05-10', '2000-01-01', '', '1', 'eekc2002', '2020-05-10 14:26:09', '2020-05-10');
INSERT INTO `student` VALUES ('S200502004', '赵奕宸', '2', '9', '男', '2020-05-10', '0', '爸爸', '爸爸', '13916428539', '无', '客户转介绍', '无', 'eekc2002', '', '3', '0', '0', '2020-05-10', '2020-05-10', '2000-01-01', '', '1', 'eekc2002', '2020-05-10 14:27:06', '2020-05-10');
INSERT INTO `student` VALUES ('S200502005', '沈琪', '2', '9', '女', '2020-05-14', '0', '妈妈', '妈妈', '13611766700', '无', '其它', '无', 'eekc2002', '', '3', '0', '0', '2020-05-14', '2020-05-14', '2000-01-01', '', '1', 'eekc2002', '2020-05-14 10:18:38', '2020-05-14');
INSERT INTO `student` VALUES ('S200502006', '张诗韵', '2', '9', '女', '2020-05-16', '0', '妈妈', '妈妈', '13611751644', '无', '其它', '无', 'eekc2002', '', '3', '0', '0', '2020-05-16', '2020-05-16', '2000-01-01', '', '1', 'eekc2002', '2020-05-16 12:45:02', '2020-05-16');
INSERT INTO `student` VALUES ('S200502007', '杨李屹', '2', '9', '男', '2020-05-16', '0', '妈妈', '妈妈', '13818394190', '无', '其它', '无', 'fsmp2002', '', '3', '0', '0', '2020-05-16', '2000-01-01', '2000-01-01', '', '1', 'fsmp2002', '2020-05-16 13:20:56', '2000-01-01');
INSERT INTO `student` VALUES ('S200503001', '万思岚', '3', '4', '女', '2020-05-05', '2', '万君杰', '爸爸', '18918900621', '无', '其它', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-05-05', '2020-05-05', '2000-01-01', '', '1', 'jjre2002', '2020-05-05 18:10:29', '2020-05-05');
INSERT INTO `student` VALUES ('S200503002', '倪宥霖', '3', '4', '男', '2020-05-05', '2', '吴志闵', '妈妈', '13701864896', '无', '学生转介绍', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-05-05', '2020-05-05', '2000-01-01', '', '1', 'jjre2002', '2020-05-05 18:25:20', '2020-05-05');
INSERT INTO `student` VALUES ('S200503003', '谭博', '3', '3', '男', '2020-05-10', '2', '欧阳娟', '妈妈', '15216768456', '无', '地推', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-05-10', '2020-06-17', '2000-01-01', '', '1', 'jjre2002', '2020-05-10 11:37:36', '2020-06-17');
INSERT INTO `student` VALUES ('S200503004', '龚雨轩', '3', '7', '女', '2020-05-20', '0', '张慧', '妈妈', '13611770507', '无', '其它', '无', 'wwwp2002', 'eafc2004', '2', '0', '0', '2020-05-20', '2020-06-09', '2000-01-01', '', '1', 'wwwp2002', '2020-05-20 13:16:38', '2020-05-20');
INSERT INTO `student` VALUES ('S200503005', '马佳怡', '3', '3', '女', '2020-05-23', '2', '胡女士', '妈妈', '13818513636', '无', '地推', '无', 'eafc2004', 'eafc2004', '3', '0', '0', '2020-05-23', '2020-05-23', '2000-01-01', '', '1', 'wwwp2002', '2020-05-23 15:35:43', '2020-05-23');
INSERT INTO `student` VALUES ('S200503006', '邱陆怡', '3', '10', '女', '2020-05-24', '0', '陆晓芬', '妈妈', '13795290733', '无', '其它', '无', 'wwwp2002', 'eafc2004', '3', '0', '0', '2020-05-24', '2020-05-24', '2000-01-01', '', '1', 'wwwp2002', '2020-05-24 15:28:39', '2020-05-24');
INSERT INTO `student` VALUES ('S200503007', '冯思诚', '3', '5', '男', '2020-05-31', '0', '吴丽超', '妈妈', '13917212684', '无', '地推', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-05-31', '2020-06-05', '2000-01-01', '', '1', 'jjre2002', '2020-05-31 14:37:33', '2020-06-05');
INSERT INTO `student` VALUES ('S200603001', '黎莉', '3', '6', '女', '2020-06-03', '0', '黎莉妈妈', '妈妈', '13918032724', '无', '客户转介绍', '无', 'wwwp2002', '', '2', '0', '0', '2020-06-03', '2020-06-03', '2000-01-01', '', '1', 'wwwp2002', '2020-06-03 18:21:22', '2020-06-03');
INSERT INTO `student` VALUES ('S200603002', '高佳琪', '3', '7', '女', '2020-06-03', '0', '高佳琪妈妈', '妈妈', '13601868847', '无', '其它', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-06-03', '2020-06-03', '2000-01-01', '', '1', 'jjre2002', '2020-06-03 19:09:11', '2020-06-03');
INSERT INTO `student` VALUES ('S200603003', '张锦哲', '3', '5', '男', '2020-06-06', '0', '陆芹', '妈妈', '13482608280', '无', '其它', '无', 'wwwp2002', 'eafc2004', '3', '0', '0', '2020-06-06', '2020-06-06', '2000-01-01', '', '1', 'eafc2004', '2020-06-06 13:27:23', '2020-06-06');
INSERT INTO `student` VALUES ('S200603004', '龙陆浩', '3', '2', '男', '2020-06-09', '0', '陆燕', '妈妈', '15000597083', '无', '学生转介绍', '无', 'wwwp2002', '', '3', '0', '0', '2020-06-09', '2020-06-09', '2000-01-01', '', '1', 'wwwp2002', '2020-06-09 17:04:06', '2020-06-09');
INSERT INTO `student` VALUES ('S200603005', '凌静妍', '3', '2', '女', '2020-06-14', '0', '凌爸爸', '爸爸', '13386287513', '无', '客户转介绍', '无', 'wwwp2002', '', '1', '0', '0', '2020-06-14', '2020-06-14', '2000-01-01', '', '1', 'wwwp2002', '2020-06-14 10:27:04', '2020-06-14');
INSERT INTO `student` VALUES ('S200603006', '孙明远', '3', '4', '男', '2020-06-14', '0', '明远妈妈', '妈妈', '15021096052', '无', '其它', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-06-14', '2020-06-14', '2000-01-01', '', '1', 'eafc2004', '2020-06-14 10:35:54', '2020-06-14');
INSERT INTO `student` VALUES ('S200603008', '张扬', '3', '3', '男', '2020-06-14', '0', '张扬妈妈', '妈妈', '13002186884', '无', '其它', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-06-14', '2020-06-14', '2000-01-01', '', '1', 'jjre2002', '2020-06-14 16:29:08', '2020-06-14');
INSERT INTO `student` VALUES ('S200603009', '刘轩宇', '3', '4', '男', '2020-06-17', '0', '轩宇妈妈', '妈妈', '15921116771', '无', '其它', '无', 'jjre2002', 'eafc2004', '3', '0', '0', '2020-06-17', '2020-06-17', '2000-01-01', '', '1', 'jjre2002', '2020-06-17 12:57:48', '2020-06-17');
INSERT INTO `student` VALUES ('S200603010', '康梓玥', '3', '6', '女', '2020-06-17', '0', '康妈妈', '妈妈', '13564104282', '无', '学生转介绍', '无', 'wwwp2002', '', '2', '0', '0', '2020-06-17', '2020-06-17', '2000-01-01', '', '1', 'wwwp2002', '2020-06-17 15:13:16', '2020-06-17');
INSERT INTO `student` VALUES ('S200603011', '王怡晨', '3', '7', '女', '2020-06-21', '0', '王爸爸', '爸爸', '13761750112', '无', '学生转介绍', '无', 'wwwp2002', '', '2', '0', '0', '2020-06-21', '2020-06-21', '2000-01-01', '', '1', 'wwwp2002', '2020-06-21 09:58:38', '2020-06-21');
INSERT INTO `student` VALUES ('S200603012', '赵梓雯', '3', '4', '女', '2020-06-26', '0', '梓雯妈妈', '妈妈', '15900470362', '无', '其它', '无', 'jjre2002', '', '3', '0', '0', '2020-06-26', '2020-06-26', '2000-01-01', '', '1', 'jjre2002', '2020-06-26 13:31:45', '2020-06-26');

-- ----------------------------
-- Table structure for `student_record`
-- ----------------------------
DROP TABLE IF EXISTS `student_record`;
CREATE TABLE `student_record` (
  `student_record_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '学生动态id',
  `student_record_student` char(10) NOT NULL COMMENT '学生id',
  `student_record_type` varchar(8) NOT NULL COMMENT '学生动态类别(0：新建档案，1：修改备注，2：跟进纪录，3：签约记录，4：更换负责人，5：退款纪录，6：删除客户，7：修改信息，8：修改优先级)',
  `student_record_content` varchar(255) NOT NULL COMMENT '学生动态内容',
  `student_record_createuser` char(8) NOT NULL COMMENT '动态创建用户',
  `student_record_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '动态创建时间',
  PRIMARY KEY (`student_record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1197 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of student_record
-- ----------------------------

-- ----------------------------
-- Table structure for `subject`
-- ----------------------------
DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject` (
  `subject_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '科目id',
  `subject_name` varchar(255) NOT NULL COMMENT '科目名称',
  `subject_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '科目可用状态(0：删除，1：可用)',
  `subject_createuser` char(8) NOT NULL COMMENT '科目创建用户',
  `subject_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '科目创建时间',
  PRIMARY KEY (`subject_id`),
  UNIQUE KEY `subject_name` (`subject_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of subject
-- ----------------------------
INSERT INTO `subject` VALUES ('1', '语文', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('2', '数学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('3', '英语', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('4', '物理', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('5', '化学', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('6', '生物', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('7', '政治', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('8', '地理', '1', 'yuto2018', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('9', '历史', '1', 'yuto2018', '2020-01-02 10:58:04');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` char(8) NOT NULL COMMENT '用户id',
  `user_password` varchar(15) NOT NULL DEFAULT '000000' COMMENT '用户密码',
  `user_name` varchar(5) NOT NULL COMMENT '用户姓名',
  `user_gender` char(1) NOT NULL DEFAULT '男' COMMENT '用户性别',
  `user_department` int(10) unsigned NOT NULL COMMENT '用户校区',
  `user_position` int(10) unsigned NOT NULL COMMENT '用户岗位',
  `user_entry_date` date NOT NULL COMMENT '用户入职日期',
  `user_cross_teaching` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户是否可以跨校区上课(0：不能跨校区上课，1：可以跨校区校区)',
  `user_phone` varchar(11) NOT NULL DEFAULT '无' COMMENT '用户手机',
  `user_wechat` varchar(20) NOT NULL DEFAULT '无' COMMENT '用户微信',
  `user_photo` varchar(20) NOT NULL DEFAULT '' COMMENT '学生照片路径',
  `user_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户可用状态(0：删除，1：可用)',
  `user_createuser` char(8) NOT NULL COMMENT '用户创建用户',
  `user_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '用户创建时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('acqd2002', '000000', '闫烁', '男', '1', '7', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:23:40');
INSERT INTO `user` VALUES ('avsj2002', '000000', '魏东', '男', '3', '12', '2020-02-24', '0', '无', '无', '', '1', 'yuto2018', '2020-02-24 12:33:03');
INSERT INTO `user` VALUES ('bcsb2003', '000000', '2', '男', '6', '7', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 10:52:04');
INSERT INTO `user` VALUES ('bdnn2002', '000000', '苏周英', '女', '2', '10', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:29:19');
INSERT INTO `user` VALUES ('beqn2003', '000000', '0', '女', '6', '3', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 12:25:12');
INSERT INTO `user` VALUES ('bltr2002', '000000', '卜令勤', '女', '1', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:45:44');
INSERT INTO `user` VALUES ('carj2002', '000000', '刘娟娟', '女', '1', '12', '2020-02-23', '1', '无', '无', '', '0', 'yuto2018', '2020-02-23 21:17:08');
INSERT INTO `user` VALUES ('ceal2006', '000000', '夏莉莉', '女', '3', '15', '2020-06-05', '0', '无', '无', '', '0', 'yuto2018', '2020-06-05 15:45:08');
INSERT INTO `user` VALUES ('cgfh2003', '000000', '31', '男', '6', '12', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 10:53:02');
INSERT INTO `user` VALUES ('chgw2002', '000000', '方梦娜', '女', '2', '14', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:36:14');
INSERT INTO `user` VALUES ('crqa2002', '529246', '魏金东', '男', '2', '12', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:32:28');
INSERT INTO `user` VALUES ('dnxn2002', '000000', '祝小飞', '男', '2', '13', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:38:21');
INSERT INTO `user` VALUES ('dtfn2002', '000000', '孙楠', '男', '1', '7', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:23:58');
INSERT INTO `user` VALUES ('eafc2004', '000000', '朱含宇', '女', '3', '7', '2020-04-09', '0', '13661737120', '无', '', '1', 'yuto2018', '2020-04-09 14:11:07');
INSERT INTO `user` VALUES ('eekc2002', '123321', '陈琳娜', '女', '2', '7', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:27:33');
INSERT INTO `user` VALUES ('eiqc2002', '000000', '成岭', '男', '3', '16', '2020-02-24', '1', '无', '无', '', '1', 'yuto2018', '2020-02-24 12:34:27');
INSERT INTO `user` VALUES ('erkf2002', '20122402kk', '李志康', '男', '4', '22', '2020-02-23', '1', '17612142402', '17612142402', '', '1', 'yuto2018', '2020-02-23 20:29:58');
INSERT INTO `user` VALUES ('erlu2002', '000000', '丁琪琪', '女', '1', '8', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:25:52');
INSERT INTO `user` VALUES ('fgwp2003', '000000', '22', '女', '6', '10', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 10:52:42');
INSERT INTO `user` VALUES ('fsmp2002', '000000', '李娟', '女', '2', '7', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:27:15');
INSERT INTO `user` VALUES ('fupn2005', '000000', '高清煜', '男', '3', '15', '2020-05-29', '0', '无', '无', '', '1', 'erkf2002', '2020-05-29 14:13:25');
INSERT INTO `user` VALUES ('gwtb2003', '000000', '32', '男', '6', '13', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 10:53:19');
INSERT INTO `user` VALUES ('heku2002', '000000', '王允', '女', '1', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:44:14');
INSERT INTO `user` VALUES ('hhxh2003', '000000', '1', '男', '6', '6', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 10:51:41');
INSERT INTO `user` VALUES ('hlfy2002', '000000', '王程程', '男', '2', '6', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:26:54');
INSERT INTO `user` VALUES ('hrov2002', '000000', '孟宁', '男', '4', '26', '2020-02-27', '1', '无', '无', '', '1', 'yuto2018', '2020-02-27 13:00:28');
INSERT INTO `user` VALUES ('hxzh2002', '000000', '张伟宏', '男', '1', '14', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:42:51');
INSERT INTO `user` VALUES ('ihag2002', '601198', '黄佳辉', '男', '4', '3', '2020-02-23', '1', '无', '无', '', '0', 'yuto2018', '2020-02-23 20:43:30');
INSERT INTO `user` VALUES ('jcex2002', 'zhang7063222', '张文举', '男', '4', '2', '2020-02-23', '1', '无', '无', '', '0', 'yuto2018', '2020-02-23 20:43:10');
INSERT INTO `user` VALUES ('jjre2002', '000000', '谭旭', '男', '3', '25', '2020-02-24', '1', '无', '无', '', '1', 'yuto2018', '2020-02-24 12:49:55');
INSERT INTO `user` VALUES ('jyfg2002', '000000', '何梦琴', '女', '2', '14', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:36:38');
INSERT INTO `user` VALUES ('kafb2003', '000000', '21', '女', '6', '9', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 10:52:27');
INSERT INTO `user` VALUES ('kago2002', '000000', '闫雪莹', '女', '3', '13', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:34:58');
INSERT INTO `user` VALUES ('kbin2003', '000000', '0909', '男', '6', '7', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 11:21:12');
INSERT INTO `user` VALUES ('knnz2002', '000000', '杨伟康', '男', '2', '8', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:28:02');
INSERT INTO `user` VALUES ('lkwc2003', '000000', '周黎1', '男', '1', '7', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 11:19:46');
INSERT INTO `user` VALUES ('mhsd2002', '000000', '刘波', '女', '3', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:35:41');
INSERT INTO `user` VALUES ('mjpe2002', '000000', '李秋兰', '女', '1', '14', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:42:11');
INSERT INTO `user` VALUES ('mwgj2002', '000000', '蒋丰丰', '男', '4', '23', '2020-02-23', '1', '无', '无', '', '0', 'yuto2018', '2020-02-23 20:41:49');
INSERT INTO `user` VALUES ('nbgj2002', 'xi100524', '孙文清', '女', '3', '25', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:49:37');
INSERT INTO `user` VALUES ('nbth2002', '123456', '熊子龙', '男', '2', '9', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:28:56');
INSERT INTO `user` VALUES ('npol2002', '000000', '张婉容', '女', '4', '21', '2020-02-27', '1', '15021913213', '无', '', '1', 'yuto2018', '2020-02-27 17:25:24');
INSERT INTO `user` VALUES ('ntgu2003', '000000', '周黎小号', '男', '1', '7', '2020-03-24', '1', '无', '无', '', '0', 'yuto2018', '2020-03-24 09:58:13');
INSERT INTO `user` VALUES ('omeo2002', '000000', '陈琼丽', '女', '1', '9', '2020-02-23', '0', '无', '无', '', '0', 'yuto2018', '2020-02-23 21:20:33');
INSERT INTO `user` VALUES ('onwp2002', '302211', '江中情', '男', '4', '4', '2020-02-23', '1', '无', '无', '', '0', 'yuto2018', '2020-02-23 20:41:10');
INSERT INTO `user` VALUES ('pbcq2004', '000000', '程琳琍', '女', '1', '14', '2020-04-15', '1', '18217197509', '无', '', '1', 'yuto2018', '2020-04-15 21:01:34');
INSERT INTO `user` VALUES ('pfdn2002', '000000', '孙敏', '男', '1', '11', '2020-02-24', '0', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:26:15');
INSERT INTO `user` VALUES ('prar2003', '000000', '11', '男', '6', '6', '2020-03-24', '0', '无', '无', '', '0', 'yuto2018', '2020-03-24 10:55:59');
INSERT INTO `user` VALUES ('pusc2003', '000000', '李大佬', '男', '1', '5', '2020-03-18', '1', '无', '无', '', '0', 'yuto2018', '2020-03-18 22:38:20');
INSERT INTO `user` VALUES ('qgxo2006', '000000', '杨静', '女', '3', '13', '2020-06-05', '0', '无', '无', '', '1', 'yuto2018', '2020-06-05 15:44:37');
INSERT INTO `user` VALUES ('robh2002', '000000', '陈翌', '男', '2', '14', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:37:28');
INSERT INTO `user` VALUES ('sbfl2002', '000000', '陈佳', '女', '1', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:40:10');
INSERT INTO `user` VALUES ('sfip2002', '000000', '周佩宁', '女', '1', '17', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:43:23');
INSERT INTO `user` VALUES ('sqce2003', '000000', '李志康顾问', '男', '1', '7', '2020-03-18', '1', '无', '无', '', '0', 'yuto2018', '2020-03-18 21:37:13');
INSERT INTO `user` VALUES ('tvix2002', '000000', '何正红', '男', '3', '25', '2020-02-24', '1', '无', '无', '', '1', 'yuto2018', '2020-02-24 12:50:19');
INSERT INTO `user` VALUES ('ujko2005', '000000', '聂加强', '男', '3', '13', '2020-05-29', '0', '无', '无', '', '1', 'erkf2002', '2020-05-29 14:13:52');
INSERT INTO `user` VALUES ('vmrs2002', '000000', '赵贤丽', '女', '1', '13', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:39:43');
INSERT INTO `user` VALUES ('waok2002', '000000', '张琳', '女', '1', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:44:39');
INSERT INTO `user` VALUES ('wsfl2002', '000000', '黄仕鹏', '男', '1', '16', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:40:50');
INSERT INTO `user` VALUES ('wwen2002', '000000', '孟倩倩', '女', '1', '16', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:46:05');
INSERT INTO `user` VALUES ('wwwp2002', '000000', '胡向红', '女', '3', '24', '2020-02-23', '0', '无', '无', '', '1', 'yuto2018', '2020-02-23 20:52:47');
INSERT INTO `user` VALUES ('xjed2002', '000000', '邹月月', '女', '4', '19', '2020-02-23', '1', '18817776100', '18817776100', '', '0', 'yuto2018', '2020-02-23 20:38:03');
INSERT INTO `user` VALUES ('xolz2006', '000000', '祝玉瑾', '女', '3', '15', '2020-06-05', '0', '无', '无', '', '1', 'yuto2018', '2020-06-05 15:45:35');
INSERT INTO `user` VALUES ('xonq2002', 'syw123', '孙有为', '男', '1', '14', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:41:11');
INSERT INTO `user` VALUES ('yaus2002', '000000', '程帅', '女', '2', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:38:40');
INSERT INTO `user` VALUES ('ybci2002', '000000', '岳千里', '男', '1', '14', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:41:41');
INSERT INTO `user` VALUES ('yclk2002', '000000', '徐家井', '男', '1', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:40:28');
INSERT INTO `user` VALUES ('yuto2018', '123456', '周黎', '男', '3', '1', '2018-09-05', '1', '无', '无', '', '1', 'yuto2018', '2020-01-02 10:13:50');
INSERT INTO `user` VALUES ('yvua2002', '000000', '王颖', '女', '3', '15', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:35:21');
INSERT INTO `user` VALUES ('zdji2002', '000000', '徐秀东', '男', '1', '6', '2020-02-23', '1', '无', '无', '', '0', 'yuto2018', '2020-02-23 21:18:45');
INSERT INTO `user` VALUES ('zmgf2002', '000000', '彭静静', '女', '1', '13', '2020-02-24', '1', '无', '无', '', '0', 'yuto2018', '2020-02-24 12:43:50');

-- ----------------------------
-- Table structure for `user_department`
-- ----------------------------
DROP TABLE IF EXISTS `user_department`;
CREATE TABLE `user_department` (
  `user_department_user` char(8) NOT NULL COMMENT '用户id',
  `user_department_department` int(10) unsigned NOT NULL COMMENT '用户校区权限',
  PRIMARY KEY (`user_department_user`,`user_department_department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_department
-- ----------------------------
INSERT INTO `user_department` VALUES ('eafc2004', '3');
INSERT INTO `user_department` VALUES ('eafc2004', '5');
INSERT INTO `user_department` VALUES ('yuto2018', '3');
INSERT INTO `user_department` VALUES ('yuto2018', '5');

-- ----------------------------
-- Table structure for `user_page`
-- ----------------------------
DROP TABLE IF EXISTS `user_page`;
CREATE TABLE `user_page` (
  `user_page_user` char(8) NOT NULL COMMENT '用户id',
  `user_page_page` varchar(40) NOT NULL COMMENT '用户权限页面',
  PRIMARY KEY (`user_page_user`,`user_page_page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_page
-- ----------------------------
INSERT INTO `user_page` VALUES ('eafc2004', 'companyClassroom');
INSERT INTO `user_page` VALUES ('eafc2004', 'companyCourse');
INSERT INTO `user_page` VALUES ('eafc2004', 'companyDepartment');
INSERT INTO `user_page` VALUES ('eafc2004', 'companySchool');
INSERT INTO `user_page` VALUES ('eafc2004', 'companySection');
INSERT INTO `user_page` VALUES ('eafc2004', 'companyUser');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationAttendedSchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationClass');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationDocument');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationDocumentCreate');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationMyAttendedSchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationMyClass');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationMySchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationMyStudent');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationSchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'educationStudent');
INSERT INTO `user_page` VALUES ('eafc2004', 'marketContract');
INSERT INTO `user_page` VALUES ('eafc2004', 'marketCustomer');
INSERT INTO `user_page` VALUES ('eafc2004', 'marketMyContract');
INSERT INTO `user_page` VALUES ('eafc2004', 'marketMyCustomer');
INSERT INTO `user_page` VALUES ('eafc2004', 'marketMyStudent');
INSERT INTO `user_page` VALUES ('eafc2004', 'marketStudent');
INSERT INTO `user_page` VALUES ('eafc2004', 'marketStudentDeleted');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationAttendedSchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationClass');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationContract');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationHour');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationMyAttendedSchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationMyContract');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationMyHour');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationMyRefund');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationMySchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationMyStudent');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationRefund');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationSchedule');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationStudent');
INSERT INTO `user_page` VALUES ('eafc2004', 'operationStudentDeleted');
INSERT INTO `user_page` VALUES ('yuto2018', 'companyClassroom');
INSERT INTO `user_page` VALUES ('yuto2018', 'companyCourse');
INSERT INTO `user_page` VALUES ('yuto2018', 'companyDepartment');
INSERT INTO `user_page` VALUES ('yuto2018', 'companySchool');
INSERT INTO `user_page` VALUES ('yuto2018', 'companySection');
INSERT INTO `user_page` VALUES ('yuto2018', 'companyUser');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationAttendedSchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationClass');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationDocument');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationDocumentCreate');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationMyAttendedSchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationMyClass');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationMySchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationMyStudent');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationSchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'educationStudent');
INSERT INTO `user_page` VALUES ('yuto2018', 'marketContract');
INSERT INTO `user_page` VALUES ('yuto2018', 'marketCustomer');
INSERT INTO `user_page` VALUES ('yuto2018', 'marketMyContract');
INSERT INTO `user_page` VALUES ('yuto2018', 'marketMyCustomer');
INSERT INTO `user_page` VALUES ('yuto2018', 'marketMyStudent');
INSERT INTO `user_page` VALUES ('yuto2018', 'marketStudent');
INSERT INTO `user_page` VALUES ('yuto2018', 'marketStudentDeleted');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationAttendedSchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationClass');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationContract');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationHour');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationMyAttendedSchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationMyContract');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationMyHour');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationMyRefund');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationMySchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationMyStudent');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationRefund');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationSchedule');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationStudent');
INSERT INTO `user_page` VALUES ('yuto2018', 'operationStudentDeleted');
