<?php

defined('BASEPATH') or exit('No direct script access allowed');

class affiliate extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('affiliate_model');
        hooks()->do_action('affiliate_init');
    }

    /**
     * affiliate orders
     *  @return view
     */
    public function affiliate_orders()
    {
        if (!affiliate_has_permission('affiliate_orders', '', 'view')) {
            access_denied('affiliate_orders');
        }
        $data['title']   = _l('affiliate_orders');
        $data['members'] = $this->affiliate_model->get_member();
        $this->load->view('affiliate_orders/manage', $data);
    }

    /**
     * affiliate logs
     *  @return view
     */
    public function affiliate_logs()
    {
        if (!affiliate_has_permission('affiliate_logs', '', 'view')) {
            access_denied('affiliate_logs');
        }
        $data['title']              = _l('affiliate_logs');
        $data['members']            = $this->affiliate_model->get_member();
        $data['affiliate_programs'] = $this->affiliate_model->get_affiliate_program();

        $this->load->view('affiliate_logs/manage', $data);
    }

    /**
     *  manage members
     *  @return view
     */
    public function members()
    {
        if (!affiliate_has_permission('member', '', 'view')) {
            access_denied('member');
        }
        $this->load->model('staff_model');

        $data          = [];
        $data['group'] = $this->input->get('group');

        $data['tab'][] = 'member_list';
        $data['tab'][] = 'manage_admin';
        $data['tab'][] = 'registration_approval';
        if ($data['group'] == '') {
            $data['group'] = 'member_list';
        }

        if ($data['group'] == 'member_list') {
            $data['members_chart'] = json_encode($this->affiliate_model->get_data_member_chart());
        } elseif ($data['group'] == 'manage_admin') {
            $data['staffs'] = $this->staff_model->get('', 'active = 1 and staffid NOT IN (select staffid from ' . db_prefix() . 'affiliate_admins)');
        } elseif ($data['group'] == 'registration_approval') {

        }

        $data['title']        = _l($data['group']);
        $data['tabs']['view'] = 'members/' . $data['group'];

        $this->load->view('members/manage', $data);
    }

    /**
     *
     *  add or edit member
     *  @param  integer  $id     The identifier
     *  @return view
     */
    public function member($id = '')
    {
        if (!affiliate_has_permission('member', '', 'edit') && !affiliate_has_permission('member', '', 'create')) {
            access_denied('affiliate_member');
        }

        if ($this->input->post()) {
            $data = $this->input->post();

            if(isset($data['password'])){
                $data['password'] = trim($this->input->post('password', false));
            }
            if ($id == '') {
                if (!affiliate_has_permission('member', '', 'create')) {
                    access_denied('affiliate_member');
                }
                $id = $this->affiliate_model->add_member($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('member')));
                    redirect(admin_url('affiliate/members'));
                }
            } else {
                if (!affiliate_has_permission('member', '', 'edit')) {
                    access_denied('affiliate_member');
                }
                $success = $this->affiliate_model->update_member($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('member')));
                }
                redirect(admin_url('affiliate/members'));
            }
        }

        if ($id == '') {
            $title           = _l('add_new', _l('member'));
            $data['members'] = $this->affiliate_model->get_member('', ['status' => 1, 'approval' => 1]);
        } else {
            $data['member']  = $this->affiliate_model->get_member($id);
            $title           = _l('edit', _l('member'));
            $data['members'] = $this->affiliate_model->get_member('', 'status = 1 and approval = 1 and id != ' . $id);
        }

        $data['id']  = $id;
        $data['groups'] = $this->affiliate_model->get_member_group();
        $data['title']  = $title;
        $this->load->view('members/member', $data);
    }

    /**
     *  add member group
     *  @return json
     */
    public function member_group()
    {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();

            if ($data['id'] == '') {
                if (!affiliate_has_permission('settings', '', 'create')) {
                    access_denied('settings');
                }
                $id      = $this->affiliate_model->add_member_group($data);
                $message = $id ? _l('added_successfully', _l('member_group')) : '';
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $message,
                    'id'      => $id,
                    'name'    => $data['name'],
                ]);
            } else {
                if (!affiliate_has_permission('settings', '', 'edit')) {
                    access_denied('settings');
                }
                $success = $this->affiliate_model->edit_member_group($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('member_group'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    /**
     *  affiliate member table
     *
     *  @return json
     */
    public function affiliate_member_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                 db_prefix() .'affiliate_users.id as id',
                'firstname',
                'lastname',
                'country',
                'email',
                'username',
                'phone',
                'vendor_status',
                'status',
            ];
            $where = [];
            array_push($where, 'AND approval = 1');

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_users';
            $join         = [];

            $custom_fields = get_table_custom_fields('aff_member');
            $customFieldsColumns = [];

            foreach ($custom_fields as $key => $field) {
                $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
                array_push($customFieldsColumns, $selectAs);
                array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
                array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'affiliate_users.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
            }

            // Fix for big queries. Some hosting have max_join_limit
            if (count($custom_fields) > 4) {
                @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
            }

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['email'] . '"><label></label></div>';

                $row[]        = $aRow['firstname'] . ' ' . $aRow['lastname'];
                $country_name = '';
                if ($aRow['country'] != '') {
                    $country = get_country($aRow['country']);
                    if ($country->short_name) {
                        $country_name = $country->short_name;
                    }
                }
                $row[] = $country_name;
                $row[] = $aRow['email'];
                $row[] = $aRow['username'];
                $row[] = '';
                $row[] = $aRow['phone'];
                $row[] = $aRow['vendor_status'];

                // Custom fields add values
                foreach ($customFieldsColumns as $customFieldColumn) {
                    $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
                }

                $options = '';
                if (affiliate_has_permission('wallet', '', 'create')) {
                    $options .= icon_btn('#', 'fa fa-money-bill', 'btn-success', [
                        'title'   => _l('add_transaction'),
                        'onclick' => 'add_transaction(' . $aRow['id'] . '); return false;',
                    ]);
                }

                if (affiliate_has_permission('member', '', 'edit')) {
                    $options .= icon_btn('affiliate/member/' . $aRow['id'], 'fa fa-edit', 'btn-default', ['title' => _l('edit')]);
                }

                if (affiliate_has_permission('member', '', 'delete')) {
                    $options .= icon_btn('affiliate/delete_member/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['title' => _l('delete')]);
                }

                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     *  Registration list table
     *
     *  @return json
     */
    public function registration_list_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                'id',
                'firstname',
                'country',
                'email',
                'username',
                'phone',
                'referral_code',
            ];
            $where = [];
            array_push($where, 'AND (approval != 1 or approval is null)');

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_users';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['lastname']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row          = [];
                $row[]        = $aRow['firstname'] . ' ' . $aRow['lastname'];
                $country_name = '';
                if ($aRow['country'] != '') {
                    $country = get_country($aRow['country']);
                    if ($country->short_name) {
                        $country_name = $country->short_name;
                    }
                }
                $row[] = $country_name;
                $row[] = $aRow['email'];
                $row[] = $aRow['username'];
                $row[] = $aRow['phone'];
                $row[] = $aRow['referral_code'];

                $options = '';
                if (affiliate_has_permission('member', '', 'approval')) {
                    $options .= icon_btn('affiliate/approve_registration/' . $aRow['id'], 'fa fa-check', 'btn-success _delete', ['title' => _l('approve')]);
                }

                if (affiliate_has_permission('member', '', 'delete')) {
                    $options .= icon_btn('affiliate/delete_registration/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['title' => _l('delete')]);
                }
                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * delete member
     *
     * @param  integer  $id     The identifier
     * @return json
     */
    public function delete_member($id)
    {
        if (!affiliate_has_permission('member', '', 'delete')) {
            access_denied('affiliate_member');
        }

        if (!$id) {
            redirect(admin_url('affiliate/members'));
        }

        $success = $this->affiliate_model->delete_member($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('member')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('member')));
        }
        redirect(admin_url('affiliate/members'));
    }

    /**
     *  affiliate member group table
     *
     *  @return json
     */
    public function affiliate_member_group_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                'id',
                'name',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_user_groups';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['name'];

                $options = '';
                if (affiliate_has_permission('settings', '', 'edit')) {
                    $options .= icon_btn('#', 'fa fa-pencil-square', 'btn-default', ['data-toggle' => 'modal', 'data-target' => '#member_group_modal', 'data-id' => $aRow['id']]);
                }
                if (affiliate_has_permission('settings', '', 'delete')) {
                    $options .= icon_btn('affiliate/delete_member_group/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete');
                }

                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * delete affiliate member group
     *
     * @param  integer  $id     The identifier
     * @return redirect
     */
    public function delete_member_group($id)
    {
        if (!affiliate_has_permission('settings', '', 'delete')) {
            access_denied('settings');
        }
        if (!$id) {
            redirect(admin_url('affiliate/settings?group=member_group'));
        }

        $success = $this->affiliate_model->delete_member_group($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('member')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('member')));
        }
        redirect(admin_url('affiliate/settings?group=member_group'));
    }

    /**
     *  affiliate member table
     *
     *  @return json
     */
    public function affiliate_admin_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                'id',
                'firstname',
                'email',
                'phonenumber',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_admins';
            $join         = ['LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'affiliate_admins.staffid'];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['lastname', db_prefix() . 'staff.staffid as staff_id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('staff/member/' . $aRow['staff_id']) . '">' . $aRow['firstname'] . ' ' . $aRow['lastname'] . '</a>';

                $row[] = $aRow['email'];
                $row[] = $aRow['phonenumber'];

                $options = '';
                if (affiliate_has_permission('member', '', 'edit')) {
                    $options = icon_btn('#', 'fa fa-edit', 'btn-default', [
                        'title'   => _l('edit'),
                        'onclick' => 'edit_admin(' . $aRow['staff_id'] . '); return false;',
                    ]);
                }
                if (affiliate_has_permission('member', '', 'delete')) {
                    $options .= icon_btn('affiliate/delete_affiliate_admins/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['title' => _l('delete')]);
                }
                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * add affiliate admin
     * @return json
     */
    public function add_affiliate_admin()
    {
        $data = $this->input->post();
        if ($data['id'] == '') {
            if (!affiliate_has_permission('member', '', 'create')) {
                access_denied('affiliate_member');
            }
            $success = $this->affiliate_model->add_affiliate_admin($data);
            if($success){
                set_alert('success', _l('added_successfully', _l('admin')));
            }
        } else {
            if (!affiliate_has_permission('member', '', 'edit')) {
                access_denied('affiliate_member');
            }

            $success = $this->affiliate_model->update_permissions($data['permissions'], $data['id']);
            if($success){
                set_alert('success', _l('updated_successfully', _l('admin')));
            }
        }

        redirect(admin_url('affiliate/members?group=manage_admin'));
    }

    /**
     * delete affiliate member group
     *
     * @param  integer  $id     The identifier
     * @return redirect
     */
    public function delete_affiliate_admins($id)
    {
        if (!affiliate_has_permission('member', '', 'delete')) {
            access_denied('member');
        }

        if (!$id) {
            redirect(admin_url('affiliate/members?group=manage_admin'));
        }

        $success = $this->affiliate_model->delete_affiliate_admins($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('admin')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('admin')));
        }
        redirect(admin_url('affiliate/members?group=manage_admin'));
    }

    /**
     * Send mail manually to member
     *
     * @return redirect
     */
    public function send_mail_members()
    {
        if (!affiliate_has_permission('member', '', 'view')) {
            access_denied('member');
        }
        $this->load->model('emails_model');

        $data    = $this->input->post();
        $emails  = explode(', ', $data['emails']);
        $message = $this->input->post('content', false);

        $count = 0;
        foreach ($emails as $key => $email) {
            $success = $this->emails_model->send_simple_email($email, $data['subject'], $message);

            if ($success) {
                $count++;
            }
        }

        if ($count > 0) {
            set_alert('success', _l('send_mail_successful'));
        } else {
            set_alert('warning', _l('send_mail_failed'));
        }

        redirect(admin_url('affiliate/members'));
    }

    /**
     * manage affiliate programs
     * @return view
     */
    public function affiliate_programs()
    {
        if (!affiliate_has_permission('affiliate_program', '', 'view')) {
            access_denied('affiliate_programs');
        }
        $data          = [];
        $data['title'] = _l('affiliate_program');

        $this->load->view('affiliate_programs/manage', $data);
    }

    /**
     * add or edit affiliate program
     * @param  integer  $id     The identifier
     * @return  view
     */
    public function affiliate_program($id = '')
    {
        if (!affiliate_has_permission('affiliate_program', '', 'edit') && !affiliate_has_permission('affiliate_program', '', 'create')) {
            access_denied('affiliate_program');
        }

        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                if (!affiliate_has_permission('affiliate_program', '', 'create')) {
                    access_denied('affiliate_program');
                }
                $id = $this->affiliate_model->add_affiliate_program($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('affiliate_program')));
                    redirect(admin_url('affiliate/affiliate_programs'));
                }
            } else {
                if (!affiliate_has_permission('affiliate_program', '', 'edit')) {
                    access_denied('affiliate_program');
                }
                $success = $this->affiliate_model->update_affiliate_program($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('affiliate_program')));
                }
                redirect(admin_url('affiliate/affiliate_programs'));
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('affiliate_program'));
        } else {
            $title                     = _l('edit', _l('affiliate_program'));
            $data['affiliate_program'] = $this->affiliate_model->get_affiliate_program($id);
        }

        $data['clients']           = $this->affiliate_model->get_customer();
        $data['client_groups']     = $this->clients_model->get_groups();
        $data['products']          = $this->affiliate_model->get_product_select();
        $data['product_groups']    = $this->affiliate_model->get_product_group_select();
        $data['member_groups']     = $this->affiliate_model->get_member_group();
        $data['members']           = $this->affiliate_model->get_member('', ['status' => 1]);
        $data['program_categorys'] = $this->affiliate_model->get_program_category();

        $data['title'] = $title;
        $this->load->view('affiliate_programs/affiliate_program', $data);
    }

    /**
     * add or edit market category
     * @return  json
     */
    public function program_category()
    {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            if ($data['id'] == '') {
                $id      = $this->affiliate_model->add_program_category($data);
                $message = $id ? _l('added_successfully', _l('program_category')) : '';
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $message,
                    'id'      => $id,
                    'name'    => $data['name'],
                ]);
            } else {
                $success = $this->affiliate_model->edit_program_category($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('program_category'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    /**
     * delete market category
     *
     * @param  redirect
     */
    public function delete_program_category($id)
    {

        if (!affiliate_has_permission('settings', '', 'delete')) {
            access_denied('settings');
        }
        if (!$id) {
            redirect(admin_url('affiliate/settings?group=program_category'));
        }

        $success = $this->affiliate_model->delete_program_category($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('program_category')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('program_category')));
        }
        redirect(admin_url('affiliate/settings?group=program_category'));
    }

    /**
     *  market category table
     *
     *  @return json
     */
    public function program_category_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                'id',
                'name',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_program_categorys';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['name'];

                $options = '';
                if (affiliate_has_permission('settings', '', 'edit')) {
                    $options .= icon_btn('#', 'fa fa-pencil-square', 'btn-default', ['data-toggle' => 'modal', 'data-target' => '#program_category_modal', 'data-id' => $aRow['id']]);
                }

                if (affiliate_has_permission('settings', '', 'delete')) {
                    $options .= icon_btn('affiliate/delete_program_category/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete');
                }
                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * manage wallet
     * @return view
     */
    public function wallet()
    {
        if (!affiliate_has_permission('wallet', '', 'view')) {
            access_denied('wallet');
        }
        $data          = [];
        $data['group'] = $this->input->get('group');

        $data['tab'][] = 'all_transactions';
        if ($data['group'] == '') {
            $data['group'] = 'all_transactions';
        }

        $data['tab'][] = 'withdraw_request';

        if ($data['group'] == 'all_transactions') {

        } elseif ($data['group'] == 'withdraw_request') {

        }

        $data['title']        = _l($data['group']);
        $data['members']      = $this->affiliate_model->get_member();
        $data['tabs']['view'] = 'wallet/' . $data['group'];
        $this->load->view('wallet/manage', $data);
    }

    /**
     * approve registration
     * @param  integer $id registration
     * @return redirect
     */
    public function approve_registration($id)
    {
        if (!affiliate_has_permission('member', '', 'approval')) {
            access_denied('member');
        }

        if (!$id) {
            redirect(admin_url('affiliate/members?tab=tab_registration_list'));
        }

        $success = $this->affiliate_model->update_member(['approval' => 1], $id);

        if ($success == true) {
            $this->load->model('emails_model');
            $member  = $this->affiliate_model->get_member($id);

            $this->affiliate_model->commission_new_registrantion($id);
            $success = $this->emails_model->send_simple_email($member->email, _l('registration_approval'), 'Hi '.$member->firstname.' '.$member->lastname.',<br><br>Your account has been approved: <a href="' . site_url('affiliate/authentication_affiliate/login') . '">Login link</a>');

            set_alert('success', _l('approve', _l('registration')));
        } else {
            set_alert('warning', _l('approve', _l('registration')));
        }

        redirect(admin_url('affiliate/members?tab=tab_registration_list'));
    }

    /**
     * delete registration
     *
     * @param  integer $id registration
     * @return redirect
     */
    public function delete_registration($id)
    {

        if (!affiliate_has_permission('member', '', 'approval')) {
            access_denied('member');
        }
        if (!$id) {
            redirect(admin_url('affiliate/members?tab=tab_registration_list'));
        }

        $success = $this->affiliate_model->delete_member($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('registration')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('registration')));
        }
        redirect(admin_url('affiliate/members?tab=tab_registration_list'));
    }

    /**
     * add transaction
     * @return json
     */
    public function add_transaction()
    {
        if (!affiliate_has_permission('wallet', '', 'create')) {
            access_denied('wallet');
        }
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();

            $id      = $this->affiliate_model->add_transaction($data);
            $message = $id ? _l('added_successfully', _l('transaction')) : '';
            echo json_encode([
                'success' => $id ? true : false,
                'message' => $message,
            ]);

        }
    }

    /**
     * all transaction table
     * @return json
     */
    public function all_transaction_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');

            $currency = $this->currencies_model->get_base_currency();

            $select = [
                db_prefix() . 'affiliate_transactions.id as id',
                'member_id',
                'amount',
                'type',
                db_prefix() . 'affiliate_transactions.datecreated as datecreated',
                db_prefix() . 'affiliate_transactions.status as status',
            ];
            $where = [];
            if ($this->input->post('member_filter')) {
                $member_filter = $this->input->post('member_filter');
                $member_where  = '';
                foreach ($member_filter as $key => $value) {
                    if ($member_where != '') {
                        $member_where .= ' or member_id = ' . $value;
                    } else {
                        $member_where .= 'member_id = ' . $value;
                    }
                }

                if ($member_where != '') {
                    array_push($where, 'AND (' . $member_where . ')');
                }
            }
            if ($this->input->post('status')) {
                $status       = $this->input->post('status');
                $status_where = '';
                foreach ($status as $key => $value) {
                    if ($value == 3) {
                        if ($status_where != '') {
                            $status_where .= ' or ' . db_prefix() . 'affiliate_transactions.status = 0';
                        } else {
                            $status_where .= db_prefix() . 'affiliate_transactions.status = 0';
                        }
                    } else {
                        if ($status_where != '') {
                            $status_where .= ' or ' . db_prefix() . 'affiliate_transactions.status = ' . $value;
                        } else {
                            $status_where .= db_prefix() . 'affiliate_transactions.status = ' . $value;
                        }
                    }
                }

                if ($status_where != '') {
                    array_push($where, 'AND (' . $status_where . ')');
                }
            }

            $from_date = '';
            $to_date   = '';
            if ($this->input->post('from_date')) {
                $from_date = $this->input->post('from_date');
                if (!$this->affiliate_model->check_format_date($from_date)) {
                    $from_date = to_sql_date($from_date);
                }
            }

            if ($this->input->post('to_date')) {
                $to_date = $this->input->post('to_date');
                if (!$this->affiliate_model->check_format_date($to_date)) {
                    $to_date = to_sql_date($to_date);
                }
            }
            if ($from_date != '' && $to_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_transactions.datecreated >= "' . $from_date . '" and ' . db_prefix() . 'affiliate_transactions.datecreated <= "' . $to_date . '")');
            } elseif ($from_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_transactions.datecreated >= "' . $from_date . '")');
            } elseif ($to_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_transactions.datecreated <= "' . $to_date . '")');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_transactions';
            $join         = ['LEFT JOIN ' . db_prefix() . 'affiliate_users ON ' . db_prefix() . 'affiliate_users.id = ' . db_prefix() . 'affiliate_transactions.member_id'];
            $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['firstname', 'lastname', 'username', db_prefix() . 'affiliate_users.id as memberid']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $row[] = _d($aRow['datecreated']);

                $row[] = $aRow['username'];
                $row[] = app_format_money($aRow['amount'], $currency->name);

                $row[] = _l($aRow['type']);
                if ($aRow['status'] == 1) {
                    $status_name = _l('waiting');
                    $label_class = 'info';
                } elseif ($aRow['status'] == 2) {
                    $status_name = _l('invoice_status_paid');
                    $label_class = 'success';
                } else {
                    $status_name = _l('invoice_status_unpaid');
                    $label_class = 'default';
                }
                $row[] = '<span class="label label-' . $label_class . ' s-status transaction-status-' . $aRow['status'] . '">' . $status_name . '</span>';

                $options = '';

                if (affiliate_has_permission('wallet', '', 'delete')) {
                    if ($aRow['status'] == 0) {
                        $options = icon_btn('affiliate/delete_transaction/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['title' => _l('delete')]);
                    } 
                } 

                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     *  affiliate program table
     *
     *  @return json
     */
    public function affiliate_program_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                'id',
                'name',
                'from_date',
                'to_date',
                'priority',
                'enable_discount',
                'enable_commission',
                'datecreated',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_programs';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['name'];
                $row[] = _d($aRow['from_date']);
                $row[] = _d($aRow['to_date']);
                $row[] = $aRow['priority'];
                $row[] = $aRow['enable_discount'];
                $row[] = $aRow['enable_commission'];
                $row[] = _dt($aRow['datecreated']);

                $options = '';
                if (affiliate_has_permission('affiliate_program', '', 'edit')) {
                    $options .= icon_btn('affiliate/affiliate_program/' . $aRow['id'], 'fa fa-edit', 'btn-default _delete', ['title' => _l('edit')]);
                }
                if (affiliate_has_permission('affiliate_program', '', 'delete')) {
                    $options .= icon_btn('affiliate/delete_affiliate_program/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['title' => _l('delete')]);
                }

                $row[]              = $options;
                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     *  Affiliate logs table
     *
     *  @return json
     */
    public function affiliate_log_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                db_prefix() . 'affiliate_logs.id',
                'name',
                'user_ip',
                'description',
                'type',
            ];
            $where = [];
            if ($this->input->post('member_filter')) {
                $member_filter = $this->input->post('member_filter');
                $member_where  = '';
                foreach ($member_filter as $key => $value) {
                    if ($member_where != '') {
                        $member_where .= ' or member_id = ' . $value;
                    } else {
                        $member_where .= 'member_id = ' . $value;
                    }
                }

                if ($member_where != '') {
                    array_push($where, 'AND (' . $member_where . ')');
                }
            }

            if ($this->input->post('affiliate_programs')) {
                $affiliate_programs       = $this->input->post('affiliate_programs');
                $affiliate_programs_where = '';
                foreach ($affiliate_programs as $key => $value) {
                    if ($affiliate_programs_where != '') {
                        $affiliate_programs_where .= ' or program_id = ' . $value;
                    } else {
                        $affiliate_programs_where .= 'program_id = ' . $value;
                    }
                }

                if ($affiliate_programs_where != '') {
                    array_push($where, 'AND (' . $affiliate_programs_where . ')');
                }
            }

            $from_date = '';
            $to_date   = '';
            if ($this->input->post('from_date')) {
                $from_date = $this->input->post('from_date');
                if (!$this->affiliate_model->check_format_date($from_date)) {
                    $from_date = to_sql_date($from_date);
                }
            }

            if ($this->input->post('to_date')) {
                $to_date = $this->input->post('to_date');
                if (!$this->affiliate_model->check_format_date($to_date)) {
                    $to_date = to_sql_date($to_date);
                }
            }

            if ($from_date != '' && $to_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_logs.datecreated >= "' . $from_date . '" and ' . db_prefix() . 'affiliate_logs.datecreated <= "' . $to_date . '")');
            } elseif ($from_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_logs.datecreated >= "' . $from_date . '")');
            } elseif ($to_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_logs.datecreated <= "' . $to_date . '")');
            }
            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_logs';
            $join         = ['LEFT JOIN ' . db_prefix() . 'affiliate_programs ON ' . db_prefix() . 'affiliate_programs.id = ' . db_prefix() . 'affiliate_logs.program_id'];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'affiliate_logs.datecreated', db_prefix() . 'affiliate_programs.id as affiliate_program_id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $row[] = $aRow['name'];
                $row[] = $aRow['user_ip'];
                $row[] = _l($aRow['type']);
                $row[] = $aRow['description'];
                $row[] = _dt($aRow['datecreated']);

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     *  withdraw request table
     *
     *  @return json
     */
    public function withdraw_request_table()
    {
        if ($this->input->is_ajax_request()) {

            $this->load->model('currencies_model');

            $currency = $this->currencies_model->get_base_currency();

            $select = [
                db_prefix() . 'affiliate_withdraws.id as withdraw_id',
                db_prefix() . 'affiliate_withdraws.datecreated as withdraw_datecreated',
                'name',
                'total',
                db_prefix() . 'affiliate_withdraws.status as withdraw_status',
                'username',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_withdraws';
            $join         = ['LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'affiliate_withdraws.paymentmode',
                'LEFT JOIN ' . db_prefix() . 'affiliate_users ON ' . db_prefix() . 'affiliate_users.id = ' . db_prefix() . 'affiliate_withdraws.member_id'];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = _d($aRow['withdraw_datecreated']);
                $row[] = $aRow['username'];
                $row[] = $aRow['name'];
                $row[] = app_format_money($aRow['total'], $currency->name);

                if ($aRow['withdraw_status'] == 1) {
                    $status_name = _l('invoice_status_paid');
                    $label_class = 'success';
                } elseif ($aRow['withdraw_status'] == 2) {
                    $status_name = _l('rejected');
                    $label_class = 'danger';
                } else {
                    $status_name = _l('invoice_status_unpaid');
                    $label_class = 'default';
                }
                $row[] = '<span class="label label-' . $label_class . ' s-status withdraw-status-' . $aRow['withdraw_id'] . '">' . $status_name . '</span>';

                $options = icon_btn('#', 'fa fa-eye', 'btn-default', [
                    'title'   => _l('view'),
                    'onclick' => 'view_withdraw(' . $aRow['withdraw_id'] . '); return false;',
                ]);

                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * get withdraw detail data
     * @param  integer $id
     * @return json
     */
    public function get_withdraw_detail_data($id)
    {
        $this->load->model('currencies_model');
        $currency = $this->currencies_model->get_base_currency();

        $withdraw_detail = $this->affiliate_model->get_withdraw_detail($id);

        $html = '';
        if ($withdraw_detail) {
            if ($withdraw_detail->withdraw_status == 1) {
                $status_name = _l('invoice_status_paid');
                $label_class = 'success';
                $btn         = '<button group="button" class="btn btn-default" data-dismiss="modal">' . _l('close') . '</button>';
            } elseif ($withdraw_detail->withdraw_status == 2) {
                $status_name = _l('rejected');
                $label_class = 'danger';
                $btn         = '<button group="button" class="btn btn-default" data-dismiss="modal">' . _l('close') . '</button>';
            } else {
                $status_name = _l('invoice_status_unpaid');
                $label_class = 'default';

                $btn = '<button group="button" class="btn btn-default" data-dismiss="modal">' . _l('close') . '</button>
                <a href="#" onclick="approve(' . $withdraw_detail->withdraw_id . ', 2); return false;" class="btn btn-danger">' . _l('reject') . '</a>
                <a href="#" onclick="approve(' . $withdraw_detail->withdraw_id . ', 1); return false;" class="btn btn-success">' . _l('approve') . '</a>';

            }

            $html = '<table class="table border table-striped no-margin">
                          <tbody>
                              <tr class="project-overview">
                                <td class="bold" width="30%">' . _l('username') . '</td>
                                <td>' . $withdraw_detail->username . '</td>
                              </tr>
                              <tr class="project-overview">
                                <td class="bold" width="30%">' . _l('datecreated') . '</td>
                                <td>' . _d($withdraw_detail->withdraw_datecreated) . '</td>
                              </tr>
                              <tr class="project-overview">
                                <td class="bold" width="30%">' . _l('payment_mode') . '</td>
                                <td>' . $withdraw_detail->name . '</td>
                              </tr>
                              <tr class="project-overview">
                                <td class="bold" width="30%">' . _l('total') . '</td>
                                <td>' . app_format_money($withdraw_detail->total, $currency->name) . '</td>
                              </tr>
                              <tr class="project-overview">
                                <td class="bold" width="30%">' . _l('status') . '</td>
                                <td><span class="label label-' . $label_class . ' s-status withdraw-status-' . $withdraw_detail->withdraw_status . '">' . $status_name . '</span></td>
                              </tr>
                              <tr class="project-overview">
                                <td class="bold" width="30%">' . _l('transactions') . '</td>
                                <td>
                                    <ul class="list-group">';
            foreach ($withdraw_detail->transactions as $value) {
                $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">
                                          ' . _l($value['type']) . ' <small>' . _d($value['datecreated']) . '</small>
                                          <span class="badge badge-primary badge-pill">' . app_format_money($value['amount'], $currency->name) . '</span>
                                        </li>';
            }
            $html .= '</ul>
                                </td>
                              </tr>
                            </tbody>
                      </table>';

        }
        echo json_encode(['data' => $html, 'btn' => $btn]);
        die();
    }

    /**
     * approve withdraw
     * @param  integer $id
     * @param  integer $status 1: approve, 2: reject
     * @return json
     */
    public function approve_withdraw($id, $status)
    {
        if (!affiliate_has_permission('wallet', '', 'approval')) {
            access_denied('wallet');
        }
        $success = $this->affiliate_model->approve_withdraw($id, $status);
        $message = $success ? _l('approve_withdraw_successfully') : '';
        if ($status == 1) {
            $btn_text = _l('invoice_status_paid');
        } else {
            $btn_text = _l('rejected');
        }

        echo json_encode([
            'success'  => $success,
            'message'  => $message,
            'btn_text' => $btn_text,
        ]);
    }

    /**
     *  affiliate member table
     *
     *  @return json
     */
    public function affiliate_order_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $currency = $this->currencies_model->get_base_currency();

            $select = [
                db_prefix() . 'affiliate_orders.id',
                'order_code',
                'company',
                'CONCAT(firstname," ",lastname) as member_name',
                'approve_status',
                db_prefix() . 'affiliate_orders.datecreated',
            ];
            $where = [];
            if ($this->input->post('status')) {
                $status = $this->input->post('status');
                array_push($where, 'AND (approve_status = 0 or approve_status is null)');
            }


            if ($this->input->post('member_filter')) {
                $member_filter = $this->input->post('member_filter');
                $member_where  = '';
                foreach ($member_filter as $key => $value) {
                    if ($member_where != '') {
                        $member_where .= ' or member_id = ' . $value;
                    } else {
                        $member_where .= 'member_id = ' . $value;
                    }
                }

                if ($member_where != '') {
                    array_push($where, 'AND (' . $member_where . ')');
                }
            }
            if ($this->input->post('approve_status')) {
                $approve_status       = $this->input->post('approve_status');
                $approve_status_where = '';
                foreach ($approve_status as $key => $value) {
                    if ($value == 3) {
                        if ($approve_status_where != '') {
                            $approve_status_where .= ' or (approve_status = 0 or approve_status is null)';
                        } else {
                            $approve_status_where .= '(approve_status = 0 or approve_status is null)';
                        }
                    } else {
                        if ($approve_status_where != '') {
                            $approve_status_where .= ' or approve_status = ' . $value;
                        } else {
                            $approve_status_where .= 'approve_status = ' . $value;
                        }
                    }
                }

                if ($approve_status_where != '') {
                    array_push($where, 'AND (' . $approve_status_where . ')');
                }
            }
            $from_date = '';
            $to_date   = '';
            if ($this->input->post('from_date')) {
                $from_date = $this->input->post('from_date');
                if (!$this->affiliate_model->check_format_date($from_date)) {
                    $from_date = to_sql_date($from_date);
                }
            }

            if ($this->input->post('to_date')) {
                $to_date = $this->input->post('to_date');
                if (!$this->affiliate_model->check_format_date($to_date)) {
                    $to_date = to_sql_date($to_date);
                }
            }

            if ($from_date != '' && $to_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_orders.datecreated >= "' . $from_date . '" and ' . db_prefix() . 'affiliate_orders.datecreated <= "' . $to_date . '")');
            } elseif ($from_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_orders.datecreated >= "' . $from_date . '")');
            } elseif ($to_date != '') {
                array_push($where, 'AND (' . db_prefix() . 'affiliate_orders.datecreated <= "' . $to_date . '")');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'affiliate_orders';
            $join         = ['LEFT JOIN ' . db_prefix() . 'affiliate_users ON ' . db_prefix() . 'affiliate_users.id = ' . db_prefix() . 'affiliate_orders.member_id',
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'affiliate_orders.customer'];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['total', db_prefix() . 'affiliate_orders.status']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['order_code'];

                $row[] = $aRow['member_name'];
                $row[] = app_format_money($aRow['total'], $currency->name);

                $row[] = _dt($aRow[db_prefix() . 'affiliate_orders.datecreated']);

                $status = af_get_status_by_index($aRow['status']);
                $row[] = '<span class="label label-success">'.$status.'</span>';  

                $options = icon_btn(admin_url('affiliate/order_detail/' . $aRow[db_prefix() . 'affiliate_orders.id']), 'fa fa-eye', 'btn-default', [ 'title' => _l('view') ]);

                $row[] = $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * view order detail
     * @param  integer $id the order id
     * @return view
     */
    public function order_detail($id)
    {
        if (!affiliate_has_permission('affiliate_orders', '', 'view')) {
            access_denied('affiliate_orders');
        }
        $data['title'] = _l('order_detail');
        $data['order'] = $this->affiliate_model->get_order_detail($id);
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $this->load->view('affiliate_orders/order_detail', $data);
    }

    /**
     * approve order
     * @param  integer $id the order id
     * @param  integer $status 1: approve, 2: reject
     * @return json
     */
    public function approve_order($id, $status)
    {
        if (!affiliate_has_permission('affiliate_orders', '', 'approval')) {
            access_denied('affiliate_orders');
        }
        $success = $this->affiliate_model->approve_order($id, $status);
        $message = $success ? _l('approve_order_successfully') : '';
        if ($status == 1) {
            $btn_text = _l('approved');
        } else {
            $btn_text = _l('rejected');
        }

        echo json_encode([
            'success'  => $success,
            'message'  => $message,
            'btn_text' => $btn_text,
        ]);
    }

    /**
     * create invoice by order
     * @param  integer $id the order id
     * @return json
     */
    public function create_invoice_by_order($id)
    {
        if (!affiliate_has_permission('affiliate_orders', '', 'create')) {
            access_denied('affiliate_orders');
        }
        $invoice_id = $this->affiliate_model->create_invoice_by_order($id);
        $message    = $invoice_id ? _l('create_invoice_successfully') : '';

        $invoice_number = '';
        if ($invoice_id > 0) {
            $invoice_number = format_invoice_number($invoice_id);
        }
        echo json_encode([
            'invoice_number' => $invoice_number,
            'message'        => $message,
        ]);
    }

    /**
     * manage setting
     * @return view
     */
    public function settings()
    {
        if (!affiliate_has_permission('settings', '', 'view')) {
            access_denied('reports');
        }
        $data          = [];
        $data['group'] = $this->input->get('group');
        $data['title'] = _l($data['group']);

        $data['tab'][] = 'member_group';
        $data['tab'][] = 'program_category';
        $data['tab'][] = 'general_settings';
        if ($data['group'] == '') {
            $data['group'] = 'member_group';
        }

        if ($data['group'] == 'general_settings') {
            
        }


        $data['tabs']['view'] = 'settings/' . $data['group'];
        $this->load->view('settings/manage', $data);
    }

    public function get_affiliate_admin_data($id)
    {
        $admin = $this->affiliate_model->get_affiliate_admin($id);

        echo json_encode($admin);die();
    }

    /**
     * affiliate reports
     *
     * @return view
     */
    public function reports()
    {
        if (!affiliate_has_permission('reports', '', 'view')) {
            access_denied('reports');
        }
        $data['products'] = $this->affiliate_model->get_product_select();
        $data['title']    = _l('reports');
        $data['members']  = $this->affiliate_model->get_member();
        $this->load->view('reports/manage_report', $data);
    }

    /**
     *  transaction table
     *
     *  @return json
     */
    public function report_transaction_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');

            $select = [
                'invoice_id',
                db_prefix() . 'affiliate_transactions.datecreated as transactions_datecreated',
                'member_id',
                'amount',
                'type',
                db_prefix() . 'affiliate_transactions.status as status',
                'affiliate_program_id',
            ];
            $where              = [];
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'affiliate_transactions.datecreated');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('staff_filter')) {
                $staff_filter = $this->input->post('staff_filter');
                array_push($where, 'AND member_id IN (' . implode(', ', $staff_filter) . ')');
            }
            if ($this->input->post('products_services')) {
                $products_services = $this->input->post('products_services');
                $where_item        = '';
                if ($products_services != '') {
                    foreach ($products_services as $key => $value) {
                        $item_name = $this->affiliate_model->get_item_name($value);
                        if ($where_item == '') {
                            $where_item .= '(select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
                        } else {
                            $where_item .= ' or (select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
                        }
                    }
                }

                if ($where_item != '') {
                    array_push($where, 'AND ' . $where_item);
                }
            }

            if ($this->input->post('status')) {
                $statuss      = $this->input->post('status');
                $where_status = '';
                if ($statuss != '') {
                    foreach ($statuss as $key => $value) {
                        if ($value == 3) {
                            $value = 0;
                        }
                        if ($where_status == '') {
                            $where_status .= db_prefix() . 'affiliate_transactions.status = ' . $value;
                        } else {
                            $where_status .= ' or ' . db_prefix() . 'affiliate_transactions.status = ' . $value;
                        }
                    }
                }

                if ($where_status != '') {
                    array_push($where, 'AND (' . $where_status . ')');
                }
            }

            if (!affiliate_has_permission('reports', '', 'view')) {
                return false;
            }

            $currency     = $this->currencies_model->get_base_currency();
            $aColumns     = $select;
            $sIndexColumn = 'invoice_id';
            $sTable       = db_prefix() . 'affiliate_transactions';
            $join         = ['LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'affiliate_transactions.invoice_id',
                'LEFT JOIN ' . db_prefix() . 'affiliate_programs ON ' . db_prefix() . 'affiliate_programs.id = ' . db_prefix() . 'affiliate_transactions.affiliate_program_id'];

            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'affiliate_programs.name as affiliate_program_name', 'total']);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'            => 0,
                'total_commission' => 0,
            ];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['affiliate_program_name'];

                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoice_id']) . '" target="_blank">' . format_invoice_number($aRow['invoice_id']) . '</a>';

                $row[] = $_data;

                $row[] = _d($aRow['transactions_datecreated']);
                $row[] = get_affiliate_full_name($aRow['member_id']);

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['amount'], $currency->name);
                $footer_data['total_commission'] += $aRow['amount'];

                $row[] = _l($aRow['type']);

                if ($aRow['status'] == 1) {
                    $status_name = _l('waiting');
                    $label_class = 'info';
                } elseif ($aRow['status'] == 2) {
                    $status_name = _l('invoice_status_paid');
                    $label_class = 'success';
                } else {
                    $status_name = _l('invoice_status_unpaid');
                    $label_class = 'default';
                }
                $row[] = '<span class="label label-' . $label_class . ' s-status transaction-status-' . $aRow['status'] . '">' . $status_name . '</span>';

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    /**
     * Gets the where report period.
     *
     * @param      string  $field  The field
     *
     * @return     string  The where report period.
     */
    private function get_where_report_period($field = 'date')
    {
        $months_report      = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $from_date . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            }
        }

        return $custom_date_select;
    }

    /**
     * get data transaction chart
     *
     * @return     json
     */
    public function report_transaction_chart()
    {
        $this->load->model('currencies_model');

        $staff_filter = [];
        if ($this->input->post('staff_filter')) {
            $staff_filter = $this->input->post('staff_filter');
        }

        if (!has_permission('commission', '', 'view')) {
            $staff_filter = [get_staff_user_id()];
        }

        $products_services = [];
        if ($this->input->post('products_services')) {
            $products_services = $this->input->post('products_services');
        }
        $year_report   = $this->input->post('year');
        $currency      = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if ($currency) {
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }

        $is_client = 0;
        if ($this->input->post('is_client')) {
            $is_client = $this->input->post('is_client');
        }
        $data = $this->affiliate_model->transaction_chart($year_report, $staff_filter, $products_services);
        echo json_encode([
            'data_total' => $data['amount'],
            'data_paid'  => $data['amount_paid'],
            'month'      => $data['month'],
            'unit'       => $currency_unit,
            'name'       => $currency_name,
        ]);
        die();
    }

    /**
     * view dashboard
     * @return view
     */
    public function dashboard()
    {
        if (!affiliate_has_permission('dashboard', '', 'view')) {
            access_denied('dashboard');
        }
        $data = [];
        $this->load->model('currencies_model');
        $data['currency'] = $this->currencies_model->get_base_currency();

        $data['title']             = _l('als_dashboard');
        $data['transaction_count'] = $this->affiliate_model->get_transaction_count();
        $this->load->view('dashboard/dashboard', $data);
    }

    /**
     * get data dashboard transaction chart
     *
     * @return     json
     */
    public function dashboard_transaction_chart()
    {
        $this->load->model('currencies_model');
        $staff_filter = [];

        $products_services = [];

        $year_report   = $this->input->post('year');
        $currency      = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if ($currency) {
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }
        $data = $this->affiliate_model->dashboard_commission_chart('', [], false);
        echo json_encode([
            'data'  => $data['amount'],
            'month' => $data['month'],
            'unit'  => $currency_unit,
            'name'  => $currency_name,
        ]);
        die();
    }

    /**
     * get data dashboard commission chart
     *
     * @return     json
     */
    public function dashboard_commission_chart()
    {
        $this->load->model('currencies_model');
        $staff_filter = [];

        $products_services = [];

        $year_report   = $this->input->post('year');
        $currency      = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if ($currency) {
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }
        $data = $this->affiliate_model->dashboard_commission_chart('', "type LIKE '%commission%'", false);
        echo json_encode([
            'data'  => $data['amount'],
            'month' => $data['month'],
            'unit'  => $currency_unit,
            'name'  => $currency_name,
        ]);
        die();
    }

    /**
     * get data dashboard discount chart
     *
     * @return     json
     */
    public function dashboard_discount_chart()
    {
        $this->load->model('currencies_model');
        $staff_filter = [];

        $products_services = [];

        $year_report   = $this->input->post('year');
        $currency      = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if ($currency) {
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }
        $data = $this->affiliate_model->dashboard_commission_chart('', "type LIKE '%discount%'", false);

        echo json_encode([
            'data'  => $data['amount'],
            'month' => $data['month'],
            'unit'  => $currency_unit,
            'name'  => $currency_name,
        ]);
        die();
    }

    /**
     * get data dashboard registration chart
     *
     * @return     json
     */
    public function dashboard_registration_chart()
    {
        $this->load->model('currencies_model');
        $staff_filter = [];

        $products_services = [];

        $year_report   = $this->input->post('year');
        $currency      = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if ($currency) {
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }
        $data = $this->affiliate_model->dashboard_registration_chart();

        echo json_encode([
            'data'  => $data['amount'],
            'month' => $data['month'],
            'name'  => '',
        ]);
        die();
    }

    /**
     * delete program
     *
     * @param  integer  $id     The identifier
     * @return json
     */
    public function delete_affiliate_program($id)
    {

        if (!affiliate_has_permission('affiliate_program', '', 'delete')) {
            access_denied('affiliate_program');
        }
        if (!$id) {
            redirect(admin_url('affiliate/affiliate_programs'));
        }

        $success = $this->affiliate_model->delete_affiliate_program($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('affiliate_program')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('affiliate_program')));
        }
        redirect(admin_url('affiliate/affiliate_programs'));
    }

    /**
     * update reset all data commission module
     */
    public function reset_data(){
        $this->load->model('affiliate_model');
        $data = $this->input->post();
        $success = $this->affiliate_model->reset_data($data,$id);
        if($success == true){
            $message = _l('reset_data_successfully');
            set_alert('success', $message);
        }
        redirect(admin_url('commission/setting?group=general_settings'));
    }

    /**
     * update general setting
     */
    public function update_setting(){
        $this->load->model('affiliate_model');
        $data = $this->input->post();
        $success = $this->affiliate_model->update_setting($data,$id);
        if($success == true){
            $message = _l('updated_successfully', _l('general_settings'));
            set_alert('success', $message);
        }
        redirect(admin_url('affiliate/settings?group=general_settings'));
    }

    /**
     * { admin change status }
     *
     * @param  $order_number  The order number
     * @return json
     */
    public function admin_change_status(){
        if($this->input->post()){
            $data = $this->input->post();
            $order_code = $data['order_code'];
            unset($data['order_code']);

            $message = '';
            $insert_id = $this->affiliate_model->change_status_order($data, $order_code, 1);
            if ($insert_id) {
                echo json_encode([
                    'message' => $message,
                    'success' => true
                ]);
                die;
            }               
        }
    }

    public function delete_transaction($id)
    {
        if (!affiliate_has_permission('wallet', '', 'delete')) {
            access_denied('affiliate_transaction');
        }

        if (!$id) {
            redirect(admin_url('affiliate/wallet?group=all_transactions'));
        }

        $success = $this->affiliate_model->delete_transaction($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('transaction')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('transaction')));
        }
        redirect(admin_url('affiliate/wallet?group=all_transactions'));
    }
}
