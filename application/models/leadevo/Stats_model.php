<?php defined('BASEPATH') or exit('No direct script access allowed');

class Stats_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function client_dashboard()
    {
        $sql = "SELECT 10 prospect_amount, 2 reported_today, 20 delivered_today, 22 delivered_yesterday, 100.5 prospect_avg_price 
            FROM tblleadevo_prospects";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function client_campaigns()
    {
        $sql = "SELECT 10 open_today,0 open_yesterday,  
                2 closed_today, 5 closed_yesterday, 
                20 to_deliver_today, 
                22 exclusive_delivered_yesterday,21 exclusive_delivered_today,
                26 non_exclusive_delivered_yesterday,29 non_exclusive_delivered_today, 
                100.5 avg_price_exclusive, 109.3 avg_price_non_exclusive 
                FROM tblleadevo_prospects";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function admin_marketplace()
    {
        $sql = "SELECT 10 exclusive_for_sale_today, 20 exclusive_for_sale_yesterday,
                12 non_exclusive_for_sale_today, 27 non_exclusive_for_sale_yesterday,
                15 exclusive_sold_today, 33 exclusive_sold_yesterday,
                42 non_exclusive_sold_today, 45 non_exclusive_sold_yesterday,
                3 exclusive_avg_time, 27 non_exclusive_avg_time,
                143 exclusive_avg_price, 100.3 non_exclusive_avg_price,
                2 reported_today, 3 reported_yesterday
                FROM tblleadevo_prospects
                ";
    }
    public function admin_campaigns()
    {
        $sql = "SELECT 10 open_today,0 open_yesterday,  
                2 closed_today, 5 closed_yesterday, 
                20 to_deliver_today, 
                22 exclusive_delivered_yesterday,21 exclusive_delivered_today,
                26 non_exclusive_delivered_yesterday,29 non_exclusive_delivered_today, 
                100.5 avg_price_exclusive, 109.3 avg_price_non_exclusive 
                FROM tblleadevo_prospects";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function admin_industry_monitoring()
    {
        $sql = "SELECT 'test' industry, 10 received, 23 exclusive_sold, 34 non_exclusive_sold from tblleadevo_prospects";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function admin_prospect_verification()
    {
        $sql = "SELECT
                    -- Amount of prospects to be verified (not yet verified)
                    COUNT(CASE WHEN verified_sms = 0 THEN 1 END) AS to_be_verified_by_sms,
                    COUNT(CASE WHEN verified_whatsapp = 0 THEN 1 END) AS to_be_verified_by_whatsapp,
                    COUNT(CASE WHEN verified_staff = 0 THEN 1 END) AS to_be_verified_by_staff,
                    
                    -- Amount of prospects already verified
                    COUNT(CASE WHEN verified_sms = 1 THEN 1 END) AS verified_by_sms,
                    COUNT(CASE WHEN verified_whatsapp = 1 THEN 1 END) AS verified_by_whatsapp,
                    COUNT(CASE WHEN verified_staff = 1 THEN 1 END) AS verified_by_staff
                FROM 
                    tblleadevo_prospects;";
        $query = $this->db->query($sql);
        return $query->result();
    }
}