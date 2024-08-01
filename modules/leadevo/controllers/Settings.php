<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('prospect_types_model');
        $this->load->database();
        $marketpalceDb = $this->load->database('leadevo_marketplace', true);
    }

    public function index()
    {
        if ($this->input->post()) {
            $sql='';
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings_0stars')."' WHERE name LIKE 'delivery_settings_0stars';";
            $this->db->query($sql);
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings_1stars')."' WHERE name LIKE 'delivery_settings_1stars';";
            $this->db->query($sql);
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings_2stars')."' WHERE name LIKE 'delivery_settings_2stars';";
            $this->db->query($sql);
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings_3stars')."' WHERE name LIKE 'delivery_settings_3stars';";
            $this->db->query($sql);
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings_4stars')."' WHERE name LIKE 'delivery_settings_4stars';";
            $this->db->query($sql);
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings_5stars')."' WHERE name LIKE 'delivery_settings_5stars';";
            $this->db->query($sql);
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings_5stars')."' WHERE name LIKE 'delivery_settings_5stars';";
            $this->db->query($sql);
            $sql = " UPDATE ". db_prefix() ."options SET value = '".$this->input->post('delivery_settings')."' WHERE name LIKE 'delivery_settings';";
            $this->db->query($sql);

        }
        $this->load->view('setup/delivery_quality');
    }

    public function deals(){
        if ($this->input->post()) {
            $data = $this->input->post();
            $sql='';
            //
            $this->marketpalceDb->query($sql);
        }

        // read data
        $this->load->view('setup/deals_settings');
    }

}
