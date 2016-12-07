
-- Create email templates table
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `emailKey` varchar(50) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `placeHolders` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_emailkey` (`emailKey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- Create admin users table
CREATE TABLE `admin_users` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(20) NOT NULL,
	`password` VARCHAR(50) NOT NULL,
	`firstName` VARCHAR(20) NULL DEFAULT NULL,
	`lastName` VARCHAR(20) NULL DEFAULT NULL,
	`active` ENUM('y','n') NULL DEFAULT NULL,
	`role` ENUM('super_admin','editor') NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB


--Add column in client user table
ALTER TABLE `client_users` ADD `phone` BIGINT(20) NOT NULL AFTER `fullName`;

--Add column in client user table
ALTER TABLE `client_users` ADD `role` ENUM('primary','normal') NOT NULL AFTER `active`;

--Insert record in email_template table for for sending mail to client user
(2, 'client_username_password', 'Your account on PlanYourContent created', 'Hi, {FULLNAME}\n\nYour username and password is below.\n\n\nUsername : {USERNAME}\n\nPassword : {PASSWORD}\n\nURL : {LOGIN_URL}\n\n\nThanks,\nRegur Team', '{FULLNAME},{USERNAME},{PASSWORD},{LOGIN_URL}');

-- Add column email admin_user table 
ALTER TABLE `admin_users` ADD `email` VARCHAR(100) NOT NULL AFTER `lastName`;

--Insert record in email_template table for sending mail to admin user for order create.
INSERT INTO `email_templates` (`id`, `emailKey`, `subject`, `body`, `placeHolders`) VALUES
(3, 'client_order_create', 'Order Details', 'Hi,\n\nOrder Details\n\n\n{ORDER_DETAILS}\n\n\nThanks,\nRegur Team', '{ORDER_DETAILS}');


-- Add column orderHash client_orders table 
ALTER TABLE `client_orders` ADD `orderHash` varchar(200) NOT NULL AFTER `dateCreated`;

--Insert record in email_template table for sending mail to client user for order update.
INSERT INTO `email_templates` (`id`, `emailKey`, `subject`, `body`, `placeHolders`) VALUES
('', 'update_order_by_admin', 'Order Details', 'Hi, {FULLNAME}\r\n\r\nOrder Details\r\n\r\n\r\n{ORDER_DETAILS}\r\n\r\n\r\nThanks,\r\nRegur Team', '{FULLNAME},{ORDER_DETAILS}');


--Add column in content_types table
ALTER TABLE `content_types` ADD `highlightColor` VARCHAR(10) NOT NULL AFTER `typeName`;

-- Create contents table
CREATE TABLE IF NOT EXISTS `contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publishingDateId` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `contentTypeId` int(10) unsigned NOT NULL,
  `keywords` text NOT NULL,
  `contents` varchar(255) NOT NULL,
  `imageUrl` text NOT NULL,
  `hashTags` text NOT NULL,
  `links` text NOT NULL,
  `source` text NOT NULL,
  `status` enum('draft','final','published') NOT NULL,
  `publishedUrl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--Add column dateCreated in contents table
ALTER TABLE `contents` ADD `dateCreated` DATETIME NULL ;


--Add column in client orders table
ALTER TABLE `client_orders` ADD `repeatTimeUnit` ENUM('daily','weekly','monthly') NOT NULL AFTER `dateCreated`;
ALTER TABLE `client_orders` ADD `startDate` date DEFAULT NOT NULL AFTER `repeatTimeUnit`;
ALTER TABLE `client_orders` ADD `repeatFrequency` tinyint(2) NOT NULL AFTER `startDate`;
ALTER TABLE `client_orders` ADD `repeatDay` varchar(50) DEFAULT 'NULL' AFTER `repeatFrequency`;



CREATE TABLE `publishing_dates` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`orderId` INT(10) UNSIGNED NOT NULL COMMENT 'refers to client_orders table',
	`contentPublishingDate` DATE NULL DEFAULT NULL COMMENT 'dates generated based on order schedule',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `idx_order_pubdate` (`orderId`, `contentPublishingDate`)
)
COMMENT='List all publishing dates of the editorial calendar as per the client order'
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


--Add column contentHash in contents table
ALTER TABLE `contents` ADD `contentHash` VARCHAR(200) NOT NULL AFTER `publishedUrl`;

--Insert records in email_templates tables
INSERT INTO `editorialcal`.`email_templates` (`id`, `emailKey`, `subject`, `body`, `placeHolders`) VALUES (NULL, 'updated_content', 'Updated Content Details', 'Hi, {FULLNAME}

Content Details


{CONTENT_DETAILS}


Thanks,
Regur Team', '{FULLNAME},{CONTENT_DETAILS}');

INSERT INTO `editorialcal`.`email_templates` (`id`, `emailKey`, `subject`, `body`, `placeHolders`) VALUES (NULL, 'new_content', 'New Content Details', 'Hi, {FULLNAME}

Content Details


{CONTENT_DETAILS}


Thanks,
Regur Team', '{FULLNAME},{CONTENT_DETAILS}');


--Change datatype of content from varchar to text in contents table.
ALTER TABLE `contents` CHANGE `content` `content` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

--Add column adminUserId in client_order table
ALTER TABLE `client_orders` ADD `adminUserId` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `clientUserId`;


