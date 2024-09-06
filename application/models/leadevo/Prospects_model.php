<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects_model extends CI_Model
{

    private $table = 'leadevo_prospects';
    private $reason_table = 'tblleadevo_report_lead_reasons';

    private $report_table = 'tblleadevo_reported_prospects';

    private $client_rating = 'leadevo_prospects_rating_client';

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
            p.desired_amount,
            p.min_amount,
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
        if (isset($filter["acquisition_channel_id"]) && $filter["acquisition_channel_id"] != "") {
            $sql .= " AND acquisition_id =" . $filter["acquisition_id"];
        }if (isset($filter["type_id"]) && $filter["type_id"] != "") {
            $sql .= " AND type_id =" . $filter["type_id"];
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
                $sql .= " AND exclusive_status = 0";
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
            p.is_fake,
            p.is_available_sale,
            p.desired_amount,
            p.min_amount,
            
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
                AND client_id = " . get_client_user_id();


        if (isset($filter["industry_id"]) && $filter["industry_id"] != "") {
            $sql .= " AND industry_id = " . $filter["industry_id"];
        } if (isset($filter["industry_name"]) && $filter["industry_name"] != "") {
           $sql .= " AND i.name LIKE '%" . $filter["industry_name"] . "%'";
        }
        if (isset($filter["acquisition_channel_id"]) && $filter["acquisition_channel_id"] != "") {
            $sql .= " AND ac.id =" . $filter["acquisition_channel_id"];
        }if (isset($filter["type"]) && $filter["type"] != "") {
            $sql .= " AND pt.name LIKE '%" . $filter["type"] . "%'";
        }
        if (isset($filter["zip_codes"]) && $filter["zip_codes"] != "" && count($filter["zip_codes"]) > 0) {
            $sql .= " AND zip_code in (" . implode(",", $filter["zip_codes"]) . ")";
        }
        if (isset($filter["generated_from"]) && $filter["generated_from"] != "") {
            $sql .= " AND DATE(p.created_at) <= DATE('" . $filter["generated_from"] . "')";
        }

        if (isset($filter["generated_to"]) && $filter["generated_to"] != "") {
            $sql .= " AND DATE(p.created_at) >= DATE('" . $filter["generated_to"] . "')";
        }
        if (isset($filter["deal"]) && $filter["deal"] != "") {

            $deal = $filter["deal"];
            if ($deal == 0)
                $sql .= " AND exclusive_status = 0";
            else if ($deal == 1)
                $sql .= " AND nonexclusive_status = 1";
        }

        if (isset($filter["price_range_from"]) && $filter["price_range_from"] != "") {
            $sql .= " AND p.desired_amount >=" . $filter["price_range_from"];
        }
        if (isset($filter["price_range_to"]) && $filter["price_range_to"] != "") {
            $sql .= " AND p.desired_amount <=" . $filter["price_range_to"];
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
        // if (isset($filter)) {
        //     $sql .= " AND status_id = " . $filter;
        // }

        return $this->db->query($sql)->result_array();
    }

    public function get_all($filter)
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
                    p. fake_description,
                    p.is_available_sale,
                    p.desired_amount,
                    p.min_amount,
                    r.rating,
                    p.phone,
                    null AS zip_code,
                    p.email,
                    null AS source,
                    null AS deal,
                    null AS quality,
                    p.is_auto_deliverable,
                    p.phone_normalize_attempt,
                    p.email_normalize_attempt,
                    nsp.name phone_normalize_status,
                    nse.name email_normalize_status
                FROM tblleadevo_prospects p
                LEFT JOIN tblleadevo_prospect_statuses ps ON p.status_id = ps.id
                LEFT JOIN tblleadevo_prospect_types pt ON p.type_id = pt.id   
                LEFT JOIN tblleadevo_prospect_categories pc ON p.category_id = pc.id
                LEFT JOIN tblleadevo_acquisition_channels ac ON p.acquisition_channel_id = ac.id
                LEFT JOIN tblleadevo_industries i ON p.industry_id = i.id
                LEFT JOIN tblleadevo_normalization_statuses nsp ON nsp.id = p.phone_normalize_status
                LEFT JOIN tblleadevo_normalization_statuses nse ON nse.id = p.email_normalize_status
                LEFT JOIN   
                    (SELECT r.*
                    FROM `tblleadevo_prospects_rating` r
                    INNER JOIN (
                        SELECT prospect_id, MAX(rated_at) AS max_rated_at
                        FROM `tblleadevo_prospects_rating`
                        GROUP BY prospect_id
                    ) AS latest_ratings ON r.prospect_id = latest_ratings.prospect_id
                    AND r.rated_at = latest_ratings.max_rated_at) r
                ON r.prospect_id = p.id
                WHERE
                    p.is_active = 1 ";
        if (isset($filter["industry_name"]) && $filter["industry_name"] != "") {
           $sql .= " AND i.name LIKE '%" . $filter["industry_name"] . "%'";
        }
        if (isset($filter["prospect_name"]) && $filter["prospect_name"] != "") {
            $sql .= " AND CONCAT(p.first_name, ' ', p.last_name) LIKE '%" . $filter["prospect_name"] . "%'";
        }if (isset($filter["industry_id"]) && $filter["industry_id"] != "") {
            $sql .= " AND p.industry_id = " . $filter["industry_id"];
        }if (isset($filter["email_normalization"]) && $filter["email_normalization"] != "") {
            $sql .= " AND p.email_normalize_status = " . $filter["email_normalization"];
        }if (isset($filter["phone_normalization"]) && $filter["phone_normalization"] != "") {
            $sql .= " AND p.phone_normalize_status = " . $filter["phone_normalization"];
        }
        
        if (isset($filter["acquisition_channel_id"]) && $filter["acquisition_channel_id"] != "") {
            $sql .= " AND ac.id =" . $filter["acquisition_channel_id"];
        }
        if (isset($filter["type"]) && $filter["type"] != "") {
            $sql .= " AND pt.name LIKE '%" . $filter["type"] . "%'";
        }
        if (isset($filter["zip_codes"]) && $filter["zip_codes"] != "" && count($filter["zip_codes"]) > 0) {
            $sql .= " AND zip_code in (" . implode(",", $filter["zip_codes"]) . ")";
        }
        if (isset($filter["generated_from"]) && $filter["generated_from"] != "") {
            $sql .= " AND DATE(p.created_at) <= DATE('" . $filter["generated_from"] . "')";
        }

        if (isset($filter["generated_to"]) && $filter["generated_to"] != "") {
            $sql .= " AND DATE(p.created_at) >= DATE('" . $filter["generated_to"] . "')";
        }
        if (isset($filter["deal"]) && $filter["deal"] != "") {

            $deal = $filter["deal"];
            if ($deal == 0)
                $sql .= " AND p.is_exclusive = 0";
            else if ($deal == 1)
                $sql .= " AND p.is_exclusive = 1";
        }

        if (isset($filter["price_range_from"]) && $filter["price_range_from"] != "") {
            $sql .= " AND p.desired_amount >=" . $filter["price_range_from"];
        }
        if (isset($filter["price_range_to"]) && $filter["price_range_to"] != "") {
            $sql .= " AND p.desired_amount <=" . $filter["price_range_to"];
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

    public function get_all_fake($filter = array())
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
                    p.fake_report_date,
                    p.is_available_sale,
                    NULL AS zip_code,  -- Assuming these columns are not available or not needed
                    NULL AS phone,    -- Confirm with your actual schema if these should be NULL
                    NULL AS email,
                    NULL AS source,
                    NULL AS deal,
                    p.fake_description,
                    NULL AS quality,  -- Assuming 'quality' is not available or not needed
                    CONCAT(s.firstname, ' ', s.lastname) AS marked_by_admin  -- Admin's name
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
                LEFT JOIN
                    tblstaff s ON p.mark_fake_by = s.staffid  -- Join to get admin details
                WHERE
                    p.is_active = 1 
                    AND p.is_fake = 1";

        // Apply filters if provided
        if (isset($filter["industry_id"]) && $filter["industry_id"] != "") {
            $sql .= " AND industry_id = " . $filter["industry_id"];
        }

        if (isset($filter["acquisition_channel_id"]) && $filter["acquisition_channel_id"] != "") {
            $sql .= " AND acquisition_channel_id = " . $filter["acquisition_channel_id"];
        }
        if (isset($filter["zip_codes"]) && !empty($filter["zip_codes"])) {
            $sql .= " AND zip_code IN (" . implode(",", $filter["zip_codes"]) . ")";
        }
        if (isset($filter["generated_from"]) && $filter["generated_from"] != "") {
            $sql .= " AND DATE(created_at) >= DATE('" . $filter["generated_from"] . "')";
        }
        if (isset($filter["generated_to"]) && $filter["generated_to"] != "") {
            $sql .= " AND DATE(created_at) <= DATE('" . $filter["generated_to"] . "')";
        }
        if (isset($filter["deal"]) && $filter["deal"] != "") {
            $deal = $filter["deal"];
            if ($deal == 0)
                $sql .= " AND exclusive_status = 0";
            else if ($deal == 1)
                $sql .= " AND nonexclusive_status = 1";
        }
        if (isset($filter["price_range_from"]) && $filter["price_range_from"] != "") {
            $sql .= " AND price >= " . $filter["price_range_from"];
        }
        if (isset($filter["price_range_to"]) && $filter["price_range_to"] != "") {
            $sql .= " AND price <= " . $filter["price_range_to"];
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
    public function get_filtered_fake($filters)
    {
        $this->db->from('prospects');
        $this->db->where('is_fake', true);

        if (!empty($filters['name'])) {
            $this->db->like('prospect_name', $filters['name']);
        }
        if (!empty($filters['status'])) {
            $this->db->like('status', $filters['status']);
        }
        if (!empty($filters['type'])) {
            $this->db->like('type', $filters['type']);
        }
        if (!empty($filters['category'])) {
            $this->db->like('category', $filters['category']);
        }

        return $this->db->get()->result_array();
    }



    public function get_all_market_place($filter = [])
    {
        // echo "<pre>";
        // print_r($filter);exit;
        $sql = "SELECT 
                    p.id, 
                    CONCAT(p.first_name, ' ', p.last_name) AS prospect_name, 
                    p.last_name,
                    p.first_name,
                    ps.name AS status, 
                    pt.name AS type, 
                    pc.name AS category, 
                    ac.name AS acquisition_channel, 
                    i.name AS industry,
                    p.is_confirmed AS confirm_status,
                    p.is_fake,
                    p.is_available_sale,
                    null AS zip_code,
                    phone,
                    email,
                    p.desired_amount,
                    p.min_amount,
                    pso.name AS source,
                    null AS deal,
                    null AS quality,
                    p.verified_sms,
                    p.verified_whatsapp,
                    p.verified_staff,
                    p.created_at,
                    p.share_audio_before_purchase,
                    p.verified_staff_audio
                FROM
                    tblleadevo_prospects p
                LEFT JOIN
                    tblleadevo_prospect_statuses ps ON p.status_id = ps.id
                LEFT JOIN
                    tblleadevo_prospects_sources pso ON p.source_id = pso.id
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
        if (isset($filter["prospect_name"]) && $filter["prospect_name"] != "") {
            $sql .= " AND CONCAT(p.first_name, ' ', p.last_name) LIKE '%" . $filter["prospect_name"] . "%'";
        }
        if (isset($filter["industry_id"]) && $filter["industry_id"] != "") {
            $sql .= " AND p.industry_id = " . $filter["industry_id"];
        } if (isset($filter["industry_name"]) && $filter["industry_name"] != "") {
           $sql .= " AND i.name LIKE '%" . $filter["industry_name"] . "%'";
        }
        if (isset($filter["acquisition_channel_id"]) && $filter["acquisition_channel_id"] != "") {
            $sql .= " AND ac.id =" . $filter["acquisition_channel_id"];
        }if (isset($filter["type"]) && $filter["type"] != "") {
            $sql .= " AND pt.name LIKE '%" . $filter["type"] . "%'";
        }
        if (isset($filter["zip_codes"]) && $filter["zip_codes"] != "" && count($filter["zip_codes"]) > 0) {
            $sql .= " AND zip_code in (" . implode(",", $filter["zip_codes"]) . ")";
        }
        if (isset($filter["generated_from"]) && $filter["generated_from"] != "") {
            $sql .= " AND DATE(p.created_at) <= DATE('" . $filter["generated_from"] . "')";
        }

        if (isset($filter["generated_to"]) && $filter["generated_to"] != "") {
            $sql .= " AND DATE(p.created_at) >= DATE('" . $filter["generated_to"] . "')";
        }
        if (isset($filter["deal"]) && $filter["deal"] != "") {

            $deal = $filter["deal"];
            if ($deal == 0)
                $sql .= " AND p.is_exclusive = 0";
            else if ($deal == 1)
                $sql .= " AND p.is_exclusive = 1";
        }

        if (isset($filter["price_range_from"]) && $filter["price_range_from"] != "") {
            $sql .= " AND p.desired_amount >=" . $filter["price_range_from"];
        }
        if (isset($filter["price_range_to"]) && $filter["price_range_to"] != "") {
            $sql .= " AND p.desired_amount <=" . $filter["price_range_to"];
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
        // $query=$this->db->last_query();
        $prospects_all = $this->db->query($sql)->result_array();
    

        $prospects = [];
        if (get_option('leadevo_deal_settings_status')) {
            $max_days = get_option('leadevo_deal_max_sell_times');
            foreach ($prospects_all as $prospect) {
                $cutOffDate = new DateTime($prospect['created_at']);
                $cutOffDate->modify('+' . $max_days . ' days'); // Set cutoff date as $max_days ago
                if (new DateTime() < $cutOffDate) {
                    $prospects[] = $prospect;
                }
            }
        } else {
            $prospects = $prospects_all;
        }

        return $prospects;
    }
    public function get_all_market_place_admin($filter = [])
    {
        $sql = "SELECT 
                p.id, 
                CONCAT(p.first_name, ' ', p.last_name) AS prospect_name, 
                ps.name AS status, 
                pt.name AS type, 
                pc.name AS category, 
                ac.name AS acquisition_channel, 
                i.name AS industry,
                pso.name AS source,
                p.is_confirmed AS confirm_status,
                p.is_fake,
                p.is_available_sale,
                p.desired_amount,
                p.min_amount,
                NULL AS zip_code,
                phone,
                email,
                SUM(CASE WHEN c.deal = 1 THEN 1 ELSE 0 END) AS exclusive_sales,  -- Count of exclusive sales
                SUM(CASE WHEN c.deal = 0 OR c.deal IS NULL THEN 1 ELSE 0 END) AS non_exclusive_sales,  -- Count of non-exclusive sales
                NULL AS quality,
                p.verified_sms,
                p.verified_whatsapp,
                p.verified_staff,
                p.created_at,
                p.share_audio_before_purchase,
                p.verified_staff_audio
            FROM
                tblleadevo_prospects p
            LEFT JOIN
                tblleadevo_prospect_statuses ps ON p.status_id = ps.id
            LEFT JOIN
                tblleadevo_prospects_sources pso ON p.source_id = pso.id
            LEFT JOIN
                tblleadevo_prospect_types pt ON p.type_id = pt.id   
            LEFT JOIN
                tblleadevo_prospect_categories pc ON p.category_id = pc.id
            LEFT JOIN
                tblleadevo_acquisition_channels ac ON p.acquisition_channel_id = ac.id
            LEFT JOIN
                tblleadevo_industries i ON p.industry_id = i.id
            LEFT JOIN
                tblleadevo_leads ll ON p.id = ll.prospect_id  -- Joining with leads table
            LEFT JOIN
                tblleadevo_campaign c ON ll.campaign_id = c.id  -- Joining with campaign table to get deal
            WHERE
                p.is_active = 1
                AND p.is_fake = 0
                AND p.is_available_sale = 1
            GROUP BY
                p.id, 
                p.first_name, 
                p.last_name, 
                ps.name, 
                pt.name, 
                pc.name, 
                ac.name, 
                i.name, 
                p.is_confirmed,
                p.is_fake,
                p.is_available_sale,
                p.desired_amount,
                p.min_amount;
 ";
        if (isset($filter["industry_id"]) && $filter["industry_id"] != "") {
            $sql .= " AND industry_id = " . $filter["industry_id"];
        }
        if (isset($filter["acquisition_channel_id"]) && $filter["acquisition_channel_id"] != "") {
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
                $sql .= " AND exclusive_status = 0";
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

        $prospects_all = $this->db->query($sql)->result_array();
        $prospects = [];
        if (get_option('leadevo_deal_settings_status')) {
            $max_days = get_option('leadevo_deal_max_sell_times');
            foreach ($prospects_all as $prospect) {
                $cutOffDate = new DateTime($prospect['created_at']);
                $cutOffDate->modify('+' . $max_days . ' days'); // Set cutoff date as $max_days ago
                if (new DateTime() < $cutOffDate) {
                    $prospects[] = $prospect;
                }
            }
        } else {
            $prospects = $prospects_all;
        }
        return $prospects;
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
        $this->db->select('c.*, i.name as industry_name, a.name as acquisition_channel_name, cat.name as category_name, t.name as type_name'); // Select campaign details, industry, acquisition channel, category, and type name
        $this->db->from($this->table . ' c'); // Alias for the campaign table
        $this->db->join('tblleadevo_industries i', 'c.industry_id = i.id', 'left'); // Join industries table on industry_id
        $this->db->join('tblleadevo_acquisition_channels a', 'c.acquisition_channel_id = a.id', 'left'); // Join acquisition channels table on acquisition_channel_id
        $this->db->join('tblleadevo_prospect_categories cat', 'c.category_id = cat.id', 'left'); // Join categories table on category_id
        $this->db->join('tblleadevo_prospect_types t', 'c.type_id = t.id', 'left'); // Join prospect types table on type_id
        $this->db->where('c.id', $id); // Filter by campaign ID
        return $this->db->get()->row(); // Return the row with campaign, industry, acquisition channel, category, and type name
    }



    public function insert($data)
    {
        if (!isset($data['client_id'])) {
            $data['client_id'] = get_client_user_id();
        }
        $data['is_available_sale'] = 0;
        $data['is_fake'] = 0;
        return $this->db->insert($this->table, $data);
    }

    // Update an prospect
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        
        $this->db->where('id', $id);
        $result = $this->db->update($this->table, ['is_active' => 0]);
    
        return $result;
    }
    

    public function mark_fake($id, $description)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array(
            'is_fake' => 1,
            'fake_report_date' => date('Y-m-d H:i:s'),
            'fake_description' => $description
        ));
    }

    public function mark_unfake($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array(
            'is_fake' => 0,
            'fake_report_date' => date('00-00-0 0:0:0'),
        ));
    }

    public function reject_prospect_report($campaign_id, $prospect_id, $description)
    {
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where('prospect_id', $prospect_id);
        return $this->db->update($this->report_table, array(
            'status' => 2,
            'feedback' => $description
        ));
    }


    public function mark_as_auto_deliverable($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('is_auto_deliverable' => 1));

    }

    public function rate($id, $ratings)
    {
        $data['user_id'] = get_staff_user_id();
        $data['prospect_id'] = $id;
        $data['rating'] = $ratings;
        $data['rated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert(db_prefix() . 'leadevo_prospects_rating', $data);
    }

    public function client_rate($id, $ratings)
    {
        $data['user_id'] = get_client_user_id();
        $data['lead_id'] = $id;
        $data['rating'] = $ratings;
        $data['rated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert(db_prefix() . 'leadevo_prospects_rating_client', $data);
    }
    public function update_sale_status($id, $available, $is_exclusive, $desired_amount, $min_amount)
    {
        $this->db->where('id', $id);
        return $this->db->update(
            $this->table,
            array(
                'is_available_sale' => $available,
                'sale_available_date' => date('Y-m-d H:i:s'),
                'is_exclusive' => $is_exclusive,
                'desired_amount' => $desired_amount,
                'min_amount' => $min_amount
            )
        );
    }

    public function deliver_prospects($campaing_id)
    {
        $sql = "SELECT id, client_id, industry_id, country_id, deal,budget,  verify_by_staff, verify_by_sms, verify_by_whatsapp, verify_by_coherence,
                timings
                FROM `tblleadevo_campaign`
                WHERE is_active = 1 AND status_id = 1 AND `start_date` < NOW() AND `end_date` > NOW() AND Id = " . $campaing_id . ";";

        $campaign = $this->db->query($sql)->row();
        if (!isset($campaign)) {
            return;
        }

        // filter prospects based on the criteria
        // country id may be category ID
        $temp_table = "SELECT p.*, r.rating FROM `tblleadevo_prospects` p
                LEFT JOIN   
                    (SELECT r.*
                    FROM `tblleadevo_prospects_rating` r
                    INNER JOIN (
                        SELECT prospect_id, MAX(rated_at) AS max_rated_at
                        FROM `tblleadevo_prospects_rating`
                        GROUP BY prospect_id
                    ) AS latest_ratings ON r.prospect_id = latest_ratings.prospect_id
                    AND r.rated_at = latest_ratings.max_rated_at) r
                ON r.prospect_id = p.id
                WHERE is_active = 1 AND is_fake = 0 AND is_available_sale = 1 AND is_auto_deliverable = 1 ";
        if (isset($campaign->industry_id)) {
            $sql .= " AND industry_id = " . $campaign->industry_id;
        }
        if ($campaign->verify_by_staff == 1)
            $temp_table .= " AND verified_staff = 1";
        if ($campaign->verify_by_sms == 1)
            $temp_table .= " AND verified_sms = 1";
        if ($campaign->verify_by_whatsapp == 1)
            $temp_table .= " AND verified_whatsapp = 1";
        if ($campaign->verify_by_coherence == 1)
            $temp_table .= " AND verified_coherence = 1";

        $sql = $temp_table;



        /** MARKET CAP
         * Market Cap is the amount of max prospects a user wants to receive
         * on specific date and time.
         * The logic below is the we fetch prospects delivered today and market caps, find caps for current date. If the market cap is set
         * within past hour, it will send the prospects to the client.
         * if the market cap is set we also check the delivered today, if there is delivery in past one hour, then 
         * we do not send any new lead to user.
         * 
         * NOTE: CURRENTLY IT IS SAFE TO KEEP A GAP OF ATLEAST ONE HOUR BETWEEN THE CAP SLOTS.
         */

        $delivered_today_sql = "SELECT * FROM tblleadevo_leads ll
        WHERE ll.campaign_id IS NOT NULL  AND DATE(ll.created_at) = CURDATE() AND ll.client_id = " . $campaign->client_id;

        $delivered_today = $this->db->query($delivered_today_sql)->result();
        // read market cap
        $cap_str = $campaign->timings;
        $caps = json_decode($cap_str, true);
        // total prospects -1 indicates that the cap is not applicable.
        $total_prospects = -1;
        if (count($caps) > 0) {
            $continue_process = true;
            $dateFound = false;
            foreach ($caps as $datetimeString) {
                list($datePart, $numberPart) = explode(' ', $datetimeString);

                $datetime = new DateTime($datePart);
                $number = (int) $numberPart; // Parse the number as an integer

                $currentDate = new DateTime();
                $isSameDate = $datetime->format('Y-m-d') === $currentDate->format('Y-m-d');


                if ($isSameDate == true) {
                    $dateFound = true;
                    $oneHourAgo = (clone $currentDate)->modify('-1 hour');

                    if ($datetime > $oneHourAgo && $datetime <= $currentDate) {
                        $continue_process = true;
                        $total_prospects = $number;
                        break;
                    }
                } else {
                    $continue_process = false;
                }
            }
            if ($continue_process == false && $dateFound == true) {
                return;
            } else {
                /**
                 * If we have a market cap set, check if we have delivered 
                 * some prospects in last hour, then we will skip it here.
                 */
                $currentDateTime = new DateTime();
                $oneHourAgo = (clone $currentDateTime)->modify('-1 hour');

                $found = false;

                // Loop through each object in the array
                foreach ($delivered_today as $entry) {
                    // Access the datetime attribute
                    $datetimeString = $entry->created_at;

                    // Parse the datetime string into a DateTime object
                    $datetime = new DateTime($datetimeString);

                    // Check if the datetime is within the last hour
                    if ($datetime > $oneHourAgo && $datetime <= $currentDateTime) {
                        $found = true;
                        break; // No need to check further, one valid entry is enough
                    }
                }
                if ($found == true) {
                    return;
                }
            }
        }
        // else delivered_today: get prospects delivered today
        //  if delivered_today > 0 continue;
        else if (count($delivered_today) > 0) {
            return;
        }
        $prospects = $this->db->query($sql)->result();
        if ($total_prospects == -1) {
            $total_prospects = count($prospects);
        }

        // now fetch prospects based on star weightage
        $sql = $temp_table;
        if (get_option('delivery_settings') == 1) {
            $sql = "SELECT * FROM (
            (SELECT * FROM (" . $temp_table . ") temp WHERE rating IS NULL ORDER BY RAND() LIMIT " . ((int) get_option('delivery_settings_0stars')) * $total_prospects . ") 
            UNION ALL
            (SELECT * FROM (" . $temp_table . ") temp WHERE rating = 1 ORDER BY RAND() LIMIT " . ((int) get_option('delivery_settings_1stars')) * $total_prospects . ")
            UNION ALL
            (SELECT * FROM (" . $temp_table . ") temp WHERE rating = 2 ORDER BY RAND() LIMIT " . ((int) get_option('delivery_settings_2stars')) * $total_prospects . ")
            UNION ALL
            (SELECT * FROM (" . $temp_table . ") temp WHERE rating = 3 ORDER BY RAND() LIMIT " . ((int) get_option('delivery_settings_3stars')) * $total_prospects . ")
            UNION ALL
            (SELECT * FROM (" . $temp_table . ") temp WHERE rating = 4 ORDER BY RAND() LIMIT " . ((int) get_option('delivery_settings_4stars')) * $total_prospects . ")
            UNION ALL
            (SELECT * FROM (" . $temp_table . ") temp WHERE rating = 5 ORDER BY RAND() LIMIT " . ((int) get_option('delivery_settings_5stars')) * $total_prospects . ")
        ) AS weighted_selection;
        ";
        } else {
            $sql = $temp_table;
        }
        // limit the max cap
        $prospects = $this->db->query($sql)->result();


        // send these prospects to client
        $sql = '';
        $this->db->trans_begin();

        try {
            foreach ($prospects as $prospect) {
                $budget_spent = $this->db->query("SELECT IFNULL(SUM(price), 0) AS budget_spent  FROM tblleadevo_leads WHERE campaign_id = " . $campaign->id)->row()->budget_spent;
                if ($budget_spent >= $campaign->budget) {
                    //  mark the campaign as completed
                    $this->db->query("UPDATE tblleadevo_Campaign SET status_id = 2 WHERE id = " . $campaign->id);
                    continue;
                }
                $budget = 0;
                if (($budget_spent + $prospect->desired_amount) <= $campaign->budget)
                    $budget = $prospect->desired_amount;
                else if (($budget_spent + $prospect->min_amount) <= $campaign->budget)
                    $budget = $prospect->min_amount;
                else
                    continue;

                // create invoice for each
                $sql = "INSERT INTO " . db_prefix() . "leads(name,email, phonenumber, status, source, hash, dateadded, addedfrom) VALUES('" . $prospect->first_name . " " . $prospect->last_name . "','" . $prospect->email
                    . "','" . $prospect->phone . "',2,2,'" . app_generate_hash() . "', '" . date('Y-m-d H:i:s') . "',0);";
                $this->db->query($sql);

                // Get the last inserted ID from tblleads
                $lastInsertId = $this->db->insert_id();
                $sql = "INSERT INTO " . db_prefix() . "leadevo_leads(lead_id, prospect_id, client_id,campaign_id, created_at, price) VALUES(" . $lastInsertId . "," . $prospect->id . "," . $campaign->client_id . "," . $campaign->id . ", '" . date('Y-m-d H:i:s') . "', '" . $budget . "');";
                $this->db->query($sql);

                if ($campaign->deal == 1) {
                    $this->db->query("UPDATE tblleadevo_prospects SET is_active=0, updated_at = UTC_TIMESTAMP() WHERE id = " . $prospect->id);
                    // clear from carts, if the prospect is exclusive
                    $this->db->query("DELETE FROM tblleadevo_cart WHERE invoice_id IS NULL and prospect_id = " . $prospect->id);
                }
            }

            // If everything is successful, commit the transaction
            if ($this->db->trans_status() === FALSE) {
                // If something went wrong, roll back the transaction
                $this->db->trans_rollback();
            } else {
                // Commit the transaction
                // $this->db->trans_rollback();
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            // Rollback transaction if any exception occurs
            $this->db->trans_rollback();
            throw new Exception("Transaction failed with exception: " . $e->getMessage());
        }

        //INSERT INTO tblinvoices (clientid, date, duedate, subtotal, total, status, currency, addedfrom, prefix, number, hash)
        //VALUES (1, '2024-08-12', '2024-09-12', 100.00, 100.00, 1, 1, 1, 'INV-', 1001, MD5(RAND()));
        //INSERT INTO tblinvoiceitems (invoiceid, description, qty, rate, taxid, taxrate)
        //VALUES (LAST_INSERT_ID(), 'Service Description', 1, 100.00, NULL, 0);


    }

    public function deliver_prospects_cart($invoice, $prospects)
    {

        if (!isset($prospects)) {
            return;
        }
        $client_id = $invoice->clientid;
        // send these prospects to client
        $sql = '';
        $this->db->trans_begin();

        try {
            foreach ($prospects as $prospect_cart) {
                $prospect = $this->db->query("SELECT * FROM tblleadevo_prospects WHERE id = " . $prospect_cart['prospect_id'])->row();
                // create invoice for each
                $sql = "INSERT INTO " . db_prefix() . "leads(name,email, phonenumber, status, source, hash, dateadded, addedfrom) VALUES('" . $prospect->first_name . " " . $prospect->last_name . "','" . $prospect->email
                    . "','" . $prospect->phone . "',2,2,'" . app_generate_hash() . "', '" . date('Y-m-d H:i:s') . "',0);";
                $this->db->query($sql);

                // Get the last inserted ID from tblleads
                $lastInsertId = $this->db->insert_id();
                $sql = "INSERT INTO " . db_prefix() . "leadevo_leads(lead_id, prospect_id, client_id, created_at, price, invoice_id) VALUES(" . $lastInsertId . "," . $prospect->id . "," . $client_id . ", '" . date('Y-m-d H:i:s') . "', '" . $prospect_cart['price'] . "', " . $invoice->id . ");";
                $this->db->query($sql);

                if ($prospect->is_exclusive == 1) {
                    $this->db->query("UPDATE tblleadevo_prospects SET is_active=0, updated_at = UTC_TIMESTAMP() WHERE id = " . $prospect->id);
                    // clear from carts, if the prospect is exclusive
                    $this->db->query("DELETE FROM tblleadevo_cart WHERE prospect_id = " . $prospect->id);
                }
                $this->db->query("DELETE FROM tblleadevo_cart WHERE Invoice_id = " . $invoice->id . " AND prospect_id = " . $prospect_cart['prospect_id']);
            }

            // If everything is successful, commit the transaction
            if ($this->db->trans_status() === FALSE) {
                // If something went wrong, roll back the transaction
                $this->db->trans_rollback();
            } else {
                // Commit the transaction
                // $this->db->trans_rollback();
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            // Rollback transaction if any exception occurs
            $this->db->trans_rollback();
            throw new Exception("Transaction failed with exception: " . $e->getMessage());
        }

        //INSERT INTO tblinvoices (clientid, date, duedate, subtotal, total, status, currency, addedfrom, prefix, number, hash)
        //VALUES (1, '2024-08-12', '2024-09-12', 100.00, 100.00, 1, 1, 1, 'INV-', 1001, MD5(RAND()));
        //INSERT INTO tblinvoiceitems (invoiceid, description, qty, rate, taxid, taxrate)
        //VALUES (LAST_INSERT_ID(), 'Service Description', 1, 100.00, NULL, 0);


    }

    public function get_Reasons()
    {
        return $this->db->get($this->reason_table)->result_array();
    }

    public function submit_report($data)
    {
        if (!isset($data['client_id'])) {
            $data['client_id'] = get_client_user_id();
        }
        $prospect_id = $this->db->query('SELECT prospect_id FROM tblleadevo_leads where lead_id = ' . $data['lead_id'])->row()->prospect_id;
        // log_message('error', );
        if (!$prospect_id || $prospect_id == 0) {
            throw new Exception('There is not prospect against this lead');
        }
        $data['prospect_id'] = $prospect_id;
        if (isset($data['lead_id']))
            unset($data['lead_id']);
        return $this->db->insert($this->report_table, $data);
    }

    public function get_replacements($prospect_id)
    {
        $sql = "SELECT p1.*
                FROM tblleadevo_prospects p1
                INNER JOIN tblleadevo_prospects p2
                    ON p1.industry_id = p2.industry_id
                    AND p1.verified_sms = p2.verified_sms
                    AND p1.verified_whatsapp = p2.verified_whatsapp
                    AND p1.verified_staff = p2.verified_staff
                    AND p1.is_confirmed = p2.is_confirmed
                    AND p1.is_exclusive = p2.is_exclusive
                WHERE p1.is_active = 1 AND p2.is_active=1 AND p1.is_fake = 0 AND p1.is_available_sale = 1 AND p2.id = " . $prospect_id . " AND p1.id <> " . $prospect_id;
        return $this->db->query($sql)->result_array();
    }

    public function replace($old_prospect_id, $new_prospect_id, $campaign_id)
    {
        try {


            $this->db->trans_begin();

            // delete previous lead
            $sql = "DELETE FROM tblleads 
                WHERE id =(SELECT lead_id FROM tblleadevo_leads WHERE prospect_id = " . $old_prospect_id . " and campaign_id = " . $campaign_id . " LIMIT 1);";
            $this->db->query($sql);
            // create new prospect
            $sql = "SELECT * FROM tblleadevo_prospects WHERE id =" . $new_prospect_id;
            $prospect = $this->db->query($sql)->row();
            $sql = "INSERT INTO " . db_prefix() . "leads(name,email, phonenumber, status, source, hash, dateadded, addedfrom) VALUES('" . $prospect->first_name . " " . $prospect->last_name . "','" . $prospect->email
                . "','" . $prospect->phone . "',2,2,'" . app_generate_hash() . "', '" . date('Y-m-d H:i:s') . "',0);";
            $lastInsertId = $this->db->insert_id();
            $sql = "UPDATE " . db_prefix() . "leadevo_leads SET lead_id = " . $lastInsertId . ", prospect_id =" . $new_prospect_id . " WHERE  prospect_id = " . $old_prospect_id . " AND campaign_id = " . $campaign_id;
            $this->db->query($sql);

            if ($prospect->is_exclusive == 1) {
                $this->db->query("UPDATE tblleadevo_prospects SET is_active=0, updated_at = UTC_TIMESTAMP() WHERE id = " . $prospect->id);
            }
            // If everything is successful, commit the transaction
            if ($this->db->trans_status() === FALSE) {
                // If something went wrong, roll back the transaction
                $this->db->trans_rollback();
                throw new Exception("Transaction failed. Rolling back.");
            } else {
                // Commit the transaction
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            // Rollback transaction if any exception occurs
            $this->db->trans_rollback();
            throw new Exception("Transaction failed with exception: " . $e->getMessage());
        }
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('tblleadevo_prospects');
        return $query->row_array();
    }
    public function get_log_by_id($id)
    {
        $this->db->where('prospect_id', $id);
        $query = $this->db->get('tblleadevo_prospect_activity_log');
        return $query->result_array();
    }

    public function get_prospect_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function log_activity($prospect_id, $type, $comments)
    {
        $data = [];
        $data['prospect_id'] = $prospect_id;
        $data['type'] = $type;
        $data['comments'] = $comments;
        $data['date'] = date('Y-m-d H:i:s');
        $data['staff_id'] = get_staff_user_id();
        $data['staff_name'] = get_staff_full_name();
        $data['client_id'] = get_client_user_id();
        $data['client_name'] = get_contact_full_name();
        $this->db->insert('tblleadevo_prospect_activity_log', $data);
    }
    public function get_all_prospects_with_admin()
    {
        $this->db->select('p.*, s.firstname AS admin_firstname, s.lastname AS admin_lastname');
        $this->db->from('tblleadevo_prospects p');
        $this->db->join('tblstaff s', 'p.mark_fake_by = s.staffid', 'left');
        $this->db->order_by('p.fake_description', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

}
