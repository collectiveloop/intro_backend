/*
Navicat MySQL Data Transfer

Source Server         : Local connection
Source Server Version : 100126
Source Host           : localhost:3306
Source Database       : intro_app

Target Server Type    : MYSQL
Target Server Version : 100126
File Encoding         : 65001

Date: 2017-12-01 17:21:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ip_contacts_pending
-- ----------------------------
DROP TABLE IF EXISTS `ip_contacts_pending`;
CREATE TABLE `ip_contacts_pending` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_user` double NOT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '0 pendiente, 1:aceptada, 2: rechazada',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`) USING BTREE,
  CONSTRAINT `ip_contacts_pending_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `ip_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_contacts_pending
-- ----------------------------
INSERT INTO `ip_contacts_pending` VALUES ('26', '152', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-27 16:08:08', '2017-11-27 20:08:08');
INSERT INTO `ip_contacts_pending` VALUES ('40', '152', 'g dfgdfgfdg dfgdfgdfgdfgdg', 'dgdfgdf@fgfdgdgd.com', '0', '2017-12-01 20:50:25', '2017-12-01 20:50:25');
INSERT INTO `ip_contacts_pending` VALUES ('41', '152', 'sdfsd sdfsdf sdfsdf sdfsdfsd', 'ghjghgh@gfhgfhf.com', '0', '2017-12-01 20:54:26', '2017-12-01 20:54:26');

-- ----------------------------
-- Table structure for ip_gainings
-- ----------------------------
DROP TABLE IF EXISTS `ip_gainings`;
CREATE TABLE `ip_gainings` (
  `id` double NOT NULL AUTO_INCREMENT,
  `gain_es` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gain_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_gainings
-- ----------------------------
INSERT INTO `ip_gainings` VALUES ('1', 'Opción 1', 'Option 1', '0000-00-00 00:00:00', null);
INSERT INTO `ip_gainings` VALUES ('2', 'Opción 2', 'Option 2', '0000-00-00 00:00:00', null);
INSERT INTO `ip_gainings` VALUES ('3', 'Opción 3', 'Option 3', '0000-00-00 00:00:00', null);
INSERT INTO `ip_gainings` VALUES ('5', 'Opción 4', 'Option 4', '2017-11-08 15:37:26', '2017-11-08 15:37:26');

-- ----------------------------
-- Table structure for ip_gainings_intros
-- ----------------------------
DROP TABLE IF EXISTS `ip_gainings_intros`;
CREATE TABLE `ip_gainings_intros` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_intro` double NOT NULL,
  `id_gain` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_gain` (`id_gain`),
  KEY `id_intro` (`id_intro`),
  CONSTRAINT `ip_gainings_intros_ibfk_1` FOREIGN KEY (`id_gain`) REFERENCES `ip_gainings` (`id`),
  CONSTRAINT `ip_gainings_intros_ibfk_2` FOREIGN KEY (`id_intro`) REFERENCES `ip_intros` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_gainings_intros
-- ----------------------------
INSERT INTO `ip_gainings_intros` VALUES ('37', '79', '1', '0000-00-00 00:00:00', null);
INSERT INTO `ip_gainings_intros` VALUES ('38', '80', '5', '0000-00-00 00:00:00', null);
INSERT INTO `ip_gainings_intros` VALUES ('39', '81', '5', '0000-00-00 00:00:00', null);

-- ----------------------------
-- Table structure for ip_general
-- ----------------------------
DROP TABLE IF EXISTS `ip_general`;
CREATE TABLE `ip_general` (
  `id` double NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_general
-- ----------------------------
INSERT INTO `ip_general` VALUES ('1', 'email_contact_us', 'renshocontact@gmail.com');

-- ----------------------------
-- Table structure for ip_intros
-- ----------------------------
DROP TABLE IF EXISTS `ip_intros`;
CREATE TABLE `ip_intros` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_user` double NOT NULL,
  `id_friend_1` double NOT NULL,
  `id_friend_2` double NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `friend_1_info` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `friend_2_info` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user_3` (`id_user`,`id_friend_1`,`id_friend_2`),
  KEY `id_friend_1` (`id_friend_1`),
  KEY `id_friend_2` (`id_friend_2`),
  KEY `id_user` (`id_user`,`id_friend_1`),
  KEY `id_user_2` (`id_user`,`id_friend_2`),
  CONSTRAINT `ip_intros_ibfk_1` FOREIGN KEY (`id_friend_1`) REFERENCES `ip_users_friends` (`id`),
  CONSTRAINT `ip_intros_ibfk_2` FOREIGN KEY (`id_friend_2`) REFERENCES `ip_users_friends` (`id`),
  CONSTRAINT `ip_intros_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `ip_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_intros
-- ----------------------------
INSERT INTO `ip_intros` VALUES ('79', '152', '31', '29', 'Hhj', 'Gg', 'Hhh', '2017-11-22 19:07:46', '2017-11-22 19:07:46');
INSERT INTO `ip_intros` VALUES ('80', '152', '31', '32', 'asdasdada', 'aasdasdas', 'dasdasdas', '2017-11-27 20:00:07', '2017-11-27 20:00:07');
INSERT INTO `ip_intros` VALUES ('81', '152', '59', '61', 'sdfsdfsdfs', 'fsdfsdfs', 'sdfsdfsd', '2017-12-01 20:55:16', '2017-12-01 20:55:16');

-- ----------------------------
-- Table structure for ip_messages
-- ----------------------------
DROP TABLE IF EXISTS `ip_messages`;
CREATE TABLE `ip_messages` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_room` double NOT NULL,
  `id_user` double NOT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `ip_messages_ibfk_1` (`id_room`),
  CONSTRAINT `ip_messages_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `ip_rooms` (`id`),
  CONSTRAINT `ip_messages_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `ip_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of ip_messages
-- ----------------------------
INSERT INTO `ip_messages` VALUES ('88', '52', '152', 'erwerwer', '2017-11-22 19:58:03', '2017-11-22 19:58:03');
INSERT INTO `ip_messages` VALUES ('89', '52', '152', 'Ggg', '2017-11-22 20:16:25', '2017-11-22 20:16:25');
INSERT INTO `ip_messages` VALUES ('90', '52', '152', 'Njhgg', '2017-11-22 20:24:03', '2017-11-22 20:24:03');
INSERT INTO `ip_messages` VALUES ('91', '52', '152', 'hgfhfghgf', '2017-11-23 16:37:31', '2017-11-23 16:37:31');
INSERT INTO `ip_messages` VALUES ('92', '52', '152', 'gfhgfhg', '2017-11-23 17:00:58', '2017-11-23 17:00:58');
INSERT INTO `ip_messages` VALUES ('93', '52', '152', 'Ggggggg', '2017-11-23 20:16:52', '2017-11-23 20:16:52');
INSERT INTO `ip_messages` VALUES ('94', '52', '152', 'Gggg', '2017-11-23 20:17:34', '2017-11-23 20:17:34');
INSERT INTO `ip_messages` VALUES ('95', '52', '152', 'Fff', '2017-11-23 20:17:49', '2017-11-23 20:17:49');
INSERT INTO `ip_messages` VALUES ('96', '52', '152', 'Rrrr', '2017-11-23 20:18:11', '2017-11-23 20:18:11');
INSERT INTO `ip_messages` VALUES ('97', '52', '152', 'Ddddd', '2017-11-23 20:20:58', '2017-11-23 20:20:58');
INSERT INTO `ip_messages` VALUES ('98', '52', '152', 'Fffffdd', '2017-11-23 20:25:24', '2017-11-23 20:25:24');
INSERT INTO `ip_messages` VALUES ('99', '52', '152', 'Ttttt', '2017-11-23 20:25:36', '2017-11-23 20:25:36');
INSERT INTO `ip_messages` VALUES ('100', '52', '152', 'Fffff', '2017-11-23 20:25:50', '2017-11-23 20:25:50');
INSERT INTO `ip_messages` VALUES ('101', '52', '152', 'Ddddd', '2017-11-23 20:26:03', '2017-11-23 20:26:03');
INSERT INTO `ip_messages` VALUES ('102', '52', '152', 'Eeee', '2017-11-23 20:31:23', '2017-11-23 20:31:23');
INSERT INTO `ip_messages` VALUES ('103', '52', '152', 'Dddd', '2017-11-23 20:31:42', '2017-11-23 20:31:42');
INSERT INTO `ip_messages` VALUES ('104', '52', '152', 'Gggg', '2017-11-23 20:31:57', '2017-11-23 20:31:57');
INSERT INTO `ip_messages` VALUES ('105', '52', '152', 'Hhh', '2017-11-23 20:32:15', '2017-11-23 20:32:15');
INSERT INTO `ip_messages` VALUES ('106', '52', '152', 'Ffff', '2017-11-23 20:32:49', '2017-11-23 20:32:49');
INSERT INTO `ip_messages` VALUES ('107', '52', '152', 'Rrrfdd', '2017-11-23 20:37:59', '2017-11-23 20:37:59');
INSERT INTO `ip_messages` VALUES ('108', '52', '152', 'Yyhgd', '2017-11-23 20:38:14', '2017-11-23 20:38:14');
INSERT INTO `ip_messages` VALUES ('109', '52', '152', 'Ffsss', '2017-11-23 20:38:46', '2017-11-23 20:38:46');
INSERT INTO `ip_messages` VALUES ('110', '52', '152', 'Hhsss', '2017-11-23 20:39:05', '2017-11-23 20:39:05');
INSERT INTO `ip_messages` VALUES ('111', '52', '152', 'Fdshj', '2017-11-23 20:39:49', '2017-11-23 20:39:49');
INSERT INTO `ip_messages` VALUES ('112', '52', '150', 'Ya deja de escribir ladilla', '2017-11-23 20:40:11', '2017-11-23 20:40:11');
INSERT INTO `ip_messages` VALUES ('113', '52', '152', 'dfgfgjkdfkgdfklg', '2017-11-27 15:37:20', '2017-11-27 15:37:20');
INSERT INTO `ip_messages` VALUES ('114', '52', '152', 'dfgkdfjgdfkjgldk', '2017-11-27 15:37:23', '2017-11-27 15:37:23');
INSERT INTO `ip_messages` VALUES ('115', '52', '152', 'dflgdkfgkdflgñ', '2017-11-27 15:37:28', '2017-11-27 15:37:28');
INSERT INTO `ip_messages` VALUES ('116', '53', '152', 'fsdfsdlfksdlkflsdkflñsdkflñsdkflsdkflsdkflñksdflñksdlñfksdñkfsñdfklñsdkflñdskfl\r\nñsdkfñlksdfñlsdñfksdlfksdlñfkslñdkfslñdkflñsdkfslñdkfslñdkflsdñkfñs asldkasl dkalsdk alñsk dalñskd lñask dñ', '2017-11-27 20:51:31', '2017-11-27 20:51:31');
INSERT INTO `ip_messages` VALUES ('117', '53', '152', 'dfg dfgdfgdfgdfgdfgdfgdfgdfgdflgkdflgldfkglkdfg kdflñgkdlñfgkldfkglñdfkgldkflñgkdflñgkdlñfkglñdfkglñdfkglñdkfglñkdflgkdflñgd', '2017-11-27 20:59:37', '2017-11-27 20:59:37');
INSERT INTO `ip_messages` VALUES ('118', '53', '152', 'cvbcvbcvb,cvb jdfjgkdfjgkdf gj dkflgdfkgj dfkljgdklfjgkldfjgkldfjgkldfjgkldfjgkdfjklgjdfklgj dfklgjdfklgj dklfj gkldfgjdklfgjkdfjgkdfj gkldfjgkl dfjgkdfj gkldfjgkldfjgkldfjgkljdfgkldf jgkldflg', '2017-11-27 21:22:13', '2017-11-27 21:22:13');
INSERT INTO `ip_messages` VALUES ('119', '53', '150', 'dfg dfgdfgkdfl gldfjgkldfgjkldfgkldfjgkdfjgkldfjgkjdfgjdfklgjkldfgjkldfjgkdlfgjkldfjgkldfjgkldfjgkldfjgkldfkl  gkdfklgdf dfklgjdf kgdjfgkdfjgklglkdfgjdfklgjdfklgjdfkgdfklgjdfkgdklfgdklfgdfklgdfklgdklgdklgdfklgdl', '2017-11-27 21:24:40', '2017-11-27 21:24:40');
INSERT INTO `ip_messages` VALUES ('120', '53', '150', 'ghjghjghjgh jghjghjghjghjghjghjghjghj hghj gh  j ghghjgh jghjghjghj', '2017-11-27 22:06:47', '2017-11-27 22:06:47');
INSERT INTO `ip_messages` VALUES ('121', '53', '150', 'gjghjghjghjghj', '2017-11-27 22:06:50', '2017-11-27 22:06:50');
INSERT INTO `ip_messages` VALUES ('122', '53', '150', 'tyuyuutyutty ty ghghjghjghjgh jgdfjklsfjskdjfklfklsdjfsjdfklsdjklfjsdklfjskdjfklsdjfklsjdfkljsdklfjsdklfjklsdjfklsdjfklsdjfkljsdklfsdlkfsldkfjsdklfjsdklfjlsdfsdf', '2017-11-27 22:12:20', '2017-11-27 22:12:20');
INSERT INTO `ip_messages` VALUES ('123', '53', '152', 'ghjgh jghjgjlklghklklñfhlfkhlñkkgfhkgfhklgfhkfkhklkñfhkfñf', '2017-11-27 22:12:54', '2017-11-27 22:12:54');
INSERT INTO `ip_messages` VALUES ('124', '53', '152', 'ghjghjghjghjghjldfsdfksldkflsñdkflñsdkflñsdkflsk dlñfksdlfksldkflñsdkflsdkflñksdlñfksdlñfklsdkf lsdkflñsdkflksdlfñksldñfkslñdfklñsdkflñskd flñsdlñfsdf lsd', '2017-11-27 22:22:03', '2017-11-27 22:22:03');
INSERT INTO `ip_messages` VALUES ('125', '53', '152', 'g hgfhfdjklfjklsdjgdfjgkldfgjkldfjgkldfjgkdfgkldfj gkldfklg dfklgjdfklgjkldfgjkldfjgkldjfgkldg', '2017-11-27 22:24:14', '2017-11-27 22:24:14');
INSERT INTO `ip_messages` VALUES ('126', '53', '152', 'canción', '2017-11-28 14:30:29', '2017-11-28 14:30:29');
INSERT INTO `ip_messages` VALUES ('127', '53', '152', ':)', '2017-11-28 14:30:33', '2017-11-28 14:30:33');
INSERT INTO `ip_messages` VALUES ('128', '53', '152', 'Gdhdhdhdh', '2017-11-28 14:45:33', '2017-11-28 14:45:33');
INSERT INTO `ip_messages` VALUES ('129', '53', '152', 'Ydyhd', '2017-11-28 14:45:38', '2017-11-28 14:45:38');
INSERT INTO `ip_messages` VALUES ('130', '53', '152', 'fghghfghfghfhfghf', '2017-11-28 14:58:20', '2017-11-28 14:58:20');
INSERT INTO `ip_messages` VALUES ('131', '53', '152', 'dfgdfgdfgdfgdfg', '2017-11-28 14:58:54', '2017-11-28 14:58:54');
INSERT INTO `ip_messages` VALUES ('132', '53', '152', 'fhfgfhgfhf', '2017-11-28 15:06:29', '2017-11-28 15:06:29');
INSERT INTO `ip_messages` VALUES ('133', '53', '152', 'dfgdfgdfgd', '2017-11-28 15:19:37', '2017-11-28 15:19:37');
INSERT INTO `ip_messages` VALUES ('134', '53', '152', 'dfgdfgdfgd', '2017-11-28 15:20:03', '2017-11-28 15:20:03');
INSERT INTO `ip_messages` VALUES ('135', '53', '152', 'vvxvxcvx', '2017-11-28 15:20:17', '2017-11-28 15:20:17');
INSERT INTO `ip_messages` VALUES ('136', '53', '152', 'fgdfgdfgd', '2017-11-28 15:20:58', '2017-11-28 15:20:58');
INSERT INTO `ip_messages` VALUES ('137', '53', '152', 'fdgdfgdfgdf', '2017-11-28 15:25:35', '2017-11-28 15:25:35');
INSERT INTO `ip_messages` VALUES ('138', '53', '152', 'sdfsdfsdfs', '2017-11-28 15:26:01', '2017-11-28 15:26:01');
INSERT INTO `ip_messages` VALUES ('139', '53', '152', 'sdfsdfsdfs', '2017-11-28 15:26:07', '2017-11-28 15:26:07');
INSERT INTO `ip_messages` VALUES ('140', '53', '152', 'dfgfgdfgdf', '2017-11-28 15:26:16', '2017-11-28 15:26:16');
INSERT INTO `ip_messages` VALUES ('141', '53', '152', 'dfgdfgdfg', '2017-11-28 15:28:16', '2017-11-28 15:28:16');
INSERT INTO `ip_messages` VALUES ('142', '53', '152', 'gfhfghgfhfh', '2017-11-28 15:28:35', '2017-11-28 15:28:35');
INSERT INTO `ip_messages` VALUES ('143', '53', '152', '1fff', '2017-11-28 15:51:52', '2017-11-28 15:51:52');
INSERT INTO `ip_messages` VALUES ('144', '53', '152', 'ghgjghjg', '2017-11-28 15:54:26', '2017-11-28 15:54:26');
INSERT INTO `ip_messages` VALUES ('145', '53', '152', 'ghjghjghj', '2017-11-28 15:54:33', '2017-11-28 15:54:33');
INSERT INTO `ip_messages` VALUES ('146', '53', '152', '11111', '2017-11-28 15:54:37', '2017-11-28 15:54:37');
INSERT INTO `ip_messages` VALUES ('147', '53', '152', 'Ggh????', '2017-11-28 15:56:56', '2017-11-28 15:56:56');
INSERT INTO `ip_messages` VALUES ('148', '53', '152', '????', '2017-11-28 15:59:07', '2017-11-28 15:59:07');
INSERT INTO `ip_messages` VALUES ('149', '53', '152', '????', '2017-11-28 15:59:31', '2017-11-28 15:59:31');
INSERT INTO `ip_messages` VALUES ('150', '53', '152', 'U????', '2017-11-28 16:02:24', '2017-11-28 16:02:24');
INSERT INTO `ip_messages` VALUES ('151', '53', '152', '😁😁😁😁', '2017-11-28 16:13:40', '2017-11-28 16:13:40');
INSERT INTO `ip_messages` VALUES ('152', '53', '152', 'gfhgfhgfhgf', '2017-11-28 17:55:43', '2017-11-28 17:55:43');
INSERT INTO `ip_messages` VALUES ('153', '53', '152', 'dfgdfgdfgdfgdfg', '2017-11-28 18:13:32', '2017-11-28 18:13:32');
INSERT INTO `ip_messages` VALUES ('154', '52', '152', 'Ggg', '2017-11-28 19:21:49', '2017-11-28 19:21:49');
INSERT INTO `ip_messages` VALUES ('155', '52', '150', 'hola', '2017-11-28 19:23:34', '2017-11-28 19:23:34');
INSERT INTO `ip_messages` VALUES ('156', '52', '150', 'ggg', '2017-11-28 19:24:14', '2017-11-28 19:24:14');
INSERT INTO `ip_messages` VALUES ('157', '52', '150', 'dfgdgdfgd', '2017-11-28 19:24:51', '2017-11-28 19:24:51');
INSERT INTO `ip_messages` VALUES ('158', '52', '150', 'dfgdfgdfgd', '2017-11-28 19:25:49', '2017-11-28 19:25:49');
INSERT INTO `ip_messages` VALUES ('159', '52', '150', 'dfgdfgfdgd', '2017-11-28 19:26:17', '2017-11-28 19:26:17');
INSERT INTO `ip_messages` VALUES ('160', '52', '150', 'jdfdsjfhdjks', '2017-11-28 19:26:46', '2017-11-28 19:26:46');
INSERT INTO `ip_messages` VALUES ('161', '53', '150', 'Ggddhhhh', '2017-11-28 19:29:12', '2017-11-28 19:29:12');
INSERT INTO `ip_messages` VALUES ('162', '52', '152', 'Gggthdhg', '2017-11-28 19:53:45', '2017-11-28 19:53:45');
INSERT INTO `ip_messages` VALUES ('163', '52', '152', 'Hggg', '2017-11-28 19:53:58', '2017-11-28 19:53:58');
INSERT INTO `ip_messages` VALUES ('164', '53', '152', 'Tggg', '2017-11-28 19:54:09', '2017-11-28 19:54:09');
INSERT INTO `ip_messages` VALUES ('165', '53', '152', 'Gtgfff', '2017-11-28 19:54:22', '2017-11-28 19:54:22');
INSERT INTO `ip_messages` VALUES ('166', '52', '150', 'Gfgghhj', '2017-11-28 19:54:50', '2017-11-28 19:54:50');
INSERT INTO `ip_messages` VALUES ('167', '53', '152', 'Ghj😆', '2017-11-28 19:55:59', '2017-11-28 19:55:59');
INSERT INTO `ip_messages` VALUES ('168', '52', '152', 'Ghfkk😂', '2017-11-28 19:56:35', '2017-11-28 19:56:35');
INSERT INTO `ip_messages` VALUES ('169', '53', '150', 'Ggfdd', '2017-11-29 13:01:03', '2017-11-29 13:01:03');
INSERT INTO `ip_messages` VALUES ('170', '53', '150', 'Fffjjj', '2017-11-29 13:01:42', '2017-11-29 13:01:42');
INSERT INTO `ip_messages` VALUES ('171', '53', '150', 'Yyyyy', '2017-11-29 13:01:45', '2017-11-29 13:01:45');
INSERT INTO `ip_messages` VALUES ('172', '53', '150', 'Ghkgfdd', '2017-11-29 13:01:50', '2017-11-29 13:01:50');
INSERT INTO `ip_messages` VALUES ('173', '53', '150', 'Fghhhj', '2017-11-29 15:52:14', '2017-11-29 15:52:14');
INSERT INTO `ip_messages` VALUES ('174', '53', '150', 'Hdddd', '2017-11-29 15:53:09', '2017-11-29 15:53:09');
INSERT INTO `ip_messages` VALUES ('175', '53', '150', 'Ffhhhh', '2017-11-29 15:54:57', '2017-11-29 15:54:57');
INSERT INTO `ip_messages` VALUES ('176', '53', '150', 'Ttth', '2017-11-29 15:56:54', '2017-11-29 15:56:54');
INSERT INTO `ip_messages` VALUES ('177', '53', '150', 'Tghjdddd', '2017-11-29 15:57:35', '2017-11-29 15:57:35');
INSERT INTO `ip_messages` VALUES ('178', '53', '150', 'Gggg', '2017-11-29 15:58:26', '2017-11-29 15:58:26');
INSERT INTO `ip_messages` VALUES ('179', '53', '150', 'Jkffgh', '2017-11-29 15:58:43', '2017-11-29 15:58:43');
INSERT INTO `ip_messages` VALUES ('180', '53', '150', 'Yyy', '2017-11-29 16:01:29', '2017-11-29 16:01:29');
INSERT INTO `ip_messages` VALUES ('181', '53', '150', 'Hddjjgg', '2017-11-29 16:06:13', '2017-11-29 16:06:13');
INSERT INTO `ip_messages` VALUES ('182', '53', '150', 'Yyyy', '2017-11-29 16:06:36', '2017-11-29 16:06:36');
INSERT INTO `ip_messages` VALUES ('183', '53', '150', 'Hggg', '2017-11-29 16:06:58', '2017-11-29 16:06:58');
INSERT INTO `ip_messages` VALUES ('184', '53', '150', 'Ffghj', '2017-11-29 16:07:33', '2017-11-29 16:07:33');
INSERT INTO `ip_messages` VALUES ('185', '53', '150', 'Ggggg', '2017-11-29 16:13:43', '2017-11-29 16:13:43');
INSERT INTO `ip_messages` VALUES ('186', '53', '150', 'Gggg', '2017-11-29 16:39:13', '2017-11-29 16:39:13');
INSERT INTO `ip_messages` VALUES ('187', '53', '150', 'Tggg', '2017-11-29 16:46:27', '2017-11-29 16:46:27');
INSERT INTO `ip_messages` VALUES ('188', '53', '150', 'Gggg', '2017-11-29 16:58:01', '2017-11-29 16:58:01');
INSERT INTO `ip_messages` VALUES ('189', '53', '150', 'Gggg', '2017-11-29 16:58:22', '2017-11-29 16:58:22');
INSERT INTO `ip_messages` VALUES ('190', '53', '150', 'Hhh', '2017-11-29 21:43:04', '2017-11-29 21:43:04');
INSERT INTO `ip_messages` VALUES ('191', '53', '152', 'fghgfhgfhgfhf', '2017-11-30 17:16:03', '2017-11-30 17:16:03');

-- ----------------------------
-- Table structure for ip_rooms
-- ----------------------------
DROP TABLE IF EXISTS `ip_rooms`;
CREATE TABLE `ip_rooms` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_intro` double NOT NULL,
  `id_user_1` double NOT NULL,
  `id_user_1_leave` smallint(6) NOT NULL DEFAULT '0',
  `id_user_2` double NOT NULL,
  `id_user_2_leave` smallint(6) NOT NULL DEFAULT '0',
  `id_user_3` double NOT NULL,
  `id_user_3_leave` smallint(6) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_intro` (`id_intro`),
  KEY `id_user_1` (`id_user_1`),
  KEY `id_user_2` (`id_user_2`),
  KEY `id_user_3` (`id_user_3`),
  CONSTRAINT `ip_rooms_ibfk_1` FOREIGN KEY (`id_intro`) REFERENCES `ip_intros` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_rooms
-- ----------------------------
INSERT INTO `ip_rooms` VALUES ('52', '79', '150', '0', '152', '0', '143', '0', '2017-11-23 16:09:00', '2017-11-23 16:09:00');
INSERT INTO `ip_rooms` VALUES ('53', '80', '152', '0', '150', '0', '151', '0', '2017-11-27 20:00:07', '2017-11-27 20:00:07');
INSERT INTO `ip_rooms` VALUES ('54', '81', '152', '0', '166', '0', '167', '0', '2017-12-01 20:55:16', '2017-12-01 20:55:16');

-- ----------------------------
-- Table structure for ip_users
-- ----------------------------
DROP TABLE IF EXISTS `ip_users`;
CREATE TABLE `ip_users` (
  `id` double NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `external_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_profile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_token` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `job_title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_description` text COLLATE utf8_unicode_ci,
  `company_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invited` smallint(6) NOT NULL DEFAULT '0',
  `push_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users
-- ----------------------------
INSERT INTO `ip_users` VALUES ('143', '', '', '$2y$10$Ub4yxX3MWGEAczC2CSBHb.ovOWIwd0fJF5V1HUMwOb.Cid7c6jAAO', 'Junior', 'Milano', 'rensho', 'renshorpg2@gmail.com', 'renshocontact@gmail.com20171121195841.jpeg', null, '$2y$2y$10$N6HZkrJvalwlz1Lja5xIa.4dMdH9CFyYqXhrzSluwQzOUXVFdi7qG\r\n$2y$10$N6HZkrJvalwlz1Lja5xIa.4dMdH9CFyYqXhrzSluwQzOUXVFdi7qG\r\n', null, null, null, '0', '', '2017-11-21 16:22:17', '2017-11-21 20:15:28');
INSERT INTO `ip_users` VALUES ('150', '', '', '$2y$10$Ub4yxX3MWGEAczC2CSBHb.ovOWIwd0fJF5V1HUMwOb.Cid7c6jAAO', 'Carlos', 'Urbina', 'clus90', 'clus90@gmail.com', 'https://lh4.googleusercontent.com/-olgv-V2YRlo/AAAAAAAAAAI/AAAAAAAAAH4/7ZvCLyuzU6Y/photo.jpg', null, '', null, null, null, '0', '', '2017-11-21 20:34:57', '2017-11-30 16:33:46');
INSERT INTO `ip_users` VALUES ('151', '', '', '$2y$10$Ub4yxX3MWGEAczC2CSBHb.ovOWIwd0fJF5V1HUMwOb.Cid7c6jAAO', 'Carlos', 'Urbina', 'urbina', 'carlosluisurbina@gmail.com', 'renshocontact@gmail.com20171121195841.jpeg', null, '', 'Co Founder - Director - iOS Developer', 'Project Management, iOS Development, Software Design, User\'s Experience Advisor', 'Sappito Technologies, C.A.', '0', '', '2017-11-21 20:36:16', '2017-11-27 19:59:35');
INSERT INTO `ip_users` VALUES ('152', '', '', '$2y$10$YoRQia7KPwsEHP/MYMqj/.VjMjn/nes7v6NBUvizpeTM/9bnCS/5W', 'Juab', 'G;nzaled', 'gonzo', 'renshocontact@gmail.com', null, null, '', 'jsd fhksjfsdjkfhsdfjksdhfjksdfjskdfjk djf sd  sdjfh sdjkfs sdjkfhsjfk jfh sdjsd  djfh sdjk', 'jd ashdjas djaskhdas asjkhaj asjdj askas  asjkdhas jd asjkdashdjka asjdasjdasjk  ajd hasjk ajksdhasj das  asja da', 'asj sdh as ddjasd asdkjasdkasj askdjdmas asdas dasdas dasd asd adas da dasd ', '0', '', '2017-11-21 20:40:58', '2017-12-01 20:58:54');
INSERT INTO `ip_users` VALUES ('153', '', '', '$2y$10$Ub4yxX3MWGEAczC2CSBHb.ovOWIwd0fJF5V1HUMwOb.Cid7c6jAAO', 'Juab', 'G;nzaled', 'gonzo', 'renshocontact2@gmail.com', null, null, '', null, null, null, '0', null, '2017-11-21 20:40:58', '2017-11-23 20:08:47');
INSERT INTO `ip_users` VALUES ('154', '', '', '$2y$10$Ub4yxX3MWGEAczC2CSBHb.ovOWIwd0fJF5V1HUMwOb.Cid7c6jAAO', 'Juab', 'G;nzaled', 'gonzo', 'renshocontact3@gmail.com', null, null, '', null, null, null, '0', '31209890-d22f-40a5-8751-d2fdbd65d159', '2017-11-21 20:40:58', '2017-11-23 20:08:47');
INSERT INTO `ip_users` VALUES ('166', '', '', null, 'g', 'dfgdfgfdg', null, 'dgdfgdf@fgfdgdgd.com', null, null, '', null, null, null, '1', null, '2017-12-01 20:50:25', '2017-12-01 20:50:25');
INSERT INTO `ip_users` VALUES ('167', '', '', null, 'sdfsd', 'sdfsdf', null, 'ghjghgh@gfhgfhf.com', null, null, '', null, null, null, '1', null, '2017-12-01 20:54:26', '2017-12-01 20:54:26');

-- ----------------------------
-- Table structure for ip_users_friends
-- ----------------------------
DROP TABLE IF EXISTS `ip_users_friends`;
CREATE TABLE `ip_users_friends` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_user` double NOT NULL,
  `id_user_friend` double NOT NULL,
  `friend_info` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_user_friend` (`id_user_friend`),
  CONSTRAINT `ip_users_friends_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `ip_users` (`id`),
  CONSTRAINT `ip_users_friends_ibfk_2` FOREIGN KEY (`id_user_friend`) REFERENCES `ip_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users_friends
-- ----------------------------
INSERT INTO `ip_users_friends` VALUES ('28', '152', '152', null, '1', '2017-11-27 11:18:51', '2017-11-27 11:18:51');
INSERT INTO `ip_users_friends` VALUES ('29', '152', '143', null, '1', '2017-11-21 20:43:19', '2017-11-21 20:43:19');
INSERT INTO `ip_users_friends` VALUES ('30', '152', '152', null, '1', '2017-11-27 11:18:52', '2017-11-27 11:18:52');
INSERT INTO `ip_users_friends` VALUES ('31', '152', '150', null, '1', '2017-11-21 20:44:37', '2017-11-21 20:44:37');
INSERT INTO `ip_users_friends` VALUES ('32', '152', '151', null, '1', '0000-00-00 00:00:00', null);
INSERT INTO `ip_users_friends` VALUES ('33', '152', '150', null, '1', '0000-00-00 00:00:00', null);
INSERT INTO `ip_users_friends` VALUES ('34', '152', '150', null, '1', '0000-00-00 00:00:00', null);
INSERT INTO `ip_users_friends` VALUES ('35', '152', '153', null, '1', '0000-00-00 00:00:00', null);
INSERT INTO `ip_users_friends` VALUES ('36', '152', '154', null, '1', '0000-00-00 00:00:00', null);
INSERT INTO `ip_users_friends` VALUES ('37', '150', '152', null, '1', '2017-11-27 20:08:08', '2017-11-27 20:08:08');
INSERT INTO `ip_users_friends` VALUES ('38', '152', '150', null, '1', '2017-11-27 20:08:08', '2017-11-27 20:08:08');
INSERT INTO `ip_users_friends` VALUES ('59', '152', '166', null, '1', '2017-12-01 20:50:25', '2017-12-01 20:50:25');
INSERT INTO `ip_users_friends` VALUES ('60', '166', '152', null, '1', '2017-12-01 20:50:25', '2017-12-01 20:50:25');
INSERT INTO `ip_users_friends` VALUES ('61', '152', '167', null, '1', '2017-12-01 20:54:26', '2017-12-01 20:54:26');
INSERT INTO `ip_users_friends` VALUES ('62', '167', '152', null, '1', '2017-12-01 20:54:26', '2017-12-01 20:54:26');
