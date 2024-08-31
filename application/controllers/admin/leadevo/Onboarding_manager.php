<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding_manager extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Manage Onboarding Steps';
        $data['onboarding_steps'] = $this->get_onboarding_steps();
        // dd($data);
        $this->load->view('admin/setup/onboarding/onboarding', $data);
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($step = 1; $step <= 6; $step++) {
                $step_title = $this->input->post("step{$step}_step_title");
                $step_content = $this->input->post("step{$step}_step_content");
                $content = $this->input->post("step{$step}_content");
                $type = $this->input->post("step{$step}_type");
                // Check if the step already exists in the database
                $this->db->where('step_number', $step);
                $existing_step = $this->db->get('tblleadevo_onboarding_steps')->row();

                if ($existing_step) {
                    // Update the existing step
                    $this->db->where('step_number', $step);
                    $this->db->update('tblleadevo_onboarding_steps', [
                        'step_title' => $step_title,
                        'step_content' => $step_content,
                        'content' => $content,
                        'type' => $type,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Insert a new step if it doesn't exist
                    $this->db->insert('tblleadevo_onboarding_steps', [
                        'step_number' => $step,
                        'step_title' => $step_title,
                        'step_content' => $step_content,
                        'content' => $content,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            set_alert('success', 'Onboarding steps updated successfully.');
            redirect(admin_url('leadevo/onboarding_manager'));
        }


    }

    private function get_onboarding_steps()
    {
        $this->db->order_by('step_number', 'asc');
        return $this->db->get('tblleadevo_onboarding_steps')->result_array();
    }
}
