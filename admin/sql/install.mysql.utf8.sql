DROP TABLE IF EXISTS `#__shop_addresses`;
CREATE TABLE `#__shop_addresses` (
	`address_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`address_line1` VARCHAR(96) DEFAULT NULL,
	`address_line2` VARCHAR(96) DEFAULT NULL,
	`address_city` VARCHAR(96) DEFAULT NULL,
	`address_state` INT(11) UNSIGNED DEFAULT NULL,
	`address_zip` VARCHAR(32) DEFAULT NULL,
	`address_phone` VARCHAR(32) DEFAULT NULL,
	`address_type` ENUM('billing', 'shipping') DEFAULT NULL,
	`customer_id` INT(11) UNSIGNED DEFAULT NULL,
	`id` INT(11) UNSIGNED DEFAULT NULL,
	KEY `customer_idx` (`customer_id`),
	KEY `idx` (`id`),
	PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__shop_carts`;
CREATE TABLE `#__shop_carts` (
	`cart_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cart_session` VARCHAR(200) DEFAULT NULL,
	`cart_status` TINYINT(3) UNSIGNED DEFAULT 0,
	`cart_checkout` DATETIME DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(11) UNSIGNED DEFAULT 0,
	`created` DATETIME DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11) UNSIGNED DEFAULT 0,
	`customer_id` INT(11) UNSIGNED DEFAULT 0,
	`id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	KEY `customer_idx` (`customer_id`),
	KEY `idx` (`id`),
	PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__shop_cart_items`;
CREATE TABLE `#__shop_cart_items` (
	`item_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`item_quantity` MEDIUMINT(6) UNSIGNED NOT NULL DEFAULT 0,
	`item_sku` VARCHAR(40) DEFAULT NULL,
	`item_price` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
	`product_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	`cart_id` INT(11) UNSIGNED DEFAULT NULL,
	KEY `product_idx` (`product_id`),
	KEY `cart_idx` (`cart_id`),
	PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__shop_customers`;
CREATE TABLE `#__shop_customers` (
	`customer_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`customer_first` VARCHAR(64) DEFAULT NULL,
	`customer_last` VARCHAR(64) DEFAULT NULL,
	`customer_session` VARCHAR(200) DEFAULT NULL,
	`created` DATETIME DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11) UNSIGNED DEFAULT 0,
	`params` TEXT DEFAULT NULL,
	`id` INT(11) UNSIGNED NOT NULL,
	KEY `idx` (`id`),
	PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__shop_images`;
CREATE TABLE `#__shop_images` (
	`image_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`image_source` VARCHAR(92) DEFAULT NULL,
	`image_full` VARCHAR(92) DEFAULT NULL,
	`image_thumbnail` VARCHAR(92) DEFAULT NULL,
	`image_alt` VARCHAR(128) DEFAULT NULL,
	`image_title` VARCHAR(128) DEFAULT NULL,
	`ordering` INT(11) UNSIGNED DEFAULT NULL,
	`product_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	KEY `product_idx` (`product_id`),
	PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__shop_options`;
CREATE TABLE `#__shop_options` (
	`option_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`option_key` VARCHAR(32) DEFAULT NULL,
	`option_value` VARCHAR(32) DEFAULT NULL,
	`option_price` DECIMAL(8,2) SIGNED DEFAULT 0.00,
	`option_sku` VARCHAR(8) DEFAULT NULL,
	`product_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	KEY `product_idx` (`product_id`),
	PRIMARY KEY (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__shop_products`;
CREATE TABLE `#__shop_products` (
	`product_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`product_name` VARCHAR(128) DEFAULT NULL,
	`product_alias` VARCHAR(32) DEFAULT NULL,
	`product_sku` VARCHAR(32) DEFAULT NULL,
	`product_description` TEXT DEFAULT NULL,
	`product_price` DECIMAL(8,2) UNSIGNED DEFAULT 0.00,
	`meta_keywords` VARCHAR(256) DEFAULT NULL,
	`meta_description` VARCHAR(256) DEFAULT NULL,
	`ordering` INT(11) unsigned DEFAULT NULL,
	`state` TINYINT(1) unsigned DEFAULT 0,
	`params` TEXT DEFAULT NULL,
	`publish_up` DATETIME DEFAULT '0000-00-00 00:00:00',
	`publish_down` DATETIME DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(11) UNSIGNED DEFAULT 0,
	`created` DATETIME DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11) UNSIGNED DEFAULT 0,
	`access` INT(11) UNSIGNED DEFAULT NULL,
	`catid` INT(11) UNSIGNED DEFAULT NULL,
	KEY `catidx` (`catid`),
	PRIMARY KEY  (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
