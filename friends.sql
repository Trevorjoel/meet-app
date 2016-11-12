CREATE TABLE friends ( 
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user1` VARCHAR(16) NOT NULL,
                `user2` VARCHAR(16) NOT NULL,
                `datemade` DATETIME NOT NULL,
                `accepted` ENUM('0','1') NOT NULL DEFAULT '0',
                PRIMARY KEY (id)
                );
