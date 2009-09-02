CREATE TABLE IF NOT EXISTS `#__projectlog_docs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `project_id` INT(11) NOT NULL,
                `name` VARCHAR(255) NOT NULL DEFAULT '',
                `path` VARCHAR(255) NOT NULL DEFAULT '',
		`date` DATE NOT NULL DEFAULT '0000-00-00',
		`submittedby` INT(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ;

CREATE TABLE IF NOT EXISTS `#__projectlog_logs` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `project_id` INT(11) NOT NULL,
                `title` VARCHAR(255) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
		`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
		`loggedby` INT(11) NOT NULL,
		`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
		`modified_by` INT(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ;

CREATE TABLE IF NOT EXISTS `#__projectlog_projects` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `release_id` INT(11) NOT NULL,
		`job_id` INT(11) NOT NULL,
		`task_id` INT(11) NOT NULL,
		`workorder_id` INT(11) NOT NULL,
                `title` VARCHAR(255) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
		`release_date` DATE NOT NULL DEFAULT '0000-00-00',
		`contract_from` DATE NOT NULL DEFAULT '0000-00-00',
		`contract_to` DATE NOT NULL DEFAULT '0000-00-00',
		`location_gen` TEXT NOT NULL DEFAULT '',
		`location_spec` TEXT NOT NULL DEFAULT '',
		`manager` INT(11) NOT NULL,
		`chief` INT(11) NOT NULL,
		`technicians` VARCHAR(200) NOT NULL,
		`deployment_from` DATE NOT NULL DEFAULT '0000-00-00',
		`deployment_to` DATE NOT NULL DEFAULT '0000-00-00',
		`onsite` TINYINT(1) NOT NULL,
		`surveytype` VARCHAR(100) NOT NULL,
		`surveyor` VARCHAR(255) NOT NULL,
		`status` ENUM('In Progress','On Hold','Complete') NOT NULL,
		`published` TINYINT(1) NOT NULL,
                PRIMARY KEY (`id`)
            ) ;