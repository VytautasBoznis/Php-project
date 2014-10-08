CREATE TABLE IF NOT EXISTS `user_data` (
	`user_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`user_name` varchar(30) NOT NULL DEFAULT '',
	`user_pass` varchar(30) NOT NULL DEFAULT '',
	`first_name` varchar(30) NOT NULL DEFAULT '',
	`last_name` varchar(30) NOT NULL DEFAULT '',
	`user_level` int NOT NULL DEFAULT 1
	);
	
CREATE TABLE IF NOT EXISTS `uni_list` (
	`uni_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`uni_name` varchar(30) NOT NULL DEFAULT ''
	);
	
CREATE TABLE IF NOT EXISTS `uni_programs` (
	`program_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`uni_id` int NOT NULL,
	`program_name` varchar(30) NOT NULL DEFAULT ''
	);
	
CREATE TABLE IF NOT EXISTS `program_criteria` (
	`criteria_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`uni_id` int NOT NULL,
	`program_id` int NOT NULL ,
	`criteria_name` varchar(30) NOT NULL DEFAULT '',
	`criteria_part` DOUBLE(5,2) NOT NULL DEFAULT '',
	);
	
CREATE TABLE IF NOT EXISTS `user_scores` (
	`user_id` int NOT NULL,
	`program_id` int NOT NULL,
	`uni_id` int NOT NULL,
	`criteria_ids` varchar(30) NOT NULL DEFAULT '',
	`criteria_scores` varchar(30) NOT NULL DEFAULT '',
	`final_score` DOUBLE(5,2) NOT NULL DEFAULT 1
	);
	