<?php defined('BASEPATH') or exit('No direct script access allowed');

class Campaigns_model extends CI_Model
{

    protected $table = 'leadevo_campaign'; // Define the table name
    protected $country_table = 'tblcountries'; // Define the table name


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }
    public function get_all_client()
    {
        $sql = "SELECT c.*, s.name status_name FROM `tblleadevo_campaign` c
                LEFT JOIN tblleadevo_campaign_statuses s
                ON c.status_id = s.id
                WHERE c.is_active = 1 AND client_id = " . get_client_user_id() . "";
        return $this->db->query($sql)->result();
    }

    public function get_active()
    {
        $sql = "SELECT c.id, start_date, end_date, status_id, budget, industry_id, country_id, deal, verify_by_staff, verify_by_sms, verify_by_whatsapp, verify_by_coherence, timings, c.client_id, IFNULL(SUM(ll.price), 0) AS budget_spent  FROM tblleadevo_campaign c 
                LEFT JOIN tblleadevo_leads ll
                ON ll.campaign_id  = c.id
                WHERE is_active = 1 
                        AND status_id = 1 
                        AND UTC_TIMESTAMP() BETWEEN start_date AND end_date
                GROUP BY c.id, start_date, end_date, status_id, budget, industry_id, country_id, deal, verify_by_staff, verify_by_sms, verify_by_whatsapp, verify_by_coherence, timings, c.client_id";
        return $this->db->query($sql)->result();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }
    public function get_by_client_id($id)
    {
        return $this->db->where('client_id', $id)->get($this->table)->result();
    }

    public function insert($data)
    {
        log_message('error', 'insertion');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
    public function get_campaign_statuses()
    {
        return $this->db->get('tblleadevo_campaign_statuses')->result_array();
    }

    public function get_all_countries()
    {
        return $this->db->get($this->country_table)->result_array();
    }

    public function get_matching($prospect_id)
    {
        $prospect = $this->db->query("SELECT * FROM tblleadevo_prospects WHERE id = " . $prospect_id)->row();
        $sql = "SELECT 
                    cam.id,
                    c.company client_name, 
                    MAX(ll.created_at) AS last_delivered, 
                    COUNT(ll.campaign_id) AS progress 
                FROM 
                    tblleadevo_campaign cam
                LEFT JOIN 
                    tblclients c ON c.userid = cam.client_id
                LEFT JOIN 
                    tblleadevo_leads ll ON ll.campaign_id = cam.id
                WHERE is_active = 1 AND status_id = 1 AND `start_date` < NOW() AND `end_date` > NOW() 
                AND deal = " . $prospect->is_exclusive . " AND verify_by_sms = " . $prospect->verified_sms . " AND
                verify_by_whatsapp = " . $prospect->verified_whatsapp . " AND verify_by_staff = " . $prospect->verified_staff
            . " AND industry_id = " . $prospect->industry_id . "
            GROUP BY c.company,cam.id";
        return $this->db->query($sql)->result_array();
    }
}
