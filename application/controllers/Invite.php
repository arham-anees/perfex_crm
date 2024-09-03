<?php

use Affiliate\Automattic\WooCommerce\Client;

defined('BASEPATH') or exit('No direct script access allowed');

class Invite extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model'); // Use lowercase for model loading
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->input->post();

            $email = $data['email'] ?? null;
            $name = $data['name'] ?? null;

            if (empty($email)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => _l('appointment_dates_required')
                ]);
                exit; // Prefer exit over die for clarity
            }

            // Handle reCAPTCHA validation if enabled
            if (!empty($data['g-recaptcha-response']) && get_option('recaptcha_secret_key') && get_option('recaptcha_site_key')) {
                if (!do_recaptcha_validation($data['g-recaptcha-response'])) {
                    echo json_encode([
                        'status' => 'error',
                        'recaptcha' => false,
                        'message' => _l('recaptcha_error'),
                    ]);
                    exit;
                }
            }

            // Remove unwanted fields from the data array
            unset($data['g-recaptcha-response'], $data['Array']);

            // Prepare and send email using mail_template
            $template = mail_template('Leadevo_invite_friend', (object) [
                'email' => $email,
                'name' => $name
            ]);

            $template->send();

            // Prepare lead data
            $lead_data = [
                'email' => $email,
                'name' => $name,
                'description' => '', // Adjust as needed
                'address' => '', // Adjust as needed
                'status' => 2,  // Status ID - adjust as necessary
                'assigned' => get_staff_user_id(),
                'hash' => app_generate_hash()
            ];

            // Add lead to the database
            $this->leads_model->add($lead_data);

            // Send success response
            echo json_encode([
                'status' => 'success',
                'message' => _l('leadevo_invite_sent_successfully'),
            ]);
        } else {

            // Load the view
            $this->view('clients/invite/invite_view');
            $this->layout();
        }
    }
}
