<?php
class Statistics extends ClientsController {

    public function __construct()
    {
        parent::__construct();
        //load some models
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Cart_model');
    }

    public function index()
    {
        $data['campaigns'] = $this->Campaigns_model->get_all();
        $data['prospects'] = $this->Prospects_model->get_all();
        $this->view('clients/statistics/statistics', $data);

        
        $this->layout();
    }
}
?>
