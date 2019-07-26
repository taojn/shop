/*
 Navicat Premium Data Transfer

 Source Server         : wt
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : ceshi

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 26/07/2019 09:47:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for address
-- ----------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人',
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '电话',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '地址',
  `detail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详细地址',
  `addtime` datetime NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  CONSTRAINT `address_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `roleid` int(255) NOT NULL COMMENT '角色id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '姓名',
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (2, 1, 'admin', 'a45958517604f5cd90d6ee51ad9cfdb6', 0);
INSERT INTO `admin` VALUES (3, 3, 'asdasd', 'e10adc3949ba59abbe56e057f20f883e', 1559186303);

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'banner名',
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图片',
  `switch` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '状态 1: 显示   2: 隐藏',
  `addtime` int(255) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of banner
-- ----------------------------
INSERT INTO `banner` VALUES (4, '自行车', '5cf495427858d.jpg', '1', 1559463520);
INSERT INTO `banner` VALUES (5, 'zxczxc', '5cf49570dd17c.jpg', '1', 1559532912);
INSERT INTO `banner` VALUES (6, 'czxv', '5d170849c312f.jpg', '1', 1559532919);

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `good` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品名',
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品规格',
  `level` enum('1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '星级',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '内容',
  `fcontent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '回复内容',
  `state` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '评论状态  1:显示   2:隐藏',
  `addtime` int(11) NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE,
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`gid`) REFERENCES `good` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for good
-- ----------------------------
DROP TABLE IF EXISTS `good`;
CREATE TABLE `good`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `typeid` int(255) NOT NULL COMMENT '分类id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名',
  `num` int(255) NOT NULL COMMENT '总库存',
  `paynum` int(255) NOT NULL COMMENT '销量',
  `goodpic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品图',
  `detailpic` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '详情图',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '描述',
  `state` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '状态  1:在售  2:下架',
  `addtime` int(255) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for goodc
-- ----------------------------
DROP TABLE IF EXISTS `goodc`;
CREATE TABLE `goodc`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL COMMENT '商品id',
  `sid` int(11) NOT NULL COMMENT '尺寸id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品颜色',
  `num` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '颜色库存',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE,
  INDEX `sid`(`sid`) USING BTREE,
  CONSTRAINT `goodc_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `good` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goodc_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `gid` int(255) NOT NULL COMMENT '商品id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品尺寸',
  `num` int(255) NOT NULL COMMENT '库存',
  `price` decimal(10, 2) NOT NULL COMMENT '单价',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE,
  CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `good` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for manage
-- ----------------------------
DROP TABLE IF EXISTS `manage`;
CREATE TABLE `manage`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '模块名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of manage
-- ----------------------------
INSERT INTO `manage` VALUES (27, '管理员管理');
INSERT INTO `manage` VALUES (30, '分类管理');
INSERT INTO `manage` VALUES (31, '轮播管理');
INSERT INTO `manage` VALUES (32, '商品管理');
INSERT INTO `manage` VALUES (33, '订单管理');
INSERT INTO `manage` VALUES (34, '评论管理');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `sid` int(11) NOT NULL COMMENT '商品规格id',
  `cid` int(11) NOT NULL COMMENT '商品样式id',
  `good` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品名',
  `goodpic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品图',
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品规格',
  `num` int(11) NOT NULL COMMENT '购买数量',
  `user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收件人',
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '电话',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '地址',
  `total` decimal(10, 2) NOT NULL COMMENT '总价',
  `state` enum('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '订单状态   1:未发货(前台)  待发货(后台)  2:确认收货(前台)  已发货(后台)  3:已签收(前台)  已完成(后台)  4:已评论 ',
  `paystate` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '支付状态    1:未支付    2:已支付',
  `paytype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支付类型',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单号',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `addtime` int(255) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (1, 1, 16, 20, 51, 'a', NULL, 'X,b', 1, '张三', '13455556666', '北京,市辖区,东城区@鬼街', 12.00, '1', '1', NULL, '123123123', '', 0);

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名',
  `manageids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '拥有的角色管理权限',
  `ruleids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '拥有的具体操作权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES (1, '超管', NULL, NULL);
INSERT INTO `role` VALUES (3, 'zzz', '34,33', '459,460,461,473,475,442,443,444,445,446,447,448,449,450,451,452,454,455,456,457,458');

-- ----------------------------
-- Table structure for rule
-- ----------------------------
DROP TABLE IF EXISTS `rule`;
CREATE TABLE `rule`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slipsrule` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '简写权限名',
  `rule` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '权限名',
  `switch` enum('on','off') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'on' COMMENT '开关',
  `manageid` int(11) NOT NULL COMMENT '模块id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `manageid`(`manageid`) USING BTREE,
  CONSTRAINT `rule_ibfk_1` FOREIGN KEY (`manageid`) REFERENCES `manage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 476 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of rule
-- ----------------------------
INSERT INTO `rule` VALUES (340, 'time_sea', '根据时间搜索', 'off', 27);
INSERT INTO `rule` VALUES (341, 'name_sea', '根据姓名搜索', 'on', 27);
INSERT INTO `rule` VALUES (342, 'phone_sea', '根据电话搜索', 'off', 27);
INSERT INTO `rule` VALUES (343, 'code_sea', '根据订单编号搜索', 'off', 27);
INSERT INTO `rule` VALUES (344, 'status_sea', '根据订单状态搜索', 'off', 27);
INSERT INTO `rule` VALUES (345, 'pay_sea', '根据支付状态搜索', 'off', 27);
INSERT INTO `rule` VALUES (346, 'shipments_sea', '根据发货状态搜索', 'off', 27);
INSERT INTO `rule` VALUES (347, 'paypart_sea', '根据支付方式搜索', 'off', 27);
INSERT INTO `rule` VALUES (348, 'shipmentspart_sea', '根据配送方式搜索', 'off', 27);
INSERT INTO `rule` VALUES (349, 'name_edit', '修改姓名', 'off', 27);
INSERT INTO `rule` VALUES (350, 'phone_edit', '修改电话', 'off', 27);
INSERT INTO `rule` VALUES (351, 'pass_edit', '修改密码', 'off', 27);
INSERT INTO `rule` VALUES (352, 'price_edit', '修改金额(单)', 'off', 27);
INSERT INTO `rule` VALUES (353, 'total_edit', '修改金额(总)', 'off', 27);
INSERT INTO `rule` VALUES (354, 'status_edit', '修改状态', 'off', 27);
INSERT INTO `rule` VALUES (355, 'add', '添加', 'on', 27);
INSERT INTO `rule` VALUES (356, 'delete', '删除', 'on', 27);
INSERT INTO `rule` VALUES (391, 'time_sea', '根据时间搜索', 'off', 30);
INSERT INTO `rule` VALUES (392, 'name_sea', '根据姓名搜索', 'off', 30);
INSERT INTO `rule` VALUES (393, 'phone_sea', '根据电话搜索', 'off', 30);
INSERT INTO `rule` VALUES (394, 'code_sea', '根据订单编号搜索', 'off', 30);
INSERT INTO `rule` VALUES (395, 'status_sea', '根据订单状态搜索', 'off', 30);
INSERT INTO `rule` VALUES (396, 'pay_sea', '根据支付状态搜索', 'off', 30);
INSERT INTO `rule` VALUES (397, 'shipments_sea', '根据发货状态搜索', 'off', 30);
INSERT INTO `rule` VALUES (398, 'paypart_sea', '根据支付方式搜索', 'off', 30);
INSERT INTO `rule` VALUES (399, 'shipmentspart_sea', '根据配送方式搜索', 'off', 30);
INSERT INTO `rule` VALUES (400, 'name_edit', '修改姓名', 'off', 30);
INSERT INTO `rule` VALUES (401, 'phone_edit', '修改电话', 'off', 30);
INSERT INTO `rule` VALUES (402, 'pass_edit', '修改密码', 'off', 30);
INSERT INTO `rule` VALUES (403, 'price_edit', '修改金额(单)', 'off', 30);
INSERT INTO `rule` VALUES (404, 'total_edit', '修改金额(总)', 'off', 30);
INSERT INTO `rule` VALUES (405, 'status_edit', '修改状态', 'on', 30);
INSERT INTO `rule` VALUES (406, 'add', '添加', 'on', 30);
INSERT INTO `rule` VALUES (407, 'delete', '删除', 'on', 30);
INSERT INTO `rule` VALUES (408, 'time_sea', '根据时间搜索', 'off', 31);
INSERT INTO `rule` VALUES (409, 'name_sea', '根据姓名搜索', 'off', 31);
INSERT INTO `rule` VALUES (410, 'phone_sea', '根据电话搜索', 'off', 31);
INSERT INTO `rule` VALUES (411, 'code_sea', '根据订单编号搜索', 'off', 31);
INSERT INTO `rule` VALUES (412, 'status_sea', '根据订单状态搜索', 'off', 31);
INSERT INTO `rule` VALUES (413, 'pay_sea', '根据支付状态搜索', 'off', 31);
INSERT INTO `rule` VALUES (414, 'shipments_sea', '根据发货状态搜索', 'off', 31);
INSERT INTO `rule` VALUES (415, 'paypart_sea', '根据支付方式搜索', 'off', 31);
INSERT INTO `rule` VALUES (416, 'shipmentspart_sea', '根据配送方式搜索', 'off', 31);
INSERT INTO `rule` VALUES (417, 'name_edit', '修改姓名', 'off', 31);
INSERT INTO `rule` VALUES (418, 'phone_edit', '修改电话', 'off', 31);
INSERT INTO `rule` VALUES (419, 'pass_edit', '修改密码', 'off', 31);
INSERT INTO `rule` VALUES (420, 'price_edit', '修改金额(单)', 'off', 31);
INSERT INTO `rule` VALUES (421, 'total_edit', '修改金额(总)', 'off', 31);
INSERT INTO `rule` VALUES (422, 'status_edit', '修改状态', 'on', 31);
INSERT INTO `rule` VALUES (423, 'add', '添加', 'on', 31);
INSERT INTO `rule` VALUES (424, 'delete', '删除', 'on', 31);
INSERT INTO `rule` VALUES (425, 'time_sea', '根据时间搜索', 'on', 32);
INSERT INTO `rule` VALUES (426, 'name_sea', '根据姓名搜索', 'on', 32);
INSERT INTO `rule` VALUES (427, 'phone_sea', '根据电话搜索', 'off', 32);
INSERT INTO `rule` VALUES (428, 'code_sea', '根据订单编号搜索', 'off', 32);
INSERT INTO `rule` VALUES (429, 'status_sea', '根据订单状态搜索', 'off', 32);
INSERT INTO `rule` VALUES (430, 'pay_sea', '根据支付状态搜索', 'off', 32);
INSERT INTO `rule` VALUES (431, 'shipments_sea', '根据发货状态搜索', 'off', 32);
INSERT INTO `rule` VALUES (432, 'paypart_sea', '根据支付方式搜索', 'off', 32);
INSERT INTO `rule` VALUES (433, 'shipmentspart_sea', '根据配送方式搜索', 'off', 32);
INSERT INTO `rule` VALUES (434, 'name_edit', '修改姓名', 'off', 32);
INSERT INTO `rule` VALUES (435, 'phone_edit', '修改电话', 'off', 32);
INSERT INTO `rule` VALUES (436, 'pass_edit', '修改密码', 'off', 32);
INSERT INTO `rule` VALUES (437, 'price_edit', '修改金额(单)', 'on', 32);
INSERT INTO `rule` VALUES (438, 'total_edit', '修改金额(总)', 'off', 32);
INSERT INTO `rule` VALUES (439, 'status_edit', '修改状态', 'on', 32);
INSERT INTO `rule` VALUES (440, 'add', '添加', 'on', 32);
INSERT INTO `rule` VALUES (441, 'delete', '删除', 'on', 32);
INSERT INTO `rule` VALUES (442, 'time_sea', '根据时间搜索', 'on', 33);
INSERT INTO `rule` VALUES (443, 'name_sea', '根据姓名搜索', 'on', 33);
INSERT INTO `rule` VALUES (444, 'phone_sea', '根据电话搜索', 'on', 33);
INSERT INTO `rule` VALUES (445, 'code_sea', '根据订单编号搜索', 'on', 33);
INSERT INTO `rule` VALUES (446, 'status_sea', '根据订单状态搜索', 'on', 33);
INSERT INTO `rule` VALUES (447, 'pay_sea', '根据支付状态搜索', 'on', 33);
INSERT INTO `rule` VALUES (448, 'shipments_sea', '根据发货状态搜索', 'on', 33);
INSERT INTO `rule` VALUES (449, 'paypart_sea', '根据支付方式搜索', 'on', 33);
INSERT INTO `rule` VALUES (450, 'shipmentspart_sea', '根据配送方式搜索', 'on', 33);
INSERT INTO `rule` VALUES (451, 'name_edit', '修改姓名', 'on', 33);
INSERT INTO `rule` VALUES (452, 'phone_edit', '修改电话', 'on', 33);
INSERT INTO `rule` VALUES (453, 'pass_edit', '修改密码', 'off', 33);
INSERT INTO `rule` VALUES (454, 'price_edit', '修改金额(单)', 'on', 33);
INSERT INTO `rule` VALUES (455, 'total_edit', '修改金额(总)', 'on', 33);
INSERT INTO `rule` VALUES (456, 'status_edit', '修改状态', 'on', 33);
INSERT INTO `rule` VALUES (457, 'add', '添加', 'on', 33);
INSERT INTO `rule` VALUES (458, 'delete', '删除', 'on', 33);
INSERT INTO `rule` VALUES (459, 'time_sea', '根据时间搜索', 'on', 34);
INSERT INTO `rule` VALUES (460, 'name_sea', '根据姓名搜索', 'on', 34);
INSERT INTO `rule` VALUES (461, 'phone_sea', '根据电话搜索', 'on', 34);
INSERT INTO `rule` VALUES (462, 'code_sea', '根据订单编号搜索', 'off', 34);
INSERT INTO `rule` VALUES (463, 'status_sea', '根据订单状态搜索', 'off', 34);
INSERT INTO `rule` VALUES (464, 'pay_sea', '根据支付状态搜索', 'off', 34);
INSERT INTO `rule` VALUES (465, 'shipments_sea', '根据发货状态搜索', 'off', 34);
INSERT INTO `rule` VALUES (466, 'paypart_sea', '根据支付方式搜索', 'off', 34);
INSERT INTO `rule` VALUES (467, 'shipmentspart_sea', '根据配送方式搜索', 'off', 34);
INSERT INTO `rule` VALUES (468, 'name_edit', '修改姓名', 'off', 34);
INSERT INTO `rule` VALUES (469, 'phone_edit', '修改电话', 'off', 34);
INSERT INTO `rule` VALUES (470, 'pass_edit', '修改密码', 'off', 34);
INSERT INTO `rule` VALUES (471, 'price_edit', '修改金额(单)', 'off', 34);
INSERT INTO `rule` VALUES (472, 'total_edit', '修改金额(总)', 'off', 34);
INSERT INTO `rule` VALUES (473, 'status_edit', '修改状态', 'on', 34);
INSERT INTO `rule` VALUES (474, 'add', '添加', 'off', 34);
INSERT INTO `rule` VALUES (475, 'delete', '删除', 'on', 34);

-- ----------------------------
-- Table structure for shopcar
-- ----------------------------
DROP TABLE IF EXISTS `shopcar`;
CREATE TABLE `shopcar`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `sid` int(11) NOT NULL COMMENT '商品规格id',
  `cid` int(11) NOT NULL COMMENT '商品样式id',
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品规格 样式',
  `num` int(11) NULL DEFAULT NULL COMMENT '数量',
  `total` decimal(10, 2) NULL DEFAULT NULL COMMENT '总价',
  `addtime` int(11) NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE,
  CONSTRAINT `shopcar_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shopcar_ibfk_2` FOREIGN KEY (`gid`) REFERENCES `good` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for type
-- ----------------------------
DROP TABLE IF EXISTS `type`;
CREATE TABLE `type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父类id',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '路径',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分类名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of type
-- ----------------------------
INSERT INTO `type` VALUES (1, 0, '0,', 'aaz');
INSERT INTO `type` VALUES (2, 1, '0,1,', 'bbb');
INSERT INTO `type` VALUES (3, 2, '0,1,2,', 'cccz');
INSERT INTO `type` VALUES (8, 0, '0,', 'sdd');
INSERT INTO `type` VALUES (9, 8, '0,8,', 'zz');
INSERT INTO `type` VALUES (10, 9, '0,8,9,', '消息');
INSERT INTO `type` VALUES (37, 9, '0,8,9,', '消息');
INSERT INTO `type` VALUES (38, 2, '0,1,2,', '消息');
INSERT INTO `type` VALUES (39, 2, '0,1,2,', 'zz');
INSERT INTO `type` VALUES (40, 1, '0,1,', 'vx');
INSERT INTO `type` VALUES (41, 1, '0,1,', 'zxc');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '会员名',
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号',
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '电话',
  `state` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '状态 1:正常  2:封号 ',
  `addtime` int(255) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE COMMENT '账号唯一'
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, '阿', 'user', 'e10adc3949ba59abbe56e057f20f883e', '13344445555', '1', 0);

SET FOREIGN_KEY_CHECKS = 1;
