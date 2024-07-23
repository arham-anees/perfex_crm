<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects_model extends CI_Model
{
    private $table = 'tblleadevo_prospects';

    public function __construct()
    {
        parent::__construct();
    }

    // Get all prospects
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    // Get a single prospect by ID
    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    // Insert a new prospects
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Update an prospect
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    // Delete an prospect
    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
