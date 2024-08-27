<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industry_categories_model extends CI_Model
{
    private $table = 'tblleadevo_industry_categories';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        // Join with the tblleadevo_industries table
        $this->db->select('tblleadevo_industry_categories.*, tblleadevo_industries.name as industry_name');
        $this->db->from($this->table);
        $this->db->join('tblleadevo_industries', 'tblleadevo_industry_categories.industry_id = tblleadevo_industries.id');
        $this->db->where('tblleadevo_industry_categories.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get($id)
    {
        // Join with the tblleadevo_industries table
        $this->db->select('tblleadevo_industry_categories.*, tblleadevo_industries.name as industry_name');
        $this->db->from($this->table);
        $this->db->join('tblleadevo_industries', 'tblleadevo_industry_categories.industry_id = tblleadevo_industries.id');
        $this->db->where('tblleadevo_industry_categories.id', $id);
        $this->db->where('tblleadevo_industry_categories.is_active', 1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('is_active', 1);
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('is_active', 1);
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
