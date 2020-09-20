/*
 Navicat Premium Data Transfer

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3306
 Source Schema         : yuteng

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 15/09/2020 14:32:28
*/

SET NAMES utf8mb4;

-- ----------------------------
-- Table structure for class
-- ----------------------------
DROP TABLE IF EXISTS `class`;
CREATE TABLE `class`  (
  `class_id` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '班级id',
  `class_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '班级名称',
  `class_department` int(10) UNSIGNED NOT NULL COMMENT '班级校区',
  `class_grade` int(10) UNSIGNED NOT NULL COMMENT '班级年级',
  `class_subject` int(10) UNSIGNED NOT NULL COMMENT '班级科目',
  `class_teacher` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '负责教师',
  `class_max_num` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '班级最大人数',
  `class_current_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '班级当前人数',
  `class_schedule_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '班级课程安排数量',
  `class_attended_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '班级上课记录数量',
  `class_remark` varchar(140) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '班级备注',
  `class_last_lesson_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '班级上次上课日期',
  `class_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '班级可用状态(0：删除，1：可用)',
  `class_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '班级创建用户',
  `class_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '班级创建时间',
  PRIMARY KEY (`class_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for classroom
-- ----------------------------
DROP TABLE IF EXISTS `classroom`;
CREATE TABLE `classroom`  (
  `classroom_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '教室id',
  `classroom_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教室名称',
  `classroom_department` int(10) UNSIGNED NOT NULL COMMENT '教室校区',
  `classroom_student_num` int(10) UNSIGNED NOT NULL COMMENT '教室容纳人数',
  `classroom_type` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教室类型',
  `classroom_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '教室可用状态(0：删除，1：可用)',
  `classroom_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教室创建用户',
  `classroom_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '教室创建时间',
  PRIMARY KEY (`classroom_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 74 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contract
-- ----------------------------
DROP TABLE IF EXISTS `contract`;
CREATE TABLE `contract`  (
  `contract_id` char(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '购课合同id',
  `contract_department` int(10) UNSIGNED NOT NULL COMMENT '购课合同校区',
  `contract_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `contract_course_num` int(10) UNSIGNED NOT NULL COMMENT '课程数量',
  `contract_original_hour` decimal(10, 1) UNSIGNED NOT NULL COMMENT '购买课时总数',
  `contract_free_hour` decimal(10, 1) UNSIGNED NOT NULL COMMENT '赠送课时总数',
  `contract_total_hour` decimal(10, 1) UNSIGNED NOT NULL COMMENT '合计课时总数',
  `contract_original_price` decimal(10, 2) NOT NULL COMMENT '购课合同原金额',
  `contract_discount_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '共计优惠',
  `contract_total_price` decimal(10, 2) NOT NULL COMMENT '购课合同实付金额',
  `contract_date` date NOT NULL COMMENT '购课合同日期',
  `contract_payment_method` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '购课付款方式',
  `contract_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '购课合同备注',
  `contract_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '签约类型(0：首次签约，1：续约)',
  `contract_extra_fee` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '额外费用(手续费)',
  `contract_checked` tinyint(4) NOT NULL DEFAULT 0 COMMENT '购课合同复核状态(0：未复核，1：已复核)',
  `contract_checked_user` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '购课合同复核用户',
  `contract_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加用户',
  `contract_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `contract_section` int(10) UNSIGNED NOT NULL COMMENT '签约部门(0：招生部门，1：运营部门)',
  `contract_paid_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '购课合同实付金额',
  PRIMARY KEY (`contract_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contract_course
-- ----------------------------
DROP TABLE IF EXISTS `contract_course`;
CREATE TABLE `contract_course`  (
  `contract_course_contract` char(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '购课合同id',
  `contract_course_course` int(10) UNSIGNED NOT NULL COMMENT '课程id',
  `contract_course_original_hour` decimal(10, 1) UNSIGNED NOT NULL COMMENT '购买课时数量',
  `contract_course_free_hour` decimal(10, 1) UNSIGNED NOT NULL COMMENT '赠送课时数量',
  `contract_course_total_hour` decimal(10, 1) UNSIGNED NOT NULL COMMENT '合计课时数量',
  `contract_course_discount_rate` decimal(4, 2) NOT NULL DEFAULT 1.00 COMMENT '折扣优惠',
  `contract_course_discount_amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '金额优惠',
  `contract_course_discount_total` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '共计优惠',
  `contract_course_original_unit_price` decimal(10, 2) NOT NULL COMMENT '课程原单价',
  `contract_course_actual_unit_price` decimal(10, 2) NOT NULL COMMENT '课程现单价',
  `contract_course_original_price` decimal(10, 2) NOT NULL COMMENT '购课原金额',
  `contract_course_total_price` decimal(10, 2) NOT NULL COMMENT '购课实付金额',
  `contract_course_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加用户',
  `contract_course_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`contract_course_contract`, `contract_course_course`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course`  (
  `course_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '课程id',
  `course_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程名称',
  `course_department` int(10) UNSIGNED NOT NULL COMMENT '课程校区',
  `course_quarter` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程季度',
  `course_grade` int(10) UNSIGNED NOT NULL COMMENT '课程年级',
  `course_subject` int(10) UNSIGNED NOT NULL COMMENT '课程科目',
  `course_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程分类',
  `course_unit_price` decimal(10, 2) NOT NULL COMMENT '课程单价',
  `course_time` int(10) UNSIGNED NOT NULL COMMENT '课程时间',
  `course_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '无' COMMENT '课程备注',
  `course_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '课程可用状态(0：删除，1：可用)',
  `course_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程创建用户',
  `course_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程创建时间',
  PRIMARY KEY (`course_id`) USING BTREE,
  UNIQUE INDEX `course_name`(`course_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 208 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for course_type
-- ----------------------------
DROP TABLE IF EXISTS `course_type`;
CREATE TABLE `course_type`  (
  `course_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '课程类型id',
  `course_type_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程类型名称',
  `course_type_icon_path` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程类型图标路径',
  `course_type_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '课程类型可用状态(0：删除，1：可用)',
  `course_type_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程类型创建用户',
  `course_typecreatetime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程类型创建时间',
  PRIMARY KEY (`course_type_id`) USING BTREE,
  UNIQUE INDEX `course_type_name`(`course_type_name`) USING BTREE,
  UNIQUE INDEX `course_type_icon_path`(`course_type_icon_path`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department`  (
  `department_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '校区id',
  `department_name` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '校区名称',
  `department_phone1` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '校区电话1',
  `department_phone2` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '校区电话2',
  `department_location` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '校区地址',
  `department_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '校区可用状态(0：删除，1：可用)(0：删除，1：可用)',
  `department_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '校区创建用户',
  `department_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '校区创建时间',
  PRIMARY KEY (`department_id`) USING BTREE,
  UNIQUE INDEX `department_name`(`department_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for document
-- ----------------------------
DROP TABLE IF EXISTS `document`;
CREATE TABLE `document`  (
  `document_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '教案id',
  `document_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教案名称',
  `document_department` int(10) UNSIGNED NOT NULL COMMENT '教案校区',
  `document_subject` int(10) UNSIGNED NOT NULL COMMENT '教案科目',
  `document_grade` int(10) UNSIGNED NOT NULL COMMENT '教案年级',
  `document_semester` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教案学期',
  `document_file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教案文件名',
  `document_file_size` decimal(4, 2) NOT NULL COMMENT '教案文件大小',
  `document_path` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教案路径',
  `document_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教案创建用户',
  `document_download_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '教案下载次数',
  `document_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '教案创建时间',
  PRIMARY KEY (`document_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grade
-- ----------------------------
DROP TABLE IF EXISTS `grade`;
CREATE TABLE `grade`  (
  `grade_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '年级id',
  `grade_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '年级名称',
  `grade_type` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '年级类型',
  `grade_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '年级可用状态(0：删除，1：可用)',
  `grade_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '年级创建用户',
  `grade_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '年级创建时间',
  PRIMARY KEY (`grade_id`) USING BTREE,
  UNIQUE INDEX `grade_name`(`grade_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for hour
-- ----------------------------
DROP TABLE IF EXISTS `hour`;
CREATE TABLE `hour`  (
  `hour_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `hour_course` int(10) UNSIGNED NOT NULL COMMENT '课程id',
  `hour_remain` decimal(10, 1) UNSIGNED NOT NULL COMMENT '剩余课时',
  `hour_used` decimal(10, 1) UNSIGNED NOT NULL DEFAULT 0.0 COMMENT '已用课时',
  `hour_average_price` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '平均单价',
  PRIMARY KEY (`hour_student`, `hour_course`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for hour_update_record
-- ----------------------------
DROP TABLE IF EXISTS `hour_update_record`;
CREATE TABLE `hour_update_record`  (
  `hour_update_record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '修改课时记录id',
  `hour_update_record_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `hour_update_record_course` int(10) UNSIGNED NOT NULL COMMENT '课程id',
  `hour_update_record_remain_before` decimal(10, 1) UNSIGNED NOT NULL DEFAULT 0.0 COMMENT '修改前课时',
  `hour_update_record_average_price_before` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '修改前单价',
  `hour_update_record_remain_after` decimal(10, 1) UNSIGNED NOT NULL DEFAULT 0.0 COMMENT '修改后课时',
  `hour_update_record_average_price_after` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '修改后单价',
  `hour_update_record_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '修改备注',
  `hour_update_record_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加用户',
  `hour_update_record_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`hour_update_record_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 358 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member`  (
  `member_class` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '班级id',
  `member_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `member_amount` decimal(10, 1) NOT NULL DEFAULT 3.0 COMMENT 'keshishu',
  `member_course` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '课程id',
  `member_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加用户',
  `member_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`member_class`, `member_student`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for participant
-- ----------------------------
DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant`  (
  `participant_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '上课成员记录id',
  `participant_schedule` int(10) UNSIGNED NOT NULL COMMENT '课程安排id',
  `participant_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生成员id',
  `participant_attend_status` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '学生考勤状态(1：正常，2：请假，3：旷课)',
  `participant_course` int(10) UNSIGNED NOT NULL COMMENT '扣除课程id',
  `participant_amount` decimal(10, 1) UNSIGNED NOT NULL COMMENT '扣除课程课时数量',
  `participant_checked` tinyint(4) NOT NULL DEFAULT 0 COMMENT '上课记录复核状态(0：待审核，1：已审核)',
  `participant_checked_user` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '上课记录复核用户',
  `participant_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程成员添加用户',
  `participant_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程成员添加时间',
  PRIMARY KEY (`participant_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11530 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payment_method
-- ----------------------------
DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE `payment_method`  (
  `payment_method_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '支付方式id',
  `payment_method_name` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支付方式名称',
  `payment_method_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '支付方式可用状态(0：删除，1：可用)',
  `payment_method_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支付方式创建用户',
  `payment_method_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '支付方式创建时间',
  PRIMARY KEY (`payment_method_id`) USING BTREE,
  UNIQUE INDEX `payment_method_name`(`payment_method_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for position
-- ----------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position`  (
  `position_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '岗位id',
  `position_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '岗位名称',
  `position_section` int(10) UNSIGNED NOT NULL COMMENT '岗位所属部门',
  `position_level` int(10) UNSIGNED NOT NULL COMMENT '岗位等级',
  `position_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '岗位可用状态(0：删除，1：可用)',
  `position_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '岗位创建用户',
  `position_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '岗位创建时间',
  PRIMARY KEY (`position_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for refund
-- ----------------------------
DROP TABLE IF EXISTS `refund`;
CREATE TABLE `refund`  (
  `refund_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '退款原因id',
  `refund_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `refund_course` int(10) UNSIGNED NOT NULL COMMENT '课程id',
  `refund_remain` decimal(10, 1) UNSIGNED NOT NULL COMMENT '退款剩余课时数量',
  `refund_used` decimal(10, 1) UNSIGNED NOT NULL COMMENT '退款前已使用课时数量',
  `refund_unit_price` decimal(10, 2) NOT NULL COMMENT '购课原单价',
  `refund_amount` decimal(10, 2) NOT NULL COMMENT '实际退款金额',
  `refund_reason` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '退费原因',
  `refund_payment_method` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '退费付款方式',
  `refund_date` date NOT NULL COMMENT '退费日期',
  `refund_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '退费备注',
  `refund_checked` tinyint(4) NOT NULL DEFAULT 0 COMMENT '退费审核状态(0：未审核，1：已审核)',
  `refund_checked_user` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '退费复核用户',
  `refund_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '添加用户',
  `refund_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`refund_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for refund_reason
-- ----------------------------
DROP TABLE IF EXISTS `refund_reason`;
CREATE TABLE `refund_reason`  (
  `refund_reason_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '退款原因id',
  `refund_reason_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '退款原因名称',
  `refund_reason_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '退款原因可用状态(0：删除，1：可用)',
  `refund_reason_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '退款原因创建用户',
  `refund_reason_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '退款原因创建时间',
  PRIMARY KEY (`refund_reason_id`) USING BTREE,
  UNIQUE INDEX `refund_reason_name`(`refund_reason_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for schedule
-- ----------------------------
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule`  (
  `schedule_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '课程安排id',
  `schedule_department` int(10) UNSIGNED NOT NULL COMMENT '课程安排校区',
  `schedule_participant` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生或班级id',
  `schedule_participant_type` tinyint(4) NOT NULL COMMENT '上课成员类型(0：学生，1：班级)',
  `schedule_teacher` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '教师id',
  `schedule_course` int(10) UNSIGNED NOT NULL COMMENT '课程id',
  `schedule_subject` int(10) UNSIGNED NOT NULL COMMENT '课程科目',
  `schedule_grade` int(10) UNSIGNED NOT NULL COMMENT '课程年级',
  `schedule_classroom` int(10) UNSIGNED NOT NULL COMMENT '课程教室',
  `schedule_date` date NOT NULL COMMENT '课程安排日期',
  `schedule_start` time(0) NOT NULL COMMENT '课程安排上课时间',
  `schedule_end` time(0) NOT NULL COMMENT '课程安排下课时间',
  `schedule_time` int(10) UNSIGNED NOT NULL COMMENT '课程时长',
  `schedule_student_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '课程人数',
  `schedule_attended_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '正常上课人数',
  `schedule_leave_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '请假人数',
  `schedule_absence_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '旷课人数',
  `schedule_attended` tinyint(4) NOT NULL DEFAULT 0 COMMENT '课程安排考勤状态(0：待考勤，1：已上课)',
  `schedule_attended_user` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '课程安排考勤用户',
  `schedule_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程安排添加用户',
  `schedule_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程安排添加时间',
  PRIMARY KEY (`schedule_id`) USING BTREE,
  INDEX `index_schedule_techer`(`schedule_teacher`) USING BTREE,
  INDEX `index_schedule_participant`(`schedule_participant`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14371 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for school
-- ----------------------------
DROP TABLE IF EXISTS `school`;
CREATE TABLE `school`  (
  `school_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '学校id',
  `school_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学校名称',
  `school_department` int(10) UNSIGNED NOT NULL COMMENT '学校校区',
  `school_location` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学校地址',
  `school_type` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学校类型',
  `school_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '学校可用状态(0：删除，1：可用)',
  `school_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学校创建用户',
  `school_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '学校创建时间',
  PRIMARY KEY (`school_id`) USING BTREE,
  UNIQUE INDEX `school_name`(`school_name`) USING BTREE,
  UNIQUE INDEX `school_location`(`school_location`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for section
-- ----------------------------
DROP TABLE IF EXISTS `section`;
CREATE TABLE `section`  (
  `section_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '部门id',
  `section_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '部门名称',
  `section_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '部门可用状态(0：删除，1：可用)',
  `section_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '部门创建用户',
  `section_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '部门创建时间',
  PRIMARY KEY (`section_id`) USING BTREE,
  UNIQUE INDEX `section_name`(`section_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source
-- ----------------------------
DROP TABLE IF EXISTS `source`;
CREATE TABLE `source`  (
  `source_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '来源id',
  `source_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '来源名称',
  `source_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '来源可用状态(0：删除，1：可用)',
  `source_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '来源创建用户',
  `source_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '来源创建时间',
  PRIMARY KEY (`source_id`) USING BTREE,
  UNIQUE INDEX `source_name`(`source_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for student
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student`  (
  `student_id` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `student_name` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生姓名',
  `student_department` int(10) UNSIGNED NOT NULL COMMENT '学生校区',
  `student_grade` int(10) UNSIGNED NOT NULL COMMENT '学生年级',
  `student_gender` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '男' COMMENT '学生性别',
  `student_birthday` date NOT NULL COMMENT '学生生日',
  `student_school` int(10) UNSIGNED NOT NULL COMMENT '学生学校',
  `student_guardian` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生监护人姓名',
  `student_guardian_relationship` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生监护人关系',
  `student_phone` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生电话',
  `student_wechat` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生微信',
  `student_source` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生来源',
  `student_remark` varchar(140) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生备注',
  `student_consultant` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '学生课程顾问',
  `student_class_adviser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '学生班主任',
  `student_follow_level` int(10) NOT NULL DEFAULT 1 COMMENT '学生跟进优先级',
  `student_follow_num` int(10) NOT NULL DEFAULT 0 COMMENT '学生跟进次数',
  `student_contract_num` int(10) NOT NULL DEFAULT 0 COMMENT '学生签约次数',
  `student_last_follow_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生上次跟进日期',
  `student_last_contract_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生上次签约日期',
  `student_last_lesson_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生上次上课日期',
  `student_photo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '学生照片路径',
  `student_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '学生可用状态(0：删除，1：可用)',
  `student_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生创建用户',
  `student_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '学生创建时间',
  `student_first_contract_date` date NOT NULL DEFAULT '2000-01-01' COMMENT '学生首次签约日期',
  PRIMARY KEY (`student_id`) USING BTREE,
  UNIQUE INDEX `student_name`(`student_name`, `student_department`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for student_record
-- ----------------------------
DROP TABLE IF EXISTS `student_record`;
CREATE TABLE `student_record`  (
  `student_record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '学生动态id',
  `student_record_student` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生id',
  `student_record_type` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生动态类别(0：新建档案，1：修改备注，2：跟进纪录，3：签约记录，4：更换负责人，5：退款纪录，6：删除客户，7：修改信息，8：修改优先级)',
  `student_record_content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学生动态内容',
  `student_record_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '动态创建用户',
  `student_record_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '动态创建时间',
  PRIMARY KEY (`student_record_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3538 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for subject
-- ----------------------------
DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject`  (
  `subject_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '科目id',
  `subject_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '科目名称',
  `subject_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '科目可用状态(0：删除，1：可用)',
  `subject_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '科目创建用户',
  `subject_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '科目创建时间',
  PRIMARY KEY (`subject_id`) USING BTREE,
  UNIQUE INDEX `subject_name`(`subject_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `user_id` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户id',
  `user_password` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '000000' COMMENT '用户密码',
  `user_name` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户姓名',
  `user_gender` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '男' COMMENT '用户性别',
  `user_department` int(10) UNSIGNED NOT NULL COMMENT '用户校区',
  `user_position` int(10) UNSIGNED NOT NULL COMMENT '用户岗位',
  `user_entry_date` date NOT NULL COMMENT '用户入职日期',
  `user_cross_teaching` tinyint(4) NOT NULL DEFAULT 0 COMMENT '用户是否可以跨校区上课(0：不能跨校区上课，1：可以跨校区校区)',
  `user_phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '无' COMMENT '用户手机',
  `user_wechat` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '无' COMMENT '用户微信',
  `user_photo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '学生照片路径',
  `user_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '用户可用状态(0：删除，1：可用)',
  `user_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户创建用户',
  `user_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '用户创建时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `user_name`(`user_name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_department
-- ----------------------------
DROP TABLE IF EXISTS `user_department`;
CREATE TABLE `user_department`  (
  `user_department_user` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户id',
  `user_department_department` int(10) UNSIGNED NOT NULL COMMENT '用户校区权限',
  PRIMARY KEY (`user_department_user`, `user_department_department`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for candidate
-- ----------------------------
DROP TABLE IF EXISTS `candidate`;
CREATE TABLE `candidate`  (
  `candidate_id` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '候选人id',
  `candidate_name` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '候选人姓名',
  `candidate_gender` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '男' COMMENT '候选人性别',
  `candidate_position` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '求职岗位',
  `candidate_phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '无' COMMENT '手机',
  `candidate_wechat` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '无' COMMENT '微信',
  `candidate_interviewer` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '候选人面试官',
  `candidate_comment` varchar(140) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '面试意见',
  `candidate_resume` int(10) UNSIGNED NOT NULL COMMENT '候选人简历',
  `candidate_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '用户可用状态(0：已转正，1：未转正)',
  `candidate_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户创建用户',
  `candidate_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '用户创建时间',
  PRIMARY KEY (`candidate_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for archive
-- ----------------------------
DROP TABLE IF EXISTS `archive`;
CREATE TABLE `archive`  (
  `archive_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '档案id',
  `archive_user` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '档案用户',
  `archive_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '档案名称',
  `archive_file_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '档案文件名',
  `archive_path` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '档案路径',
  `archive_createuser` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '档案创建用户',
  `archive_createtime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '档案创建时间',
  PRIMARY KEY (`archive_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for `access`
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `access_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '页面权限id',
  `access_category` varchar(10) NOT NULL COMMENT '类别名称',
  `access_page` varchar(10) NOT NULL COMMENT '页面名称',
  `access_feature` varchar(10) NOT NULL COMMENT '功能名称',
  `access_url` varchar(40) NOT NULL COMMENT '页面url',
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `user_access`
-- ----------------------------
DROP TABLE IF EXISTS `user_access`;
CREATE TABLE `user_access` (
  `user_access_user` char(8) NOT NULL COMMENT '用户id',
  `user_access_access` varchar(40) NOT NULL COMMENT '用户功能权限',
  PRIMARY KEY (`user_access_user`,`user_access_access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


