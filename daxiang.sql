/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : daxiang

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-01-21 17:23:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_activity
-- ----------------------------
DROP TABLE IF EXISTS `tp_activity`;
CREATE TABLE `tp_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动列表',
  `title` varchar(90) NOT NULL COMMENT '活动标题',
  `fu_title` varchar(150) NOT NULL COMMENT '副标题',
  `keywords` varchar(60) NOT NULL COMMENT '关键字',
  `desc` varchar(255) NOT NULL COMMENT '简介',
  `image` varchar(255) NOT NULL COMMENT '活动图片路径',
  `action` varchar(255) NOT NULL COMMENT '能帮助孩子实现什么',
  `decribe` varchar(255) NOT NULL COMMENT '参与介绍',
  `end_time` varchar(30) NOT NULL COMMENT '结束时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（1  正常  2，已到期）',
  `editorValue` text NOT NULL COMMENT '活动详情',
  `music` varchar(255) NOT NULL COMMENT '背景音乐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_activity
-- ----------------------------
INSERT INTO `tp_activity` VALUES ('1', '测试活动', '测试副标题副标题副标题', '学得会    记得牢   考的好', '测试副标题副标题副标题测试副标题副标题副标题测试副标题副标题副标题测试副标题副标题副标题测试副标题副标题副标题', '/tmp/uploads/20181229\\a3f6415749e0623fcf0b6d1ad2b6dfac.png', '测试功能1，测试功能2，测试功能3', '参与说明说明参与说明说明参与说明说明参与说明说明参与说明说明参与说明说明', '2019-1-28', '2018-12-29 15:16:04', '1', '&lt;p&gt;测试详情&lt;br/&gt;&lt;/p&gt;', '/tmp/uploads/20181229\\5869046d57b557dd378db82f41e81e30.mp4');
INSERT INTO `tp_activity` VALUES ('2', '31312121', '3131', '313', '13131', '/tmp/uploads/20181226\\45cb90e549a0d58fd4e2c77a8f154ab7.png', '31321', '31231', '2018-12-27', '2018-12-26 14:57:10', '2', '&lt;p&gt;132&lt;br/&gt;&lt;/p&gt;', '');

-- ----------------------------
-- Table structure for tp_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_access`;
CREATE TABLE `tp_admin_access` (
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '0',
  `node_id` smallint(6) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0',
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_access
-- ----------------------------

-- ----------------------------
-- Table structure for tp_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_group`;
CREATE TABLE `tp_admin_group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT 'icon小图标',
  `sort` int(11) unsigned NOT NULL DEFAULT '50',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_group
-- ----------------------------
INSERT INTO `tp_admin_group` VALUES ('1', '系统管理', '&#xe61d;', '2', '1', '', '0', '1450752856', '1481180175');
INSERT INTO `tp_admin_group` VALUES ('2', '工具', '&#xe616;', '3', '1', '', '0', '1476016712', '1481180175');
INSERT INTO `tp_admin_group` VALUES ('3', '优惠券管理', '', '50', '1', '', '0', '1545802182', '1545802182');
INSERT INTO `tp_admin_group` VALUES ('4', '活动管理', '', '50', '1', '', '0', '1545803917', '1545803917');
INSERT INTO `tp_admin_group` VALUES ('5', '会员管理', '', '50', '1', '', '0', '1545808634', '1545874585');
INSERT INTO `tp_admin_group` VALUES ('6', '分销管理', '', '50', '1', '', '0', '1545812661', '1545812661');

-- ----------------------------
-- Table structure for tp_admin_node
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_node`;
CREATE TABLE `tp_admin_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '节点类型，1-控制器 | 0-方法',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '50',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`),
  KEY `isdelete` (`isdelete`),
  KEY `sort` (`sort`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_node
-- ----------------------------
INSERT INTO `tp_admin_node` VALUES ('1', '0', '1', 'Admin', '后台管理', '后台管理，不可更改', '1', '1', '1', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('2', '1', '1', 'AdminGroup', '分组管理', ' ', '2', '1', '1', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('3', '1', '1', 'AdminNode', '节点管理', ' ', '2', '1', '2', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('4', '1', '1', 'AdminRole', '角色管理', ' ', '2', '1', '3', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('5', '1', '1', 'AdminUser', '用户管理', '', '2', '1', '4', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('6', '1', '0', 'Index', '首页', '', '2', '1', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('7', '6', '0', 'welcome', '欢迎页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('8', '6', '0', 'index', '未定义', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('9', '1', '2', 'Generate', '代码自动生成', '', '2', '1', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('10', '1', '2', 'Demo/excel', 'Excel一键导出', '', '2', '0', '2', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('11', '1', '2', 'Demo/download', '下载', '', '2', '0', '3', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('12', '1', '2', 'Demo/downloadImage', '远程图片下载', '', '2', '0', '4', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('13', '1', '2', 'Demo/mail', '邮件发送', '', '2', '0', '5', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('14', '1', '2', 'Demo/qiniu', '七牛上传', '', '2', '0', '6', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('15', '1', '2', 'Demo/hashids', 'ID加密', '', '2', '0', '7', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('16', '1', '2', 'Demo/layer', '丰富弹层', '', '2', '0', '8', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('17', '1', '2', 'Demo/tableFixed', '表格溢出', '', '2', '0', '9', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('18', '1', '2', 'Demo/ueditor', '百度编辑器', '', '2', '0', '10', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('19', '1', '2', 'Demo/imageUpload', '图片上传', '', '2', '0', '11', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('20', '1', '2', 'Demo/qrcode', '二维码生成', '', '2', '0', '12', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('21', '1', '1', 'NodeMap', '节点图', '', '2', '1', '5', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('22', '1', '1', 'WebLog', '操作日志', '', '2', '1', '6', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('23', '1', '1', 'LoginLog', '登录日志', '', '2', '1', '7', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('59', '1', '2', 'one.two.three.Four/index', '多级节点', '', '2', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('24', '23', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('25', '22', '0', 'index', '列表', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('26', '22', '0', 'detail', '详情', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('27', '21', '0', 'load', '自动导入', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('28', '21', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('29', '5', '0', 'add', '添加', '', '3', '0', '51', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('30', '21', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('31', '21', '0', 'deleteForever', '永久删除', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('32', '9', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('33', '9', '0', 'generate', '生成方法', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('34', '5', '0', 'password', '修改密码', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('35', '5', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('36', '5', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('37', '5', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('38', '4', '0', 'user', '用户列表', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('39', '4', '0', 'access', '授权', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('40', '4', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('41', '4', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('42', '4', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('43', '4', '0', 'forbid', '默认禁用操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('44', '4', '0', 'resume', '默认恢复操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('45', '3', '0', 'load', '节点快速导入测试', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('46', '3', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('47', '3', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('48', '3', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('49', '3', '0', 'forbid', '默认禁用操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('50', '3', '0', 'resume', '默认恢复操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('51', '2', '0', 'index', '首页', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('52', '2', '0', 'add', '添加', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('53', '2', '0', 'edit', '编辑', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('54', '2', '0', 'forbid', '默认禁用操作', '', '3', '0', '51', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('55', '2', '0', 'resume', '默认恢复操作', '', '3', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('56', '1', '2', 'one', '一级菜单', '', '2', '1', '13', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('60', '56', '2', 'two', '二级', '', '3', '1', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('61', '60', '2', 'three', '三级菜单', '', '4', '1', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('62', '61', '2', 'Four', '四级菜单', '', '5', '1', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('63', '1', '3', 'coupon/index', '优惠券列表', '', '2', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('64', '1', '4', 'activity/index', '活动列表', '', '2', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('65', '1', '5', 'bminfo/index', '会员列表', '', '2', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('66', '1', '6', 'user/index', '会员管理', '', '2', '0', '50', '1', '0');
INSERT INTO `tp_admin_node` VALUES ('67', '1', '6', 'yongjin/index', '佣金管理', '', '2', '0', '50', '1', '0');

-- ----------------------------
-- Table structure for tp_admin_node_load
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_node_load`;
CREATE TABLE `tp_admin_node_load` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='节点快速导入';

-- ----------------------------
-- Records of tp_admin_node_load
-- ----------------------------
INSERT INTO `tp_admin_node_load` VALUES ('4', '编辑', 'edit', '1');
INSERT INTO `tp_admin_node_load` VALUES ('5', '添加', 'add', '1');
INSERT INTO `tp_admin_node_load` VALUES ('6', '首页', 'index', '1');
INSERT INTO `tp_admin_node_load` VALUES ('7', '删除', 'delete', '1');

-- ----------------------------
-- Table structure for tp_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_role`;
CREATE TABLE `tp_admin_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `status` (`status`),
  KEY `isdelete` (`isdelete`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_role
-- ----------------------------
INSERT INTO `tp_admin_role` VALUES ('1', '0', '领导组', ' ', '1', '0', '1208784792', '1254325558');
INSERT INTO `tp_admin_role` VALUES ('2', '0', '网编组', ' ', '0', '0', '1215496283', '1454049929');

-- ----------------------------
-- Table structure for tp_admin_role_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_role_user`;
CREATE TABLE `tp_admin_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of tp_admin_role_user
-- ----------------------------

-- ----------------------------
-- Table structure for tp_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_user`;
CREATE TABLE `tp_admin_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(32) NOT NULL DEFAULT '',
  `realname` varchar(255) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` char(15) NOT NULL DEFAULT '',
  `login_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '50',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `accountpassword` (`account`,`password`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tp_admin_user
-- ----------------------------
INSERT INTO `tp_admin_user` VALUES ('1', 'admin', '超级管理员', 'e10adc3949ba59abbe56e057f20f883e', '1546673951', '127.0.0.1', '390', 'tianpian0805@gmail.com', '13121126169', '我是超级管理员', '1', '0', '1222907803', '1451033528');
INSERT INTO `tp_admin_user` VALUES ('2', 'demo', '测试', 'e10adc3949ba59abbe56e057f20f883e', '1481206367', '127.0.0.1', '5', '', '', '', '1', '0', '1476777133', '1477399793');

-- ----------------------------
-- Table structure for tp_bminfo
-- ----------------------------
DROP TABLE IF EXISTS `tp_bminfo`;
CREATE TABLE `tp_bminfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '报名人列表',
  `name` varchar(150) NOT NULL COMMENT '用户名（从微信获取）',
  `image` varchar(255) NOT NULL COMMENT '头像（从微信获取）',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `create_time` datetime NOT NULL COMMENT '报名时间',
  `pid` int(11) NOT NULL COMMENT '上级id',
  `qrcode` varchar(255) NOT NULL COMMENT '二维码图片',
  `yongjin` varchar(90) NOT NULL COMMENT '佣金',
  `open_id` varchar(60) NOT NULL COMMENT 'openID（微信）',
  `client_id` varchar(60) NOT NULL COMMENT '机器的ip地址(用于发红包）',
  `status` tinyint(2) NOT NULL COMMENT '是否分享（1 分享  2 不分享）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_bminfo
-- ----------------------------
INSERT INTO `tp_bminfo` VALUES ('1', '测试2', '/tmp/uploads/20181228\\d54c8b9a0f1c8dbb3a8c26279a42d9e7.png', '18326124596', '2018-12-28 16:36:22', '0', '/Uploads/QRcode/1545811897.png', '120', '', '', '1');
INSERT INTO `tp_bminfo` VALUES ('9', '测试1', '/tmp/uploads/20181226\\e950e2427aa60c3611eb011fa39c84c6.png', '18326991238', '2018-12-28 16:35:53', '0', '/Uploads/QRcode/1545812542.png', '100', '', '', '1');

-- ----------------------------
-- Table structure for tp_coupon
-- ----------------------------
DROP TABLE IF EXISTS `tp_coupon`;
CREATE TABLE `tp_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠券id',
  `name` varchar(90) NOT NULL COMMENT '优惠券名称',
  `desc` varchar(150) NOT NULL COMMENT '优惠券说明',
  `end_time` varchar(30) NOT NULL COMMENT '使用截止时间',
  `create_time` datetime NOT NULL COMMENT '优惠券创建时间',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `status` tinyint(2) NOT NULL COMMENT '是否使用（1 已使用  2，未使用）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_coupon
-- ----------------------------
INSERT INTO `tp_coupon` VALUES ('1', '120', '优惠券说明', '2018-12-31', '2018-12-26 13:58:23', '1', '1');
INSERT INTO `tp_coupon` VALUES ('2', '100', '优惠券说明', '2018-12-31', '2018-12-28 11:36:00', '1', '2');
INSERT INTO `tp_coupon` VALUES ('3', '199', '测试寿命是否会搜你粉丝', '2019-01-04', '2018-12-29 14:16:01', '9', '0');

-- ----------------------------
-- Table structure for tp_file
-- ----------------------------
DROP TABLE IF EXISTS `tp_file`;
CREATE TABLE `tp_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '文件类型，1-image | 2-file',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `original` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_file
-- ----------------------------
INSERT INTO `tp_file` VALUES ('1', '3', '/tmp/uploads/20181226\\13e534eb1badd9b3cf0db073f6372ba7.png', 'aa01.png', '', 'image/png', '103821', '1545805602');
INSERT INTO `tp_file` VALUES ('2', '3', '/tmp/uploads/20181226\\7ce68c7d54a315e2704feb616215ebd6.png', 'aa02.png', '', 'image/png', '207893', '1545806236');
INSERT INTO `tp_file` VALUES ('3', '3', '/tmp/uploads/20181226\\45cb90e549a0d58fd4e2c77a8f154ab7.png', 'aa02.png', '', 'image/png', '207893', '1545806287');
INSERT INTO `tp_file` VALUES ('4', '3', '/tmp/uploads/20181226\\bfcf757d94bc194419c20b1314d6d07f.png', 'aa02.png', '', 'image/png', '207893', '1545806463');
INSERT INTO `tp_file` VALUES ('5', '3', '/tmp/uploads/20181226\\ba9f37b2519f920fc9c59549c7900b7c.png', 'aa02.png', '', 'image/png', '207893', '1545806566');
INSERT INTO `tp_file` VALUES ('6', '3', '/tmp/uploads/20181226\\953e895363c0d6a8bc13e1df9b8f7067.png', 'aa02.png', '', 'image/png', '207893', '1545806727');
INSERT INTO `tp_file` VALUES ('7', '3', '/tmp/uploads/20181226\\add8fabc0ccdaa8d265e14a2fa8ccf12.png', 'left.png', '', 'image/png', '1866', '1545810008');
INSERT INTO `tp_file` VALUES ('8', '3', '/tmp/uploads/20181226\\5fe720b47e773e3e147fe2bab055b524.png', 'cn.png', '', 'image/png', '1103', '1545811182');
INSERT INTO `tp_file` VALUES ('9', '3', '/tmp/uploads/20181226\\32553ee2a286325e705b41f26c3e3bd6.png', 'l04.png', '', 'image/png', '8294', '1545811826');
INSERT INTO `tp_file` VALUES ('10', '3', '/tmp/uploads/20181226\\a0f6df4d7e43e48dc036cc22e56e7c5e.png', 'l01.png', '', 'image/png', '10466', '1545812129');
INSERT INTO `tp_file` VALUES ('11', '3', '/tmp/uploads/20181226\\5f0cfab162dc5a6899a27d5bdba319ed.png', 'l01.png', '', 'image/png', '10466', '1545812300');
INSERT INTO `tp_file` VALUES ('12', '3', '/tmp/uploads/20181226\\dfb6da59e9e940ecc0863a5166923d90.png', 'logo.png', '', 'image/png', '3955', '1545812431');
INSERT INTO `tp_file` VALUES ('13', '3', '/tmp/uploads/20181226\\e950e2427aa60c3611eb011fa39c84c6.png', 'p4.png', '', 'image/png', '120846', '1545812540');
INSERT INTO `tp_file` VALUES ('14', '3', '/tmp/uploads/20181228\\d54c8b9a0f1c8dbb3a8c26279a42d9e7.png', 'p5.png', '', 'image/png', '116890', '1545986180');
INSERT INTO `tp_file` VALUES ('15', '3', '/tmp/uploads/20181229\\d8e901b21260acbe16f9ace9a21c484c.mp4', '5acdd3b3c13421040d8f99e9e827cb8a.mp4', '', 'video/mp4', '1535459', '1546067481');
INSERT INTO `tp_file` VALUES ('16', '3', '/tmp/uploads/20181229\\3b3490886fc2fe0b1e3dbebb4d6c9404.png', 'p3.png', '', 'image/png', '97278', '1546067568');
INSERT INTO `tp_file` VALUES ('17', '3', '/tmp/uploads/20181229\\c79a42b3acd78311090cefad178604c6.mp4', '5acdd3b3c13421040d8f99e9e827cb8a.mp4', '', 'video/mp4', '1535459', '1546067577');
INSERT INTO `tp_file` VALUES ('18', '3', '/tmp/uploads/20181229\\72a55c9be049f6d2a4b9f69a55823fca.mp4', '5acdd3b3c13421040d8f99e9e827cb8a.mp4', '', 'video/mp4', '1535459', '1546067674');
INSERT INTO `tp_file` VALUES ('19', '3', '/tmp/uploads/20181229\\a3f6415749e0623fcf0b6d1ad2b6dfac.png', 'p3.png', '', 'image/png', '97278', '1546067750');
INSERT INTO `tp_file` VALUES ('20', '3', '/tmp/uploads/20181229\\5869046d57b557dd378db82f41e81e30.mp4', '5acdd3b3c13421040d8f99e9e827cb8a.mp4', '', 'video/mp4', '1535459', '1546067762');

-- ----------------------------
-- Table structure for tp_login_log
-- ----------------------------
DROP TABLE IF EXISTS `tp_login_log`;
CREATE TABLE `tp_login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `login_ip` char(15) NOT NULL DEFAULT '',
  `login_location` varchar(255) NOT NULL DEFAULT '',
  `login_browser` varchar(255) NOT NULL DEFAULT '',
  `login_os` varchar(255) NOT NULL DEFAULT '',
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_login_log
-- ----------------------------
INSERT INTO `tp_login_log` VALUES ('1', '1', '127.0.0.1', '本机地址 本机地址  ', 'Firefox(64.0)', 'Windows 10', '2018-12-26 13:26:38');
INSERT INTO `tp_login_log` VALUES ('2', '1', '127.0.0.1', '本机地址 本机地址  ', 'Firefox(64.0)', 'Windows 10', '2018-12-27 09:15:58');
INSERT INTO `tp_login_log` VALUES ('3', '1', '127.0.0.1', '本机地址 本机地址  ', 'Firefox(64.0)', 'Windows 10', '2018-12-28 10:36:05');
INSERT INTO `tp_login_log` VALUES ('4', '1', '127.0.0.1', '本机地址 本机地址  ', 'Firefox(64.0)', 'Windows 10', '2018-12-29 13:20:30');
INSERT INTO `tp_login_log` VALUES ('5', '1', '127.0.0.1', '本机地址 本机地址  ', 'Firefox(64.0)', 'Windows 10', '2019-01-05 15:39:11');

-- ----------------------------
-- Table structure for tp_node_map
-- ----------------------------
DROP TABLE IF EXISTS `tp_node_map`;
CREATE TABLE `tp_node_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL DEFAULT '' COMMENT '模块',
  `controller` varchar(255) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(255) NOT NULL DEFAULT '' COMMENT '方法',
  `method` char(6) NOT NULL DEFAULT '' COMMENT '请求方式',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '节点图描述',
  PRIMARY KEY (`id`),
  KEY `map` (`method`,`module`,`controller`,`action`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='节点图';

-- ----------------------------
-- Records of tp_node_map
-- ----------------------------

-- ----------------------------
-- Table structure for tp_one_two_three_four
-- ----------------------------
DROP TABLE IF EXISTS `tp_one_two_three_four`;
CREATE TABLE `tp_one_two_three_four` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '四级控制器主键',
  `field1` varchar(255) DEFAULT NULL COMMENT '字段一',
  `option` varchar(255) DEFAULT NULL COMMENT '选填',
  `select` varchar(255) DEFAULT NULL COMMENT '下拉框',
  `radio` varchar(255) DEFAULT NULL COMMENT '单选',
  `checkbox` varchar(255) DEFAULT NULL COMMENT '复选框',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `textarea` varchar(255) DEFAULT NULL COMMENT '文本域',
  `date` varchar(255) DEFAULT NULL COMMENT '日期',
  `mobile` varchar(255) DEFAULT NULL COMMENT '手机号',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `sort` smallint(5) DEFAULT '50' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1-正常 | 0-禁用',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态，1-删除 | 0-正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='四级控制器';

-- ----------------------------
-- Records of tp_one_two_three_four
-- ----------------------------
INSERT INTO `tp_one_two_three_four` VALUES ('1', 'yuan1994', 'tpadmin', '2', '1', null, '2222', 'https://github.com/yuan1994/tpadmin', '2016-12-07', '13012345678', 'tianpian0805@gmail.com', '50', '1', '0', '1481947278', '1481947353');

-- ----------------------------
-- Table structure for tp_user_activity
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_activity`;
CREATE TABLE `tp_user_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动-购买人关联表',
  `uid` int(11) NOT NULL COMMENT '客户id',
  `actid` int(11) NOT NULL COMMENT '活动id',
  `order_sn` varchar(60) NOT NULL COMMENT '订单编号',
  `status` tinyint(2) NOT NULL COMMENT '状态（1 已支付  2 未支付）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_activity
-- ----------------------------
INSERT INTO `tp_user_activity` VALUES ('1', '9', '1', '', '0');
INSERT INTO `tp_user_activity` VALUES ('2', '1', '1', '', '0');

-- ----------------------------
-- Table structure for tp_user_activity_share
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_activity_share`;
CREATE TABLE `tp_user_activity_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动-用户分享关联表',
  `uid` int(11) NOT NULL COMMENT '分享者id',
  `actid` int(11) NOT NULL COMMENT '活动id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_activity_share
-- ----------------------------
INSERT INTO `tp_user_activity_share` VALUES ('1', '9', '1');
INSERT INTO `tp_user_activity_share` VALUES ('2', '1', '1');

-- ----------------------------
-- Table structure for tp_web_log_001
-- ----------------------------
DROP TABLE IF EXISTS `tp_web_log_001`;
CREATE TABLE `tp_web_log_001` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志主键',
  `uid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT '访客ip',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT '访客地址',
  `os` varchar(255) NOT NULL DEFAULT '' COMMENT '操作系统',
  `browser` varchar(255) NOT NULL DEFAULT '' COMMENT '浏览器',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'url',
  `module` varchar(255) NOT NULL DEFAULT '' COMMENT '模块',
  `controller` varchar(255) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(255) NOT NULL DEFAULT '' COMMENT '方法',
  `method` char(6) NOT NULL DEFAULT '' COMMENT '请求方式',
  `data` text COMMENT '请求的param数据，serialize后的',
  `create_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `ip` (`ip`),
  KEY `create_at` (`create_at`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=540 DEFAULT CHARSET=utf8 COMMENT='网站日志';

-- ----------------------------
-- Records of tp_web_log_001
-- ----------------------------

-- ----------------------------
-- Table structure for tp_web_log_all
-- ----------------------------
DROP TABLE IF EXISTS `tp_web_log_all`;
CREATE TABLE `tp_web_log_all` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志主键',
  `uid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT '访客ip',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT '访客地址',
  `os` varchar(255) NOT NULL DEFAULT '' COMMENT '操作系统',
  `browser` varchar(255) NOT NULL DEFAULT '' COMMENT '浏览器',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'url',
  `module` varchar(255) NOT NULL DEFAULT '' COMMENT '模块',
  `controller` varchar(255) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(255) NOT NULL DEFAULT '' COMMENT '方法',
  `method` char(6) NOT NULL DEFAULT '' COMMENT '请求方式',
  `data` text COMMENT '请求的param数据，serialize后的',
  `create_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `ip` (`ip`) USING BTREE,
  KEY `create_at` (`create_at`) USING BTREE
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC INSERT_METHOD=LAST UNION=(`tp_web_log_001`) COMMENT='网站日志';

-- ----------------------------
-- Records of tp_web_log_all
-- ----------------------------
