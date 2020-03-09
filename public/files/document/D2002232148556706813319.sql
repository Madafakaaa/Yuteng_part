/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 80012
Source Host           : localhost:3306
Source Database       : yuteng

Target Server Type    : MYSQL
Target Server Version : 80012
File Encoding         : 65001

Date: 2020-02-22 17:28:07
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `class_remark` varchar(140) NOT NULL COMMENT '班级备注',
  `class_last_lesson_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '班级上次上课日期',
  `class_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '班级可用状态(0：删除，1：可用)',
  `class_createuser` char(8) NOT NULL COMMENT '班级创建用户',
  `class_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '班级创建时间',
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`)
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
  PRIMARY KEY (`classroom_id`),
  UNIQUE KEY `classroom_name` (`classroom_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of classroom
-- ----------------------------
INSERT INTO `classroom` VALUES ('1', '小教室1', '1', '5', '小班教室', '1', 'qqqq1111', '2020-01-02 10:59:02');
INSERT INTO `classroom` VALUES ('2', '小教室2', '1', '5', '小班教室', '1', 'qqqq1111', '2020-01-02 10:59:15');
INSERT INTO `classroom` VALUES ('3', '一对一教室1', '1', '1', '一对一教室', '1', 'qqqq1111', '2020-01-02 10:59:56');
INSERT INTO `classroom` VALUES ('4', '一对一教室2', '1', '1', '一对一教室', '1', 'qqqq1111', '2020-01-02 11:00:07');

-- ----------------------------
-- Table structure for `contract`
-- ----------------------------
DROP TABLE IF EXISTS `contract`;
CREATE TABLE `contract` (
  `contract_id` char(12) NOT NULL COMMENT '购课合同id',
  `contract_department` int(10) unsigned NOT NULL COMMENT '购课合同校区',
  `contract_student` char(10) NOT NULL COMMENT '学生id',
  `contract_course_num` int(10) unsigned NOT NULL COMMENT '课程数量',
  `contract_original_hour` int(10) unsigned NOT NULL COMMENT '购买课时总数',
  `contract_free_hour` int(10) unsigned NOT NULL COMMENT '赠送课时总数',
  `contract_total_hour` int(10) unsigned NOT NULL COMMENT '合计课时总数',
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
  `contract_course_original_hour` int(10) unsigned NOT NULL COMMENT '购买课时数量',
  `contract_course_free_hour` int(10) unsigned NOT NULL COMMENT '赠送课时数量',
  `contract_course_total_hour` int(10) unsigned NOT NULL COMMENT '合计课时数量',
  `contract_course_discount_rate` decimal(4,2) NOT NULL DEFAULT '1.00' COMMENT '折扣优惠',
  `contract_course_discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额优惠',
  `contract_course_discount_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '共计优惠',
  `contract_course_original_unit_price` decimal(10,2) NOT NULL COMMENT '课程原单价',
  `contract_course_actual_unit_price` decimal(10,2) NOT NULL COMMENT '课程现单价',
  `contract_course_original_price` decimal(10,2) NOT NULL COMMENT '购课原金额',
  `contract_course_total_price` decimal(10,2) NOT NULL COMMENT '购课实付金额',
  `contract_course_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '合同课程状态(0：已退费，1：正常)',
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES ('1', '三年级1V1', '0', '全年', '3', '0', '一对一', '155.00', '40', '0', '1', 'qqqq1111', '2020-01-03 10:54:59');
INSERT INTO `course` VALUES ('2', '五年级1V1', '0', '全年', '5', '0', '一对一', '165.00', '40', '0', '1', 'qqqq1111', '2020-01-03 10:55:32');
INSERT INTO `course` VALUES ('3', '四年级1V1', '0', '全年', '4', '0', '一对一', '160.00', '40', '0', '1', 'qqqq1111', '2020-01-03 10:55:53');
INSERT INTO `course` VALUES ('4', '六年级1V1', '0', '全年', '6', '0', '一对一', '175.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:34:00');
INSERT INTO `course` VALUES ('5', '七年级1V1', '0', '全年', '7', '0', '一对一', '180.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:34:52');
INSERT INTO `course` VALUES ('6', '八年级1V1', '0', '全年', '8', '0', '一对一', '190.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:35:29');
INSERT INTO `course` VALUES ('7', '九年级1V1', '0', '全年', '9', '0', '一对一', '200.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:36:02');
INSERT INTO `course` VALUES ('8', '高一1V1', '0', '全年', '10', '0', '一对一', '220.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:36:34');
INSERT INTO `course` VALUES ('9', '高二1V1', '0', '全年', '11', '0', '一对一', '230.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:37:05');
INSERT INTO `course` VALUES ('10', '高三1V1', '0', '全年', '12', '0', '一对一', '250.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:38:11');
INSERT INTO `course` VALUES ('11', '三年级1V6', '0', '全年', '3', '0', '一对六', '85.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:39:12');
INSERT INTO `course` VALUES ('12', '四年级1V6', '0', '全年', '4', '0', '一对六', '90.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:41:43');
INSERT INTO `course` VALUES ('13', '五年级1V6', '0', '全年', '5', '0', '一对六', '95.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:42:33');
INSERT INTO `course` VALUES ('14', '六年级1V6', '0', '全年', '6', '0', '一对六', '100.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:43:08');
INSERT INTO `course` VALUES ('15', '七年级1V6', '0', '全年', '7', '0', '一对六', '105.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:43:37');
INSERT INTO `course` VALUES ('16', '八年级1V6', '0', '全年', '8', '0', '一对六', '110.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:44:05');
INSERT INTO `course` VALUES ('17', '九年级1V6', '0', '全年', '9', '0', '一对六', '120.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:44:43');
INSERT INTO `course` VALUES ('18', '高一1V3', '0', '全年', '10', '0', '一对六', '150.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:49:05');
INSERT INTO `course` VALUES ('19', '高二1V3', '0', '全年', '11', '0', '一对六', '165.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:49:46');
INSERT INTO `course` VALUES ('20', '高三1V3', '0', '全年', '12', '0', '一对六', '180.00', '40', '', '1', 'qqqq1111', '2020-02-06 17:50:21');

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
INSERT INTO `course_type` VALUES ('1', '一对一', '/img/icons/class_types/course_type_1.png', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `course_type` VALUES ('2', '一对六', '/img/icons/class_types/course_type_2.png', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `course_type` VALUES ('3', '班课', '/img/icons/class_types/course_type_3.png', '1', 'qqqq1111', '2020-01-02 10:58:04');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('1', '惠南一校', '02158011172', '18817598931', '上海市惠南镇城南路1号博佳大楼3楼', '1', 'qqqq1111', '2020-01-02 10:45:29');
INSERT INTO `department` VALUES ('2', '惠南二校', '02158011172', '18817598931', '上海市惠南镇拱极路3031号4楼', '1', 'qqqq1111', '2020-01-02 10:46:30');
INSERT INTO `department` VALUES ('3', '周浦校区', '02158075681', '18017764285', '周浦镇关岳西路136弄君领国际306室', '1', 'qqqq1111', '2020-02-06 16:55:38');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
INSERT INTO `grade` VALUES ('1', '小一', '小学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('2', '小二', '小学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('3', '小三', '小学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('4', '小四', '小学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('5', '小五', '小学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('6', '预备', '初中', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('7', '初一', '初中', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('8', '初二', '初中', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('9', '初三', '初中', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('10', '高一', '高中', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('11', '高二', '高中', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `grade` VALUES ('12', '高三', '高中', '1', 'qqqq1111', '2020-01-02 10:58:04');

-- ----------------------------
-- Table structure for `hour`
-- ----------------------------
DROP TABLE IF EXISTS `hour`;
CREATE TABLE `hour` (
  `hour_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '剩余课时id',
  `hour_contract` char(12) NOT NULL COMMENT '购课合同id',
  `hour_student` char(10) NOT NULL COMMENT '学生id',
  `hour_course` int(10) unsigned NOT NULL COMMENT '课程id',
  `hour_original` int(10) unsigned NOT NULL COMMENT '原有课时',
  `hour_remain` int(10) unsigned NOT NULL COMMENT '剩余课时',
  `hour_used` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已用课时',
  `hour_remain_free` int(10) unsigned NOT NULL COMMENT '剩余赠送课时',
  `hour_used_free` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已用赠送课时',
  `hour_refunded` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已退课时',
  `hour_createuser` char(8) NOT NULL COMMENT '添加用户',
  `hour_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`hour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hour
-- ----------------------------

-- ----------------------------
-- Table structure for `member`
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_class` char(10) NOT NULL COMMENT '班级id',
  `member_student` char(10) NOT NULL COMMENT '学生id',
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
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of page
-- ----------------------------
INSERT INTO `page` VALUES ('archive', '员工档案');
INSERT INTO `page` VALUES ('classroom', '教室设置');
INSERT INTO `page` VALUES ('course', '课程设置');
INSERT INTO `page` VALUES ('department', '校区设置');
INSERT INTO `page` VALUES ('educationAttendedScheduleAll', '教学中心-上课记录管理');
INSERT INTO `page` VALUES ('educationAttendedScheduleMy', '教学中心-我的上课记录');
INSERT INTO `page` VALUES ('educationClassAll', '教学中心-班级管理');
INSERT INTO `page` VALUES ('educationClassMy', '教学中心-我的班级');
INSERT INTO `page` VALUES ('educationDocument', '教学中心-教案中心');
INSERT INTO `page` VALUES ('educationDocumentCreate', '教学中心-上传教案');
INSERT INTO `page` VALUES ('educationScheduleAll', '教学中心-课程安排管理');
INSERT INTO `page` VALUES ('educationScheduleMy', '教学中心-我的课程安排');
INSERT INTO `page` VALUES ('educationStudentAll', '教学中心-学生管理');
INSERT INTO `page` VALUES ('educationStudentMy', '教学中心-我的学生');
INSERT INTO `page` VALUES ('marketContractAll', '招生中心-签约管理');
INSERT INTO `page` VALUES ('marketContractCreate', '招生中心-签约合同');
INSERT INTO `page` VALUES ('marketContractMy', '招生中心-我的签约');
INSERT INTO `page` VALUES ('marketCustomerAll', '招生中心-客户管理');
INSERT INTO `page` VALUES ('marketCustomerMy', '招生中心-我的客户');
INSERT INTO `page` VALUES ('marketFollowerEdit', '招生中心-修改负责人');
INSERT INTO `page` VALUES ('marketmarketRefundCreate', '招生中心-学生退费');
INSERT INTO `page` VALUES ('marketMyCustomerCreate', '招生中心-我的客户录入');
INSERT INTO `page` VALUES ('marketPublicCustomerCreate', '招生中心-公共客户录入');
INSERT INTO `page` VALUES ('marketRefundAll', '招生中心-退费管理');
INSERT INTO `page` VALUES ('marketRefundMy', '招生中心-我的退费');
INSERT INTO `page` VALUES ('marketStudentMy', '招生中心-我的学生');
INSERT INTO `page` VALUES ('operationAttendedScheduleAll', '运营中心-上课记录');
INSERT INTO `page` VALUES ('operationAttendedScheduleMy', '运营中心-我的学生上课记录');
INSERT INTO `page` VALUES ('operationClassAll', '运营中心-班级管理');
INSERT INTO `page` VALUES ('operationClassCreate', '运营中心-新建班级');
INSERT INTO `page` VALUES ('operationClassScheduleAll', '运营中心-班级课程');
INSERT INTO `page` VALUES ('operationClassScheduleCreate', '运营中心-班级排课');
INSERT INTO `page` VALUES ('operationContractAll', '运营中心-签约管理');
INSERT INTO `page` VALUES ('operationContractCreate', '运营中心-签约合同');
INSERT INTO `page` VALUES ('operationContractMy', '运营中心-我的签约');
INSERT INTO `page` VALUES ('operationFollowerEdit', '运营中心-修改负责人');
INSERT INTO `page` VALUES ('operationRefundAll', '运营中心-退费管理');
INSERT INTO `page` VALUES ('operationRefundCreate', '运营中心-学生退费');
INSERT INTO `page` VALUES ('operationRefundMy', '运营中心-我的退费');
INSERT INTO `page` VALUES ('operationScheduleMy', '运营中心-我的学生课程安排');
INSERT INTO `page` VALUES ('operationStudentAll', '运营中心-学生管理');
INSERT INTO `page` VALUES ('operationStudentMy', '运营中心-我的学生');
INSERT INTO `page` VALUES ('operationStudentScheduleAll', '运营中心-学生课程');
INSERT INTO `page` VALUES ('operationStudentScheduleCreate', '运营中心-学生排课');
INSERT INTO `page` VALUES ('school', '公立学校');
INSERT INTO `page` VALUES ('section', '部门架构');
INSERT INTO `page` VALUES ('user', '用户管理');

-- ----------------------------
-- Table structure for `participant`
-- ----------------------------
DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
  `participant_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '上课成员记录id',
  `participant_schedule` int(10) unsigned NOT NULL COMMENT '课程安排id',
  `participant_student` char(10) NOT NULL COMMENT '学生成员id',
  `participant_attend_status` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '学生考勤状态(1：正常，2：请假，3：旷课)',
  `participant_hour` int(10) unsigned NOT NULL COMMENT '扣除剩余课时id',
  `participant_amount` int(10) unsigned NOT NULL COMMENT '扣除课程课时数量',
  `participant_checked` tinyint(4) NOT NULL DEFAULT '0' COMMENT '上课记录复核状态(0：待审核，1：已审核)',
  `participant_checked_user` char(8) NOT NULL DEFAULT '' COMMENT '上课记录复核用户',
  `participant_createuser` char(8) NOT NULL COMMENT '课程成员添加用户',
  `participant_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程成员添加时间',
  PRIMARY KEY (`participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
INSERT INTO `payment_method` VALUES ('1', '现金', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('2', '银行', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('3', '微信', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('4', '支付宝', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `payment_method` VALUES ('5', '其它', '1', 'qqqq1111', '2020-01-02 10:58:04');

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO `position` VALUES ('1', '总经办', '1', '0', '1', 'qqqq1111', '2020-01-01 21:06:19');
INSERT INTO `position` VALUES ('2', '课程顾问', '2', '7', '1', 'qqqq1111', '2020-02-06 17:03:43');
INSERT INTO `position` VALUES ('3', '咨询主管', '2', '5', '1', 'qqqq1111', '2020-02-06 17:04:05');
INSERT INTO `position` VALUES ('4', '咨询经理', '2', '3', '1', 'qqqq1111', '2020-02-06 17:04:21');
INSERT INTO `position` VALUES ('5', '班主任', '3', '7', '1', 'qqqq1111', '2020-02-06 17:05:22');
INSERT INTO `position` VALUES ('6', '运营主管', '3', '5', '1', 'qqqq1111', '2020-02-06 17:05:38');
INSERT INTO `position` VALUES ('7', '二校咨询主管', '2', '5', '1', 'qqqq1111', '2020-02-06 17:09:56');
INSERT INTO `position` VALUES ('8', '运营经理', '3', '3', '1', 'qqqq1111', '2020-02-06 17:10:51');
INSERT INTO `position` VALUES ('9', '教学经理', '4', '3', '1', 'qqqq1111', '2020-02-06 17:11:27');
INSERT INTO `position` VALUES ('10', '教学主管', '4', '5', '1', 'qqqq1111', '2020-02-06 17:11:43');
INSERT INTO `position` VALUES ('11', '学科教师', '4', '7', '1', 'qqqq1111', '2020-02-06 17:12:00');
INSERT INTO `position` VALUES ('12', '教研院院长', '5', '3', '1', 'qqqq1111', '2020-02-06 17:12:26');
INSERT INTO `position` VALUES ('13', '教研员', '5', '5', '1', 'qqqq1111', '2020-02-06 17:12:40');
INSERT INTO `position` VALUES ('14', '人事经理', '6', '3', '1', 'qqqq1111', '2020-02-06 17:13:19');
INSERT INTO `position` VALUES ('15', '财务专员', '7', '5', '1', 'qqqq1111', '2020-02-06 17:13:41');
INSERT INTO `position` VALUES ('16', '行政主管', '8', '5', '1', 'qqqq1111', '2020-02-06 17:13:58');
INSERT INTO `position` VALUES ('17', '咨询主管', '2', '4', '1', 'qqqq1111', '2020-02-07 12:25:00');
INSERT INTO `position` VALUES ('18', '咨询主管', '2', '6', '1', 'qqqq1111', '2020-02-07 12:25:15');
INSERT INTO `position` VALUES ('19', '课程顾问', '2', '8', '1', 'qqqq1111', '2020-02-07 12:25:35');
INSERT INTO `position` VALUES ('20', '课程顾问', '2', '9', '1', 'qqqq1111', '2020-02-07 12:25:53');
INSERT INTO `position` VALUES ('21', '运营主管', '3', '4', '1', 'qqqq1111', '2020-02-07 12:26:15');
INSERT INTO `position` VALUES ('22', '运营主管', '3', '6', '1', 'qqqq1111', '2020-02-07 12:26:42');
INSERT INTO `position` VALUES ('23', '班主任', '3', '8', '1', 'qqqq1111', '2020-02-07 12:27:05');
INSERT INTO `position` VALUES ('24', '班主任', '3', '9', '1', 'qqqq1111', '2020-02-07 12:27:32');
INSERT INTO `position` VALUES ('25', '教学主管', '4', '4', '1', 'qqqq1111', '2020-02-07 12:27:55');
INSERT INTO `position` VALUES ('26', '教学主管', '4', '6', '1', 'qqqq1111', '2020-02-07 12:28:07');
INSERT INTO `position` VALUES ('27', '学科教师', '4', '8', '1', 'qqqq1111', '2020-02-07 12:28:20');
INSERT INTO `position` VALUES ('28', '学科教师', '4', '9', '1', 'qqqq1111', '2020-02-07 12:28:34');
INSERT INTO `position` VALUES ('29', '教研员', '5', '4', '1', 'qqqq1111', '2020-02-07 12:31:32');
INSERT INTO `position` VALUES ('30', '教研员', '5', '6', '1', 'qqqq1111', '2020-02-07 12:31:45');

-- ----------------------------
-- Table structure for `refund`
-- ----------------------------
DROP TABLE IF EXISTS `refund`;
CREATE TABLE `refund` (
  `refund_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '退款原因id',
  `refund_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '退费类型(0：咨询部退款，1：运营部退款)',
  `refund_contract` char(12) NOT NULL COMMENT '购课合同id',
  `refund_hour` int(10) unsigned NOT NULL COMMENT '剩余课时id',
  `refund_course` int(10) unsigned NOT NULL COMMENT '课程id',
  `refund_department` int(10) unsigned NOT NULL COMMENT '退费校区',
  `refund_student` char(10) NOT NULL COMMENT '学生id',
  `refund_remain_hour` int(10) unsigned NOT NULL COMMENT '退款正常课时数量',
  `refund_free_hour` int(10) unsigned NOT NULL COMMENT '退款赠送课时数量',
  `refund_total_hour` int(10) unsigned NOT NULL COMMENT '退款合计课时数量',
  `refund_used_hour` int(10) unsigned NOT NULL COMMENT '已使用课时数量',
  `refund_actual_total_price` decimal(10,2) NOT NULL COMMENT '购课实付金额',
  `refund_original_unit_price` decimal(10,2) NOT NULL COMMENT '购课原单价',
  `refund_fine` decimal(10,2) NOT NULL COMMENT '违约金金额',
  `refund_amount` decimal(10,2) NOT NULL COMMENT '可退款金额',
  `refund_actual_amount` decimal(10,2) NOT NULL COMMENT '实退款金额',
  `refund_reason` varchar(10) NOT NULL COMMENT '退费原因',
  `refund_payment_method` varchar(5) NOT NULL COMMENT '退费付款方式',
  `refund_date` date NOT NULL COMMENT '退费日期',
  `refund_remark` varchar(255) NOT NULL COMMENT '退费备注',
  `refund_checked` tinyint(4) NOT NULL DEFAULT '0' COMMENT '退费审核状态(0：未审核，1：已审核)',
  `refund_checked_user` char(8) NOT NULL DEFAULT '' COMMENT '退费复核用户',
  `refund_createuser` char(8) NOT NULL COMMENT '添加用户',
  `refund_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`refund_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
INSERT INTO `refund_reason` VALUES ('1', '教学质量', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('2', '学生转课', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('3', '学生转学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('4', '购课过多', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('5', '收费价格', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('6', '学生纪律', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `refund_reason` VALUES ('7', '其它原因', '1', 'qqqq1111', '2020-01-02 10:58:04');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
INSERT INTO `school` VALUES ('1', '惠南大区', '1', '惠南镇', '其它', '1', 'qqqq1111', '2020-02-06 22:10:27');
INSERT INTO `school` VALUES ('2', '周浦大区', '3', '周浦镇', '其它', '1', 'qqqq1111', '2020-02-07 16:31:31');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of section
-- ----------------------------
INSERT INTO `section` VALUES ('1', '个性化事业部', '1', 'qqqq1111', '2020-01-01 21:06:19');
INSERT INTO `section` VALUES ('2', '咨询部', '1', 'qqqq1111', '2020-02-06 17:01:44');
INSERT INTO `section` VALUES ('3', '运营部', '1', 'qqqq1111', '2020-02-06 17:02:30');
INSERT INTO `section` VALUES ('4', '教学部', '1', 'qqqq1111', '2020-02-06 17:02:47');
INSERT INTO `section` VALUES ('5', '教研院', '1', 'qqqq1111', '2020-02-06 17:03:02');
INSERT INTO `section` VALUES ('6', '人事部', '1', 'qqqq1111', '2020-02-06 17:12:47');
INSERT INTO `section` VALUES ('7', '财务部', '1', 'qqqq1111', '2020-02-06 17:12:54');
INSERT INTO `section` VALUES ('8', '行政部', '1', 'qqqq1111', '2020-02-06 17:13:04');
INSERT INTO `section` VALUES ('9', '乔思学堂', '1', 'qqqq1111', '2020-02-07 12:33:02');
INSERT INTO `section` VALUES ('10', '班课事业部', '1', 'qqqq1111', '2020-02-07 12:36:52');

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
INSERT INTO `source` VALUES ('1', '学生转介绍', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('2', '客户转介绍', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('3', '员工转介绍', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('4', '短信', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('5', '广告', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('6', '传单', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('7', '网络', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('8', '地推', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `source` VALUES ('9', '其它', '1', 'qqqq1111', '2020-01-02 10:58:04');

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
  `student_customer_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '学生客户状态(0：客户，1：学生)',
  `student_consultant` char(8) NOT NULL DEFAULT '' COMMENT '学生课程顾问',
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
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `student_name` (`student_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of student
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
INSERT INTO `subject` VALUES ('1', '语文', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('2', '数学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('3', '英语', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('4', '物理', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('5', '化学', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('6', '生物', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('7', '政治', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('8', '地理', '1', 'qqqq1111', '2020-01-02 10:58:04');
INSERT INTO `subject` VALUES ('9', '历史', '1', 'qqqq1111', '2020-01-02 10:58:04');

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
INSERT INTO `user` VALUES ('qqqq1111', '000000', '用户一', '男', '1', '1', '2018-09-05', '0', '无', '无', '', '1', 'qqqq1111', '2020-01-02 10:13:50');
INSERT INTO `user` VALUES ('qqqq2222', '000000', '课程顾问一', '男', '1', '3', '2020-02-20', '0', '无', '无', '', '1', 'qqqq1111', '2020-02-20 18:52:40');
INSERT INTO `user` VALUES ('qqqq3333', '000000', '班主任一', '男', '1', '24', '2020-02-20', '0', '无', '无', '', '1', 'qqqq1111', '2020-02-20 18:53:50');
INSERT INTO `user` VALUES ('qqqq4444', '000000', '教师一', '男', '1', '27', '2020-02-20', '0', '无', '无', '', '1', 'qqqq1111', '2020-02-20 18:54:08');

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
INSERT INTO `user_department` VALUES ('qqqq1111', '1');
INSERT INTO `user_department` VALUES ('qqqq1111', '2');
INSERT INTO `user_department` VALUES ('qqqq1111', '3');

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
INSERT INTO `user_page` VALUES ('qqqq1111', 'archive');
INSERT INTO `user_page` VALUES ('qqqq1111', 'classroom');
INSERT INTO `user_page` VALUES ('qqqq1111', 'course');
INSERT INTO `user_page` VALUES ('qqqq1111', 'department');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationAttendedScheduleAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationAttendedScheduleMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationClassAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationClassMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationDocument');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationDocumentCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationScheduleAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationScheduleMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationStudentAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'educationStudentMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketContractAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketContractCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketContractMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketCustomerAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketCustomerMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketFollowerEdit');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketmarketRefundCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketMyCustomerCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketPublicCustomerCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketRefundAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketRefundMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'marketStudentMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationAttendedScheduleAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationAttendedScheduleMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationClassAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationClassCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationClassScheduleAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationClassScheduleCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationContractAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationContractCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationContractMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationFollowerEdit');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationRefundAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationRefundCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationRefundMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationScheduleMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationStudentAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationStudentMy');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationStudentScheduleAll');
INSERT INTO `user_page` VALUES ('qqqq1111', 'operationStudentScheduleCreate');
INSERT INTO `user_page` VALUES ('qqqq1111', 'school');
INSERT INTO `user_page` VALUES ('qqqq1111', 'section');
INSERT INTO `user_page` VALUES ('qqqq1111', 'user');
