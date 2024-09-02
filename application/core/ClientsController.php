<?php

defined('BASEPATH') or exit('No direct script access allowed');

define('CLIENTS_AREA', true);

class ClientsController extends App_Controller
{
    public $template = [];

    public $data = [];

    public $use_footer = false;

    public $use_submenu = true;

    public $use_navigation = true;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('clients_model');
        $this->load->model('authentication_model');

        $cart_prospects = [];
        // $currentUser = new stdClass();
        // $currentUser->direction = 'ltr';
        $currentUser = $this->clients_model->get_contact(get_contact_user_id());

        // Deleted or inactive but have session
        if (!$currentUser || $currentUser->active == 0) {
            //$this->authentication_model->logout();
            //redirect(site_url('authentication'));
        }

        if (
            !is_client_logged_in()
        ) {

            $this->load->model('leadevo/Cart_model');
            $currentUser = $this->session->get_userdata();

            $cart_prospects = $this->Cart_model->get_cart_prospects();

            $GLOBALS['current_user'] = $currentUser;
            $GLOBALS['cart_prospects'] = $cart_prospects;
        }


        hooks()->do_action('client_init');
        hooks()->do_action('app_client_assets');


        $this->load->library('app_clients_area_constructor');

        if (method_exists($this, 'validateContact')) {
            $this->validateContact();
        }

        // init_admin_assets();
        $vars = [
            'current_user' => $currentUser,
            'current_version' => $this->current_db_version,
            'task_statuses' => $this->tasks_model->get_statuses(),
            'cart_prospects' => $cart_prospects
        ];

        $vars['sidebar_menu'] = $this->app_menu->get_client_sidebar_menu_items();
        $this->load->vars($vars);
    }

    public function layout($notInThemeViewFiles = false)
    {
        /**
         * Navigation and submenu
         * @deprecated 2.3.2
         * @var boolean
         */

        $this->data['use_navigation'] = $this->use_navigation == true;
        $this->data['use_submenu'] = $this->use_submenu == true;

        /**
         * @since  2.3.2 new variables
         * @var array
         */
        $this->data['navigationEnabled'] = $this->use_navigation == true;
        $this->data['subMenuEnabled'] = $this->use_submenu == true;

        /**
         * Theme head file
         * @var string
         */
        $this->template['head'] = $this->load->view('themes/' . active_clients_theme() . '/head', $this->data, true);

        $GLOBALS['customers_head'] = $this->template['head'];

        /**
         * Load the template view
         * @var string
         */
        $module = CI::$APP->router->fetch_module();
        $this->data['current_module'] = $module;
        $viewPath = !is_null($module) || $notInThemeViewFiles ?
            $this->view :
            $this->createThemeViewPath($this->view);

        $this->template['view'] = $this->load->view($viewPath, $this->data, true);
        $GLOBALS['customers_view'] = $this->template['view'];

        /**
         * Theme footer
         * @var string
         */
        $this->template['footer'] = $this->use_footer == true
            ? $this->load->view('themes/' . active_clients_theme() . '/footer', $this->data, true)
            : '';
        $GLOBALS['customers_footer'] = $this->template['footer'];

        /**
         * @deprecated 2.3.0
         * Theme scripts.php file is no longer used since vresion 2.3.0, add app_customers_footer() in themes/[theme]/index.php
         * @var string
         */
        $this->template['scripts'] = '';
        if (file_exists(VIEWPATH . 'themes/' . active_clients_theme() . '/scripts.php')) {
            if (ENVIRONMENT != 'production') {
                trigger_error(sprintf('%1$s', 'Clients area theme file scripts.php file is no longer used since version 2.3.0, add app_customers_footer() in themes/[theme]/index.php. You can check the original theme index.php for example.'));
            }

            $this->template['scripts'] = $this->load->view('themes/' . active_clients_theme() . '/scripts', $this->data, true);
        }

        /**
         * Load the theme compiled template
         */
        $this->load->view('themes/' . active_clients_theme() . '/index', $this->template);
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
     * Create theme view path
     *
     * @param  string $view
     *
     * @return string
     */
    protected function createThemeViewPath($view)
    {
        return 'themes/' . active_clients_theme() . '/views/' . $view;
    }
}
