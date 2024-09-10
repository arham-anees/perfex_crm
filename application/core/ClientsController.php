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

        // Load necessary models
        $this->load->model('clients_model');
        $this->load->model('authentication_model');
        $this->load->model('leadevo/Cart_model');

        // Initialize default variables
        $cart_prospects = [];
        $currentUser = null;

        // Load cart prospects if the user is logged in
        if (is_client_logged_in()) {
            // Retrieve the current user and cart prospects
            $currentUser = $this->clients_model->get_contact(get_contact_user_id());
            $cart_prospects = $this->Cart_model->get_cart_prospects();

            // Trigger client-specific hooks
            hooks()->do_action('client_init');
            hooks()->do_action('app_client_assets');
        }

        // Set global variables to be available across views
        $vars = [
            'current_version' => $this->current_db_version,
            'task_statuses' => $this->tasks_model->get_statuses(),
            'current_user' => $currentUser,
            'cart_prospects' => $cart_prospects, // Make cart prospects globally accessible
        ];

        // Initialize sidebar menu and load it globally
        $vars['sidebar_menu'] = $this->app_menu->get_client_sidebar_menu_items();
        $this->load->vars($vars);

        // Load clients area constructor library
        $this->load->library('app_clients_area_constructor');

        // Validate the contact if required
        if (method_exists($this, 'validateContact')) {
            $this->validateContact();
        }
    }

    public function layout($notInThemeViewFiles = false)
    {
        // Manage navigation and submenu options
        $this->data['use_navigation'] = $this->use_navigation == true;
        $this->data['use_submenu'] = $this->use_submenu == true;

        // Additional navigation variables (since v2.3.2)
        $this->data['navigationEnabled'] = $this->use_navigation == true;
        $this->data['subMenuEnabled'] = $this->use_submenu == true;

        // Load the theme's head view
        $this->template['head'] = $this->load->view('themes/' . active_clients_theme() . '/head', $this->data, true);
        $GLOBALS['customers_head'] = $this->template['head'];

        // Load the appropriate view
        $module = CI::$APP->router->fetch_module();
        $this->data['current_module'] = $module;
        $viewPath = !is_null($module) || $notInThemeViewFiles
            ? $this->view
            : $this->createThemeViewPath($this->view);

        $this->template['view'] = $this->load->view($viewPath, $this->data, true);
        $GLOBALS['customers_view'] = $this->template['view'];

        // Load footer if enabled
        $this->template['footer'] = $this->use_footer == true
            ? $this->load->view('themes/' . active_clients_theme() . '/footer', $this->data, true)
            : '';
        $GLOBALS['customers_footer'] = $this->template['footer'];

        // Render the final template
        $this->load->view('themes/' . active_clients_theme() . '/index', $this->template);
    }

    /**
     * Sets view data
     * @param  array $data
     * @return ClientsController
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
     * Set the view to load
     * @param  string $view
     * @return ClientsController
     */
    public function view($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Sets the title for the view
     * @param  string $title
     * @return ClientsController
     */
    public function title($title)
    {
        $this->data['title'] = $title;
        return $this;
    }

    /**
     * Disables navigation for the theme
     * @return ClientsController
     */
    public function disableNavigation()
    {
        $this->use_navigation = false;
        return $this;
    }

    /**
     * Disables the submenu for the theme
     * @return ClientsController
     */
    public function disableSubMenu()
    {
        $this->use_submenu = false;
        return $this;
    }

    /**
     * Disables the footer for the theme
     * @return ClientsController
     */
    public function disableFooter()
    {
        $this->use_footer = false;
        return $this;
    }

    /**
     * Creates the theme view path
     * @param  string $view
     * @return string
     */
    protected function createThemeViewPath($view)
    {
        return 'themes/' . active_clients_theme() . '/views/' . $view;
    }
}
