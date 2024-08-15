<?php
class Statistics extends ClientsController 
    {
        public function __construct()
        {
            parent::__construct();

            $this->load->model('leadevo/Stats_model'); 
        }
    
    public function index()
    {
        $data['dashboard_stats'] = $this->Stats_model->client_dashboard();
        $data['campaign_stats'] = $this->Stats_model->client_campaigns();

        $this->data($data);
        $this->view('clients/statistics/statistics');
        $this->layout();
    }
}
?>
