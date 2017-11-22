/*
Navicat MySQL Data Transfer

Source Server         : Local connection
Source Server Version : 100126
Source Host           : localhost:3306
Source Database       : intro_app

Target Server Type    : MYSQL
Target Server Version : 100126
File Encoding         : 65001

Date: 2017-11-22 16:31:56
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_contacts_pending
-- ----------------------------
INSERT INTO `ip_contacts_pending` VALUES ('25', '152', 'Junior Milano', 'renshorpg@gmail.com', '1', '2017-11-21 16:43:19', '2017-11-21 20:43:19');
INSERT INTO `ip_contacts_pending` VALUES ('26', '152', 'Carlos Urbina', 'clus90@gmail.com', '1', '2017-11-21 16:44:37', '2017-11-21 20:44:37');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_gainings_intros
-- ----------------------------
INSERT INTO `ip_gainings_intros` VALUES ('37', '79', '1', '0000-00-00 00:00:00', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_intros
-- ----------------------------
INSERT INTO `ip_intros` VALUES ('79', '152', '31', '29', 'Hhj', 'Gg', 'Hhh', '2017-11-22 19:07:46', '2017-11-22 19:07:46');

-- ----------------------------
-- Table structure for ip_messages
-- ----------------------------
DROP TABLE IF EXISTS `ip_messages`;
CREATE TABLE `ip_messages` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_room` double NOT NULL,
  `id_user` double NOT NULL,
  `message` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `ip_messages_ibfk_1` (`id_room`),
  CONSTRAINT `ip_messages_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `ip_rooms` (`id`),
  CONSTRAINT `ip_messages_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `ip_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_messages
-- ----------------------------
INSERT INTO `ip_messages` VALUES ('88', '52', '152', 'erwerwer', '2017-11-22 19:58:03', '2017-11-22 19:58:03');
INSERT INTO `ip_messages` VALUES ('89', '52', '152', 'Ggg', '2017-11-22 20:16:25', '2017-11-22 20:16:25');
INSERT INTO `ip_messages` VALUES ('90', '52', '152', 'Njhgg', '2017-11-22 20:24:03', '2017-11-22 20:24:03');

-- ----------------------------
-- Table structure for ip_rooms
-- ----------------------------
DROP TABLE IF EXISTS `ip_rooms`;
CREATE TABLE `ip_rooms` (
  `id` double NOT NULL AUTO_INCREMENT,
  `id_intro` double NOT NULL,
  `id_user_1` double NOT NULL,
  `id_user_2` double NOT NULL,
  `id_user_3` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_intro` (`id_intro`),
  KEY `id_user_1` (`id_user_1`),
  KEY `id_user_2` (`id_user_2`),
  KEY `id_user_3` (`id_user_3`),
  CONSTRAINT `ip_rooms_ibfk_1` FOREIGN KEY (`id_intro`) REFERENCES `ip_intros` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_rooms
-- ----------------------------
INSERT INTO `ip_rooms` VALUES ('52', '79', '152', '150', '143', '2017-11-22 19:07:46', '2017-11-22 19:07:46');

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
  `push_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users
-- ----------------------------
INSERT INTO `ip_users` VALUES ('143', '', '', '$2y$10$YbwGCLC.MUnGqO1tNogjm.QFHXHyNMqba3g2BC/1t0mRn2wGq3spm', 'Junior', 'Milano', 'rensho', 'renshorpg@gmail.com', 'renshocontact@gmail.com20171121195841.jpeg', '', null, null, null, '', '2017-11-21 16:22:17', '2017-11-21 20:15:28');
INSERT INTO `ip_users` VALUES ('150', 'google_plus', '105530762269265533571', '$2y$10$E8a6sj5muWvGM.3RuGta8uMu9OrQab8is6g/KuoQL/X8CQDqa4H4G', 'Carlos', 'Urbina', 'clus90', 'clus90@gmail.com', 'https://lh4.googleusercontent.com/-olgv-V2YRlo/AAAAAAAAAAI/AAAAAAAAAH4/7ZvCLyuzU6Y/photo.jpg', '', null, null, null, '57368cdd-a25d-4aef-81e2-da024eb22d8b', '2017-11-21 20:34:57', '2017-11-22 20:16:35');
INSERT INTO `ip_users` VALUES ('151', 'linkedin', 'N1izupToy9', '$2y$10$iFTWhCoLy7ZO/u4lAzNSSeYK5z2CFtyt7Xk0tMHo1eDx3dT2M2LKi', 'Carlos', 'Urbina', 'urbina', 'carlosluisurbina@gmail.com', 'https://media.licdn.com/mpr/mprx/0_xbKKrjeYvihaNE1comu1CEXgbjh6FQ-RaGunT_5Zkf2inEx4MTSqc5JRqAhQne1YxzS-QBZRbISiLBCAw230F64ZrIS_LBmRV23ttjX45ApELh0tVeAPNd_bpJ', '', 'Co Founder - Director - iOS Developer', 'Project Management, iOS Development, Software Design, User\'s Experience Advisor', 'Sappito Technologies, C.A.', '57368cdd-a25d-4aef-81e2-da024eb22d8b', '2017-11-21 20:36:16', '2017-11-22 15:05:22');
INSERT INTO `ip_users` VALUES ('152', '', '', '$2y$10$Ub4yxX3MWGEAczC2CSBHb.ovOWIwd0fJF5V1HUMwOb.Cid7c6jAAO', 'Juab', 'G;nzaled', 'gonzo', 'renshocontact@gmail.com', null, '', null, null, null, '31209890-d22f-40a5-8751-d2fdbd65d159', '2017-11-21 20:40:58', '2017-11-22 20:16:16');

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of ip_users_friends
-- ----------------------------
INSERT INTO `ip_users_friends` VALUES ('28', '143', '152', null, '1', '2017-11-21 20:43:19', '2017-11-21 20:43:19');
INSERT INTO `ip_users_friends` VALUES ('29', '152', '143', null, '1', '2017-11-21 20:43:19', '2017-11-21 20:43:19');
INSERT INTO `ip_users_friends` VALUES ('30', '150', '152', null, '1', '2017-11-21 20:44:37', '2017-11-21 20:44:37');
INSERT INTO `ip_users_friends` VALUES ('31', '152', '150', null, '1', '2017-11-21 20:44:37', '2017-11-21 20:44:37');
