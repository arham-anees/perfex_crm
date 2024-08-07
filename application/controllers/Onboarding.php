<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding extends ClientsController
{
    // private $Onboarding_model;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/explanatory_videos_model'); // Correct path to the model

        $this->load->database();
        $this->load->model('leadevo/Onboarding_model');
    }

    public function index()
    {
        // Load the onboarding view
        $data['title'] = _l('Onboarding');
        $data['videos'] = $this->explanatory_videos_model->get_all(); // Fetch all videos


        $onboarding = $this->Onboarding_model->get(get_client_user_id());
        if (isset($onboarding)) {
            $data['completed_step'] = $onboarding->onboarding_step;
        } else {
            $data['completed_step'] = '0';
        }
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
        $client_id = get_client_user_id(); // Get the client ID
        $data['client_id'] = $client_id; // Ensure client_id is included in the data

        if ($step == 1) {
            $this->Onboarding_model->insert($data);
        } else {
            $this->Onboarding_model->update($client_id, $data); // Pass both client ID and data
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
