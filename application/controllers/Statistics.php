<?php
class Statistics extends ClientsController {

    public function __construct()
    {
        parent::__construct();
        //load some models
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/stats_model');
    }

    public function index()
    {
        $data['dashboard_stats'] = $this->Stats_model->client_dashboard();
        $data['campaign_stats'] = $this->Stats_model->client_campaigns();
        $this->view('clients/statistics/statistics', $data);

        
        $this->layout();
    }
}
?>
