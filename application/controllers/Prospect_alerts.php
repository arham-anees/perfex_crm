<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_alerts extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Prospect_alerts_model');
        
        $this->load->model('leadevo/Industries_model');
        
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
        if (is_client_logged_in() && !is_contact_email_verified()) {
            redirect(site_url('verification'));
        }
        if(!is_onboarding_completed()){
            redirect(site_url('onboarding'));
        }
    }

    public function index()
    {
        // Fetch filter parameter
        $filter = $this->input->get('filter');
        
        // Prepare the conditions based on the filter
        $conditions = [];
        if ($filter == 'active') {
            $conditions['status'] = 1;
        } elseif ($filter == 'inactive') {
            $conditions['status'] = 0;
        }
        if($this->input->post()){
        
            $search = array(
                'industry_name' => $this->input->post('industry'),
                'acquisition_channel_id' => $this->input->post('acquisition'),
                'status' => $this->input->post('status'),
                'deal' => $this->input->post('deal'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone_no' => $this->input->post('phone_no'),
                
                // 'zip_codes' => $this->input->post('zip_codes')
            );
        //        echo "<pre>";
        // print_r($search);
        // exit;
            $data['alerts'] = $this->Prospect_alerts_model->get_all($search);
        }else{
            // Fetch alerts

            $data['alerts'] = $this->Prospect_alerts_model->get_all();
        }
        
        // Fetch industries for other purposes if needed
        $data['industries'] = $this->Industries_model->get_all('');
    
        // Fetch acquisition channels if needed
        $data['acquisition_channels'] = $this->Prospect_alerts_model->get_all_acquisition_channels();
        // echo "<pre>";
        // print_r($data['alerts']);exit;
        // Passing data to the view
        $this->data($data);
        $this->view('clients/prospect_alerts/prospect_alerts');
        $this->layout();
    }
    
   public function create()
{
        $this->form_validation->set_rules('name','Name', 'required');
        $this->form_validation->set_rules('phone','Phone', 'required');
        $this->form_validation->set_rules('email','Email', 'trim|required|valid_email');
        
        $this->form_validation->set_rules('is_exclusive','Type', 'required');
  
        $this->form_validation->set_rules('acquisition_channel_id','Acquisition Channel', 'required');
      
    if ($this->input->post() && $this->form_validation->run() !== false) {
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'industry_id' => $this->input->post('industry_id'),
            'acquisition_channel_id' => $this->input->post('acquisition_channel_id'),
            'is_exclusive' => (int) $this->input->post('is_exclusive'),
            'verified_whatsapp' => $this->input->post('verified_whatsapp') ? 1 : 0,
            'verified_sms' => $this->input->post('verified_sms') ? 1 : 0,
            'verified_staff' => $this->input->post('verified_staff') ? 1 : 0,
        ];

        // Insert the data into tblleadevo_prospect_alerts
        $this->Prospect_alerts_model->insert($data);
        redirect('prospect_alerts');
    } else {
      

        // Fetch industries from tblleadevo_industries for the dropdown
        $data['industries'] = $this->Prospect_alerts_model->get_all_industries();

        // Fetch acquisition channels for the dropdown
        $data['acquisition_channels'] = $this->Prospect_alerts_model->get_all_acquisition_channels();

        // Pass data to the view
        $this->data($data);
        $this->view('clients/prospect_alerts/create');
        $this->layout();
    }
}

    
public function edit($id)
{
    if ($this->input->post()) {
        // Fetch and prepare the updated data
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'is_exclusive' => (int) $this->input->post('is_exclusive'),
            'acquisition_channel_id' => $this->input->post('acquisition_channel_id'),
            'industry_id' => $this->input->post('industry_id'),
            'source_id' => $this->input->post('source_id'),
            'verified_whatsapp' => $this->input->post('verified_whatsapp') === null ? null : (int) $this->input->post('verified_whatsapp'),
            'verified_sms' => $this->input->post('verified_sms') === null ? null : (int) $this->input->post('verified_sms'),
            'verified_staff' => $this->input->post('verified_staff') === null ? null : (int) $this->input->post('verified_staff')
        ];

        // Update the prospect alert in the database
        $this->Prospect_alerts_model->update($id, $data);
        redirect('prospect_alerts');
    } else {
        // Fetch the current prospect alert data
        $data['alert'] = $this->Prospect_alerts_model->get($id);
        
        // Fetch industries for the dropdown
        $data['industries'] = $this->Prospect_alerts_model->get_all_industries();
        
        // Fetch acquisition channels for the dropdown
        $data['acquisition_channels'] = $this->Prospect_alerts_model->get_all_acquisition_channels();
        // Load the edit view with the current data
        $this->data($data);
        $this->view('clients/prospect_alerts/edit');
        $this->layout();
    }
}


    public function delete($id)
    {
        if ($this->Prospect_alerts_model->delete($id)) {
            set_alert('success', 'Prospect alert deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete prospect alert.');
        }
        redirect('prospect_alerts');
    }
    public function activate($id)
    {
        if ($this->Prospect_alerts_model->activate($id)) {
            set_alert('success', _l('leadevo_prospect_alert_activated'));
        } else {
            set_alert('danger', _l('leadevo_prospect_alert_activation_failed'));
        }
        redirect('prospect_alerts');
    }
    public function deactivate($id)
    {
        if ($this->Prospect_alerts_model->deactivate($id)) {
            set_alert('success', _l('leadevo_prospect_alert_deactivated'));
        } else {
            set_alert('danger', _l('leadevo_prospect_alert_deactivation_failed'));
        }
        redirect('prospect_alerts');
    }
    public function details($id)
    {
        $data['alert'] = $this->Prospect_alerts_model->get($id);
        $this->data($data);
        $this->view('clients/prospect_alerts/view');
        $this->layout();
    }

    public function send_alerts()
    {
        $this->Prospect_alerts_model->send_alerts();
    }
}
