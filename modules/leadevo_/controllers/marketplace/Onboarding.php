<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->mpDb = $this->load->database();
        $this->load->database();
    }

    public function index()
    {
        // Load the onboarding view
        $data['title'] = _l('Onboarding');
        // check unique identification
        log_message('error', get_marketplace_id());
        $this->load->view('marketplace/onboarding', $data);

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
