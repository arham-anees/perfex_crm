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
public function get_all()
{
    $this->db->select('tblleadevo_prospect_alerts.id, tblleadevo_prospect_alerts.name, tblleadevo_prospect_alerts.email, tblleadevo_prospect_alerts.phone, tblleadevo_prospect_alerts.is_active, tblleadevo_prospect_categories.name as prospect_category');
    $this->db->from('tblleadevo_prospect_alerts');
    $this->db->join('tblleadevo_prospect_categories', 'tblleadevo_prospect_alerts.prospect_category_id = tblleadevo_prospect_categories.id', 'left');
    $query = $this->db->get();
    return $query->result_array();
}


    // Get a single prospect alert by ID
    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
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
}
