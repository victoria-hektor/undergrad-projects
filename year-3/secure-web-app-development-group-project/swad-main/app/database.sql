-- Creates database - coursework_db
-- Creates Tables - telemetry, users
-- populates column headings for each table
-- grants user access and sets privileges

CREATE DATABASE IF NOT EXISTS coursework_db COLLATE utf8_unicode_ci;

CREATE USER 'coursework_user'@localhost IDENTIFIED BY 'coursework_user_password';

GRANT SELECT, INSERT, UPDATE, DELETE on coursework_db.* TO 'coursework_user'@localhost;

USE coursework_db;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS telemetry;

CREATE TABLE users (
    `user_id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password_hash` CHAR(60) NOT NULL,
    `account_timestamp` timestamp NOT NULL DEFAULT
    current_timestamp() ON UPDATE current_timestamp()


)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='CURRENT_TIMESTAMP';

CREATE TABLE telemetry (

    `message_id` INT PRIMARY KEY AUTO_INCREMENT,
    `msisdn` BIGINT(13) NOT NULL,
    `temperature` INT(100) NOT NULL,
    `fan_direction` VARCHAR(10) NOT NULL,
    `keypad` INT(11) NOT NULL,
    `switch_1` VARCHAR(3) NOT NULL,
    `switch_2` VARCHAR(3) NOT NULL,
    `switch_3` VARCHAR(3) NOT NULL,
    `switch_4` VARCHAR(3) NOT NULL,
    `message_timestamp` timestamp NOT NULL DEFAULT
    current_timestamp() ON UPDATE current_timestamp()

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='CURRENT_TIMESTAMP';
