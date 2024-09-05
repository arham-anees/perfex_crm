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
        $this->db->select('tblleadevo_reported_prospects.*, tblleadevo_report_lead_reasons.name as reason_name,tblleadevo_reject_prospect_status.status as status_name');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->join('tblleadevo_report_lead_reasons', 'tblleadevo_reported_prospects.reason = tblleadevo_report_lead_reasons.id', 'left');
        $this->db->join('tblleadevo_reject_prospect_status', 'tblleadevo_reported_prospects.status = tblleadevo_reject_prospect_status.id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_client()
    {
        $this->db->select('tblleadevo_reported_prospects.*, tblleadevo_report_lead_reasons.name as reason_name');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->where('tblleadevo_reported_prospects.client_id', get_client_user_id());
        $this->db->join('tblleadevo_report_lead_reasons', 'tblleadevo_reported_prospects.reason = tblleadevo_report_lead_reasons.id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get($id)
    {
        $this->db->select('tblleadevo_reported_prospects.*, tblleadevo_report_lead_reasons.name as reason_name');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->join('tblleadevo_report_lead_reasons', 'tblleadevo_reported_prospects.reason = tblleadevo_report_lead_reasons.id', 'left');
        $this->db->where('tblleadevo_reported_prospects.prospect_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_all_by_filter($search=array())
    {
        // Ensure valid filter is passed
        // echo "<pre>";
        // print_r($filter);exit;
        // $valid_filters = ['rejected', 'pending', 'replaced'];  // Adjust these filters based on your requirements
        // if (!in_array(strtolower($filter), $valid_filters)) {
        //     return []; // Return empty array if filter is not valid
        // }

        $this->db->select('tblleadevo_reported_prospects.*, tblleadevo_report_lead_reasons.name as reason_name, tblleadevo_reject_prospect_status.status as status_name');
        $this->db->from('tblleadevo_reported_prospects');
        $this->db->join('tblleadevo_report_lead_reasons', 'tblleadevo_reported_prospects.reason = tblleadevo_report_lead_reasons.id', 'left');
        $this->db->join('tblleadevo_reject_prospect_status', 'tblleadevo_reported_prospects.status = tblleadevo_reject_prospect_status.id', 'left');

        // Apply the status filter with case-insensitivity
        // $this->db->where('LOWER(tblleadevo_reject_prospect_status.status)', strtolower($filter));
        if (!empty($search['start_date'])) {
            $this->db->where('tblleadevo_reported_prospects.created_at >=', $search['start_date']);
        }
        if (!empty($search['end_date'])) {
            $this->db->where('tblleadevo_reported_prospects.created_at <=', $search['end_date']);
        }
        if (isset($search['status']) && $search['status']!='') {

            $this->db->where('tblleadevo_reported_prospects.status', $search['status']);
        }
        $query = $this->db->get();
            //  echo "<pre>";
            // print_r($query);
            // exit;
        return $query->result_array();
    }

    public function get_status_options()
    {
        $this->db->select('id, status');
        $this->db->from('tblleadevo_reject_prospect_status');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_status_name_by_id($status_id)
    {
        $this->db->select('status');
        $this->db->from('tblleadevo_reject_prospect_status');
        $this->db->where('id', $status_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->status;
        } else {
            return 'Unknown'; // Return 'Unknown' if status ID does not exist
        }
    }
}
