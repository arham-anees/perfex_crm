<?php defined('BASEPATH') or exit('No direct script access allowed');

class Invite extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Leads_model');
    }

   
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->input->post();
            
            $email = $data['email'];
            $name = $data['name'];
            if(!isset($email) ){
                echo json_encode([
                    'success' => false,
                    'message' => _l('appointment_dates_required')
                ]);
                die;
            }
            if (!$data) {
                show_404();
            }
            

            if (isset($data['g-recaptcha-response'])) {
                if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') {
                    if (!do_recaptcha_validation($data['g-recaptcha-response'])) {
                        echo json_encode([
                            'success'   => false,
                            'recaptcha' => false,
                            'message'   => _l('recaptcha_error'),
                        ]);
                        die;
                    }
                }
            }

            if (isset($data['g-recaptcha-response']))
                unset($data['g-recaptcha-response']);
            if (isset($data['Array'])) {
                unset($data['Array']);
            }
         
            echo json_encode([
                'success' => true,
                'message' => _l('appointment_sent_successfully'),
            ]);
            $template = mail_template('Leadevo_invite_friend', 'leadevo',array_to_object(['email'=>$email, 'name'=>$name]));

            $lead_data = [];
            $lead_data['email'] = $email;
            $lead_data['name'] = $name;
            $lead_data['description'] = '';
            $lead_data['address'] = '';
            $lead_data['status'] = '2';
            $lead_data['source'] = getInviteSourceId()['id'];
            $lead_data['assigned'] = get_staff_user_id();
            $lead_data['hash'] = app_generate_hash();
            $this->leads_model->add($lead_data);
    
            $template->send();

        } 
        $this->load->view('invite/index');
    }

}