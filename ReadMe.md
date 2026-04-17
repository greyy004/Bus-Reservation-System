CREATE DATABASE busease;

USE busease;

CREATE TABLE `user` (
    `user_id` INT(255) NOT NULL AUTO_INCREMENT,
    `fullname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `usertype` VARCHAR(255) NOT NULL DEFAULT 'user',
    PRIMARY KEY (`user_id`)
);



CREATE TABLE `bus_info` (
    `bus_id` INT(255) NOT NULL AUTO_INCREMENT,
    `pickup` VARCHAR(255) NOT NULL,
    `destination` VARCHAR(255) NOT NULL,
    `date` DATETIME(6) NOT NULL,
    `price` INT(255) NOT NULL,
    PRIMARY KEY (`bus_id`)
);


CREATE TABLE `ticket` (
    `id` INT(255) NOT NULL AUTO_INCREMENT,
    `bus_id` INT(255) NOT NULL,
    `seat` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `price` INT(255) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY(`bus_id`) REFERENCES bus_info(`bus_id`)
);
