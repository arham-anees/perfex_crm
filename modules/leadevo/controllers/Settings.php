<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{
    private $marketplaceDb;
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('prospect_types_model');
        $this->load->database();
        $this->marketplaceDb = $this->load->database('leadevo_marketplace', true);
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
            $max_sell_times = $data['max_sell_times'];
            $days_to_discount = $data['days_to_discount'];
            $discount_type = $data['discount_type'];
            $discount_amount = $data['discount_amount']; // Assuming you have this field in the form
    
            // Data array to insert into tblleadevo_deals_settings
            $updateData = [
                'nonexclusive_status' => $nonexclusive_status,
                'max_sell_times' => $max_sell_times,
                'days_to_discount' => $days_to_discount,
                'discount_type' => $discount_type,
                'discount_amount' => $discount_amount
            ];
    
            $this->marketplaceDb->where('tenant_id',  get_marketplace_id());
            // Insert data into the database
            $this->marketplaceDb->update('tblleadevo_deals_settings', $updateData);
    
            // Check if the row was inserted
            if ($this->marketplaceDb->affected_rows() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Settings saved']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save settings']);
            }
            exit;
        }
    }
    public function get_deals_settings() {
        // Fetch the deal settings from the database
        $deal_settings = $this->marketplaceDb->get('tblleadevo_deals_settings')->row();
    
        // Check if the data was retrieved successfully
        if ($deal_settings) {
            // Return the deal settings as JSON
            echo json_encode([
                'status' => 'success',
                'data' => $deal_settings
            ]);
        } else {
            // Return an error message if no data was found
            

            $this->marketplaceDb->insert('tblleadevo_deals_settings', 
            ['nonexclusive_status'=>1, 
            'max_sell_times'=>1,
            'days_to_discount'=>-1,
            'discount_type'=>1,
            'discount_amount'=>10,
            'tenant_id'=> get_marketplace_id()]);

            echo json_encode([
                'status' => 'error',
                'data' => ['nonexclusive_status'=>1, 
                                'max_sell_times'=>1,
                                'days_to_discount'=>-1,
                                'discount_type'=>1,
                                'discount_amount'=>10]
            ]);
        }
    }
    
    

}
