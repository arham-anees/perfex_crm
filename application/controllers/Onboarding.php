<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding extends ClientsController
{
    private $Onboarding;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Onboarding');
    }

    public function index()
    {
        // Load the onboarding view
        $data['title'] = _l('Onboarding');
        // check unique identification
        $this->data($data);
        $this->view('clients/onboarding');
        $this->layout();
    }

    public function update_step()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $step = $data['onboarding_step'];
            $data['client_id'] = get_client_user_id();
            if ($step == 1) {
                $this->Onboarding->insert($data);
            } else {
                $this->Onboarding->update($data);
            }
        }
    }



    // Method to handle Facebook group join action
    public function join_facebook_group()
    {
        // Example: Update the user's progress in the database
        // $this->onboarding_model->update_progress($user_id, 'facebook_group', true);

        // Return a JSON response
        echo json_encode(['status' => 'success', 'message' => 'Facebook group joined successfully.']);
    }

    // Method to handle signup for email alerts action
    public function signup_alert()
    {
        // Example: Update the user's progress in the database
        // $this->onboarding_model->update_progress($user_id, 'signup_alert', true);

        // Return a JSON response
        echo json_encode(['status' => 'success', 'message' => 'Signed up for alerts successfully.']);
    }
}
