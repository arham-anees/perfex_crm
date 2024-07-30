<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Affiliate_store_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  get list product by group
     * @param  int  $id_chanel
     * @param  int  $id_group
     * @param  string  $key
     * @param  integer $limit
     * @param  integer $ofset
     * @return  array $result
     */
    public function get_list_product_by_group($member_code, $id_group = '0', $key = '', $limit = 0, $ofset = 1)
    {
        $this->load->model('affiliate_model');
        $member          = $this->affiliate_model->get_member_by_code($member_code);
        if($member){
            $product_list    = $this->affiliate_model->get_product_list($member->id);
            $list_product_id = '';
            $where_product   = '';
            if ($product_list) {
                foreach ($product_list as $value) {
                    if ($list_product_id == '') {
                        $list_product_id = $value['id'];
                    } else {
                        $list_product_id .= ',' . $value['id'];
                    }
                }
                if ($list_product_id != '') {
                    $where_product = ' id in (' . $list_product_id . ')';
                }
            }else{
              $where_product = '1=0';
            }
    
            $search = '';
            if ($key != '') {
                $search = ' and (description like \'%' . $key . '%\' or rate like \'%' . $key . '%\' or sku_code like \'%' . $key . '%\' or commodity_barcode like \'%' . $key . '%\') ';
            }
    
            $group = '';
            if ($id_group != '0') {
                $group = ' and group_id = ' . $id_group . '';
            }
    
            $where = $where_product . '' . $group . '' . $search;
    
            if ($where != '') {
                $where = 'where ' . $where.' and can_be_sold = "can_be_sold"';
            }else{
                $where = 'where can_be_sold = "can_be_sold"';
            }
    
            $count_product       = 'select count(id) as count from ' . db_prefix() . 'items ' . $where;
            $select_list_product = 'select  id, description, long_description, rate, sku_code, tax, group_id, commodity_barcode from ' . db_prefix() . 'items ' . $where . ' limit ' . $limit . ',' . $ofset;
            return [
                'list_product' => $this->db->query($select_list_product)->result_array(),
                'count'        => (int) $this->db->query($count_product)->row()->count,
            ];
        }
        else{
            return [
                'list_product' => [],
                'count'        => 0
            ];
        }
    }

    /**
     *  get_group_product
     * @param  int $id
     * @return  object or array object
     */
    public function get_group_product($id = '')
    {
        if ($id != '') {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'items_groups')->row();
        } else {
            return $this->db->get(db_prefix() . 'items_groups')->result_array();
        }
    }

    /**
     * check discount
     * @param  int $id_product
     * @param  date $date
     * @return object
     */
    public function check_discount($id_product, $date, $channel = 0, $store = '')
    {
        if ($store == '') {
            return $this->db->query('select * from ' . db_prefix() . 'affiliate_trade_discount where find_in_set(' . $id_product . ',items) and start_time <= \'' . $date . '\' and end_time >= \'' . $date . '\' and voucher = \'\' and group_clients = \'\' and group_items = \'\' and clients = \'\'
        and channel = ' . $channel . '  ')->row();
        } else {
            return $this->db->query('select * from ' . db_prefix() . 'affiliate_trade_discount where find_in_set(' . $id_product . ',items) and start_time <= \'' . $date . '\' and end_time >= \'' . $date . '\' and voucher = \'\' and group_clients = \'\' and group_items = \'\' and clients = \'\' and channel = ' . $channel . ' and store = ' . $store . '')->row();
        }
    }

    /**
     * get_price_channel
     * @param  $product_id
     * @param  $sales_channel_id
     * @return  object
     */
    public function get_price_channel($product_id, $sales_channel_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('sales_channel_id', $sales_channel_id);
        $this->db->select('prices');
        return $this->db->get(db_prefix() . 'sales_channel_detailt')->row();
    }

    /**
     * get total inventory commodity
     * @param  boolean $id
     * @return object
     */
    public function get_total_inventory_commodity($commodity_id = false)
    {
        if ($commodity_id != false) {
            $sql = 'SELECT sum(inventory_number) as inventory_number FROM ' . db_prefix() . 'inventory_manage
      where ' . db_prefix() . 'inventory_manage.commodity_id = ' . $commodity_id . ' order by ' . db_prefix() . 'inventory_manage.warehouse_id';
            return $this->db->query($sql)->row();
        }

    }

    /**
     * has product cat
     * @param  integer  $affiliate_code
     * @param  integer  $group_id
     * @return boolean
     */
    public function has_product_cat($affiliate_code, $group_id)
    {
        $this->load->model('affiliate_model');
        $member          = $this->affiliate_model->get_member_by_code($affiliate_code);

        if($member){
            $this->db->where('member_id', $member->id);
            $this->db->where('group_id', $group_id);
            $this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.id = ' . db_prefix() . 'affiliate_user_products.product_id', 'left');
            $count = $this->db->count_all_results(db_prefix().'affiliate_user_products');

            if ($count > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * get_image_items
     * @param  integer $item_id
     * @return string
     */
    public function get_image_items($item_id)
    {
        $file_path = site_url('modules/affiliate/assets/images/no_image.jpg');
        $data_file = $this->get_image_file_name($item_id);
        if ($data_file) {
            if ($data_file->file_name != '') {
                $file_path = site_url('modules/warehouse/uploads/item_img/' . $item_id . '/' . $data_file->file_name);
            }
        }
        return $file_path;
    }

    /**
     * get image file name
     * @param   int $id
     * @return  object
     */
    public function get_image_file_name($id)
    {
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'commodity_item_file');
        $this->db->select('file_name');
        return $this->db->get(db_prefix() . 'files')->row();
    }

    /**
     *  get product
     * @param  int $id
     * @return  object or array object
     */
    public function get_product($id = '')
    {
        if ($id != '') {
            $this->db->select(db_prefix() . 'ware_unit_type.unit_name' . ',' . db_prefix() . 'items.*');
            $this->db->join(db_prefix() . 'ware_unit_type', db_prefix() . 'ware_unit_type.unit_type_id=' . db_prefix() . 'items.unit_id', 'left');
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'items')->row();
        } else {
            $this->db->where('can_be_sold = "can_be_sold"');
            return $this->db->get(db_prefix() . 'items')->result_array();
        }
    }

    /**
     *  check tax product
     * @param  $list_product
     * @return  array
     */
    public function check_tax_product($list_product)
    {
        $array = [];
        if (!empty($list_product)) {
            $list_product = explode(',', $list_product);
            foreach ($list_product as $key => $value) {
                $product = $this->get_product($value);
                if ($product) {
                    if ($product->tax != '' && !is_null($product->tax)) {
                        $this->db->where('id', $product->tax);
                        $tax = $this->db->get(db_prefix() . 'taxes')->row();
                        if ($tax) {
                            array_push($array, $tax->taxrate);
                        } else {
                            array_push($array, 0);
                        }
                    } else {
                        array_push($array, 0);
                    }
                }
            }
        }
        return $array;
    }

/**
 * Get single contacts
 * @param  mixed $id contact id
 * @return object
 */
    public function get_contact($id)
    {
        $this->db->where('id', $id);

        return $this->db->get(db_prefix() . 'contacts')->row();
    }

    /**
     * get tax product
     * @return  decimal $tax
     */
    public function get_tax_product($id_product)
    {
        if ($id_product != '') {
            $product = $this->get_product($id_product);
            if ($product) {

                if ($product->tax != '' && $product->tax) {
                    $this->db->where('id', $product->tax);
                    $tax = $this->db->get(db_prefix() . 'taxes')->row();
                    if ($tax) {
                        return $tax->taxrate;
                    } else {
                        return 0;
                    }
                }

            }
            return 0;
        }
    }

    /**
     * check out
     * @param  array $data
     * @return string order_number
     */
    public function check_out($data, $member_code)
    {

        $this->load->model('clients_model');
        $data_client = $this->clients_model->get($data['userid']);
        if ($data_client) {
            $this->load->model('affiliate_model');
            $data_order                   = [];
            $user_id                      = $data['userid'];
            $order_number                 = get_option('order_number');
            $data_order['customer']       = $user_id;
            $data_order['order_code']     = '#' . $member_code . str_pad($order_number, 5, '0', STR_PAD_LEFT);
            $data_order['approve_status'] = 0;
            $data_order['channel']        = 'portal';
            $data_order['datecreated']    = date('Y-m-d H:i:s');
            $m                            = $this->affiliate_model->get_member_by_code($member_code);
            if (isset($m->id)) {
                $data_order['member_id'] = $m->id;
            }
            $data_order['billing_street']   = $data_client->billing_street;
            $data_order['billing_city']     = $data_client->billing_city;
            $data_order['billing_state']    = $data_client->billing_state;
            $data_order['billing_country']  = $data_client->billing_country;
            $data_order['billing_zip']      = $data_client->billing_zip;
            $data_order['shipping_street']  = $data_client->shipping_street;
            $data_order['shipping_city']    = $data_client->shipping_city;
            $data_order['shipping_state']   = $data_client->shipping_state;
            $data_order['shipping_country'] = $data_client->shipping_country;
            $data_order['shipping_zip']     = $data_client->shipping_zip;
            $data_order['total']            = preg_replace('%,%', '', $data['total']);
            $data_order['subtotal']         = $data['sub_total'];
            $data_order['note']             = $data['notes'];
            $data_order['total_tax']        = $data['tax'];
            $data_order['payment_mode']     = $data['payment_methods'];

            $this->db->insert(db_prefix() . 'affiliate_orders', $data_order);
            $insert_id = $this->db->insert_id();
            if ($insert_id) {
                update_option('order_number', $order_number + 1);

                $date           = date('Y-m-d');
                $productid_list = explode(',', $data['list_id_product']);
                $quantity_list  = explode(',', $data['list_qty_product']);
                $prices_product = explode(',', $data['list_prices_product']);

                foreach ($productid_list as $key => $productid) {
                    $this->db->where('id', $productid);
                    $it                       = $this->db->get(db_prefix() . 'items')->row();
                    $item                     = [];
                    $item['description']      = $it->description;
                    $item['long_description'] = $it->long_description;
                    $item['qty']              = $quantity_list[$key];
                    $item['rate']             = $prices_product[$key];
                    $item['order']            = $key + 1;
                    $item['unit']             = '';
                    $item['item_id']          = $productid;

                    $this->add_new_order_item($item, $insert_id);
                }
                $invoice_id = $this->affiliate_model->create_invoice_by_order($insert_id);
                $this->remove_cart_data_cookie();

                if ($invoice_id) {
                    return $invoice_id;
                } else {
                    return 0;
                }
            }
            return '';
        }
    }

/**
 * get cart of client by status
 * @param  int  $userid
 * @param  int $status
 * @return array
 */
    public function get_cart_of_client_by_status($userid = '', $status = 0)
    {
        if ($userid != '') {
            $this->db->where('userid', $userid);
            $this->db->where('status', $status);
            $this->db->order_by('datecreator', 'DESC');
            return $this->db->get(db_prefix() . 'cart')->result_array();
        } elseif ($userid == '' && $status != '') {
            $this->db->where('status', $status);
            $this->db->order_by('datecreator', 'DESC');
            return $this->db->get(db_prefix() . 'cart')->result_array();
        } else {
            return $this->db->get(db_prefix() . 'cart')->result_array();
        }
    }

    /**
     * Add new order item do database
     * @param array $item     item from $_POST
     * @param mixed $order_id   order id
     * @return item id
     */
    public function add_new_order_item($item, $order_id)
    {

        $CI = &get_instance();

        $CI->db->insert(db_prefix() . 'affiliate_order_items', [
            'description'      => $item['description'],
            'long_description' => nl2br($item['long_description']),
            'qty'              => $item['qty'],
            'rate'             => number_format($item['rate'], get_decimal_places(), '.', ''),
            'order_id'         => $order_id,
            'item_order'       => $item['order'],
            'unit'             => $item['unit'],
            'item_id'          => $item['item_id'],
        ]);

        $id = $CI->db->insert_id();

        return $id;
    }

    /**
     * insert order item tax
     * @param  mixed $item_id   item id
     * @param  array $post_item $item from $_POST
     * @return  boolean
     */
    public function insert_order_item_tax($item_id, $post_item)
    {
        $affectedRows = 0;
        if (isset($post_item['taxname']) && is_array($post_item['taxname'])) {
            $CI = &get_instance();
            foreach ($post_item['taxname'] as $taxname) {
                if ($taxname != '') {
                    $tax_array = explode('|', $taxname);
                    if (isset($tax_array[0]) && isset($tax_array[1])) {
                        $tax_name = trim($tax_array[0]);
                        $tax_rate = trim($tax_array[1]);
                        if (total_rows(db_prefix() . 'affiliate_order_item_taxs', [
                            'order_item_id' => $item_id,
                            'taxrate'       => $tax_rate,
                            'taxname'       => $tax_name,
                        ]) == 0) {
                            $CI->db->insert(db_prefix() . 'affiliate_order_item_taxs', [
                                'order_item_id' => $item_id,
                                'taxrate'       => $tax_rate,
                                'taxname'       => $tax_name,
                            ]);
                            $affectedRows++;
                        }
                    }
                }
            }
        }

        return $affectedRows > 0 ? true : false;
    }

    /**
     * remove cart data cookie
     * @return bool
     */
    public function remove_cart_data_cookie()
    {
        if (isset($_COOKIE['cart_id_list']) && isset($_COOKIE['cart_qty_list'])) {
            unset($_COOKIE['cart_id_list']);
            unset($_COOKIE['cart_qty_list']);
            setcookie('cart_id_list', null, -1, '/');
            setcookie('cart_qty_list', null, -1, '/');
            return true;
        } else {
            return false;
        }
    }

    /**
     * get list product by group
     * @param  int  $id_chanel
     * @param  int  $id_group
     * @param  int  $id_product
     * @param  int $limit
     * @param  int $ofset
     * @return array
     */
    public function get_list_product_by_group_s($member_id, $id_product = '', $limit = 0, $ofset = 1)
    {
        if ($member_id == '') {
            $member_id = get_affiliate_user_id();
        }
        $item_affiliate = 'select product_id from '.db_prefix() . 'affiliate_user_products where member_id = '.$member_id.' and product_id != '.$id_product;
        $count_product       = 'select count(id) as count from ' . db_prefix() . 'items where id in ('.$item_affiliate.')';
        $select_list_product = 'select  id, description, long_description, rate from ' . db_prefix() . 'items where id in ('.$item_affiliate.') limit ' . $limit . ',' . $ofset;
        $result              = [
            'list_product' => $this->db->query($select_list_product)->result_array(),
            'count'        => (int) $this->db->query($count_product)->row()->count,
        ];
        return $result;
    }

    /**
     * get_group_product
     * @param  int $id_group
     * @return array
     */
    public function get_group_product_s($id_group)
    {
        return $this->db->query('select * from ' . db_prefix() . 'items_groups where id != ' . $id_group)->result_array();
    }

    /**
     * get all image file name
     * @param  int $id
     * @return array
     */
    public function get_all_image_file_name($id)
    {
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'commodity_item_file');
        $this->db->select('file_name');
        return $this->db->get(db_prefix() . 'files')->result_array();
    }

    /**
     * Get order list
     * @param  mixed $id    client id
     * @param  array  $where
     * @return mixed
     */
    public function get_order_list($user_id, $where = [])
    {
        $this->db->select(db_prefix() . 'affiliate_orders.id as order_id,order_code, company, ' . db_prefix() . 'affiliate_orders.datecreated as datecreated, ' . db_prefix() . 'affiliate_orders.status as status, approve_status, ' . db_prefix() . 'affiliate_orders.invoice_id as invoice_id, total,subtotal, admin_action, reason');

        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'affiliate_orders.customer', 'left');

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        $this->db->where(db_prefix() . 'affiliate_orders.customer', $user_id);

        $this->db->order_by('datecreated', 'desc');

        return $this->db->get(db_prefix() . 'affiliate_orders')->result_array();
    }

    /**
     * get order items
     * @param  integer $id
     * @return mixed
     */
    public function get_order_items($id)
    {
        $this->db->where('order_id', $id);
        $items = $this->db->get(db_prefix() . 'affiliate_order_items')->result_array();

        return $items;
    }

    /**
     * [change_status_order
     * @param  array  $data
     * @param  string  $order_number
     * @param  integer $admin_action
     * @return bool
     */
    public function change_status_order($data, $order_id, $admin_action = 0)
    {
        $this->db->where('id', $order_id);
        $data_update = [];
        $data_update['reason']       = _l($data['cancelReason']);
        $data_update['status']       = $data['status'];
        $data_update['admin_action'] = $admin_action;
        $this->db->update(db_prefix() . 'affiliate_orders', $data_update);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}
