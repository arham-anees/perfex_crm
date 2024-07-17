<?php defined('BASEPATH') or exit('No direct script access allowed');

class Leads_Report_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all leads
     * @return array
     */
    public function get_all_leads($startDate = null, $endDate = null, $last_action = null, $source = [], $staff = [], $statuses=[])
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'leads');
         // Apply date range filters if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $this->db->where('DATE(dateAdded) >= DATE('. $startDate .')');
            $this->db->where('DATE(dateAdded) <= DATE('. $endDate.')');
        }
        
        // Apply last_action filter if provided
        if (!is_null($last_action) && $last_action != '') {
            $this->db->where('DATE(lastContact)', 'DATE(' . $last_action . ')');
        }
        
        // Apply source filter if provided and not empty
        if (!empty($source)) {
            $this->db->where_in('source', $source);
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $this->db->where_in('status', $status);
        }
        
        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $this->db->where_in('assigned', $staff);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    // Get all staff
    public function get_all_staff()
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_leads_assigned_per_agent($startDate = null, $endDate = null, $last_action = null, $source = [], $staff = [], $status = [])
    {
        $sql = "SELECT COUNT(l.id) as lead_count, l.assigned as agent_id, s.firstname as agent
        FROM `tblleads` l
        LEFT JOIN `tblstaff` s ON l.assigned = s.staffid
        WHERE 1=1";

        // Apply date range filters if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $sql .= " AND DATE(l.dateAdded) >= DATE('" . $this->db->escape_str($startDate) . "')";
            $sql .= " AND DATE(l.dateAdded) <= DATE('" . $this->db->escape_str($endDate) . "')";
        }

        // Apply last_action filter if provided
        if (!is_null($last_action) && $last_action!='') {
            $sql .= " AND DATE(l.lastContact) = DATE('" . $this->db->escape_str($last_action) . "')";
        }

        // Apply source filter if provided and not empty
        if (!empty($source)) {
            
            $source_ids = implode(",",  $source);
            $sql .= " AND l.source IN (" . $source_ids . ")";
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $status_ids = implode(",",  $status);
            $sql .= " AND l.status IN (" . $status_ids . ")";
        }

        // Apply staff filter if provided and not empty
        log_message('error', 'staff');
        if (!empty($staff)) {
            $staff_ids = implode(",",  $staff);
            log_message('error', 'staff id'. $staff_ids);
            $sql .= " AND l.assigned IN (" . $staff_ids . ")";
        }

        $sql .= " GROUP BY l.assigned";


        $query = $this->db->query($sql);

        return $query->result_array();
    }
    
    public function get_leads_created_per_agent($startDate = null, $endDate = null, $last_action = null, $source = [], $staff = [],$status = [])
    {
        $sql = "SELECT COUNT(l.id) as lead_count, l.addedfrom as agent_id, s.firstname as agent
        FROM `tblleads` l
        LEFT JOIN `tblstaff` s ON l.assigned = s.staffid
        WHERE 1=1";

        // Apply date range filters if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $sql .= " AND DATE(l.dateAdded) >= DATE('" . $this->db->escape_str($startDate) . "')";
            $sql .= " AND DATE(l.dateAdded) <= DATE('" . $this->db->escape_str($endDate) . "')";
        }
        // Apply last_action filter if provided
        if (!is_null($last_action) && $last_action!='') {
            $sql .= " AND DATE(l.lastContact) = DATE('" . $this->db->escape_str($last_action) . "')";
        }
        // Apply source filter if provided and not empty
        if (!empty($source)) {
            $source_ids = implode(",",  $source);
            $sql .= " AND l.source IN (" . $source_ids . ")";
        }
        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $status_ids = implode(",", $status);
            $sql .= " AND l.status IN (" . $status_ids . ")";
        }
        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $staff_ids = implode(",", $staff);
            $sql .= " AND l.addedfrom IN (" . $staff_ids . ")";
        }
        $sql .= " GROUP BY l.addedfrom";

        $query = $this->db->query($sql);

        return $query->result_array();
    }
    // Get leads per agent
    public function get_leads_assigned_per_agent2($startDate = null, $endDate = null, $last_action = null, $source = [], $staff = [], $statuses=[])
    {
        $query = $this->db->query("SELECT COUNT(l.id) as lead_count, 
                                        s.firstname as agent
                                    FROM `tblstaff` s
                                    LEFT JOIN tblleads l
                                    ON s.staffid = l.assigned
                                    GROUP BY s.staffid
       ");
       
//        SELECT COUNT(tblleads.id) as lead_count, 

//        tblstaff.firstname as agent
//    FROM `" . db_prefix() . "leads`
//    INNER JOIN " . db_prefix() . "staff
//    ON " . db_prefix() . "staff.staffid = " . db_prefix() . "leads.assigned
//    GROUP BY " . db_prefix() . "staff.staffid


    //    SELECT COUNT(l.id) as lead_count,
//        s.firstname as firstname
// FROM " . db_prefix() . "leads l
// INNER JOIN " . db_prefix() . "staff s
// ON s.staffid = l.assigned
// GROUP BY s.staffid
        
        return $query->result_array();
        // $this->db->select('s.firstname as agent, COUNT(l.id) as lead_count');
        // $this->db->from(db_prefix() . 'staff s');
        // $this->db->join(db_prefix() . 'leads l', 's.staffid = l.assigned');
        // $this->db->group_by('s.staffid');
        // $query = $this->db->get();

        // return $query->result_array();
    }


    public function get_leads_created_per_agent2()
    {
        $this->db->select('addedfrom, COUNT(id) as lead_count');
        $this->db->from(db_prefix() . 'leads');
        $this->db->group_by('addedfrom');
        $query = $this->db->get();
        return $query->result_array(); // Ensure it returns an array
    }

    // Conversion Rate of Prospects to Customers
    public function get_conversion_rate($startDate = null, $endDate = null, $last_action = null, $source = [], $status = [], $staff = [])
    {
        $sql = "SELECT
            s.staffid AS agent_id,
            CONCAT(s.firstname, ' ', s.lastname) AS agent_name,
            COUNT(DISTINCT l.id) AS total_prospects,
            SUM(CASE WHEN c.leadid IS NOT NULL THEN 1 ELSE 0 END) AS total_customers,
            CASE WHEN COUNT(DISTINCT l.id) > 0 THEN SUM(CASE WHEN c.leadid IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(DISTINCT l.id) ELSE 0 END AS conversion_rate,
            AVG(TIMESTAMPDIFF(DAY, l.dateadded, c.datecreated)) AS avg_conversion_time
        FROM
            tblstaff s
        LEFT JOIN
            tblleads l ON s.staffid = l.assigned
        LEFT JOIN
            tblclients c ON l.id = c.leadid
        WHERE
            1=1 ";

        // Apply date range filters if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $sql .= " AND DATE(l.dateadded) >= DATE('" . $this->db->escape_str($startDate) . "')";
            $sql .= " AND DATE(l.dateadded) <= DATE('" . $this->db->escape_str($endDate) . "')";
        }

        // Apply last_action filter if provided and not empty
        if (!is_null($last_action) && $last_action !== '') {
            $sql .= " AND l.lastcontact = DATE('" . $this->db->escape_str($last_action) . "')";
        }

        // Apply source filter if provided and not empty
        if (!empty($source)) {
            $source_ids = implode(",",  $source);
            $sql .= " AND l.source IN (" . $source_ids . ")";
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $status_ids = implode(",",  $status);
            $sql .= " AND l.status IN (" . $status_ids . ")";
        }

        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $staff_ids = implode(",", $staff);
            $sql .= " AND l.assigned IN (" . $staff_ids . ")";
        }

        $sql .= " GROUP BY
                    s.staffid, CONCAT(s.firstname, ' ', s.lastname)
                ORDER BY
                    conversion_rate DESC;";



        // $this->db->select('assigned, COUNT(id) as total_leads, SUM(IF(client_id IS NOT NULL, 1, 0)) as converted_leads');
        // $this->db->from(db_prefix() . 'leads');
        // $this->db->group_by('assigned');
        // $query = $this->db->get();

        // Select clause
//     $this->db->select("
//     s.staffid AS agent_id,
//     CONCAT(s.firstname, ' ', s.lastname) AS agent_name,
//     COUNT(DISTINCT l.id) AS total_prospects,
//     SUM(CASE WHEN c.clientid IS NOT NULL THEN 1 ELSE 0 END) AS total_customers,
//     CASE WHEN COUNT(DISTINCT l.id) > 0 THEN SUM(CASE WHEN c.clientid IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(DISTINCT l.id) ELSE 0 END AS conversion_rate
// ");

// // From clause and joins
// $this->db->from('tblstaff s');
// $this->db->join('tblleads l', 's.staffid = l.assigned', 'left');
// $this->db->join('tblclients c', 'l.id = c.leadid', 'left');
// $this->db->join('tblinvoices i', 'c.userid = i.clientid', 'left');

// // Apply date range filter if provided
// if (!is_null($startDate) && !is_null($endDate)) {
//     $this->db->where("DATE(l.dateadded) BETWEEN", [$startDate, $endDate]);
// }

// // Apply status filter if provided and not empty
// if (!empty($status)) {
//     $this->db->where_in('l.status', $status);
// }

// // Apply staff filter if provided and not empty
// if (!empty($staff)) {
//     $this->db->where_in('s.staffid', $staff);
// }

// // Group by clause
// $this->db->group_by('s.staffid, CONCAT(s.firstname, " ", s.lastname)');

// // Order by clause (if needed)
// // $this->db->order_by('agent_name ASC');

// // Execute the query
// $query = $this->db->get();
$query = $this->db->query($sql);

// Returning the result array
// return $query->result_array();
        return $query->result_array();
    }

    // Average Time Spent per Prospect
    public function get_average_time_spent_per_prospect($startDate = null, $endDate = null, $last_action = null, $source = [], $status = [], $staff = [])
    {
    //     $this->db->select("
    //     s.staffid AS agent_id,
    //     CONCAT(s.firstname, ' ', s.lastname) AS agent_name,
    //     AVG(ipr.amount) AS average_value_won
    // ");

    // // From clause and joins
    // $this->db->from('tblstaff s');
    // $this->db->join('tblclients c', 's.staffid = c.leadid', 'left');
    // $this->db->join('tblinvoices i', 'c.userid = i.clientid', 'left');
    // $this->db->join('tblinvoicepaymentrecords ipr', 'i.id = ipr.invoiceid', 'left');

    // // Where clause
    // $this->db->where('ipr.amount >', 0);

    // // Apply date range filter if provided
    // if (!is_null($startDate) && !is_null($endDate)) {
    //     $this->db->where("(i.date BETWEEN '" . $this->db->escape_str($startDate) . "' AND '" . $this->db->escape_str($endDate) . "')");
    // }

    // // Group by and order by clauses
    // $this->db->group_by('s.staffid, agent_name');
    // // $this->db->order_by('ipr.amount DESC');

    // // Execute query
    // $query = $this->db->get();

   


        $sql = "SELECT assigned, AVG(TIMESTAMPDIFF(HOUR, dateassigned, IFNULL(date_converted, NOW()))) as avg_time
                FROM " . db_prefix() . "leads
                WHERE 1=1";
    
        // Apply date range filters if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $sql .= " AND DATE(dateassigned) >= DATE('" . $this->db->escape_str($startDate) . "')";
            $sql .= " AND DATE(dateassigned) <= DATE('" . $this->db->escape_str($endDate) . "')";
        }
    
        // Apply last_action filter if provided and not empty
        if (!is_null($last_action) && $last_action !== '') {
                $sql .= " AND lastcontact = DATE('" . $this->db->escape_str($last_action) . "')";
        }
    
        // Apply source filter if provided and not empty
        if (!empty($source)) {
            $source_ids = implode(",", $source);
            $sql .= " AND source IN (" . $source_ids . ")";
        }
    
        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $status_ids = implode(",",  $status);
            $sql .= " AND status IN (" . $status_ids . ")";
        }
    
        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $staff_ids = implode(",",  $staff);
            $sql .= " AND assigned IN (" . $staff_ids . ")";
        }
    
        $sql .= " GROUP BY assigned
                 HAVING avg_time IS NOT NULL";
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // Follow-up Rate with Prospects
    public function get_follow_up_rate()
    {
        $this->db->select('assigned, COUNT(id) as total_leads, SUM(IF(last_status_change IS NOT NULL, 1, 0)) as follow_ups');
        $this->db->from(db_prefix() . 'leads');
        $this->db->group_by('assigned');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Number of Appointments Set
    public function get_appointments_set()
    {
        // Using 'created_by' as the column for staff ID
        $this->db->select('created_by as assigned, COUNT(id) as appointments');
        $this->db->from(db_prefix() . 'appointly_appointments');
        $this->db->group_by('created_by');
        $query = $this->db->get();
        return $query->result_array();
    }



    // Prospect Attrition Rate
    public function get_prospect_attrition_rate()
    {
        $this->db->select('assigned, COUNT(id) as total_leads, SUM(IF(lost = 1 OR junk = 1, 1, 0)) as lost_prospects');
        $this->db->from(db_prefix() . 'leads');
        $this->db->group_by('assigned');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Average Value of Won Prospects
    public function get_average_value_of_won_prospects($startDate = null, $endDate = null, $last_action = null, $source = [], $status = [], $staff = [])
    {
        $sql="SELECT
            s.staffid AS agent_id,
            CONCAT(s.firstname, ' ', s.lastname) AS agent_name,
            AVG(ipr.amount) AS average_value_won
        FROM
            tblstaff s
        LEFT JOIN
            tblclients c ON s.staffid = c.leadid

        LEFT JOIN
            tblleads l ON l.id = c.leadid
        LEFT JOIN
            tblleads_sources ls ON ls.id = l.source
        LEFT JOIN
            tblinvoices i ON c.userid = i.clientid
        LEFT JOIN
            tblinvoicepaymentrecords ipr ON i.id = ipr.invoiceid
        WHERE ipr.amount>0
        ";

        // Apply date range filters if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $sql .= " AND DATE(i.datecreated) >= '" . $this->db->escape_str($startDate) . "'";
            $sql .= " AND DATE(i.datecreated) <= '" . $this->db->escape_str($endDate) . "'";
        }


        // Apply source filter if provided and not empty
        if (!empty($source)) {
            $source_ids = implode(",",  $source);
            $sql .= " AND l.source IN (" . $source_ids . ")";
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $status_ids = implode(",", $status);
            $sql .= " AND l.status IN (" . $status_ids . ")";
        }

        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $staff_ids = implode(",",  $staff);
            $sql .= " AND l.assigned IN (" . $staff_ids . ")";
        }

            $sql .= "GROUP BY
            s.staffid, agent_name
        ORDER BY
            average_value_won DESC;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // Average Sales Cycle
    public function calculate_average_sales_cycle($startDate = null, $endDate = null,$last_action = null, $source = [], $status = [], $staff = []) 
    {
        $this->db->select('AVG(TIMESTAMPDIFF(HOUR, dateadded, date_converted)) as avg_sales_cycle');
        $this->db->from(db_prefix() . 'leads');
        if (!is_null($startDate) && !is_null($endDate)) {
            $this->db->where('DATE(dateAdded) >= ', $startDate);
            $this->db->where('DATE(dateAdded) <= ', $endDate);
        }
        
        // Apply last_action filter if provided
        if (!is_null($last_action) && $last_action != '') {
            $this->db->where('DATE(lastContact)', 'DATE(' . $last_action . ')');
        }
        
        // Apply source filter if provided and not empty
        if (!empty($source)) {
            $this->db->where_in('source', $source);
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $this->db->where_in('status', $status);
        }
        
        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $this->db->where_in('assigned', $staff);
        }
        $query = $this->db->get();

        // Check if there are results
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    //lead source effectiveness report
    public function get_lead_source_effectiveness($startDate = null, $endDate = null,$last_action = null, $source = [], $status = [], $staff = []) 
    {
          // Select clause for the query
          $this->db->select("
          ls.name AS source_name,
          AVG(TIMESTAMPDIFF(DAY, l.dateadded, l.date_converted)) AS avg_conversion_time,
          CASE WHEN COUNT(l.id) > 0 THEN SUM(CASE WHEN l.date_converted IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(l.id) ELSE 0 END AS conversion_rate,
          COUNT(l.id) AS total_leads,
          COALESCE(pro.quotes_sent, 0) AS quotes_sent,
          COALESCE(pro.quotes_signed, 0) AS quotes_signed
        ");

        // From clause for the query with dynamic prefix and table name
        $this->db->from($this->db->dbprefix('leads') . ' l');

        // Join with lead_sources table to get source names
        $this->db->join($this->db->dbprefix('leads_sources') . ' ls', 'ls.id = l.source', 'left');

        // Left join for proposals
        $this->db->join("
            (SELECT assigned,
                COUNT(CASE WHEN status = 6 THEN 1 END) AS quotes_sent,
                COUNT(CASE WHEN status = 3 THEN 1 END) AS quotes_signed
            FROM " . $this->db->dbprefix('proposals') . "
            GROUP BY assigned
            ) pro", 'pro.assigned = l.source', 'left');

        // Apply date range filter if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $this->db->where("DATE(l.dateadded) BETWEEN DATE('" . $this->db->escape_str($startDate) . "') AND DATE('" . $this->db->escape_str($endDate) . "')");
         }

        // Apply last_action filter if provided and not empty
        if (!is_null($last_action) && $last_action !== '') {
            $this->db->where('DATE(l.lastcontact)', 'DATE('.$last_action.')');
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $this->db->where_in('l.status', $status);
        }

        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $this->db->where_in('l.assigned', $staff);
        }
        // Apply staff filter if provided and not empty
        if (!empty($source)) {
            $this->db->where_in('l.source', $source);
        }


        // Group by source
        $this->db->group_by('l.source');

        // Order by conversion rate descending
        $this->db->order_by('conversion_rate DESC');

        // Limit to top 5 sources
        $this->db->limit(5);

        // Execute the query
        $query = $this->db->get();
        return $query->result_array();
    }

    // agent effectiveness report
    function get_agent_effectiveness_report($startDate = null, $endDate = null, $last_action = null, $source = [], $status = [], $staff = [])
    {
        // Select clause for the query
        $this->db->select("
            CONCAT(s.firstname, ' ', s.lastname) AS agent_name,
            AVG(TIMESTAMPDIFF(DAY, l.dateadded, l.date_converted)) AS avg_conversion_time,
            CASE WHEN COUNT(l.id) > 0 THEN SUM(CASE WHEN l.date_converted IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(l.id) ELSE 0 END AS conversion_rate,
            COUNT(l.id) AS total_leads,
            COALESCE(a.total_appointments, 0) AS total_appointments,
            COALESCE(a.appointments_missed, 0) AS appointments_missed,
            COALESCE(pro.quotes_sent, 0) AS quotes_sent,
            COALESCE(pro.quotes_signed, 0) AS quotes_signed
        ");

        // From clause for the query
        $this->db->from('tblstaff s');
        $this->db->join('tblleads l', 's.staffid = l.assigned', 'left');

        // Left join for appointments
        $this->db->join("
            (SELECT created_by,
                COUNT(created_by) AS total_appointments,
                SUM(CASE WHEN STR_TO_DATE(CONCAT(date, ' ', start_hour), '%Y-%m-%d %H:%i:%s') > NOW() THEN 0 ELSE 1 END) AS appointments_missed
            FROM tblappointly_appointments
            WHERE cancelled = 0
            GROUP BY created_by
            ) a", 'a.created_by = l.assigned', 'left');

        // Left join for proposals
        $this->db->join("
            (SELECT assigned,
                COUNT(CASE WHEN status = 6 THEN 1 END) AS quotes_sent,
                COUNT(CASE WHEN status = 3 THEN 1 END) AS quotes_signed
            FROM tblproposals
            GROUP BY assigned
            ) pro", 'pro.assigned = l.assigned', 'left');

        // Where clause for filters
        $this->db->where('1 = 1'); // Dummy condition to start WHERE clause

        // Apply date range filter if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $this->db->where("DATE(l.dateadded) BETWEEN DATE('" . $this->db->escape_str($startDate) . "') AND DATE('" . $this->db->escape_str($endDate) . "')");
        }

        // Apply last_action filter if provided and not empty
        if (!is_null($last_action) && $last_action !== '') {
            $this->db->where('DATE(l.lastcontact)', 'DATE('.$last_action.')');
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $this->db->where_in('l.status', $status);
        }

        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $this->db->where_in('l.assigned', $staff);
        }

        // Group by clause
        $this->db->group_by('s.staffid');

        // Order by clause
        $this->db->order_by('avg_conversion_time ASC, conversion_rate DESC, total_leads DESC');

        // Limit clause
        $this->db->limit(5); // Limit to top 5 agents

        // Execute the query
        $query = $this->db->get();
        return $query->result_array();
    }
}
