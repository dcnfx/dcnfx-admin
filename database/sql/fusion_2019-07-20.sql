# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.26-log)
# Database: fusion
# Generation Time: 2019-07-20 15:39:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_logs`;

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(11) NOT NULL COMMENT '用户ID',
  `log_url` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL',
  `log_ip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ip',
  `log_info` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
  `log_time` datetime DEFAULT NULL COMMENT '日志日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_logs` WRITE;
/*!40000 ALTER TABLE `admin_logs` DISABLE KEYS */;

INSERT INTO `admin_logs` (`id`, `admin_id`, `log_url`, `log_ip`, `log_info`, `log_time`)
VALUES
	(1,1,'/admin/login','116.227.134.44','后台登陆','2019-07-18 11:49:16'),
	(2,1,'/users/destroy','116.227.134.44','操作用户操作失败','2019-07-18 11:56:22'),
	(3,1,'/menus/story','116.227.134.44','操作菜单操作成功','2019-07-18 12:38:23'),
	(4,1,'/menus/story','116.227.134.44','操作菜单操作成功','2019-07-18 12:39:50'),
	(5,1,'/menus/story','116.227.134.44','操作菜单操作成功','2019-07-18 12:41:31'),
	(6,1,'/menus/story','116.227.134.44','操作菜单操作成功','2019-07-18 12:42:36'),
	(7,1,'/menus/story','116.227.134.44','操作菜单操作成功','2019-07-18 12:44:19'),
	(8,1,'/admin/login','183.195.6.227','后台登陆','2019-07-18 23:24:06'),
	(9,1,'/saveinfo/2','183.195.6.227','修改用户密码操作成功','2019-07-18 23:24:26'),
	(10,1,'/admin/login','183.195.6.227','后台登陆','2019-07-18 23:25:08'),
	(11,1,'/admin/login','116.227.134.44','后台登陆','2019-07-19 10:38:05'),
	(12,1,'/admin/login','218.82.228.218','后台登陆','2019-07-19 17:45:20'),
	(13,1,'/admin/login','183.195.6.227','后台登陆','2019-07-20 08:36:07'),
	(14,1,'/admin/login','183.195.6.227','后台登陆','2019-07-20 11:44:30'),
	(15,1,'/admin/login','183.195.6.227','后台登陆','2019-07-20 16:56:42');

/*!40000 ALTER TABLE `admin_logs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_material_process
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_material_process`;

CREATE TABLE `admin_material_process` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `admin_material_process` WRITE;
/*!40000 ALTER TABLE `admin_material_process` DISABLE KEYS */;

INSERT INTO `admin_material_process` (`id`, `name`, `value`)
VALUES
	(1,'model_cutface','5k'),
	(2,'model_cutface','1w'),
	(3,'model_cutface','5w'),
	(4,'model_compress_algorithm','dgene'),
	(5,'texture_resize','512'),
	(6,'texture_resize','1k'),
	(7,'texture_resize','2k'),
	(8,'texture_resize','4k'),
	(9,'texture_compress','webp'),
	(10,'texture_compress','jpg');

/*!40000 ALTER TABLE `admin_material_process` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_menus`;

CREATE TABLE `admin_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '菜单排序',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图标',
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URI',
  `routes` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '路由,如url:/menu,controller:MenuController',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_menus` WRITE;
/*!40000 ALTER TABLE `admin_menus` DISABLE KEYS */;

INSERT INTO `admin_menus` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `routes`, `created_at`, `updated_at`)
VALUES
	(1,0,2,'权限设置','layui-icon-set-fill','/admin/qx','url:','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(2,1,5,'用户管理','layui-icon-friends','/admin/users','url:/users','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(3,1,6,'角色管理','layui-icon-util','/admin/roles','url:/roles','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(4,1,7,'权限管理','layui-icon-chart-screen','/admin/permissions','url:/permissions','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(5,1,8,'菜单管理','layui-icon-tabs','/admin/menus','url:/menus','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(6,0,1,'日志设置','layui-icon-layouts','/admin/log','url:','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(7,6,2,'日志管理','layui-icon-file','/admin/logs','url:/logs','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(8,0,3,'项目管理','layui-icon-app','/admin/project','url:/admin/project','2019-07-18 12:38:23','2019-07-18 12:38:23'),
	(9,8,1,'上传素材','layui-icon-app','/admin/material/upload','url:/admin/material/upload','2019-07-18 12:39:49','2019-07-18 12:39:49'),
	(10,8,2,'素材管理','layui-icon-template','/admin/material/list','url:/admin/material/list','2019-07-18 12:41:31','2019-07-18 12:41:31'),
	(11,8,3,'监控视频管理','layui-icon-video','/admin/stream','url:/admin/stream','2019-07-18 12:42:36','2019-07-18 12:42:36'),
	(12,8,4,'项目管理','layui-icon-component','/admin/project','url:/admin/project','2019-07-18 12:44:19','2019-07-18 12:44:19');

/*!40000 ALTER TABLE `admin_menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_permission_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_permission_menu`;

CREATE TABLE `admin_permission_menu` (
  `permission_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  KEY `permission_menu_permission_id_menu_id_index` (`permission_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_permission_menu` WRITE;
/*!40000 ALTER TABLE `admin_permission_menu` DISABLE KEYS */;

INSERT INTO `admin_permission_menu` (`permission_id`, `menu_id`)
VALUES
	(1,2),
	(2,2),
	(3,3),
	(4,3),
	(5,4),
	(6,4),
	(7,5),
	(8,5),
	(9,7);

/*!40000 ALTER TABLE `admin_permission_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_permission_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_permission_role`;

CREATE TABLE `admin_permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_permission_role` WRITE;
/*!40000 ALTER TABLE `admin_permission_role` DISABLE KEYS */;

INSERT INTO `admin_permission_role` (`permission_id`, `role_id`)
VALUES
	(1,1),
	(2,1),
	(3,1),
	(4,1),
	(5,1),
	(6,1),
	(7,1),
	(8,1),
	(9,1);

/*!40000 ALTER TABLE `admin_permission_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_permissions`;

CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限名 英文',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '显示名 中文',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
  `controllers` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '对应的controllers',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;

INSERT INTO `admin_permissions` (`id`, `name`, `display_name`, `description`, `controllers`, `created_at`, `updated_at`)
VALUES
	(1,'userlist','用户管理查看','用户管理查看','UserController@get','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(2,'userhandle','用户管理编辑','用户管理编辑','UserController@post','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(3,'rolelist','角色管理查看','角色管理查看','RoleController@get','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(4,'rolehandle','角色管理编辑','角色管理编辑','RoleController@post','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(5,'perlist','权限管理查看','权限管理查看','PermissionController@get','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(6,'perhandle','权限管理编辑','权限管理编辑','PermissionController@post','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(7,'menulist','菜单管理查看','菜单管理查看','MenuController@get','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(8,'menuhandle','菜单管理编辑','菜单管理编辑','MenuController@post','2019-07-18 11:28:31','2019-07-18 11:28:31'),
	(9,'loglist','日志管理查看','日志管理查看','LogController@get','2019-07-18 11:28:31','2019-07-18 11:28:31');

/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_menu`;

CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  KEY `role_menu_role_id_menu_id_index` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`)
VALUES
	(1,1),
	(1,2),
	(1,3),
	(1,4),
	(1,5),
	(1,6),
	(1,7),
	(1,8),
	(1,9),
	(1,10),
	(1,11),
	(1,12);

/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_user`;

CREATE TABLE `admin_role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_user` WRITE;
/*!40000 ALTER TABLE `admin_role_user` DISABLE KEYS */;

INSERT INTO `admin_role_user` (`user_id`, `role_id`)
VALUES
	(1,1);

/*!40000 ALTER TABLE `admin_role_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_roles`;

CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '显示名',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;

INSERT INTO `admin_roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`)
VALUES
	(1,'admin','超级管理员','最高级的权限','2019-07-18 11:28:31','2019-07-18 11:28:31');

/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮件',
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号码',
  `sex` smallint(6) NOT NULL DEFAULT '1' COMMENT '性别',
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'TOKEN',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;

INSERT INTO `admin_users` (`id`, `username`, `email`, `mobile`, `sex`, `password`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'admin','admin@admin.com','18888888888',1,'$2y$10$V51YJr7QDtj1SiL0y6yDOePUG1RKC9WOM1QLLvw2YlFUcuKDfg0iW','CvsQGqX1PcBYVQcVRvna0K6HID6Bz3m91tRTyb7RKYTUe5wG0mQTlmWWorOM','2019-07-18 11:28:31','2019-07-18 23:24:25');

/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2017_07_04_104528_create_admin_users_table',1),
	(2,'2017_07_04_104719_create_admin_roles_table',1),
	(3,'2017_07_04_104933_create_admin_logs_table',1),
	(4,'2017_07_04_104933_create_admin_menus_table',1),
	(5,'2017_07_04_104933_create_admin_permission_menu_table',1),
	(6,'2017_07_04_104933_create_admin_permission_role_table',1),
	(7,'2017_07_04_104933_create_admin_permissions_table',1),
	(8,'2017_07_04_104933_create_admin_role_menu_table',1),
	(9,'2017_07_04_104933_create_admin_role_user_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
