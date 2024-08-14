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
                    P.desired_amount,
                    p.min_amount,
                    r.rating,
                    null AS zip_code,
                    null AS phone,
                    null AS email,
                    null AS source,
                    null AS deal,
                    null AS quality,
                    p.is_auto_deliverable
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
                    p.is_active = 1";

        return $this->db->query($sql)->result_array();
    }
    public function get_all_purchased()
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
                    r.rating,
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
                INNER JOIN tblleadevo_prospects_purchased lpp
                ON lpp.prospect_id = p.id
                WHERE
                    p.is_active = 1 AND lpp.client_id = " . get_client_user_id();

        return $this->db->query($sql)->result_array();
    }
    public function get_all_fake()
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
                    p.is_active = 1 AND is_fake = 1";

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
                    phone,
                    email,
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

        return $this->db->query($sql)->result_array();
    }
    public function get_all_market_place_admin()
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
        return $this->db->where('id', $id)->delete($this->table);
    }
    public function mark_fake($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('is_fake' => 1, 'fake_report_date' => date('Y-m-d H:i:s')));
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
        $sql = "SELECT id, client_id, industry_id, country_id, deal,  verify_by_staff, verify_by_sms, verify_by_whatsapp, verify_by_coherence
                FROM `tblleadevo_campaign`
                WHERE is_active = 1 AND status_id = 1 AND `start_date` < NOW() AND `end_date` > NOW() AND Id = " . $campaing_id . ";";

        $campaign = $this->db->query($sql)->row();

        if (!isset($campaign)) {
            return;
        }
        // $campaign = $campaign[0];
        // deal_type will be dealt later

        // check if budget is expired

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

        $prospects = $this->db->query($sql)->result();
        $total_prospects = count($prospects);

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
            // insert each prospect into the tblleadevo_prospects_purchased
            foreach ($prospects as $prospect) {
                $budget_spent = $this->db->query("SELECT IFNULL(SUM(price), 0) AS budget_spent  FROM tblleadevo_leads WHERE campaign_id = " . $campaign->id);
                if ($budget_spent >= $campaign->budget)
                    continue;
                $budget = $prospect->desired_amount;
                if (($budget_spent + $prospect->desired_amount) >= $campaign->budget && ($budget_spent + $prospect->min_amount) <= $campaign->budget)
                    $budget = $prospect->min_amount;

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
                }
                // TODO: clear from carts
            }

            // If everything is successful, commit the transaction
            if ($this->db->trans_status() === FALSE) {
                // If something went wrong, roll back the transaction
                $this->db->trans_rollback();
                echo "Transaction failed. Rolling back.";
            } else {
                // Commit the transaction
                $this->db->trans_commit();
                echo "Transaction successful.";
            }
        } catch (Exception $e) {
            // Rollback transaction if any exception occurs
            $this->db->trans_rollback();
            echo "Transaction failed with exception: " . $e->getMessage();
        }

        //INSERT INTO tblinvoices (clientid, date, duedate, subtotal, total, status, currency, addedfrom, prefix, number, hash)
        //VALUES (1, '2024-08-12', '2024-09-12', 100.00, 100.00, 1, 1, 1, 'INV-', 1001, MD5(RAND()));
        //INSERT INTO tblinvoiceitems (invoiceid, description, qty, rate, taxid, taxrate)
        //VALUES (LAST_INSERT_ID(), 'Service Description', 1, 100.00, NULL, 0);


    }
}
