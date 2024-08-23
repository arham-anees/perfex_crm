<?php defined('BASEPATH') or exit('No direct script access allowed');

class Public_paths extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // Cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }


        $this->load->model('Authentication_model');
    }

    public function index()
    {
        // Your code here
    }

    public function authenticate()
    {
        return $this->Authentication_model->login_third_party(
            $this->input->post('email'),
            $this->input->post('password', false),
            $this->input->post('remember'),
            false
        );
    }
    public function me()
    {
        return $this->Authentication_model->login_third_party(
            'imran@mail.com',
            'test',
            $this->input->post('remember'),
            false
        );
    }
    public function receive_zapier()
    {

    }
}