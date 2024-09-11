<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends ClientsController
{
    public function __construct()
    {
        parent::__construct();

        // Ensure the client is logged in and has completed the necessary steps
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
        if (!is_onboarding_completed()) {
            redirect(site_url('onboarding'));
        }
    }

    public function index($tab = 'profile')
    {
        $data['title'] = 'Client Settings';
        $data['active_tab'] = $tab; 
        $this->data($data);
        $this->view('clients/settings/index');
        $this->layout();
    }
}
