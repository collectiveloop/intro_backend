/*
Navicat MySQL Data Transfer

Source Server         : Local connection
Source Server Version : 100126
Source Host           : localhost:3306
Source Database       : intro_app

Target Server Type    : MYSQL
Target Server Version : 100126
File Encoding         : 65001

Date: 2017-10-26 14:03:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ip_gainings
-- ----------------------------
DROP TABLE IF EXISTS `ip_gainings`;
CREATE TABLE `ip_gainings` (
  `id` double NOT NULL AUTO_INCREMENT,
  `gain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_gainings
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_gainings_intros
-- ----------------------------

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
  KEY `id_friend_1` (`id_friend_1`),
  KEY `id_friend_2` (`id_friend_2`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `ip_intros_ibfk_1` FOREIGN KEY (`id_friend_1`) REFERENCES `ip_users_friends` (`id`),
  CONSTRAINT `ip_intros_ibfk_2` FOREIGN KEY (`id_friend_2`) REFERENCES `ip_users_friends` (`id`),
  CONSTRAINT `ip_intros_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `ip_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_intros
-- ----------------------------
INSERT INTO `ip_intros` VALUES ('1', '129', '1', '2', 'una razón de pruba con longitud variable', 'jajajaja', 'jejejeje', '2017-10-26 10:15:03', '2017-10-26 10:15:03');
INSERT INTO `ip_intros` VALUES ('3', '129', '2', '4', 'dfgdfgdfgd', '55454545', 'gfdgdfgdf', '2017-10-25 17:04:26', '2017-10-25 17:04:26');
INSERT INTO `ip_intros` VALUES ('4', '110', '3', '6', '', '', '', '2017-10-25 17:02:06', '2017-10-25 17:02:06');

-- ----------------------------
-- Table structure for ip_messages
-- ----------------------------
DROP TABLE IF EXISTS `ip_messages`;
CREATE TABLE `ip_messages` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_intro` double NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_messages
-- ----------------------------

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
  `remember_token` varchar(500) COLLATE utf8_unicode_ci DEFAULT '',
  `job_title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_description` text COLLATE utf8_unicode_ci,
  `company_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users
-- ----------------------------
INSERT INTO `ip_users` VALUES ('109', 'facebook', '10214729874099097', '$2y$10$r8C0Ni73s.ZaadHSlWpcx.GuK/a1z7HxpnpFDEJP34i9nhJ5POUXG', 'Junior', 'Milano', null, 'renshorpg@hotmail.com', 'email@email.com20171024183935.jpeg', null, null, null, null, '2017-10-19 16:41:41', '2017-10-19 16:41:41');
INSERT INTO `ip_users` VALUES ('110', 'linkedin', 'N1izupToy9', '$2y$10$ND.LpnzsgbjGuTqRTUfsF.apeDnx/.Grq5fvUdPSwk1zqCr0z1QPO', 'Carlos', 'Urbina', null, 'carlosluisurbina@gmail.com', 'jua@gmail.com20171013211144.jpeg', null, 'Co Founder - Director - iOS Developer', 'Project Management, iOS Development, Software Design, User\'s Experience Advisor', 'Sappito Technologies, C.A.', '2017-10-19 16:50:37', '2017-10-19 16:50:37');
INSERT INTO `ip_users` VALUES ('118', null, '', '$2y$10$XLgL0Fc0zAEPu3J7SvkTuOTolGajhH45zoO9pa3KZ0g7fDmFfqD1O', '1111111111111', '22222222222222', 'jajaja', 'jajaja@jajaja.com', 'jua@gmail.com20171013211144.jpeg', null, null, null, null, '2017-10-20 13:19:13', '2017-10-20 13:19:13');
INSERT INTO `ip_users` VALUES ('119', null, '', '$2y$10$9Z5280nlOheuYyUyCtydgu3KxOo/o1M4gyI3DYQEnSMKQAqf.tBSa', 'gdfgfdgdf', 'gdfgdfgd', 'jajajasddfsdfs', 'jajaja2@jajaja.com', 'jua@gmail.com20171013211144.jpeg', null, null, null, null, '2017-10-20 13:23:57', '2017-10-20 13:23:57');
INSERT INTO `ip_users` VALUES ('120', 'google_plus', '105530762269265533571', '$2y$10$71HEvKcsPJGp7otQR0yKV.gEEJIjop1LKjzdrtrxRtzYlxgKm5Mli', 'Carlos', 'Urbina', null, 'clus90@gmail.com', 'jua@gmail.com20171013211144.jpeg', null, null, null, null, '2017-10-20 14:02:21', '2017-10-20 14:02:21');
INSERT INTO `ip_users` VALUES ('122', null, '', '$2y$10$HU4c7jTs465vrl3o3ZXwXeI6YLdulw.r.E9XTFdNl6t.2rDIWXxWi', 'jajajajaja', 'jejejejeje', 'gdfgdfgdfg', 'dfgdf@dsfsdfsdf.comd', 'rens@gmail.con20171025145218.jpeg', null, null, null, null, '2017-10-24 16:01:24', '2017-10-24 16:01:24');
INSERT INTO `ip_users` VALUES ('129', '', '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'juan', 'gonzales', 'rrfgg', 'renshocontact@gmail.com', 'rens@gmail.con20171025145218.jpeg', '', 'Ffvvgh', '', 'Gggvggggg', '2017-10-25 14:51:53', '2017-10-25 14:52:18');

-- ----------------------------
-- Table structure for ip_users_friends
-- ----------------------------
DROP TABLE IF EXISTS `ip_users_friends`;
CREATE TABLE `ip_users_friends` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_user` double NOT NULL,
  `id_user_friend` double NOT NULL,
  `friend_info` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_user_friend` (`id_user_friend`),
  CONSTRAINT `ip_users_friends_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `ip_users` (`id`),
  CONSTRAINT `ip_users_friends_ibfk_2` FOREIGN KEY (`id_user_friend`) REFERENCES `ip_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users_friends
-- ----------------------------
INSERT INTO `ip_users_friends` VALUES ('1', '129', '109', 'jejejeje', '2017-10-25 16:29:11', '2017-10-25 16:29:11');
INSERT INTO `ip_users_friends` VALUES ('2', '129', '129', 'jijijijiji', '2017-10-25 16:23:03', '2017-10-25 16:23:03');
INSERT INTO `ip_users_friends` VALUES ('3', '110', '120', 'fdgdfgdfg', '0000-00-00 00:00:00', null);
INSERT INTO `ip_users_friends` VALUES ('4', '129', '122', 'sdfsfsd', '2017-10-25 16:23:05', '2017-10-25 16:23:05');
INSERT INTO `ip_users_friends` VALUES ('5', '129', '118', 'gfdfgdf', '2017-10-25 16:06:34', '2017-10-25 16:06:34');
INSERT INTO `ip_users_friends` VALUES ('6', '129', '119', 'hfghfghfh', '2017-10-25 16:06:56', '2017-10-25 16:06:56');
