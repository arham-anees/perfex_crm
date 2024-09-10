<?php defined('BASEPATH') or exit('No direct script access allowed');

class Stats_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function client_dashboard()
    {
        $sql = "
    SELECT 
        COUNT(CASE WHEN DATE(l.created_at) = CURDATE() THEN 1 END) AS prospect_amount,
        reported.reported_today,
        COUNT(CASE WHEN DATE(l.created_at) = CURDATE() THEN 1 END) AS delivered_today,
        COUNT(CASE WHEN DATE(l.created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS delivered_yesterday,
        ROUND(AVG(l.price),2) AS prospect_avg_price
    FROM tblclients c
    INNER JOIN tblleadevo_leads l ON l.client_id = c.userid
    CROSS JOIN (
        SELECT 
            COUNT(CASE WHEN DATE(r.created_at) = CURDATE() THEN 1 END) AS reported_today
        FROM tblclients c
        INNER JOIN tblleadevo_reported_prospects r ON r.client_id = c.userid
        WHERE r.client_id = " . get_client_user_id() . "
    ) AS reported
    WHERE l.client_id = " . get_client_user_id() . " 
    AND l.campaign_id IS NOT NULL
    GROUP BY reported.reported_today;
";

$sql="SELECT 
IFNULL(COUNT(CASE WHEN DATE(l.created_at) = CURDATE() THEN 1 END), 0) AS prospect_amount,
COUNT(CASE WHEN DATE(l.created_at) = CURDATE() THEN 1 END) AS delivered_today,
COUNT(CASE WHEN DATE(l.created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS delivered_yesterday,
IFNULL(ROUND(AVG(l.price), 2),'-') AS prospect_avg_price,
(SELECT COUNT(CASE WHEN DATE(r.created_at) = CURDATE() THEN 1 END) 
    FROM tblleadevo_reported_prospects r 
    WHERE r.client_id =  " . get_client_user_id() . " ) AS reported_today
FROM tblclients c
LEFT JOIN tblleadevo_leads l ON l.client_id = c.userid
WHERE c.userid =  " . get_client_user_id() . " 
AND l.campaign_id IS NOT NULL;
";
$query = $this->db->query($sql);
return $query->result();

    }
    public function client_campaigns()
    {
        $sql_campaigns = "SELECT 
                    -- Count of campaigns open today
                    COUNT(CASE 
                        WHEN campaigns.status_id = 1 
                        AND DATE(start_date) < CURDATE() 
                        AND DATE(end_date) >= CURDATE() 
                        THEN 1 
                    END) AS open_today,

                    -- Count of campaigns open yesterday
                    COUNT(CASE 
                        WHEN campaigns.status_id = 2 
                        AND DATE(end_date) = CURDATE() - INTERVAL 1 DAY 
                        THEN 1 
                    END) AS open_yesterday,

                    -- Count of campaigns closed today
                    COUNT(CASE 
                        WHEN DATE(end_date) = CURDATE() 
                        AND campaigns.status_id = 2 
                        THEN 1 
                    END) AS closed_today,

                    -- Count of campaigns closed yesterday
                    COUNT(CASE 
                        WHEN DATE(end_date) = CURDATE() - INTERVAL 1 DAY 
                        AND campaigns.status_id = 2 
                        THEN 1 
                    END) AS closed_yesterday

                FROM tblclients c
                INNER JOIN tblleadevo_campaign campaigns ON campaigns.client_id = c.userid
                WHERE campaigns.client_id = " . get_client_user_id();


        $sql_leads = "SELECT 
                    -- Count of campaigns to deliver today
                    0 AS to_deliver_today,

                    -- Count of exclusive delivered yesterday
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = DATE(CURDATE()) - INTERVAL 1 DAY 
                        AND p.is_exclusive = 1 
                        THEN 1 
                    END) AS exclusive_delivered_yesterday,

                    -- Count of exclusive delivered today
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE()  
                        AND p.is_exclusive = 1 
                        THEN 1 
                    END) AS exclusive_delivered_today,

                    -- Count of non-exclusive delivered yesterday
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE() - INTERVAL 1 DAY 
                        AND p.is_exclusive = 0 
                        THEN 1 
                    END) AS non_exclusive_delivered_yesterday,

                    -- Count of non-exclusive delivered today
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE() 
                        AND p.is_exclusive = 0 
                        THEN 1 
                    END) AS non_exclusive_delivered_today,

                    -- Average price of exclusive leads
                    AVG(CASE 
                        WHEN p.is_exclusive = 1 
                        THEN l.price 
                        ELSE NULL 
                    END) AS avg_price_exclusive,

                    -- Average price of non-exclusive leads
                    AVG(CASE 
                        WHEN p.is_exclusive = 0 
                        THEN l.price 
                        ELSE NULL 
                    END) AS avg_price_non_exclusive

                FROM tblclients c
                INNER JOIN tblleadevo_leads l ON l.client_id = c.userid
                INNER JOIN tblleadevo_prospects p ON p.id = l.prospect_id
                WHERE l.client_id = " . get_client_user_id() . " AND l.campaign_id IS NOT NULL;";
        $sql = "SELECT 
                    -- Count of campaigns to deliver today
                    0 AS to_deliver_today,

                    -- Count of exclusive delivered yesterday
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = DATE(CURDATE()) - INTERVAL 1 DAY 
                        AND p.is_exclusive = 1 
                        THEN 1 
                    END) AS exclusive_delivered_yesterday,

                    -- Count of exclusive delivered today
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE()  
                        AND p.is_exclusive = 1 
                        THEN 1 
                    END) AS exclusive_delivered_today,

                    -- Count of non-exclusive delivered yesterday
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE() - INTERVAL 1 DAY 
                        AND p.is_exclusive = 0 
                        THEN 1 
                    END) AS non_exclusive_delivered_yesterday,

                    -- Count of non-exclusive delivered today
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE() 
                        AND p.is_exclusive = 0 
                        THEN 1 
                    END) AS non_exclusive_delivered_today,

                    -- Average price of exclusive leads
                    ROUND(AVG(CASE 
                        WHEN p.is_exclusive = 1 
                        THEN l.price 
                        ELSE NULL 
                    END),2) AS avg_price_exclusive,

                    -- Average price of non-exclusive leads
                    ROUND(AVG(CASE 
                        WHEN p.is_exclusive = 0 
                        THEN l.price 
                        ELSE NULL 
                    END),2) AS avg_price_non_exclusive,
                    leads.*

                FROM tblclients c
                INNER JOIN tblleadevo_leads l ON l.client_id = c.userid
                INNER JOIN tblleadevo_prospects p ON p.id = l.prospect_id
                JOIN (SELECT 
                    -- Count of campaigns open today
                    COUNT(CASE 
                        WHEN campaigns.status_id = 1 
                        AND DATE(start_date) < CURDATE() 
                        AND DATE(end_date) >= CURDATE() 
                        THEN 1 
                    END) AS open_today,

                    -- Count of campaigns open yesterday
                    COUNT(CASE 
                        WHEN campaigns.status_id = 2 
                        AND DATE(end_date) = CURDATE() - INTERVAL 1 DAY 
                        THEN 1 
                    END) AS open_yesterday,

                    -- Count of campaigns closed today
                    COUNT(CASE 
                        WHEN DATE(end_date) = CURDATE() 
                        AND campaigns.status_id = 2 
                        THEN 1 
                    END) AS closed_today,

                    -- Count of campaigns closed yesterday
                    COUNT(CASE 
                        WHEN DATE(end_date) = CURDATE() - INTERVAL 1 DAY 
                        AND campaigns.status_id = 2 
                        THEN 1 
                    END) AS closed_yesterday

                FROM tblclients c
                INNER JOIN tblleadevo_campaign campaigns ON campaigns.client_id = c.userid
                WHERE campaigns.client_id =  " . get_client_user_id() . ") AS leads
                
                WHERE l.client_id = " . get_client_user_id() . " AND l.campaign_id IS NOT NULL;";
        $query = $this->db->query($sql);
        return $query->result();
    }

    // public function admin_marketplace()
    // {
    //     $sql = "SELECT 10 exclusive_for_sale_today, 20 exclusive_for_sale_yesterday,
    //             12 non_exclusive_for_sale_today, 27 non_exclusive_for_sale_yesterday,
    //             15 exclusive_sold_today, 33 exclusive_sold_yesterday,
    //             42 non_exclusive_sold_today, 45 non_exclusive_sold_yesterday,
    //             3 exclusive_avg_time, 27 non_exclusive_avg_time,
    //             143 exclusive_avg_price, 100.3 non_exclusive_avg_price,
    //             2 reported_today, 3 reported_yesterday
    //             FROM tblleadevo_prospects
    //             ";
    // }

    public function admin_marketplace($filter)
    {
        $clients = $filter['selected_clients'];
        $sources = $filter['selected_sources'];

        $client_ids = implode(',', array_map('intval', $clients));
        $source_ids = implode(',', array_map('intval', $sources));
        $sql_market = "SELECT 
                    COUNT(CASE WHEN p.is_exclusive = 1 THEN 1 END) AS exclusive_for_sale_today,
                    COUNT(CASE WHEN p.is_exclusive = 0 THEN 1 END) AS non_exclusive_for_sale_today
                FROM
                    tblleadevo_prospects p
                WHERE
                    p.is_active = 1
                AND is_fake = 0
                AND is_available_sale = 1 
                AND (" . get_option('leadevo_deal_settings_status') . " = 0 OR DATE_ADD(p.created_at, INTERVAL " . get_option('leadevo_deal_max_sell_times') . " DAY ) >= CURDATE())";
        if (count($sources) > 0) {
            $sql_market .= " AND p.source_id IN (" . $source_ids . ")";
        }
        $sql_sold = "SELECT 
                        COUNT(CASE 
                            WHEN DATE(ll.created_at) = CURDATE() AND p.is_exclusive = 1  
                            THEN 1 
                            ELSE NULL 
                        END) AS exclusive_sold_today,
                        COUNT(CASE 
                            WHEN DATE(ll.created_at) = CURDATE() - INTERVAL 1 DAY AND p.is_exclusive = 1 
                            THEN 1 
                            ELSE NULL 
                        END) AS exclusive_sold_yesterday,
                        COUNT(CASE 
                            WHEN DATE(ll.created_at) = CURDATE() AND p.is_exclusive = 0  
                            THEN 1 
                            ELSE NULL 
                        END) AS non_exclusive_sold_today,
                        COUNT(CASE 
                            WHEN DATE(ll.created_at) = CURDATE() - INTERVAL 1 DAY AND p.is_exclusive = 0 
                            THEN 1 
                            ELSE NULL 
                        END) AS non_exclusive_sold_yesterday,
                        ROUND(AVG(CASE 
                            WHEN p.is_exclusive = 1 AND ll.created_at IS NOT NULL AND p.created_at IS NOT NULL
                            THEN TIMESTAMPDIFF(DAY, p.created_at, ll.created_at)
                            ELSE NULL 
                        END),2) AS avg_time_to_sell_exclusive_days,
                        ROUND(AVG(CASE 
                            WHEN p.is_exclusive = 0 AND ll.created_at IS NOT NULL AND p.created_at IS NOT NULL
                            THEN TIMESTAMPDIFF(DAY, p.created_at, ll.created_at)
                            ELSE NULL 
                        END),2) AS avg_time_to_sell_non_exclusive_days,
                        ROUND(AVG(CASE 
                            WHEN p.is_exclusive = 1 
                            THEN ll.price 
                            ELSE NULL 
                        END),2) AS avg_price_exclusive,
                        ROUND(AVG(CASE 
                            WHEN p.is_exclusive = 0 
                            THEN ll.price 
                            ELSE NULL 
                        END),2) AS avg_price_non_exclusive
                    FROM tblleadevo_leads ll 
                    INNER JOIN tblleadevo_prospects p ON p.id = ll.prospect_id
                    WHERE ll.campaign_id IS NOT NULL";
        if (count($clients) > 0) {
            $sql_sold .= " AND ll.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql_sold .= " AND p.source_id IN (" . $source_ids . ")";
        }


        $sql_reported = "SELECT 
                            COUNT(CASE 
                                WHEN DATE(r.created_at) = CURDATE() 
                                THEN 1 
                                ELSE NULL 
                            END) AS reported_today,
                            COUNT(CASE 
                                WHEN DATE(r.created_at) = CURDATE() - INTERVAL 1 DAY 
                                THEN 1 
                                ELSE NULL 
                            END) AS reported_yesterday
                        FROM tblleadevo_reported_prospects r
                        INNER JOIN tblleadevo_prospects p ON p.id = r.prospect_id
                        WHERE 1=1";
        if (count($clients) > 0) {
            $sql_reported .= " AND r.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql_sold .= " AND p.source_id IN (" . $source_ids . ")";
        }

        $sql = "SELECT 
                    0 AS exclusive_for_sale_yesterday,
                    0 AS non_exclusive_for_sale_yesterday,
                    market.*,
                    sold.*,
                    reported.*
                FROM tblleadevo_prospects p
                LEFT JOIN tblleadevo_leads l ON l.prospect_id = p.id
                LEFT JOIN tblleadevo_reported_prospects r ON r.prospect_id = p.id
                JOIN (" . $sql_market . ") AS market
                JOIN (" . $sql_sold . ") sold
                JOIN (" . $sql_reported . ") reported";
        $query = $this->db->query($sql);
        return $query->result();
    }


    // public function admin_campaigns()
    // {
    //     $sql = "SELECT 10 open_today,0 open_yesterday,  
    //             2 closed_today, 5 closed_yesterday, 
    //             20 to_deliver_today, 
    //             22 exclusive_delivered_yesterday,21 exclusive_delivered_today,
    //             26 non_exclusive_delivered_yesterday,29 non_exclusive_delivered_today, 
    //             100.5 avg_price_exclusive, 109.3 avg_price_non_exclusive 
    //             FROM tblleadevo_prospects";
    //     $query = $this->db->query($sql);
    //     return $query->result();
    // }

    public function admin_campaigns($filter)
    {
        $clients = $filter['selected_clients'];
        $sources = $filter['selected_sources'];

        $client_ids = implode(',', array_map('intval', $clients));
        $source_ids = implode(',', array_map('intval', $sources));


        $sql = "SELECT 
                    -- Count of campaigns to deliver today
                    0 AS to_deliver_today,

                    -- Count of exclusive delivered yesterday
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = DATE(CURDATE()) - INTERVAL 1 DAY 
                        AND p.is_exclusive = 1 
                        THEN 1 
                    END) AS exclusive_delivered_yesterday,

                    -- Count of exclusive delivered today
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE()  
                        AND p.is_exclusive = 1 
                        THEN 1 
                    END) AS exclusive_delivered_today,

                    -- Count of non-exclusive delivered yesterday
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE() - INTERVAL 1 DAY 
                        AND p.is_exclusive = 0 
                        THEN 1 
                    END) AS non_exclusive_delivered_yesterday,

                    -- Count of non-exclusive delivered today
                    COUNT(CASE 
                        WHEN DATE(l.created_at) = CURDATE() 
                        AND p.is_exclusive = 0 
                        THEN 1 
                    END) AS non_exclusive_delivered_today,

                    -- Average price of exclusive leads
                    ROUND(AVG(CASE 
                        WHEN p.is_exclusive = 1 
                        THEN l.price 
                        ELSE NULL 
                    END),2) AS avg_price_exclusive,

                    -- Average price of non-exclusive leads
                    ROUND(AVG(CASE 
                        WHEN p.is_exclusive = 0 
                        THEN l.price 
                        ELSE NULL 
                    END),2) AS avg_price_non_exclusive,
                    leads.*

                FROM tblclients c
                INNER JOIN tblleadevo_leads l ON l.client_id = c.userid
                INNER JOIN tblleadevo_prospects p ON p.id = l.prospect_id
                JOIN (SELECT 
                    -- Count of campaigns open today
                    COUNT(CASE 
                        WHEN campaigns.status_id = 1 
                        AND DATE(start_date) < CURDATE() 
                        AND DATE(end_date) >= CURDATE() 
                        THEN 1 
                    END) AS open_today,

                    -- Count of campaigns open yesterday
                    COUNT(CASE 
                        WHEN campaigns.status_id = 2 
                        AND DATE(end_date) = CURDATE() - INTERVAL 1 DAY 
                        THEN 1 
                    END) AS open_yesterday,

                    -- Count of campaigns closed today
                    COUNT(CASE 
                        WHEN DATE(end_date) = CURDATE() 
                        AND campaigns.status_id = 2 
                        THEN 1 
                    END) AS closed_today,

                    -- Count of campaigns closed yesterday
                    COUNT(CASE 
                        WHEN DATE(end_date) = CURDATE() - INTERVAL 1 DAY 
                        AND campaigns.status_id = 2 
                        THEN 1 
                    END) AS closed_yesterday

                FROM tblclients c
                INNER JOIN tblleadevo_campaign campaigns ON campaigns.client_id = c.userid
                WHERE 1=1 ";
        if (count($clients) > 0) {
            $sql .= " AND campaigns.client_id IN (" . $client_ids . ")";
        }
        $sql .= ") AS leads
                
                WHERE 1=1";
        if (count($clients) > 0) {
            $sql .= " AND p.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql .= " AND p.source_id IN (" . $source_ids . ")";
        }

        $query = $this->db->query($sql);
        return $query->result();
    }





    // public function admin_industry_monitoring()
    // {
    //     $sql = "SELECT 'test' industry, 10 received, 23 exclusive_sold, 34 non_exclusive_sold from tblleadevo_prospects";
    //     $query = $this->db->query($sql);
    //     return $query->result();
    // }


    public function admin_industry_monitoring_received($filter)
    {
        $start_date = $filter["start_date"];
        $end_date = $filter["end_date"];
        $clients = $filter['selected_clients'];
        $sources = $filter['selected_sources'];

        $client_ids = implode(',', array_map('intval', $clients));
        $source_ids = implode(',', array_map('intval', $sources));

        $sql = "SELECT i.id,i.name, COUNT(p.id) total_prospects FROM tblleadevo_industries i 
                LEFT JOIN tblleadevo_prospects p ON i.id = p.industry_id
                WHERE i.is_active = 1 ";
        if (isset($start_date)) {
            $sql .= " AND DATE(p.created_at) >= DATE('" . $start_date . "')";
        }
        if (isset($end_date)) {
            $sql .= " AND DATE(p.created_at) <= DATE('" . $end_date . "')";
        }
        if (count($clients) > 0) {
            $sql .= " AND p.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql .= " AND p.source_id IN (" . $source_ids . ")";
        }
        $sql .= " GROUP BY i.id, i.name  ORDER BY total_prospects DESC";

        $query = $this->db->query($sql);
        return $query->result();

    }


    public function admin_industry_monitoring_exclusive($filter)
    {
        $start_date = $filter["start_date"];
        $end_date = $filter["end_date"];
        $clients = $filter['selected_clients'];
        $sources = $filter['selected_sources'];

        $client_ids = implode(',', array_map('intval', $clients));
        $source_ids = implode(',', array_map('intval', $sources));

        $sql = "SELECT i.id,i.name, COUNT(p.id) total_prospects FROM tblleadevo_industries i 
                LEFT JOIN tblleadevo_prospects p ON i.id = p.industry_id
                WHERE i.is_active = 1 AND p.is_exclusive = 1 ";
        if (isset($start_date)) {
            $sql .= " AND DATE(p.created_at) >= DATE('" . $start_date . "')";
        }
        if (isset($end_date)) {
            $sql .= " AND DATE(p.created_at) <= DATE('" . $end_date . "')";
        }
        if (count($clients) > 0) {
            $sql .= " AND p.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql .= " AND p.source_id IN (" . $source_ids . ")";
        }
        $sql .= " GROUP BY i.id, i.name ORDER BY total_prospects DESC";

        $query = $this->db->query($sql);
        return $query->result();

    }


    public function admin_industry_monitoring_non_exclusive($filter)
    {
        $start_date = $filter["start_date"];
        $end_date = $filter["end_date"];
        $clients = $filter['selected_clients'];
        $sources = $filter['selected_sources'];

        $client_ids = implode(',', array_map('intval', $clients));
        $source_ids = implode(',', array_map('intval', $sources));

        $sql = "SELECT i.id,i.name, COUNT(p.id) total_prospects FROM tblleadevo_industries i 
                LEFT JOIN tblleadevo_prospects p ON i.id = p.industry_id
                WHERE i.is_active = 1 AND p.is_exclusive = 0 ";
        if (isset($start_date)) {
            $sql .= " AND DATE(p.created_at) >= DATE('" . $start_date . "')";
        }
        if (isset($end_date)) {
            $sql .= " AND DATE(p.created_at) <= DATE('" . $end_date . "')";
        }
        if (count($clients) > 0) {
            $sql .= " AND p.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql .= " AND p.source_id IN (" . $source_ids . ")";
        }
        $sql .= " GROUP BY i.id, i.name ORDER BY total_prospects DESC";

        $query = $this->db->query($sql);
        return $query->result();

    }
    public function admin_industry_monitoring($filter)
    {
        $start_date = $filter["start_date"];
        $end_date = $filter["end_date"];
        $clients = $filter['selected_clients'];
        $sources = $filter['selected_sources'];

        $client_ids = implode(',', array_map('intval', $clients));
        $source_ids = implode(',', array_map('intval', $sources));

        $sql = "SELECT 
                i.name AS industry, 
                COUNT(p.id) AS received,
                COUNT(CASE WHEN p.is_exclusive = 1 THEN 1 END) AS exclusive_sold,
                COUNT(CASE WHEN p.is_exclusive = 0 THEN 1 END) AS non_exclusive_sold
            FROM tblleadevo_prospects p
            LEFT JOIN tblleadevo_industries i ON p.industry_id = i.id
            WHERE 1=1 ";
        if (count($clients) > 0) {
            $sql .= " AND p.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql .= " AND p.source_id IN (" . $source_ids . ")";
        }
        $sql .= " GROUP BY i.name";

        $query = $this->db->query($sql);
        return $query->result();
    }


    public function admin_prospect_verification($filter)
    {
        $start_date = $filter["start_date"];
        $end_date = $filter["end_date"];
        $clients = $filter['selected_clients'];
        $sources = $filter['selected_sources'];

        $client_ids = implode(',', array_map('intval', $clients));
        $source_ids = implode(',', array_map('intval', $sources));


        $sql = "SELECT
                    -- Amount of prospects to be verified (not yet verified)
                    COUNT(CASE WHEN verified_sms = 0 THEN 1 END) AS to_be_verified_by_sms,
                    COUNT(CASE WHEN verified_whatsapp = 0 THEN 1 END) AS to_be_verified_by_whatsapp,
                    COUNT(CASE WHEN verified_staff = 0 THEN 1 END) AS to_be_verified_by_staff,
                    
                    -- Amount of prospects already verified
                    COUNT(CASE WHEN verified_sms = 1 THEN 1 END) AS verified_by_sms,
                    COUNT(CASE WHEN verified_whatsapp = 1 THEN 1 END) AS verified_by_whatsapp,
                    COUNT(CASE WHEN verified_staff = 1 THEN 1 END) AS verified_by_staff
                FROM 
                    tblleadevo_prospects p
                    WHERE 1=1 ";
        if (isset($start_date)) {
            $sql .= " AND DATE(p.created_at) >= DATE('" . $start_date . "')";
        }
        if (isset($end_date)) {
            $sql .= " AND DATE(p.created_at) <= DATE('" . $end_date . "')";
        }
        if (count($clients) > 0) {
            $sql .= " AND p.client_id IN (" . $client_ids . ")";
        }
        if (count($sources) > 0) {
            $sql .= " AND p.source_id IN (" . $source_ids . ")";
        }

        $query = $this->db->query($sql);
        return $query->result();
    }


    public function client_billing_stats()
    {
        $sql = "SELECT 
                    SUM(i.total ) total_invoices,
                    SUM(CASE WHEN i.status = 1 THEN i.total ELSE NULL END ) pending_invoices,
                    SUM(CASE WHEN i.status = 2 THEN i.total ELSE NULL END ) paid_invoices,
                    SUM(CASE WHEN i.status = 4 THEN i.total ELSE NULL END ) overdue_invoice,
                    SUM(CASE WHEN i.status = 5 THEN i.total ELSE NULL END ) cancelled_invoices
                FROM `tblinvoices` i 
                INNER JOIN tbltaggables t ON t.rel_id = i.id
                INNER JOIN tbltags tags ON tags.id = t.tag_id
                WHERE i.clientid = " . get_client_user_id() . " AND t.rel_type = 'invoice' AND (tags.name LIKE 'LeadEvo Campaign Checkout'  OR tags.name='LeadEvo Checkout')";
        return $this->db->query($sql)->result_array();
    }

    public function admin_billing_stats()
    {
        $sql = "SELECT 
                    SUM(i.total ) total_invoices,
                    SUM(CASE WHEN i.status = 1 THEN i.total ELSE NULL END ) pending_invoices,
                    SUM(CASE WHEN i.status = 2 THEN i.total ELSE NULL END ) paid_invoices,
                    SUM(CASE WHEN i.status = 4 THEN i.total ELSE NULL END ) overdue_invoices,
                    SUM(CASE WHEN i.status = 5 THEN i.total ELSE NULL END ) cancelled_invoices
                FROM `tblinvoices` i 
                INNER JOIN tbltaggables t ON t.rel_id = i.id
                INNER JOIN tbltags tags ON tags.id = t.tag_id
                WHERE  t.rel_type = 'invoice' AND (tags.name LIKE 'LeadEvo Campaign Checkout'  OR tags.name='LeadEvo Checkout')";
        return $this->db->query($sql)->result_array();
    }
}