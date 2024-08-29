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
        $this->db->select('a.id, a.name, a.email, a.phone, a.is_active,a.status, a.is_exclusive, c.name as prospect_category');
        $this->db->from('tblleadevo_prospect_alerts a');
        $this->db->join('tblleadevo_prospect_categories c', 'a.prospect_category_id = c.id', 'left');
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
}
