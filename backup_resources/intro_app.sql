/*
SQLyog Enterprise - MySQL GUI v8.05 
MySQL - 5.5.54 : Database - intro_app
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `ip_products` */

DROP TABLE IF EXISTS `ip_products`;

CREATE TABLE `ip_products` (
  `id` double NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `title_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `images` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ip_products` */

insert  into `ip_products`(`id`,`name`,`description`,`title_image`,`images`,`created_at`,`updated_at`) values (1,'my-fast-burner ','Niveles de tangibilidad del producto. Bien tangible puro: es la oferta de un bien tangible, sin ningún tipo de servicio asociado; por ejemplo, sal, arroz, ruedas. Bien tangible con servicios anexos: el bien se vende acompañado de uno o más servicios; por ejemplo, automóviles, máquinas.','title.png','image1.png;image2.png','2017-09-22 11:57:14','2017-09-22 11:57:14');

/*Table structure for table `ip_users` */

DROP TABLE IF EXISTS `ip_users`;

CREATE TABLE `ip_users` (
  `id` double NOT NULL AUTO_INCREMENT,
  `external_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_profile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_description` text COLLATE utf8_unicode_ci,
  `company_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ip_users` */

insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (2,NULL,'$2y$10$8PlJKfiulIspYtnOeQ57XujkU/JAM6.1YfqQVZdOpBEDWV/A30iS.','first','user','user@user.com',NULL,NULL,'',NULL,NULL,'2017-09-26 15:45:51','2017-09-26 15:45:51');
insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (9,'','$2y$10$8PlJKfiulIspYtnOeQ57XujkU/JAM6.1YfqQVZdOpBEDWV/A30iS.','Junior','Milano','test@test.com','renshocontact@gmail.com20170929183521.jpeg',NULL,'',NULL,NULL,'2017-10-03 12:36:37','2017-10-03 12:36:37');
insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (10,'','$2y$10$hYzy6F3gDo2d0uKcknhOzuULe0aHk/PrcNxvxSOx9uyg620LeIUeC','Carlos Luis','Urbina','clus90@gmail.com',NULL,NULL,'',NULL,NULL,'2017-10-02 14:30:35','2017-10-02 18:30:35');
insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (12,'','$2y$10$58Bkq2kwCkGRFbNyJjjkhuxpCmoOhqsHcDfQlQXJlKGVeEYfbf4Uy','Hyd','Fffffff','renshocontact@gmail.com','renshocontact@gmail.com20171010160436.png',NULL,'',NULL,NULL,'2017-10-10 12:04:36','2017-10-10 16:04:36');
insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (13,'','$2y$10$hy6gerD53Vbuhl1FzsfYNe03eimsGFvf9NMiZw.scdA/9d.FUNOc2','fdsfsdfdsf','dsfsdfsd','sdfsdf@sdfsdfsd.com',NULL,NULL,'',NULL,NULL,'2017-10-03 17:10:05','2017-10-03 17:10:05');
insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (14,'','$2y$10$vXrhyOFMTLEmU.4PQ5gsCuybESwjMunk54LjnqlOj8AvQ8sJvIkd6','bcvbcv','cvbcvbcv','cvbcv@dgfsdfsdf.com',NULL,NULL,'',NULL,NULL,'2017-10-05 13:51:23','2017-10-05 13:51:23');
insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (15,'','$2y$10$S/OU53Hc4bvtdbul1rDlKeK4fWTIHWIuAdS5u8FroCpG0t/X6Qw3W','juan','gonzales','hjdfjshfjsdk@dfsdfsdf.com',NULL,NULL,'',NULL,NULL,'2017-10-09 15:33:38','2017-10-09 15:33:38');
insert  into `ip_users`(`id`,`external_id`,`password`,`first_name`,`last_name`,`email`,`image_profile`,`remember_token`,`job_title`,`job_description`,`company_name`,`created_at`,`updated_at`) values (16,'','$2y$10$NlSWojfE5rRQYH5IJkZrNONPZs2nkwG5rUDXySzM6hp/bRZUT209u','fgdfgdf','dfgdfgdf','renshocontact2@gmail.com',NULL,NULL,'',NULL,NULL,'2017-10-10 16:33:07','2017-10-10 16:33:07');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
