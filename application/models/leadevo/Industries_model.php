<?php defined('BASEPATH') or exit('No direct script access allowed');

class Industries_model extends CI_Model
{
    private $table = 'tblleadevo_industries';

    public function __construct()
    {
        parent::__construct();
    }

    // Get all industries
    public function get_all($filter=0)
    {
        
        if(isset($filter['is_active']) && $filter["is_active"] != ""){
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->table)->result_array();
    }

    // Get a single industry by ID
    public function get($id)
    {
        // $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    // Insert a new industry
    public function insert($data)
    {
        // echo "<pre>";
        // print_r($data);exit;
        return $this->db->insert($this->table, $data);
    }

    // Update an industry
    public function update($id, $data)
    {
        // $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    // Delete an industry
    public function delete($id)
    {
        // $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->delete($this->table);
    }
}
