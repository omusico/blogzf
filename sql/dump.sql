CREATE TABLE `posts` (
`post_id` MEDIUMINT( 10 ) NOT NULL AUTO_INCREMENT ,
`user_id` MEDIUMINT( 10 ) NOT NULL ,
`title` TEXT NOT NULL ,
`content` LONGTEXT NOT NULL ,
`comment` TINYINT( 1 ) NOT NULL ,
`created_date` DATETIME NOT NULL ,
`modified_date` DATETIME NOT NULL ,
`status` CHAR( 10 ) NOT NULL ,
PRIMARY KEY ( `post_id` )
) ENGINE = MYISAM;

CREATE TABLE `users` (
`user_id` MEDIUMINT( 10 ) NOT NULL AUTO_INCREMENT ,
`username` CHAR( 50 ) NOT NULL ,
`password` CHAR( 50 ) NOT NULL ,
`display_name` CHAR( 100 ) NOT NULL ,
`status` CHAR( 10 ) NOT NULL ,
PRIMARY KEY ( `user_id` )
) ENGINE = MYISAM;
