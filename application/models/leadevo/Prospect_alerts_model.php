<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_alerts_model extends CI_Model
{
    private $table = 'tblleadevo_prospect_alerts';

    public function __construct()
    {
        parent::__construct();
    }

    // Get all prospect alerts
    // Prospect_alerts_model.php
   public function get_all($search = array())
{
    $this->db->select('a.id, a.name, a.email, a.phone, a.is_active, a.status, a.is_exclusive, i.name as prospect_industry, ac.name as acquisition_channel, c.name as prospect_category');
    $this->db->from('tblleadevo_prospect_alerts a');
    $this->db->join('tblleadevo_industries i', 'a.industry_id = i.id', 'left');
    $this->db->join('tblleadevo_prospect_categories c', 'a.prospect_category_id = c.id', 'left');
    $this->db->join('tblleadevo_acquisition_channels ac', 'a.acquisition_channel_id = ac.id', 'left');

    // Apply filters if they are set
    if (!empty($search)) {
        if (isset($search['industry_name'])) {
            $this->db->where('i.name', $search['industry_name']);
        }
        if (!empty($search['acquisition_channel_id'])) {
            $this->db->where('a.acquisition_channel_id', $search['acquisition_channel_id']);
        }
        if (isset($search['status']) && $search['status']!="") {
            $this->db->where('a.status', $search['status']);
        }
        if (isset($search['deal']) && $search['deal']!="" ) {
            $this->db->where('a.is_exclusive', $search['deal']); // Assuming there is a 'deal' column in the table
        }
        if (!empty($search['name'])) {
            $this->db->like('a.name', $search['name']);
        }
        if (!empty($search['email'])) {
            $this->db->like('a.email', $search['email']);
        }
        if (!empty($search['phone_no'])) {
            $this->db->like('a.phone', $search['phone_no']);
        }
    }

    $query = $this->db->get();
    return $query->result_array();
}


    public function get_all_client()
{
    $this->db->select('a.*, i.name as industry, ac.name as acquisition_channel');
    $this->db->from('tblleadevo_prospect_alerts a');
    $this->db->where('a.client_id', get_client_user_id());
    $this->db->join('tblleadevo_industries i', 'a.industry_id = i.id', 'left');
    $this->db->join('tblleadevo_industries i', 'a.industry_id = i.id', 'left');
    $this->db->join('tblleadevo_acquisition_channels ac', 'a.acquisition_channel_id = ac.id', 'left');
    $query = $this->db->get();
    return $query->result_array();
}
// Prospect_alerts_model.php

public function get_all_industries()
{
    $this->db->select('id, name');
    $this->db->from('tblleadevo_industries');
    $this->db->where('is_active', 1); // Assuming you want only active industries
    $query = $this->db->get();
    return $query->result();
}
public function get_all_categories() {
    $this->db->select('id, name');
    $query = $this->db->get('tblleadevo_prospect_categories');
    return $query->result();
}

// Prospect_alerts_model.php

public function get_all_acquisition_channels()
{
    $this->db->select('id, name');
    $this->db->from('tblleadevo_acquisition_channels'); // Assuming the table name
    $this->db->where('is_active', 1); // Assuming you want only active channels
    $query = $this->db->get();
    return $query->result();
}



    // Get a single prospect alert by ID
    // Prospect_alerts_model.php
    public function get($id)
    {
        $this->db->select('a.id, a.name, a.email, a.phone, a.is_active,a.status, a.is_exclusive, c.name as prospect_category');
        $this->db->from('tblleadevo_prospect_alerts a');
        $this->db->join('tblleadevo_prospect_categories c', 'a.prospect_category_id = c.id', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }


    // Insert a new prospect alert
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Update an existing prospect alert
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    public function activate($id)
    {
        return $this->db->where('id', $id)->update($this->table, ['status' => 1]);
    }
    public function deactivate($id)
    {
        return $this->db->where('id', $id)->update($this->table, ['status' => 0]);
    }

    // Delete a prospect alert
    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    // Get filtered prospect alerts
    public function get_filtered($filter = null)
    {
        if ($filter == 'active') {
            $this->db->where('is_active', 1);
        } elseif ($filter == 'inactive') {
            $this->db->where('is_active', 0);
        }

        return $this->db->get($this->table)->result_array();
    }

    public function send_alerts()
    {
        $sql = "SELECT * FROM tblleadevo_prospect_alerts
                WHERE is_active = 1 AND status = 1 AND id NOT IN (
                SELECT alert_id FROM tblleadevo_prospect_alert_logs WHERE DATE(last_alert_sent) = CURDATE())";

        $alerts = $this->db->query($sql)->result_array();

        //foreach alert
        foreach ($alerts as $alert) {
            $sql = "SELECT * FROM tblleadevo_prospects 
                    WHERE is_active = 1 
                        AND is_available_sale = 1 
                        AND is_fake = 0 
                        AND client_id <> " . ($alert['client_id'] ?? 0) . "
                        AND is_exclusive = " . $alert['is_exclusive'] .  " ";  
                       
            $prospects = $this->db->query($sql)->result_array();
            if (count($prospects) == 0)
                continue;
            $html = "<table><thead><tr><th>Name</th><th>Actions</th></tr></thead><tbody>";
            // generate html table
            foreach ($prospects as $prospect) {
                $html .= "<tr><td>" . $prospect["first_name"] . " " . $prospect["last_name"] . "</td><td><a href=\"" . site_url("prospect/" . $prospect["id"]) . "\">View</a></td></tr>";
            }
            $html .= "</tbody></table>";

            $template = mail_template('Leadevo_prospect_alert', array_to_object(['email' => $alert['email'], 'name' => $html]));
            $template->send();
        }
    }
}
