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
        $sql = "SELECT COUNT(l.id) as lead_count, l.assigned as agent_id, s.firstname as agent,
        (SELECT COUNT(lc.id) 
             FROM `tblleads` lc 
             WHERE lc.addedfrom = l.assigned ";
              if (!is_null($startDate) && !is_null($endDate)) {
                $sql .= " AND DATE(lc.dateAdded) >= DATE('" . $this->db->escape_str($startDate) . "')";
                $sql .= " AND DATE(lc.dateAdded) <= DATE('" . $this->db->escape_str($endDate) . "')";
            }
             // Apply last_action filter if provided
        if (!is_null($last_action) && $last_action!='') {
            $sql .= " AND DATE(lc.lastContact) = DATE('" . $this->db->escape_str($last_action) . "')";
        }

        // Apply source filter if provided and not empty
        if (!empty($source)) {
            
            $source_ids = implode(",",  $source);
            $sql .= " AND lc.source IN (" . $source_ids . ")";
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $status_ids = implode(",",  $status);
            $sql .= " AND lc.status IN (" . $status_ids . ")";
        }

        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $staff_ids = implode(",",  $staff);
            $sql .= " AND lc.assigned IN (" . $staff_ids . ")";
        }

        $sql .= ") as leads_created_count
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
        if (!empty($staff)) {
            $staff_ids = implode(",",  $staff);
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
        LEFT JOIN `tblstaff` s ON l.addedfrom = s.staffid
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

    // Conversion Rate of Prospects to Customers
    public function get_attrition_rate($startDate = null, $endDate = null, $last_action = null, $source = [], $status = [], $staff = [])
    {
        $sql = "SELECT
                    l.assigned AS agent_id,
                    CONCAT(s.firstname, ' ', s.lastname) AS agent_name,
                    COUNT(l.id) AS total_prospects,
                    SUM(CASE WHEN l.lost = 1 OR l.junk = 1 THEN 1 ELSE 0 END) AS attrited_prospects,
                    CASE WHEN COUNT(l.id) > 0 THEN SUM(CASE WHEN l.lost = 1 OR l.junk = 1 THEN 1 ELSE 0 END) * 100 / COUNT(l.id) ELSE 0 END AS attrition_rate,
                    SUM(CASE WHEN l.date_converted IS NOT NULL THEN 1 ELSE 0 END) AS converted_clients,
                    CASE WHEN COUNT(l.id) > 0 THEN SUM(CASE WHEN l.date_converted IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(l.id) ELSE 0 END AS conversion_rate
                FROM
                    tblleads l
                LEFT JOIN
                    tblstaff s ON l.assigned = s.staffid
                WHERE 1=1 ";

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
                    attrition_rate DESC;";



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
    public function get_follow_up_rate($startDate = null, $endDate = null, $last_action = null, $source = [], $status = [], $staff = [])
    {
        
        $sql = "SELECT 
            s.staffid AS agent_id,
            CONCAT(s.firstname, ' ', s.lastname) AS agent_name,
            COUNT(CASE WHEN la.description IN ('not_lead_activity_contacted', 'not_activity_new_reminder_create', 'not_lead_activity_status_updated', 'not_lead_activity_note_added') THEN 1 END) AS follow_ups
        FROM 
            " . db_prefix() . "lead_activity_log la
        LEFT JOIN 
            " . db_prefix() . "leads l ON la.leadid = l.id
        LEFT JOIN 
            " . db_prefix() . "staff s ON l.assigned = s.staffid
        WHERE 1=1";

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
            $source_ids = implode(",", $source);
            $sql .= " AND l.source IN (" . $source_ids . ")";
        }
    
        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $status_ids = implode(",",  $status);
            $sql .= " AND l.status IN (" . $status_ids . ")";
        }
    
        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $staff_ids = implode(",",  $staff);
            $sql .= " AND l.assigned IN (" . $staff_ids . ")";
        }

            // " AND l.addeddate BETWEEN " . $this->db->escape($startDate) . " AND " . $this->db->escape($endDate);
            // "AND l.source IN (" . implode(',', array_map([$this->db, 'escape'], $source)) . ")";
            // "AND l.assigned IN (" . implode(',', array_map([$this->db, 'escape'], $staff)) . ")";
        
            $sql .= "GROUP BY 
            s.staffid;
        ";
        $query = $this->db->query($sql);
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
        if (!is_null($last_action) && $last_action != '') {
            $sql .= " AND DATE(l.lastContact) = Date('" . $this->db->escape_str($last_action) . "')";
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
        $this->db->select('AVG(TIMESTAMPDIFF(DAY, dateadded, date_converted)) as avg_sales_cycle');
        $this->db->from(db_prefix() . 'leads');
        if (!is_null($startDate) && !is_null($endDate)) {
            $this->db->where('DATE(dateAdded) >= ', $startDate);
            $this->db->where('DATE(dateAdded) <= ', $endDate);
        }
        
        // Apply last_action filter if provided
        if (!is_null($last_action) && $last_action != '') {
            $this->db->where('DATE(lastContact)', 'DATE(' . $this->db->escape_str($last_action) . ')');
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


    public function calculate_average_satisfaction_score($startDate = null, $endDate = null,$last_action = null, $source = [], $status = [], $staff = []) 
    {
        $this->db->select('AVG(feedback) as satisfaction_score');
        $this->db->from(db_prefix() . 'appointly_appointments');
        
        // Join with appointly_attendees table
        $this->db->join(db_prefix() . 'appointly_attendees', db_prefix() . 'appointly_appointments.id = ' . db_prefix() . 'appointly_attendees.appointment_id', 'left');
        
        if (!is_null($startDate) && !is_null($endDate)) {
            $this->db->where('DATE(date) >= ', $startDate);
            $this->db->where('DATE(date) <= ', $endDate);
            // $sql .= " AND (DATE(date) >= '2024-01-01 00:00' AND DATE(date) <= '2024-12-31')";
        }
        
        // Apply source filter if provided and not empty
        if (!empty($source)) {
            $this->db->where_in('source', $source);
        }
        
        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $this->db->where_in(db_prefix() . 'appointly_attendees.staff_id', $staff);
            // $sql .= " AND tblappointly_attendees.staff_id IN (1);";
        }
        $query = 
        // $query = $this->db->query($sql);
        $this->db->get();
        
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
            COALESCE(a.total_appointments, 0) AS total_appointments,
            COALESCE(a.appointments_missed, 0) AS appointments_missed,
            COALESCE(pro.quotes_sent, 0) AS quotes_sent,
            COALESCE(pro.quotes_signed, 0) AS quotes_signed
        ");

        // From clause for the query with dynamic prefix and table name
        $this->db->from($this->db->dbprefix('leads') . ' l');

        // Join with lead_sources table to get source names
        $this->db->join($this->db->dbprefix('leads_sources') . ' ls', 'ls.id = l.source', 'right');

        // Left join for proposals
        $this->db->join("
            (SELECT assigned,
                COUNT(CASE WHEN status = 6 THEN 1 END) AS quotes_sent,
                COUNT(CASE WHEN status = 3 THEN 1 END) AS quotes_signed
            FROM " . $this->db->dbprefix('proposals') . "
            GROUP BY assigned
            ) pro", 'pro.assigned = l.source', 'left');

                    // Left join for appointments
        $this->db->join("(SELECT email, COUNT(email) AS total_appointments,
                            SUM( CASE WHEN STR_TO_DATE( CONCAT(DATE, ' ', start_hour),
                                '%Y-%m-%d %H:%i:%s') > NOW() THEN 0 ELSE 1
                                END) AS appointments_missed
                        FROM tblappointly_appointments
                        WHERE cancelled = 0
                        GROUP BY email
                    ) a", 'a.email = l.email', 'left');

        // Apply date range filter if provided
        if (!is_null($startDate) && !is_null($endDate)) {
            $this->db->where("DATE(l.dateadded) BETWEEN DATE('" . $this->db->escape_str($startDate) . "') AND DATE('" . $this->db->escape_str($endDate) . "')");
         }

        // Apply last_action filter if provided and not empty
        if (!is_null($last_action) && $last_action !== '') {
            $this->db->where("DATE(l.lastcontact) = DATE('". $this->db->escape_str($last_action) ." 00:00')");
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
        $this->db->from('tblleads l');
        $this->db->join('tblstaff s', 's.staffid = l.assigned', 'left');

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
            $this->db->where("DATE(l.lastcontact) = DATE('". $last_action ." 00:00')");
        }

        // Apply status filter if provided and not empty
        if (!empty($status)) {
            $this->db->where_in('l.status', $status);
        }
         
        // Apply status filter if provided and not empty
        if (!empty($source)) {
            $this->db->where_in('l.source', $source);
        }

        // Apply staff filter if provided and not empty
        if (!empty($staff)) {
            $this->db->where_in('l.assigned', $staff);
        }

        // Group by clause
        $this->db->group_by('s.staffid');

        // Order by clause
        $this->db->order_by('avg_conversion_time ASC, conversion_rate DESC, total_leads DESC');

        // Execute the query
        $query = $this->db->get();
        return $query->result_array();
    }

    
    /**
     * Get leads summary
     * @return array
     */
    function get_leads_summary($startDate = null, $endDate = null, $last_action = null, $source = [], $status = [], $staff = [])
    {
        $CI = &get_instance();
        if (!class_exists('leads_model')) {
            $CI->load->model('leads_model');
        }
        $statuses = $CI->leads_model->get_status();

        $totalStatuses         = count($statuses);
        $has_permission_view   = staff_can('view',  'leads');
        $sql                   = '';
        $whereNoViewPermission = '(addedfrom = ' . get_staff_user_id() . ' OR assigned=' . get_staff_user_id() . ' OR is_public = 1)';

        $statuses[] = [
            'lost'  => true,
            'name'  => _l('lost_leads'),
            'color' => '#fc2d42',
        ];

        /*    $statuses[] = [
            'junk'  => true,
            'name'  => _l('junk_leads'),
            'color' => '',
        ];*/

        $sql_internal="SELECT l.* FROM " . db_prefix() . 'leads l WHERE 1=1';
             // Apply date range filters if provided
             if (!is_null($startDate) && !is_null($endDate)) {
                $sql_internal .= " AND DATE(l.dateAdded) >= DATE('" . $this->db->escape_str($startDate) . "')";
                $sql_internal .= " AND DATE(l.dateAdded) <= DATE('" . $this->db->escape_str($endDate) . "')";
            }
    
            // Apply last_action filter if provided
            if (!is_null($last_action) && $last_action!='') {
                $sql_internal .= " AND DATE(l.lastContact) = DATE('" . $this->db->escape_str($last_action) . "')";
            }
    
            // Apply source filter if provided and not empty
            if (!empty($source)) {
                
                $source_ids = implode(",",  $source);
                $sql_internal .= " AND l.source IN (" . $source_ids . ")";
            }
    
            // Apply status filter if provided and not empty
            if (!empty($status)) {
                $status_ids = implode(",",  $status);
                $sql_internal .= " AND l.status IN (" . $status_ids . ")";
            }
    
            // Apply staff filter if provided and not empty
            if (!empty($staff)) {
                $staff_ids = implode(",",  $staff);
                $sql_internal .= " AND l.assigned IN (" . $staff_ids . ")";
            }
    

        foreach ($statuses as $status) {
            $sql .= ' SELECT COUNT(*) as total';
            $sql .= ',SUM(lead_value) as value';
            $sql .= ' FROM (' . $sql_internal .') a';

            if (isset($status['lost'])) {
                $sql .= ' WHERE a.lost=1';
            } elseif (isset($status['junk'])) {
                $sql .= ' WHERE a.junk=1';
            } else {
                $sql .= ' WHERE a.status=' . $status['id'];
            }
            if (!$has_permission_view) {
                $sql .= ' AND ' . $whereNoViewPermission;
            }
            
          
            $sql .= ' UNION ALL ';
            $sql = trim($sql);
        }

        $result = [];

        // Remove the last UNION ALL
        $sql    = substr($sql, 0, -10);
        $result = $CI->db->query($sql)->result();

        if (!$has_permission_view) {
            $CI->db->where($whereNoViewPermission);
        }

        $total_leads = $CI->db->count_all_results(db_prefix() . 'leads');

        foreach ($statuses as $key => $status) {
            if (isset($status['lost']) || isset($status['junk'])) {
                $statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($result[$key]->total * 100) / $total_leads, 2) : 0);
            }

            $statuses[$key]['total'] = $result[$key]->total;
            $statuses[$key]['value'] = $result[$key]->value;
        }

        return $statuses;
    }
}
