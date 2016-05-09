 CREATE TABLE `Movie`(
		`id` INT NOT NULL,
		`title` VARCHAR(100) NOT NULL,
		`year` INT,
		`rating` VARCHAR(10),
		`company` VARCHAR(50),
		PRIMARY KEY(`id`)
 )engine=InnoDB;
 
 CREATE TABLE `Actor`(
		`id` INT NOT NULL,
		`last` VARCHAR(20),
		`first` VARCHAR(20),
		`sex` VARCHAR(6),
		`dob` DATE NOT NULL,
		`dod` DATE,
		PRIMARY KEY(`id`),
		CHECK((`dod` IS NOT NULL AND `dod`>`dob`) or(`dod` is NULL))
 )engine=InnoDB;
 
 CREATE TABLE `Director`(
		`id` INT NOT NULL,
		`last` VARCHAR(20),
		`first` VARCHAR(20),
		`sex` VARCHAR(6),
		`dob` DATE NOT NULL,
		`dod` DATE,
		PRIMARY KEY(`id`),
		CHECK((`dod` IS NOT NULL AND `dod`>`dob`) or(`dod` is NULL))
)engine=InnoDB;
 
 
 CREATE TABLE `MovieGenre`(
		`mid` INT NOT NULL,
		`genre` VARCHAR(20) NOT NULL,
		UNIQUE(`mid`,`genre`),
		FOREIGN KEY(`mid`) references Movie(`id`)
 )ENGINE = INNODB;
 
 CREATE TABLE `MovieDirector`(
		`mid` INT NOT NULL,
		`did` INT NOT NULL,
		UNIQUE key(`mid`,`did`),
		FOREIGN KEY(`mid`) references Movie(`id`),
		FOREIGN KEY(`did`) references Director(`id`)
 )ENGINE=MyISAM AUTO_INCREMENT=68639 DEFAULT CHARSET=gbk;
 
 CREATE TABLE `MovieActor`(
		`mid` INT NOT NULL,
		`aid` INT NOT NULL,
		`role` VARCHAR(50) NOT NULL,
		UNIQUE(`mid`,`aid`,`role`),
		FOREIGN KEY(`mid`) references Movie(`id`),
		FOREIGN KEY(`aid`) references Actor(`id`)
 )ENGINE = INNODB;
 
 CREATE TABLE `Review`(
		`name` VARCHAR(20) NOT NULL,
		`time` TIMESTAMP NOT NULL,
		`mid` INT NOT NULL,
		`rating` INT NOT NULL,
		`comment` VARCHAR(500),
		UNIQUE(`name`,`mid`),
		FOREIGN KEY(`mid`) references Movie(`id`),
		CHECK(`rating`>=0 and `rating`<=5)
 )ENGINE = INNODB;
 
 CREATE TABLE `MaxPersonID` (
		`id` INT NOT NULL
 )ENGINE = INNODB;
 
 CREATE TABLE `MaxMovieID` (
		`id` INT NOT NULL
 )ENGINE = INNODB;