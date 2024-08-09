<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospect_sources_model extends CI_Model
{
    private $table = 'tblleadevo_prospects_sources';

    public function __construct()
    {
        parent::__construct();
    }

    //get all prospect sources
    public function get_prospect_sources($search = '')
    {
        $this->db->select('id, name, description');
        $this->db->from($this->table);

        // Fetch prospect sources based on the search parameter
        if ($search) {
            $this->db->like('name', $search); // Assuming you want to search by 'name'
        }

        return $this->db->get()->result_array();



    }

    // Get prospect source by ID
    public function get_prospect_source($id)
    {
        $this->db->select('id, name, description');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    //insert prospect source
    public function insert_prospect_source($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    //update prospect source
    public function update_prospect_source($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    //delete prospect source
    public function delete_prospect_source($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }













    public function get_prospect_sources_effectiveness()
    {
        // Query to fetch prospect source effectiveness data
        $this->db->select("
            ps.name AS source_name,
            AVG(TIMESTAMPDIFF(DAY, p.created_at, p.confirmed_at)) AS avg_confirmation_time,
            CASE 
                WHEN COUNT(p.id) > 0 THEN 
                    SUM(CASE WHEN p.confirmed_at IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(p.id) 
                ELSE 0 
            END AS confirmation_rate,
            COUNT(p.id) AS total_prospects,
            COALESCE(n.total_notes, 0) AS total_notes,
            COALESCE(r.avg_rating, 0) AS avg_rating,
            COALESCE(a.total_alerts, 0) AS total_alerts,
            AVG(p.desired_amount) AS avg_desired_amount,
            AVG(p.min_amount) AS avg_min_amount,
            SUM(CASE WHEN p.is_active = 1 THEN 1 ELSE 0 END) AS active_prospects,
            SUM(CASE WHEN p.is_active = 0 THEN 1 ELSE 0 END) AS inactive_prospects
        ");

        $this->db->from('tblleadevo_prospects p');
        $this->db->join('tblleadevo_prospects_sources ps', 'ps.id = p.source_id', 'left');

        // Subquery for notes
        $this->db->join("
            (SELECT 
                p.source_id AS source_id, 
                COUNT(n.id) AS total_notes
            FROM tblleadevo_prospects_notes n
            INNER JOIN tblleadevo_prospects p ON p.id = n.prospect_id
            GROUP BY p.source_id
            ) n", 'n.source_id = p.source_id', 'left');

        // Subquery for ratings
        $this->db->join("
            (SELECT 
                p.source_id AS source_id,
                AVG(r.rating) AS avg_rating
            FROM tblleadevo_prospects_rating r
            INNER JOIN tblleadevo_prospects p ON p.id = r.prospect_id
            GROUP BY p.source_id
            ) r", 'r.source_id = p.source_id', 'left');

        // Subquery for alerts
        $this->db->join("
            (SELECT 
                p.source_id AS source_id,
                COUNT(a.id) AS total_alerts
            FROM tblleadevo_prospect_alerts a
            INNER JOIN tblleadevo_prospects p ON p.category_id = a.prospect_category_id
            GROUP BY p.source_id
            ) a", 'a.source_id = p.source_id', 'left');

        $this->db->group_by('ps.name');
        $this->db->order_by('ps.name');

        $query = $this->db->get();
        return $query->result_array();
    }

}
