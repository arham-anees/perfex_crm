<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reported_Prospects_model extends CI_Model
{

    

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->select('*');
        $this->db->from('tblleadevo_reported_prospects');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_by_filter($filter)
    {
        $this->db->select('*');
        $this->db->from('tblleadevo_reported_prospects');
        // Apply your filter logic here if needed
        $this->db->like('reason', $filter); // Example filter
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get($id)
    {
        $this->db->select('*');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
}
