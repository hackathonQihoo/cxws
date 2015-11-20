/*
 Navicat MySQL Data Transfer

 Source Server         : workspace
 Source Server Version : 50615
 Source Host           : localhost
 Source Database       : air

 Target Server Version : 50615
 File Encoding         : utf-8

 Date: 07/25/2015 21:49:35 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `weather`
-- ----------------------------
DROP TABLE IF EXISTS `weather`;
CREATE TABLE `weather` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `weather` varchar(10) NOT NULL,
  `wind_speed` varchar(10) NOT NULL,
  `wind_direct` varchar(5) NOT NULL,
  `height` float(10,2) NOT NULL,
  `temperature` varchar(20) NOT NULL,
  `uv` varchar(2) NOT NULL,
  `preci` int(3) NOT NULL,
  `rain` int(3) NOT NULL,
  `message` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `weather`
-- ----------------------------
BEGIN;
INSERT INTO `weather` VALUES ('1', '晴', '2-3级', '西北', '1300.00', '22℃-30℃', '强', '30', '0', '适合骑行，请注意防晒'), ('2', '阴', '8-9级', '东南', '870.00', '28℃-32℃', '弱', '36', '0', '大风预警，不利于骑行');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
