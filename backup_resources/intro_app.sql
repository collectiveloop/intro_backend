/*
Navicat MySQL Data Transfer

Source Server         : Local connection
Source Server Version : 100126
Source Host           : localhost:3306
Source Database       : intro_app

Target Server Type    : MYSQL
Target Server Version : 100126
File Encoding         : 65001

Date: 2017-11-10 17:20:01
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_contacts_pending
-- ----------------------------
INSERT INTO `ip_contacts_pending` VALUES ('1', '140', '2222222', 'raqone@gmail.com', '0', '0000-00-00 00:00:00', null);
INSERT INTO `ip_contacts_pending` VALUES ('2', '140', 'fsdfsfd', 'renshocontact3@gmail.com', '2', '2017-11-10 10:59:21', '2017-11-10 14:59:21');
INSERT INTO `ip_contacts_pending` VALUES ('3', '120', '2342434', 'renshocontact@gmail.com', '2', '2017-11-07 14:07:03', '2017-11-07 18:07:03');
INSERT INTO `ip_contacts_pending` VALUES ('4', '140', 'dssfsdfsdfs sdf sl', 'andreinackofinke@gmail.com', '0', '0000-00-00 00:00:00', null);
INSERT INTO `ip_contacts_pending` VALUES ('5', '140', 'csfsdf  sdfs df', 'renshocontact@gmail.com', '2', '2017-11-06 10:32:06', '2017-11-06 10:32:06');
INSERT INTO `ip_contacts_pending` VALUES ('8', '140', 'Carlos Luis Urbina', 'carlosluisurbina@gmail.com', '0', '2017-11-03 16:08:54', '2017-11-03 16:08:54');
INSERT INTO `ip_contacts_pending` VALUES ('9', '140', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-07 14:19:15', '2017-11-07 18:19:15');
INSERT INTO `ip_contacts_pending` VALUES ('10', '140', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-07 14:19:15', '2017-11-07 18:19:15');
INSERT INTO `ip_contacts_pending` VALUES ('11', '140', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-07 14:19:15', '2017-11-07 18:19:15');
INSERT INTO `ip_contacts_pending` VALUES ('12', '140', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-07 14:19:15', '2017-11-07 18:19:15');
INSERT INTO `ip_contacts_pending` VALUES ('13', '140', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-07 14:19:15', '2017-11-07 18:19:15');
INSERT INTO `ip_contacts_pending` VALUES ('14', '140', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-07 14:19:15', '2017-11-07 18:19:15');
INSERT INTO `ip_contacts_pending` VALUES ('15', '120', 'Junior Milano', 'renshocontact2@gmail.com', '2', '2017-11-10 10:56:31', '2017-11-10 10:56:31');
INSERT INTO `ip_contacts_pending` VALUES ('17', '120', 'Junior Milano', 'renshocontact2@gmail.com', '2', '2017-11-10 10:57:17', '2017-11-10 14:57:17');
INSERT INTO `ip_contacts_pending` VALUES ('19', '141', 'Junior Milano', 'renshocontact2@gmail.com', '2', '2017-11-10 11:01:52', '2017-11-10 15:01:52');
INSERT INTO `ip_contacts_pending` VALUES ('20', '141', 'Junior Milano', 'renshocontact2@gmail.com', '2', '2017-11-10 11:19:01', '2017-11-10 15:19:01');
INSERT INTO `ip_contacts_pending` VALUES ('21', '142', 'Carlos Urbinazzz', 'clus90@gmail.com', '2', '2017-11-10 11:34:18', '2017-11-10 15:34:18');
INSERT INTO `ip_contacts_pending` VALUES ('23', '142', 'Carlos Urbinazzz', 'clus90@gmail.com', '1', '2017-11-10 11:36:36', '2017-11-10 15:36:36');
INSERT INTO `ip_contacts_pending` VALUES ('24', '142', 'Carlos Urbina', 'carlosluisurbina@gmail.com', '0', '2017-11-10 15:39:36', '2017-11-10 15:39:36');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_gainings_intros
-- ----------------------------
INSERT INTO `ip_gainings_intros` VALUES ('10', '24', '5', '0000-00-00 00:00:00', null);
INSERT INTO `ip_gainings_intros` VALUES ('11', '24', '3', '0000-00-00 00:00:00', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_intros
-- ----------------------------
INSERT INTO `ip_intros` VALUES ('24', '142', '26', '1', '43', '12', '23', '2017-11-10 18:08:38', '2017-11-10 18:08:38');

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
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users
-- ----------------------------
INSERT INTO `ip_users` VALUES ('109', 'facebook', '10214729874099097', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'Junior', 'Milano', 'paso3', 'renshorpg2@hotmail.com', 'email@email.com20171024183935.jpeg', null, null, null, null, '2017-10-19 16:41:41', '2017-10-19 16:41:41');
INSERT INTO `ip_users` VALUES ('110', 'linkedin', 'N1izupToy9', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'Carlos', 'Urbina', '34343434', 'carlosluisurbina@gmail.com', 'jua@gmail.com20171013211144.jpeg', null, 'Co Founder - Director - iOS Developer', 'Project Management, iOS Development, Software Design, User\'s Experience Advisor', 'Sappito Technologies, C.A.', '2017-10-19 16:50:37', '2017-10-19 16:50:37');
INSERT INTO `ip_users` VALUES ('118', null, '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', '1111111111111', '22222222222222', 'jajaja', 'raqone@gmail.com', '', null, null, null, null, '2017-10-20 13:19:13', '2017-10-20 13:19:13');
INSERT INTO `ip_users` VALUES ('119', null, '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'gdfgfdgdf', 'gdfgdfgd', 'jajajasddfsdfs', 'jajaja2@jajaja.com', 'jua@gmail.com20171013211144.jpeg', null, null, null, null, '2017-10-20 13:23:57', '2017-10-20 13:23:57');
INSERT INTO `ip_users` VALUES ('120', '', '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'Carlos', 'Urbinazzz', 'dsfdsf', 'clus90@gmail.com', 'jua@gmail.com20171013211144.jpeg', null, null, null, null, '2017-10-20 14:02:21', '2017-10-20 14:02:21');
INSERT INTO `ip_users` VALUES ('122', null, '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'jajajajaja', 'jejejejeje', 'gdfgdfgdfg', 'renshocontact5@gmail.com', '', null, null, null, null, '2017-10-24 16:01:24', '2017-10-24 16:01:24');
INSERT INTO `ip_users` VALUES ('130', '', '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'ghfghgh', 'hfghgfhf', 'paso1', 'renshocontact4@gmail.com', null, '', null, null, null, '2017-10-26 20:45:00', '2017-10-27 16:10:15');
INSERT INTO `ip_users` VALUES ('140', '', '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'JUan', 'Gggff', 'rensho', 'renshocontact@gmail.com', 'https://fb-s-d-a.akamaihd.net/h-ak-fbx/v/t1.0-1/p320x320/22780637_10214975473558930_3567830510212017554_n.jpg?oh=b390af3752cd2d0b735538ac558ef2c5&oe=5A77484D&__gda__=1520899385_099fd983e21e6581be850bb5692fe838', '', null, null, null, '2017-10-27 15:30:16', '2017-10-30 19:13:32');
INSERT INTO `ip_users` VALUES ('141', '', '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'juan', 'gonzales', 'rensho2', 'renshocontact3@gmail.com', 'https://fb-s-d-a.akamaihd.net/h-ak-fbx/v/t1.0-1/p320x320/22780637_10214975473558930_3567830510212017554_n.jpg?oh=b390af3752cd2d0b735538ac558ef2c5&oe=5A77484D&__gda__=1520899385_099fd983e21e6581be850bb5692fe838', '', 'Ffvvgh', '', '', '2017-10-25 14:51:53', '2017-10-30 18:37:57');
INSERT INTO `ip_users` VALUES ('142', '', '', '$2y$10$0eTZGm8DzqVTSYbJ9SWkvOFIxK2qNeG5fLFyYI2PpaKkrpoQCKzau', 'Junior', 'Milano', 'fijfjf', 'renshocontact2@gmail.com', '', '', 'Desarrollador web trabajo remoto', 'Desarrollo de proyecto web comercial bajo tecnología Angular JS 1, Bootstrap (Frontend), PHP laravel 5 (Backend), Administración de base de datos Mysql y SQL Server', 'IFL chile', '2017-10-27 19:42:11', '2017-10-27 19:42:53');

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users_friends
-- ----------------------------
INSERT INTO `ip_users_friends` VALUES ('1', '142', '141', 'jejejeje', '2017-11-03 12:02:24', '2017-11-03 12:02:24');
INSERT INTO `ip_users_friends` VALUES ('6', '140', '119', 'hfghfghfh', '2017-10-31 14:38:42', '2017-10-31 14:38:42');
INSERT INTO `ip_users_friends` VALUES ('25', '120', '142', null, '2017-11-10 15:36:36', '2017-11-10 15:36:36');
INSERT INTO `ip_users_friends` VALUES ('26', '142', '120', null, '2017-11-10 15:36:36', '2017-11-10 15:36:36');
