-- ----------------------------
-- Table structure for `access`
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `access_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '页面权限id',
  `access_category` varchar(10) NOT NULL COMMENT '类别名称',
  `access_page` varchar(10) NOT NULL COMMENT '页面名称',
  `access_feature` varchar(10) NOT NULL COMMENT '功能名称',
  `access_url` varchar(40) NOT NULL UNIQUE COMMENT '页面url',
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Records of access
-- ----------------------------
-- 公司管理 --
	-- 校区设置 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '校区设置', '查看', '/company/department');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '校区设置', '添加', '/company/department/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '校区设置', '修改', '/company/department/edit');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '校区设置', '删除', '/company/department/delete');
	-- 课程设置 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '课程设置', '查看', '/company/course');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '课程设置', '添加', '/company/course/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '课程设置', '修改', '/company/course/edit');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '课程设置', '删除', '/company/course/delete');
	-- 教室设置 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '教室设置', '查看', '/company/classroom');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '教室设置', '添加', '/company/classroom/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '教室设置', '修改', '/company/classroom/edit');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '教室设置', '删除', '/company/classroom/delete');
	-- 部门架构 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('公司管理', '部门架构', '全部', '/company/section');
	
-- 人事管理 --
	-- 用户管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '用户管理', '查看', '/humanResource/user');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '用户管理', '添加', '/humanResource/user/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '用户管理', '权限', '/humanResource/user/access');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '用户管理', '密码重置', '/humanResource/user/password/restore');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '用户管理', '离职', '/humanResource/user/delete');
	-- 面试用户 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '面试用户', '查看', '/humanResource/candidate');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '面试用户', '添加', '/humanResource/candidate/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '面试用户', '转正', '/humanResource/candidate/upgrade');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '面试用户', '删除', '/humanResource/candidate/delete');
	-- 离职用户 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '离职用户', '查看', '/humanResource/user/deleted');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '离职用户', '恢复', '/humanResource/user/deleted/restore');
	-- 员工档案 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '员工档案', '查看', '/humanResource/archive');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '员工档案', '添加', '/humanResource/archive/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '员工档案', '下载', '/humanResource/archive/download');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('人事管理', '员工档案', '删除', '/humanResource/archive/delete');
	
-- 招生中心 --
	-- 未签约学生 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '未签约学生', '查看', '/market/customer');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '未签约学生', '添加', '/market/customer/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '未签约学生', '签约', '/market/customer/contract/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '未签约学生', '删除', '/market/customer/delete');
	-- 学生管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '学生管理', '查看', '/market/student');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '学生管理', '签约', '/market/student/contract/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '学生管理', '删除', '/market/student/delete');
	-- 签约管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '签约管理', '查看', '/market/contract');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('招生中心', '签约管理', '删除', '/market/contract/delete');
	
-- 运营中心 --
	-- 学生管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '学生管理', '查看', '/operation/student');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '学生管理', '签约', '/operation/student/contract/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '学生管理', '排课', '/operation/student/schedule/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '学生管理', '删除', '/operation/student/delete');
	-- 学生课时 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '学生课时', '查看', '/operation/hour');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '学生课时', '修改', '/operation/hour/edit');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '学生课时', '退费', '/operation/hour/refund/create');
	-- 班级管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '班级管理', '查看', '/operation/class');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '班级管理', '添加', '/operation/class/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '班级管理', '排课', '/operation/class/schedule/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '班级管理', '删除', '/operation/class/delete');
	-- 课程安排 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '课程安排', '查看', '/operation/schedule');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '课程安排', '点名', '/operation/schedule/attend');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '课程安排', '删除', '/operation/schedule/delete');
	-- 上课记录 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '上课记录', '查看', '/operation/attendedSchedule');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '上课记录', '删除', '/operation/attendedSchedule/delete');
	-- 课程表 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '课程表', '查看', '/operation/calendar');
	-- 签约管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '签约管理', '查看', '/operation/contract');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '签约管理', '删除', '/operation/contract/delete');
	-- 退费管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '退费管理', '查看', '/operation/refund');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '退费管理', '删除', '/operation/refund/delete');
	-- 离校学生 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '离校学生', '查看', '/operation/student/deleted');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('运营中心', '离校学生', '恢复', '/operation/student/deleted/restore');
	
-- 教学中心 --
	-- 学生管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '学生管理', '查看', '/education/student');
	-- 班级管理 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '班级管理', '查看', '/education/class');
	-- 课程安排 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '课程安排', '查看', '/education/schedule');
	-- 上课记录 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '上课记录', '查看', '/education/attendedSchedule');
	-- 课程表 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '课程表', '查看', '/education/calendar');
	-- 教案中心 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '教案中心', '查看', '/education/document');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '教案中心', '添加', '/education/document/create');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '教案中心', '下载', '/education/document/download');
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('教学中心', '教案中心', '删除', '/education/document/delete');
	
	
-- 统计中心 --
	-- 校区签约统计 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('统计中心', '校区签约统计', '查看', '/finance/contract/department');
	-- 个人签约统计 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('统计中心', '个人签约统计', '查看', '/finance/contract/user');
	-- 校区课消统计 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('统计中心', '校区课消统计', '查看', '/finance/consumption/department');
	-- 个人课消统计 --
	INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
	VALUES ('统计中心', '个人课消统计', '查看', '/finance/consumption/user');
	
	
-- 学生详情 --
INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
VALUES ('详情资料', '学生详情', '查看', '/student');
-- 班级详情 --
INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
VALUES ('详情资料', '班级详情', '查看', '/class');
-- 用户详情 --
INSERT INTO `access`(access_category, access_page, access_feature, access_url) 
VALUES ('详情资料', '用户详情', '查看', '/user');

