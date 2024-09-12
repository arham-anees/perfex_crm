<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends ClientsController
{
    public function __construct()
    {
        parent::__construct();

        // Ensure the client is logged in and has completed the necessary steps
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
        if (!is_onboarding_completed()) {
            redirect(site_url('onboarding'));
        }

        
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Stats_model');
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Reported_Prospects_model');
        $this->load->model('leadevo/Industries_model');
        $this->load->model('leadevo/Onboarding_model');
        $this->load->model('Misc_model');
    }

    public function index()
    {
        $this->profile();
    }

    public function onChange($tab)
    {
     
        switch ($tab) {
            case 'profile':
                $this->profile();
                break;
            case 'billing':
                $this->billing();
                break;
            case 'report':
                $this->statistics();
                break;
            default:
                echo 'Invalid tab selected';
        }
    }

    public function billing($status = false)
    {
        if (!has_contact_permission('invoices')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }

        $where = [
            'clientid' => get_client_user_id(),
        ];

        if (is_numeric($status)) {
            $where['status'] = $status;
        }

        if (isset($where['status'])) {
            if (
                $where['status'] == Invoices_model::STATUS_DRAFT
                && get_option('exclude_invoice_from_client_area_with_draft_status') == 1
            ) {
                unset($where['status']);
                $where['status !='] = Invoices_model::STATUS_DRAFT;
            }
        } else {
            if (get_option('exclude_invoice_from_client_area_with_draft_status') == 1) {
                $where['status !='] = Invoices_model::STATUS_DRAFT;
            }
        }

        $data['invoices'] = $this->invoices_model->get('', $where);
        $data['title'] = _l('clients_my_invoices');
        $this->data($data);
        $this->view('clients/settings/invoices');
        $this->layout();
    }

    public function profile()
    {
        if ($this->input->post('profile')) {
            $this->form_validation->set_rules('firstname', _l('client_firstname'), 'required');
            $this->form_validation->set_rules('lastname', _l('client_lastname'), 'required');

            $this->form_validation->set_message('contact_email_profile_unique', _l('form_validation_is_unique'));
            $this->form_validation->set_rules('email', _l('clients_email'), 'required|valid_email|callback_contact_email_profile_unique');

            $custom_fields = get_custom_fields('contacts', [
                'show_on_client_portal' => 1,
                'required' => 1,
                'disalow_client_to_edit' => 0,
            ]);
            foreach ($custom_fields as $field) {
                $field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
                if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
                    $field_name .= '[]';
                }
                $this->form_validation->set_rules($field_name, $field['name'], 'required');
            }
            if ($this->form_validation->run() !== false) {
                handle_contact_profile_image_upload();

                $data = $this->input->post();

                $contact = $this->clients_model->get_contact(get_contact_user_id());

                if (has_contact_permission('invoices')) {
                    $data['invoice_emails'] = isset($data['invoice_emails']) ? 1 : 0;
                    $data['credit_note_emails'] = isset($data['credit_note_emails']) ? 1 : 0;
                } else {
                    $data['invoice_emails'] = $contact->invoice_emails;
                    $data['credit_note_emails'] = $contact->credit_note_emails;
                }

                if (has_contact_permission('estimates')) {
                    $data['estimate_emails'] = isset($data['estimate_emails']) ? 1 : 0;
                } else {
                    $data['estimate_emails'] = $contact->estimate_emails;
                }

                if (has_contact_permission('support')) {
                    $data['ticket_emails'] = isset($data['ticket_emails']) ? 1 : 0;
                } else {
                    $data['ticket_emails'] = $contact->ticket_emails;
                }

                if (has_contact_permission('contracts')) {
                    $data['contract_emails'] = isset($data['contract_emails']) ? 1 : 0;
                } else {
                    $data['contract_emails'] = $contact->contract_emails;
                }

                if (has_contact_permission('projects')) {
                    $data['project_emails'] = isset($data['project_emails']) ? 1 : 0;
                    $data['task_emails'] = isset($data['task_emails']) ? 1 : 0;
                } else {
                    $data['project_emails'] = $contact->project_emails;
                    $data['task_emails'] = $contact->task_emails;
                }

                $success = $this->clients_model->update_contact([
                    'firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname'),
                    'title' => $this->input->post('title'),
                    'email' => $this->input->post('email'),
                    'phonenumber' => $this->input->post('phonenumber'),
                    'direction' => $this->input->post('direction'),
                    'invoice_emails' => $data['invoice_emails'],
                    'credit_note_emails' => $data['credit_note_emails'],
                    'estimate_emails' => $data['estimate_emails'],
                    'ticket_emails' => $data['ticket_emails'],
                    'contract_emails' => $data['contract_emails'],
                    'project_emails' => $data['project_emails'],
                    'task_emails' => $data['task_emails'],
                    'custom_fields' => isset($data['custom_fields']) && is_array($data['custom_fields']) ? $data['custom_fields'] : [],
                ], get_contact_user_id(), true);

                if ($success == true) {
                    set_alert('success', _l('clients_profile_updated'));
                }

                redirect(site_url('clients/profile'));
            }
        } elseif ($this->input->post('change_password')) {
            $this->form_validation->set_rules('oldpassword', _l('clients_edit_profile_old_password'), 'required');
            $this->form_validation->set_rules('newpassword', _l('clients_edit_profile_new_password'), 'required');
            $this->form_validation->set_rules('newpasswordr', _l('clients_edit_profile_new_password_repeat'), 'required|matches[newpassword]');
            if ($this->form_validation->run() !== false) {
                $success = $this->clients_model->change_contact_password(
                    get_contact_user_id(),
                    $this->input->post('oldpassword', false),
                    $this->input->post('newpasswordr', false)
                );

                if (is_array($success) && isset($success['old_password_not_match'])) {
                    set_alert('danger', _l('client_old_password_incorrect'));
                } elseif ($success == true) {
                    set_alert('success', _l('client_password_changed'));
                }

                redirect(site_url('clients/profile'));
            }
        }
        $data['title'] = _l('clients_profile_heading');
        $this->data($data);
        $this->view('clients/settings/profile');
        $this->layout();
    }

    public function statistics(){
        $data['campaign_stats'] = $this->Stats_model->client_campaigns();

        $this->data($data);
        $this->view('clients/settings/statistics');
        $this->layout();
    }

}
