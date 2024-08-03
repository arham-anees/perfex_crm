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


    public function deals() {
        if ($this->input->post()) {
            $data = $this->input->post();
        
            // Extract form data
            $nonexclusive_status = $data['nonexclusive_status'];
            $max_sell_time = $data['max_sell_time'];
            $days_to_discount = $data['days_to_discount'];
            $discount_type = $data['discount_type'];
            $discount_amount = $data['discount_amount']; // Assuming you have this field in the form
    
            // Data array to insert into tblleadevo_deals_settings
            $insertData = [
                'nonexclusive_status' => $nonexclusive_status,
                'max_sell_times' => $max_sell_time,
                'days_to_discount' => $days_to_discount,
                'Discount_mode_percentage' => $discount_type,
                'Discount_amount' => $discount_amount
            ];
    
            // Insert data into the database
            $this->marketpalceDb->insert('tblleadevo_deals_settings', $insertData);
    
            // Check if the row was inserted
            if ($this->marketpalceDb->affected_rows() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Settings saved']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save settings']);
            }
            exit;
        }
    
        // Load the view if not a POST request
        $this->load->view('setup/deals_settings');
    }
    
    

}
