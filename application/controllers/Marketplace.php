<?php defined('BASEPATH') or exit('No direct script access allowed');
class Marketplace extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Industries_model');
        $this->load->model('leadevo/Cart_model');

    }

    public function index()
    {
        $data['prospects'] = $this->Prospects_model->get_all();
        $data['industries'] = $this->Industries_model->get_all();
        $data['cart'] = $this->Cart_model->get();
        // Create an array of prospect_ids from the cart for easy lookup
        $cart_prospect_ids = array_map(function ($item) {
            return $item['prospect_id'];
        }, $data['cart']);

        // Iterate through prospects and set is_in_cart property
        foreach ($data['prospects'] as &$prospect) {
            $prospect['is_in_cart'] = in_array($prospect['id'], $cart_prospect_ids);
        }
        $this->data($data);
        $this->view('clients/marketplace/leads');
        $this->layout();
    }
}
