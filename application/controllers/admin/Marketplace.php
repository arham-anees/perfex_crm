<?php defined('BASEPATH') or exit('No direct script access allowed');
class Marketplace extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Industries_model');
        $this->load->model('leadevo/Acquisition_channels_model');
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Cart_model');

        if (!staff_can('manage_marketplace', 'leadevo')) {
            access_denied();
        }
    }

    public function index()
    {
        // Check if form is submitted
        if ($this->input->post()) {
            // Retrieve filters from POST request
            $filter = array(
                'prospect_name' => $this->input->post('name'),
                'industry_id' => $this->input->post('industry_id'),
                'acquisition_channel_id' => $this->input->post('acquisition'),
                'price_range_from' => $this->input->post('price_range_start'),
                'price_range_to' => $this->input->post('price_range_end'),
                'generated_from' => $this->input->post('location_from'),
                'generated_to' => $this->input->post('location_to'),
                'deal' => $this->input->post('deal'),
                'quality' => $this->input->post('quality'),
                // 'zip_codes' => $this->input->post('zip_codes')
            );

            // Get filtered prospects
            $prospects = $this->Prospects_model->get_all_market_place($filter);

            // Check if the request is an AJAX request
            if ($this->input->is_ajax_request()) {
                // Return JSON response
                echo json_encode($prospects);
                exit;
            } else {
                $data['prospects'] = $prospects;
            }
        } else {
            // Get all prospects if no filter is applied
            $data['prospects'] = $this->Prospects_model->get_all_market_place();
        }
        // echo "<pre>";
        // print_r($data['prospects']);exit;
        // Fetch other necessary data
        $data['industries'] = $this->Industries_model->get_all();
        $data['acquisitions'] = $this->Acquisition_channels_model->get_all();
        $data['countries'] = $this->Campaigns_model->get_all_countries();

        // Load the view
        $this->load->view('admin/marketplace/leads', $data);
    }

    public function remove_from_market($id)
    {
        // $id = $this->input->post('id');
        if (isset($id)) {
            $this->Prospects_model->update_sale_status($id, 0, 0, 0, 0);
            prospect_activity($id, 'remove_from_market', '');
        }
        redirect(admin_url('/marketplace'));
    }

}
