<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usercontrol extends App_Controller
{

    public $template = [];

    public $data = [];

    public $use_footer = true;

    public $use_submenu = true;

    public $use_navigation = true;

    public function __construct()
    {
        parent::__construct();

        hooks()->do_action('after_clients_area_init', $this);

        $this->load->library('app_usercontrol_area_constructor');

        $this->load->model('affiliate_model');
    }

    /**
     * layout
     * @param  boolean $notInThemeViewFiles
     * @return view                      
     */
    public function layout($notInThemeViewFiles = false)
    {
        /**
         * Navigation and submenu
         * @var boolean
         */

        $this->data['use_navigation'] = $this->use_navigation == true;
        $this->data['use_submenu']    = $this->use_submenu == true;

        /**
         * @since  2.3.2 new variables
         * @var array
         */
        $this->data['navigationEnabled'] = $this->use_navigation == true;
        $this->data['subMenuEnabled']    = $this->use_submenu == true;

        /**
         * Theme head file
         * @var string
         */
        $this->template['head'] = $this->load->view('usercontrol/head', $this->data, true);

        $GLOBALS['customers_head'] = $this->template['head'];

        /**
         * Load the template view
         * @var string
         */
        $module                       = CI::$APP->router->fetch_module();
        $this->data['current_module'] = $module;

        $viewPath = !is_null($module) || $notInThemeViewFiles ? $this->view : 'usercontrol/' . $this->view;

        $this->template['view']    = $this->load->view($viewPath, $this->data, true);
        $GLOBALS['customers_view'] = $this->template['view'];

        /**
         * Theme footer
         * @var string
         */
        $this->template['footer'] = $this->use_footer == true
        ? $this->load->view('usercontrol/footer', $this->data, true)
        : '';
        $GLOBALS['customers_footer'] = $this->template['footer'];

        /**
         * Theme scripts.php file is no longer used since vresion 2.3.0, add app_customers_footer() in themes/[theme]/index.php
         * @var string
         */
        $this->template['scripts'] = '';
        if (file_exists(VIEWPATH . 'usercontrol/scripts.php')) {
            if (ENVIRONMENT != 'production') {
                trigger_error(sprintf('%1$s', 'Clients area theme file scripts.php file is no longer used since version 2.3.0, add app_customers_footer() in themes/[theme]/index.php. You can check the original theme index.php for example.'));
            }

            $this->template['scripts'] = $this->load->view('usercontrol/scripts', $this->data, true);
        }

        /**
         * Load the theme compiled template
         */
        $this->load->view('usercontrol/index', $this->template);
    }

    /**
     * Sets view data
     * @param  array $data
     * @return core/ClientsController
     */
    public function data($data)
    {
        if (!is_array($data)) {
            return false;
        }

        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Set view to load
     * @param  string $view view file
     * @return core/ClientsController
     */
    public function view($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Sets view title
     * @param  string $title
     * @return core/ClientsController
     */
    public function title($title)
    {
        $this->data['title'] = $title;

        return $this;
    }

    /**
     * Disables theme navigation
     * @return core/ClientsController
     */
    public function disableNavigation()
    {
        $this->use_navigation = false;

        return $this;
    }

    /**
     * Disables theme navigation
     * @return core/ClientsController
     */
    public function disableSubMenu()
    {
        $this->use_submenu = false;

        return $this;
    }

    /**
     * Disables theme footer
     * @return core/ClientsController
     */
    public function disableFooter()
    {
        $this->use_footer = false;

        return $this;
    }
    /**
     * { index }
     */
    public function index()
    {
        if (is_affiliate_logged_in()) {
            $data['title']   = _l('als_dashboard');
            $data['is_home'] = true;

            $data['project_statuses'] = $this->projects_model->get_project_statuses();

            $this->data($data);
            $this->view('usercontrol/home');
            $this->layout();
        } else {
            redirect(site_url('affiliate/authentication_affiliate'));
        }
    }

    /**
     * { profile contact }
     */
    public function profile()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        if ($this->input->post('profile')) {
            $this->form_validation->set_rules('firstname', _l('client_firstname'), 'required');
            $this->form_validation->set_rules('lastname', _l('client_lastname'), 'required');

            $this->form_validation->set_message('contact_email_profile_unique', _l('form_validation_is_unique'));
            $this->form_validation->set_rules('email', _l('clients_email'), 'required|valid_email');
            if ($this->form_validation->run() !== false) {

                handle_affiliate_member_profile_image_upload(get_affiliate_user_id());

                $data = $this->input->post();
                if (isset($data['profile'])) {
                    unset($data['profile']);
                }

                $success = $this->affiliate_model->update_member($data, get_affiliate_user_id());

                if ($success == true) {
                    set_alert('success', _l('profile_updated'));
                }

                redirect(site_url('affiliate/usercontrol/profile'));
            }
        } elseif ($this->input->post('change_password')) {

            $this->form_validation->set_rules('oldpassword', _l('clients_edit_profile_old_password'), 'required');
            $this->form_validation->set_rules('newpassword', _l('clients_edit_profile_new_password'), 'required');
            $this->form_validation->set_rules('newpasswordr', _l('clients_edit_profile_new_password_repeat'), 'required|matches[newpassword]');
            if ($this->form_validation->run() !== false) {
                $success = $this->affiliate_model->change_member_password(
                    get_affiliate_user_id(),
                    $this->input->post('oldpassword', false),
                    $this->input->post('newpasswordr', false)
                );

                if (is_array($success) && isset($success['old_password_not_match'])) {
                    set_alert('danger', _l('client_old_password_incorrect'));
                } elseif ($success == true) {
                    set_alert('success', _l('client_password_changed'));
                }

                redirect(site_url('affiliate/usercontrol/profile'));
            }
        }
        $data['contact'] = $this->affiliate_model->get_member(get_affiliate_user_id());
        $data['title']   = _l('clients_profile_heading');
        $this->data($data);
        $this->view('usercontrol/profile/profile');
        $this->layout();
    }

    /**
     * affiliate programs
     * @return view
     */
    public function affiliate_programs()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data['title']              = _l('affiliate_programs');
        $data['affiliate_programs'] = $this->affiliate_model->get_my_affiliate_programs(get_affiliate_user_id());

        $this->data($data);
        $this->view('usercontrol/affiliate_programs/manage');
        $this->layout();
    }

    /**
     * my orders
     * @return view
     */
    public function my_orders()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $this->load->model('currencies_model');

        $data['currency'] = $this->currencies_model->get_base_currency();
        $data['title']    = _l('my_orders');
        $data['orders']   = $this->affiliate_model->get_my_order(get_affiliate_user_id());

        $this->data($data);
        $this->view('usercontrol/my_orders/manage');
        $this->layout();
    }

    /**
     * my logs
     * @return view
     */
    public function my_logs()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data['title'] = _l('my_logs');
        $data['logs']  = $this->affiliate_model->get_affiliate_log_for_member(get_affiliate_user_id());

        $this->data($data);
        $this->view('usercontrol/my_logs/manage');
        $this->layout();
    }

    /**
     * my reports
     * @return view
     */
    public function my_reports()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data['title'] = _l('reports');
        $this->load->model('currencies_model');

        $data['currency']      = $this->currencies_model->get_base_currency();
        $data['products']      = $this->affiliate_model->get_product_select();
        $data['portal_client'] = 1;
        $data['commissions']   = $this->affiliate_model->get_transactions('', ['member_id' => get_affiliate_user_id()]);

        $this->data($data);
        $this->view('usercontrol/my_reports/manage');
        $this->layout();
    }

    /**
     * transaction
     * @return view
     */
    public function transactions()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $this->load->model('currencies_model');
        $this->load->model('payment_modes_model');
        $data                  = [];
        $data['currency']      = $this->currencies_model->get_base_currency();
        $data['payment_modes'] = $this->payment_modes_model->get('', [
            'expenses_only !=' => 1,
        ]);
        $data['title']        = _l('transactions');
        $data['transactions'] = $this->affiliate_model->get_transactions('', ['member_id' => get_affiliate_user_id()]);

        $this->data($data);
        $this->view('usercontrol/transactions/manage');
        $this->layout();
    }

    /**
     *  withdraw request
     * @return view
     */
    public function withdraw_request()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $this->load->model('currencies_model');

        $data['currency']          = $this->currencies_model->get_base_currency();
        $data['title']             = _l('withdraw_request');
        $data['withdraw_requests'] = $this->affiliate_model->get_withdraw_requests('', ['member_id' => get_affiliate_user_id()]);

        $this->data($data);
        $this->view('usercontrol/withdraw_requests/manage');
        $this->layout();
    }

    /**
     * add new withdraw
     * @return redirect
     */
    public function add_withdraw()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data    = $this->input->post();
        $id      = $this->affiliate_model->add_withdraw($data);
        $message = $id ? _l('added_successfully', _l('withdraw')) : '';

        redirect(admin_url('affiliate/usercontrol/transactions'));
    }

    /**
     * my customers
     * @return view
     */
    public function my_customers()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data['customers'] = $this->affiliate_model->get_my_customer(get_affiliate_user_code());

        $data['title'] = _l('my_customers');

        $this->data($data);
        $this->view('usercontrol/my_customers/manage');
        $this->layout();
    }

    /**
     * add new customer
     */
    public function add_customer()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        if ($this->input->post()) {
            $data = $this->input->post();

            define('CONTACT_REGISTERING', true);

            $clientid = $this->clients_model->add([
                'billing_street'      => $data['address'],
                'billing_city'        => $data['city'],
                'billing_state'       => $data['state'],
                'billing_zip'         => $data['zip'],
                'billing_country'     => is_numeric($data['country']) ? $data['country'] : 0,
                'firstname'           => $data['firstname'],
                'lastname'            => $data['lastname'],
                'email'               => $data['email'],
                'contact_phonenumber' => $data['contact_phonenumber'],
                'website'             => $data['website'],
                'title'               => $data['title'],
                'password'            => $data['passwordr'],
                'company'             => $data['company'],
                'vat'                 => isset($data['vat']) ? $data['vat'] : '',
                'phonenumber'         => $data['phonenumber'],
                'country'             => $data['country'],
                'city'                => $data['city'],
                'address'             => $data['address'],
                'zip'                 => $data['zip'],
                'state'               => $data['state'],
                'custom_fields'       => isset($data['custom_fields']) && is_array($data['custom_fields']) ? $data['custom_fields'] : [],
                'affiliate_code'      => get_affiliate_user_code(),
            ], true);

            if ($clientid) {
                set_alert('success', _l('added_successfully', _l('customer')));
            }
        }

        redirect(site_url('affiliate/usercontrol/my_customers'));
    }

    /**
     * add or update the order
     * @param  string $id the order id
     * @return view
     */
    public function order($id = '')
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        if ($this->input->post()) {
            $order_data = $this->input->post();
            if ($id == '') {

                $id = $this->affiliate_model->add_order($order_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('order')));
                    $redUrl = site_url('affiliate/usercontrol/my_orders');

                    redirect($redUrl);
                }
            } else {

                $success = $this->affiliate_model->update_order($order_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('order')));
                }
                redirect(site_url('affiliate/usercontrol/my_orders'));
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('order'));
        } else {
            $data['order'] = $this->affiliate_model->get_order_detail($id);
            $title         = _l('edit', _l('order'));
        }
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['customers']     = $this->affiliate_model->get_my_customer(get_affiliate_user_code());
        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', [
            'expenses_only !=' => 1,
        ]);
        // $this->load->model('invoice_items_model');
        // $data['items'] = $this->invoice_items_model->get_grouped();
        $data['items'] = $this->affiliate_model->get_my_product_select(get_affiliate_user_id());

        $this->load->model('taxes_model');
        $data['taxes']  = $this->taxes_model->get();
        $data['groups'] = $this->affiliate_model->get_member_group();
        $data['title']  = $title;

        $this->data($data);
        $this->view('usercontrol/my_orders/order');
        $this->layout();
    }

    /**
     * view order detail
     * @param  integer $id the order id
     * @return view
     */
    public function order_detail($id)
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data['title'] = _l('order_detail');
        $data['order'] = $this->affiliate_model->get_order_detail($id);
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $this->data($data);
        $this->view('usercontrol/my_orders/order_detail');
        $this->layout();
    }

    /**
     * delete order
     *
     * @param  integer  $id     The order id
     * @return redirect
     */
    public function delete_order($id)
    {
        if (!$id) {
            redirect('affiliate/usercontrol/my_orders');
        }

        $success = $this->affiliate_model->delete_order($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('order')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('order')));
        }
        redirect('affiliate/usercontrol/my_orders');
    }

    /**
     * view affiliate_program detail
     * @param  integer $id the affiliate_program id
     * @return view
     */
    public function affiliate_program_detail($id)
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data['title']             = _l('affiliate_program');
        $data['affiliate_program'] = $this->affiliate_model->get_affiliate_program($id);
        $this->load->model('currencies_model');
        $data['currency']                   = $this->currencies_model->get_base_currency();
        $data['affiliate_program_products'] = $this->affiliate_model->get_item_id_by_program($id);

        $this->data($data);
        $this->view('usercontrol/affiliate_programs/affiliate_program_detail');
        $this->layout();
    }

    public function get_product_detail($id, $program_id = '')
    {
        $woo = $this->input->post('woo');
        $this->db->where('id', $id);

        $commodites     = $this->db->get(db_prefix() . 'items')->row();
        $commodity_file = $this->affiliate_model->get_warehourse_attachments($id);

        $html = '<div class="row col-md-12 mbot10">
              <h4 class="h4-color">' . _l('general_infor') . '</h4>
              <hr class="hr-color">
              <div class="row">
              <div class="col-md-7 panel-padding">
                <table class="table border table-striped no-margin">
                    <tbody>

                        <tr class="project-overview">
                          <td class="bold" width="30%">' . _l('commodity_code') . '</td>
                          <td>' . html_entity_decode($commodites->commodity_code ?? '') . '</td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold">' . _l('commodity_name') . '</td>
                          <td>' . html_entity_decode($commodites->description ?? '') . '</td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold">' . _l('commodity_group') . '</td>
                          <td>';
        if (get_affiliate_group_name(html_entity_decode($commodites->group_id ?? '')) != null) {
            $html .= get_affiliate_group_name($commodites->group_id);
        }
        $html .= '</tr>
                       <tr class="project-overview">
                          <td class="bold">' . _l('commodity_barcode') . '</td>
                          <td>' . html_entity_decode($commodites->commodity_barcode ?? '') . '</td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold">' . _l('sku_code') . '</td>
                          <td>' . html_entity_decode($commodites->sku_code ?? '') . '</td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold">' . _l('sku_name') . '</td>
                          <td>' . html_entity_decode($commodites->sku_name ?? '') . '</td>
                       </tr>';
        if ($woo != true && $woo != 'true') {
            $html .= '<tr class="project-overview">
                          <td class="bold" width="30%">' . _l('public_link') . '</td>
                          <td>
                            <div class="row">
                              <div class="pull-right _buttons mright5">
                                <a href="javascript:void(0)" onclick="copy_product_link(); return false;" class="btn btn-warning btn-with-tooltip" data-toggle="tooltip" title="' . _l('copy_public_link') . '" data-placement="bottom"><i class="fa fa-clone "></i></a>
                              </div>
                              <div class="col-md-9">
                                ';
            if ($program_id > 0) {
                $html .= render_input('link_product', '', site_url('affiliate/store/detailt/' . get_affiliate_user_code() . '/' . $id . '?program=' . $program_id));
            } else {
                $html .= render_input('link_product', '', site_url('affiliate/store/detailt/' . get_affiliate_user_code() . '/' . $id));
            }
            $html .= '</div>
                            </div>
                           </td>
                       </tr>';
        }
        $html .= '</tbody>
                </table>
            </div>
              <div class="gallery">
                  <div class="wrapper-masonry">
                    <div id="masonry" class="masonry-layout columns-2">';

        if (isset($commodity_file) && count($commodity_file) > 0) {
            foreach ($commodity_file as $key => $value) {
                if (file_exists('modules/warehouse/uploads/item_img/' . $value["rel_id"] . '/' . $value["file_name"])) {
                    $html .= '<a  class="images_w_table" href="' . site_url('modules/warehouse/uploads/item_img/' . $value["rel_id"] . '/' . $value["file_name"]) . '"><img class="images_w_table" src="' . site_url('modules/warehouse/uploads/item_img/' . $value["rel_id"] . '/' . $value["file_name"]) . '" alt="' . html_entity_decode($value['file_name'] ?? '') . '"/></a>';
                } elseif (site_url('modules/purchase/uploads/item_img/' . $value["rel_id"] . '/' . $value["file_name"])) {
                    $html .= '<a  class="images_w_table" href="' . site_url('modules/purchase/uploads/item_img/' . $value["rel_id"] . '/' . $value["file_name"]) . '"><img class="images_w_table" src="' . site_url('modules/purchase/uploads/item_img/' . $value["rel_id"] . '/' . $value["file_name"]) . '" alt="' . html_entity_decode($value['file_name'] ?? '') . '"/></a>';
                } else {
                    $html .= '<a href="' . site_url('modules/affiliate/uploads/nul_image.jpg') . '"><img class="images_w_table" src="' . site_url('modules/affiliate/uploads/nul_image.jpg') . '" alt="nul_image.jpg"/></a>';
                }
            }
        } else {
            $html .= '<a href="' . site_url('modules/affiliate/uploads/nul_image.jpg') . '"><img class="images_w_table" src="' . site_url('modules/affiliate/uploads/nul_image.jpg') . '" alt="nul_image.jpg"/></a>';
        }
        $html .= '<div class="clear"></div>
                </div>
              </div>
              </div>
              <br>
          </div>
          </div>
          <h4 class="h4-color">' . _l('infor_detail') . '</h4>
            <hr class="hr-color">
            <div class="row">
            <div class="col-md-6 panel-padding" >
              <table class="table border table-striped no-margin" >
                  <tbody>
                     <tr class="project-overview">
                        <td class="bold td-width">' . _l('origin') . '</td>
                          <td>' . html_entity_decode($commodites->origin ?? '') . '</td>
                     </tr>
                     <tr class="project-overview">
                        <td class="bold">' . _l('colors') . '</td>';
        $color_value = '';
        if ($commodites->color) {
            $color = get_affiliate_color_type($commodites->color);
            if ($color) {
                $color_value .= $color->color_code . '_' . $color->color_name;
            }
        }

        $html .= ' <td>' . html_entity_decode($color_value ?? '') . '</td>
                     </tr>
                     <tr class="project-overview">
                        <td class="bold">' . _l('style_id') . '</td>
                      <td>';
        if ($commodites->style_id != null) {
            if (get_affiliate_style_name(html_entity_decode($commodites->style_id ?? '')) != null) {
                $html .= get_affiliate_style_name(html_entity_decode($commodites->style_id ?? ''))->style_name;
            }
        }
        $html .= '</td>
                     </tr>

                      <tr class="project-overview">
                        <td class="bold">' . _l('rate') . '</td>
                        <td>' . app_format_money((float) $commodites->rate, '') . '</td>
                     </tr>
                  </tbody>
              </table>
            </div>
            <div class="col-md-6 panel-padding" >
              <table class="table table-striped no-margin">
                  <tbody>
                     <tr class="project-overview">
                        <td class="bold" width="40%">' . _l('model_id') . '</td>
                         <td>';
        if ($commodites->style_id != null) {
            if (get_affiliate_model_name(html_entity_decode($commodites->model_id ?? '')) != null) {
                $html .= get_affiliate_model_name(html_entity_decode($commodites->model_id ?? ''))->body_name;
            }
        }
        $html .= '</td>
                     </tr>
                     <tr class="project-overview">
                        <td class="bold">' . _l('size_id') . '</td>
                        <td>';
        if ($commodites->style_id != null) {
            if (get_affiliate_size_name(html_entity_decode($commodites->size_id ?? '')) != null) {
                $html .= get_affiliate_size_name(html_entity_decode($commodites->size_id ?? ''))->size_name;
            }
        }
        $html .= '</td>
                     </tr>

                       <tr class="project-overview">
                          <td class="bold">' . _l('unit_id') . '</td>
                          <td>';
        if ($commodites->unit_id != '' && get_affiliate_unit_type($commodites->unit_id) != null) {
            $html .= get_affiliate_unit_name($commodites->unit_id);
        }
        $html .= '</td>
                       </tr>

                    </tbody>
                  </table>
            </div>
            </div>
            <h4 class="h4-color">' . _l('description') . '</h4>
            <hr class="hr-color">
            <p class="mleft10">' . html_entity_decode($commodites->long_description ?? '') . '</p>
            ';

        echo json_encode(
            $html
        );
        die();
    }

    /**
     * get data commission chart
     *
     * @return     json
     */
    public function commission_chart()
    {
        $this->load->model('currencies_model');
        $staff_filter = [get_affiliate_user_id()];

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

        $data = $this->affiliate_model->transaction_chart($year_report, $staff_filter, $products_services);
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
        if ($this->input->post('staff_filter')) {
            $staff_filter = $this->input->post('staff_filter');
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
        $data = $this->affiliate_model->dashboard_commission_chart($staff_filter);
        echo json_encode([
            'data'  => $data['amount'],
            'month' => $data['month'],
            'unit'  => $currency_unit,
            'name'  => $currency_name,
        ]);
        die();
    }

    /**
     * Removes a profile image.
     */
    public function remove_profile_image()
    {
        $id = get_affiliate_user_id();

        if (file_exists(AFFILIATE_MODULE_UPLOAD_FOLDER . '/member_image/' . $id)) {
            delete_dir(AFFILIATE_MODULE_UPLOAD_FOLDER . '/member_image/' . $id);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'affiliate_users', [
            'profile_image' => null,
        ]);

        if ($this->db->affected_rows() > 0) {
            redirect(site_url('affiliate/usercontrol/profile'));
        }
        redirect(site_url('affiliate/usercontrol/profile'));
    }

    /**
     * search item
     * @return json
     */
    public function search_item()
    {
        $this->load->model('invoice_items_model');

        if ($this->input->post() && $this->input->is_ajax_request()) {
            echo json_encode($this->invoice_items_model->search($this->input->post('q')));
        }
    }

    /**
     * get taxes dropdown template
     * @return view
     */
    public function get_taxes_dropdown_template()
    {
        $this->load->model('misc_model');
        $name    = $this->input->post('name');
        $taxname = $this->input->post('taxname');
        echo html_entity_decode($this->misc_model->get_taxes_dropdown_template($name, $taxname));
    }

    /* Get item by id / ajax */
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('invoice_items_model');
            $item                     = $this->invoice_items_model->get($id);
            $item->long_description   = nl2br($item->long_description);
            $item->custom_fields_html = render_custom_fields('items', $id, [], ['items_pr' => true]);
            $item->custom_fields      = [];

            $cf = get_custom_fields('items');

            foreach ($cf as $custom_field) {
                $val = get_custom_field_value($id, $custom_field['id'], 'items_pr');
                if ($custom_field['type'] == 'textarea') {
                    $val = clear_textarea_breaks($val);
                }
                $custom_field['value'] = $val;
                $item->custom_fields[] = $custom_field;
            }

            echo json_encode($item);
        }
    }

    /**
     * client change data
     * @param  integer $customer_id
     * @param  string $current_invoice
     * @return json
     */
    public function client_change_data($customer_id, $current_invoice = '')
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('projects_model');
            $data                     = [];
            $data['billing_shipping'] = $this->clients_model->get_customer_billing_and_shipping_details($customer_id);

            echo json_encode($data);
        }
    }

    public function products_list()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $this->load->model('currencies_model');

        $data['currency']     = $this->currencies_model->get_base_currency();
        $data['title']        = _l('products_list');
        $data['product_list'] = $this->affiliate_model->get_product_list(get_affiliate_user_id());
        $where                = '';
        $where_product        = '';
        if ($data['product_list']) {
            foreach ($data['product_list'] as $key => $value) {
                if ($value['id'] != '') {
                    if ($where == '') {
                        $where = $value['id'];
                    } else {
                        $where .= ',' . $value['id'];
                    }
                }
            }
            if ($where != '') {
                $where_product = 'id not in (' . $where . ')';
            }
        }
        $data['products'] = $this->affiliate_model->get_my_product_select(get_affiliate_user_id(), $where_product);
        $this->data($data);
        $this->view('usercontrol/products_list/manage');
        $this->layout();
    }

    /**
     * add product
     * @return redirect
     */
    public function add_product()
    {
        if (!is_affiliate_logged_in()) {
            redirect(site_url('affiliate/authentication_affiliate/login'));
        }
        $data    = $this->input->post();
        $id      = $this->affiliate_model->add_product($data);
        $message = $id ? _l('added_successfully', _l('affiliate_product')) : '';

        redirect(admin_url('affiliate/usercontrol/products_list'));
    }

    /**
     * delete product
     *
     * @param  integer  $id     The product id
     * @return redirect
     */
    public function delete_product($id)
    {
        if (!$id) {
            redirect('affiliate/usercontrol/products_list');
        }

        $success = $this->affiliate_model->delete_product($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('affiliate_product')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('affiliate_product')));
        }
        redirect('affiliate/usercontrol/products_list');
    }

    /**
     * sales channel
     * @return view
     */
    public function sales_channel()
    {
        $data['title']    = _l('channel_woocommerce');
        $data['channels'] = $this->affiliate_model->get_woocommerce_channel();

        $this->data($data);
        $this->view('usercontrol/sales_channel/manage_channel_woocommerce');
        $this->layout();
    }

    /**
     * add woocommerce channel
     * @return redirect
     */
    public function add_woocommerce_channel()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['id'] == '') {
                $insert_id = $this->affiliate_model->add_woocommerce_channel($data);
                if ($insert_id) {
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->affiliate_model->update_woocommerce_channel($data, $id);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
            }
            redirect(site_url('affiliate/usercontrol/sales_channel'));
        }
    }

    /**
     * detail channel wcm
     * @param  int $id
     * @return view
     */
    public function woocommerce_channel_detail($id)
    {
        $this->load->model('affiliate_store_model');
        $data['group'] = $this->input->get('group');

        $data['title'] = _l('channel_woocommerce');

        $data['tab'][] = 'product';

        if ($data['group'] == '') {
            $data['group'] = 'product';
        }

        $data['tabs']['view']  = 'includes/' . $data['group'];
        $data['id']            = $id;
        $data['group_product'] = $this->affiliate_store_model->get_group_product();
        $store_choose          = $this->affiliate_model->get_woocommere_products($id, [], true);
        $data['list_product']  = $this->affiliate_model->get_woocommere_products($id);
        $data['products']      = [];
        $products              = $this->affiliate_model->get_my_product_select(get_affiliate_user_id(), [], false);

        foreach ($products as $key => $value) {
            if (!in_array($value['id'], $store_choose)) {
                array_push($data['products'], $value);
            }
        }
        $data['status'] = get_option('status_sync');
        $this->data($data);
        $this->view('usercontrol/sales_channel/detail_channel_woocommerce');
        $this->layout();
    }
    /**
     * delete channel wcm
     * @param  int $id
     * @return  redirect
     */
    public function delete_woocommerce_channel($id)
    {
        $this->load->model('affiliate_model');
        $response = $this->affiliate_model->delete_woocommerce_channel($id);
        if ($response == true) {
            set_alert('success', _l('deleted'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(site_url('affiliate/usercontrol/sales_channel'));
    }

    /**
     * get list product
     * @param  int $id
     * @return json
     */
    public function get_list_product($channel_id = '', $id = '')
    {
        if ($id == '') {
            $list = $this->affiliate_model->get_my_product_select(get_affiliate_user_id(), [], false);
        } else {
            $list = $this->affiliate_model->get_my_product_select(get_affiliate_user_id(), ['group_id' => $id], false);
        }
        $html         = '';
        $store_choose = $this->affiliate_model->get_woocommere_products($channel_id, [], true);

        foreach ($list as $key => $value) {
            if (!in_array($value['id'], $store_choose)) {
                $html .= '<option value="' . $value['id'] . '">' . $value['commodity_code'] . ' # ' . $value['description'] . '</option>';
            }
        }
        echo json_encode([
            'success' => true,
            'html'    => $html,
        ]);
        die;
    }

    /**
     * add product channel wcm
     * @return redirect
     */
    public function add_product_channel_wcm()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['woocommere_channel_id'] != '') {
                if ($data['id'] == '') {
                    $insert_id = $this->affiliate_model->add_product_channel_wcm($data);
                    if ($insert_id) {
                        $message = _l('added_successfully');
                        set_alert('success', $message);
                    }
                } else {
                    $insert_id = $this->affiliate_model->update_product_channel_wcm($data, $data['id']);
                    if ($insert_id) {
                        $message = _l('updated_successfully');
                        set_alert('success', $message);
                    }
                }
                redirect(site_url('affiliate/usercontrol/woocommerce_channel_detail/' . $data['woocommere_channel_id']));
            }
            redirect(site_url('affiliate/usercontrol/sales_channel'));
        }
    }

    /**
     * { delete product store }
     *
     * @param  $store  The store
     * @param  $id     The identifier
     * @return redirect
     */
    public function delete_product_store($id, $channel)
    {
        $response = $this->affiliate_model->delete_product_channel($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('affiliate_product')));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }

        redirect(site_url('affiliate/usercontrol/woocommerce_channel_detail/' . $channel));

    }

    /**
     * sync products to store
     * @param  int $store_id
     * @return json
     */
    public function sync_products_to_store_detail()
    {
        $data     = $this->input->post();
        $detail   = $data["arr_val"];
        $store_id = $data["id"];
        update_option('status_sync', 1);
        $this->load->model('sync_woo_model');
        $success = $this->sync_woo_model->sync_from_the_system_to_the_store_single(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     * sync products to store
     * @param  int $store_id
     * @return json
     */
    public function sync_products_to_store($store_id)
    {
        $this->load->model('sync_woo_model');
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->sync_from_the_system_to_the_store_single(get_affiliate_user_id(), $store_id);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     *  process asynclibrary inventory
     * @param  int $store_id
     * @return json
     */
    public function process_asynclibrary_inventory_detail()
    {
        $this->load->model('sync_woo_model');
        $data     = $this->input->post();
        $detail   = $data["arr_val"];
        $store_id = $data["id"];
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->process_inventory_synchronization_detail(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     *  Sync price store
     * @return json
     */
    public function sync_price()
    {
        $this->load->model('sync_woo_model');
        $data     = $this->input->post();
        $detail   = isset($data["arr_val"]) ? $data["arr_val"] : null;
        $store_id = $data["id"];
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->process_price_synchronization(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     *  Sync price all of store
     * @param  int $store_id
     * @return json
     */
    public function sync_price_all($store_id)
    {
        $this->load->model('sync_woo_model');
        $data   = $this->input->post();
        $detail = isset($data["arr_val"]) ? $data["arr_val"] : null;
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->process_price_synchronization(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     *  process decriptions synchronization
     * @param  int $store_id
     * @return json
     */
    public function process_decriptions_synchronization($store_id)
    {
        $this->load->model('sync_woo_model');
        $data     = $this->input->post();
        $detail = isset($data["arr_val"]) ? $data["arr_val"] : null;
        update_option('status_sync', 1);
        $result = $this->sync_woo_model->process_decriptions_synchronization_detail(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($result);
    }

    /**
     *  process decriptions synchronization
     * @param  int $store_id
     * @return json
     */
    public function process_decriptions_synchronization_detail()
    {
        $this->load->model('sync_woo_model');
        $data     = $this->input->post();
        $detail   = $data["arr_val"];
        $store_id = $data["id"];
        update_option('status_sync', 1);
        $result = $this->sync_woo_model->process_decriptions_synchronization_detail(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($result);
    }

    /**
     * sync all info products to store
     * @return json
     */
    public function sync_all()
    {
        $this->load->model('sync_woo_model');
        $data     = $this->input->post();
        $detail   = $data["arr_val"];
        $store_id = $data["id"];
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->sync_all(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     * process orders woo
     * @param  int $store_id
     * @return json
     */
    public function process_orders_woo($store_id)
    {
        $this->load->model('sync_woo_model');
        update_option('status_sync', 1);
        $result = $this->sync_woo_model->process_orders_woo(get_affiliate_user_id(), $store_id);
        update_option('status_sync', 2);
        echo json_encode($result);
    }

    /**
     * sync all info products to store
     * @param  int $store_id
     * @return json
     */
    public function sync_all_not_selected($store_id)
    {
        $this->load->model('sync_woo_model');
        $data   = $this->input->post();
        $detail = isset($data["arr_val"]) ? $data["arr_val"] : null;
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->sync_all(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     *  process asynclibrary image
     * @param  int $store_id
     * @return json
     */
    public function process_asynclibrary_image($store_id)
    {
        $this->load->model('sync_woo_model');
        update_option('status_sync', 1);
        $result = $this->sync_woo_model->process_images_synchronization_detail(get_affiliate_user_id(), $store_id);
        update_option('status_sync', 2);
        echo json_encode($result);
    }

    /**
     * process images synchronization
     * @param  integer $store_id
     * @return json
     */
    public function process_images_synchronization($store_id)
    {
        $this->load->model('sync_woo_model');
        $result = $this->sync_woo_model->process_images_synchronization_detail(get_affiliate_user_id(), $store_id);
        echo json_encode($result);
    }

    /**
     *  process asynclibrary inventory
     * @param  int $store_id
     * @return json
     */
    public function process_asynclibrary_inventory($store_id)
    {
        $this->load->model('sync_woo_model');
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->process_inventory_synchronization_detail(get_affiliate_user_id(), $store_id);
        update_option('status_sync', 2);
        echo json_encode($success);
    }

    /**
     * process inventory synchronization
     * @param  integer $store_id
     * @return json
     */
    public function process_inventory_synchronization($store_id)
    {
        $this->load->model('sync_woo_model');
        $result = $this->sync_woo_model->process_inventory_synchronization_detail(get_affiliate_user_id(), $store_id);
        echo json_encode($result);
    }

    /**
     * manage setting
     * @return view
     */
    public function settings()
    {
        $data          = [];
        $data['group'] = $this->input->get('group');
        $data['title'] = _l($data['group']);

        $data['tab'][] = 'automatic_sync_config';
        if ($data['group'] == '') {
            $data['group'] = 'automatic_sync_config';
        }
        $this->load->model('affiliate_model');
        if ($data['group'] == 'automatic_sync_config') {
            $data['minute']                           = get_option('minute_sync_orders');
            $data['minute_sync_product_info_time1']   = get_option('minute_sync_product_info_time1');
            $data['minute_sync_inventory_info_time2'] = get_option('minute_sync_inventory_info_time2');
            $data['minute_sync_price_time3']          = get_option('minute_sync_price_time3');
            $data['minute_sync_decriptions_time4']    = get_option('minute_sync_decriptions_time4');
            $data['minute_sync_images_time5']         = get_option('minute_sync_images_time5');

            $data['setting_woo_store'] = $this->affiliate_model->get_setting_auto_sync_store(get_affiliate_user_id());
            $data['store']             = $this->affiliate_model->get_woocommerce_channel();
        }

        $data['tabs']['view'] = 'settings/' . $data['group'];
        $this->data($data);
        $this->view('usercontrol/settings/manage');
        $this->layout();
    }

    /**
     * test connect
     * @return json
     */
    public function test_connect()
    {
        $this->load->model('sync_woo_model');
        $data = $this->input->post();
        if ($data['url'] != '' && $data['consumer_key'] != '' && $data['consumer_secret'] != '') {
            $success = $this->sync_woo_model->test_connect($data);
            if ($success) {
                $message = _l('connection_successful');
            } else {
                $message = _l('connection_failed');
            }
        } else {
            $success = false;
            $message = _l('connection_failed');
        }

        echo json_encode(['check' => $success, 'message' => $message]);
    }

    /**
     * sync_auto_store
     * @return redirect
     */
    public function sync_auto_store()
    {
        if ($this->input->post()) {
            $arr_store_exit = $this->affiliate_model->get_setting_auto_sync_store_exit();
            $data           = $this->input->post();
            if ($data['id'] == '') {
                unset($data['id']);
                if (in_array($data['store'], $arr_store_exit)) {
                    $message = _l('config_store_exit');
                    set_alert('warning', $message);
                    redirect(site_url('affiliate/usercontrol/settings?group=automatic_sync_config'));
                }
                $insert_id = $this->affiliate_model->add_setting_auto_sync_store($data);
                if ($insert_id) {
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->affiliate_model->update_setting_auto_sync_store($data, $id);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
            }
            redirect(site_url('affiliate/usercontrol/settings?group=automatic_sync_config'));
        }
    }

    /**
     * delete sync auto store
     * @param  integer $id
     * @return redirect
     */
    public function delete_sync_auto_store($id)
    {
        $response = $this->affiliate_model->delete_sync_auto_store($id);
        if ($response == true) {
            set_alert('success', _l('deleted'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(site_url('affiliate/usercontrol/settings?group=automatic_sync_config'));
    }

    /**
     *  process asynclibrary image
     * @param  int $store_id
     * @return json
     */
    public function process_asynclibrary_image_detail()
    {
        $this->load->model('sync_woo_model');
        $data     = $this->input->post();
        $detail   = $data["arr_val"];
        $store_id = $data["id"];
        update_option('status_sync', 1);
        $success = $this->sync_woo_model->process_images_synchronization_detail(get_affiliate_user_id(), $store_id, $detail);
        update_option('status_sync', 2);
        echo json_encode($success);
    }
}
