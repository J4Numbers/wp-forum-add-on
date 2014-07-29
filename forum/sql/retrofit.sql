/*
--------------------------------------
---  (c) 2014 Matthew David Ball   ---
---     numbers@cynicode.co.uk     ---
--------------------------------------
*/

USE `m4numbers`;

CREATE TABLE `wp_forum_heads` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `desc` VARCHAR (128) NOT NULL,
  `order` INTEGER(11) NOT NULL,
  PRIMARY KEY (`ID`)
);

INSERT INTO `wp_forum_heads` (`name`,`desc`,`order`) VALUES ('Testing','Welcome','1');

CREATE TABLE `wp_forum_cats` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `head` INTEGER(11) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `desc` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`head`) REFERENCES `wp_forum_heads`(`ID`)
);

INSERT INTO `wp_forum_cats` (`head`,`name`,`desc`) VALUES ('1','Hola!','This is another test');

CREATE TABLE `wp_forum_threads` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `cat` INTEGER(11) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `time` INTEGER(11) NOT NULL,
  `creator` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`cat`) REFERENCES `wp_forum_cats`(`ID`),
  FOREIGN KEY (`creator`) REFERENCES `cyni_wp_users`(`ID`)
);

INSERT INTO `wp_forum_threads` (`cat`,`name`,`time`,`creator`) VALUES ('1','Bonjour!','1','1');

CREATE TABLE `wp_forum_posts` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `thread` INTEGER(11) NOT NULL,
  `time` INTEGER(11) NOT NULL,
  `content` TEXT NOT NULL,
  `poster` BIGINT(20) UNSIGNED NOT NULL,
  `last_edited` INTEGER(11) NOT NULL,
  `edited_by` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`thread`) REFERENCES `wp_forum_threads`(`ID`),
  FOREIGN KEY (`poster`) REFERENCES `cyni_wp_users`(`ID`),
  FOREIGN KEY (`edited_by`) REFERENCES `cyni_wp_users`(`ID`)
);

INSERT INTO `wp_forum_posts` (`thread`,`time`,`content`,`poster`,`last_edited`,`edited_by`) VALUES
  ('1','1','Guten Tag!','1','1','1');