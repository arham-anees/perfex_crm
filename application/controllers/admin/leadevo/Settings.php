<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        if ($this->input->post()) {
            $sql = '';
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings_0stars') . "' WHERE name LIKE 'delivery_settings_0stars';";
            $this->db->query($sql);
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings_1stars') . "' WHERE name LIKE 'delivery_settings_1stars';";
            $this->db->query($sql);
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings_2stars') . "' WHERE name LIKE 'delivery_settings_2stars';";
            $this->db->query($sql);
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings_3stars') . "' WHERE name LIKE 'delivery_settings_3stars';";
            $this->db->query($sql);
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings_4stars') . "' WHERE name LIKE 'delivery_settings_4stars';";
            $this->db->query($sql);
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings_5stars') . "' WHERE name LIKE 'delivery_settings_5stars';";
            $this->db->query($sql);
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings_5stars') . "' WHERE name LIKE 'delivery_settings_5stars';";
            $this->db->query($sql);
            $sql = " UPDATE " . db_prefix() . "options SET value = '" . $this->input->post('delivery_settings') . "' WHERE name LIKE 'delivery_settings';";
            $this->db->query($sql);

        }
        $this->load->view('admin/setup/delivery_quality');
    }


    public function deals()
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            // Extract form data
            $settings_status = $data['settings_status'];
            $max_sell_times = $data['max_sell_times'];
            $days_to_discount = $data['days_to_discount'];
            $discount_type = $data['discount_type'];
            $discount_amount = $data['discount_amount']; // Assuming you have this field in the form
            if ($max_sell_times < 0 || $days_to_discount < 0 || $discount_amount < 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input: Values cannot be negative']);
            return;
            exit;
        }
            update_option('leadevo_deal_settings_status', $settings_status);
            update_option('leadevo_deal_max_sell_times', $max_sell_times);
            update_option('leadevo_deal_days_to_discount', $days_to_discount);
            update_option('leadevo_deal_discount_type', $discount_type);
            update_option('leadevo_deal_discount_amount', $discount_amount);

            echo json_encode(['status' => 'success', 'message' => 'Settings saved']);
            return;
        }
        echo json_encode(['status' => 'error', 'message' => 'Failed to save settings']);
    }
    public function get_deals_settings()
    {
        // Fetch the deal settings from the database
        $deal_settings = [
            'settings_status' => get_option('leadevo_deal_settings_status'),
            'max_sell_times' => get_option('leadevo_deal_max_sell_times'),
            'days_to_discount' => get_option('leadevo_deal_days_to_discount'),
            'discount_type' => get_option('leadevo_deal_discount_type'),
            'discount_amount' => get_option('leadevo_deal_discount_amount'),
        ];

        // Check if the data was retrieved successfully
        if ($deal_settings) {
            // Return the deal settings as JSON
            echo json_encode([
                'status' => 'success',
                'data' => $deal_settings
            ]);
        } else {
            // Return an error message if no data was found


            update_option('leadevo_deal_settings_status', 1);
            update_option('leadevo_deal_max_sell_times', 1);
            update_option('leadevo_deal_days_to_discount', -1);
            update_option('leadevo_deal_discount_type', 1);
            update_option('leadevo_deal_discount_amount', 10);
            echo json_encode([
                'status' => 'error',
                'data' => [
                    'settings_status' => get_option('leadevo_deal_settings_status'),
                    'max_sell_times' => get_option('leadevo_deal_max_sell_times'),
                    'days_to_discount' => get_option('leadevo_deal_days_to_discount'),
                    'discount_type' => get_option('leadevo_deal_discount_type'),
                    'discount_amount' => get_option('leadevo_deal_discount_amount')
                ]
            ]);
        }
    }



}
