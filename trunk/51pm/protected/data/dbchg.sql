ALTER TABLE `phoenix`.`Activity` MODIFY COLUMN `userId` VARCHAR(100) NOT NULL;
ALTER TABLE `phoenix`.`Company` MODIFY COLUMN `createdBy` VARCHAR(100);
ALTER TABLE `phoenix`.`Company` MODIFY COLUMN `modifiedBy` VARCHAR(100);

ALTER TABLE `phoenix`.`Project` MODIFY COLUMN `createdBy` VARCHAR(100);
ALTER TABLE `phoenix`.`Project` MODIFY COLUMN `modifiedBy` VARCHAR(100);

ALTER TABLE `phoenix`.`User` MODIFY COLUMN `createdBy` VARCHAR(100);
ALTER TABLE `phoenix`.`User` MODIFY COLUMN `modifiedBy` VARCHAR(100);


ALTER TABLE `phoenix`.`Ticket` MODIFY COLUMN `createdBy` VARCHAR(100);
ALTER TABLE `phoenix`.`Ticket` MODIFY COLUMN `modifiedBy` VARCHAR(100);

ALTER TABLE `phoenix`.`Attachment` MODIFY COLUMN `createdBy` VARCHAR(100);
ALTER TABLE `phoenix`.`Attachment` MODIFY COLUMN `modifiedBy` VARCHAR(100);


ALTER TABLE `phoenix`.`Milestone` MODIFY COLUMN `createdBy` VARCHAR(100);
ALTER TABLE `phoenix`.`Milestone` MODIFY COLUMN `modifiedBy` VARCHAR(100);

ALTER TABLE `phoenix`.`ShortMsg` MODIFY COLUMN `createdBy` VARCHAR(100);


alter table Project add column	dfltAssigned varchar(100),
add column		dlftMilestone integer default 0,
add column		ticketStatuses text,
add column		openStates text,
add column		closeStates text;


alter table User add column	uuid varchar(50);
alter table User add column	active tinyint(1) default 0;
