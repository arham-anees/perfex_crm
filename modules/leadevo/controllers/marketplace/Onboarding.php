<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/explanatory_videos_model'); // Correct path to the model
    }

    public function index()
    {
        $data['title'] = _l('Onboarding');
        $data['videos'] = $this->explanatory_videos_model->get_all(); // Fetch all videos

        // Load the view with the videos data
        $this->load->view('marketplace/onboarding', $data);
    }
    public function update_step()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $step = $data['onboarding_step'];
            $data['client_id'] = get_client_user_id();
            if ($step == 1) {
                $this->Onboarding_model->insert($data);
            } else {
                $this->Onboarding_model->update($data);
            }
        }
    }



    // Method to handle Facebook group join action
    public function join_facebook_group()
    {
        echo json_encode(['status' => 'success', 'message' => 'Facebook group joined successfully.']);
    }

    // Method to handle signup for email alerts action
    public function signup_alert()
    {
        echo json_encode(['status' => 'success', 'message' => 'Signed up for alerts successfully.']);
    }
}
