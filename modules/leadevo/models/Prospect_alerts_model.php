<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_alerts_model extends CI_Model
{
    private $table = 'tblleadevo_prospect_alerts';

    public function __construct()
    {
        parent::__construct();
    }

    //Get prospect alerts by filter
    public function get_filtered_prospect_alerts($search='', $filter= 'all')
    {
        $this->db->select('pa.id, pa.name AS alert_name, pc.name AS prospect_category, pa.email, pa.phone, pa.is_active AS status');
        $this->db->from('tblleadevo_prospect_alerts pa');
        $this->db->join('tblleadevo_prospect_categories pc', 'pa.prospect_category_id = pc.id', 'left');


        if($search){
            $this->db->like('pa.name', $search); // Assuming you want to search by 'name'
        }


        if($filter=='active'){
            $this->db->where('pa.is_active', 1);
        } elseif($filter=='inactive'){
            $this->db->where('pa.is_active', 0);
        }

        return $this->db->get()->result_array();
    }

    
    // Get a single prospect by ID
    public function get($id)
    {
        $sql = "SELECT 
                pa.id, 
                pa.name AS alert_name, 
                pa.prospect_category_id, 
                pc.name AS prospect_category, 
                pa.email,
                pa.phone,
                pa.is_active AS status
            FROM
                tblleadevo_prospect_alerts pa
            LEFT JOIN
                tblleadevo_prospect_categories pc ON pa.prospect_category_id = pc.id
            WHERE
                pa.id = ?";
        return $this->db->query($sql, [$id])->row_array();  
    }

    // Get all prospect categories
    public function get_prospect_categories()
        {
            $this->db->select('id, name');
            $this->db->from('tblleadevo_prospect_categories');
            $query = $this->db->get();
            return $query->result_array();
        }
        

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);

    }
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

  

}
