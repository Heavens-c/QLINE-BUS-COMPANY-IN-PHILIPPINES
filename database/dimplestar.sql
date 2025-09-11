/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : dimplestar

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-08-05 16:26:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `regs`
-- ----------------------------
DROP TABLE IF EXISTS `regs`;
CREATE TABLE `regs` (
  `ticket` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `bustype` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `seat_no` varchar(255) DEFAULT NULL,
  `timetodep` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ticket`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of regs
-- ----------------------------
INSERT INTO `regs` VALUES ('1', 'A', 'B', '123465', 'C@D.COM', 'Ordinary', 'Espana', 'San Jose', '200', '7', '5:30am');
INSERT INTO `regs` VALUES ('2', 'A', 'B', '123465', 'C@D.COM', 'Ordinary', 'Espana', 'San Jose', '200', '8', '5:30am');
INSERT INTO `regs` VALUES ('3', 'A', 'B', '123465', 'C@D.COM', 'Ordinary', 'Espana', 'San Jose', '200', '9', '5:30am');
INSERT INTO `regs` VALUES ('4', 'A', 'B', '123465', 'C@D.COM', 'Ordinary', 'Espana', 'San Jose', '200', '10', '5:30am');
INSERT INTO `regs` VALUES ('5', 'A', 'B', '123465', 'C@D.COM', 'Ordinary', 'Espana', 'San Jose', '200', '11', '5:30am');
INSERT INTO `regs` VALUES ('6', 'A', 'B', '123465', 'C@D.COM', 'Ordinary', 'Espana', 'San Jose', '200', '12', '5:30am');
INSERT INTO `regs` VALUES ('7', 'A', 'B', '123465', 'C@D.COM', 'Ordinary', 'Espana', 'San Jose', '200', '13', '5:30am');

-- ----------------------------
-- Table structure for `routes`
-- ----------------------------
DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes` (
  `busid` int(20) NOT NULL AUTO_INCREMENT,
  `origin` varchar(20) NOT NULL,
  `destination` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `price` varchar(20) NOT NULL,
  `bustype` varchar(20) NOT NULL,
  `smsstat` varchar(20) NOT NULL,
  PRIMARY KEY (`busid`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of routes
-- ----------------------------
INSERT INTO `routes` VALUES ('1', 'Ali Mall Cubao', 'San Jose', '10am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('2', 'Ali Mall Cubao', 'San Jose', '9am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('3', 'Ali Mall Cubao', 'San Jose', '1pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('4', 'Ali Mall Cubao', 'San Jose', '4pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('5', 'Alabang', 'San Jose', '6am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('6', 'Alabang', 'San Jose', '7am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('7', 'Alabang', 'San Jose', '2pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('8', 'Alabang', 'San Jose', '6pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('9', 'Alabang', 'San Jose', '10pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('10', 'Cabuyao ', 'San Jose', '8am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('11', 'Cabuyao ', 'San Jose', '9am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('12', 'Cabuyao ', 'San Jose', '4pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('13', 'Cabuyao ', 'San Jose', '8pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('14', 'Espana', 'San Jose', '4:30am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('15', 'Espana', 'San Jose', '5:30am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('16', 'Espana', 'San Jose', '12am', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('17', 'Espana', 'San Jose', '4pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('18', 'Espana', 'San Jose', '8pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('19', 'San Lorenzo', 'San Jose', '3am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('20', 'San Lorenzo', 'San Jose', '4:30am', '200', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('21', 'San Lorenzo', 'San Jose', '11am', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('22', 'San Lorenzo', 'San Jose', '3pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('23', 'San Lorenzo', 'San Jose', '7pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('24', 'Pasay', 'San Jose', '5am', '300', 'Air Conditioned', 'None');
INSERT INTO `routes` VALUES ('25', 'Pasay', 'San Jose', '6am', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('26', 'Pasay', 'San Jose', '1pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('27', 'Pasay', 'San Jose', '3pm', '300', 'Air Conditioned', 'none');
INSERT INTO `routes` VALUES ('28', 'Ali Mall Cubao', 'San Jose', '10am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('29', 'Ali Mall Cubao', 'San Jose', '9am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('30', 'Ali Mall Cubao', 'San Jose', '1pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('31', 'Ali Mall Cubao', 'San Jose', '4pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('32', 'Alabang', 'San Jose', '6am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('33', 'Alabang', 'San Jose', '7am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('34', 'Alabang', 'San Jose', '2pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('35', 'Alabang', 'San Jose', '6pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('36', 'Alabang', 'San Jose', '10pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('37', 'Cabuyao ', 'San Jose', '8am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('38', 'Cabuyao ', 'San Jose', '9am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('39', 'Cabuyao ', 'San Jose', '4pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('40', 'Cabuyao ', 'San Jose', '8pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('41', 'Espana', 'San Jose', '4:30am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('42', 'Espana', 'San Jose', '5:30am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('43', 'Espana', 'San Jose', '12am', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('44', 'Espana', 'San Jose', '4pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('45', 'Espana', 'San Jose', '8pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('46', 'San Lorenzo', 'San Jose', '3am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('47', 'San Lorenzo', 'San Jose', '4:30am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('48', 'San Lorenzo', 'San Jose', '11am', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('49', 'San Lorenzo', 'San Jose', '3pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('50', 'San Lorenzo', 'San Jose', '7pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('51', 'Pasay', 'San Jose', '5am', '200', 'Ordinary', 'None');
INSERT INTO `routes` VALUES ('52', 'Pasay', 'San Jose', '6am', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('53', 'Pasay', 'San Jose', '1pm', '200', 'Ordinary', 'none');
INSERT INTO `routes` VALUES ('54', 'Pasay', 'San Jose', '3pm', '200', 'Ordinary', 'none');
