/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : gg-english

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2014-11-27 00:29:03
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `accessibility_options`
-- ----------------------------
DROP TABLE IF EXISTS `accessibility_options`;
CREATE TABLE `accessibility_options` (
  `id` varbinary(16) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `network_id` varbinary(16) DEFAULT NULL,
  `display_order` smallint(3) DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `network_id` (`network_id`) USING BTREE,
  CONSTRAINT `accessibility_options_ibfk_1` FOREIGN KEY (`network_id`) REFERENCES `networks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of accessibility_options
-- ----------------------------

-- ----------------------------
-- Table structure for `activation_codes`
-- ----------------------------
DROP TABLE IF EXISTS `activation_codes`;
CREATE TABLE `activation_codes` (
  `id` varbinary(16) NOT NULL,
  `code` varchar(128) NOT NULL,
  `expiry_date` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_activation_codes_id` (`id`) USING BTREE,
  UNIQUE KEY `code` (`code`) USING BTREE,
  KEY `code_expiry_date` (`code`,`expiry_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activation_codes
-- ----------------------------

-- ----------------------------
-- Table structure for `change_email_ownership_validations`
-- ----------------------------
DROP TABLE IF EXISTS `change_email_ownership_validations`;
CREATE TABLE `change_email_ownership_validations` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `code` varchar(64) NOT NULL,
  `new_email` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `code` (`code`) USING BTREE,
  CONSTRAINT `change_email_ownership_validations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `change_email_ownership_validations_ibfk_2` FOREIGN KEY (`code`) REFERENCES `activation_codes` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of change_email_ownership_validations
-- ----------------------------

-- ----------------------------
-- Table structure for `change_email_validations`
-- ----------------------------
DROP TABLE IF EXISTS `change_email_validations`;
CREATE TABLE `change_email_validations` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `code` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `code` (`code`) USING BTREE,
  CONSTRAINT `change_email_validations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `change_email_validations_ibfk_2` FOREIGN KEY (`code`) REFERENCES `activation_codes` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of change_email_validations
-- ----------------------------

-- ----------------------------
-- Table structure for `concerns`
-- ----------------------------
DROP TABLE IF EXISTS `concerns`;
CREATE TABLE `concerns` (
  `id` varbinary(16) NOT NULL,
  `object_id` varbinary(16) NOT NULL,
  `object_type` int(2) NOT NULL,
  `content` text NOT NULL,
  `info` text,
  `created` int(11) DEFAULT NULL,
  `checked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of concerns
-- ----------------------------

-- ----------------------------
-- Table structure for `core_authassignment`
-- ----------------------------
DROP TABLE IF EXISTS `core_authassignment`;
CREATE TABLE `core_authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varbinary(16) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `fk_authassignment_users` (`userid`) USING BTREE,
  CONSTRAINT `core_authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `core_authitem` (`name`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `core_authassignment_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `userbase_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of core_authassignment
-- ----------------------------

-- ----------------------------
-- Table structure for `core_authitem`
-- ----------------------------
DROP TABLE IF EXISTS `core_authitem`;
CREATE TABLE `core_authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of core_authitem
-- ----------------------------
INSERT INTO `core_authitem` VALUES ('Admin', '2', 'Administrator', null, 'N;');
INSERT INTO `core_authitem` VALUES ('Authenticated', '2', 'Authenticated', 'return currentUser()->id != -1;', 'N;');
INSERT INTO `core_authitem` VALUES ('Awaiting', '2', 'Awaiting', null, 'N;');
INSERT INTO `core_authitem` VALUES ('Guest', '2', 'Guest', 'return currentUser()->id == -1;', 'N;');
INSERT INTO `core_authitem` VALUES ('Member', '2', 'Member', null, 'N;');
INSERT INTO `core_authitem` VALUES ('profile.*', '1', 'profile.*', null, 'N;');
INSERT INTO `core_authitem` VALUES ('SAdmin', '2', 'Super Administrator', null, 'N;');
INSERT INTO `core_authitem` VALUES ('Users.Profile.*', '1', null, null, 'N;');

-- ----------------------------
-- Table structure for `core_authitemchild`
-- ----------------------------
DROP TABLE IF EXISTS `core_authitemchild`;
CREATE TABLE `core_authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`) USING BTREE,
  CONSTRAINT `core_authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `core_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `core_authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `core_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of core_authitemchild
-- ----------------------------
INSERT INTO `core_authitemchild` VALUES ('Admin', 'profile.*');
INSERT INTO `core_authitemchild` VALUES ('Member', 'profile.*');
INSERT INTO `core_authitemchild` VALUES ('Authenticated', 'Users.Profile.*');

-- ----------------------------
-- Table structure for `core_objects`
-- ----------------------------
DROP TABLE IF EXISTS `core_objects`;
CREATE TABLE `core_objects` (
  `id` varbinary(16) NOT NULL DEFAULT '',
  `name` varchar(128) NOT NULL,
  `alias` varchar(128) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of core_objects
-- ----------------------------

-- ----------------------------
-- Table structure for `core_rights`
-- ----------------------------
DROP TABLE IF EXISTS `core_rights`;
CREATE TABLE `core_rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`),
  CONSTRAINT `core_rights_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `core_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of core_rights
-- ----------------------------
INSERT INTO `core_rights` VALUES ('Admin', '2', '1');
INSERT INTO `core_rights` VALUES ('Authenticated', '2', '4');
INSERT INTO `core_rights` VALUES ('Awaiting', '2', '3');
INSERT INTO `core_rights` VALUES ('Guest', '2', '5');
INSERT INTO `core_rights` VALUES ('Member', '2', '2');
INSERT INTO `core_rights` VALUES ('SAdmin', '2', '0');

-- ----------------------------
-- Table structure for `email_templates`
-- ----------------------------
DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` varbinary(16) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `footer` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `layout` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of email_templates
-- ----------------------------

-- ----------------------------
-- Table structure for `facebook_photos`
-- ----------------------------
DROP TABLE IF EXISTS `facebook_photos`;
CREATE TABLE `facebook_photos` (
  `id` varbinary(16) NOT NULL DEFAULT '',
  `fb_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` text COLLATE utf8_unicode_ci,
  `user_id` varbinary(16) DEFAULT NULL,
  `done` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of facebook_photos
-- ----------------------------

-- ----------------------------
-- Table structure for `feedbacks`
-- ----------------------------
DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks` (
  `id` varbinary(20) NOT NULL DEFAULT '',
  `name` varchar(32) DEFAULT NULL,
  `content` text NOT NULL,
  `screen` varchar(128) DEFAULT NULL,
  `regions` text,
  `snapshot` longtext,
  `created` datetime DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `platform` varchar(128) DEFAULT NULL,
  `browser` varchar(128) DEFAULT NULL,
  `canvas` longtext,
  `url` varchar(512) DEFAULT NULL,
  `user_id` varbinary(18) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of feedbacks
-- ----------------------------

-- ----------------------------
-- Table structure for `forgot_password_validation`
-- ----------------------------
DROP TABLE IF EXISTS `forgot_password_validation`;
CREATE TABLE `forgot_password_validation` (
  `id` varbinary(16) NOT NULL DEFAULT '',
  `code` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`) USING BTREE,
  CONSTRAINT `forgot_password_validation_ibfk_1` FOREIGN KEY (`code`) REFERENCES `activation_codes` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forgot_password_validation
-- ----------------------------

-- ----------------------------
-- Table structure for `linked_accessibility_options`
-- ----------------------------
DROP TABLE IF EXISTS `linked_accessibility_options`;
CREATE TABLE `linked_accessibility_options` (
  `id` varbinary(16) NOT NULL,
  `linked_account_id` varbinary(16) DEFAULT NULL,
  `accessibility_option_id` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `linked_account_id` (`linked_account_id`) USING BTREE,
  KEY `accessibility_option_id` (`accessibility_option_id`) USING BTREE,
  CONSTRAINT `linked_accessibility_options_ibfk_1` FOREIGN KEY (`linked_account_id`) REFERENCES `linked_accounts` (`id`),
  CONSTRAINT `linked_accessibility_options_ibfk_2` FOREIGN KEY (`accessibility_option_id`) REFERENCES `accessibility_options` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of linked_accessibility_options
-- ----------------------------

-- ----------------------------
-- Table structure for `linked_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `linked_accounts`;
CREATE TABLE `linked_accounts` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) DEFAULT NULL,
  `network_id` varbinary(16) DEFAULT NULL,
  `network_account_id` varchar(128) NOT NULL,
  `network_account_data` text NOT NULL,
  `has_created_password` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `compound_unique_linked_accounts` (`user_id`,`network_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `network_id` (`network_id`) USING BTREE,
  CONSTRAINT `linked_accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `linked_accounts_ibfk_2` FOREIGN KEY (`network_id`) REFERENCES `networks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of linked_accounts
-- ----------------------------
INSERT INTO `linked_accounts` VALUES (0x5178047586F44765806E1840C0A801BE, 0x51780475F324454F82781840C0A801BE, 0x50EC8F4CAD78832F194E16307F780135, '1768137801', 'a:8:{s:2:\"id\";s:10:\"1768137801\";s:4:\"name\";s:14:\"Bình Quan Duc\";s:10:\"first_name\";s:5:\"Bình\";s:9:\"last_name\";s:8:\"Quan Duc\";s:8:\"birthday\";s:10:\"09/21/1981\";s:5:\"email\";s:16:\"binhqd@gmail.com\";s:6:\"locale\";s:5:\"en_US\";s:7:\"picture\";a:1:{s:4:\"data\";a:4:{s:3:\"url\";s:101:\"https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash3/s148x148/1238146_3301661358206_1552757675_a.jpg\";s:5:\"width\";i:148;s:6:\"height\";i:148;s:13:\"is_silhouette\";b:0;}}}', '1');

-- ----------------------------
-- Table structure for `networks`
-- ----------------------------
DROP TABLE IF EXISTS `networks`;
CREATE TABLE `networks` (
  `id` varbinary(16) NOT NULL,
  `name` varchar(50) NOT NULL,
  `alias` varchar(16) NOT NULL,
  `description` text,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  UNIQUE KEY `alias` (`alias`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of networks
-- ----------------------------
INSERT INTO `networks` VALUES (0x50DFA64398C7432D543396307F7A7A9C, 'Yahoo', 'yahoo', null, null);
INSERT INTO `networks` VALUES (0x50EC8F4CAD78832F194E16307F780135, 'Facebook', 'facebook', null, null);
INSERT INTO `networks` VALUES (0x5EBC8A4CADC7432F19D396307F7A65F4, 'Google', 'google', null, null);

-- ----------------------------
-- Table structure for `user_data`
-- ----------------------------
DROP TABLE IF EXISTS `user_data`;
CREATE TABLE `user_data` (
  `id` varbinary(16) NOT NULL DEFAULT '',
  `title` varchar(16) NOT NULL,
  `words` smallint(4) unsigned DEFAULT '0',
  `year` smallint(4) unsigned DEFAULT '2013',
  `user_id` varbinary(16) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_data
-- ----------------------------
INSERT INTO `user_data` VALUES (0x521B0EED9FF040E5AF781458C0A801BE, 'Bộ sưu tập 1', '0', '2013', 0x51780475F324454F82781840C0A801BE, '2013-08-26 10:16:45');
INSERT INTO `user_data` VALUES (0x521EA6E402E445C88E9615A8C0A801BE, 'Bộ sưu tập 2', '0', '2013', 0x51780475F324454F82781840C0A801BE, '2013-08-29 03:41:56');
INSERT INTO `user_data` VALUES (0x52780BBA82404D4191E01A40C0A801BE, 'Bộ sưu tập 3', '0', '2013', 0x51780475F324454F82781840C0A801BE, '2013-09-04 12:30:18');

-- ----------------------------
-- Table structure for `user_properties_tmp`
-- ----------------------------
DROP TABLE IF EXISTS `user_properties_tmp`;
CREATE TABLE `user_properties_tmp` (
  `id` varbinary(16) NOT NULL DEFAULT '',
  `category` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `user_id` varbinary(16) DEFAULT NULL,
  `created` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `user_properties_tmp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of user_properties_tmp
-- ----------------------------

-- ----------------------------
-- Table structure for `userbase_activation_codes`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_activation_codes`;
CREATE TABLE `userbase_activation_codes` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `code` varchar(64) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `created` int(11) NOT NULL,
  `expiry_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userbase_activation_codes
-- ----------------------------

-- ----------------------------
-- Table structure for `userbase_profiles`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_profiles`;
CREATE TABLE `userbase_profiles` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `gender` tinyint(4) DEFAULT '0',
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `status_text` text,
  `image` varchar(64) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `lastsyncfbphotos` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `userbase_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userbase_profiles
-- ----------------------------
INSERT INTO `userbase_profiles` VALUES (0x5178047514E8437893781840C0A801BE, 0x51780475F324454F82781840C0A801BE, '1', '', '', '', 'bae23d6f9954dd914041a22b171f8ade.jpg', null, '1376896514');
INSERT INTO `userbase_profiles` VALUES (0x51D69E5550CC417181230854CBDD56CB, 0x510B77D28C9C41FBA28E0630CBDD56CB, '0', 'Poland', '', 'The future is something which everyone reaches at the rate of 60 minutes an hour, whatever he does, whoever he is.', '11084b6b5035204ff277ded6fae10656.jpg', null, null);

-- ----------------------------
-- Table structure for `userbase_registration_activations`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_registration_activations`;
CREATE TABLE `userbase_registration_activations` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `code` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `userbase_registration_activations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_tmp_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `userbase_registration_activations_ibfk_2` FOREIGN KEY (`code`) REFERENCES `activation_codes` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userbase_registration_activations
-- ----------------------------

-- ----------------------------
-- Table structure for `userbase_socials`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_socials`;
CREATE TABLE `userbase_socials` (
  `id` varbinary(16) NOT NULL,
  `alias` varchar(48) DEFAULT NULL,
  `name` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_userbase_socials_alias` (`alias`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userbase_socials
-- ----------------------------

-- ----------------------------
-- Table structure for `userbase_tmp_profiles`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_tmp_profiles`;
CREATE TABLE `userbase_tmp_profiles` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `gender` tinyint(4) DEFAULT '0',
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `status_text` text,
  `image` varchar(64) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `userbase_tmp_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userbase_tmp_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of userbase_tmp_profiles
-- ----------------------------
INSERT INTO `userbase_tmp_profiles` VALUES (0x51F76AF6A7BC4E9D87E26D7BAC1F091F, 0x51F76AF64E9444978DD26D7BAC1F091F, '0', '51d53a34aba04a2aa4121339ac111364', null, null, null, '1987-06-09');
INSERT INTO `userbase_tmp_profiles` VALUES (0x520221C9B7BC40B7830375F8AC1F091F, 0x520221C95AA44F9093A375F8AC1F091F, '1', '51d54e6dfef44c8882ed75d6ac111364', null, null, null, '1976-11-20');
INSERT INTO `userbase_tmp_profiles` VALUES (0x5209E2FB037847E9A0FF1E9CAC1F091F, 0x5209E2FB2DC04280A82D1E9CAC1F091F, '0', '51d5481b56d84e1cb5606967ac111364', null, null, null, null);
INSERT INTO `userbase_tmp_profiles` VALUES (0x5209E3390CC84AD1BA781EFAAC1F091F, 0x5209E33978A041968E4B1EFAAC1F091F, '0', '51d5481b56d84e1cb5606967ac111364', null, null, null, null);
INSERT INTO `userbase_tmp_profiles` VALUES (0x5209E58E78504C39A4981F73AC1F091F, 0x5209E58EBCC84A9B8BD21F73AC1F091F, '0', '51d5481b56d84e1cb5606967ac111364', null, null, null, null);
INSERT INTO `userbase_tmp_profiles` VALUES (0x5209ECD2DB54464E8C402078AC1F091F, 0x5209ECD2820849DA9ADC2078AC1F091F, '1', '51d5481b56d84e1cb5606967ac111364', null, null, null, '0000-00-00');
INSERT INTO `userbase_tmp_profiles` VALUES (0x520C60EB5F90404BB34912F0AC1F091F, 0x520C60EBD9684B1496E412F0AC1F091F, '1', '51d5481b56d84e1cb5606967ac111364', null, null, null, null);
INSERT INTO `userbase_tmp_profiles` VALUES (0x5212D1BABEF44B768DBA7ABDAC1F091F, 0x5212D1BA97F041629FD17ABDAC1F091F, '1', '51d54e6dfef44c8882ed75d6ac111364', null, null, null, '1986-02-26');
INSERT INTO `userbase_tmp_profiles` VALUES (0x5212D47265C841A3976E7B36AC1F091F, 0x5212D4727DC44BD481D47B36AC1F091F, '1', '51d53aa9b14c4cdebcfb1339ac111364', null, null, null, '1991-06-18');
INSERT INTO `userbase_tmp_profiles` VALUES (0x5278EA3F524849E2AD995333AC1F091F, 0x5278EA3FA5B84A4F90685333AC1F091F, '1', '51d54e6dfef44c8882ed75d6ac111364', null, null, null, '1913-03-16');

-- ----------------------------
-- Table structure for `userbase_tmp_users`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_tmp_users`;
CREATE TABLE `userbase_tmp_users` (
  `id` varbinary(16) NOT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `firstname` varchar(24) DEFAULT NULL,
  `lastname` varchar(48) DEFAULT NULL,
  `saltkey` varchar(8) DEFAULT NULL,
  `has_sent_code` tinyint(4) DEFAULT '0',
  `created` int(11) DEFAULT NULL,
  `displayname` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userbase_tmp_users
-- ----------------------------
INSERT INTO `userbase_tmp_users` VALUES (0x51F76AF64E9444978DD26D7BAC1F091F, 'huyenanhgmailcom', '61f4feadcbee99a2198d036481181278f988e957', 'HuyenAnh@gmail.com', 'Huyen anh', 'Anh', 'a7fca02c', '1', '1375169270', 'Huyen anh A.');
INSERT INTO `userbase_tmp_users` VALUES (0x520221C95AA44F9093A375F8AC1F091F, 'hailtgmailcom', '91b8ad1e2712c34caa0c154832f75eb12effd606', 'hailt@gmail.com', 'Hai', 'Le', '8f9ff5e9', '1', '1375871433', 'Hai L.');
INSERT INTO `userbase_tmp_users` VALUES (0x5209E2FB2DC04280A82D1E9CAC1F091F, 'new4mt2014com', '0600b5a9d392df418535895844b9f183e3bfab91', 'new4@mt2014.com', 'New4', 'New4', 'e499e050', '1', '1376379643', 'New4 N.');
INSERT INTO `userbase_tmp_users` VALUES (0x5209E33978A041968E4B1EFAAC1F091F, 'new3mt2014com', 'fd5faf9a8d6e249c7644d76acc4b5584d1939290', 'NEW3@mt2014.com', 'New3', 'New3', '0a47286f', '1', '1376379705', 'New3 N.');
INSERT INTO `userbase_tmp_users` VALUES (0x5209E58EBCC84A9B8BD21F73AC1F091F, 'new5mt2014com', '07dfd94d6885ec1062d019e84850970fc88ebe52', 'new5@mt2014.com', 'New5', 'New5', '7ac707c9', '1', '1376380302', 'New5 N.');
INSERT INTO `userbase_tmp_users` VALUES (0x5209ECD2820849DA9ADC2078AC1F091F, 'new6mt2014com', 'c85e6abe42ac597de88355e4a3004fbc300c207b', 'new6@mt2014.com', 'New6', 'New6', 'f2541795', '1', '1376382162', 'New6 N.');
INSERT INTO `userbase_tmp_users` VALUES (0x520C60EBD9684B1496E412F0AC1F091F, 'dunguyen2109yahocom', '2da484f38e96d98e9b9a9159b4de38bcf82d8f30', 'dunguyen2109@yahoo.com', 'Andrew', 'Nguyen', 'ca576e0f', '1', '1376542955', 'Andrew N.');
INSERT INTO `userbase_tmp_users` VALUES (0x5212D1BA97F041629FD17ABDAC1F091F, 'vuhatoancauxanhvn', '6ec19857976b7c00eadb4ae6548802a3fa25dcae', 'vuha@toancauxanh.vn', 'Vu', 'Hoang anh', 'b3e5b3e0', '1', '1376965050', 'Vu H.');
INSERT INTO `userbase_tmp_users` VALUES (0x5212D4727DC44BD481D47B36AC1F091F, 'thekaylokgmailcom', '836b20ff855696f95947600c52d1dc56d34a391e', 'thekaylook@gmail.com', 'Kay', 'Hieu', '6aa22429', '1', '1376965746', 'Kay H.');
INSERT INTO `userbase_tmp_users` VALUES (0x5278EA3FA5B84A4F90685333AC1F091F, 'baonwebdevvn', '0405fcdd55776b78a76a2186cc2036a6dda77cfc', 'baonn@webdev.vn', 'Baonn', 'Baonn', '99e47e57', '1', '1376643647', 'Baonn B.');

-- ----------------------------
-- Table structure for `userbase_users`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_users`;
CREATE TABLE `userbase_users` (
  `id` varbinary(16) NOT NULL,
  `displayname` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `firstname` varchar(24) DEFAULT NULL,
  `lastname` varchar(48) DEFAULT NULL,
  `saltkey` varchar(8) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `superuser` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(11) DEFAULT NULL,
  `lastvisited` int(11) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_core_users_email` (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userbase_users
-- ----------------------------
INSERT INTO `userbase_users` VALUES (0x510B77D28C9C41FBA28E0630CBDD56CB, 'Adminad K.', '4f44f6b0821b8a5fe5a0022475c5f8f28efd96e1', 'admin@greennet.com', 'Admin', 'GreenNet', 'f1865b91', '1', '1', '1359706066', '1373593733', 'admingrenet');
INSERT INTO `userbase_users` VALUES (0x51780475F324454F82781840C0A801BE, 'Binh Q.', 'a238478449e95d5e297b1e34a3b8fb8c7590457c', 'binhqd@gmail.com', 'Binh', 'Quan Duc', '1690828d', '1', '0', '1364984949', '1378291599', 'binhqdgmail');

-- ----------------------------
-- Table structure for `userbase_users_socials`
-- ----------------------------
DROP TABLE IF EXISTS `userbase_users_socials`;
CREATE TABLE `userbase_users_socials` (
  `id` varbinary(16) NOT NULL,
  `user_id` varbinary(16) NOT NULL,
  `social_id` varbinary(16) NOT NULL,
  `social_alias` varchar(48) DEFAULT NULL,
  `social_account_id` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_id` (`social_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `userbase_users_socials_ibfk_1` FOREIGN KEY (`social_id`) REFERENCES `userbase_socials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `userbase_users_socials_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `userbase_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userbase_users_socials
-- ----------------------------

-- ----------------------------
-- Table structure for `words`
-- ----------------------------
DROP TABLE IF EXISTS `words`;
CREATE TABLE `words` (
  `id` varbinary(16) NOT NULL DEFAULT '',
  `word` varchar(32) NOT NULL,
  `phonetic` varchar(64) DEFAULT NULL,
  `vietnamese` varchar(256) DEFAULT NULL,
  `meaning` text,
  `example` text,
  `user_data_id` varbinary(16) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `type` enum('verb','adjective','noun','adverb') DEFAULT 'noun',
  `learned` tinyint(1) unsigned DEFAULT '0',
  `image` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of words
-- ----------------------------
INSERT INTO `words` VALUES (0x35323236666239373733646334386162, 'spot', '/spɔt/', 'dấu, đốm, vết, vết nhơ, vết đen', 'a small, circumscribed mark caused by disease, allergic reaction, decay, etc.', 'About a month ago, one of the cats developed a small bald spot  on her head.', null, null, 'noun', '0', null);
INSERT INTO `words` VALUES (0x521C552A3B1C41D0AE2613D8C0A801BE, 'peak', '/pi:k/', 'Đỉnh, chóp', 'Reach a highest point', 'The peak of herpolitical career', 0x521B0EED9FF040E5AF781458C0A801BE, '2013-08-27 09:28:42', 'noun', '0', null);
INSERT INTO `words` VALUES (0x521C56EA8240433BBD2E13D8C0A801BE, 'bump', '/bump/', 'Làm nổi lên, thổi ra', 'to come more or less violently in contact with; collide with; strike', 'His car bumped a truck.', 0x521B0EED9FF040E5AF781458C0A801BE, '2013-08-27 09:36:10', 'noun', '0', null);
INSERT INTO `words` VALUES (0x521EA79C658C4957AB4415A8C0A801BE, 'smite', '/smait/', 'sự làm thử, sự cố gắng (n), làm thất bại, đánh thắng (v)', 'to strike or hit hard, with or as with the hand, a stick, or other weapon', ' She smote him on the back with her umbrella.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-08-29 03:45:00', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226F76BD608476F8AB31A40C0A801BE, 'irritate', '/\'iriteit/', 'làm phát cáu, chọc tức', 'to excite to impatience or anger; annoy.', 'Aspirin may irritate  the stomach and alcohol can amplify the toxic effects of  ...', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:03:39', 'verb', '1', null);
INSERT INTO `words` VALUES (0x5226F7E8BFC4418FA0621A40C0A801BE, 'steam', '/sti:m/', 'hơi nước (n), bốc hơi (v)', 'water in the form of an invisible gas or vapor.', '', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:05:44', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226F85E86784809B9B91A40C0A801BE, 'annoy', '/ə\'nɔi/', 'làm trái ý, làm khó chịu, làm bực mình; chọc tức, làm cho tức giận quấy rầy, làm phiền', 'to disturb or bother (a person) in a way that displeases, troubles, or slightly irritates', 'Big things, little things, all kinds of things annoy  you.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:07:42', 'verb', '1', null);
INSERT INTO `words` VALUES (0x5226F913F6E4427689601A40C0A801BE, 'regularly', '/\'regjuləri/', 'đều đều, đều đặn, thường xuyên', 'at regular times or intervals.', 'Keep watering them regularly if you want to grow them larger.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:10:43', 'verb', '1', null);
INSERT INTO `words` VALUES (0x5226F95E42484C1694EC1A40C0A801BE, 'mundane', '/\'mʌndein/', '(thuộc) cõi trần, thế tục, trần tục', 'common; ordinary; banal; unimaginative.', 'What I fear is that my conclusions appear trivial and mundane.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:11:58', 'adjective', '1', null);
INSERT INTO `words` VALUES (0x5226F9B9F61447B895661A40C0A801BE, 'retreat', '/ri\'tri:t/', '(quân sự) sự rút lui, sự rút quân; hiệu lệnh rút quân', 'the act of withdrawing, as into safety or privacy; retirement; seclusion.', 'The dollar staged a broad retreat  in sluggish year-end trading yesterday.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:13:29', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226FAC8C52C49A69C301A40C0A801BE, 'agenda', '/ə\'dʤendə/', '(số nhiều) những việc phải làm, chương trình nghị sự, nhật ký công tác', 'something that is to be done.', 'To win these victories, we must first place them on our diplomatic agenda .', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:18:00', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226FB3401204F78B86A1A40C0A801BE, 'over-talkative', '/\'ouvə \'tɔ:kətiv/', 'nói quá nhiều', '', '', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:19:48', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226FB9773DC48AB99B91A40C0A801BE, 'spot', '/spɔt/', 'dấu, đốm, vết, vết nhơ, vết đen', 'a small, circumscribed mark caused by disease, allergic reaction, decay, etc.', 'About a month ago, one of the cats developed a small bald spot  on her head.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:21:27', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226FBC7ACD04A7FA8311A40C0A801BE, 'colleague', '/kɔ\'li:g/', 'bạn đồng nghiệp, bạn đồng sự', '', '', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:22:15', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226FC2578B4432B961B1A40C0A801BE, 'gossip', '/\'gɔsip/', 'chuyện ngồi lê đôi mách, chuyện tầm phào, tin đồn nhảm', 'idle talk or rumor, especially about the personal or private affairs of others', 'When stressful times arise, so do rumors and gossip .', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:23:49', 'noun', '1', null);
INSERT INTO `words` VALUES (0x5226FC55807842F692B81A40C0A801BE, 'throughout', '/θru:\'aut/', 'từ đầu đến cuối, khắp, suốt', 'in or to every part of; everywhere in', 'They searched throughout the house.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:24:37', '', '1', null);
INSERT INTO `words` VALUES (0x5226FC9C470C459EBBAB1A40C0A801BE, 'tempt', '/tempt/', 'cám dỗ, quyến rũ, nhử, làm thèm, gợi thèm', 'to attract, appeal strongly to, or invite', 'They tempt  governments to splurge with money that may disappear tomorrow.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:25:48', 'verb', '1', null);
INSERT INTO `words` VALUES (0x5226FD0613784A57A0081A40C0A801BE, 'rude', '/ru:d/', 'khiếm nhã, bất lịch sự, vô lễ, láo xược; thô lỗ', 'without culture, learning, or refinement', 'Pointing at or touching something with the feet is also considered rude ', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:27:34', 'adjective', '1', null);
INSERT INTO `words` VALUES (0x5226FD5709504A78B5611A40C0A801BE, 'unfounded', '/ʌn\'faundid/', 'không căn cứ, không có sơ sở', 'without foundation; not based on fact, realistic considerations', 'the prophet of a religion as yet unfounded.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:28:55', 'adjective', '1', null);
INSERT INTO `words` VALUES (0x5226FD96D9884FD3A7CC1A40C0A801BE, 'disperse', '/dis\'pə:s/', 'xua tan, làm tan tác (mây mù...)', 'to spread widely; disseminate', 'Police had to disperse  them.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:29:58', 'verb', '1', null);
INSERT INTO `words` VALUES (0x5226FDE2D59C43FDA4671A40C0A801BE, 'informative', '/in\'fɔ:mətiv/', 'cung cấp nhiều tin tức, có nhiều tài liệu', 'giving information; instructive', 'Thanks so much for an informative  and educational article.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:31:14', 'adjective', '1', null);
INSERT INTO `words` VALUES (0x5226FE456A0441F4A1991A40C0A801BE, 'incentive ', '/in\'sentiv/', 'khuyến khích, khích lệ; thúc đẩy', 'something that incites or tends to incite to action or greater effort, as a reward offered for increased productivity.', 'an additional payment made to employees as a means of increasing production', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:32:53', 'adjective', '1', null);
INSERT INTO `words` VALUES (0x5226FE8560E8418EACA81A40C0A801BE, 'reminiscent', '/,remi\'nisnt/', 'nhớ lại; làm nhớ lại, gợi lại', 'awakening memories of something similar', 'His style of writing is reminiscent of Melville\'s.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:33:57', 'adjective', '1', '');
INSERT INTO `words` VALUES (0x5226FF6AD39C457C98A41A40C0A801BE, 'genuine', '/\'dʤenjuin/', 'thật, chính cống, xác thực', 'possessing the claimed or attributed character, quality, or origin; not counterfeit; authentic; real', 'Get real people, these aren\'t as genuine  as you think.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:37:46', 'adjective', '1', null);
INSERT INTO `words` VALUES (0x52780148348044528BFC1A40C0A801BE, 'populate', '/\'pɔpjuleit/', 'ở, cư trú (một vùng), đưa dân đến', 'to furnish with inhabitants, as by colonization; people.', 'And the insurers who populate  that market have grown all the stronger.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:45:44', 'verb', '1', null);
INSERT INTO `words` VALUES (0x52780199B6DC400BA0241A40C0A801BE, 'aid', '/eid/', 'sự giúp đỡ, sự cứu giúp, sự viện trợ', 'to provide support for or relief to', 'His heart is dodgy, he wears a hearing aid  and needs reading glasses.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:47:05', 'noun', '1', 'b2ad43aae5acd61dc56ff44f40679e78.jpg');
INSERT INTO `words` VALUES (0x52780212797042D7A3A81A40C0A801BE, 'meme', '/mi:m/', 'Như nhau', 'an idea or element of social behaviour passed on through generations in a culture', 'Your meme  needs a place to do business.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:49:06', 'noun', '1', null);
INSERT INTO `words` VALUES (0x527802590184415091781A40C0A801BE, 'tactics', '/\'tæktiks/', 'Chiến thuật', 'a plan, procedure, or expedient for promoting a desired end or result', '', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:50:17', 'noun', '1', null);
INSERT INTO `words` VALUES (0x527802C6A2604F2F86D91A40C0A801BE, 'debate', '/di\'beit/', 'cuộc tranh luận, cuộc thảo luận, cuộc tranh cãi', 'a discussion, as of a public question in an assembly, involving opposing viewpoints', 'While experts debate  that question, they agree that more devastating tempests are headed our way.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:52:06', 'noun', '1', null);
INSERT INTO `words` VALUES (0x52780304BB2C493BB7E41A40C0A801BE, 'regardless', '/ri\'gɑ:dlis/', 'không kể, không đếm xỉa tới, không chú ý tới; bất chấp', 'without concern as to advice, warning, hardship', 'They\'ll do it regardless of the cost.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:53:08', 'adjective', '1', null);
INSERT INTO `words` VALUES (0x527803595DD441F7889F1A40C0A801BE, 'sift', '/sift/', 'xem xét, chọn lọc (sự kiện về mặt chính xác, thật hư); phân tích tính chất của', 'to separate by or as if by a sieve to question closely.', 'It\'s only been two weeks and they have many applications to sift  through.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:54:33', 'noun', '1', null);
INSERT INTO `words` VALUES (0x52780E027C90424A88671A40C0A801BE, 'rid', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:02', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E07D5C44A6B889D1A40C0A801BE, 'ingredient', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:07', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E0C58A44D78A5831A40C0A801BE, 'overwhelming', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:12', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E12F4244575A4DD1A40C0A801BE, 'typo', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:18', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E19E3784F3B94671A40C0A801BE, 'behold', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:25', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E20672C467FB0541A40C0A801BE, 'culprit', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:32', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E25C5B845669BD01A40C0A801BE, 'compatibility', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:37', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E2B68384FDBAC031A40C0A801BE, 'bane', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:43', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E327830461CB3911A40C0A801BE, 'inadvertently', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:50', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E37029C47328B141A40C0A801BE, 'annual', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:40:55', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E43B3904C31B8571A40C0A801BE, 'lend credibility', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:41:07', 'noun', '0', null);
INSERT INTO `words` VALUES (0x52780E59BE784B78BD7A1A40C0A801BE, 'repurpose', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:41:29', 'noun', '0', null);
INSERT INTO `words` VALUES (0x5278780511E0402A81781A40C0A801BE, 'reveal', '/ri\'vi:l/', 'để lộ, tỏ ra, biểu lộ; bộc lộ, tiết lộ (điều bí mật)', 'to make known; disclose; divulge', '', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:40:21', 'verb', '1', '');
INSERT INTO `words` VALUES (0x527878107870485D89771A40C0A801BE, 'affinity', '/ə\'finiti/', 'mối quan hệ, sự giống nhau về cấu trúc (giữa các loài vật, cây cỏ, ngôn ngữ)', 'a natural liking for or attraction to a person, thing, idea, etc', 'It has a close linguistic and cultural affinity with its neighbours.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:40:32', 'noun', '1', '');
INSERT INTO `words` VALUES (0x527878AC5E9C4FB39DC61A40C0A801BE, 'pillar', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:38:36', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878B9B8F4483999A01A40C0A801BE, 'fluidity', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:38:49', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878BEA2A048F299781A40C0A801BE, 'perspective', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:38:54', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878C3A4A84CFDAD951A40C0A801BE, 'ambient', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:38:59', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878C8A7BC426F8F301A40C0A801BE, 'tombstone', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:04', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878CD4F4048A0ADCD1A40C0A801BE, 'commission', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:09', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878D3BE4C4D7885DA1A40C0A801BE, 'approximate', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:15', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878DA1FB846F3817C1A40C0A801BE, 'instrumentation', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:22', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878E278244FAC8EFE1A40C0A801BE, 'implanting', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:30', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878E823184C519F191A40C0A801BE, 'decay', '/di\'kei/', 'tình trạng suy tàn, tình trạng suy sụp, tình trạng sa sút (quốc gia, gia đình...)', 'to become decomposed', 'the decay of international relations; the decay of the Aztec civilizations.', 0x521EA6E402E445C88E9615A8C0A801BE, '2013-09-04 11:44:08', 'noun', '1', null);
INSERT INTO `words` VALUES (0x527878E9583444A4BC2A1A40C0A801BE, 'correlate', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:37', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878EFD7184123B5DE1A40C0A801BE, 'throw off', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:43', 'noun', '0', null);
INSERT INTO `words` VALUES (0x527878F6BCC4444EBE491A40C0A801BE, 'disastrous', '', '', '', '', 0x52780BBA82404D4191E01A40C0A801BE, '2013-09-04 12:39:50', 'noun', '0', null);
