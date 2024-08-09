<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects_model extends CI_Model
{

    private $table = 'leadevo_prospects';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //Get all prospects by filter
    public function get_all_by_filter($filter)
    {
        $sql = "SELECT 
            p.id, 
            CONCAT(p.first_name, ' ', p.last_name) AS prospect_name, 
            ps.name AS status, 
            pt.name AS type, 
            pc.name AS category, 
            ac.name AS acquisition_channel, 
            i.name AS industry,
            p.is_fake,
            p.is_available_sale,
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
            p.is_active = 1 
            AND is_fake = 0
            AND is_available_sale = 1 ";
        if (isset($filter["industry_id"]) && $filter["industry_id"] != "") {
            $sql .= " AND industry_id = " . $filter["industry_id"];
        }
        if (isset($filter["acquisition_id"]) && $filter["acquisition_id"] != "") {
            $sql .= " AND acquisition_id =" . $filter["acquisition_id"];
        }
        if (isset($filter["zip_codes"]) && $filter["zip_codes"] != "" && count($filter["zip_codes"]) > 0) {
            $sql .= " AND zip_code in (" . implode(",", $filter["zip_codes"]) . ")";
        }
        if (isset($filter["generated_from"]) && $filter["generated_from"] != "") {
            $sql .= " AND DATE(created_at) <= DATE('" . $filter["generated_from"] . "')";
        }

        if (isset($filter["generated_to"]) && $filter["generated_to"] != "") {
            $sql .= " AND DATE(created_at) >= DATE('" . $filter["generated_to"] . "')";
        }
        if (isset($filter["deal"]) && $filter["deal"] != "") {

            $deal = $filter["deal"];
            if ($deal == 0)
                $sql .= " AND nonexclusive_status = 0";
            else if ($deal == 1)
                $sql .= " AND nonexclusive_status = 1";
        }

        if (isset($filter["price_range_from"]) && $filter["price_range_from"] != "") {
            $sql .= " AND price >=" . $filter["price_range_from"];
        }
        if (isset($filter["price_range_to"]) && $filter["price_range_to"] != "") {
            $sql .= " AND price <=" . $filter["price_range_to"];
        }
        if (isset($filter["quality"]) && $filter["quality"] != "") {
            $quality = $filter["quality"];
            if ($quality == 1)
                $sql .= " AND verified_coherence = 1";
            else if ($quality == 2)
                $sql .= " AND verified_whatsapp = 1";
            else if ($quality == 3)
                $sql .= " AND verified_sms = 1";
            else if ($quality == 4)
                $sql .= " AND verified_staff = 1";
        }
        return $this->db->query($sql)->result_array();
    }

    public function get_all_client($filter)
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
                    p.is_active = 1 AND client_id = " . get_client_user_id();
        // if (isset($filter)) {
        //     $sql .= " AND status_id = " . $filter;
        // }

        log_message('error', $sql);

        return $this->db->query($sql)->result_array();
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
                    p.is_confirmed AS confirm_status,
                    p.is_fake,
                    p.is_available_sale,
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

    public function get_all_market_place()
    {
        $sql = "SELECT 
                    p.id, 
                    CONCAT(p.first_name, ' ', p.last_name) AS prospect_name, 
                    ps.name AS status, 
                    pt.name AS type, 
                    pc.name AS category, 
                    ac.name AS acquisition_channel, 
                    i.name AS industry,
                    p.is_confirmed AS confirm_status,
                    p.is_fake,
                    p.is_available_sale,
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
                    p.is_active = 1
                AND is_fake = 0
                AND is_available_sale = 1 ";

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
        if (!isset($data['client_id'])) {
            $data['client_id'] = get_client_user_id();
        }
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
    public function mark_fake($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('is_fake' => 1, 'fake_report_date' => date('Y-m-d H:i:s')));
    }
    public function mark_available_sale($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('is_available_sale' => 1, 'sale_available_date' => date('Y-m-d H:i:s')));
    }
}
