<?php
class Statistics extends ClientsController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('leadevo/Stats_model');
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
    }

    public function index()
    {

        $data['campaign_stats'] = $this->Stats_model->client_campaigns();

        $this->data($data);
        $this->view('clients/statistics/statistics');
        $this->layout();
    }
}
?>