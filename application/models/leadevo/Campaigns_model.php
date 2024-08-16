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

    public function get_by_invoice($id)
    {
        return $this->db->where('invoice_id', $id)->get($this->table)->row();
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
                    (SELECT * FROM tblleadevo_leads WHERE prospect_id <> " . $prospect_id . ") AS ll ON ll.campaign_id = cam.id
                WHERE is_active = 1 AND status_id = 1 AND `start_date` < NOW() AND `end_date` > NOW() 
                AND deal = " . $prospect->is_exclusive . " AND verify_by_sms = " . $prospect->verified_sms . " AND
                verify_by_whatsapp = " . $prospect->verified_whatsapp . " AND verify_by_staff = " . $prospect->verified_staff
            . " AND industry_id = " . $prospect->industry_id . " 
            GROUP BY c.company,cam.id";
        return $this->db->query($sql)->result_array();
    }

    public function send_prospect($prospect_id, $campaign_id)
    {
        $this->db->trans_begin();
        $campaign = $this->db->query("SELECT * FROM tblleadevo_campaign WHERE id =" . $campaign_id)->row();
        $prospect = $this->db->query("SELECT * FROM tblleadevo_prospects WHERE id = " . $prospect_id)->row();
        $budget_spent = $this->db->query("SELECT IFNULL(SUM(price), 0) AS budget_spent  FROM tblleadevo_leads WHERE campaign_id = " . $campaign->id)->row()->budget_spent;
        if ($budget_spent >= $campaign->budget) {
            // TODO: mark the campaign as completed
            throw new Exception("Client's budget can been used. The Campaign is Completed");
        }
        $budget = (float) $prospect->desired_amount;
        $desired_amount = (float) $prospect->desired_amount;
        $min_amount = (float) $prospect->min_amount;
        $campaign_budget = (float) $campaign->budget;
        if (($budget_spent + $desired_amount) >= $campaign_budget && ($budget_spent + $min_amount) <= $campaign_budget)
            $budget = $min_amount;

        // create invoice for each
        $sql = "INSERT INTO " . db_prefix() . "leads(name,email, phonenumber, status, source, hash, dateadded, addedfrom) VALUES('" . $prospect->first_name . " " . $prospect->last_name . "','" . $prospect->email
            . "','" . $prospect->phone . "',2,2,'" . app_generate_hash() . "', '" . date('Y-m-d H:i:s') . "',0);";
        $this->db->query($sql);

        // Get the last inserted ID from tblleads
        $lastInsertId = $this->db->insert_id();
        $sql = "INSERT INTO " . db_prefix() . "leadevo_leads(lead_id, prospect_id, client_id,campaign_id, created_at, price) VALUES(" . $lastInsertId . "," . $prospect->id . "," . $campaign->client_id . "," . $campaign->id . ", '" . date('Y-m-d H:i:s') . "', '" . $budget . "');";
        $this->db->query($sql);

        if ($campaign->deal == 1) {
            $this->db->query("UPDATE tblleadevo_prospects SET is_active=0, updated_at = UTC_TIMESTAMP() WHERE id = " . $prospect->id);
        }
        // TODO: clear from carts

        // If everything is successful, commit the transaction
        if ($this->db->trans_status() === FALSE) {
            // If something went wrong, roll back the transaction
            $this->db->trans_rollback();
            echo "Transaction failed. Rolling back.";
        } else {
            // Commit the transaction
            $this->db->trans_commit();
        }

    }
}
