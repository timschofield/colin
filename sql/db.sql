CREATE DATABASE IF NOT EXISTS `colin`;

USE `colin`

CREATE TABLE IF NOT EXISTS `tests` (
  `testnumber` INT(11) NOT NULL DEFAULT 0,
  `description` TEXT NOT NULL DEFAULT '',
  `lastrun` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`testnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `outputs` (
  `runtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `testnumber` INT(11) NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `message` VARCHAR(200) NOT NULL DEFAULT '',
  `testoutput` TEXT NOT NULL DEFAULT '',
  PRIMARY KEY (`runtime`, `testnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;