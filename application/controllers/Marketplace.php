<?php defined('BASEPATH') or exit('No direct script access allowed');

class Marketplace extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Industries_model');
        $this->load->model('leadevo/Acquisition_channels_model');
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Cart_model'); // Ensure Cart_model is loaded
        $this->load->helper('general');
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
    
            // Apply masking functions
            foreach ($prospects as &$prospect) {
                $prospect['last_name'] = maskLastNameFunction($prospect['last_name']);
                $prospect['phone'] = maskPhoneNumber($prospect['phone']);
                $prospect['email'] = maskEmailAddress($prospect['email']);
            }
    
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
    
            // Apply masking functions
            foreach ($data['prospects'] as &$prospect) {
                $prospect['last_name'] = maskLastNameFunction($prospect['last_name']);
                $prospect['phone'] = maskPhoneNumber($prospect['phone']);
                $prospect['email'] = maskEmailAddress($prospect['email']);
            }
        }

        // Fetch other necessary data
        $data['industries'] = $this->Industries_model->get_all();
        $data['acquisitions'] = $this->Acquisition_channels_model->get_all();
        $data['countries'] = $this->Campaigns_model->get_all_countries();

        // Get cart prospects with additional details
        $data['cart_prospects'] = $this->Cart_model->get_cart_prospects(); // Update this to the new method

        // Create an array of prospect_ids from the cart for easy lookup
        $cart_prospect_ids = array_map(function ($item) {
            return $item['prospect_id'];
        }, $data['cart_prospects']);

        // Iterate through prospects and set is_in_cart property
        foreach ($data['prospects'] as &$prospect) {
            $prospect['is_in_cart'] = in_array($prospect['id'], $cart_prospect_ids);
        }

        // Load the view
        $this->data($data);
        $this->view('clients/marketplace/leads');
        $this->layout();
    }
    public function cart_view()
    {
        if (!is_client_logged_in()) {
            redirect('login'); // Redirect to login if not logged in
        }

        // Fetch all cart prospects for the logged-in client
        $data['cart_prospects'] = $this->Cart_model->get_cart_prospects();

        // Load view with cart details
        $this->data($data);
        $this->view('clients/marketplace/cart_view');
        $this->layout();
    }

    public function delete_from_cart($prospect_id)
    {
        $client_id = get_client_user_id();

        // Delete the item from the cart
        $this->Cart_model->delete_item($client_id, $prospect_id);

        // Return a JSON response
        echo json_encode(['status' => 'success']);
        exit;
    }


}
