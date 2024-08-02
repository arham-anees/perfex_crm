<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Affiliate_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get member
     * @param  integer $id member id
     * @param  array  $where
     * @return array
     */
    public function get_member($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_users')->row();
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'affiliate_users')->result_array();
    }

    /**
     * get member
     * @param object $data
     * @return id or false
     */
    public function add_member($data)
    {
        $data['password'] = app_hash_password($data['password']);
        $data['datecreated'] = date('Y-m-d H:i:s');
        if (!isset($data['approval'])) {
            $data['addedfrom']   = get_staff_user_id();
            $data['approval']    = 1;
        }

        if (isset($data['referral_code'])) {
            $this->db->where('affiliate_code', $data['referral_code']);
            $affiliate = $this->db->get(db_prefix() . 'affiliate_users')->row();
            if ($affiliate) {
                $data['under_affiliate'] = $affiliate->id;
            }
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['affiliate_code'] = $this->generate_affiliate_code();

        $this->db->insert(db_prefix() . 'affiliate_users', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {

            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }

            return $insert_id;
        }

        return false;
    }

    /**
     * Update member
     * @param  array $data member data
     * @param  mixed $id   member id
     * @return boolean
     */
    public function update_member($data, $id)
    {
        $affectedRows = 0;
        if (isset($data['password']) && $data['password'] != '') {
            $data['password'] = app_hash_password($data['password']);
        } else {
            unset($data['password']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_users', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * delete member
     *
     * @param      integer  $id     The identifier
     * @return boolean
     */
    public function delete_member($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_users');

        if ($this->db->affected_rows() > 0) {
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'aff_member');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            return true;
        }
        return false;
    }

    /**
     * get member group
     * @param  integer $id    member group id
     * @param  array  $where
     * @return object
     */
    public function get_member_group($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_user_groups')->row();
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'affiliate_user_groups')->result_array();
    }

    /**
     * Add new member group
     * @param array $data
     * @return id or false
     */
    public function add_member_group($data)
    {
        $this->db->insert(db_prefix() . 'affiliate_user_groups', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * Edit member group
     * @param  array $data
     * @return boolean
     */
    public function edit_member_group($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update(db_prefix() . 'affiliate_user_groups', [
            'name' => $data['name'],
        ]);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Delete member group
     * @param  mixed $id group id
     * @return boolean
     */
    public function delete_member_group($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_user_groups');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Add new affiliate admin
     * @param array $data
     * @return boolean
     */
    public function add_affiliate_admin($data)
    {
        $permissions = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
            unset($data['permissions']);
        }

        $affectedRows = 0;
        if (isset($data['staff'])) {
            $this->db->insert(db_prefix() . 'affiliate_admins', ['staffid' => $data['staff']]);

            $insert_id = $this->db->insert_id();

            if ($insert_id) {
                $affectedRows++;

                $this->update_permissions($permissions, $data['staff']);
            }
        }

        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Delete affiliate admin
     * @param  mixed $id admin id
     * @return boolean
     */
    public function delete_affiliate_admins($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_admins');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('staff_id', $id);
            $this->db->delete(db_prefix() . 'affiliate_admin_permissions');

            return true;
        }

        return false;
    }

    /**
     * get data member chart
     * @return object
     */
    public function get_data_member_chart()
    {
        $affiliate_users = $this->db->query('select id, under_affiliate, CONCAT(firstname," ",lastname) as full_name, email, phone
        from ' . db_prefix() . 'affiliate_users where status = 1 and approval = 1 order by under_affiliate, id')->result_array();

        $member_tree = array();

        $list_user_id = [];
        foreach ($affiliate_users as $value) {
            $list_user_id[] = $value['id'];
        }

        foreach ($affiliate_users as $user) {

            if ($user['under_affiliate'] == 0 || !in_array($user['under_affiliate'], $list_user_id)) {
                $node          = array();
                $node['name']  = $user['full_name'];
                $node['email'] = $user['email'];
                $node['phone'] = $user['phone'];

                $node['children'] = $this->get_child_node_staff_chart($user['id'], $affiliate_users);

                $member_tree[] = $node;
            }
        }
        return $member_tree;
    }

    /**
     * get child node staff chart
     * @param  integer $id
     * @param  array $affiliate_users
     * @return array
     */
    private function get_child_node_staff_chart($id, $affiliate_users)
    {
        $member_tree = array();
        foreach ($affiliate_users as $user) {
            if ($user['under_affiliate'] == $id) {
                $node          = array();
                $node['name']  = $user['full_name'];
                $node['email'] = $user['email'];
                $node['phone'] = $user['phone'];

                $node['children'] = $this->get_child_node_staff_chart($user['id'], $affiliate_users);
                if (count($node['children']) == 0) {
                    unset($node['children']);
                }
                $member_tree[] = $node;
            }
        }

        return $member_tree;
    }

    /**
     * Gets the customer.
     *
     * @param      string  $id     The identifier
     * @param      array   $where  The where
     *
     * @return     object  The customer.
     */
    public function get_customer($id = '', $where = [])
    {
        if ($id != '') {
            $this->load->model('clients_model');
            return $this->clients_model->get($id);
        }

        $this->db->select('userid, company, (SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'customer_groups JOIN ' . db_prefix() . 'customers_groups ON ' . db_prefix() . 'customer_groups.groupid = ' . db_prefix() . 'customers_groups.id WHERE customer_id = ' . db_prefix() . 'clients.userid ORDER by name ASC) as customerGroups');
        $this->db->where($where);
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }

    /**
     * Gets the product group select.
     *
     * @return     array  The product group select.
     */
    public function get_product_group_select()
    {

        $items_groups     = $this->db->get(db_prefix() . 'items_groups')->result_array();
        $list_item_groups = [];
        foreach ($items_groups as $key => $group) {
            $note               = [];
            $note['id']         = $group['id'];
            $note['label']      = $group['name'];
            $list_item_groups[] = $note;
        }
        return $list_item_groups;
    }

    /**
     * Gets the product select.
     *
     * @param      string  $staffid  The staffid
     *
     * @return     array   The product select.
     */
    public function get_product_select($where = [])
    {
        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }
        $this->db->where('can_be_sold = "can_be_sold"');
        $items     = $this->db->get(db_prefix() . 'items')->result_array();
        $list_item = [];
        foreach ($items as $key => $item) {
            $note          = [];
            $note['id']    = $item['id'];
            $note['label'] = $item['description'];
            $list_item[]   = $note;
        }
        return $list_item;
    }

    /**
     * Add new market category
     * @param       array $data
     * @return      id or false
     */
    public function add_program_category($data)
    {
        $this->db->insert(db_prefix() . 'affiliate_program_categorys', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * Edit market category
     * @param  array $data
     * @return boolean
     */
    public function edit_program_category($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update(db_prefix() . 'affiliate_program_categorys', [
            'name' => $data['name'],
        ]);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Delete market category
     * @param  integer $id group id
     * @return boolean
     */
    public function delete_program_category($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_program_categorys');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * generate affiliate code
     * @return string
     */
    public function generate_affiliate_code()
    {
        $alphabet     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $number       = '1234567890';
        $pass         = array();
        $alphaLength  = strlen($alphabet) - 1;
        $numberLength = strlen($number) - 1;

        $a = 0;
        $n = 0;
        for ($i = 0; $i < 5; $i++) {
            if ($a == 3) {
                $k      = rand(0, $numberLength);
                $pass[] = $number[$k];
            } elseif ($n == 2) {
                $k      = rand(0, $alphaLength);
                $pass[] = $alphabet[$k];
            } else {
                if (rand(0, 1) == 1) {
                    $k      = rand(0, $alphaLength);
                    $pass[] = $alphabet[$k];
                    $a++;
                } else {
                    $k      = rand(0, $numberLength);
                    $pass[] = $number[$k];
                    $n++;
                }
            }
        }

        $affiliate_code = implode($pass);

        $this->db->where('affiliate_code', $affiliate_code);
        $affiliate = $this->db->get(db_prefix() . 'affiliate_users')->row();
        if ($affiliate) {
            $affiliate_code = $this->generate_affiliate_code();
        }

        return $affiliate_code;
    }

    /**
     * Add new transaction
     * @param array $data
     * @return id or false
     */
    public function add_transaction($data)
    {
        $data['datecreated'] = date('Y-m-d H:i:s');
        $data['addedfrom']   = get_staff_user_id();
        $data['amount']      = str_replace(',', '', $data['amount']);
        $this->db->insert(db_prefix() . 'affiliate_transactions', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * Adds a affiliate program.
     *
     * @param      Object   $data   The data
     *
     * @return     boolean
     */
    public function add_affiliate_program($data)
    {
        $discount_ladder_setting = [];
        foreach ($data['discount_from_amount'] as $key => $value) {
            $node                                    = [];
            $node['discount_from_amount']            = $value;
            $node['discount_to_amount']              = $data['discount_to_amount'][$key];
            $node['discount_percent_enjoyed_ladder'] = $data['discount_percent_enjoyed_ladder'][$key];
            $discount_ladder_setting[]               = $node;
        }

        $discount_ladder_product_setting = [];
        foreach ($data['discount_from_amount_product'] as $key => $value) {
            $node                                                                    = [];
            $node['discount_from_amount_product']                                    = $value;
            $node['discount_to_amount_product']                                      = $data['discount_to_amount_product'][$key];
            $node['discount_percent_enjoyed_ladder_product']                         = $data['discount_percent_enjoyed_ladder_product'][$key];
            $discount_ladder_product_setting[$data['discount_ladder_product'][$key]] = $node;
        }

        $data['discount_ladder_setting']         = json_encode($discount_ladder_setting);
        $data['discount_ladder_product_setting'] = json_encode($discount_ladder_product_setting);

        unset($data['discount_from_amount']);
        unset($data['discount_to_amount']);
        unset($data['discount_percent_enjoyed_ladder']);

        unset($data['discount_from_amount_product']);
        unset($data['discount_to_amount_product']);
        unset($data['discount_percent_enjoyed_ladder_product']);
        unset($data['discount_ladder_product']);

        if (isset($data['discount_customers'])) {
            $data['discount_customers'] = implode(',', $data['discount_customers']);
        } else {
            $data['discount_customers'] = '';
        }

        if (isset($data['discount_customer_groups'])) {
            $data['discount_customer_groups'] = implode(',', $data['discount_customer_groups']);
        } else {
            $data['discount_customer_groups'] = '';
        }

        if (isset($data['discount_products'])) {
            $data['discount_products'] = implode(',', $data['discount_products']);
        } else {
            $data['discount_products'] = '';
        }

        if (isset($data['discount_product_groups'])) {
            $data['discount_product_groups'] = implode(',', $data['discount_product_groups']);
        } else {
            $data['discount_product_groups'] = '';
        }

        if (isset($data['discount_members'])) {
            $data['discount_members'] = implode(',', $data['discount_members']);
        } else {
            $data['discount_members'] = '';
        }

        if (isset($data['discount_member_groups'])) {
            $data['discount_member_groups'] = implode(',', $data['discount_member_groups']);
        } else {
            $data['discount_member_groups'] = '';
        }

        if (!isset($data['enable_discount'])) {
            $data['enable_discount'] = 'disable';
        }

        if (!isset($data['discount_enable_customer'])) {
            $data['discount_enable_customer'] = 'disable';
        }

        if (!isset($data['discount_enable_product'])) {
            $data['discount_enable_product'] = 'disable';
        }

        if (!isset($data['discount_enable_member'])) {
            $data['discount_enable_member'] = 'disable';
        }

        if (!isset($data['discount_first_invoices'])) {
            $data['discount_first_invoices'] = 'disable';
        }

        $commission_ladder_setting = [];
        foreach ($data['commission_from_amount'] as $key => $value) {
            $node                                      = [];
            $node['commission_from_amount']            = $value;
            $node['commission_to_amount']              = $data['commission_to_amount'][$key];
            $node['commission_percent_enjoyed_ladder'] = $data['commission_percent_enjoyed_ladder'][$key];
            $commission_ladder_setting[]               = $node;
        }

        $commission_ladder_product_setting = [];
        foreach ($data['commission_from_amount_product'] as $key => $value) {
            $node                                                                        = [];
            $node['commission_from_amount_product']                                      = $value;
            $node['commission_to_amount_product']                                        = $data['commission_to_amount_product'][$key];
            $node['commission_percent_enjoyed_ladder_product']                           = $data['commission_percent_enjoyed_ladder_product'][$key];
            $commission_ladder_product_setting[$data['commission_ladder_product'][$key]] = $node;
        }

        $data['commission_ladder_setting']         = json_encode($commission_ladder_setting);
        $data['commission_ladder_product_setting'] = json_encode($commission_ladder_product_setting);

        unset($data['commission_from_amount']);
        unset($data['commission_to_amount']);
        unset($data['commission_percent_enjoyed_ladder']);

        unset($data['commission_from_amount_product']);
        unset($data['commission_to_amount_product']);
        unset($data['commission_percent_enjoyed_ladder_product']);
        unset($data['commission_ladder_product']);

        if (isset($data['commission_customers'])) {
            $data['commission_customers'] = implode(',', $data['commission_customers']);
        } else {
            $data['commission_customers'] = '';
        }

        if (isset($data['commission_customer_groups'])) {
            $data['commission_customer_groups'] = implode(',', $data['commission_customer_groups']);
        } else {
            $data['commission_customer_groups'] = '';
        }

        if (isset($data['commission_products'])) {
            $data['commission_products'] = implode(',', $data['commission_products']);
        } else {
            $data['commission_products'] = '';
        }

        if (isset($data['commission_product_groups'])) {
            $data['commission_product_groups'] = implode(',', $data['commission_product_groups']);
        } else {
            $data['commission_product_groups'] = '';
        }

        if (isset($data['commission_members'])) {
            $data['commission_members'] = implode(',', $data['commission_members']);
        } else {
            $data['commission_members'] = '';
        }

        if (isset($data['commission_member_groups'])) {
            $data['commission_member_groups'] = implode(',', $data['commission_member_groups']);
        } else {
            $data['commission_member_groups'] = '';
        }

        if (!isset($data['enable_commission'])) {
            $data['enable_commission'] = 'disable';
        }

        if (!isset($data['commission_enable_customer'])) {
            $data['commission_enable_customer'] = 'disable';
        }

        if (!isset($data['commission_enable_product'])) {
            $data['commission_enable_product'] = 'disable';
        }

        if (!isset($data['commission_enable_member'])) {
            $data['commission_enable_member'] = 'disable';
        }

        if (!isset($data['commission_first_invoices'])) {
            $data['commission_first_invoices'] = '0';
        }

        $data['addedfrom']   = get_staff_user_id();
        $data['datecreated'] = date('Y-m-d H:i:s');

        if (!$this->check_format_date($data['from_date'])) {
            $data['from_date'] = to_sql_date($data['from_date']);
        }
        if (!$this->check_format_date($data['to_date'])) {
            $data['to_date'] = to_sql_date($data['to_date']);
        }

        $this->db->insert(db_prefix() . 'affiliate_programs', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return true;
        }
        return false;
    }

    /**
     * update affiliate program
     *
     * @param      Object   $data   The data
     *
     * @return     boolean
     */
    public function update_affiliate_program($data, $id)
    {
        $discount_ladder_setting = [];
        foreach ($data['discount_from_amount'] as $key => $value) {
            $node                                    = [];
            $node['discount_from_amount']            = $value;
            $node['discount_to_amount']              = $data['discount_to_amount'][$key];
            $node['discount_percent_enjoyed_ladder'] = $data['discount_percent_enjoyed_ladder'][$key];
            $discount_ladder_setting[]               = $node;
        }

        $discount_ladder_product_setting = [];
        foreach ($data['discount_from_amount_product'] as $key => $value) {
            $node                                                                    = [];
            $node['discount_from_amount_product']                                    = $value;
            $node['discount_to_amount_product']                                      = $data['discount_to_amount_product'][$key];
            $node['discount_percent_enjoyed_ladder_product']                         = $data['discount_percent_enjoyed_ladder_product'][$key];
            $discount_ladder_product_setting[$data['discount_ladder_product'][$key]] = $node;
        }

        $data['discount_ladder_setting']         = json_encode($discount_ladder_setting);
        $data['discount_ladder_product_setting'] = json_encode($discount_ladder_product_setting);

        unset($data['discount_from_amount']);
        unset($data['discount_to_amount']);
        unset($data['discount_percent_enjoyed_ladder']);

        unset($data['discount_from_amount_product']);
        unset($data['discount_to_amount_product']);
        unset($data['discount_percent_enjoyed_ladder_product']);
        unset($data['discount_ladder_product']);

        if (isset($data['discount_customers'])) {
            $data['discount_customers'] = implode(',', $data['discount_customers']);
        } else {
            $data['discount_customers'] = '';
        }

        if (isset($data['discount_customer_groups'])) {
            $data['discount_customer_groups'] = implode(',', $data['discount_customer_groups']);
        } else {
            $data['discount_customer_groups'] = '';
        }

        if (isset($data['discount_products'])) {
            $data['discount_products'] = implode(',', $data['discount_products']);
        } else {
            $data['discount_products'] = '';
        }

        if (isset($data['discount_product_groups'])) {
            $data['discount_product_groups'] = implode(',', $data['discount_product_groups']);
        } else {
            $data['discount_product_groups'] = '';
        }

        if (isset($data['discount_members'])) {
            $data['discount_members'] = implode(',', $data['discount_members']);
        } else {
            $data['discount_members'] = '';
        }

        if (isset($data['discount_member_groups'])) {
            $data['discount_member_groups'] = implode(',', $data['discount_member_groups']);
        } else {
            $data['discount_member_groups'] = '';
        }

        if (!isset($data['enable_discount'])) {
            $data['enable_discount'] = 'disable';
        }

        if (!isset($data['discount_enable_customer'])) {
            $data['discount_enable_customer'] = 'disable';
        }

        if (!isset($data['discount_enable_product'])) {
            $data['discount_enable_product'] = 'disable';
        }

        if (!isset($data['discount_enable_member'])) {
            $data['discount_enable_member'] = 'disable';
        }

        if (!isset($data['discount_first_invoices'])) {
            $data['discount_first_invoices'] = 'disable';
        }

        $commission_ladder_setting = [];
        foreach ($data['commission_from_amount'] as $key => $value) {
            $node                                      = [];
            $node['commission_from_amount']            = $value;
            $node['commission_to_amount']              = $data['commission_to_amount'][$key];
            $node['commission_percent_enjoyed_ladder'] = $data['commission_percent_enjoyed_ladder'][$key];
            $commission_ladder_setting[]               = $node;
        }

        $commission_ladder_product_setting = [];
        foreach ($data['commission_from_amount_product'] as $key => $value) {
            $node                                                                        = [];
            $node['commission_from_amount_product']                                      = $value;
            $node['commission_to_amount_product']                                        = $data['commission_to_amount_product'][$key];
            $node['commission_percent_enjoyed_ladder_product']                           = $data['commission_percent_enjoyed_ladder_product'][$key];
            $commission_ladder_product_setting[$data['commission_ladder_product'][$key]] = $node;
        }

        $data['commission_ladder_setting']         = json_encode($commission_ladder_setting);
        $data['commission_ladder_product_setting'] = json_encode($commission_ladder_product_setting);

        unset($data['commission_from_amount']);
        unset($data['commission_to_amount']);
        unset($data['commission_percent_enjoyed_ladder']);

        unset($data['commission_from_amount_product']);
        unset($data['commission_to_amount_product']);
        unset($data['commission_percent_enjoyed_ladder_product']);
        unset($data['commission_ladder_product']);

        if (isset($data['commission_customers'])) {
            $data['commission_customers'] = implode(',', $data['commission_customers']);
        } else {
            $data['commission_customers'] = '';
        }

        if (isset($data['commission_customer_groups'])) {
            $data['commission_customer_groups'] = implode(',', $data['commission_customer_groups']);
        } else {
            $data['commission_customer_groups'] = '';
        }

        if (isset($data['commission_products'])) {
            $data['commission_products'] = implode(',', $data['commission_products']);
        } else {
            $data['commission_products'] = '';
        }

        if (isset($data['commission_product_groups'])) {
            $data['commission_product_groups'] = implode(',', $data['commission_product_groups']);
        } else {
            $data['commission_product_groups'] = '';
        }

        if (isset($data['commission_members'])) {
            $data['commission_members'] = implode(',', $data['commission_members']);
        } else {
            $data['commission_members'] = '';
        }

        if (isset($data['commission_member_groups'])) {
            $data['commission_member_groups'] = implode(',', $data['commission_member_groups']);
        } else {
            $data['commission_member_groups'] = '';
        }

        if (!isset($data['enable_commission'])) {
            $data['enable_commission'] = 'disable';
        }

        if (!isset($data['commission_enable_customer'])) {
            $data['commission_enable_customer'] = 'disable';
        }

        if (!isset($data['commission_enable_product'])) {
            $data['commission_enable_product'] = 'disable';
        }

        if (!isset($data['commission_enable_member'])) {
            $data['commission_enable_member'] = 'disable';
        }

        if (!isset($data['commission_first_invoices'])) {
            $data['commission_first_invoices'] = '0';
        }

        if (!$this->check_format_date($data['from_date'])) {
            $data['from_date'] = to_sql_date($data['from_date']);
        }
        if (!$this->check_format_date($data['to_date'])) {
            $data['to_date'] = to_sql_date($data['to_date']);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_programs', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * check format date Y-m-d
     *
     * @param      String   $date   The date
     *
     * @return     boolean
     */
    public function check_format_date($date)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * commission view product
     * @param  integer $product_id
     * @param  string $affiliate_code
     * @return boolean
     */
    public function commission_view_product($data)
    {
        $member            = $this->get_member_by_code($data['affiliate_code']);
        $affiliate_program = $this->get_affiliate_program($data['program']);
        $date              = date('Y-m-d');
        $check             = true;
        if ($affiliate_program) {
            if (strtotime($affiliate_program->from_date) <= strtotime($date) && strtotime($date) <= strtotime($affiliate_program->to_date)) {

            }

            if ($affiliate_program->commission_products != '') {
                $commission_products = explode(',', $affiliate_program->commission_products);

                if (!in_array($data['product']->id, $commission_products)) {
                    $check = false;
                }
            }

            if ($affiliate_program->commission_product_groups != '') {
                $commission_product_groups = explode(',', $affiliate_program->commission_product_groups);

                if (!in_array($data['product']->group_id, $commission_product_groups)) {
                    $check = false;
                }
            }

            $this->db->where('program_id', $data['program']);
            $this->db->where('user_ip', $this->get_client_ip());
            $log = $this->db->get(db_prefix() . 'affiliate_logs')->row();
            if ($log) {
                $check = false;
            }

            if ($check) {
                $this->db->insert(db_prefix() . 'affiliate_logs', [
                    'program_id'  => $data['program'],
                    'description' => 'Customer view product',
                    'type'        => 'product_views',
                    'datecreated' => date('Y-m-d H:i:s'),
                    'user_ip'     => $this->get_client_ip(),
                    'link'        => $_SERVER['REQUEST_URI'],
                    'member_id'   => $member->id,
                ]);
                $insert_id = $this->db->insert_id();

                if ($insert_id) {
                    $affiliate_logs = $this->get_affiliate_logs('', ['type' => 'product_views', 'member_id' => $member->id, 'program_id' => $data['program']]);

                    $count = count($affiliate_logs);

                    $commission = 0;
                    if ($count >= $affiliate_program->commission_number_view) {
                        $division = floor($count / $affiliate_program->commission_number_view);

                        $commission = $division * $affiliate_program->commission_of_view;
                    }

                    if ($commission > 0) {
                        $this->db->where('affiliate_program_id', $affiliate_program->id);
                        $this->db->where('member_id', $member->id);
                        $this->db->where('status', 0);
                        $this->db->where('type', 'product_views');
                        $transactions = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
                        if ($transactions) {
                            $this->db->where('member_id', $member->id);
                            $this->db->update(db_prefix() . 'affiliate_transactions', [
                                'amount' => $commission,
                            ]);
                        } else {
                            $this->db->insert(db_prefix() . 'affiliate_transactions', [
                                'affiliate_program_id' => $affiliate_program->id,
                                'member_id'            => $member->id,
                                'status'               => 0,
                                'amount'               => $commission,
                                'type'                 => 'product_views',
                                'datecreated'          => date('Y-m-d H:i:s'),
                                'addedfrom'            => get_staff_user_id(),
                            ]);
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function commission_new_registrantion($member_id)
    {
        $member = $this->get_member($member_id);
        $date   = date('Y-m-d');

        $this->db->where('from_date <= "' . $date . '" and to_date >= "' . $date . '"');
        $this->db->where('enable_commission', 'enable');
        $this->db->where('commission_affiliate_type', '2');
        $this->db->order_by('priority', 'desc');
        $affiliate_program = $this->db->get(db_prefix() . 'affiliate_programs')->row();

        if ($affiliate_program) {
            $this->db->insert(db_prefix() . 'affiliate_logs', [
                'program_id'  => $affiliate_program->id,
                'description' => 'New registration',
                'type'        => 'new_registration',
                'datecreated' => date('Y-m-d H:i:s'),
                'member_id'   => $member->under_affiliate ? $member->under_affiliate : 0,
            ]);
            $insert_id = $this->db->insert_id();
            if ($insert_id) {
                $affiliate_logs = $this->get_affiliate_logs('', ['type' => 'new_registration', 'member_id' => $member->under_affiliate ? $member->under_affiliate : 0, 'program_id' => $affiliate_program->id]);

                $count = count($affiliate_logs);

                $commission = 0;
                if ($count >= $affiliate_program->commission_number_registration) {
                    $division = floor($count / $affiliate_program->commission_number_registration);

                    $commission = $division * $affiliate_program->commission_of_registration;
                }
                if ($commission > 0) {
                    $this->db->where('affiliate_program_id', $affiliate_program->id);
                    $this->db->where('member_id', $member->under_affiliate ? $member->under_affiliate : 0);
                    $this->db->where('status', 0);
                    $this->db->where('type', 'new_registration');
                    $transactions = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
                    if ($transactions) {
                        $this->db->where('id', $transactions->id);
                        $this->db->update(db_prefix() . 'affiliate_transactions', [
                            'amount' => $commission,
                        ]);
                    } else {
                        $this->db->insert(db_prefix() . 'affiliate_transactions', [
                            'affiliate_program_id' => $affiliate_program->id,
                            'member_id'            => $member->under_affiliate ? $member->under_affiliate : 0,
                            'status'               => 0,
                            'amount'               => $commission,
                            'type'                 => 'new_registration',
                            'datecreated'          => date('Y-m-d H:i:s'),
                            'addedfrom'            => get_staff_user_id(),
                        ]);
                    }
                }
                return true;
            }
        }

        return false;

    }

    /**
     * get member by code
     * @param  string $affiliate_code
     * @return object
     */
    public function get_member_by_code($affiliate_code)
    {
        $this->db->where('affiliate_code', $affiliate_code);
        return $this->db->get(db_prefix() . 'affiliate_users')->row();
    }

    /**
     * get affiliate program
     * @param  string $id
     * @param  array  $where
     * @return object
     */
    public function get_affiliate_program($id = '', $where = [])
    {
        if ($id != '') {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_programs')->row();
        }

        return $this->db->get(db_prefix() . 'affiliate_programs')->result_array();
    }

    /**
     * Function to get the client IP address
     * @return string
     */
    public function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    /**
     * get affiliate log for member
     * @param  integer $member_id   the member id
     * @return object
     */
    public function get_affiliate_log_for_member($member_id)
    {
        $this->db->select(db_prefix() . 'affiliate_logs.id, name, type, user_ip, link, description, ' . db_prefix() . 'affiliate_logs.datecreated');
        $this->db->where('member_id', $member_id);
        $this->db->join(db_prefix() . 'affiliate_programs', '' . db_prefix() . 'affiliate_programs.id = ' . db_prefix() . 'affiliate_logs.program_id', 'left');
        return $this->db->get(db_prefix() . 'affiliate_logs')->result_array();
    }

    /**
     * get logs
     * @param  integer $id
     * @param  array  $where
     * @return array
     */
    public function get_affiliate_logs($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_logs')->row();
        }
        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'affiliate_logs')->result_array();
    }

    /**
     * get transactions
     * @param  integer $id
     * @param  array  $where
     * @return array
     */
    public function get_transactions($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_transactions')->row();
        }
        $this->db->select(db_prefix() . 'affiliate_transactions.id, amount, comment, ' . db_prefix() . 'affiliate_transactions.datecreated, order_id, type, ' . db_prefix() . 'affiliate_transactions.addedfrom, ' . db_prefix() . 'affiliate_transactions.status, username, invoice_id, , ' . db_prefix() . 'invoices.hash as invoice_hash, ' . db_prefix() . 'affiliate_programs.name as program_name, total, member_id, ' . db_prefix() . 'affiliate_transactions.status as transaction_status, type');
        $this->db->order_by('id', 'desc');
        $this->db->join(db_prefix() . 'affiliate_users', '' . db_prefix() . 'affiliate_users.id = ' . db_prefix() . 'affiliate_transactions.member_id', 'left');
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'affiliate_transactions.invoice_id', 'left');
        $this->db->join(db_prefix() . 'affiliate_programs', '' . db_prefix() . 'affiliate_programs.id = ' . db_prefix() . 'affiliate_transactions.affiliate_program_id', 'left');

        return $this->db->get(db_prefix() . 'affiliate_transactions')->result_array();
    }

    /**
     * get withdraw requests
     * @param  integer $id
     * @param  array  $where
     * @return array
     */
    public function get_withdraw_requests($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_withdraws')->row();
        }
        $this->db->select(db_prefix() . 'affiliate_withdraws.id, total, ' . db_prefix() . 'affiliate_withdraws.datecreated, paymentmode, ' . db_prefix() . 'affiliate_withdraws.status, username, name');
        $this->db->order_by('id', 'desc');
        $this->db->join(db_prefix() . 'affiliate_users', '' . db_prefix() . 'affiliate_users.id = ' . db_prefix() . 'affiliate_withdraws.member_id', 'left');
        $this->db->join(db_prefix() . 'payment_modes', '' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'affiliate_withdraws.paymentmode', 'left');

        return $this->db->get(db_prefix() . 'affiliate_withdraws')->result_array();
    }

    /**
     * Add new withdraw
     * @param array $data
     * @return id or false
     */
    public function add_withdraw($data)
    {
        $data['datecreated'] = date('Y-m-d H:i:s');
        $data['total']       = str_replace(',', '', $data['total']);
        $data['member_id']   = get_affiliate_user_id();
        $data['status']      = 0;
        if (isset($data['transaction_ids'])) {
            $transaction_ids = explode(', ', $data['transaction_ids']);
            unset($data['transaction_ids']);
        }

        $this->db->insert(db_prefix() . 'affiliate_withdraws', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            if ($transaction_ids) {
                foreach ($transaction_ids as $value) {
                    $node                   = [];
                    $node['withdraw_id']    = $insert_id;
                    $node['transaction_id'] = $value;
                    $this->db->insert(db_prefix() . 'affiliate_withdraw_details', $node);

                    $this->db->where('id', $value);
                    $this->db->update(db_prefix() . 'affiliate_transactions', ['status' => 1]);
                }
            }
            return $insert_id;
        }

        return false;
    }

    /**
     * get withdraw detail
     * @param  integer $id
     * @return array
     */
    public function get_withdraw_detail($id)
    {
        $this->db->select('name,username, total, ' . db_prefix() . 'affiliate_withdraws.status as withdraw_status, ' . db_prefix() . 'affiliate_withdraws.datecreated as withdraw_datecreated, ' . db_prefix() . 'affiliate_withdraws.id as withdraw_id');
        $this->db->where(db_prefix() . 'affiliate_withdraws.id', $id);
        $this->db->join(db_prefix() . 'affiliate_users', '' . db_prefix() . 'affiliate_users.id = ' . db_prefix() . 'affiliate_withdraws.member_id', 'left');
        $this->db->join(db_prefix() . 'payment_modes', '' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'affiliate_withdraws.paymentmode', 'left');
        $withdraw = $this->db->get(db_prefix() . 'affiliate_withdraws')->row();

        if ($withdraw) {
            $this->db->where('withdraw_id', $id);
            $this->db->join(db_prefix() . 'affiliate_transactions', '' . db_prefix() . 'affiliate_transactions.id = ' . db_prefix() . 'affiliate_withdraw_details.transaction_id', 'left');
            $withdraw->transactions = $this->db->get(db_prefix() . 'affiliate_withdraw_details')->result_array();
        }

        return $withdraw;
    }

    /**
     * approve withdraw
     * @param  integer $id
     * @param  integer $status
     * @return boolean
     */
    public function approve_withdraw($id, $status)
    {
        $affectedRows = 0;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_withdraws', ['status' => $status]);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;

            $this->db->where('withdraw_id', $id);
            $transactions       = $this->db->get(db_prefix() . 'affiliate_withdraw_details')->result_array();
            $transaction_status = 0;
            if ($status == 1) {
                $transaction_status = 2;
            }
            foreach ($transactions as $value) {
                $this->db->where('id', $value['transaction_id']);
                $this->db->update(db_prefix() . 'affiliate_transactions', ['status' => $transaction_status]);
            }
        }

        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }

    /**
     * Get client object based on passed clientid if not passed clientid return array of all clients
     * @param  mixed $id    client id
     * @param  array  $where
     * @return mixed
     */
    public function get_my_customer($affiliate_code = '', $where = [])
    {
        $this->db->select('firstname, lastname, ' . db_prefix() . 'contacts.email as contact_email, ' . db_prefix() . 'contacts.phonenumber as contact_phonenumber, ' . implode(',', prefixed_table_fields_array(db_prefix() . 'clients')) . ',' . get_sql_select_client_company());

        $this->db->join(db_prefix() . 'countries', '' . db_prefix() . 'countries.country_id = ' . db_prefix() . 'clients.country', 'left');
        $this->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.userid = ' . db_prefix() . 'clients.userid AND is_primary = 1', 'left');

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        if ($affiliate_code != '') {
            $this->db->where(db_prefix() . 'clients.affiliate_code', $affiliate_code);
        }

        $this->db->order_by('company', 'asc');

        return $this->db->get(db_prefix() . 'clients')->result_array();
    }

    /**
     * Insert new order to database
     * @param array $data order data
     * @return mixed - false if not insert, order ID if success
     */
    public function add_order($data, $expense = false)
    {
        if (isset($data['item_select'])) {
            unset($data['item_select']);
        }
        if (isset($data['description'])) {
            unset($data['description']);
        }
        if (isset($data['long_description'])) {
            unset($data['long_description']);
        }
        if (isset($data['quantity'])) {
            unset($data['quantity']);
        }
        if (isset($data['unit'])) {
            unset($data['unit']);
        }
        if (isset($data['rate'])) {
            unset($data['rate']);
        }
        if (isset($data['taxname'])) {
            unset($data['taxname']);
        }
        $order_number        = get_option('order_number');
        $data['order_code']  = '#' . get_affiliate_user_code() . str_pad($order_number, 5, '0', STR_PAD_LEFT);
        $data['datecreated'] = date('Y-m-d H:i:s');

        $data['member_id'] = get_affiliate_user_id();

        $data['hash'] = app_generate_hash();

        $items = [];
        if (isset($data['newitems'])) {
            $items = $data['newitems'];
            unset($data['newitems']);
        }

        $data = $this->map_shipping_columns($data, $expense);

        if (isset($data['shipping_street'])) {
            $data['shipping_street'] = trim($data['shipping_street']);
            $data['shipping_street'] = nl2br($data['shipping_street']);
        }

        $data['billing_street'] = trim($data['billing_street']);
        $data['billing_street'] = nl2br($data['billing_street']);

        $this->db->insert(db_prefix() . 'affiliate_orders', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            update_option('order_number', $order_number + 1);

            foreach ($items as $key => $item) {
                $item['item_id'] = 0;
                if ($itemid = $this->add_new_order_item($item, $insert_id)) {
                    $this->insert_order_item_tax($itemid, $item);
                }
            }

            return $insert_id;
        }

        return false;
    }

    /**
     * Update invoice data
     * @param  array $data invoice data
     * @param  mixed $id   invoiceid
     * @return boolean
     */
    public function update_order($data, $id)
    {
        $affectedRows = 0;
        if (isset($data['item_select'])) {
            unset($data['item_select']);
        }
        if (isset($data['description'])) {
            unset($data['description']);
        }
        if (isset($data['long_description'])) {
            unset($data['long_description']);
        }
        if (isset($data['quantity'])) {
            unset($data['quantity']);
        }
        if (isset($data['unit'])) {
            unset($data['unit']);
        }
        if (isset($data['rate'])) {
            unset($data['rate']);
        }
        if (isset($data['taxname'])) {
            unset($data['taxname']);
        }
        if (isset($data['merge_current_invoice'])) {
            unset($data['merge_current_invoice']);
        }

        $items = [];
        if (isset($data['items'])) {
            $items = $data['items'];
            unset($data['items']);
        }

        $newitems = [];
        if (isset($data['newitems'])) {
            $newitems = $data['newitems'];
            unset($data['newitems']);
        }

        $data = $this->map_shipping_columns($data);

        $data['billing_street']  = trim($data['billing_street']);
        $data['shipping_street'] = trim($data['shipping_street']);

        $data['billing_street']  = nl2br($data['billing_street']);
        $data['shipping_street'] = nl2br($data['shipping_street']);

        // Delete items checked to be removed from database
        if (isset($data['removed_items'])) {
            foreach ($data['removed_items'] as $remove_item_id) {
                $this->db->where('order_item_id', $remove_item_id);
                $this->db->delete(db_prefix() . 'affiliate_order_item_taxs');

                $this->db->where('id', $remove_item_id);
                $this->db->delete(db_prefix() . 'affiliate_order_items');

                $affectedRows++;
            }
            unset($data['removed_items']);
        }

        if (isset($data['isedit'])) {
            unset($data['isedit']);
        }
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_orders', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if (count($items) > 0) {
            foreach ($items as $key => $item) {
                $update = [
                    'item_order'       => $item['order'],
                    'description'      => $item['description'],
                    'long_description' => nl2br($item['long_description']),
                    'rate'             => number_format($item['rate'], get_decimal_places(), '.', ''),
                    'qty'              => $item['qty'],
                    'unit'             => $item['unit'],
                ];

                $this->db->where('id', $item['itemid']);
                $this->db->update(db_prefix() . 'affiliate_order_items', $update);

                if (!isset($item['taxname']) || (isset($item['taxname']) && count($item['taxname']) == 0)) {
                    $this->db->where('order_item_id', $item['itemid']);
                    $this->db->delete(db_prefix() . 'affiliate_order_item_taxs');
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                } else {
                    $this->db->where('order_item_id', $item['itemid']);
                    $item_taxes = $this->db->get(db_prefix() . 'affiliate_order_item_taxs')->result_array();

                    $_item_taxes_names = [];
                    foreach ($item_taxes as $_item_tax) {
                        array_push($_item_taxes_names, $_item_tax['taxname']);
                    }
                    $i = 0;
                    foreach ($_item_taxes_names as $_item_tax) {
                        if (!in_array($_item_tax, $item['taxname'])) {
                            $this->db->where('id', $item_taxes[$i]['id'])
                                ->delete(db_prefix() . 'affiliate_order_item_taxs');
                            if ($this->db->affected_rows() > 0) {
                                $affectedRows++;
                            }
                        }
                        $i++;
                    }

                    foreach ($item['taxname'] as $taxname) {
                        if ($taxname != '') {
                            $tax_array = explode('|', $taxname);
                            if (isset($tax_array[0]) && isset($tax_array[1])) {
                                $tax_name = trim($tax_array[0]);
                                $tax_rate = trim($tax_array[1]);
                                if (total_rows(db_prefix() . 'affiliate_order_item_taxs', [
                                    'order_item_id' => $item['itemid'],
                                    'taxrate'       => $tax_rate,
                                    'taxname'       => $tax_name,
                                ]) == 0) {
                                    $this->db->insert(db_prefix() . 'affiliate_order_item_taxs', [
                                        'order_id'      => $id,
                                        'order_item_id' => $item['itemid'],
                                        'taxrate'       => $tax_rate,
                                        'taxname'       => $tax_name,
                                    ]);
                                    $affectedRows++;
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($newitems as $key => $item) {
            $this->db->where('order_id', $id);
            $original_item = $this->db->insert(db_prefix() . 'affiliate_order_items', [
                'description'      => $item['description'],
                'long_description' => nl2br($item['long_description']),
                'qty'              => $item['qty'],
                'rate'             => number_format($item['rate'], get_decimal_places(), '.', ''),
                'order_id'         => $id,
                'item_order'       => $item['order'],
                'unit'             => $item['unit'],
            ]);
            $new_item_added = $this->db->insert_id();
            if ($new_item_added) {
                foreach ($item['taxname'] as $taxname) {
                    if ($taxname != '') {
                        $tax_array = explode('|', $taxname);
                        if (isset($tax_array[0]) && isset($tax_array[1])) {
                            $tax_name = trim($tax_array[0]);
                            $tax_rate = trim($tax_array[1]);
                            if (total_rows(db_prefix() . 'affiliate_order_item_taxs', [
                                'order_item_id' => $new_item_added,
                                'taxrate'       => $tax_rate,
                                'taxname'       => $tax_name,
                            ]) == 0) {
                                $this->db->insert(db_prefix() . 'affiliate_order_item_taxs', [
                                    'order_id'      => $id,
                                    'order_item_id' => $new_item_added,
                                    'taxrate'       => $tax_rate,
                                    'taxname'       => $tax_name,
                                ]);
                                $affectedRows++;
                            }
                        }
                    }
                }

                $affectedRows++;
            }
        }

        if ($affectedRows > 0) {
            hooks()->do_action('after_invoice_updated', $id);

            return true;
        }

        return false;
    }

    /**
     * map shipping columns
     * @param  array  $data
     * @param  boolean $expense
     * @return array
     */
    private function map_shipping_columns($data, $expense = false)
    {
        if (!isset($data['include_shipping'])) {
            foreach ($this->shipping_fields as $_s_field) {
                if (isset($data[$_s_field])) {
                    $data[$_s_field] = null;
                }
            }
            $data['show_shipping_on_invoice'] = 1;
            $data['include_shipping']         = 0;
        } else {
            // We dont need to overwrite to 1 unless its coming from the main function add
            if (!DEFINED('CRON') && $expense == false) {
                $data['include_shipping'] = 1;
                // set by default for the next time to be checked
                if (isset($data['show_shipping_on_invoice']) && ($data['show_shipping_on_invoice'] == 1 || $data['show_shipping_on_invoice'] == 'on')) {
                    $data['show_shipping_on_invoice'] = 1;
                } else {
                    $data['show_shipping_on_invoice'] = 0;
                }
            }
            // else its just like they are passed
        }

        return $data;
    }

    /**
     * Get client object based on passed clientid if not passed clientid return array of all clients
     * @param  mixed $id    client id
     * @param  array  $where
     * @return mixed
     */
    public function get_my_order($member_id = '', $where = [])
    {
        $this->db->select(db_prefix() . 'affiliate_orders.id as order_id,order_code, company, ' . db_prefix() . 'affiliate_orders.datecreated as datecreated, ' . db_prefix() . 'affiliate_orders.status as status, approve_status, ' . db_prefix() . 'affiliate_orders.invoice_id as invoice_id, total,subtotal');

        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'affiliate_orders.customer', 'left');

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        if ($member_id != '') {
            $this->db->where(db_prefix() . 'affiliate_orders.member_id', $member_id);
        }

        $this->db->order_by('datecreated', 'desc');

        return $this->db->get(db_prefix() . 'affiliate_orders')->result_array();
    }

    /**
     * Add new order item do database
     * @param array $item     item from $_POST
     * @param mixed $order_id   order id
     * @return item id
     */
    public function add_new_order_item($item, $order_id)
    {
        $custom_fields = false;

        if (isset($item['custom_fields'])) {
            $custom_fields = $item['custom_fields'];
        }

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
     * get order detail
     * @param  integer $id
     * @return mixed
     */
    public function get_order_detail($id)
    {

        $this->db->select('*, ' . db_prefix() . 'affiliate_orders.datecreated as order_datecreated');
        $this->db->where('id', $id);
        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'affiliate_orders.customer', 'left');
        $order = $this->db->get(db_prefix() . 'affiliate_orders')->row();

        if ($order) {
            $this->db->where('order_id', $order->id);
            $order->items = $this->db->get(db_prefix() . 'affiliate_order_items')->result_array();
            foreach ($order->items as $key => $value) {
                $this->db->where('order_item_id', $value['id']);
                $order->items[$key]['taxs'] = $this->db->get(db_prefix() . 'affiliate_order_item_taxs')->result_array();
            }
        }

        return $order;
    }

    /**
     * delete order
     *
     * @param      integer  $id     The identifier
     * @return boolean
     */
    public function delete_order($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_orders');

        if ($this->db->affected_rows() > 0) {
            $this->db->where('order_id', $id);
            $this->db->delete(db_prefix() . 'affiliate_order_items');

            $this->db->where('order_id', $id);
            $this->db->delete(db_prefix() . 'affiliate_order_item_taxs');

            return true;
        }
        return false;
    }

    /**
     * approve order
     * @param  integer $id the order id
     * @param  integer $status
     * @return boolean
     */
    public function approve_order($id, $status)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_orders', ['approve_status' => $status]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * create invoice by order
     * @param  integer $id the order id
     * @return boolean
     */
    public function create_invoice_by_order($id)
    {
        $order = $this->get_order_detail($id);
        $items = [];

        $this->load->model('invoices_model');
        $this->load->model('credit_notes_model');
        $count    = 0;
        $newitems = [];
        foreach ($order->items as $value) {
            $taxname = [];
            foreach ($value['taxs'] as $tax) {
                $taxname[] = $tax['taxname'] . '|' . $tax['taxrate'];
            }
            array_push($newitems, array('order' => $value['item_order'], 'description' => $value['description'], 'long_description' => $value['long_description'], 'qty' => $value['qty'], 'unit' => $value['unit'], 'rate' => $value['rate'], 'taxname' => $taxname));
            $count++;
        }

        $__number        = get_option('next_invoice_number');
        $_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
        $this->db->where('isdefault', 1);
        $curreny = $this->db->get(db_prefix() . 'currencies')->row()->id;

        if ($order) {
            $data['clientid']                 = $order->customer;
            $data['billing_street']           = $order->billing_street;
            $data['billing_city']             = $order->billing_city;
            $data['billing_state']            = $order->billing_state;
            $data['billing_zip']              = $order->billing_zip;
            $data['billing_country']          = $order->billing_country;
            $data['include_shipping']         = 1;
            $data['show_shipping_on_invoice'] = 1;
            $data['shipping_street']          = $order->shipping_street;
            $data['shipping_city']            = $order->shipping_city;
            $data['shipping_state']           = $order->shipping_state;
            $data['shipping_zip']             = $order->shipping_zip;
            $date_format                      = get_option('dateformat');
            $date_format                      = explode('|', $date_format);
            $date_format                      = $date_format[0];
            $data['date']                     = date($date_format);
            $data['duedate']                  = date($date_format);

            $data['currency']            = $curreny;
            $data['number']              = $_invoice_number;
            $data['total']               = $order->total;
            $data['subtotal']            = $order->subtotal;
            $data['affiliate_member_id'] = $order->member_id;

            $payment_model_list = [];
            if ($order->payment_mode != '') {
                $payment_model_list = explode(',', $order->payment_mode);
            }
            $data["allowed_payment_modes"] = $payment_model_list;
            if (isset($order->shipping)) {
                array_push($newitems, array('order' => $count + 1, 'description' => _l('shipping'), 'long_description' => "", 'qty' => 1, 'unit' => "", 'rate' => $order->shipping, 'taxname' => array()));
            }
            $data['newitems'] = $newitems;

            $invoice_id = $this->invoices_model->add($data);

            if ($invoice_id) {
                $this->db->where('id', $id);
                $curreny = $this->db->update(db_prefix() . 'affiliate_orders', ['invoice_id' => $invoice_id]);
            }

            return $invoice_id;
        }
        return false;
    }

    /**
     * apply affiliate program.
     *
     * @param      integer   $payment_id  The payment identifier
     *
     * @return     boolean
     */
    public function apply_affiliate_program($payment_id)
    {
        $this->load->model('payments_model');
        $this->load->model('invoices_model');
        $this->load->model('invoice_items_model');

        $payment      = $this->payments_model->get($payment_id);
        $affectedRows = 0;
        $invoices     = $this->invoices_model->get($payment->invoiceid);
        $count        = 0;
        $salesperson  = '';
        $type         = '';
        if ($invoices->affiliate_member_id) {
            $salesperson = $invoices->affiliate_member_id;
        } else {
            return false;
        }

        if ($invoices) {
            $affiliate_program = $this->get_affiliate_program_by_member($salesperson, $invoices);
            if ($affiliate_program) {
                if ($affiliate_program->enable_commission == 'enable') {
                    $type .= _l('commission');
                    $profit_percent = 1;
                    $profit         = 0;
                    if ($affiliate_program->commission_amount_to_calculate == 'profit') {
                        foreach ($invoices->items as $value) {
                            $item = $this->get_item_by_name($value['description']);
                            if ($item) {
                                $profit += ($value['rate'] - $item->purchase_price) * $value['qty'];
                            }
                        }

                        $profit_percent = $profit / $invoices->total;
                    }

                    if ($affiliate_program->commission_policy_type == '2') {
                        if ($affiliate_program->commission_type == 'percentage') {
                            $payments_amount = ($payment->amount - round(($invoices->total_tax * ($payment->amount / $invoices->total)), 2)) * $profit_percent;
                            if ($affiliate_program->commission_first_invoices == '1') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $payment->invoiceid, $affiliate_program->commission_number_first_invoices, $affiliate_program);
                                if (in_array($payment->invoiceid, $list_first_invoices)) {
                                    $count += $payments_amount * ($affiliate_program->commission_percent_first_invoices / 100);
                                } else {
                                    $count += $payments_amount * ($affiliate_program->commission_percent_enjoyed / 100);
                                }
                            } else {
                                $count += $payments_amount * ($affiliate_program->commission_percent_enjoyed / 100);
                            }
                        } else {
                            if ($affiliate_program->commission_first_invoices == '1') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $payment->invoiceid, $affiliate_program->commission_number_first_invoices, $affiliate_program);

                                if (in_array($payment->invoiceid, $list_first_invoices)) {
                                    $count += $affiliate_program->commission_percent_first_invoices;
                                } else {
                                    $count += $affiliate_program->commission_percent_enjoyed;
                                }
                            } else {
                                $count += $affiliate_program->commission_percent_enjoyed;
                            }
                        }
                    } elseif ($affiliate_program->commission_policy_type == '3') {
                        $product_setting = json_decode($affiliate_program->commission_product_setting);
                        if ($invoices->items) {
                            $payments_amount = ($payment->amount - round(($invoices->total_tax * ($payment->amount / $invoices->total)), 2)) * $profit_percent;

                            foreach ($invoices->items as $item) {
                                $item_id = $this->get_item_id_by_name($item['description']);
                                $it      = '';
                                $percent = 0;
                                if ($item_id != '') {
                                    $it = $this->get_item_by_name($item['description']);

                                    if ($affiliate_program->commission_amount_to_calculate == 'profit') {
                                        $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                        if ($profit > 0) {
                                            $percent = $item_amount / $profit;
                                        } else {
                                            $percent = 0;
                                        }
                                    } else {
                                        $item_amount = ($item['qty'] * $item['rate']);
                                        $percent     = $item_amount / $payments_amount;
                                    }
                                }

                                foreach ($product_setting as $value) {
                                    $group_setting       = explode('|', $value[0]);
                                    $item_setting        = explode('|', $value[1]);
                                    $from_number_setting = $value[2];
                                    $to_number_setting   = $value[3];
                                    $percent_setting     = $value[4];

                                    $check = true;
                                    if ($item_id != '') {
                                        if ($it != '') {
                                            if ($value[0] != '' && !in_array($it->group_id, $group_setting)) {
                                                $check = false;
                                            }
                                        } else {
                                            if ($value[0] != '') {
                                                $check = false;
                                            }
                                        }

                                        if ($value[1] != '' && !in_array($item_id, $item_setting)) {
                                            $check = false;
                                        }
                                    } else {
                                        if ($value[1] != '' || $value[0] != '') {
                                            $check = false;
                                        }
                                    }

                                    if (($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)) {
                                        $check = false;
                                    }

                                    if ($check == true) {
                                        if ($affiliate_program->commission_type == 'percentage') {
                                            $count += ($percent * $payments_amount) * ($percent_setting / 100);
                                        } else {
                                            $count += $percent_setting;
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($affiliate_program->commission_policy_type == '1') {
                        $total_payments  = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_setting  = json_decode($affiliate_program->commission_ladder_setting);
                        $amount          = $payments_amount;
                        if ($affiliate_program->commission_type == 'percentage') {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->commission_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $to_amount = str_replace(',', '', $value->commission_to_amount);
                                    if ($to_amount == '') {
                                        $count += $amount * ($value->commission_percent_enjoyed_ladder / 100);
                                        $amount = 0;
                                    } elseif ($from_amount == '') {
                                        $count += $amount * ($value->commission_percent_enjoyed_ladder / 100);

                                        $amount = $amount - $to_amount;
                                    } else {
                                        if ($payments_amount > $to_amount) {
                                            $count += ($to_amount - $from_amount) * ($value->commission_percent_enjoyed_ladder / 100);
                                            if ($key == 0) {
                                                $amount = $amount - $to_amount;
                                            } else {
                                                $amount = $amount - ($to_amount - $from_amount);
                                            }
                                        } else {
                                            if ($key == 0) {
                                                $count += ($amount - $from_amount) * ($value->commission_percent_enjoyed_ladder / 100);
                                            } else {
                                                $count += $amount * ($value->commission_percent_enjoyed_ladder / 100);
                                            }
                                            $amount = 0;
                                        }
                                    }
                                } else {
                                    break;
                                }
                            }
                        } else {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->commission_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $count += $value->commission_percent_enjoyed_ladder;
                                } else {
                                    break;
                                }
                            }
                        }
                    } elseif ($affiliate_program->commission_policy_type == '4') {
                        $total_payments         = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount        = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_product_setting = json_decode($affiliate_program->commission_ladder_product_setting, true);
                        foreach ($invoices->items as $item) {
                            $it = $this->get_item_by_name($item['description']);
                            if ($it) {
                                $percent = 0;
                                if ($affiliate_program->commission_amount_to_calculate == 'profit') {
                                    $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                    if ($profit > 0) {
                                        $percent = $item_amount / $profit;
                                    } else {
                                        $percent = 0;
                                    }
                                } else {
                                    $item_amount = ($item['qty'] * $item['rate']);
                                    $percent     = $item_amount / $payments_amount;
                                }
                                $item_amount = $payments_amount * $percent;
                                $amount      = $item_amount;

                                if ($affiliate_program->commission_type == 'percentage') {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['commission_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $to_amount = str_replace(',', '', $value['commission_to_amount_product'][$k]);

                                                    if ($to_amount == '') {
                                                        $count += $amount * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = 0;
                                                    } elseif ($from_amount == '') {
                                                        $count += $amount * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = $amount - $to_amount;
                                                    } else {
                                                        if ($item_amount > $to_amount) {
                                                            $count += ($to_amount - $from_amount) * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);
                                                            if ($k == 0) {
                                                                $amount = $amount - $to_amount;
                                                            } else {
                                                                $amount = $amount - ($to_amount - $from_amount);
                                                            }
                                                        } else {
                                                            if ($k == 0) {
                                                                $count += ($amount - $from_amount) * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);
                                                            } else {
                                                                $count += $amount * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);
                                                            }
                                                            $amount = 0;
                                                        }
                                                    }
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['commission_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $to_amount = str_replace(',', '', $value['commission_to_amount_product'][$k]);
                                                    $count += $value['commission_percent_enjoyed_ladder_product'][$k];
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if ($affiliate_program->enable_discount == 'enable') {

                    if ($type != '') {
                        $type .= ', ' . _l('discount');
                    } else {
                        $type .= _l('discount');
                    }
                    $profit_percent = 1;
                    $profit         = 0;
                    if ($affiliate_program->discount_amount_to_calculate == 'profit') {
                        foreach ($invoices->items as $value) {
                            $item = $this->get_item_by_name($value['description']);
                            if ($item) {
                                $profit += ($value['rate'] - $item->purchase_price) * $value['qty'];
                            }
                        }

                        $profit_percent = $profit / $invoices->total;
                    }

                    if ($affiliate_program->discount_policy_type == '2') {
                        if ($affiliate_program->discount_type == 'percentage') {
                            $payments_amount = ($payment->amount - round(($invoices->total_tax * ($payment->amount / $invoices->total)), 2)) * $profit_percent;

                            if ($affiliate_program->discount_first_invoices == 'enable') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $payment->invoiceid, $affiliate_program->discount_number_first_invoices, $affiliate_program, 1);

                                if (in_array($payment->invoiceid, $list_first_invoices)) {
                                    $count += $payments_amount * ($affiliate_program->discount_percent_first_invoices / 100);
                                } else {
                                    $count += $payments_amount * ($affiliate_program->discount_percent_enjoyed / 100);
                                }
                            } else {
                                $count += $payments_amount * ($affiliate_program->discount_percent_enjoyed / 100);
                            }
                        } else {
                            if ($affiliate_program->discount_first_invoices == 'enable') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $payment->invoiceid, $affiliate_program->discount_number_first_invoices, $affiliate_program, 1);
                                if (in_array($payment->invoiceid, $list_first_invoices)) {
                                    $count += $affiliate_program->discount_percent_first_invoices;
                                } else {
                                    $count += $affiliate_program->discount_percent_enjoyed;
                                }
                            } else {
                                $count += $affiliate_program->discount_percent_enjoyed;
                            }
                        }
                    } elseif ($affiliate_program->discount_policy_type == '3') {
                        $product_setting = json_decode($affiliate_program->discount_product_setting);
                        if ($invoices->items) {
                            $payments_amount = ($payment->amount - round(($invoices->total_tax * ($payment->amount / $invoices->total)), 2)) * $profit_percent;

                            foreach ($invoices->items as $item) {
                                $item_id = $this->get_item_id_by_name($item['description']);
                                $it      = '';
                                $percent = 0;
                                if ($item_id != '') {
                                    $it = $this->get_item_by_name($item['description']);

                                    if ($affiliate_program->discount_amount_to_calculate == 'profit') {
                                        $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                        if ($profit > 0) {
                                            $percent = $item_amount / $profit;
                                        } else {
                                            $percent = 0;
                                        }
                                    } else {
                                        $item_amount = ($item['qty'] * $item['rate']);
                                        $percent     = $item_amount / $payments_amount;
                                    }
                                }

                                foreach ($product_setting as $value) {
                                    $group_setting       = explode('|', $value[0]);
                                    $item_setting        = explode('|', $value[1]);
                                    $from_number_setting = $value[2];
                                    $to_number_setting   = $value[3];
                                    $percent_setting     = $value[4];

                                    $check = true;
                                    if ($item_id != '') {
                                        if ($it != '') {
                                            if ($value[0] != '' && !in_array($it->group_id, $group_setting)) {
                                                $check = false;
                                            }
                                        } else {
                                            if ($value[0] != '') {
                                                $check = false;
                                            }
                                        }

                                        if ($value[1] != '' && !in_array($item_id, $item_setting)) {
                                            $check = false;
                                        }
                                    } else {
                                        if ($value[1] != '' || $value[0] != '') {
                                            $check = false;
                                        }
                                    }

                                    if (($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)) {
                                        $check = false;
                                    }

                                    if ($check == true) {
                                        if ($affiliate_program->discount_type == 'percentage') {
                                            $count += ($percent * $payments_amount) * ($percent_setting / 100);
                                        } else {
                                            $count += $percent_setting;
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($affiliate_program->discount_policy_type == '1') {
                        $total_payments  = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_setting  = json_decode($affiliate_program->discount_ladder_setting);

                        $amount = $payments_amount;
                        if ($affiliate_program->discount_type == 'percentage') {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->discount_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $to_amount = str_replace(',', '', $value->discount_to_amount);
                                    if ($to_amount == '') {
                                        $count += $amount * ($value->discount_percent_enjoyed_ladder / 100);
                                        $amount = 0;
                                    } elseif ($from_amount == '') {
                                        $count += $amount * ($value->discount_percent_enjoyed_ladder / 100);

                                        $amount = $amount - $to_amount;
                                    } else {
                                        if ($payments_amount > $to_amount) {
                                            $count += ($to_amount - $from_amount) * ($value->discount_percent_enjoyed_ladder / 100);
                                            if ($key == 0) {
                                                $amount = $amount - $to_amount;
                                            } else {
                                                $amount = $amount - ($to_amount - $from_amount);
                                            }
                                        } else {
                                            if ($key == 0) {
                                                $count += ($amount - $from_amount) * ($value->discount_percent_enjoyed_ladder / 100);
                                            } else {
                                                $count += $amount * ($value->discount_percent_enjoyed_ladder / 100);
                                            }
                                            $amount = 0;
                                        }
                                    }
                                } else {
                                    break;
                                }
                            }
                        } else {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->discount_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $count += $value->discount_percent_enjoyed_ladder;
                                } else {
                                    break;
                                }
                            }
                        }
                    } elseif ($affiliate_program->discount_policy_type == '4') {
                        $total_payments         = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount        = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_product_setting = json_decode($affiliate_program->discount_ladder_product_setting, true);
                        foreach ($invoices->items as $item) {
                            $it = $this->get_item_by_name($item['description']);
                            if ($it) {
                                $percent = 0;
                                if ($affiliate_program->discount_amount_to_calculate == 'profit') {
                                    $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                    if ($profit > 0) {
                                        $percent = $item_amount / $profit;
                                    } else {
                                        $percent = 0;
                                    }
                                } else {
                                    $item_amount = ($item['qty'] * $item['rate']);
                                    $percent     = $item_amount / $payments_amount;
                                }
                                $item_amount = $payments_amount * $percent;
                                $amount      = $item_amount;

                                if ($affiliate_program->discount_type == 'percentage') {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['discount_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $to_amount = str_replace(',', '', $value['discount_to_amount_product'][$k]);

                                                    if ($to_amount == '') {
                                                        $count += $amount * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = 0;
                                                    } elseif ($from_amount == '') {
                                                        $count += $amount * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = $amount - $to_amount;
                                                    } else {
                                                        if ($item_amount > $to_amount) {
                                                            $count += ($to_amount - $from_amount) * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);
                                                            if ($k == 0) {
                                                                $amount = $amount - $to_amount;
                                                            } else {
                                                                $amount = $amount - ($to_amount - $from_amount);
                                                            }
                                                        } else {
                                                            if ($k == 0) {
                                                                $count += ($amount - $from_amount) * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);
                                                            } else {
                                                                $count += $amount * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);
                                                            }
                                                            $amount = 0;
                                                        }
                                                    }
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['discount_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $count += $value['discount_percent_enjoyed_ladder_product'][$k];
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($count > 0) {

                $data               = [];
                $data[$salesperson] = $count;
                $list_isset         = [];
                $list_isset[]       = $salesperson;
                do {
                    foreach ($data as $k => $count) {
                        $this->db->where('invoice_id', $invoices->id);
                        $this->db->where('member_id', $k);
                        $transaction = $this->db->get(db_prefix() . 'affiliate_transactions')->row();

                        if ($transaction) {
                            if ($affiliate_program->commission_policy_type == '2' || $affiliate_program->commission_policy_type == '3') {
                                $count = $count + $transaction->amount;
                            }

                            $this->db->where('id', $transaction->id);
                            $this->db->update(db_prefix() . 'affiliate_transactions', ['amount' => round($count, 2), 'datecreated' => date('Y-m-d H:i:s')]);
                            if ($this->db->affected_rows() > 0) {
                                $affectedRows++;
                            }
                        } else {
                            $node                         = [];
                            $node['member_id']            = $k;
                            $node['invoice_id']           = $invoices->id;
                            $node['amount']               = round($count, 2);
                            $node['datecreated']          = date('Y-m-d H:i:s');
                            $node['type']                 = $type;
                            $node['addedfrom']            = get_staff_user_id();
                            $node['status']               = 0;
                            $node['affiliate_program_id'] = $affiliate_program->id;
                            $this->db->insert(db_prefix() . 'affiliate_transactions', $node);
                            $insert_id = $this->db->insert_id();

                            if ($insert_id) {
                                $affectedRows++;
                            }
                        }
                        unset($data[$k]);
                    }
                } while (count($data) > 0);
            }
        }
        if ($affectedRows > 0) {
            $this->db->insert(db_prefix() . 'affiliate_logs', [
                'program_id'  => $affiliate_program->id,
                'description' => $type,
                'type'        => $type,
                'datecreated' => date('Y-m-d H:i:s'),
                'member_id'   => get_staff_user_id(),
            ]);
            return true;
        }

        return false;
    }

    /**
     * Gets the commission policy by staff.
     *
     * @param      string  $staff  The staff
     *
     * @return     object  The commission policy by staff.
     */
    public function get_affiliate_program_by_member($member_id, $invoice)
    {
        $client_id = 0;
        if ($invoice->clientid) {
            $client_id = $invoice->clientid;
        }

        if ($member_id == '') {
            $member_id    = 0;
            $member_group = 0;
        } else {
            $member = $this->get_member($member_id);
            
            if ($member && $member->group != '') {
                $member_group = $member->group;
            } else {
                $member_group = 0;
            }
        }
        $date = date('Y-m-d');

        $this->db->select('userid, company, (SELECT GROUP_CONCAT(groupid SEPARATOR ",") FROM ' . db_prefix() . 'customer_groups WHERE customer_id = ' . db_prefix() . 'clients.userid) as customerGroups');
        $this->db->where('userid', $client_id);
        $client = $this->db->get(db_prefix() . 'clients')->row();

        $where_customer = "IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                IF(commission_enable_customer = 'enable',
                                    IF(commission_customers IS NOT NULL, IF(commission_customers != \"\",find_in_set(" . $client_id . ",commission_customers), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_discount_customer = "IF(enable_discount = 'enable',
                                IF(discount_enable_customer = 'enable',
                                    IF(discount_customers IS NOT NULL, IF(discount_customers != \"\",find_in_set(" . $client_id . ",discount_customers), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_customer_group          = '';
        $where_discount_customer_group = '';
        if ($client->customerGroups != '') {
            foreach (explode(',', $client->customerGroups) as $key => $value) {
                if ($where_customer_group != '') {
                    $where_customer_group .= " OR IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                                    IF(commission_enable_customer = 'enable',
                                                        IF(commission_customer_groups IS NOT NULL, IF(commission_customer_groups != \"\",find_in_set(" . $value . ",commission_customer_groups), 1=1), 1=1)
                                                        , 1=1)
                                                    ,1=0)";
                } else {
                    $where_customer_group = "IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                                IF(commission_enable_customer = 'enable',
                                                    IF(commission_customer_groups IS NOT NULL, IF(commission_customer_groups != \"\",find_in_set(" . $value . ",commission_customer_groups), 1=1), 1=1)
                                                    , 1=1)
                                                ,1=0)";
                }

                if ($where_discount_customer_group != '') {
                    $where_discount_customer_group .= " OR IF(enable_discount = 'enable',
                                                    IF(discount_enable_customer = 'enable',
                                                        IF(discount_customer_groups IS NOT NULL, IF(discount_customer_groups != \"\",find_in_set(" . $value . ",discount_customer_groups), 1=1), 1=1)
                                                        , 1=1)
                                                    ,1=0)";
                } else {
                    $where_discount_customer_group = "IF(enable_discount = 'enable',
                                                IF(discount_enable_customer = 'enable',
                                                    IF(discount_customer_groups IS NOT NULL, IF(discount_customer_groups != \"\",find_in_set(" . $value . ",discount_customer_groups), 1=1), 1=1)
                                                    , 1=1)
                                                ,1=0)";
                }
            }

            if ($where_discount_customer_group != '') {
                $where_discount_customer_group = ' and (' . $where_discount_customer_group . ')';
            }
            if ($where_customer_group != '') {
                $where_customer_group = ' and (' . $where_customer_group . ')';
            }
        }

        $where_product                = '';
        $where_product_group          = '';
        $where_discount_product       = '';
        $where_discount_product_group = '';
        if ($invoice->items) {
            foreach ($invoice->items as $key => $value) {
                $item = $this->get_item_by_name($value['description']);
                if ($item) {
                    if ($where_product_group != '') {
                        $where_product_group .= " OR IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                                        IF(commission_enable_product = 'enable',
                                                            IF(commission_product_groups IS NOT NULL, IF(commission_product_groups != \"\",find_in_set(" . $item->group_id . ",commission_product_groups), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    } else {
                        $where_product_group = "IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                                        IF(commission_enable_product = 'enable',
                                                            IF(commission_product_groups IS NOT NULL, IF(commission_product_groups != \"\",find_in_set(" . $item->group_id . ",commission_product_groups), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    }

                    if ($where_product != '') {
                        $where_product .= " OR IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                                        IF(commission_enable_product = 'enable',
                                                            IF(commission_products IS NOT NULL, IF(commission_products != \"\",find_in_set(" . $item->id . ",commission_products), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    } else {
                        $where_product = "IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                                        IF(commission_enable_product = 'enable',
                                                            IF(commission_products IS NOT NULL, IF(commission_products != \"\",find_in_set(" . $item->id . ",commission_products), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    }

                    if ($where_discount_product_group != '') {
                        $where_discount_product_group .= " OR IF(enable_discount = 'enable',
                                                        IF(discount_enable_product = 'enable',
                                                            IF(discount_product_groups IS NOT NULL, IF(discount_product_groups != \"\",find_in_set(" . $item->group_id . ",discount_product_groups), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    } else {
                        $where_discount_product_group = "IF(enable_discount = 'enable',
                                                        IF(discount_enable_product = 'enable',
                                                            IF(discount_product_groups IS NOT NULL, IF(discount_product_groups != \"\",find_in_set(" . $item->group_id . ",discount_product_groups), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    }

                    if ($where_discount_product != '') {
                        $where_discount_product .= " OR IF(enable_discount = 'enable',
                                                        IF(discount_enable_product = 'enable',
                                                            IF(discount_products IS NOT NULL, IF(discount_products != \"\",find_in_set(" . $item->id . ",discount_products), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    } else {
                        $where_discount_product = "IF(enable_discount = 'enable',
                                                        IF(discount_enable_product = 'enable',
                                                            IF(discount_products IS NOT NULL, IF(discount_products != \"\",find_in_set(" . $item->id . ",discount_products), 1=1), 1=1)
                                                            , 1=1)
                                                        ,1=0)";
                    }
                }
            }

            if ($where_product_group != '') {
                $where_product_group = ' AND (' . $where_product_group . ')';
            }

            if ($where_product != '') {
                $where_product = ' AND (' . $where_product . ')';
            }

            if ($where_discount_product_group != '') {
                $where_discount_product_group = ' AND (' . $where_discount_product_group . ')';
            }

            if ($where_discount_product != '') {
                $where_discount_product = ' AND (' . $where_discount_product . ')';
            }
        }

        $where_member = "AND IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                IF(commission_enable_member = 'enable',
                                    IF(commission_members IS NOT NULL, IF(commission_members != \"\",find_in_set(" . $member_id . ",commission_members), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_member_group = "AND IF(enable_commission = 'enable' and commission_affiliate_type = 3,
                                IF(commission_enable_member = 'enable',
                                    IF(commission_member_groups IS NOT NULL, IF(commission_member_groups != \"\",find_in_set(" . $member_group . ",commission_member_groups), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_discount_member = "AND IF(enable_discount = 'enable',
                                IF(discount_enable_member = 'enable',
                                    IF(discount_members IS NOT NULL, IF(discount_members != \"\",find_in_set(" . $member_id . ",discount_members), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_discount_member_group = "AND IF(enable_discount = 'enable',
                                IF(discount_enable_member = 'enable',
                                    IF(discount_member_groups IS NOT NULL, IF(discount_member_groups != \"\",find_in_set(" . $member_group . ",discount_member_groups), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        return $this->db->query('SELECT * FROM ' . db_prefix() . 'affiliate_programs where from_date <= "' . $date . '" and to_date >= "' . $date . '" AND ((' . $where_customer . ' ' . $where_customer_group . ' ' . $where_product . ' ' . $where_product_group . ' ' . $where_member . ' ' . $where_member_group . ') OR (' . $where_discount_customer . ' ' . $where_discount_customer_group . ' ' . $where_discount_product . ' ' . $where_discount_product_group . ' ' . $where_discount_member . ' ' . $where_discount_member_group . ')) order by ' . db_prefix() . 'affiliate_programs.priority desc')->row();

    }

    /**
     * Gets the item id by name.
     *
     * @param      string  $item_name  The itemid
     *
     * @return     string  The item name.
     */
    public function get_item_id_by_name($item_name)
    {

        $this->db->where('description', $item_name);
        $items = $this->db->get(db_prefix() . 'items')->row();

        if ($items) {
            return $items->id;
        }
        return '';
    }

    /**
     * Gets the item by name.
     *
     * @param      string  $item_name  The itemid
     *
     * @return     object  The item.
     */
    public function get_item_by_name($item_name)
    {

        $this->db->where('description', $item_name);
        return $this->db->get(db_prefix() . 'items')->row();
    }

    /**
     * Gets the first invoices.
     *
     * @param      integer   $staffid            The staffid
     * @param      integer   $invoiceid          The invoiceid
     * @param      integer  $max                The maximum
     * @param      object   $commission_policy  The commission policy
     * @param      integer  $is_client         Indicates if contact
     *
     * @return     array    The first invoices.
     */
    public function get_first_invoices($member_id, $invoiceid, $max, $affiliate_program, $commission = 0)
    {
        $where = 'affiliate_member_id = ' . $member_id;

        $where_group = '';
        if ($commission == 0) {
            if ($affiliate_program->commission_customer_groups != '') {
                foreach (explode(',', $affiliate_program->commission_customer_groups) as $value) {
                    if ($where_group != '') {
                        $where_group .= ' OR ' . $value . ' IN (select groupid from ' . db_prefix() . 'customer_groups where clientid)';
                    } else {
                        $where_group = ' ' . $value . ' IN (select groupid from ' . db_prefix() . 'customer_groups where clientid)';
                    }
                }

                if ($where_group != '') {
                    $where .= ' and (' . $where_group . ')';
                }
            }

            if ($affiliate_program->commission_customers != '') {
                $where .= ' and find_in_set(clientid, "' . $affiliate_program->commission_customers . '")';
            }
        } else {
            if ($affiliate_program->discount_customer_groups != '') {
                foreach (explode(',', $affiliate_program->discount_customer_groups) as $value) {
                    if ($where_group != '') {
                        $where_group .= ' OR ' . $value . ' IN (select groupid from ' . db_prefix() . 'customer_groups where clientid)';
                    } else {
                        $where_group = ' ' . $value . ' IN (select groupid from ' . db_prefix() . 'customer_groups where clientid)';
                    }
                }

                if ($where_group != '') {
                    $where .= ' and (' . $where_group . ')';
                }
            }

            if ($affiliate_program->discount_customers != '') {
                $where .= ' and find_in_set(clientid, "' . $affiliate_program->discount_customers . '")';
            }
        }

        $this->db->where($where);
        $this->db->order_by('datecreated', 'asc');
        $invoices = $this->db->get(db_prefix() . 'invoices')->result_array();

        $list_invoices = [];
        foreach ($invoices as $key => $value) {
            if ($key == $max) {
                break;
            }
            $list_invoices[] = $value['id'];
        }
        return $list_invoices;
    }

    /**
     * Gets the commission policy by staff.
     *
     * @param      string  $staff  The staff
     *
     * @return     object  The commission policy by staff.
     */
    public function get_my_affiliate_programs($member_id)
    {

        if ($member_id == '') {
            $member_id    = 0;
            $member_group = 0;
        } else {
            $member = $this->get_member($member_id);
            if ($member && $member->group != '') {
                $member_group = $member->group;
            } else {
                $member_group = 0;
            }
        }
        $date = date('Y-m-d');

        $where_member = "IF(enable_commission = 'enable',
                                IF(commission_enable_member = 'enable',
                                    IF(commission_members IS NOT NULL, IF(commission_members != \"\",find_in_set(" . $member_id . ",commission_members) and commission_affiliate_type = 3, 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_member_group = "AND IF(enable_commission = 'enable',
                                IF(commission_enable_member = 'enable',
                                    IF(commission_member_groups IS NOT NULL, IF(commission_member_groups != \"\",find_in_set(" . $member_group . ",commission_member_groups) and commission_affiliate_type = 3, 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_discount_member = "IF(enable_discount = 'enable',
                                IF(discount_enable_member = 'enable',
                                    IF(discount_members IS NOT NULL, IF(discount_members != \"\",find_in_set(" . $member_id . ",discount_members), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        $where_discount_member_group = "AND IF(enable_discount = 'enable',
                                IF(discount_enable_member = 'enable',
                                    IF(discount_member_groups IS NOT NULL, IF(discount_member_groups != \"\",find_in_set(" . $member_group . ",discount_member_groups), 1=1), 1=1)
                                    , 1=1)
                                ,1=0)";

        return $this->db->query('SELECT * FROM ' . db_prefix() . 'affiliate_programs where from_date <= "' . $date . '" and to_date >= "' . $date . '" AND ((' . $where_member . ' ' . $where_member_group . ') OR (' . $where_discount_member . ' ' . $where_discount_member_group . ') OR (enable_commission = "enable" and commission_affiliate_type = 2) OR (enable_commission = "enable" and commission_affiliate_type = 1)) order by ' . db_prefix() . 'affiliate_programs.priority desc')->result_array();

    }

    /**
     * Gets the item id by name.
     *
     * @param      string  $item_name  The itemid
     *
     * @return     string  The item name.
     */
    public function get_item_id_by_program($program_id, $return_query = false)
    {
        $items   = [];
        $program = $this->get_affiliate_program($program_id);

        if ($program->enable_discount == 'enable') {
            $where = 'can_be_sold = "can_be_sold"';
            if ($program->discount_products != '') {
                $where .= ' AND FIND_IN_SET(id, "' . $program->discount_products . '")';
            }

            if ($program->discount_product_groups != '') {
                $where .= ' AND FIND_IN_SET(group_id, "' . $program->discount_product_groups . '")';
            }

            if($return_query){
                $items['discount'] = 'SELECT id FROM '.db_prefix().'items WHERE '. $where;
            }else{
                
                $this->db->where($where);
                $items['discount'] = $this->db->get(db_prefix() . 'items')->result_array();
            }
        }

        if ($program->enable_commission == 'enable' && $program->commission_affiliate_type != 2) {
            $where = 'can_be_sold = "can_be_sold"';

            if ($program->commission_products != '') {
                $where .= ' AND FIND_IN_SET(id, "' . $program->commission_products . '")';
            }

            if ($program->commission_product_groups != '') {
                $where .= ' AND FIND_IN_SET(group_id, "' . $program->commission_product_groups . '")';
            }
            
            if($return_query){
                $items['commission'] = 'SELECT id FROM '.db_prefix().'items WHERE '. $where;
            }else{
                $this->db->where($where);
                $items['commission'] = $this->db->get(db_prefix() . 'items')->result_array();
            }

        }

        return $items;
    }

    /**
     * get program category
     * @param  integer $id program category id
     * @param  array  $where
     * @return array
     */
    public function get_program_category($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_program_categorys')->row();
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'affiliate_program_categorys')->result_array();
    }

    public function update_permissions($permissions, $staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->delete(db_prefix() . 'affiliate_admin_permissions');

        foreach ($permissions as $feature => $capabilities) {
            foreach ($capabilities as $capability) {
                $this->db->insert(db_prefix() . 'affiliate_admin_permissions', ['staff_id' => $staff_id, 'feature' => $feature, 'capability' => $capability]);
            }
        }

        return true;
    }

    /**
     * Get admin permissions
     * @param  mixed $id staff id
     * @return array
     */
    public function get_admin_permissions($id)
    {
        // Fix for version 2.3.1 tables upgrade
        if (defined('DOING_DATABASE_UPGRADE')) {
            return [];
        }

        $permissions = $this->app_object_cache->get('affiliate-admin-' . $id . '-permissions');

        if (!$permissions && !is_array($permissions)) {
            $this->db->where('staff_id', $id);
            $permissions = $this->db->get(db_prefix() . 'affiliate_admin_permissions')->result_array();

            $this->app_object_cache->add('affiliate-admin-' . $id . '-permissions', $permissions);
        }

        return $permissions;
    }

    /**
     * get program category
     * @param  integer $id program category id
     * @param  array  $where
     * @return array
     */
    public function get_affiliate_admin($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $admin                = [];
            $admin['name']        = get_staff_full_name($id);
            $permissions          = $this->get_admin_permissions($id);
            $admin['permissions'] = [];

            foreach ($permissions as $permission) {
                $admin['permissions'][$permission['feature']][$permission['capability']] = 1;
            }

            return $admin;
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'affiliate_admins')->result_array();
    }

    /**
     * Gets the item name.
     *
     * @param      string  $itemid  The itemid
     *
     * @return     string  The item name.
     */
    public function get_item_name($itemid)
    {
        $this->db->where('id', $itemid);
        $items = $this->db->get(db_prefix() . 'items')->row();

        if ($items) {
            return $items->description;
        }
        return '';
    }

    /**
     * get data transaction chart
     *
     * @param      string  $year   The year
     *
     * @return     array
     */
    public function transaction_chart($year = '', $staff_filter = [], $products_services = [])
    {
        $this->load->model('staff_model');
        $this->load->model('clients_model');
        if ($year == '') {
            $year = date('Y');
        }
        $amount      = [];
        $amount_paid = [];
        $month       = [];
        if ($staff_filter == []) {
            $members = $this->get_member();
            foreach ($members as $key => $value) {
                $count = $this->sum_transaction($value['id'], 'year(datecreated) = ' . $year, $products_services);
                if ($count) {
                    $amount[] = (double) $count;
                } else {
                    $amount[] = 0;
                }

                $count_paid = $this->sum_transaction($value['id'], 'year(datecreated) = ' . $year . ' and status = 2', $products_services);
                if ($count_paid) {
                    $amount_paid[] = (double) $count_paid;
                } else {
                    $amount_paid[] = 0;
                }

                $month[] = trim($value['firstname'] . ' ' . $value['lastname']);
            }
            return ['amount' => $amount, 'amount_paid' => $amount_paid, 'month' => $month];
        } else {
            if (count($staff_filter) == 1) {
                $date_minus = $year . '-01-01';
                for ($i = 0; $i < 12; $i++) {
                    $count = $this->sum_transaction($staff_filter[0], 'year(datecreated) = ' . date('Y', strtotime($date_minus)) . ' and month(datecreated) = ' . date('m', strtotime($date_minus)), $products_services);
                    if ($count) {
                        $amount[] = (double) $count;
                    } else {
                        $amount[] = 0;
                    }

                    $count_paid = $this->sum_transaction($staff_filter[0], 'year(datecreated) = ' . date('Y', strtotime($date_minus)) . ' and month(datecreated) = ' . date('m', strtotime($date_minus)) . ' and status = 2', $products_services);
                    if ($count_paid) {
                        $amount_paid[] = (double) $count_paid;
                    } else {
                        $amount_paid[] = 0;
                    }

                    $month[]    = date("M Y", strtotime($date_minus));
                    $date_minus = date("Y-m-d", strtotime($date_minus . " +1 month"));
                }
            } else {
                foreach ($staff_filter as $key => $value) {
                    $count = $this->sum_transaction($value, 'year(datecreated) = ' . $year, $products_services);
                    if ($count) {
                        $amount[] = (double) $count;
                    } else {
                        $amount[] = 0;
                    }

                    $count_paid = $this->sum_transaction($value, 'year(datecreated) = ' . $year . ' and status = 2', $products_services);
                    if ($count_paid) {
                        $amount_paid[] = (double) $count_paid;
                    } else {
                        $amount_paid[] = 0;
                    }

                    $month[] = trim(get_affiliate_full_name($value));

                }
            }
            return ['amount' => $amount, 'amount_paid' => $amount_paid, 'month' => $month];
        }
    }

    /**
     * sum transaction amount
     *
     * @param      integer        $member_id  The member id
     * @param      array|string  $where    The where
     *
     * @return     integer
     */
    public function sum_transaction($member_id = '', $where = [], $products_services = '')
    {
        $this->db->select_sum('amount');
        if ($member_id != '') {
            $this->db->where('member_id', $member_id);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $this->db->from(db_prefix() . 'affiliate_transactions');
        $result = $this->db->get()->row();
        if ($result) {
            return $result->amount;
        }
        return 0;
    }

    /**
     * get data dashboard commission chart
     *
     * @param      string  $year   The year
     *
     * @return     array
     */
    public function dashboard_commission_chart($staffid = '', $where = [], $view_member = true)
    {
        $date_minus = date("Y-m-d", strtotime(date('Y-m-1') . " -11 month"));
        $amount     = [];
        $month      = [];
        if ($view_member == true) {
            $staffid = get_affiliate_user_id();
        }

        for ($i = 0; $i < 12; $i++) {
            if ($view_member == true) {
                $count = 0;
                $this->db->select_sum('amount');
                if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
                    $this->db->where($where);
                }
                $this->db->where(array('member_id' => $staffid, 'year(datecreated)' => date('Y', strtotime($date_minus)), 'month(datecreated)' => date('m', strtotime($date_minus))));

                $this->db->from(db_prefix() . 'affiliate_transactions');
                $result = $this->db->get()->row();

                if ($result->amount) {
                    $count = $result->amount;
                }
            } else {
                $count = 0;
                $this->db->select_sum('amount');
                if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
                    $this->db->where($where);
                }
                $this->db->where(array('year(datecreated)' => date('Y', strtotime($date_minus)), 'month(datecreated)' => date('m', strtotime($date_minus))));

                $this->db->from(db_prefix() . 'affiliate_transactions');
                $result = $this->db->get()->row();

                if ($result->amount) {
                    $count = $result->amount;
                }
            }

            if ($count) {
                $amount[] = (double) $count;
            } else {
                $amount[] = 0;
            }
            $month[]    = date("M Y", strtotime($date_minus));
            $date_minus = date("Y-m-d", strtotime($date_minus . " +1 month"));
        }

        return ['amount' => $amount, 'month' => $month];
    }

    /**
     * Change member password, used from client area
     * @param  mixed $id          member id to change password
     * @param  string $oldPassword old password to verify
     * @param  string $newPassword new password
     * @return boolean
     */
    public function change_member_password($id, $oldPassword, $newPassword)
    {
        // Get current password
        $this->db->where('id', $id);
        $member = $this->db->get(db_prefix() . 'affiliate_users')->row();

        if (!app_hasher()->CheckPassword($oldPassword, $member->password)) {
            return [
                'old_password_not_match' => true,
            ];
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_users', [
            'password' => app_hash_password($newPassword),
        ]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * delete affiliate program
     *
     * @param      integer  $id     The identifier
     * @return boolean
     */
    public function delete_affiliate_program($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_programs');

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * get warehourse attachments
     * @param  integer $commodity_id
     * @return array
     */
    public function get_warehourse_attachments($commodity_id)
    {

        $this->db->order_by('dateadded', 'desc');
        $this->db->where('rel_id', $commodity_id);
        $this->db->where('rel_type', 'commodity_item_file');

        return $this->db->get(db_prefix() . 'files')->result_array();

    }

    /**
     * get transaction count
     * @param  integer
     * @return array
     */
    public function get_transaction_count($type = '')
    {
        $transaction_count                         = [];
        $transaction_count['commission']['total']  = 0;
        $transaction_count['commission']['unpaid'] = 0;
        $transaction_count['commission']['paid']   = 0;
        $transaction_count['discount']['total']    = 0;
        $transaction_count['discount']['unpaid']   = 0;
        $transaction_count['discount']['paid']     = 0;

        $this->db->select_sum('amount');
        $this->db->where("type LIKE '%commission%'");
        $result = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
        if ($result->amount) {
            $transaction_count['commission']['total'] = $result->amount;
        }

        $this->db->select_sum('amount');
        $this->db->where("type LIKE '%commission%' and status != 2");
        $result = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
        if ($result->amount) {
            $transaction_count['commission']['unpaid'] = $result->amount;
        }

        $this->db->select_sum('amount');
        $this->db->where("type LIKE '%commission%' and status = 2");
        $result = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
        if ($result->amount) {
            $transaction_count['commission']['paid'] = $result->amount;
        }

        $this->db->select_sum('amount');
        $this->db->where("type LIKE '%discount%'");
        $result = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
        if ($result->amount) {
            $transaction_count['discount']['total'] = $result->amount;
        }

        $this->db->select_sum('amount');
        $this->db->where("type LIKE '%discount%' and status != 2");
        $result = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
        if ($result->amount) {
            $transaction_count['discount']['unpaid'] = $result->amount;
        }

        $this->db->select_sum('amount');
        $this->db->where("type LIKE '%discount%' and status = 2");
        $result = $this->db->get(db_prefix() . 'affiliate_transactions')->row();
        if ($result->amount) {
            $transaction_count['discount']['paid'] = $result->amount;
        }

        return $transaction_count;
    }

    /**
     * get data dashboard registration chart
     *
     * @param      string  $year   The year
     *
     * @return     array
     */
    public function dashboard_registration_chart()
    {
        $date_minus = date("Y-m-d", strtotime(date('Y-m-1') . " -11 month"));
        $amount     = [];
        $month      = [];

        for ($i = 0; $i < 12; $i++) {

            $count = 0;
            $this->db->select('count(*) as total');

            $this->db->where(array('year(datecreated)' => date('Y', strtotime($date_minus)), 'month(datecreated)' => date('m', strtotime($date_minus))));

            $this->db->from(db_prefix() . 'affiliate_users');
            $result = $this->db->get()->row();
            if ($result->total) {
                $count = $result->total;
            }

            if ($count) {
                $amount[] = (double) $count;
            } else {
                $amount[] = 0;
            }
            $month[]    = date("M Y", strtotime($date_minus));
            $date_minus = date("Y-m-d", strtotime($date_minus . " +1 month"));
        }

        return ['amount' => $amount, 'month' => $month];
    }

    /**
     * Add new product
     * @param array $data
     * @return id or false
     */
    public function add_product($data)
    {
        $affectedRows = 0;

        if (isset($data['product'])) {
            $member_id = get_affiliate_user_id();
            foreach ($data['product'] as $value) {
                $node               = [];
                $node['member_id']  = $member_id;
                $node['product_id'] = $value;
                $this->db->insert(db_prefix() . 'affiliate_user_products', $node);

                $insert_id = $this->db->insert_id();
                if ($insert_id) {
                    $affectedRows++;
                }
            }
        }

        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Gets the product list.
     *
     * @param      string  $member_id  The member id
     *
     * @return     array   The product list.
     */
    public function get_product_list($member_id = '', $where = '')
    {
        if ($member_id == '') {
            $member_id = get_affiliate_user_id();
        }
        $this->db->select('*,' . db_prefix() . 'affiliate_user_products.id as user_product_id');
        if($where != ''){
            $this->db->where($where);
        }
        $this->db->where('member_id', $member_id);
        $this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.id = ' . db_prefix() . 'affiliate_user_products.product_id', 'left');
        $product_list = $this->db->get(db_prefix() . 'affiliate_user_products')->result_array();

        return $product_list;
    }

    /**
     * delete product
     *
     * @param      integer  $id     The identifier
     * @return boolean
     */
    public function delete_product($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_user_products');

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Gets the product select.
     *
     * @param      string  $staffid  The staffid
     *
     * @return     array   The product select.
     */
    public function get_my_product_select($member_id, $where = [], $select = true)
    {
        $my_programs   = $this->get_my_affiliate_programs($member_id);
        $where_product = '';
        foreach ($my_programs as $k => $val) {
            $product = $this->affiliate_model->get_item_id_by_program($val['id'], true);
            if (isset($product['discount'])) {
                if ($where_product == '') {
                    $where_product = 'id in (' . $product['discount'].')';
                } else {
                    $where_product .= ' OR id in (' . $product['discount'].')';
                }
            }

            if (isset($product['commission'])) {
                if ($where_product == '') {
                    $where_product = 'id in (' . $product['commission'].')';
                } else {
                    $where_product .= ' OR id in (' . $product['commission'].')';
                }
            }
        }

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        if ($where_product != '') {
            $this->db->where($where_product);
        }else{
            $this->db->where('id = -1');
        }

        $items = $this->db->get(db_prefix() . 'items')->result_array();

        if ($select) {
            $list_item = [];
            foreach ($items as $key => $item) {
                $note          = [];
                $note['id']    = $item['id'];
                $note['label'] = $item['description'];
                $note['long_description'] = $item['long_description'];
                $note['rate'] = $item['rate'];
                $list_item[]   = $note;
            }
            return $list_item;
        }
        return $items;
    }

    /**
     *  add woocommerce channel
     * @param  array  $data
     * @return  int $insert_id
     */
    public function add_woocommerce_channel($data)
    {
        $data['member_id']   = get_affiliate_user_id();
        $data['datecreated'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'affiliate_woocommerce_channels', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     *  update woocommerce channel
     * @param  array  $data
     * @return  int insert_id
     */
    public function update_woocommerce_channel($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_woocommerce_channels', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * get woocommerce channel
     * @param  integer $id    member group id
     * @param  array  $where
     * @return object
     */
    public function get_woocommerce_channel($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'affiliate_woocommerce_channels')->row();
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'affiliate_woocommerce_channels')->result_array();
    }

    /**
     * delete woocommerce channel
     *
     * @param  integer  $id     The identifier
     * @return boolean
     */
    public function delete_woocommerce_channel($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_woocommerce_channels');

        if ($this->db->affected_rows() > 0) {
            $this->db->where('woocommere_channel_id', $id);
            $this->db->delete(db_prefix() . 'affiliate_woocommere_products');
            return true;
        }
        return false;
    }

    /**
     *  get woocommere products
     * @param   int  $woocommere_channel_id
     * @param   int  $only_id
     * @return  object
     */
    public function get_woocommere_products($woocommere_channel_id, $where = [], $only_id = false)
    {
        if (!$only_id) {
            $this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.id = ' . db_prefix() . 'affiliate_woocommere_products.product_id', 'left');
        }
        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }
        $this->db->select('*, ' . db_prefix() . 'affiliate_woocommere_products.id as woocommere_product_id');
        $this->db->where('woocommere_channel_id', $woocommere_channel_id);
        $products = $this->db->get(db_prefix() . 'affiliate_woocommere_products')->result_array();
        if ($only_id) {
            $ids = [];
            foreach ($products as $product) {
                array_push($ids, $product['product_id']);
            }

            return $ids;
        }
        return $products;
    }

    /**
     *  add product channel wcm
     * @param  array  $data
     * @return  int insert_id
     */
    public function add_product_channel_wcm($data)
    {
        $this->load->model('affiliate_store_model');
        $insert_id = 0;

        foreach ($data['product_id'] as $key => $value) {
            $prices = 0;
            if ($data['prices'] == '') {
                $get_data = $this->affiliate_store_model->get_product($value);
                if ($get_data) {
                    $prices = $get_data->rate;
                }
            } else {
                $prices = str_replace(',', '', $data['prices']);
            }
            $data_add['woocommere_channel_id'] = $data['woocommere_channel_id'];
            $data_add['group_product_id']      = $data['group_product_id'];
            $data_add['product_id']            = $value;
            $data_add['prices']                = $prices;
            $data_saved                        = $this->get_woocommere_products($data['woocommere_channel_id'], ['product_id' => $value]);
            if ($data_saved) {
                $this->db->where('id', $data_saved->id);
                $this->db->update(db_prefix() . 'affiliate_woocommere_products', $data_add);
            } else {
                $this->db->insert(db_prefix() . 'affiliate_woocommere_products', $data_add);
            }
            $insert_id = 1;
        }
        return $insert_id;
    }

    /**
     *  update product channel wcm
     * @param  array  $data
     * @return  int insert_id
     */
    public function update_product_channel_wcm($data, $id)
    {
        $prices = str_replace(',', '', $data['prices']);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_woocommere_products', ['prices' => $prices]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    /**
     * delete product channel wcm
     *
     * @param      integer  $id     The identifier
     * @return boolean
     */
    public function delete_product_channel($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_woocommere_products');

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * process price synchronization
     * @param  int $store_id
     * @return bool
     */
    public function process_price_synchronization_update_product($store_id, $price, $product_id)
    {
        $store           = $this->get_woocommere_store($store_id);
        $product         = $this->affiliate_store_model->get_product($product_id);
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
        $arr_product_store = [];

        foreach ($products_store as $key => $value) {

            if ($value->sku != '') {
                if ($product->sku_code == $value->sku) {
                    $data = [
                        'regular_price' => $price,
                    ];

                    $log_price = [
                        'name'          => $product->description,
                        'regular_price' => $price,
                        'chanel'        => 'WooCommerce(' . $this->get_name_store($store_id) . ')',
                        "type"          => "price",
                    ];
                    $this->db->insert(db_prefix() . 'affiliate_log_sync_woo', $log_price);
                    $rs = $woocommerce->post('products/' . $value->id, $data);
                }
            }
        }
        return true;
    }

/**
 * get all product in group
 * @param  $group_items
 * @return list items
 */
    public function get_all_product_in_group($group_items)
    {
        $this->db->where('group_id', $group_items);
        $this->db->where('can_be_sold = "can_be_sold"');
        return $this->db->get(db_prefix() . 'items')->result_array();
    }

    /**
     * add setting auto sync store
     * @param object $data [description]
     */
    public function add_setting_auto_sync_store($data)
    {
        if (isset($data['sync_omni_sales_products'])) {
            $data['sync_omni_sales_products'] = 1;
        } else {
            $data['sync_omni_sales_products'] = 0;
        }

        if (isset($data['sync_omni_sales_inventorys'])) {
            $data['sync_omni_sales_inventorys'] = 1;
        } else {
            $data['sync_omni_sales_inventorys'] = 0;
        }

        if (isset($data['price_crm_woo'])) {
            $data['price_crm_woo'] = 1;
        } else {
            $data['price_crm_woo'] = 0;
        }

        if (isset($data['sync_omni_sales_description'])) {
            $data['sync_omni_sales_description'] = 1;
        } else {
            $data['sync_omni_sales_description'] = 0;
        }

        if (isset($data['sync_omni_sales_images'])) {
            $data['sync_omni_sales_images'] = 1;
        } else {
            $data['sync_omni_sales_images'] = 0;
        }

        if (isset($data['sync_omni_sales_orders'])) {
            $data['sync_omni_sales_orders'] = 1;
        } else {
            $data['sync_omni_sales_orders'] = 0;
        }

        $data['member_id']   = get_affiliate_user_id();
        $data['datecreator'] = date('Y-m-d H:i:s');
        $data['records_time1'] = date('H:i:s');
        $data['records_time2'] = date('H:i:s');
        $data['records_time3'] = date('H:i:s');
        $data['records_time4'] = date('H:i:s');
        $data['records_time5'] = date('H:i:s');
        $data['records_time6'] = date('H:i:s');

        $this->db->insert('affiliate_setting_woo_store', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**
     * update setting auto sync store
     * @param  object $data
     * @param  integer $id  
     * @return boolean      
     */
    public function update_setting_auto_sync_store($data, $id)
    {
        if (isset($data['sync_omni_sales_products'])) {
            $data['sync_omni_sales_products'] = 1;
        } else { $data['sync_omni_sales_products'] = 0;}

        if (isset($data['sync_omni_sales_inventorys'])) {
            $data['sync_omni_sales_inventorys'] = 1;
        } else { $data['sync_omni_sales_inventorys'] = 0;}

        if (isset($data['price_crm_woo'])) {
            $data['price_crm_woo'] = 1;
        } else { $data['price_crm_woo'] = 0;}

        if (isset($data['sync_omni_sales_description'])) {
            $data['sync_omni_sales_description'] = 1;
        } else { $data['sync_omni_sales_description'] = 0;}

        if (isset($data['sync_omni_sales_images'])) {
            $data['sync_omni_sales_images'] = 1;
        } else { $data['sync_omni_sales_images'] = 0;}

        if (isset($data['sync_omni_sales_orders'])) {
            $data['sync_omni_sales_orders'] = 1;
        } else { $data['sync_omni_sales_orders'] = 0;}

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_setting_woo_store', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * delete sync auto store
     * @param  integer $id
     * @return boolean
     */
    public function delete_sync_auto_store($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'affiliate_setting_woo_store');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    /**
     * get setting auto sync store
     * @param  string $store
     * @return object or array
     */
    public function get_setting_auto_sync_store($member_id, $store = '')
    {
        if ($store != '') {
            $this->db->where('id', $store);
            return $this->db->get(db_prefix() . 'affiliate_setting_woo_store')->row();
        }

        $this->db->select('*, '.db_prefix() . 'affiliate_setting_woo_store.id as id');
        $this->db->where(db_prefix() . 'affiliate_setting_woo_store.member_id', $member_id);
         $this->db->join(db_prefix() . 'affiliate_woocommerce_channels', '' . db_prefix() . 'affiliate_woocommerce_channels.id = ' . db_prefix() . 'affiliate_setting_woo_store.store', 'left');
        return $this->db->get(db_prefix() . 'affiliate_setting_woo_store')->result_array();
    }

    /**
     * delete all data the affiliate module
     *
     * @param      int   $id     The identifier
     *
     * @return     boolean
     */
    public function reset_data()
    {
        $affectedRows = 0;
        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_admins');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_admin_permissions');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_logs');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_orders');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_order_items');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_order_item_taxs');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_programs');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_setting_woo_store');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_transactions');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_users');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_user_groups');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_users');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_user_products');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_withdraws');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_withdraw_details');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_woocommere_products');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        $this->db->where('id > 0');
        $this->db->delete(db_prefix() . 'affiliate_woocommerce_channels');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }

    /**
     * update general setting
     *
     * @param      array   $data   The data
     *
     * @return     boolean
     */
    public function update_setting($data)
    {
        $affectedRows = 0;

        foreach ($data as $key => $value) {
            $this->db->where('name', $key);
            $this->db->update(db_prefix() . 'options', [
                'value' => $value,
            ]);
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
            }
        }

        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }

    /**
     * get setting auto sync store exit
     * @param  string $id
     * @return array    
     */
    public function get_setting_auto_sync_store_exit($id = '')
    {
        $omni_setting_woo_store = $this->db->get(db_prefix() . 'affiliate_setting_woo_store')->result_array();
        $arr                    = [];
        foreach ($omni_setting_woo_store as $key => $value) {
            $arr[] = $value['store'];
        }
        return $arr;
    }

    /**
     * apply affiliate program.
     *
     * @param      integer   $payment_id  The payment identifier
     *
     * @return     boolean
     */
    public function credit_apply_affiliate_programs($credit)
    {
        $this->load->model('payments_model');
        $this->load->model('invoices_model');
        $this->load->model('invoice_items_model');

        $affectedRows = 0;
        $invoices     = $this->invoices_model->get($credit['data']['invoice_id']);
        $count        = 0;
        $salesperson  = '';
        $type         = '';
        if ($invoices->affiliate_member_id) {
            $salesperson = $invoices->affiliate_member_id;
        } else {
            return false;
        }

        if ($invoices) {
            $affiliate_program = $this->get_affiliate_program_by_member($salesperson, $invoices);
            if ($affiliate_program) {
                if ($affiliate_program->enable_commission == 'enable') {
                    $type .= _l('commission');
                    $profit_percent = 1;
                    $profit         = 0;
                    if ($affiliate_program->commission_amount_to_calculate == 'profit') {
                        foreach ($invoices->items as $value) {
                            $item = $this->get_item_by_name($value['description']);
                            if ($item) {
                                $profit += ($value['rate'] - $item->purchase_price) * $value['qty'];
                            }
                        }

                        $profit_percent = $profit / $invoices->total;
                    }

                    if ($affiliate_program->commission_policy_type == '2') {
                        if ($affiliate_program->commission_type == 'percentage') {
                            $payments_amount = ($credit['data']['amount'] - round(($invoices->total_tax * ($credit['data']['amount'] / $invoices->total)), 2)) * $profit_percent;
                            if ($affiliate_program->commission_first_invoices == '1') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $credit['data']['invoice_id'], $affiliate_program->commission_number_first_invoices, $affiliate_program);
                                if (in_array($credit['data']['invoice_id'], $list_first_invoices)) {
                                    $count += $payments_amount * ($affiliate_program->commission_percent_first_invoices / 100);
                                } else {
                                    $count += $payments_amount * ($affiliate_program->commission_percent_enjoyed / 100);
                                }
                            } else {
                                $count += $payments_amount * ($affiliate_program->commission_percent_enjoyed / 100);
                            }
                        } else {
                            if ($affiliate_program->commission_first_invoices == '1') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $credit['data']['invoice_id'], $affiliate_program->commission_number_first_invoices, $affiliate_program);

                                if (in_array($credit['data']['invoice_id'], $list_first_invoices)) {
                                    $count += $affiliate_program->commission_percent_first_invoices;
                                } else {
                                    $count += $affiliate_program->commission_percent_enjoyed;
                                }
                            } else {
                                $count += $affiliate_program->commission_percent_enjoyed;
                            }
                        }
                    } elseif ($affiliate_program->commission_policy_type == '3') {
                        $product_setting = json_decode($affiliate_program->commission_product_setting);
                        if ($invoices->items) {
                            $payments_amount = ($credit['data']['amount'] - round(($invoices->total_tax * ($credit['data']['amount'] / $invoices->total)), 2)) * $profit_percent;

                            foreach ($invoices->items as $item) {
                                $item_id = $this->get_item_id_by_name($item['description']);
                                $it      = '';
                                $percent = 0;
                                if ($item_id != '') {
                                    $it = $this->get_item_by_name($item['description']);

                                    if ($affiliate_program->commission_amount_to_calculate == 'profit') {
                                        $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                        if ($profit > 0) {
                                            $percent = $item_amount / $profit;
                                        } else {
                                            $percent = 0;
                                        }
                                    } else {
                                        $item_amount = ($item['qty'] * $item['rate']);
                                        $percent     = $item_amount / $payments_amount;
                                    }
                                }

                                foreach ($product_setting as $value) {
                                    $group_setting       = explode('|', $value[0]);
                                    $item_setting        = explode('|', $value[1]);
                                    $from_number_setting = $value[2];
                                    $to_number_setting   = $value[3];
                                    $percent_setting     = $value[4];

                                    $check = true;
                                    if ($item_id != '') {
                                        if ($it != '') {
                                            if ($value[0] != '' && !in_array($it->group_id, $group_setting)) {
                                                $check = false;
                                            }
                                        } else {
                                            if ($value[0] != '') {
                                                $check = false;
                                            }
                                        }

                                        if ($value[1] != '' && !in_array($item_id, $item_setting)) {
                                            $check = false;
                                        }
                                    } else {
                                        if ($value[1] != '' || $value[0] != '') {
                                            $check = false;
                                        }
                                    }

                                    if (($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)) {
                                        $check = false;
                                    }

                                    if ($check == true) {
                                        if ($affiliate_program->commission_type == 'percentage') {
                                            $count += ($percent * $payments_amount) * ($percent_setting / 100);
                                        } else {
                                            $count += $percent_setting;
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($affiliate_program->commission_policy_type == '1') {
                        $total_payments  = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_setting  = json_decode($affiliate_program->commission_ladder_setting);
                        $amount          = $payments_amount;
                        if ($affiliate_program->commission_type == 'percentage') {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->commission_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $to_amount = str_replace(',', '', $value->commission_to_amount);
                                    if ($to_amount == '') {
                                        $count += $amount * ($value->commission_percent_enjoyed_ladder / 100);
                                        $amount = 0;
                                    } elseif ($from_amount == '') {
                                        $count += $amount * ($value->commission_percent_enjoyed_ladder / 100);

                                        $amount = $amount - $to_amount;
                                    } else {
                                        if ($payments_amount > $to_amount) {
                                            $count += ($to_amount - $from_amount) * ($value->commission_percent_enjoyed_ladder / 100);
                                            if ($key == 0) {
                                                $amount = $amount - $to_amount;
                                            } else {
                                                $amount = $amount - ($to_amount - $from_amount);
                                            }
                                        } else {
                                            if ($key == 0) {
                                                $count += ($amount - $from_amount) * ($value->commission_percent_enjoyed_ladder / 100);
                                            } else {
                                                $count += $amount * ($value->commission_percent_enjoyed_ladder / 100);
                                            }
                                            $amount = 0;
                                        }
                                    }
                                } else {
                                    break;
                                }
                            }
                        } else {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->commission_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $count += $value->commission_percent_enjoyed_ladder;
                                } else {
                                    break;
                                }
                            }
                        }
                    } elseif ($affiliate_program->commission_policy_type == '4') {
                        $total_payments         = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount        = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_product_setting = json_decode($affiliate_program->commission_ladder_product_setting, true);
                        foreach ($invoices->items as $item) {
                            $it = $this->get_item_by_name($item['description']);
                            if ($it) {
                                $percent = 0;
                                if ($affiliate_program->commission_amount_to_calculate == 'profit') {
                                    $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                    if ($profit > 0) {
                                        $percent = $item_amount / $profit;
                                    } else {
                                        $percent = 0;
                                    }
                                } else {
                                    $item_amount = ($item['qty'] * $item['rate']);
                                    $percent     = $item_amount / $payments_amount;
                                }
                                $item_amount = $payments_amount * $percent;
                                $amount      = $item_amount;

                                if ($affiliate_program->commission_type == 'percentage') {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['commission_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $to_amount = str_replace(',', '', $value['commission_to_amount_product'][$k]);

                                                    if ($to_amount == '') {
                                                        $count += $amount * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = 0;
                                                    } elseif ($from_amount == '') {
                                                        $count += $amount * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = $amount - $to_amount;
                                                    } else {
                                                        if ($item_amount > $to_amount) {
                                                            $count += ($to_amount - $from_amount) * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);
                                                            if ($k == 0) {
                                                                $amount = $amount - $to_amount;
                                                            } else {
                                                                $amount = $amount - ($to_amount - $from_amount);
                                                            }
                                                        } else {
                                                            if ($k == 0) {
                                                                $count += ($amount - $from_amount) * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);
                                                            } else {
                                                                $count += $amount * ($value['commission_percent_enjoyed_ladder_product'][$k] / 100);
                                                            }
                                                            $amount = 0;
                                                        }
                                                    }
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['commission_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $to_amount = str_replace(',', '', $value['commission_to_amount_product'][$k]);
                                                    $count += $value['commission_percent_enjoyed_ladder_product'][$k];
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if ($affiliate_program->enable_discount == 'enable') {

                    if ($type != '') {
                        $type .= ', ' . _l('discount');
                    } else {
                        $type .= _l('discount');
                    }
                    $profit_percent = 1;
                    $profit         = 0;
                    if ($affiliate_program->discount_amount_to_calculate == 'profit') {
                        foreach ($invoices->items as $value) {
                            $item = $this->get_item_by_name($value['description']);
                            if ($item) {
                                $profit += ($value['rate'] - $item->purchase_price) * $value['qty'];
                            }
                        }

                        $profit_percent = $profit / $invoices->total;
                    }

                    if ($affiliate_program->discount_policy_type == '2') {
                        if ($affiliate_program->discount_type == 'percentage') {
                            $payments_amount = ($credit['data']['amount'] - round(($invoices->total_tax * ($credit['data']['amount'] / $invoices->total)), 2)) * $profit_percent;

                            if ($affiliate_program->discount_first_invoices == 'enable') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $credit['data']['invoice_id'], $affiliate_program->discount_number_first_invoices, $affiliate_program, 1);

                                if (in_array($credit['data']['invoice_id'], $list_first_invoices)) {
                                    $count += $payments_amount * ($affiliate_program->discount_percent_first_invoices / 100);
                                } else {
                                    $count += $payments_amount * ($affiliate_program->discount_percent_enjoyed / 100);
                                }
                            } else {
                                $count += $payments_amount * ($affiliate_program->discount_percent_enjoyed / 100);
                            }
                        } else {
                            if ($affiliate_program->discount_first_invoices == 'enable') {
                                $list_first_invoices = $this->get_first_invoices($salesperson, $credit['data']['invoice_id'], $affiliate_program->discount_number_first_invoices, $affiliate_program, 1);
                                if (in_array($credit['data']['invoice_id'], $list_first_invoices)) {
                                    $count += $affiliate_program->discount_percent_first_invoices;
                                } else {
                                    $count += $affiliate_program->discount_percent_enjoyed;
                                }
                            } else {
                                $count += $affiliate_program->discount_percent_enjoyed;
                            }
                        }
                    } elseif ($affiliate_program->discount_policy_type == '3') {
                        $product_setting = json_decode($affiliate_program->discount_product_setting);
                        if ($invoices->items) {
                            $payments_amount = ($credit['data']['amount'] - round(($invoices->total_tax * ($credit['data']['amount'] / $invoices->total)), 2)) * $profit_percent;

                            foreach ($invoices->items as $item) {
                                $item_id = $this->get_item_id_by_name($item['description']);
                                $it      = '';
                                $percent = 0;
                                if ($item_id != '') {
                                    $it = $this->get_item_by_name($item['description']);

                                    if ($affiliate_program->discount_amount_to_calculate == 'profit') {
                                        $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                        if ($profit > 0) {
                                            $percent = $item_amount / $profit;
                                        } else {
                                            $percent = 0;
                                        }
                                    } else {
                                        $item_amount = ($item['qty'] * $item['rate']);
                                        $percent     = $item_amount / $payments_amount;
                                    }
                                }

                                foreach ($product_setting as $value) {
                                    $group_setting       = explode('|', $value[0]);
                                    $item_setting        = explode('|', $value[1]);
                                    $from_number_setting = $value[2];
                                    $to_number_setting   = $value[3];
                                    $percent_setting     = $value[4];

                                    $check = true;
                                    if ($item_id != '') {
                                        if ($it != '') {
                                            if ($value[0] != '' && !in_array($it->group_id, $group_setting)) {
                                                $check = false;
                                            }
                                        } else {
                                            if ($value[0] != '') {
                                                $check = false;
                                            }
                                        }

                                        if ($value[1] != '' && !in_array($item_id, $item_setting)) {
                                            $check = false;
                                        }
                                    } else {
                                        if ($value[1] != '' || $value[0] != '') {
                                            $check = false;
                                        }
                                    }

                                    if (($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)) {
                                        $check = false;
                                    }

                                    if ($check == true) {
                                        if ($affiliate_program->discount_type == 'percentage') {
                                            $count += ($percent * $payments_amount) * ($percent_setting / 100);
                                        } else {
                                            $count += $percent_setting;
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ($affiliate_program->discount_policy_type == '1') {
                        $total_payments  = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_setting  = json_decode($affiliate_program->discount_ladder_setting);

                        $amount = $payments_amount;
                        if ($affiliate_program->discount_type == 'percentage') {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->discount_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $to_amount = str_replace(',', '', $value->discount_to_amount);
                                    if ($to_amount == '') {
                                        $count += $amount * ($value->discount_percent_enjoyed_ladder / 100);
                                        $amount = 0;
                                    } elseif ($from_amount == '') {
                                        $count += $amount * ($value->discount_percent_enjoyed_ladder / 100);

                                        $amount = $amount - $to_amount;
                                    } else {
                                        if ($payments_amount > $to_amount) {
                                            $count += ($to_amount - $from_amount) * ($value->discount_percent_enjoyed_ladder / 100);
                                            if ($key == 0) {
                                                $amount = $amount - $to_amount;
                                            } else {
                                                $amount = $amount - ($to_amount - $from_amount);
                                            }
                                        } else {
                                            if ($key == 0) {
                                                $count += ($amount - $from_amount) * ($value->discount_percent_enjoyed_ladder / 100);
                                            } else {
                                                $count += $amount * ($value->discount_percent_enjoyed_ladder / 100);
                                            }
                                            $amount = 0;
                                        }
                                    }
                                } else {
                                    break;
                                }
                            }
                        } else {
                            foreach ($ladder_setting as $key => $value) {
                                $from_amount = str_replace(',', '', $value->discount_from_amount);
                                if ($payments_amount > $from_amount) {
                                    $count += $value->discount_percent_enjoyed_ladder;
                                } else {
                                    break;
                                }
                            }
                        }
                    } elseif ($affiliate_program->discount_policy_type == '4') {
                        $total_payments         = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id)));
                        $credits_applied = total_credits_applied_to_invoice($invoices->id);
                        if($credits_applied){
                            $total_payments += $credits_applied;
                        }
                        $payments_amount        = ($total_payments - round(($invoices->total_tax * ($total_payments / $invoices->total)), 2)) * $profit_percent;
                        $ladder_product_setting = json_decode($affiliate_program->discount_ladder_product_setting, true);
                        foreach ($invoices->items as $item) {
                            $it = $this->get_item_by_name($item['description']);
                            if ($it) {
                                $percent = 0;
                                if ($affiliate_program->discount_amount_to_calculate == 'profit') {
                                    $item_amount = ($item['qty'] * ($item['rate'] - $it->purchase_price));
                                    if ($profit > 0) {
                                        $percent = $item_amount / $profit;
                                    } else {
                                        $percent = 0;
                                    }
                                } else {
                                    $item_amount = ($item['qty'] * $item['rate']);
                                    $percent     = $item_amount / $payments_amount;
                                }
                                $item_amount = $payments_amount * $percent;
                                $amount      = $item_amount;

                                if ($affiliate_program->discount_type == 'percentage') {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['discount_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $to_amount = str_replace(',', '', $value['discount_to_amount_product'][$k]);

                                                    if ($to_amount == '') {
                                                        $count += $amount * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = 0;
                                                    } elseif ($from_amount == '') {
                                                        $count += $amount * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);

                                                        $amount = $amount - $to_amount;
                                                    } else {
                                                        if ($item_amount > $to_amount) {
                                                            $count += ($to_amount - $from_amount) * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);
                                                            if ($k == 0) {
                                                                $amount = $amount - $to_amount;
                                                            } else {
                                                                $amount = $amount - ($to_amount - $from_amount);
                                                            }
                                                        } else {
                                                            if ($k == 0) {
                                                                $count += ($amount - $from_amount) * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);
                                                            } else {
                                                                $count += $amount * ($value['discount_percent_enjoyed_ladder_product'][$k] / 100);
                                                            }
                                                            $amount = 0;
                                                        }
                                                    }
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($ladder_product_setting as $key => $value) {
                                        if ($it->id == $key) {
                                            foreach ($value['discount_from_amount_product'] as $k => $val) {
                                                $from_amount = str_replace(',', '', $val);
                                                if ($item_amount > $from_amount) {
                                                    $count += $value['discount_percent_enjoyed_ladder_product'][$k];
                                                } else {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($count > 0) {

                $data               = [];
                $data[$salesperson] = $count;
                $list_isset         = [];
                $list_isset[]       = $salesperson;
                do {
                    foreach ($data as $k => $count) {
                        $this->db->where('invoice_id', $invoices->id);
                        $this->db->where('member_id', $k);
                        $transaction = $this->db->get(db_prefix() . 'affiliate_transactions')->row();

                        if ($transaction) {
                            if ($affiliate_program->commission_policy_type == '2' || $affiliate_program->commission_policy_type == '3') {
                                $count = $count + $transaction->amount;
                            }

                            $this->db->where('id', $transaction->id);
                            $this->db->update(db_prefix() . 'affiliate_transactions', ['amount' => round($count, 2), 'datecreated' => date('Y-m-d H:i:s')]);
                            if ($this->db->affected_rows() > 0) {
                                $affectedRows++;
                            }
                        } else {
                            $node                         = [];
                            $node['member_id']            = $k;
                            $node['invoice_id']           = $invoices->id;
                            $node['amount']               = round($count, 2);
                            $node['datecreated']          = date('Y-m-d H:i:s');
                            $node['type']                 = $type;
                            $node['addedfrom']            = get_staff_user_id();
                            $node['status']               = 0;
                            $node['affiliate_program_id'] = $affiliate_program->id;
                            $this->db->insert(db_prefix() . 'affiliate_transactions', $node);
                            $insert_id = $this->db->insert_id();

                            if ($insert_id) {
                                $affectedRows++;
                            }
                        }
                        unset($data[$k]);
                    }
                } while (count($data) > 0);
            }
        }
        if ($affectedRows > 0) {
            $this->db->insert(db_prefix() . 'affiliate_logs', [
                'program_id'  => $affiliate_program->id,
                'description' => $type,
                'type'        => $type,
                'datecreated' => date('Y-m-d H:i:s'),
                'member_id'   => get_staff_user_id(),
            ]);
            return true;
        }

        return false;
    }

    /**
     * [change_status_order
     * @param  array  $data         
     * @param  string  $order_code 
     * @param  integer $admin_action 
     * @return bool                
     */
    public function change_status_order($data, $order_code,$admin_action = 0){
        $this->db->where('order_code',$order_code);
        $data_update['reason'] = _l($data['cancelReason']);
        $data_update['status'] = $data['status'];
        $data_update['admin_action'] = $admin_action;
        $this->db->update(db_prefix().'affiliate_orders',$data_update);
        if ($this->db->affected_rows() > 0) {
            $channel = $this->get_cart_by_order_code($order_code);
            if($channel){
                $regex = "/\(([^)]*)\)/";
                preg_match_all($regex,$channel->channel, $matches);
                if (isset($matches[1][0])) {
                    $this->db->where('name_channel', $matches[1][0]);
                    $channel_data = $this->db->get(db_prefix().'affiliate_woocommerce_channels')->row();
                    if($channel_data){
                        $this->load->model('sync_woo_model');
                        $woocommerce = $this->sync_woo_model->init_connect_woocommerce($channel_data->id);
                        $status = af_get_status_by_index_woo($data['status']);
                        if($status != ''){
                            $data = [
                                'update' => [
                                    [
                                        'id' => $order_code,
                                        'status' => $status
                                    ]
                                ]
                            ];
                            $woocommerce->post('orders/batch', $data);
                        }
                    }
                }
                return true;
            }
            return true;
        }
        return false;
    }

    /**
     * get cart by order code
     * @param  string $order code 
     * @return object or array               
    */
    public function get_cart_by_order_code($order_code = ''){
        if($order_code != ''){
            $this->db->where('order_code', $order_code);
            return $this->db->get(db_prefix().'affiliate_orders')->row();
        }
        else{     
            return $this->db->get(db_prefix().'affiliate_orders')->result_array();
        }
    }

    /**
     * delete transaction
     *
     * @param      integer  $id     The identifier
     * @return boolean
     */
    public function delete_transaction($id)
    {
        $this->db->where('id', $id);
        $this->db->where('status', 0);
        $this->db->delete(db_prefix() . 'affiliate_transactions');

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}
