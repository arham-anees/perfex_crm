<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industries_model extends CI_Model
{
    private $table = 'tblleadevo_industries';

    public function __construct()
    {
        parent::__construct();
    }

    // Get all industries
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    // Get a single industry by ID
    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    // Insert a new industry
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Update an industry
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    // Delete an industry
    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
