<?php
defined('BASEPATH') or exit('No direct script access allowed');

translations_db_migration_down();

function translations_db_migration_down(){
    $CI       = & get_instance();

    /* ADD TABLE TO SAVE PROJECT - TASK - EMPLOYEE HOURLY RATE */
    if ($CI->db->table_exists(db_prefix().'translations')) {
        $CI->db->query('DROP TABLE `' . db_prefix().'translations`;');
    }

    delete_option('translations_purchase_code');
}