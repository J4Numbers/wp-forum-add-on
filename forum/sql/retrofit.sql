/*
--------------------------------------
---  (c) 2014 Matthew David Ball   ---
---     numbers@cynicode.co.uk     ---
--------------------------------------
*/

/*
 -- @ = Forum
 -- # = Wordpress
 */

CREATE TABLE `@heads` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `desc` VARCHAR (128) NOT NULL,
  `order` INTEGER(11) NOT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO `@heads` (`name`,`desc`,`order`) VALUES ('Your First Heading','Welcome!','1');

CREATE TABLE `@cats` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `head` INTEGER(11) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `desc` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`head`) REFERENCES `@heads`(`ID`)
);

INSERT INTO `@cats` (`head`,`name`,`desc`) VALUES ('1','Your First Category','Welcome!');

CREATE TABLE `@threads` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `cat` INTEGER(11) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `time` INTEGER(11) NOT NULL,
  `creator` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`cat`) REFERENCES `@cats`(`ID`),
  FOREIGN KEY (`creator`) REFERENCES `#users`(`ID`)
);

INSERT INTO `@threads` (`cat`,`name`,`time`,`creator`) VALUES ('1','Your First Thread','1','1');

CREATE TABLE `@posts` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `thread` INTEGER(11) NOT NULL,
  `time` INTEGER(11) NOT NULL,
  `content` TEXT NOT NULL,
  `poster` BIGINT(20) UNSIGNED NOT NULL,
  `last_edited` INTEGER(11) NOT NULL,
  `edited_by` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`thread`) REFERENCES `@threads`(`ID`),
  FOREIGN KEY (`poster`) REFERENCES `#users`(`ID`),
  FOREIGN KEY (`edited_by`) REFERENCES `#users`(`ID`)
);

INSERT INTO `@posts` (`thread`,`time`,`content`,`poster`,`last_edited`,`edited_by`) VALUES
  ('1','1','This is your first post! Congratulations!','1','1','1');