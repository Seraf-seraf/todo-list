CREATE TABLE `USER` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_general_ci',
	`username` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_general_ci',
	`password` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_general_ci',
	`date_create_user` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `email` (`email`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
	
	
CREATE TABLE PROJECT (
	id INT(10) AUTO_INCREMENT PRIMARY KEY,
	user_id INT(10),
	project_name VARCHAR(64) NOT NULL COLLATE 'utf8mb4_general_ci',
	FOREIGN KEY (user_id) REFERENCES USER(id)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
	
CREATE TABLE `TASK` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`project_id` INT(10) NOT NULL,
	`task_name` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_general_ci',
	`date_create` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`file_url` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`task_completed` TINYINT(3) NULL DEFAULT '0',
	`task_deadline` DATE NULL DEFAULT NULL,
	`user_id` INT(10) NOT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `user_id` (`user_id`) USING BTREE,
	INDEX `project_id` (`project_id`) USING BTREE,
	FULLTEXT INDEX `task_name_4` (`task_name`),
	CONSTRAINT `task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `task_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `PROJECT` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
