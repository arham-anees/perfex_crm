<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Onboarding_model');
        $this->load->database();

        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }

        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
    }

    public function index()
    {
        $client_id = get_client_user_id();
        $data['title'] = _l('Onboarding');
        $data['completed_step'] = $this->get_completed_step($client_id);

        $data['steps'] = $this->get_onboarding_steps(); // Load onboarding steps dynamically
        // dd($data);
        $this->data($data);
        $this->view('clients/onboarding');
        $this->layout();
    }

    private function get_completed_step($client_id)
    {
        $this->db->select('onboarding_step');
        $this->db->where('client_id', $client_id);
        $query = $this->db->get('tblleadevo_onboarding');

        $result = $query->row();
        return isset($result) ? $result->onboarding_step : 0;
    }

    private function get_onboarding_steps()
    {
        $this->db->order_by('step_number', 'ASC');
        return $this->db->get('tblleadevo_onboarding_steps')->result_array();
    }
    public function update_step()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $step = $data['onboarding_step'];
            $client_id = get_client_user_id(); // Get the client ID
            $data['client_id'] = $client_id; // Ensure client_id is included in the data

            if ($step == 1) {
                $this->Onboarding_model->insert($data);
            } else {
                $this->Onboarding_model->update($client_id, $data); // Pass both client ID and data
            }
        }
    }
}
