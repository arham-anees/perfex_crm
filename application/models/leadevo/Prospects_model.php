<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects_model extends CI_Model
{

    private $table = 'tblleadevo_prospects';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //Get all prospects by filter
    public function get_all_by_filter($filter)
    {
        return $this->db->get($this->table)->result();
    }


    public function get_all()
    {
        $sql = "SELECT 
                    p.id, 
                    CONCAT(p.first_name, ' ', p.last_name) AS prospect_name, 
                    ps.name AS status, 
                    pt.name AS type, 
                    pc.name AS category, 
                    ac.name AS acquisition_channel, 
                    i.name AS industry,
                    null AS zip_code,
                    null AS phone,
                    null AS email,
                    null AS source,
                    null AS deal,
                    null AS quality
                FROM
                    tblleadevo_prospects p
                LEFT JOIN
                    tblleadevo_prospect_statuses ps ON p.status_id = ps.id
                LEFT JOIN
                    tblleadevo_prospect_types pt ON p.type_id = pt.id   
                LEFT JOIN
                    tblleadevo_prospect_categories pc ON p.category_id = pc.id
                LEFT JOIN
                    tblleadevo_acquisition_channels ac ON p.acquisition_channel_id = ac.id
                LEFT JOIN
                    tblleadevo_industries i ON p.industry_id = i.id
                WHERE
                    p.is_active = 1";

        return $this->db->query($sql)->result_array();
    }


    // // Get a single prospect by ID
    // public function get($id) {
    //     $sql = "SELECT 
    //                 p.id, 
    //                 CONCAT(p.first_name, ' ', p.last_name) AS prospect_name, 
    //                 ps.name AS status, 
    //                 pt.name AS type, 
    //                 pc.name AS category, 
    //                 ac.name AS acquisition_channel, 
    //                 i.name AS industry
    //             FROM
    //                 tblleadevo_prospects p
    //             LEFT JOIN
    //                 tblleadevo_prospect_statuses ps ON p.status_id = ps.id
    //             LEFT JOIN
    //                 tblleadevo_prospect_types pt ON p.type_id = pt.id   
    //             LEFT JOIN
    //                 tblleadevo_prospect_categories pc ON p.category_id = pc.id
    //             LEFT JOIN
    //                 tblleadevo_acquisition_channels ac ON p.acquisition_channel_id = ac.id
    //             LEFT JOIN
    //                 tblleadevo_industries i ON p.industry_id = i.id
    //             WHERE
    //                 p.id = ? AND p.is_active = 1";

    //     // Execute the query with the provided ID
    //     return $this->db->query($sql, [$id])->row_array();
    // }

    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }


    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Update an prospect
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
