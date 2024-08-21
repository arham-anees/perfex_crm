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
        $sql = "SELECT 
                    COUNT(CASE WHEN DATE(l.created_at) = CURDATE() THEN 1 END) AS prospect_amount,
                    COUNT(CASE WHEN DATE(r.created_at) = CURDATE() THEN 1 END) reported_today,
                    COUNT(CASE WHEN DATE(l.created_at) = CURDATE() THEN 1 END) AS delivered_today,
                    COUNT(CASE WHEN DATE(l.created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS delivered_yesterday,
                    AVG(CASE WHEN DATE(l.created_at) = CURDATE() THEN l.price END) AS prospect_avg_price
                FROM tblclients c
                INNER JOIN tblleadevo_leads l ON l.client_id = c.userid
                INNER JOIN tblleadevo_reported_prospects r ON r.client_id = c.userid
                WHERE c.client_id = " . get_client_user_id();
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function client_campaigns()
    {
        $sql = "SELECT 
                    COUNT(CASE WHEN DATE(start_date) = CURDATE() THEN 1 END) AS open_today,
                    COUNT(CASE WHEN DATE(start_date) = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS open_yesterday,
                    COUNT(CASE WHEN DATE(end_date) = CURDATE() AND campaigns.status_id = 1 THEN 1 END) AS closed_today,
                    COUNT(CASE WHEN DATE(end_date) = CURDATE() - INTERVAL 1 DAY AND campaigns.status_id = 1 THEN 1 END) AS closed_yesterday,
                    COUNT(CASE WHEN DATE(end_date) = CURDATE() THEN 1 END) AS to_deliver_today,
                    COUNT(CASE WHEN DATE(p.created_at) = CURDATE() - INTERVAL 1 DAY AND p.is_exclusive = 1 THEN 1 END) AS exclusive_delivered_yesterday,
                    COUNT(CASE WHEN DATE(p.created_at) = CURDATE()  AND p.is_exclusive = 1 THEN 1 END) AS exclusive_delivered_today,
                    COUNT(CASE WHEN DATE(p.created_at) = CURDATE() - INTERVAL 1 DAY AND p.is_exclusive = 0 THEN 1 END) AS non_exclusive_delivered_yesterday,
                    COUNT(CASE WHEN DATE(p.created_at) = CURDATE() AND p.is_exclusive = 0 THEN 1 END) AS non_exclusive_delivered_today,
                    AVG(CASE WHEN p.is_exclusive = 1 THEN l.price ELSE NULL END) AS avg_price_exclusive,
                    AVG(CASE WHEN p.is_exclusive = 0 THEN l.price ELSE NULL END) AS avg_price_non_exclusive
                FROM tblclients c
                INNER JOIN tblleadevo_leads l ON l.client_id = c.userid
                INNER JOIN tblleadevo_prospects p ON p.id = l.prospect_id
                INNER JOIN tblleadevo_campaign campaigns ON campaigns.client_id = c.userid
                WHERE c.client_id = " . get_client_user_id();
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