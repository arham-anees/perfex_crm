<?php defined('BASEPATH') or exit('No direct script access allowed');

class Invite extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('lead_reasons_model');
    }

    public function index()
    {
        $this->load->view('invite/index');
    }

    private function _validate_email($email){
        return true;
    }
    public function send_invitation(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->input->post();
            
            $email = $data['email'];
            if(!isset($email) || !_validate_email($email) ){
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
        } else {
          
        }

    }

}