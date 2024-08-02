<?php

defined('BASEPATH') or exit('No direct script access allowed');
require 'modules/affiliate/third_party/WooCommerce/HttpClient/OAuth.php';
require 'modules/affiliate/third_party/WooCommerce/HttpClient/BasicAuth.php';
require 'modules/affiliate/third_party/WooCommerce/HttpClient/HttpClientException.php';
require 'modules/affiliate/third_party/WooCommerce/HttpClient/HttpClient.php';
require 'modules/affiliate/third_party/WooCommerce/HttpClient/Options.php';
require 'modules/affiliate/third_party/WooCommerce/HttpClient/Request.php';
require 'modules/affiliate/third_party/WooCommerce/HttpClient/Response.php';
require 'modules/affiliate/third_party/WooCommerce/Client.php';
use Affiliate\Automattic\WooCommerce\Client;

class Sync_woo_model extends App_Model
{
    public $amount        = 10;
    public $per_page_tags = 100;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * sync_from_the_system_to_the_store_single
     * @param  $store_id
     * @param  $arr
     * @return
     */
    public function sync_from_the_system_to_the_store_single($member_id, $store_id, $arr = null)
    {
        $this->load->model('affiliate/affiliate_model');
        $this->load->model('affiliate/affiliate_store_model');
        $channel    = $this->affiliate_model->get_woocommerce_channel($store_id);
        $store_name = $channel->name_channel;

        $woocommerce = $this->init_connect_woocommerce($store_id);

        $per_page       = 100;
        $products_store = [];
        for ($page = 1; $page <= 100; $page++) {
            $offset        = ($page - 1) * $per_page;
            $list_products = $woocommerce->get('products', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);

            $products_store = array_merge($products_store, $list_products);

            if (count($list_products) < $per_page) {
                break;
            }
        }
        $taxes_classes = $woocommerce->get('taxes/classes');
        $arr_taxes     = [];
        foreach ($taxes_classes as $taxes) {
            array_push($arr_taxes, $taxes->name);
        }
        $arr_product_store    = [];
        $arr_product_id_store = [];
        foreach ($products_store as $key => $value) {
            if ($value->sku != '') {
                array_push($arr_product_store, $value->sku);
                array_push($arr_product_id_store, $value->id);
            }
        }
        $product_detail = [];

        if (isset($arr)) {
            $products_list = $this->products_list_store_detail($store_id, $arr);
            foreach ($products_list as $key => $product) {
                $product_detail[] = $this->affiliate_store_model->get_product($product[0]['product_id']);
            }
        } else {
            $products_list = $this->products_list_store($store_id);
            foreach ($products_list as $key => $product) {
                $product_detail[] = $this->affiliate_store_model->get_product($product['product_id']);
            }
        }

        $data_cus_update_       = [];
        $data_cus_update_master = [];

        $data_create        = [];
        $data_create_master = [];

        $list_tag = [];
        for ($page = 1; $page <= $this->per_page_tags; $page++) {
            $offset    = ($page - 1) * $per_page;
            $list_tags = $woocommerce->get('products/tags', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);

            $list_tag = array_merge($list_tag, $list_tags);

            if (count($list_tags) < $this->per_page_tags) {
                break;
            }
        }
        $tag_woo_slug = [];
        $tag_woo_id   = [];

        foreach ($list_tag as $tag_w) {
            $tag_woo_slug[] = $tag_w->slug;
            $tag_woo_name[] = $tag_w->name;
            $tag_woo_id[]   = $tag_w->id;
        }

        foreach ($product_detail as $key => $value) {
            if (!is_null($value)) {

                if (!in_array($value->sku_code, $arr_product_store)) {
                    if ($this->affiliate_store_model->get_all_image_file_name($value->id)) {
                        $file_name = $this->affiliate_store_model->get_all_image_file_name($value->id);
                    }

                    $images       = [];
                    $images_final = [];
                    if (isset($file_name)) {
                        foreach ($file_name as $k => $name) {
                            if (file_exists('./modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])) {
                                    array_push($images, array('src' => site_url('modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])));
                            }
                        }
                    }
                    $date     = date('Y-m-d');
                    $discount = $this->affiliate_store_model->check_discount($value->id, $date, 3);

                    $price_discount    = 0;
                    $date_on_sale_from = null;
                    $date_on_sale_to   = null;
                    if (!is_null($discount)) {
                        if ($discount->formal == 1) {
                            $price_discount = $value->rate - (($value->rate * $discount->discount) / 100);
                        } else {
                            $price_discount = $value->rate - $discount->discount;
                        }
                        $date_on_sale_from = $discount->start_time;
                        $date_on_sale_to   = $discount->end_time;
                    } else {
                        $price_discount = "";
                    }
                    $tax_status = 'taxable';
                    $tax_class  = '';
                    $taxname    = '';
                    if ($value->tax != '' && !is_null($value->tax)) {
                        $tax = $this->get_tax($value->tax);
                        if ($tax != '') {
                            $tax_status = 'taxable';
                            $tax->name  = $this->vn_to_str($tax->name);
                            $tax->name  = strtolower($this->clean($tax->name));
                            if (!in_array($tax->name, $arr_taxes)) {
                                $slug_class = $this->create_new_tax_sync($store_id, $tax->name, $tax->taxrate);
                                $data_rates = [
                                    "country"  => "",
                                    "state"    => "",
                                    "postcode" => "",
                                    "city"     => "",
                                    "compound" => false,
                                    "shipping" => false,
                                    'rate'     => $tax->taxrate,
                                    'name'     => $tax->name,
                                    'class'    => $slug_class,
                                ];
                                $woocommerce->post('taxes', $data_rates);
                            } else {
                                $name_tax_finnal = explode(" ", $tax->name);
                                $slug_class      = strtolower(implode("-", $name_tax_finnal));
                            }
                            if ($tax == '') {
                                $taxname = 'zero-rate';
                            } else {
                                if (isset($slug_class)) {
                                    $taxname = $slug_class;
                                } else {
                                    $taxname = 'standard';
                                }
                            }
                            $tax_class = $taxname;
                        }
                    }

                    $stock_quantity   = $this->affiliate_store_model->get_total_inventory_commodity($value->id);
                    $minimum_inventory = get_option('affiliate_minimum_inventory');
                    $maximum_inventory = get_option('affiliate_maximum_inventory');
                    $inventory_number = 0;
                    if(isset($stock_quantity->inventory_number)){
                        if($stock_quantity->inventory_number > $minimum_inventory){
                            $inventory_number = $stock_quantity->inventory_number - $minimum_inventory;
                        }

                        if($inventory_number > $maximum_inventory){
                            $inventory_number = $maximum_inventory;
                        }
                    }
                    $regular_price    = $this->get_price_store($value->id, $store_id);
                    $get_tags_product = $this->get_tags_product($value->id);

                    $tags_id    = [];
                    $tags_name  = [];
                    $tags_final = [];

                    if (count($get_tags_product) > 0) {
                        foreach ($get_tags_product as $get_tags_) {
                            $tags_id[]   = $get_tags_['rel_id'];
                            $tags_name[] = $get_tags_['name'];
                        }
                    }

                    if (count($tags_name) > 0) {
                        $data_tag_ = [];
                        foreach ($tags_name as $key_count => $tags_) {
                            $tags_    = strtolower($tags_);
                            $tags_    = trim($tags_);
                            $tags_    = $this->vn_to_str($tags_);
                            $name_tag = $this->clean($tags_);

                            if (!in_array($name_tag, $tag_woo_slug)) {
                                $data_tag_[] = [
                                    'name' => $name_tag,
                                ];

                            } else {
                                foreach ($tag_woo_slug as $keyss => $valuess_) {
                                    if ($valuess_ == $name_tag) {
                                        $tags_final[] = ['id' => $tag_woo_id[$keyss]];
                                    }
                                }
                            }

                        }
                        foreach ($data_tag_ as $data_1) {
                            if (!in_array($data_1["name"], $tag_woo_name)) {
                                $avbcs          = $woocommerce->post('products/tags', $data_1);
                                $tag_woo_slug[] = $avbcs->slug;
                                $tag_woo_id[]   = $avbcs->id;
                                $tags_final[]   = ['id' => $avbcs->id];
                            }
                        }
                    }

                    $data = [
                        'name'              => $value->description,
                        'type'              => 'simple',
                        'regular_price'     => $value->rate,
                        'sale_price'        => strval($price_discount),
                        'date_on_sale_from' => $date_on_sale_from,
                        'date_on_sale_to'   => $date_on_sale_to,
                        'short_description' => $value->long_description,
                        'stock_quantity'    => $inventory_number,
                        'manage_stock'      => true,
                        'tax_status'        => $tax_status,
                        'tax_class'         => $tax_class,
                        'sku'               => $value->sku_code,
                        'tags'              => $tags_final,

                    ];
                    
                    array_push($data_create, $data);
                    if (count($data_create) == $this->amount) {
                        array_push($data_create_master, $data_create);
                        $data_create = [];
                    }
                    
                } else {

                    $get_tags_product = $this->get_tags_product($value->id);

                    $tags_id    = [];
                    $tags_name  = [];
                    $tags_final = [];

                    if (count($get_tags_product) > 0) {
                        foreach ($get_tags_product as $get_tags_) {
                            $tags_id[]   = $get_tags_['rel_id'];
                            $tags_name[] = $get_tags_['name'];
                        }
                    }if (count($tags_name) > 0) {
                        $data_tag_ = [];
                        foreach ($tags_name as $key_count => $tags_) {
                            $tags_    = strtolower($tags_);
                            $tags_    = trim($tags_);
                            $tags_    = $this->vn_to_str($tags_);
                            $name_tag = $this->clean($tags_);

                            if (!in_array($name_tag, $tag_woo_slug)) {
                                $data_tag_[] = [
                                    'name' => $name_tag,
                                ];

                            } else {
                                foreach ($tag_woo_slug as $keyss => $valuess_) {
                                    if ($valuess_ == $name_tag) {
                                        $tags_final[] = ['id' => $tag_woo_id[$keyss]];
                                    }
                                }
                            }

                        }
                        foreach ($data_tag_ as $data_1) {
                            if (!in_array($data_1["name"], $tag_woo_name)) {
                                $avbcs          = $woocommerce->post('products/tags', $data_1);
                                $tag_woo_slug[] = $avbcs->slug;
                                $tag_woo_id[]   = $avbcs->id;
                                $tags_final[]   = ['id' => $avbcs->id];
                            }
                        }
                    }
                    $index_key = array_search($value->sku_code, $arr_product_store, true);
                    if (count($arr_product_id_store) > 0) {
                        $regular_price        = $this->get_price_store($value->id, $store_id);
                        $regular_price_prices = '';
                        if (!isset($regular_price->prices)) {
                            $regular_price_prices = 0;
                        } else {
                            $regular_price_prices = $regular_price->prices;
                        }
                        if ($this->affiliate_store_model->get_all_image_file_name($value->id)) {
                            $file_name = $this->affiliate_store_model->get_all_image_file_name($value->id);
                        }

                        $images       = [];
                        $images_final = [];
                        if (isset($file_name)) {
                            foreach ($file_name as $k => $name) {
                                if (file_exists('./modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])) {
                                    array_push($images, array('src' => site_url('modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])));
                                }
                            }
                        }
                        $tax_status = 'taxable';
                        $tax_class  = '';
                        $taxname    = '';
                        if ($value->tax != '' && !is_null($value->tax)) {
                            $tax = $this->get_tax($value->tax);
                            if ($tax != '') {
                                $tax_status = 'taxable';
                                $tax->name  = $this->vn_to_str($tax->name);
                                $tax->name  = strtolower($this->clean($tax->name));
                                if (!in_array($tax->name, $arr_taxes)) {
                                    $slug_class = $this->create_new_tax_sync($store_id, $tax->name, $tax->taxrate);
                                    $data_rates = [
                                        "country"  => "",
                                        "state"    => "",
                                        "postcode" => "",
                                        "city"     => "",
                                        "compound" => false,
                                        "shipping" => false,
                                        'rate'     => $tax->taxrate,
                                        'name'     => $tax->name,
                                        'class'    => $slug_class,
                                    ];
                                    $woocommerce->post('taxes', $data_rates);
                                } else {
                                    $name_tax_finnal = explode(" ", $tax->name);
                                    $slug_class      = strtolower(implode("-", $name_tax_finnal));
                                }
                                if ($tax == '') {
                                    $taxname = 'zero-rate';
                                } else {
                                    if (isset($slug_class)) {
                                        $taxname = $slug_class;
                                    } else {
                                        $taxname = 'standard';
                                    }
                                }
                                $tax_class = $taxname;
                            }
                        }
                        $data_cus_update_2 = [
                            'id'                => $arr_product_id_store[$index_key],
                            'tags'              => $tags_final,
                            'name'              => $value->description,
                            'regular_price'     => $value->rate,
                            'tax_status'        => $tax_status,
                            'tax_class'         => $tax_class,
                            'short_description' => $value->long_description,
                        ];
                        array_push($data_cus_update_, $data_cus_update_2);

                        if (count($data_cus_update_) == $this->amount) {

                            array_push($data_cus_update_master, $data_cus_update_);
                            $data_cus_update_ = [];

                        }
                    }
                }
            }
        }
        if (count($arr_product_id_store) > 0) {
            if (count($data_cus_update_) < $this->amount) {
                array_push($data_cus_update_master, $data_cus_update_);
            }

            if ($data_cus_update_) {
                foreach ($data_cus_update_master as $data__s) {
                    $data_cus_ = [
                        'update' => $data__s,
                    ];
                    $woocommerce->post('products/batch', $data_cus_);
                    $this->exit_type_variation($store_id, 2);
                }
            }
        }

        if (count($data_create) < 10) {
            array_push($data_create_master, $data_create);
        }

        if (count($data_create_master) > 0) {
            foreach ($data_create_master as $data__) {
                $data_cus = [
                    'create' => $data__,
                ];

                $woocommerce->post('products/batch', $data_cus);
                $this->exit_type_variation($store_id, 1);
            }
        }

        $log_product = [
            'member_id'   => $member_id,
            'description' => 'Sync product WooCommerce(' . $store_name . ')',
            'datecreated' => date('Y-m-d H:i:s'),
            "type"        => "affiliate_product",
        ];
        $this->db->insert(db_prefix() . 'affiliate_logs', $log_product);
        return true;
    }

    /**
     * process inventory synchronization
     * @param  int $store_id
     * @return bool
     */
    public function process_inventory_synchronization_detail($member_id, $store_id, $arr_detail = null)
    {
        $this->load->model('affiliate_model');
        $this->load->model('affiliate_store_model');
        $store          = $this->affiliate_model->get_woocommerce_channel($store_id);
        $store_name     = $store->name_channel;
        $products_store = $this->affiliate_model->get_woocommere_products($store_id);

        $items = [];
        if (isset($arr_detail)) {
            foreach ($arr_detail as $key => $product) {
                $this->db->where('id', $product);
                array_push($items, $this->db->get(db_prefix() . 'items')->row());
            }
        } else {
            if (!empty($products_store)) {
                if (count($products_store) > 0) {
                    foreach ($products_store as $key => $product) {
                        $this->db->where('id', $product['product_id']);
                        array_push($items, $this->db->get(db_prefix() . 'items')->row());
                    }
                }
            }
        }

        $woocommerce = $this->init_connect_woocommerce($store_id);

        $per_page       = 100;
        $products_store = [];
        for ($page = 1; $page <= 100; $page++) {
            $offset        = ($page - 1) * $per_page;
            $list_products = $woocommerce->get('products', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);

            $products_store = array_merge($products_store, $list_products);

            if (count($list_products) < $per_page) {
                break;
            }
        }
        $data_create        = [];
        $data_create_master = [];

        foreach ($products_store as $key => $value) {

            if ($value->sku != '') {
                foreach ($items as $item) {
                    if ($item->sku_code == $value->sku) {

                        $stock_quantity = $this->affiliate_store_model->get_total_inventory_commodity($item->id);
                        $minimum_inventory = get_option('affiliate_minimum_inventory');
                        $maximum_inventory = get_option('affiliate_maximum_inventory');
                        $inventory_number = 0;
                        if(isset($stock_quantity->inventory_number)){
                            if($stock_quantity->inventory_number > $minimum_inventory){
                                $inventory_number = $stock_quantity->inventory_number - $minimum_inventory;
                            }

                            if($inventory_number > $maximum_inventory){
                                $inventory_number = $maximum_inventory;
                            }
                        }
                        $images         = [];
                        if ($this->affiliate_store_model->get_all_image_file_name($item->id)) {
                            $file_name = $this->affiliate_store_model->get_all_image_file_name($item->id);
                        }
                        if (isset($file_name)) {
                            foreach ($file_name as $k => $name) {
                                if (file_exists('./modules/warehouse/uploads/item_img/' . $item->id . '/' . $name['file_name'])) {
                                    array_push($images, array('src' => site_url('modules/warehouse/uploads/item_img/' . $item->id . '/' . $name['file_name'])));
                                }
                            }
                        }
                        
                        $date              = date('Y-m-d');
                        $discount          = $this->affiliate_store_model->check_discount($item->id, $date, 3, $store_id);
                        $price_discount    = 0;
                        $date_on_sale_from = null;
                        $date_on_sale_to   = null;
                        if (!is_null($discount)) {
                            if ($discount->formal == 1) {
                                $price_discount = $item->rate - (($item->rate * $discount->discount) / 100);
                            } else {
                                $price_discount = $item->rate - $discount->discount;
                            }
                            $date_on_sale_from = $discount->start_time;
                            $date_on_sale_to   = $discount->end_time;
                        } else {
                            $price_discount = "";
                        }
                        $regular_price        = $this->get_price_store($item->id, $store_id);
                        $regular_price_prices = '';
                        if (!isset($regular_price->prices)) {
                            $regular_price_prices = 0;
                        } else {
                            $regular_price_prices = $regular_price->prices;
                        }
                        $data = [
                            'id'             => $value->id,
                            "stock_quantity" => $inventory_number,
                            "manage_stock"   => true,
                        ];
                        if (is_null($value->stock_quantity)) {
                            $value->stock_quantity = 0;
                        }
                        
                        array_push($data_create, $data);
                        if (count($data_create) == $this->amount) {
                            array_push($data_create_master, $data_create);
                            $data_create = [];
                        }
                        
                    }
                }
            }
        }
        if (count($data_create) < $this->amount) {
            array_push($data_create_master, $data_create);
        }
        if ($data_create_master > 0) {
            foreach ($data_create_master as $data__) {
                $data_cus = [
                    'update' => $data__,
                ];
                $woocommerce->post('products/batch', $data_cus);
            }
            $this->exit_type_variation($store_id, 2);
        }
        $log_inventory = [
            'member_id'   => $member_id,
            'description' => 'Sync inventory WooCommerce(' . $store_name . ')',
            'datecreated' => date('Y-m-d H:i:s'),
            "type"        => "inventory",
        ];
        $this->db->insert(db_prefix() . 'affiliate_logs', $log_inventory);
        return true;
    }

    /**
     * process price synchronization
     * @param  int $store_id
     * @param  array $arr_detail
     * @return bool
     */
    public function process_price_synchronization($member_id, $store_id, $arr_detail = null)
    {
        $this->load->model('affiliate_model');
        $this->load->model('affiliate_store_model');
        $products_store = $this->affiliate_model->get_woocommere_products($store_id);
        $store          = $this->affiliate_model->get_woocommerce_channel($store_id);
        $store_name     = $store->name_channel;
        $items          = [];

        if (isset($arr_detail)) {
            foreach ($arr_detail as $key => $product) {
                $this->db->where('id', $product);
                array_push($items, $this->db->get(db_prefix() . 'items')->row());
            }
        } else {
            if (!empty($products_store)) {
                foreach ($products_store as $key => $product) {
                    if (!is_null($this->affiliate_store_model->get_product($product['product_id']))) {
                        $this->db->where('id', $product['product_id']);
                        array_push($items, $this->db->get(db_prefix() . 'items')->row());
                    }
                }
            }
        }

        $woocommerce = $this->init_connect_woocommerce($store_id);

        $per_page       = 100;
        $products_store = [];
        for ($page = 1; $page <= 100; $page++) {
            $offset        = ($page - 1) * $per_page;
            $list_products = $woocommerce->get('products', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);

            $products_store = array_merge($products_store, $list_products);

            if (count($list_products) < $per_page) {
                break;
            }
        }
        $data_create        = [];
        $data_create_master = [];


        foreach ($products_store as $key => $value) {
            if ($value->sku != '') {
                foreach ($items as $item) {
                    if ($item->sku_code == $value->sku) {
                        
                        $date              = date('Y-m-d');
                        $discount          = $this->affiliate_store_model->check_discount($item->id, $date, 3, $store_id);
                        $price_discount    = 0;
                        $date_on_sale_from = null;
                        $date_on_sale_to   = null;
                        if (!is_null($discount)) {
                            if ($discount->formal == 1) {
                                $price_discount = $item->rate - (($item->rate * $discount->discount) / 100);
                            } else {
                                $price_discount = $item->rate - $discount->discount;
                            }
                            $date_on_sale_from = $discount->start_time;
                            $date_on_sale_to   = $discount->end_time;
                        } else {
                            $price_discount = "";
                        }
                        $regular_price = $this->get_price_store($item->id, $store_id);

                        $regular_price_prices = '';
                        if (!isset($regular_price->prices)) {
                            $regular_price_prices = 0;
                        } else {
                            $regular_price_prices = $regular_price->prices;
                        }
                        $data = [
                            'id'                => $value->id,
                            'name'              => $item->description,
                            'regular_price'     => $regular_price_prices,
                            'price'             => $regular_price_prices,
                            'sale_price'        => strval($price_discount),
                            'date_on_sale_from' => $date_on_sale_from,
                            'date_on_sale_to'   => $date_on_sale_to,
                        ];
                        
                        array_push($data_create, $data);
                        if (count($data_create) == $this->amount) {
                            array_push($data_create_master, $data_create);
                            $data_create = [];
                        }
                        
                    }
                }
            }
        }
        if (count($data_create) < 10) {
            array_push($data_create_master, $data_create);
        }

        if ($data_create_master > 0) {
            foreach ($data_create_master as $data__) {
                $data_cus = [
                    'update' => $data__,
                ];
                $woocommerce->post('products/batch', $data_cus);
                $this->exit_type_variation($store_id, 2);
            }
        }

        $log_price = [
            'member_id'   => $member_id,
            'description' => 'Sync price WooCommerce(' . $store_name . ')',
            'datecreated' => date('Y-m-d H:i:s'),
            "type"        => "price",
        ];
        $this->db->insert(db_prefix() . 'affiliate_logs', $log_price);

        return true;
    }

    /**
     * process decriptions synchronization
     * @param $store_id
     * @return
     */
    public function process_decriptions_synchronization_detail($member_id, $store_id, $arr_detail = null)
    {
        $this->load->model('affiliate_model');
        $this->load->model('affiliate_store_model');
        $store          = $this->affiliate_model->get_woocommerce_channel($store_id);
        $store_name     = $store->name_channel;
        $items          = [];
        $products_store = $this->affiliate_model->get_woocommere_products($store_id);

        if (isset($arr_detail)) {
            foreach ($arr_detail as $key => $product) {
                $this->db->where('id', $product);
                array_push($items, $this->db->get(db_prefix() . 'items')->row());
            }
        } else {
            if (!empty($products_store)) {
                if (count($products_store) > 0) {
                    foreach ($products_store as $key => $product) {
                        $this->db->where('id', $product['product_id']);
                        array_push($items, $this->db->get(db_prefix() . 'items')->row());
                    }
                }
            }
        }

        $woocommerce = $this->init_connect_woocommerce($store_id);

        $per_page       = 100;
        $products_store = [];
        for ($page = 1; $page <= 100; $page++) {
            $offset        = ($page - 1) * $per_page;
            $list_products = $woocommerce->get('products', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);

            $products_store = array_merge($products_store, $list_products);

            if (count($list_products) < $per_page) {
                break;
            }
        }
        $arr_product_store  = [];
        $data_create        = [];
        $data_create_master = [];
        foreach ($products_store as $key => $value) {

            if ($value->sku != '') {
                foreach ($items as $item) {
                    if ($item->sku_code == $value->sku) {
                        $data = [
                            'id'          => $value->id,
                            'description' => $item->long_descriptions,
                        ];

                        if (is_null($value->stock_quantity)) {
                            $value->stock_quantity = 0;
                        }
                        array_push($data_create, $data);
                        if (count($data_create) == $this->amount) {
                            array_push($data_create_master, $data_create);
                            $data_create = [];
                        }
                    }
                }
            }
        }
        if (count($data_create) < 10) {
            array_push($data_create_master, $data_create);
        }

        if (count($data_create_master) > 0) {
            foreach ($data_create_master as $data__) {
                $data_cus = [
                    'update' => $data__,
                ];
                $woocommerce->post('products/batch', $data_cus);
            }
            $this->exit_type_variation($store_id, 2);
        }

        $log_description = [
            'member_id'   => $member_id,
            'description' => 'Sync description WooCommerce(' . $store_name . ')',
            'datecreated' => date('Y-m-d H:i:s'),
            "type"        => "description",
        ];
        $this->db->insert(db_prefix() . 'affiliate_logs', $log_description);

        return true;
    }

    /**
     * sync all
     * @param  $store_id
     * @param  $arr
     * @return
     */
    public function sync_all($member_id, $store_id, $arr = null)
    {

        $this->load->model('affiliate_model');
        $this->load->model('affiliate_store_model');
        $store       = $this->affiliate_model->get_woocommerce_channel($store_id);
        $store_name  = $store->name_channel;
        $woocommerce = $this->init_connect_woocommerce($store_id);

        //get all products have variation include ids and sku_codes
        $products_variation = $this->get_item_have_variation();

        $per_page       = 100;
        $products_store = [];
        for ($page = 1; $page <= 100; $page++) {
            $offset        = ($page - 1) * $per_page;
            $list_products = $woocommerce->get('products', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);

            $products_store = array_merge($products_store, $list_products);

            if (count($list_products) < $per_page) {
                break;
            }
        }

        $taxes_classes = $woocommerce->get('taxes/classes');
        $arr_taxes     = [];
        foreach ($taxes_classes as $taxes) {
            array_push($arr_taxes, $taxes->name);
        }
        $arr_product_store    = [];
        $arr_product_id_store = [];
        foreach ($products_store as $key => $value) {
            if ($value->sku != '') {
                array_push($arr_product_store, $value->sku);
                array_push($arr_product_id_store, $value->id);
            }
        }

        $product_detail = [];

        if (isset($arr)) {
            $products_list = $this->products_list_store_detail($store_id, $arr);
            foreach ($products_list as $key => $product) {
                $this->db->where('id', $product[0]['product_id']);
                $product_detail[] = $this->db->get(db_prefix() . 'items')->row();
            }
        } else {
            $products_list = $this->products_list_store($store_id);
            foreach ($products_list as $key => $product) {
                $this->db->where('id', $product['product_id']);
                $product_detail[] = $this->db->get(db_prefix() . 'items')->row();
            }
        }

        $data_cus_update_       = [];
        $data_cus_update_master = [];

        $data_create        = [];
        $data_create_master = [];

        $list_tag = [];

        for ($page = 1; $page <= $this->per_page_tags; $page++) {
            $offset    = ($page - 1) * $per_page;
            $list_tags = $woocommerce->get('products/tags', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);
            $list_tag  = array_merge($list_tag, $list_tags);
            if (count($list_tags) < $this->per_page_tags) {
                break;
            }
        }

        $tag_woo_slug = [];
        $tag_woo_id   = [];

        foreach ($list_tag as $tag_w) {
            $tag_woo_slug[] = $tag_w->slug;
            $tag_woo_name[] = $tag_w->name;
            $tag_woo_id[]   = $tag_w->id;
        }

        foreach ($product_detail as $key => $value) {
        
            if (!is_null($value)) {

                if (!in_array($value->sku_code, $arr_product_store)) {

                    if ($this->affiliate_store_model->get_all_image_file_name($value->id)) {
                        $file_name = $this->affiliate_store_model->get_all_image_file_name($value->id);
                    }

                    $images       = [];
                    $images_final = [];
                    if (isset($file_name)) {
                        foreach ($file_name as $k => $name) {
                            if (file_exists('./modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])) {
                                array_push($images, array('src' => site_url('modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])));
                            }
                        }
                    }

                    $date     = date('Y-m-d');
                    $discount = $this->affiliate_store_model->check_discount($value->id, $date, 3);

                    $price_discount    = 0;
                    $date_on_sale_from = null;
                    $date_on_sale_to   = null;
                    if (!is_null($discount)) {
                        if ($discount->formal == 1) {
                            $price_discount = $value->rate - (($value->rate * $discount->discount) / 100);
                        } else {
                            $price_discount = $value->rate - $discount->discount;
                        }
                        $date_on_sale_from = $discount->start_time;
                        $date_on_sale_to   = $discount->end_time;
                    } else {
                        $price_discount = "";
                    }
                    $tax_status = 'taxable';
                    $tax_class  = '';
                    $taxname    = '';
                    if ($value->tax != '' && !is_null($value->tax)) {
                        $tax = $this->get_tax($value->tax);
                        if ($tax != '') {
                            $tax_status = 'taxable';
                            $tax->name  = $this->vn_to_str($tax->name);
                            $tax->name  = strtolower($this->clean($tax->name));
                            if (!in_array($tax->name, $arr_taxes)) {
                                $slug_class = $this->create_new_tax_sync($store_id, $tax->name, $tax->taxrate);
                                $data_rates = [
                                    "country"  => "",
                                    "state"    => "",
                                    "postcode" => "",
                                    "city"     => "",
                                    "compound" => false,
                                    "shipping" => false,
                                    'rate'     => $tax->taxrate,
                                    'name'     => $tax->name,
                                    'class'    => $slug_class,
                                ];
                                $woocommerce->post('taxes', $data_rates);
                            } else {
                                $name_tax_finnal = explode(" ", $tax->name);
                                $slug_class      = strtolower(implode("-", $name_tax_finnal));
                            }
                            if ($tax == '') {
                                $taxname = 'zero-rate';
                            } else {
                                if (isset($slug_class)) {
                                    $taxname = $slug_class;
                                } else {
                                    $taxname = 'standard';
                                }
                            }
                            $tax_class = $taxname;
                        }
                    }

                    $stock_quantity   = $this->affiliate_store_model->get_total_inventory_commodity($value->id);
                    $minimum_inventory = get_option('affiliate_minimum_inventory');
                    $maximum_inventory = get_option('affiliate_maximum_inventory');
                    $inventory_number = 0;
                    if(isset($stock_quantity->inventory_number)){
                        if($stock_quantity->inventory_number > $minimum_inventory){
                            $inventory_number = $stock_quantity->inventory_number - $minimum_inventory;
                        }

                        if($inventory_number > $maximum_inventory){
                            $inventory_number = $maximum_inventory;
                        }
                    }
                    $get_tags_product = $this->get_tags_product($value->id);

                    $tags_id    = [];
                    $tags_name  = [];
                    $tags_final = [];

                    if (count($get_tags_product) > 0) {
                        foreach ($get_tags_product as $get_tags_) {
                            $tags_id[]   = $get_tags_['rel_id'];
                            $tags_name[] = $get_tags_['name'];
                        }
                    }

                    if (count($tags_name) > 0) {
                        $data_tag_ = [];
                        foreach ($tags_name as $key_count => $tags_) {
                            $tags_    = strtolower($tags_);
                            $tags_    = trim($tags_);
                            $tags_    = $this->vn_to_str($tags_);
                            $name_tag = $this->clean($tags_);

                            if (!in_array($name_tag, $tag_woo_slug)) {
                                $data_tag_[] = [
                                    'name' => $name_tag,
                                ];

                            } else {
                                foreach ($tag_woo_slug as $keyss => $valuess_) {
                                    if ($valuess_ == $name_tag) {
                                        $tags_final[] = ['id' => $tag_woo_id[$keyss]];
                                    }
                                }
                            }

                        }
                        foreach ($data_tag_ as $data_1) {
                            if (!in_array($data_1["name"], $tag_woo_name)) {
                                $avbcs          = $woocommerce->post('products/tags', $data_1);
                                $tag_woo_slug[] = $avbcs->slug;
                                $tag_woo_id[]   = $avbcs->id;
                                $tags_final[]   = ['id' => $avbcs->id];
                            }
                        }
                    }

                    $regular_price        = $this->get_price_store($value->id, $store_id);
                    $regular_price_prices = '';
                    if (!isset($regular_price->prices)) {
                        $regular_price_prices = 0;
                    } else {
                        $regular_price_prices = $regular_price->prices;
                    }

                    $type       = 'simple';
                    $attributes = [];

                    if (in_array($value->sku_code, $products_variation['sku_code_s'])) {
                        $type = 'variable';
                        //get id by index of array $products_variation['sku_code_s']
                        $index = array_search($value->sku_code, $products_variation['sku_code_s'], true);

                        $this->db->where('id', $products_variation['ids'][$index]);

                        $item_attributes = $this->db->get(db_prefix() . 'items')->row();

                        if ($item_attributes->parent_attributes != null || $item_attributes->parent_attributes != '') {
                            $parent_attributes = json_decode($item_attributes->parent_attributes);
                        } else {
                            $parent_attributes = [];
                        }

                        $products_attributes = $woocommerce->get('products/attributes');
                        //get name attr
                        $slug_attributes = [];
                        $name_attributes = [];
                        $id_attributes   = [];

                        if (count($products_attributes) > 0) {
                            foreach ($products_attributes as $key_products_attributes => $value_products_attributes) {
                                array_push($slug_attributes, $value_products_attributes->slug);
                                array_push($name_attributes, $value_products_attributes->name);
                                array_push($id_attributes, $value_products_attributes->id);
                            }
                        }

                        if (count($parent_attributes) > 0) {
                            foreach ($parent_attributes as $key_parent_attributes => $value_parent_attributes) {
                                $create_attributes = $this->vn_to_str($value_parent_attributes->name);
                                $create_attributes = strtolower($this->clean($value_parent_attributes->name));
                                $create_attributes = "pa_" . $create_attributes;
                                //check in_array exit in slug
                                if (!in_array($create_attributes, $slug_attributes)) {

                                    $create_data_attr = [
                                        'name'         => $value_parent_attributes->name,
                                        'slug'         => $create_attributes,
                                        'has_archives' => true,
                                    ];

                                    $data_terms = [];

                                    foreach ($value_parent_attributes->options as $key_options => $value_options) {
                                        $data_terms[] = ['name' => $value_options];
                                    }

                                    //add attr to woo api
                                    $attr_id = $woocommerce->post('products/attributes', $create_data_attr);

                                    $create_attr_terms_data = [
                                        'create' => $data_terms,
                                    ];

                                    //add attr term to woo api
                                    $woocommerce->post('products/attributes/' . $attr_id->id . '/terms/batch', $create_attr_terms_data);

                                    $attributes[] = [
                                        "id"        => $attr_id->id,
                                        "name"      => $value_parent_attributes->name,
                                        "visible"   => true,
                                        "variation" => true,
                                        "options"   => $value_parent_attributes->options,
                                    ];

                                } else {
                                    $index_exit_attr = array_search($create_attributes, $slug_attributes, true);

                                    $attributes[] = [
                                        "id"        => $id_attributes[$index_exit_attr],
                                        "name"      => $value_parent_attributes->name,
                                        "visible"   => true,
                                        "variation" => true,
                                        "options"   => $value_parent_attributes->options,
                                    ];
                                }

                            }

                        }
                    }

                    $data = [
                        'name'              => $value->description,
                        'type'              => $type,
                        'regular_price'     => $regular_price_prices,
                        'sale_price'        => strval($price_discount),
                        'date_on_sale_from' => $date_on_sale_from,
                        'date_on_sale_to'   => $date_on_sale_to,
                        'short_description' => $value->long_description,
                        'stock_quantity'    => $inventory_number,
                        'manage_stock'      => true,
                        'tax_status'        => $tax_status,
                        'tax_class'         => $tax_class,
                        'sku'               => $value->sku_code,
                        'tags'              => $tags_final,
                        'images'            => $images,
                        'description'       => $value->long_descriptions,
                        'attributes'        => $attributes,
                    ];

                    array_push($data_create, $data);
                    if (count($data_create) == $this->amount) {
                        array_push($data_create_master, $data_create);
                        $data_create = [];
                    }

                } else {

                    $get_tags_product = $this->get_tags_product($value->id);

                    $tags_id    = [];
                    $tags_name  = [];
                    $tags_final = [];

                    if (count($get_tags_product) > 0) {
                        foreach ($get_tags_product as $get_tags_) {
                            $tags_id[]   = $get_tags_['rel_id'];
                            $tags_name[] = $get_tags_['name'];
                        }
                    }if (count($tags_name) > 0) {
                        $data_tag_ = [];
                        foreach ($tags_name as $key_count => $tags_) {
                            $tags_    = strtolower($tags_);
                            $tags_    = trim($tags_);
                            $tags_    = $this->vn_to_str($tags_);
                            $name_tag = $this->clean($tags_);

                            if (!in_array($name_tag, $tag_woo_slug)) {
                                $data_tag_[] = [
                                    'name' => $name_tag,
                                ];

                            } else {
                                foreach ($tag_woo_slug as $keyss => $valuess_) {
                                    if ($valuess_ == $name_tag) {
                                        $tags_final[] = ['id' => $tag_woo_id[$keyss]];
                                    }
                                }
                            }

                        }
                        foreach ($data_tag_ as $data_1) {
                            if (!in_array($data_1["name"], $tag_woo_name)) {
                                $avbcs          = $woocommerce->post('products/tags', $data_1);
                                $tag_woo_slug[] = $avbcs->slug;
                                $tag_woo_id[]   = $avbcs->id;
                                $tags_final[]   = ['id' => $avbcs->id];
                            }
                        }
                    }
                    $index_key = array_search($value->sku_code, $arr_product_store, true);

                    if (count($arr_product_id_store) > 0) {
                        $regular_price        = $this->get_price_store($value->id, $store_id);
                        $regular_price_prices = '';
                        if (!isset($regular_price->prices)) {
                            $regular_price_prices = 0;
                        } else {
                            $regular_price_prices = $regular_price->prices;
                        }

                        $stock_quantity = $this->affiliate_store_model->get_total_inventory_commodity($value->id);
                        $minimum_inventory = get_option('affiliate_minimum_inventory');
                        $maximum_inventory = get_option('affiliate_maximum_inventory');
                        $inventory_number = 0;
                        if(isset($stock_quantity->inventory_number)){
                            if($stock_quantity->inventory_number > $minimum_inventory){
                                $inventory_number = $stock_quantity->inventory_number - $minimum_inventory;
                            }

                            if($inventory_number > $maximum_inventory){
                                $inventory_number = $maximum_inventory;
                            }
                        }

                        if ($this->affiliate_store_model->get_all_image_file_name($value->id)) {
                            $file_name = $this->affiliate_store_model->get_all_image_file_name($value->id);
                        }

                        $images       = [];
                        $images_final = [];
                        if (isset($file_name)) {
                            foreach ($file_name as $k => $name) {
                                if (file_exists('./modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])) {
                                array_push($images, array('src' => site_url('modules/warehouse/uploads/item_img/' . $value->id . '/' . $name['file_name'])));
                            }
                            }
                        }
                        $tax_status = 'taxable';
                        $tax_class  = '';
                        $taxname    = '';
                        if ($value->tax != '' && !is_null($value->tax)) {
                            $tax = $this->get_tax($value->tax);
                            if ($tax != '') {
                                $tax_status = 'taxable';
                                $tax->name  = $this->vn_to_str($tax->name);
                                $tax->name  = strtolower($this->clean($tax->name));
                                if (!in_array($tax->name, $arr_taxes)) {
                                    $slug_class = $this->create_new_tax_sync($store_id, $tax->name, $tax->taxrate);
                                    $data_rates = [
                                        "country"  => "",
                                        "state"    => "",
                                        "postcode" => "",
                                        "city"     => "",
                                        "compound" => false,
                                        "shipping" => false,
                                        'rate'     => $tax->taxrate,
                                        'name'     => $tax->name,
                                        'class'    => $slug_class,
                                    ];
                                    $woocommerce->post('taxes', $data_rates);
                                } else {
                                    $name_tax_finnal = explode(" ", $tax->name);
                                    $slug_class      = strtolower(implode("-", $name_tax_finnal));
                                }
                                if ($tax == '') {
                                    $taxname = 'zero-rate';
                                } else {
                                    if (isset($slug_class)) {
                                        $taxname = $slug_class;
                                    } else {
                                        $taxname = 'standard';
                                    }
                                }
                                $tax_class = $taxname;
                            }
                        }

                        $type       = 'simple';
                        $attributes = [];

                        if (in_array($value->sku_code, $products_variation['sku_code_s'])) {
                            $type = 'variable';
                            //get id by index of array $products_variation['sku_code_s']
                            $index = array_search($value->sku_code, $products_variation['sku_code_s'], true);

                            $this->db->where('id', $products_variation['ids'][$index]);

                            $item_attributes = $this->db->get(db_prefix() . 'items')->row();

                            if ($item_attributes->parent_attributes != null || $item_attributes->parent_attributes != '') {
                                $parent_attributes = json_decode($item_attributes->parent_attributes);
                            } else {
                                $parent_attributes = [];
                            }

                            $products_attributes = $woocommerce->get('products/attributes');
                            //get name attr
                            $slug_attributes = [];
                            $name_attributes = [];
                            $id_attributes   = [];

                            if (count($products_attributes) > 0) {
                                foreach ($products_attributes as $key_products_attributes => $value_products_attributes) {
                                    array_push($slug_attributes, $value_products_attributes->slug);
                                    array_push($name_attributes, $value_products_attributes->name);
                                    array_push($id_attributes, $value_products_attributes->id);
                                }
                            }

                            if (count($parent_attributes) > 0) {
                                $update_attr = $woocommerce->get('products/' . $arr_product_id_store[$index_key]);
                                foreach ($parent_attributes as $key_parent_attributes => $value_parent_attributes) {
                                    if ($value_parent_attributes->name == $update_attr->attributes[$key_parent_attributes]->name) {
                                        $attributes[] = [
                                            "id"        => $update_attr->attributes[$key_parent_attributes]->id,
                                            "name"      => $value_parent_attributes->name,
                                            "visible"   => true,
                                            "variation" => true,
                                            "options"   => $value_parent_attributes->options,
                                        ];
                                    }
                                }
                            }
                        }

                        $data_cus_update_2 = [
                            'id'                => $arr_product_id_store[$index_key],
                            'tags'              => $tags_final,
                            'type'              => $type,
                            'name'              => $value->description,
                            'regular_price'     => $regular_price_prices,
                            'tax_status'        => $tax_status,
                            'tax_class'         => $tax_class,
                            'short_description' => $value->long_description,
                            'description'       => $value->long_descriptions,
                            'stock_quantity'    => $inventory_number,
                            'manage_stock'      => true,
                            'images'            => $images,
                            'attributes'        => $attributes,
                        ];
                        array_push($data_cus_update_, $data_cus_update_2);
                        if (count($data_cus_update_) == $this->amount) {
                            array_push($data_cus_update_master, $data_cus_update_);
                            $data_cus_update_ = [];
                        }
                    }
                }
            }
        }

        if (count($arr_product_id_store) > 0) {
            if (count($data_cus_update_) < $this->amount) {
                array_push($data_cus_update_master, $data_cus_update_);
            }

            if ($data_cus_update_) {
                foreach ($data_cus_update_master as $data__s) {
                    $data_cus_ = [
                        'update' => $data__s,
                    ];

                    $woocommerce->post('products/batch', $data_cus_);
                    $this->exit_type_variation($store_id, 2);
                }
            }
        }
        if (count($data_create) < 10) {
            array_push($data_create_master, $data_create);
        }

        if (count($data_create_master) > 0 && count($data_create_master[0]) > 0) {
            foreach ($data_create_master as $data__) {
                $data_cus = [
                    'create' => $data__,
                ];

                $create_batch = $woocommerce->post('products/batch', $data_cus);

            }
            $this->exit_type_variation($store_id, 1);
        }

        $log_product = [
            'member_id'   => $member_id,
            'description' => 'Sync all product WooCommerce(' . $store_name . ')',
            'datecreated' => date('Y-m-d H:i:s'),
            "type"        => "affiliate_product_all",
        ];
        $this->db->insert(db_prefix() . 'affiliate_logs', $log_product);

        return true;
    }

    /**
     * process orders woo
     * @param  int $store_id
     * @return bool
     */
    public function process_orders_woo($member_id, $store_id)
    {
        $this->load->model('clients_model');
        $this->load->model('emails_model');
        $this->load->model('affiliate_model');
        $this->load->model('affiliate_store_model');
        $store      = $this->affiliate_model->get_woocommerce_channel($store_id);
        $store_name = $store->name_channel;
        $password   = $this->generate_string();

        $data = $this->sync_order_woo_system($store_id);
        $this->db->select('iso2');
        $iso2 = $this->db->get(db_prefix() . 'countries')->result_array();
        $iso1 = [];
        foreach ($iso2 as $key => $value) {
            $iso1[] = $value['iso2'];
        }
        $email_client = $this->get_all_email_contacts();

        $orders = [];

        if (!empty($data)) {
            //for
            foreach ($data as $key => $value) {

                if ($value->status == "completed" || $value->status == "cancelled" || $value->status == "pending" || $value->status == "refunded" || $value->status == "failed") {
                    $status_update_order_sync = 0;
                    $admin_action             = 0;
                    switch ($value->status) {
                        case 'completed':
                            $status_update_order_sync = 4;
                            $admin_action             = 0;
                            break;
                        case 'cancelled':
                            $status_update_order_sync = 7;
                            $admin_action             = 1;
                            break;
                        case 'pending':
                            $status_update_order_sync = 1;
                            $admin_action             = 0;
                            break;
                        case 'refunded':
                            $status_update_order_sync = 5;
                            $admin_action             = 0;
                            break;
                        case 'failed':
                            $status_update_order_sync = 7;
                            $admin_action             = 1;
                            break;
                    }

                    $data_update['status']       = $status_update_order_sync;
                    $data_update['admin_action'] = $admin_action;
                    $this->db->where('order_code', $value->number);
                    $this->db->update(db_prefix() . 'affiliate_orders', $data_update);

                } else if ($value->status == "processing" || $value->status == "on-hold") {
                    if (!in_array($value->billing->email, $email_client)) {
                        if (in_array($value->billing->country, $iso1)) {
                            $this->db->where('iso2', $value->billing->country);
                            $info_create['country'] = $this->db->get(db_prefix() . 'countries')->row()->country_id;
                        }
                        $first_name                         = $value->billing->first_name;
                        $last_name                          = $value->billing->last_name;
                        $address_1                          = $value->billing->address_1;
                        $address_2                          = $value->billing->address_2;
                        $street                             = $address_1 . ',' . $address_2;
                        $city                               = $value->billing->city;
                        $state                              = $value->billing->state;
                        $postcode                           = $value->billing->postcode;
                        $info_create['company']             = $first_name . ' ' . $last_name;
                        $info_create['address']             = $street;
                        $info_create['city']                = $city;
                        $info_create['state']               = $state;
                        $info_create['billing_street']      = $street;
                        $info_create['billing_city']        = $city;
                        $info_create['billing_state']       = $state;
                        $info_create['billing_zip']         = $postcode;
                        $info_create['billing_country']     = is_numeric($info_create['country']) ? $info_create['country'] : 0;
                        $info_create['shipping_street']     = $street;
                        $info_create['shipping_city']       = $city;
                        $info_create['shipping_state']      = $info_create['country'];
                        $info_create['country']             = $info_create['country'];
                        $info_create['shipping_zip']        = $postcode;
                        $info_create['shipping_country']    = $info_create['country'];
                        $info_create['firstname']           = $first_name;
                        $info_create['lastname']            = $last_name;
                        $info_create['zip']                 = $postcode;
                        $info_create['email']               = $value->billing->email;
                        $info_create['contact_phonenumber'] = $value->billing->phone;
                        $info_create['password']            = $password;
                        $info_create['affiliate_code']      = get_affiliate_user_code();
                        $link                               = '<a href="' . site_url("authentication/login") . '">' . site_url('authentication/login') . '</a>';
                        $client                             = $this->clients_model->add($info_create, true);
                    }
                    array_push($orders,
                        array(
                            'order_code'           => $value->number,
                            'billing'              => $value->billing,
                            'status'               => $value->status,
                            'shipping'             => $value->shipping,
                            'line_items'           => $value->line_items,
                            'email'                => $value->billing->email,
                            'notes'                => $value->customer_note,
                            'tax'                  => $value->total_tax,
                            'shipping_total'       => $value->shipping_total,
                            'payment_method'       => $value->payment_method,
                            'payment_method_title' => $value->payment_method_title,
                        )
                    );
                    

                }
            }
            $order_number = [];
            $this->db->where('member_id', $member_id);
            $member_orders = $this->db->get(db_prefix() . 'affiliate_orders')->result_array();
            foreach ($member_orders as $key => $value) {
                $order_number[] = $value['order_code'];
            }
            $store_item_list = [];
            $products_list_store = $this->products_list_store($store_id, true);
            foreach ($products_list_store as $key => $value) {
                $store_item_list[] = $value['sku_code'];
            }

            foreach ($orders as $key => $value_) {
                if (!in_array($value_['order_code'], $order_number)) {
                    $productid_list = [];
                    $prices         = [];
                    $quantity_list  = [];

                    $total     = 0;
                    $total_tax = 0;
                    $subtotal  = 0;
                    $check_item = false;
                    foreach ($value_['line_items'] as $items) {
                        if(!in_array($items->sku, $store_item_list)){
                            $check_item = false;
                            break;
                        }else{
                            $check_item = true;
                        }

                        $prices[]        = $items->price;
                        $quantity_list[] = $items->quantity;
                        $subtotal += $items->subtotal;
                        $total_tax += $items->subtotal_tax;
                        $this->db->where('sku_code', $items->sku);
                        $productid_list[] = $this->db->get(db_prefix() . 'items')->row();
                    }

                    if(!$check_item){
                        continue;
                    }
                    
                    if ($value_['shipping_total'] != "0.00") {
                        $subtotal += $value_['shipping_total'];
                    }

                    $discounts_woo = 0;
                    $total         = $subtotal + $total_tax - $discounts_woo;
                    $this->db->where('email', $value_['email']);
                    $contact = $this->db->get(db_prefix() . 'contacts')->row();

                    $data_client                   = $this->clients_model->get($contact->userid);
                    $data_cart['customer']         = $contact->userid;
                    $data_cart['order_code']       = $value_['order_code'];
                    $data_cart['channel_id']       = 3;
                    $data_cart['channel']          = 'WooCommerce(' . $store_name . ')  ';
                    $data_cart['billing_street']   = $data_client->billing_street;
                    $data_cart['billing_city']     = $data_client->billing_city;
                    $data_cart['billing_state']    = $data_client->billing_state;
                    $data_cart['billing_country']  = $data_client->billing_country;
                    $data_cart['billing_zip']      = $data_client->billing_zip;
                    $data_cart['shipping_street']  = $data_client->shipping_street;
                    $data_cart['shipping_city']    = $data_client->shipping_city;
                    $data_cart['shipping_state']   = $data_client->shipping_state;
                    $data_cart['shipping_country'] = $data_client->shipping_country;
                    $data_cart['shipping_zip']     = $data_client->shipping_zip;
                    $data_cart['shipping_zip']     = $data_client->shipping_zip;
                    $data_cart['note']             = $value_['notes'];
                    $data_cart['admin_action']     = 0;
                    $data_cart['total']            = $total;
                    $data_cart['subtotal']         = $subtotal;
                    $data_cart['total_tax']        = $value_['tax'];
                    $data_cart['datecreated']      = date('Y-m-d H:i:s');
                    $data_cart['member_id']        = $member_id;
                    //add shipping and payment method
                    $data_cart['shipping']              = $value_['shipping_total'];
                    $data_cart['allowed_payment_modes'] = $value_['payment_method'];
                    $data_cart['payment_method_title']  = $value_['payment_method_title'];
                    $this->db->insert(db_prefix() . 'affiliate_orders', $data_cart);
                    $insert_id     = $this->db->insert_id();
                    
                    $temp = '';
                    if ($insert_id) {
                        foreach ($productid_list as $key => $p_value) {
                            if (isset($p_value->description)) {
                                $data_detailt['item_id']          = $p_value->id;
                                $data_detailt['qty']              = $quantity_list[$key];
                                $data_detailt['order_id']         = $insert_id;
                                $data_detailt['description']      = $p_value->description;
                                $data_detailt['rate']             = $prices[$key];
                                $data_detailt['long_description'] = $p_value->long_description;
                                $this->db->insert(db_prefix() . 'affiliate_order_items', $data_detailt);
                                $temp = $data_detailt;
                            }
                        }
                        $this->affiliate_store_model->remove_cart_data_cookie();
                    }
                }
            }
            $log_orders = [
                'member_id'   => $member_id,
                'description' => 'Sync orders WooCommerce(' . $store_name . ')',
                'datecreated' => date('Y-m-d H:i:s'),
                "type"        => "orders",
            ];
            $this->db->insert(db_prefix() . 'affiliate_logs', $log_orders);
            return true;
        }
    }

/**
 * process images synchronization
 * @param $store_id
 */

    public function process_images_synchronization_detail($member_id, $store_id, $arr_detail = null)
    {
        $this->load->model('affiliate_store_model');

        $items          = [];
        $products_store = $this->affiliate_model->get_woocommere_products($store_id);

        if (isset($arr_detail)) {
            foreach ($arr_detail as $key => $product) {
                $this->db->where('id', $product);
                array_push($items, $this->db->get(db_prefix() . 'items')->row());
            }
        } else {
            if (!empty($products_store)) {
                if (count($products_store) > 0) {
                    foreach ($products_store as $key => $product) {
                        $this->db->where('id', $product['product_id']);
                        array_push($items, $this->db->get(db_prefix() . 'items')->row());
                    }
                }
            }
        }

        $woocommerce = $this->init_connect_woocommerce($store_id);

        $per_page       = 100;
        $products_store = [];
        for ($page = 1; $page <= 100; $page++) {
            $offset         = ($page - 1) * $per_page;
            $list_products  = $woocommerce->get('products', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);
            $products_store = array_merge($products_store, $list_products);

            if (count($list_products) < $per_page) {
                break;
            }
        }
        $arr_product_store      = [];
        $data_create            = [];
        $data_create_master     = [];
        $data_cus_update_       = [];
        $data_cus_update_master = [];
        foreach ($products_store as $key => $value) {

            if ($value->sku != '') {
                foreach ($items as $item) {
                    if ($item->sku_code == $value->sku) {

                        $images = [];
                        if ($this->affiliate_store_model->get_all_image_file_name($item->id)) {
                            $file_name = $this->affiliate_store_model->get_all_image_file_name($item->id);
                        }
                        foreach ($file_name as $k => $name) {
                            if (file_exists('./modules/warehouse/uploads/item_img/' . $item->id . '/' . $name['file_name'])) {
                                array_push($images, array('src' => site_url('modules/warehouse/uploads/item_img/' . $item->id . '/' . $name['file_name'])));
                            }
                        }

                        $data_cus_update_2 = [
                            'id'     => $value->id,
                            'images' => $images,
                        ];

                        array_push($data_cus_update_, $data_cus_update_2);
                        if (count($data_cus_update_) == $this->amount) {
                            array_push($data_cus_update_master, $data_cus_update_);
                            $data_cus_update_ = [];
                        }

                        $images_arr = [
                            'id'     => $value->id,
                            'images' => $images,
                        ];
                    }
                }
            }
        }
        if (count($data_cus_update_) < $this->amount) {
            array_push($data_cus_update_master, $data_cus_update_);
        }

        if (count($data_cus_update_) > 0) {
            foreach ($data_cus_update_master as $data__s) {
                $data_cus_ = [
                    'update' => $data__s,
                ];
                $woocommerce->post('products/batch', $data_cus_);
            }
            $this->exit_type_variation($store_id, 2);
        }

        return true;
    }

    /**
     * [init_connect_woocommerce description]
     * @param  [type] $store_id [description]
     * @return [type]           [description]
     */
    public function init_connect_woocommerce($store_id)
    {
        $this->load->model('affiliate_model');
        $channel         = $this->affiliate_model->get_woocommerce_channel($store_id);
        $consumer_key    = $channel->consumer_key;
        $consumer_secret = $channel->consumer_secret;
        $url             = trim($channel->url);
        $woocommerce     = new Client(
            $url,
            $consumer_key,
            $consumer_secret,
            [
                'wp_api'            => true,
                'version'           => 'wc/v3',
                'query_string_auth' => true,
                'timeout'           => (40 * 60 * 1000),
            ]
        );
        return $woocommerce;
    }

    /**
     * products list store
     * @param  int $store_id
     * @return array
     */
    public function products_list_store_detail($store_id, $arr = [])
    {
        $rs = [];

        if (count($arr) > 0) {
            foreach ($arr as $key => $value_id) {
                $this->db->where('woocommere_channel_id = ' . $store_id . ' and product_id = ' . $value_id . '');
                array_push($rs, $this->db->get(db_prefix() . 'affiliate_woocommere_products')->result_array());
            }
        }
        return $rs;
    }

    /**
     * products list store
     * @param  int $store_id
     * @return array
     */
    public function products_list_store($store_id, $product_detail = false)
    {
        $this->db->where('woocommere_channel_id', $store_id);
        if($product_detail){
            $this->db->join(db_prefix() . 'items', db_prefix() . 'items.id = ' . db_prefix() . 'affiliate_woocommere_products.product_id', 'left');
        }
        return $this->db->get(db_prefix() . 'affiliate_woocommere_products')->result_array();
    }

    /**
     * get tax
     * @param $product_id
     * @return
     */
    public function get_tax($product_id)
    {
        if ($product_id == 0) {
            return '';
        }
        $this->db->where('id', $product_id);
        return $this->db->get(db_prefix() . 'taxes')->row();
    }

    public function vn_to_str($str)
    {

        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }
        $str = str_replace('  ', ' ', $str);
        $str = str_replace(' ', '-', $str);

        return $str;

    }
    /**
     * clean
     * @param  $string
     * @return
     */
    public function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    /**
     * create_new_tax_sync
     * @param  $store_id
     * @param  $taxclass_name
     * @param  $tax_rate
     * @return
     */
    public function create_new_tax_sync($store_id, $taxclass_name, $tax_rate)
    {
        $this->load->model('affiliate_model');
        $store = $this->affiliate_model->get_woocommerce_channel($store_id);

        $consumer_key    = $store->consumer_key;
        $consumer_secret = $store->consumer_secret;
        $url             = $store->url;
        $woocommerce     = new Client(
            $url,
            $consumer_key,
            $consumer_secret,
            [
                'wp_api'            => true,
                'version'           => 'wc/v3',
                'query_string_auth' => true,
            ]
        );
        $data = [
            'name' => $taxclass_name,
        ];
        $list_tax_class = $woocommerce->get('taxes/classes');
        $slug           = [];
        foreach ($list_tax_class as $key => $value) {
            $slug[] = $value->slug;
        }
        $replaces = $this->clean($taxclass_name);
        if (in_array($replaces, $slug)) {
            return $replaces;
        }

        $list_tax      = $woocommerce->get('taxes/classes');
        $tax_class_new = $woocommerce->post('taxes/classes', $data);
        $slug_class    = $tax_class_new->slug;

        $data_rates = [
            "country"  => "",
            "state"    => "",
            "postcode" => "",
            "city"     => "",
            "compound" => false,
            "shipping" => false,
            'rate'     => $tax_rate,
            'name'     => $taxclass_name,
            'class'    => $slug_class,
        ];
        $woocommerce->post('taxes', $data_rates);
        return $slug_class;
    }

    /**
     * get price store
     * @param  int $product_id
     * @param  int $woocommere_store_id
     * @return object
     */
    public function get_price_store($product_id, $woocommere_store_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('woocommere_channel_id', $woocommere_store_id);
        $this->db->select('prices');
        return $this->db->get('affiliate_woocommere_products')->row();
    }

    /**
     * get tags product
     * @param  integer $id the product id
     * @return array
     */
    public function get_tags_product($id)
    {
        $this->db->from(db_prefix() . 'taggables');
        $this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = ' . db_prefix() . 'taggables.tag_id', 'left');

        $this->db->where(db_prefix() . 'taggables.rel_id', $id);
        $this->db->where(db_prefix() . 'taggables.rel_type', 'item_tags');
        $this->db->order_by('tag_order', 'ASC');

        return $item_tags = $this->db->get()->result_array();
    }

    /**
     * exit type variation
     * @param  $store_id
     * @return
     */
    public function exit_type_variation($store_id, $status = 1)
    {
        $this->load->model('affiliate_store_model');
        //status = 1 create, 2 update
        $woocommerce = $this->init_connect_woocommerce($store_id);

        //get all products have variation include ids and sku_codes
        $products_variation = $this->get_item_have_variation();
        //get all product form woo
        $per_page                = 100;
        $products_store          = [];
        $products_store_variable = [];
        for ($page = 1; $page <= 100; $page++) {
            $offset        = ($page - 1) * $per_page;
            $list_products = $woocommerce->get('products', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);
            if (count($list_products) > 0) {
                foreach ($list_products as $key => $value) {
                    if ($value->type == "variable") {
                        array_push($products_store_variable, $value);
                    }
                }
            }
            $products_store = array_merge($products_store, $list_products);

            if (count($list_products) < $per_page) {
                break;
            }
        }
        $products_attributes = $woocommerce->get('products/attributes');
        //get name attr
        $slug_attributes = [];
        $name_attributes = [];
        $id_attributes   = [];

        if (count($products_attributes) > 0) {
            foreach ($products_attributes as $key_products_attributes => $value_products_attributes) {
                array_push($slug_attributes, $value_products_attributes->slug);
                array_push($name_attributes, $value_products_attributes->name);
                array_push($id_attributes, $value_products_attributes->id);
            }
        }
        //get sku and id have type "variable"
        $arr_product_store    = [];
        $arr_product_id_store = [];

        //# variable
        $arr_product_store_    = [];
        $arr_product_id_store_ = [];
        foreach ($products_store as $key => $value) {
            if ($value->type == 'variable') {
                array_push($arr_product_store, $value->sku);
                array_push($arr_product_id_store, $value->id);
            } else {
                array_push($arr_product_store_, $value->sku);
                array_push($arr_product_id_store_, $value->id);
            }

        }
        if (count($arr_product_store) > 0) {
            foreach ($arr_product_store as $key_arr_product_store => $value_arr_product_store) {
                $inventory_update = 0;
                if (in_array($value_arr_product_store, $products_variation['sku_code_s'])) {
                    //get id by index of array $products_variation['sku_code_s']
                    $index = array_search($value_arr_product_store, $products_variation['sku_code_s'], true);

                    $this->db->where('parent_id', $products_variation['ids'][$index]);

                    $variations = $this->db->get(db_prefix() . 'items')->result_array();

                    $data = [];

                    foreach ($variations as $key_variations => $value_variations) {
                        $attributes = [];

                        //quantity stock
                        $stock_quantity = $this->affiliate_store_model->get_total_inventory_commodity($value_variations['id']);
                        $minimum_inventory = get_option('affiliate_minimum_inventory');
                        $maximum_inventory = get_option('affiliate_maximum_inventory');
                        $inventory_number = 0;
                        if(isset($stock_quantity->inventory_number)){
                            if($stock_quantity->inventory_number > $minimum_inventory){
                                $inventory_number = $stock_quantity->inventory_number - $minimum_inventory;
                            }

                            if($inventory_number > $maximum_inventory){
                                $inventory_number = $maximum_inventory;
                            }
                        }

                        //get image product
                        if ($this->affiliate_store_model->get_all_image_file_name($value_variations['id'])) {
                            $file_name = $this->affiliate_store_model->get_all_image_file_name($value_variations['id']);
                        }

                        $images = [];

                        if (isset($file_name)) {
                            foreach ($file_name as $k => $name) {
                                if (file_exists('./modules/warehouse/uploads/item_img/' . $value_variations['id'] . '/' . $name['file_name'])) {
                                    array_push($images, array('src' => site_url('modules/warehouse/uploads/item_img/' . $value_variations['id'] . '/' . $name['file_name'])));
                                }
                            }
                        }

                        //get attr variable product
                        if ($value_variations['attributes'] != null || $value_variations['attributes'] != '') {
                            $variations_attributes = json_decode($value_variations['attributes']);
                        } else {
                            $variations_attributes = [];
                        }

                        if (count($variations_attributes) > 0) {
                            foreach ($variations_attributes as $key_variations_attributes => $value_variations_attributes) {
                                $cus_attributes = $this->vn_to_str($value_variations_attributes->name);
                                $cus_attributes = strtolower($this->clean($value_variations_attributes->name));
                                $cus_attributes = "pa_" . $cus_attributes;
                                //check in_array exit in slug
                                if (in_array($cus_attributes, $slug_attributes)) {
                                    $index_exit_attr_variable = array_search($cus_attributes, $slug_attributes, true);
                                    $attributes[]             = [
                                        "id"     => $id_attributes[$index_exit_attr_variable],
                                        "name"   => $name_attributes[$index_exit_attr_variable],
                                        "option" => $value_variations_attributes->option,
                                    ];
                                }
                            }
                        }

                        $inventory_update += (int) $inventory_number;
                        if ($status == 1) {
                            $data[] = [
                                'regular_price'      => $value_variations['rate'],
                                'price'              => $value_variations['rate'],
                                'stock_quantity'     => $inventory_number,
                                'manage_stock'       => true,
                                'sku'                => $value_variations['sku_code'],
                                'image'              => $images,
                                'description'        => ($value_variations['long_descriptions'] == null ? "" : $value_variations['long_descriptions']),
                                'attributes'         => $attributes,
                                "backorders"         => "yes",
                                "backorders_allowed" => true,
                                "backordered"        => false,
                            ];
                        } else {
                            $variation_product_value = $woocommerce->get('products/' . $arr_product_id_store[$key_arr_product_store] . '/variations');

                            if (count($variation_product_value) > 0) {
                                foreach ($variation_product_value as $key_variation_product_value => $value_variation_product_value) {
                                    if ($value_variation_product_value->sku == $value_variations['sku_code']) {
                                        $data[] = [
                                            'id'                 => $value_variation_product_value->id,
                                            'regular_price'      => $value_variations['rate'],
                                            'price'              => $value_variations['rate'],
                                            'stock_quantity'     => $inventory_number,
                                            'manage_stock'       => true,
                                            'sku'                => $value_variations['sku_code'],
                                            'image'              => $images,
                                            'description'        => ($value_variations['long_descriptions'] == null ? "" : $value_variations['long_descriptions']),
                                            'attributes'         => $attributes,
                                            "backorders"         => "yes",
                                            "backorders_allowed" => true,
                                            "backordered"        => false,
                                        ];
                                    }
                                }
                            }
                        }

                    }

                    if ($status == 1) {
                        $data_variations = [
                            'create' => $data,
                        ];
                    } else {
                        $data_variations = [
                            'update' => $data,
                        ];
                    }

                    $data_update = [
                        'stock_quantity' => $inventory_update,
                    ];

                    //check and insert product variable
                    if (count($products_store_variable) > 0) {
                        foreach ($products_store_variable as $key => $value_products_variation) {
                            if ($value_products_variation->sku == $products_variation['sku_code_s'][$index]) {
                                $woocommerce->post('products/' . $value_products_variation->id . '/variations/batch', $data_variations);
                                //update inventory product parent variable
                                $woocommerce->post('products/' . $value_products_variation->id, $data_update);
                            }
                        }
                    }

                }
            }
        }

    }

    /**
     * get item have variation
     * @return array
     */
    public function get_item_have_variation()
    {
        $data                 = $this->db->query('select DISTINCT (parent_id) as ids,(select sku_code FROM ' . db_prefix() . 'items where id = ids) as sku_code_s from ' . db_prefix() . 'items where parent_id != "" or parent_id != 0 or parent_id IS NOT NULL')->result_array();
        $result['ids']        = [];
        $result['sku_code_s'] = [];
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $result['ids'][]        = $value['ids'];
                $result['sku_code_s'][] = $value['sku_code_s'];
            }
        }
        return $result;
    }

    /**
     *  get product not parent id
     * @return array
     */
    public function get_product_parent_id()
    {
        $this->db->where('parent_id IS NULL or parent_id = "" or parent_id = 0');
        return $this->db->get(db_prefix() . 'items')->result_array();
    }

    /**
     * generate_string
     * @param  integer $strength
     * @return string
     */
    public function generate_string($strength = 16)
    {
        $input         = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length  = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }

    /**
     * sync order woo system
     * @param  int $store_id
     * @return string
     */
    public function sync_order_woo_system($store_id)
    {
        $woocommerce = $this->init_connect_woocommerce($store_id);
        $per_page    = 100;
        $order       = [];
        for ($page = 1; $page <= $this->per_page_tags; $page++) {
            $offset = ($page - 1) * $per_page;
            $orders = $woocommerce->get('orders', ['per_page' => $per_page, 'offset' => $offset, 'page' => $page]);

            $order = array_merge($order, $orders);

            if (count($orders) < $this->per_page_tags) {
                break;
            }
        }

        return $order;
    }

    /**
     * get all email contacts
     * @return $data_email
     */
    public function get_all_email_contacts()
    {
        $data       = $this->db->get(db_prefix() . 'contacts')->result_array();
        $data_email = [];
        foreach ($data as $key => $value) {
            $data_email[] = $value['email'];
        }
        return $data_email;
    }

    /**
     * add invoice when order v2
     * @param int $orderid
     * @return bolean
     */
    public function add_inv_when_order_v2($orderid, $status = '')
    {

        $this->load->model('invoices_model');
        $this->load->model('credit_notes_model');
        $cart = $this->get_cart($orderid);

        $cart_detailt = $this->get_cart_detailt_by_master($orderid);
        $newitems     = [];
        $count        = 0;
        foreach ($cart_detailt as $key => $value) {
            $unit      = 0;
            $unit_name = '';
            $this->db->where('id', $value['product_id']);
            $data_product = $this->db->get(db_prefix() . 'items')->row();
            $tax          = $this->get_tax($data_product->tax);
            if ($tax == '') {
                $taxname = '';
            } else {
                $taxname = $tax->name . '|' . $tax->taxrate;
            }
            if ($data_product) {
                $unit = $data_product->unit_id;
                if ($unit != 0 || $unit != null) {
                    $this->db->where('unit_type_id', $unit);
                    $unit_parent = $this->db->get(db_prefix() . 'ware_unit_type')->row();
                    $unit_name   = $unit_parent->unit_name;
                } else {
                    $unit_name = "";
                }
            }
            $count = $key;
            array_push($newitems, array('order' => $key, 'description' => $value['product_name'], 'long_description' => $value['long_description'], 'qty' => $value['quantity'], 'unit' => $unit_name, 'rate' => $value['prices'], 'taxname' => array($taxname)));
        }
        $total           = $this->get_total_order($orderid)['total'];
        $sub_total       = $this->get_total_order($orderid)['sub_total'];
        $discount_total  = $this->get_total_order($orderid)['discount'];
        $__number        = get_option('next_invoice_number');
        $_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
        $this->db->where('isdefault', 1);
        $curreny = $this->db->get(db_prefix() . 'currencies')->row()->id;
        if ($cart) {
            $data['clientid']                 = $cart->userid;
            $data['billing_street']           = $cart->billing_street;
            $data['billing_city']             = $cart->billing_city;
            $data['billing_state']            = $cart->billing_state;
            $data['billing_zip']              = $cart->billing_zip;
            $data['billing_country']          = $cart->billing_country;
            $data['include_shipping']         = 1;
            $data['show_shipping_on_invoice'] = 1;
            $data['shipping_street']          = $cart->shipping_street;
            $data['shipping_city']            = $cart->shipping_city;
            $data['shipping_state']           = $cart->shipping_state;
            $data['shipping_zip']             = $cart->shipping_zip;
            $date_format                      = get_option('dateformat');
            $date_format                      = explode('|', $date_format);
            $date_format                      = $date_format[0];
            $data['date']                     = date($date_format);
            $data['duedate']                  = date($date_format);
            //terms_invoice
            $data['terms'] = get_option('predefined_terms_invoice');

            $payment_model_list = [];
            if ($cart->allowed_payment_modes != '') {
                $payment_model_list = explode(',', $cart->allowed_payment_modes);
            }
            $data["allowed_payment_modes"] = $payment_model_list;
            if (isset($cart->shipping)) {
                array_push($newitems, array('order' => $count + 1, 'description' => _l('shipping'), 'long_description' => "", 'qty' => 1, 'unit' => "", 'rate' => $cart->shipping, 'taxname' => array()));
            }
            $data['currency'] = $curreny;
            $data['newitems'] = $newitems;
            $data['number']   = $_invoice_number;
            $data['total']    = $cart->total;
            $data['subtotal'] = $cart->sub_total;
            if ($cart->discount_type == 1) {
                $data['discount_percent'] = $cart->discount;
                $data['discount_total']   = ($cart->discount * $data['subtotal']) / 100;
            } elseif ($cart->discount_type == 2) {
                $data['discount_total']   = $cart->discount;
                $data['discount_percent'] = ($cart->discount / $data['subtotal']) * 100;
            } else {
                $data['discount_total']   = '';
                $data['discount_percent'] = '';
            }
            $id = $this->invoices_model->add($data);

            if ($cart->discount != '' && $cart->discount_type != '' && $cart->voucher != '') {
                $credit_notes = $this->credit_note_from_invoice_omni($id);
            }
            $prefix = get_option('invoice_prefix');
            $this->update_status_order_comfirm($orderid, $prefix, $_invoice_number, $__number, $status);
            return $id;
        }
        return true;
    }

    /**
     * test connect 
     * @param   $data 
     * @return        
     */
    public function test_connect($data)
    {
      $consumer_key = $data['consumer_key'];
      $consumer_secret = $data['consumer_secret'];
      $url = $data['url'];
      $woocommerce = new Client(
        $url, 
        $consumer_key, 
        $consumer_secret,
        [ 
          'wp_api' => true,
          'version' => 'wc/v3',
          'query_string_auth' => true,
          'timeout' => 400,
        ]
      );
      try {
        if($woocommerce->get('')){
          return true;
        }
      } catch (Exception $e) {
        return false;
      }
    }
}
