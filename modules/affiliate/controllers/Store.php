<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\ValidatesContact;

class Store extends App_Controller
{
  /**
   * __construct
   */
  
    public $template = [];

    public $data = [];

    public $use_footer = true;

    public $use_submenu = true;

    public $use_navigation = true;
    public function __construct()
    {
        parent::__construct();
        hooks()->do_action('after_clients_area_init', $this);

        $this->load->model('Affiliate_store_model');
        $this->load->library('app_usercontrol_area_constructor');

    }

    public function layout($notInThemeViewFiles = false)
    {
        $member_code = $this->input->get('member_code');
        if($member_code){
          $GLOBALS['member_code'] = $member_code;
        }

        /**
         * Navigation and submenu
         * @deprecated 2.3.2
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
        $this->template['head'] = $this->load->view('store/themes/head', $this->data, true);

        $GLOBALS['customers_head'] = $this->template['head'];

        /**
         * Load the template view
         * @var string
         */
        $module                       = CI::$APP->router->fetch_module();
        $this->data['current_module'] = $module;

        $viewPath = !is_null($module) || $notInThemeViewFiles ? $this->view : 'store/themes/' . $this->view;

        $this->template['view']    = $this->load->view($viewPath, $this->data, true);
        $GLOBALS['customers_view'] = $this->template['view'];

        /**
         * Theme footer
         * @var string
         */
        $this->template['footer'] = $this->use_footer == true
        ? $this->load->view('store/themes/footer', $this->data, true)
        : '';
        $GLOBALS['customers_footer'] = $this->template['footer'];

        /**
         * @deprecated 2.3.0
         * Theme scripts.php file is no longer used since vresion 2.3.0, add app_customers_footer() in themes/[theme]/index.php
         * @var string
         */
        $this->template['scripts'] = '';
        if (file_exists(VIEWPATH . 'store/themes/scripts.php')) {
            if (ENVIRONMENT != 'production') {
                trigger_error(sprintf('%1$s', 'Clients area theme file scripts.php file is no longer used since version 2.3.0, add app_customers_footer() in themes/[theme]/index.php. You can check the original theme index.php for example.'));
            }

            $this->template['scripts'] = $this->load->view('store/themes/scripts', $this->data, true);
        }

        /**
         * Load the theme compiled template
         */
        $this->load->view('store/themes/index', $this->template);
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
     * index 
     * @param  int $page 
     * @param  int $id   
     * @param  string $key  
     * @return view       
     */
      public function index($member_code='', $page='', $id = '', $warehouse = '', $key = ''){  
          if($member_code == ''){
            redirect(site_url());
          }
          if($warehouse == ''|| !is_numeric($warehouse)){
            $warehouse = 0;
          }
          if($page == '' || !is_numeric($page)){
            $page = 1;
          }
          if($id == ''|| !is_numeric($id)){
            $id = 0;
          }
          $data['ofset'] = 24;
          $data['title'] = _l('sales');
          $data['group_product'] = $this->Affiliate_store_model->get_group_product();        
          $data['group_id'] = $id;
          $data['member_code'] = $member_code;
          $data_product = $this->Affiliate_store_model->get_list_product_by_group($member_code,$id,$key,($page-1)*$data['ofset'],$data['ofset']);

          $data['product'] = [];
          $date = date('Y-m-d');
          foreach ($data_product['list_product'] as $item) {
            $discount_percent = 0;
            $data_discount = $this->Affiliate_store_model->check_discount($item['id'], $date, 2);
            if($data_discount){
              $discount_percent = $data_discount->discount;
            }
            $price = $item['rate'];

            array_push($data['product'], array(
              'id' => $item['id'],
              'name' => $item['description'],
              'price' => $price,
              'w_quantity' => $this->get_stock($item['id']),
              'discount_percent' => $discount_percent,
              'price_discount' => $this->get_price_discount($price, $discount_percent)
            ));
          }
          $data['title_group'] = _l('all_products');
          $data['page'] = $page;
          $data['ofset_count'] = $data_product['count'];
          $data['total_page'] = ceil($data['ofset_count']/$data['ofset']);
          $this->load->model('currencies_model');
          $data['base_currency'] = $this->currencies_model->get_base_currency();
          $this->data($data);
          $this->view('store/sales');
          $this->layout();
      }
      
      /**
       * view_cart
       * @param  string $id 
       * @return      
       */
      public function view_cart($member_code='', $id = ''){

          $this->load->model('currencies_model');
          $data['base_currency'] = $this->currencies_model->get_base_currency();
          $data['title'] = _l('cart');
          $data['logged'] = $id;
          $data['member_code'] = $member_code;
          $this->data($data);
          $this->view('store/cart/cart');
          $this->layout();
      }
    /**
     * form contact
     * @param  int $contact_id 
     * @return            
     */
    public function form_contact($contact_id = '')
    {      
        $data['customer_id'] = '';
        $data['contactid']   = '';
        if ($this->input->post()) {
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            unset($data['contactid']);
        if ($contact_id == '') {
            $id      = $this->Affiliate_store_model->add_contact($data);
            $message = '';
            if ($id) {
                handle_contact_profile_image_upload($id);
                $message = _l('added_successfully');
                set_alert('success', $message);
            }
            redirect(site_url('affiliate/store/view_cart'));
        }
      }            
    }
    /**
     * check out
     * @param  int $id 
     * @return redirect 
     */
    public function check_out($member_code = '', $id = '')
    {   
        if(is_client_logged_in()) {
          if($id == ''){
            redirect(site_url('affiliate/store/view_cart/'.$member_code.'/1'));         
          }
          else{
            redirect(site_url('affiliate/store/view_overview/'.$member_code));        
          }
        }
        else{
            redirect_after_login_to_current_url();
            redirect(site_url('authentication/login'));
        }  
    }
    /**
     * order success
     * @return view
     */
    public function order_success(){  
       infor_page(_l('order_success'),_l('you_have_successfully_placed_an_order'),site_url('affiliate/store'));
    }

    /**
     * { view overview }
     *
     * @param      string  $id     The identifier
     * @return  redirect
     */
    public function view_overview($member_code = '', $id = ''){
        if($this->input->post()){
              $data = $this->input->post();
              $invoice_id = $this->Affiliate_store_model->check_out($data, $member_code);
              if($invoice_id){
                  $this->load->model('invoices_model');
                  $data_invoice = $this->invoices_model->get($invoice_id);
                  if($data_invoice){
                    redirect(site_url('invoice/'.$data_invoice->id.'/'.$data_invoice->hash));
                  }
              }
          }
          
          if(is_client_logged_in()){
              $data_userid = get_client_user_id();
              $data_profile = $this->clients_model->get($data_userid);
              if($data_profile){
                if($data_profile->shipping_street!='' && $data_profile->shipping_city!='' && $data_profile->shipping_street!='' && $data_profile->shipping_state!=''){
                   if(isset($_COOKIE['cart_id_list'])){
                      $list_id = $_COOKIE['cart_id_list'];
                      $array_id = explode(',', $list_id);
                      $list_group = [];
                      $list_prices = [];
                      foreach ($array_id as $key => $id) {
                        $data_group = $this->Affiliate_store_model->get_product($id);
                        if($data_group){
                          $list_group[] = $data_group->group_id;
                          $list_prices[] = $this->Affiliate_store_model->get_product($id)->rate;
                        }
                      }
                      $data['list_group'] = implode(',', $list_group);
                      $data['list_prices'] = implode(',', $list_prices);
                      $data['tax'] = $this->Affiliate_store_model->check_tax_product($list_id);
                      $this->load->model('payment_modes_model');
                      $this->load->model('payments_model');
                      $data['payment_modes'] = $this->payment_modes_model->get('', [
                          'expenses_only !=' => 1,
                      ]);
                      $this->load->model('currencies_model');
                      $data['base_currency'] = $this->currencies_model->get_base_currency();
                      $data['title'] = _l('cart');
                      $data['member_code'] = $member_code;
                      $this->data($data);
                      $this->view('store/cart/overview_cart');
                      $this->layout();
                   }
                   else{
                      redirect(site_url('affiliate/store/index/'.$member_code.'/1/0/0'));
                   }

                }
                else{
                  redirect(site_url('affiliate/store/client/'.$member_code.'/'.$data_userid));
                }
              }
              else{
                  redirect(site_url('affiliate/store/index/'.$member_code.'/1/0/0'));
              }
          }
          else{
              redirect(site_url('affiliate/store/index/'.$member_code.'/1/0/0'));
          }
    }
    /**
     * order successfull
     * @param  int $order_number 
     * @return   view            
     */
    public function order_successfull($order_number){
        $this->load->model('currencies_model');

        $base_currency = $this->currencies_model->get_base_currency();
        $currency_name = '';
        if(isset($base_currency)){
          $currency_name = $base_currency->name;
        }
        $order = $this->Affiliate_store_model->get_cart_by_order_number($order_number);

        $data['order_detait'] = $this->Affiliate_store_model->get_cart_detailt_by_cart_id($order->id);
        $address = $order->shipping_street.', '.$order->shipping_city.', '.$order->shipping_state.', '.get_country_short_name($order->shipping_country).', '.$order->shipping_zip;
        $data['content'] = '<div class="head_content"><span><i class="fa fa-check"></i></span></div>'._l('you_have_successfully_placed_an_order_with_a_code').' '.$order->order_number.', '._l('order_value_is').' '.app_format_money($order->total,'').' '.$currency_name.'.</br></br>'._l('please_wait_for_our_order_confirmation_and_delivery_to_the_address').': '.$address.'.</br></br>'._l('we_are_honored_to_serve_you').'!</br></br></br>';

        $data['previous_link'] = site_url('affiliate/store');
        $data['link_text'] = _l('continue_shopping');
        $data['custom_link'] = site_url('affiliate/store/view_order_detail/'.$order_number);
        $data['custom_link_text'] = _l('order_details');
        $this->data($data);
        $this->view('store/info_page');
        $this->layout();

    }
    /**
     * view order detail
     * @param  int $order_number 
     * @return  view             
     */
    public function view_order_detail($member_code,$order_id){
          $this->load->model('currencies_model');
          $this->load->model('affiliate_model');
          $data['base_currency'] = $this->currencies_model->get_base_currency();
          $data['order'] = $this->affiliate_model->get_order_detail($order_id);

          $data['title'] = _l('order_detail');
          $data['member_code'] = $member_code;
          $this->data($data);
          $this->view('store/cart/order_detailt');
          $this->layout();
    }
    /**
     * change status order
     * @param  int $order_number 
     * @return   redirect             
     */
    public function change_status_order($member_code, $order_id){
       if($this->input->post()){
            $data = $this->input->post();
                $this->Affiliate_store_model->change_status_order($data,$order_id);
                redirect(site_url('affiliate/store/view_order_detail/'.$member_code.'/'.$order_id));         
        }
    }
    /**
     * order list
     * @param  int $tab 
     * @return   view    
     */
    public function order_list($member_code = '',$tab = ''){
          $data['title'] = _l('order_list');

          if($tab == ''){
             $data['tab'] = 'processing';
          }
          else{
             $data['tab'] = $tab;
          }
            $status = 0;
            switch ($data['tab']) {
              case 'processing':
                $status = 0;
                break;
              case 'pending_payment':
                $status = 1;
                break;  
              case 'confirm':
                $status = 2;
                break;  
              case 'being_transported':
                $status = 3;
                break;  
              case 'finish':
                $status = 4;
                break;  
              case 'refund':
                $status = 5;
                break;  
              case 'lie':
                $status = 6;
                break; 
              case 'cancelled':
                $status = 7;
                break;   

          }           
          $this->load->model('currencies_model');
          $data['base_currency'] = $this->currencies_model->get_base_currency();
          $data['member_code'] = $member_code;
          $userid = get_client_user_id();
          $data['cart_list'] = $this->Affiliate_store_model->get_order_list($userid, ['status' => $status]);
          $this->data($data);
          $this->view('store/cart/order_list');
          $this->layout();
    }
    /**
     * detailt 
     * @param  int  $id 
     * @return    view  
     */
    public function detailt($member_code, $id){
      $this->load->model('affiliate_model');
      $member = $this->affiliate_model->get_member_by_code($member_code);
      if($member){
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();          
        $date = date('Y-m-d');
        $data['detailt_product'] = $this->Affiliate_store_model->get_product($id);
        $group_id = $data['detailt_product']->group_id;
        $data['group_id'] = $group_id;
        $data['title'] = _l('affiliate_product');
        $data['member_code'] = $member_code;
        $max_product = 15;
        $count_product = 0;


        $data_product  = $this->Affiliate_store_model->get_list_product_by_group_s($member->id, $id,0,$max_product);
        $group = $this->Affiliate_store_model->get_group_product($group_id);
        $data['group'] = '';
        if($group){
          $data['group'] = $group->name;
        }
        $data['product'] = [];
        $data['price']  = $data['detailt_product']->rate;

        $discount_percent = 0;
        $data['discount'] = $this->Affiliate_store_model->check_discount($id, $date, 2);
        if($data['discount']){
          $discount_percent = $data['discount']->discount;
        }
        $data['discount_percent'] = $discount_percent;

        $data['price_discount'] = $this->get_price_discount($data['price'], $discount_percent);
        $data['amount_in_stock'] = $this->get_stock($id);


        $date = date('Y-m-d');
        foreach ($data_product['list_product'] as $item) {
          $discount_percent = 0;
          $data_discount = $this->Affiliate_store_model->check_discount($item['id'], $date);
          if($data_discount){
            $discount_percent = $data_discount->discount;
          }
          $price = $item['rate'];
         
          array_push($data['product'], array(
            'id' => $item['id'],
            'name' => $item['description'],
            'price' => $price,
            'w_quantity' => $this->get_stock($item['id']),
            'discount_percent' => $discount_percent,
            'price_discount' => $this->get_price_discount($price, $discount_percent)
          ));
        }
        hooks()->apply_filters('before_view_product_detail', $data['detailt_product']);
        $this->data($data);
        $this->view('store/detailt_product');
        $this->layout();
      }
      else{
        redirect(site_url('affiliate/store/index/'.$member_code.'/1/0/0'));
      }
    }
    /**
     * get product by group 
     * @param  int $page 
     * @param  int $id   
     * @return    json    
     */
     public function get_product_by_group($member_code,$page='',$id = '',$key = ''){  

          $data['ofset'] = 24;          
          $data_product = $this->Affiliate_store_model->get_list_product_by_group($member_code,$id, $key,($page-1)*$data['ofset'],$data['ofset']);
          $data['product'] = [];
          $date = date('Y-m-d');
          foreach ($data_product['list_product'] as $item) {
            $discount_percent = 0;
            $data_discount = $this->Affiliate_store_model->check_discount($item['id'], $date);
            if($data_discount){
              $discount_percent = $data_discount->discount;
            }
            $price = $item['rate'];
            
            array_push($data['product'], array(
              'id' => $item['id'],
              'name' => $item['description'],
              'price' => $item['rate'],
              'w_quantity' => $this->get_stock($item['id']),
              'discount_percent' => $discount_percent,
              'price_discount' => $this->get_price_discount($price, $discount_percent)
            ));
            }         
          $data['title_group'] = '';
          $data['member_code'] = $member_code;
          $this->load->model('currencies_model');
          $data['base_currency'] = $this->currencies_model->get_base_currency();
          $html = $this->load->view('store/list_product/list_product_partial',$data,true);

          echo json_encode([
            'data'=>$html
          ]);
          die;
      } 
      /**
       * search product 
       * @param  int  $group_id 
       * @return            
       */
      public function search_product($member_code, $group_id){
          if($this->input->post()){
            $data = $this->input->post();
            $data['member_code'] = $member_code;
            redirect(site_url('affiliate/store/index/'.$member_code.'/1/'.$group_id.'/0/'.$data['keyword']));                    
          }
      }
      /**
       * get stock 
       * @param  int $product_id 
       * @return   $w_qty           
       */
      public function get_stock($product_id){
        $w_qty = 0;
        $wh = $this->Affiliate_store_model->get_total_inventory_commodity($product_id);
        if($wh){
          if($wh->inventory_number){
            $w_qty = $wh->inventory_number;
          }
        }
        return $w_qty;
      }
      /**
       * get price discount
       * @param  int $prices           
       * @param   $discount_percent 
       * @return      discount_percent              
       */
      public function get_price_discount($prices, $discount_percent){
          return ($discount_percent * $prices) / 100;
      }
      /**
       * voucher_apply 
       * @return  json
       */
      public function voucher_apply(){
          $data = $this->input->post();           
          $return = $this->Affiliate_store_model->get_discount_list($data['channel'],$data['client'],$data['voucher']);
          echo json_encode([$return]);
      }


    /**
     * edit client info
     * @param int $id
     * @return view
     */
    public function client($member_code = '', $id = '')
    {
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                redirect(site_url('affiliate/store/index/'.$member_code.'/1/0/0'));
            } else {
                $success = $this->clients_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfully', _l('client')));
                }
                redirect(site_url('affiliate/store/view_overview/'.$member_code));
            }
        }

        $group         = !$this->input->get('group') ? 'profile' : $this->input->get('group');
        $data['group'] = $group;
        $data['member_code'] = $member_code;

        if ($group != 'contacts' && $contact_id = $this->input->get('contactid')) {
            redirect(admin_url('clients/client/' . $id . '?group=contacts&contactid=' . $contact_id));
        }

        $data['groups'] = $this->clients_model->get_groups();

        if ($id == '') {
            $title = _l('add_new', _l('client_lowercase'));
        } else {
            $client                = $this->clients_model->get($id);
            $data['customer_tabs'] = get_customer_profile_tabs();

            if (!$client) {
                show_404();
            }

            $data['contacts'] = $this->clients_model->get_contacts($id);
            $data['tab']      = isset($data['customer_tabs'][$group]) ? $data['customer_tabs'][$group] : null;

         

            if ($group == 'profile') {
                $data['customer_groups'] = $this->clients_model->get_customer_groups($id);
                $data['customer_admins'] = $this->clients_model->get_admins($id);

            } elseif ($group == 'attachments') {
                $data['attachments'] = get_all_customer_attachments($id);
            } elseif ($group == 'vault') {


                $data['vault_entries'] = hooks()->apply_filters('check_vault_entries_visibility', $this->clients_model->get_vault_entries($id));

                if ($data['vault_entries'] === -1) {
                    $data['vault_entries'] = [];
                }
            } elseif ($group == 'estimates') {
                $this->load->model('estimates_model');
                $data['estimate_statuses'] = $this->estimates_model->get_statuses();
            } elseif ($group == 'invoices') {
                $this->load->model('invoices_model');
                $data['invoice_statuses'] = $this->invoices_model->get_statuses();
            } elseif ($group == 'credit_notes') {
                $this->load->model('credit_notes_model');
                $data['credit_notes_statuses'] = $this->credit_notes_model->get_statuses();
                $data['credits_available']     = $this->credit_notes_model->total_remaining_credits_by_customer($id);
            } elseif ($group == 'payments') {
                $this->load->model('payment_modes_model');
                $data['payment_modes'] = $this->payment_modes_model->get();
            } elseif ($group == 'notes') {
                $data['user_notes'] = $this->misc_model->get_notes($id, 'customer');
            } elseif ($group == 'projects') {
                $this->load->model('projects_model');
                $data['project_statuses'] = $this->projects_model->get_project_statuses();
            } elseif ($group == 'statement') {
                if (!has_permission('invoices', '', 'view') && !has_permission('payments', '', 'view')) {
                    set_alert('danger', _l('access_denied'));
                    redirect(admin_url('clients/client/' . $id));
                }

                $data = array_merge($data, prepare_mail_preview_data('customer_statement', $id));
            } elseif ($group == 'map') {
                if (get_option('google_api_key') != '' && !empty($client->latitude) && !empty($client->longitude)) {

                    $this->app_scripts->add('map-js', base_url($this->app_scripts->core_file('assets/js', 'map.js')) . '?v=' . $this->app_css->core_version());

                    $this->app_scripts->add('google-maps-api-js', [
                        'path'       => 'https://maps.googleapis.com/maps/api/js?key=' . get_option('google_api_key') . '&callback=initMap',
                        'attributes' => [
                            'async',
                            'defer',
                            'latitude'       => "$client->latitude",
                            'longitude'      => "$client->longitude",
                            'mapMarkerTitle' => "$client->company",
                        ],
                        ]);
                }
            }
            $data['staff'] = $this->staff_model->get('', ['active' => 1]);
            $data['client'] = $client;
            $title          = $client->company;
            $data['members'] = $data['staff'];
            if (!empty($data['client']->company)) {
                if (is_empty_customer_company($data['client']->userid)) {
                    $data['client']->company = '';
                }
            }
        }
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        if ($id != '') {

            $customer_currency = $data['client']->default_currency;

            foreach ($data['currencies'] as $currency) {
                if ($customer_currency != 0) {
                    if ($currency['id'] == $customer_currency) {
                        $customer_currency = $currency;

                        break;
                    }
                } else {
                    if ($currency['isdefault'] == 1) {
                        $customer_currency = $currency;

                        break;
                    }
                }
            }

            if (is_array($customer_currency)) {
                $customer_currency = (object) $customer_currency;
            }

            $data['customer_currency'] = $customer_currency;

            $slug_zip_folder = (
                $client->company != ''
                ? $client->company
                : get_contact_full_name(get_primary_contact_user_id($client->userid))
            );

            $data['zip_in_folder'] = slug_it($slug_zip_folder);
        }

        $data['bodyclass'] = 'customer-profile dynamic-create-groups';
        $data['title']     = $title;
        $this->data($data);
        $this->view('store/cart/client_info');
        $this->layout();
    }
     /**
     * get trade discount
     * @return json 
     */
    public function get_trade_discount(){
        $data = $this->input->get();
        $channel = 1;
        if(isset($data['channel'])){
            $channel = $data['channel'];
        }    
        $list_discount = $this->Affiliate_store_model->get_discount_list($channel, $data['id']);
        $result = [];        
        if($list_discount != false){
          $result = $list_discount;
        }
        echo json_encode([$result]);
    }
}