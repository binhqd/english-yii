/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50527
Source Host           : 127.0.0.1:3306
Source Database       : core_greennet

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2013-04-06 09:11:58
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `dnt_articles`
-- ----------------------------
DROP TABLE IF EXISTS `dnt_articles`;
CREATE TABLE `dnt_articles` (
  `id` varbinary(16) NOT NULL DEFAULT '',
  `title` varchar(128) NOT NULL,
  `alias` varchar(128) DEFAULT NULL,
  `description` text,
  `content` text,
  `created` datetime DEFAULT NULL,
  `image` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dnt_articles
-- ----------------------------
INSERT INTO `dnt_articles` VALUES (0x515EA59081704E9EAABA19A0C0A801BE, 'Test article', 'test-article', 'asdf sfd asdf sadf asdf', 'asdf sfd asdf sadf asdf', '0000-00-00 00:00:00', '8097f32a48006500929ca06f2647b4bb.jpg');
INSERT INTO `dnt_articles` VALUES (0x515F801F79B048718FF91780C0A801BE, 'The second article', 'the-second-article', 'sasdf asdf', 'sasdf asdf', '0000-00-00 00:00:00', '17c60bb961727e8c88b13c8f755b6fc4.jpg');
