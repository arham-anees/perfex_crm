<?php
defined('BASEPATH') or exit('No direct script access allowed');

translations_db_up();

function translations_db_up(){
    $CI       = & get_instance();

    /* ADD TABLE TO STORE ALL LANGUAGE FILES IN DB */
    if (!$CI->db->table_exists(TRANSLATIONS_TABLE_NAME)) {
        $CI->db->query('CREATE TABLE `' . TRANSLATIONS_TABLE_NAME.  "` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `language` varchar(64) NOT NULL,
  `index` varchar(127) NOT NULL,
  `value` longtext NOT NULL,
  `new_value` longtext DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `published` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `language` (`language`,`index`,`file_name`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
    }
}