<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('order_number', 1);
add_option('status_sync', 0);

if (!$CI->db->table_exists(db_prefix() . 'affiliate_users')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_users` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `firstname` VARCHAR(255) NOT NULL,
	  `lastname` VARCHAR(255) NOT NULL,
	  `email` VARCHAR(255) NOT NULL,
	  `username` VARCHAR(255) NOT NULL,
	  `password` VARCHAR(255) NOT NULL,
	  `phone` VARCHAR(255) NOT NULL,
	  `country` VARCHAR(255) NULL,
	  `vendor_status` VARCHAR(255) NOT NULL,
	  `under_affiliate` INT(11) NULL,
	  `group` INT(11) NULL,
	  `status` INT(11) NOT NULL DEFAULT 1,
	  `datecreated` DATETIME NULL,
	  `addedfrom` INT(11) NULL,
	  `affiliate_code` VARCHAR(255) NOT NULL,
	  `approval` INT(11) NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_user_groups')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_user_groups` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `name` VARCHAR(255) NOT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_admins')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_admins` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `staffid` INT(11) NOT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_program_categorys')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_program_categorys` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `name` VARCHAR(255) NOT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_transactions')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_transactions` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `member_id` INT(11) NOT NULL,
	  `order_id` INT(11) NULL,
	  `amount` DOUBLE NOT NULL,
	  `comment` VARCHAR(255) NOT NULL,
	  `type` VARCHAR(255) NOT NULL,
	  `status` INT(11) NOT NULL DEFAULT 0,
	  `datecreated` DATETIME NULL,
	  `addedfrom` INT(11) NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_programs')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_programs` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `name` VARCHAR(255) NOT NULL,
	  `from_date` DATE NOT NULL,
	  `to_date` DATE NOT NULL,
	  `priority` INT(11) NULL,
	  `datecreated` DATETIME NULL,
	  `addedfrom` INT(11) NULL,
	  `enable_discount` VARCHAR(45) NULL,
	  `discount_enable_customer` VARCHAR(45) NULL,
	  `discount_enable_product` VARCHAR(45) NULL,
	  `discount_enable_member` VARCHAR(45) NULL,
	  `discount_customer_groups` VARCHAR(255) NULL,
	  `discount_customers` VARCHAR(255) NULL,
	  `discount_product_groups` VARCHAR(255) NULL,
	  `discount_products` VARCHAR(255) NULL,
	  `discount_member_groups` VARCHAR(255) NULL,
	  `discount_members` VARCHAR(255) NULL,
	  `discount_amount_to_calculate` VARCHAR(45) NULL,
	  `discount_type` VARCHAR(45) NULL,
	  `discount_policy_type` VARCHAR(45) NULL,
	  `discount_first_invoices` VARCHAR(45) NULL,
	  `discount_number_first_invoices` INT(11) NULL,
	  `discount_percent_first_invoices` VARCHAR(45) NULL,
	  `discount_ladder_product_setting` TEXT NULL,
	  `discount_product_setting` TEXT NULL,
	  `discount_ladder_setting` TEXT NULL,
	  `discount_percent_enjoyed` VARCHAR(45) NULL,
	  `enable_commission` VARCHAR(45) NULL,
	  `commission_enable_customer` VARCHAR(45) NULL,
	  `commission_enable_product` VARCHAR(45) NULL,
	  `commission_enable_member` VARCHAR(45) NULL,
	  `commission_affiliate_type` VARCHAR(45) NULL,
	  `commission_customer_groups` VARCHAR(255) NULL,
	  `commission_customers` VARCHAR(255) NULL,
	  `commission_product_groups` VARCHAR(255) NULL,
	  `commission_products` VARCHAR(255) NULL,
	  `commission_member_groups` VARCHAR(255) NULL,
	  `commission_members` VARCHAR(255) NULL,
	  `commission_type` VARCHAR(45) NULL,
	  `commission_amount_to_calculate` VARCHAR(45) NULL,
	  `commission_policy_type` VARCHAR(45) NULL,
	  `commission_ladder_setting` TEXT NULL,
	  `commission_product_setting` TEXT NULL,
	  `commission_percent_enjoyed` VARCHAR(45) NULL,
	  `commission_first_invoices` INT(11) NULL,
	  `commission_number_first_invoices` INT(11) NULL,
	  `commission_percent_first_invoices` VARCHAR(45) NULL,
	  `commission_ladder_product_setting` TEXT NULL,
	  `commission_number_view` INT(11) NULL,
	  `commission_of_view` VARCHAR(45) NULL,
	  `commission_number_registration` INT(11) NULL,
	  `commission_of_registration` VARCHAR(45) NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_logs')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_logs` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `program_id` INT(11) NULL,
	  `member_id` INT(11) NULL,
	  `type` VARCHAR(45) NULL,
	  `user_ip` VARCHAR(255) NULL,
	  `link` VARCHAR(555) NULL,
	  `datecreated` DATETIME NULL,
	  `description` TEXT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_withdraws')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_withdraws` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `datecreated` DATETIME NULL,
	  `status` INT(11) NULL,
	  `total` DECIMAL(15,2) NULL,
	  `member_id` INT(11) NULL,
	  `paymentmode` INT(11) NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_withdraw_details')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_withdraw_details` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `withdraw_id` INT(11) NOT NULL,
	  `transaction_id` INT(11) NOT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('affiliate_code', db_prefix() . 'clients')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "clients`
    ADD COLUMN `affiliate_code` VARCHAR(255)
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_orders')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_orders` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`customer` INT(11) NOT NULL,
		`order_code` VARCHAR(255) NULL,
		`datecreated` DATETIME NULL,
		`subtotal` DECIMAL(15,2) NULL,
		`total_tax` DECIMAL(15,2) NULL,
		`total` DECIMAL(15,2) NULL,
		`addedfrom` INT(11) NULL,
		`hash` VARCHAR(32) NULL,
		`status` INT(11) NULL,
		`note` TEXT NULL,
		`approve_status` INT(11) NULL,
		`invoice_id` INT(11) NULL,
		`member_id` INT(11) NULL,
		`billing_street` VARCHAR(200) NULL,
		`billing_city` VARCHAR(45) NULL,
		`billing_state` VARCHAR(100) NULL DEFAULT NULL,
		`billing_zip` VARCHAR(100) NULL DEFAULT NULL,
		`billing_country` INT(11) NULL DEFAULT NULL,
		`shipping_street` VARCHAR(200) NULL DEFAULT NULL,
		`shipping_city` VARCHAR(100) NULL DEFAULT NULL,
		`shipping_state` VARCHAR(100) NULL DEFAULT NULL,
		`shipping_zip` VARCHAR(100) NULL DEFAULT NULL,
		`shipping_country` INT(11) NULL DEFAULT NULL,
		`include_shipping` TINYINT(1) NULL DEFAULT NULL,
		`show_shipping_on_invoice` TINYINT(1) NOT NULL DEFAULT '1',
		`show_quantity_as` INT(11) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_order_items')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'affiliate_order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `description` MEDIUMTEXT NOT NULL,
  `long_description` MEDIUMTEXT NULL DEFAULT NULL,
  `qty` DECIMAL(15,2) NOT NULL,
  `rate` DECIMAL(15,2) NOT NULL,
  `unit` VARCHAR(40) NULL DEFAULT NULL,
  `item_order` INT(11) NULL DEFAULT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_order_item_taxs')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'affiliate_order_item_taxs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_item_id` INT(11) NOT NULL,
  `taxrate` DECIMAL(15,2) NOT NULL,
  `taxname` VARCHAR(100) NOT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}
if (!$CI->db->field_exists('order_id', db_prefix() . 'affiliate_order_item_taxs')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_order_item_taxs`
    ADD COLUMN `order_id` INT(11)
  ;");
}
if (!$CI->db->field_exists('affiliate_member_id', db_prefix() . 'invoices')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "invoices`
    ADD COLUMN `affiliate_member_id` INT(11)
  ;");
}

if (!$CI->db->field_exists('category', db_prefix() . 'affiliate_programs')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_programs`
    ADD COLUMN `category` INT(11)
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_admin_permissions')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_admin_permissions` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `staff_id` INT(11) NOT NULL,
	  `feature` VARCHAR(255) NOT NULL,
	  `capability` VARCHAR(45) NOT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('profile_image', db_prefix() . 'affiliate_users')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_users`
    ADD COLUMN `profile_image` VARCHAR(255)
  ;");
}

if (!$CI->db->field_exists('referral_code', db_prefix() . 'affiliate_users')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_users`
    ADD COLUMN `referral_code` VARCHAR(255)
  ;");
}

if (!$CI->db->field_exists('invoice_id', db_prefix() . 'affiliate_transactions')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_transactions`
    ADD COLUMN `invoice_id` INT(11)
  ;");
}

if (!$CI->db->field_exists('affiliate_program_id', db_prefix() . 'affiliate_transactions')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_transactions`
    ADD COLUMN `affiliate_program_id` INT(11)
  ;");
}

if (!$CI->db->field_exists('payment_mode', db_prefix() . 'affiliate_orders')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_orders`
    ADD COLUMN `payment_mode` INT(11),
    ADD COLUMN `channel` VARCHAR(255)
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_user_products')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_user_products` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	`member_id` INT(11) NOT NULL,
  	`product_id` INT(11) NOT NULL,
	  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('reason', db_prefix() . 'affiliate_orders')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "affiliate_orders`
    ADD COLUMN `reason` VARCHAR(255) NULL,
	ADD COLUMN `admin_action` INT(11) NOT NULL DEFAULT 0,
	CHANGE COLUMN `status` `status` INT(11) NOT NULL DEFAULT 0
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_woocommerce_channels')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_woocommerce_channels` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name_channel` TEXT NOT NULL,
      `consumer_key` TEXT NOT NULL,
      `consumer_secret` TEXT NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('url' ,db_prefix() . 'affiliate_woocommerce_channels')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_woocommerce_channels`
      ADD COLUMN `url` TEXT NOT NULL
  ');
}

if (!$CI->db->field_exists('member_id' ,db_prefix() . 'affiliate_woocommerce_channels')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_woocommerce_channels`
      ADD COLUMN `member_id` int(11) NOT NULL,
      ADD COLUMN `datecreated` DATETIME NOT NULL
  ');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_woocommere_products')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_woocommere_products` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `woocommere_channel_id` int(11) NOT NULL,
      `group_product_id` int(11) NOT NULL,
      `product_id` int(11) NOT NULL,
      `member_id` int(11) NOT NULL,
      `datecreated` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('prices' ,db_prefix() . 'affiliate_woocommere_products')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_woocommere_products`
      ADD COLUMN `prices` DECIMAL(15,2)
');}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_setting_woo_store')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "affiliate_setting_woo_store` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `store` int(11) NOT NULL,
      `sync_omni_sales_products` int(11) NOT NULL default 0,
      `time1` int(11) NOT NULL default 50,
      `sync_omni_sales_inventorys` int(11) NOT NULL default 0,
      `time2` int(11) NOT NULL default 50,
      `price_crm_woo` int(11) NOT NULL default 0,
      `time3` int(11) NOT NULL default 50,
      `sync_omni_sales_description` int(11) NOT NULL default 0,
      `time4` int(11) NOT NULL default 50,
      `sync_omni_sales_images` int(11) NOT NULL default 0,
      `time5` int(11) NOT NULL default 50,
      `sync_omni_sales_orders` int(11) NOT NULL default 0,
      `time6` int(11) NOT NULL default 50,
      `datecreator` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('member_id' ,db_prefix() . 'affiliate_setting_woo_store')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_setting_woo_store`
      ADD COLUMN `member_id` INT(11) NULL
');}

if (!$CI->db->field_exists('records_time1' ,db_prefix() . 'affiliate_setting_woo_store')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_setting_woo_store`
      ADD COLUMN `records_time1` varchar(45) NULL,
      ADD COLUMN `records_time2` varchar(45) NULL,
      ADD COLUMN `records_time3` varchar(45) NULL,
      ADD COLUMN `records_time4` varchar(45) NULL,
      ADD COLUMN `records_time5` varchar(45) NULL,
      ADD COLUMN `records_time6` varchar(45) NULL
');}
if (!$CI->db->field_exists('channel_id' ,db_prefix() . 'affiliate_orders')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_orders`
      ADD COLUMN `channel_id` INT(11) NULL
');}

if (!$CI->db->field_exists('shipping' ,db_prefix() . 'affiliate_orders')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_orders`
      ADD COLUMN `shipping` DECIMAL(15,2) not null default "0.00",                
      ADD COLUMN `payment_method_title` varchar(250) null                
  ');
}

if (!$CI->db->field_exists('allowed_payment_modes' ,db_prefix() . 'affiliate_orders')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'affiliate_orders`
      ADD COLUMN `allowed_payment_modes` varchar(200) null
  ');
}

add_option('affiliate_minimum_inventory', 0);
add_option('affiliate_maximum_inventory', 100);

if (!$CI->db->table_exists(db_prefix() . 'affiliate_log_sync_woo')) {
	$CI->db->query('CREATE TABLE ' . db_prefix() . 'affiliate_log_sync_woo (
	  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	  `name` varchar(250) NOT NULL,
	  `regular_price` int(11) NOT NULL,
	  `sale_price` int(11) NOT NULL,
	  `date_on_sale_from` date NULL,
	  `date_on_sale_to` date NULL,
	  `short_description` TEXT NULL,
	  `stock_quantity` int(11) NULL,
	  `sku` TEXT NOT NULL,
	  `type` varchar(225) NOT NULL,
	  `date_sync` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `stock_quantity_history` int(11) NOT NULL DEFAULT 0,
	  `order_id` int(11) NOT NULL,
	  `chanel` varchar(250) NOT NULL DEFAULT "",
	  `company` varchar(250) NOT NULL DEFAULT "",
	  `description` TEXT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'affiliate_trade_discount')) {
  $CI->db->query('CREATE TABLE ' . db_prefix() . 'affiliate_trade_discount (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name_trade_discount` varchar(250) NOT NULL,
    `start_time` date NOT NULL,
    `end_time` date NOT NULL,      
    `group_clients` TEXT NOT NULL,
    `clients` TEXT NOT NULL,
    `group_items` TEXT NOT NULL,
    `items` TEXT NOT NULL,
    `formal` int(11) NOT NULL,
    `discount` int(11) NOT NULL,
    `voucher` TEXT NULL,
    `channel` int(11) NOT NULL DEFAULT 0,
    `store` varchar(11) NOT NULL DEFAULT "",
    `minimum_order_value` DECIMAL(15,2) NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}
