-- =============================================================================
-- DIMPLESTAR – fresh schema + seed
-- Engine: InnoDB, Charset: utf8mb4
-- Safe to run on a clean DB. If tables exist they will be dropped first.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Create DB if not exists and use it
CREATE DATABASE IF NOT EXISTS `dimplestar`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE `dimplestar`;

-- -----------------------------------------------------------------------------
-- MEMBERS
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fname`      VARCHAR(100) NOT NULL,
  `lname`      VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role`       ENUM('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dev admin (email: admin@example.com / password: password)
INSERT INTO `members` (`fname`,`lname`,`email`,`password`,`role`)
VALUES
('Site','Admin','admin@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- bcrypt for "password"
 'admin');

-- -----------------------------------------------------------------------------
-- AUDIT TRAIL
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `audit_trail`;
CREATE TABLE `audit_trail` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `actor`      VARCHAR(190) NOT NULL,         -- usually email or user id
  `action`     VARCHAR(190) NOT NULL,         -- e.g., login_success
  `ip_address` VARCHAR(45)  DEFAULT NULL,     -- IPv4/IPv6
  `user_agent` TEXT         DEFAULT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ix_actor` (`actor`),
  KEY `ix_action` (`action`),
  KEY `ix_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------------
-- ABOUT PAGE (single-row storage; id=1)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `about_page`;
CREATE TABLE `about_page` (
  `id`         TINYINT UNSIGNED NOT NULL,
  `title`      VARCHAR(191) NOT NULL,
  `p1`         TEXT NOT NULL,
  `p2`         TEXT NOT NULL,
  `updated_at` DATETIME NOT NULL
               DEFAULT CURRENT_TIMESTAMP
               ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `about_page` (`id`,`title`,`p1`,`p2`)
VALUES
(1,
 'About Us',
 'Dimple Star Transport has been serving passengers with safe, reliable, and comfortable travel across the Philippines. With a strong commitment to affordable transportation and excellent service, we continue to connect people to destinations that matter most.',
 'Our fleet and dedicated staff ensure every journey is smooth and enjoyable — because your comfort and safety are always our priority.'
);

-- -----------------------------------------------------------------------------
-- REGS (kept close to your legacy structure; changed to utf8mb4)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `regs`;
CREATE TABLE `regs` (
  `ticket`     INT(100) NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255) DEFAULT NULL,
  `address`    VARCHAR(255) DEFAULT NULL,
  `mobile`     VARCHAR(255) DEFAULT NULL,
  `email`      VARCHAR(255) DEFAULT NULL,
  `bustype`    VARCHAR(255) DEFAULT NULL,
  `origin`     VARCHAR(255) DEFAULT NULL,
  `destination`VARCHAR(255) DEFAULT NULL,
  `price`      VARCHAR(255) DEFAULT NULL,
  `seat_no`    VARCHAR(255) DEFAULT NULL,
  `timetodep`  VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `regs` VALUES 
(1,'A','B','123465','C@D.COM','Ordinary','Espana','San Jose','200','7','5:30am'),
(2,'A','B','123465','C@D.COM','Ordinary','Espana','San Jose','200','8','5:30am'),
(3,'A','B','123465','C@D.COM','Ordinary','Espana','San Jose','200','9','5:30am'),
(4,'A','B','123465','C@D.COM','Ordinary','Espana','San Jose','200','10','5:30am'),
(5,'A','B','123465','C@D.COM','Ordinary','Espana','San Jose','200','11','5:30am'),
(6,'A','B','123465','C@D.COM','Ordinary','Espana','San Jose','200','12','5:30am'),
(7,'A','B','123465','C@D.COM','Ordinary','Espana','San Jose','200','13','5:30am');

-- -----------------------------------------------------------------------------
-- ROUTES (legacy + utf8mb4)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes` (
  `busid`      INT(20) NOT NULL AUTO_INCREMENT,
  `origin`     VARCHAR(50) NOT NULL,
  `destination`VARCHAR(50) NOT NULL,
  `time`       VARCHAR(20) NOT NULL,
  `price`      VARCHAR(20) NOT NULL,
  `bustype`    VARCHAR(20) NOT NULL,
  `smsstat`    VARCHAR(20) NOT NULL,
  PRIMARY KEY (`busid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `routes` VALUES
(1,'Ali Mall Cubao','San Jose','10am','300','Air Conditioned','None'),
(2,'Ali Mall Cubao','San Jose','9am','300','Air Conditioned','None'),
(3,'Ali Mall Cubao','San Jose','1pm','300','Air Conditioned','none'),
(4,'Ali Mall Cubao','San Jose','4pm','300','Air Conditioned','none'),
(5,'Alabang','San Jose','6am','300','Air Conditioned','None'),
(6,'Alabang','San Jose','7am','300','Air Conditioned','None'),
(7,'Alabang','San Jose','2pm','300','Air Conditioned','none'),
(8,'Alabang','San Jose','6pm','300','Air Conditioned','none'),
(9,'Alabang','San Jose','10pm','300','Air Conditioned','none'),
(10,'Cabuyao ','San Jose','8am','300','Air Conditioned','None'),
(11,'Cabuyao ','San Jose','9am','300','Air Conditioned','None'),
(12,'Cabuyao ','San Jose','4pm','300','Air Conditioned','none'),
(13,'Cabuyao ','San Jose','8pm','300','Air Conditioned','none'),
(14,'Espana','San Jose','4:30am','300','Air Conditioned','None'),
(15,'Espana','San Jose','5:30am','300','Air Conditioned','None'),
(16,'Espana','San Jose','12am','300','Air Conditioned','none'),
(17,'Espana','San Jose','4pm','300','Air Conditioned','none'),
(18,'Espana','San Jose','8pm','300','Air Conditioned','none'),
(19,'San Lorenzo','San Jose','3am','300','Air Conditioned','None'),
(20,'San Lorenzo','San Jose','4:30am','200','Air Conditioned','None'),
(21,'San Lorenzo','San Jose','11am','300','Air Conditioned','none'),
(22,'San Lorenzo','San Jose','3pm','300','Air Conditioned','none'),
(23,'San Lorenzo','San Jose','7pm','300','Air Conditioned','none'),
(24,'Pasay','San Jose','5am','300','Air Conditioned','None'),
(25,'Pasay','San Jose','6am','300','Air Conditioned','none'),
(26,'Pasay','San Jose','1pm','300','Air Conditioned','none'),
(27,'Pasay','San Jose','3pm','300','Air Conditioned','none'),
(28,'Ali Mall Cubao','San Jose','10am','200','Ordinary','None'),
(29,'Ali Mall Cubao','San Jose','9am','200','Ordinary','None'),
(30,'Ali Mall Cubao','San Jose','1pm','200','Ordinary','none'),
(31,'Ali Mall Cubao','San Jose','4pm','200','Ordinary','none'),
(32,'Alabang','San Jose','6am','200','Ordinary','None'),
(33,'Alabang','San Jose','7am','200','Ordinary','None'),
(34,'Alabang','San Jose','2pm','200','Ordinary','none'),
(35,'Alabang','San Jose','6pm','200','Ordinary','none'),
(36,'Alabang','San Jose','10pm','200','Ordinary','none'),
(37,'Cabuyao ','San Jose','8am','200','Ordinary','None'),
(38,'Cabuyao ','San Jose','9am','200','Ordinary','None'),
(39,'Cabuyao ','San Jose','4pm','200','Ordinary','none'),
(40,'Cabuyao ','San Jose','8pm','200','Ordinary','none'),
(41,'Espana','San Jose','4:30am','200','Ordinary','None'),
(42,'Espana','San Jose','5:30am','200','Ordinary','None'),
(43,'Espana','San Jose','12am','200','Ordinary','none'),
(44,'Espana','San Jose','4pm','200','Ordinary','none'),
(45,'Espana','San Jose','8pm','200','Ordinary','none'),
(46,'San Lorenzo','San Jose','3am','200','Ordinary','None'),
(47,'San Lorenzo','San Jose','4:30am','200','Ordinary','None'),
(48,'San Lorenzo','San Jose','11am','200','Ordinary','none'),
(49,'San Lorenzo','San Jose','3pm','200','Ordinary','none'),
(50,'San Lorenzo','San Jose','7pm','200','Ordinary','none'),
(51,'Pasay','San Jose','5am','200','Ordinary','None'),
(52,'Pasay','San Jose','6am','200','Ordinary','none'),
(53,'Pasay','San Jose','1pm','200','Ordinary','none'),
(54,'Pasay','San Jose','3pm','200','Ordinary','none');

SET FOREIGN_KEY_CHECKS = 1;
-- =============================================================================
-- End of file
-- =============================================================================
