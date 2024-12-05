CREATE TABLE `esgi`.`USERS` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `firstname` VARCHAR(50) NOT NULL , 
    `lastname` VARCHAR(50) NOT NULL , 
    `email` VARCHAR(255) NOT NULL , 
    `country` VARCHAR(50) NOT NULL , 
    `password` VARCHAR(255) NOT NULL , 
RIMARY KEY (`id`)) ENGINE = InnoDB;