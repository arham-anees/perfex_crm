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
        $this->db->select('tblleadevo_reported_prospects.*, tblleadevo_report_lead_reasons.name as reason_name');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->join('tblleadevo_report_lead_reasons', 'tblleadevo_reported_prospects.reason = tblleadevo_report_lead_reasons.id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get($id)
    {
        $this->db->select('tblleadevo_reported_prospects.*, tblleadevo_report_lead_reasons.name as reason_name');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->join('tblleadevo_report_lead_reasons', 'tblleadevo_reported_prospects.reason = tblleadevo_report_lead_reasons.id', 'left');
        $this->db->where('tblleadevo_reported_prospects.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function get_all_by_filter($filter)
    {
        $this->db->select('tblleadevo_reported_prospects.*, tblleadevo_report_lead_reasons.name as reason_name');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->join('tblleadevo_report_lead_reasons', 'tblleadevo_reported_prospects.reason = tblleadevo_report_lead_reasons.id', 'left');
        // Apply your filter logic here if needed
        $this->db->like('tblleadevo_report_lead_reasons.name', $filter); // Example filter
        $query = $this->db->get();
        return $query->result_array();
    }


}
