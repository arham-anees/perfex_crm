<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_103 extends App_module_migration
{
	public function up()
	{        
        $CI = &get_instance();  

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
	}
}


