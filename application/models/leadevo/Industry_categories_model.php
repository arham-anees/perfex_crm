<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industry_categories_model extends CI_Model
{
    private $table = 'tblleadevo_industry_categories';

    public function __construct()
    {
        parent::__construct();
    }

    // Fetch all active categories with industry names
    public function get_all()
    {
        $this->db->where('is_active', 1);
        return $this->db->get($this->table)->result_array();
    }

    // Fetch a single category by ID
    public function get($id)
    {
        $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->get($this->table)->row();
    }
    // Insert a new category
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Update an existing category
    public function update($id, $data)
    {
        $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    // 
    // Delete a category (soft delete)
    public function delete($id)
    {
        $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->delete($this->table);
    }
}
