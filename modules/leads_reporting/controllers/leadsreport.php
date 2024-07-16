<?php defined('BASEPATH') or exit('No direct script access allowed');

class LeadsReport extends AdminController
{
    private $staff_no_view_permissions;

    public function __construct()
    {
        parent::__construct();

         // Load the leads_report_model
         $this->load->model('leads_report_model');
         $this->load->model('staff_model');
         $this->load->model('appointments_status_model');


        $this->staff_no_view_permissions = !staff_can('view', 'leads_report');

    }
    public function index()
    {
        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
        } else {
            $end_date =  date('Y-m-d');

            // Subtract 30 days from today's date
            $thirtyDaysAgo = (new DateTime())->sub(new DateInterval('P30D'));

            // Format the date as YYYY-MM-DD
            $start_date = $thirtyDaysAgo->format('Y-m-d');
        }
        if(isset($_GET['last_action_date'])){
            $last_action_date=$_GET['last_action_date'];
        }else{
            $last_action_date=null;
        }
        $att = isset($_GET['attendees']) ? $_GET['attendees'] : [];
        $selected_statuses = isset($_GET['selected_statuses']) ? $_GET['selected_statuses'] : [];
        $selected_sources = isset($_GET['selected_sources']) ? $_GET['selected_sources'] : [];
        $attendees = $att; //implode(',',$att);


        $data['leads'] = $this->leads_report_model->get_all_leads();//$start_date, $end_date, $last_action_date, $selected_sources, $attendees, $selected_statuses);
        // $data['staff'] = $this->leads_report_model->get_all_staff();
        $data['leads_per_agent'] = $this->leads_report_model->get_leads_assigned_per_agent($start_date, $end_date, $last_action_date, $selected_sources, $attendees, $selected_statuses);
        $data['leads_created_per_agent'] = $this->leads_report_model->get_leads_created_per_agent($start_date, $end_date, $last_action_date, $selected_sources, $attendees, $selected_statuses);
        // $data['leads_created_per_agent'] = $this->leads_report_model->get_leads_created_per_agent();
        $data['conversion_rate'] = $this->leads_report_model->get_conversion_rate($start_date, $end_date, $last_action_date, $selected_sources, $selected_statuses, $attendees);
        $data['average_time_spent_per_prospect'] = $this->leads_report_model->get_average_time_spent_per_prospect($start_date, $end_date, $last_action_date, $selected_sources, $selected_statuses, $attendees);
        // $data['follow_up_rate'] = $this->leads_report_model->get_follow_up_rate();
        // $data['appointments_set'] = $this->leads_report_model->get_appointments_set();
        $data['prospect_attrition_rate'] = $this->leads_report_model->get_prospect_attrition_rate($start_date, $end_date);
        $data['average_value_of_won_prospects'] = $this->leads_report_model->get_average_value_of_won_prospects($start_date, $end_date, $last_action_date, $selected_sources, $selected_statuses, $attendees);
        $data['average_sales_cycle_length'] = $this->leads_report_model->calculate_average_sales_cycle();
        $data['lead_source_effectiveness'] = $this->leads_report_model->get_lead_source_effectiveness($start_date, $end_date);
        $data['agent_effectiveness'] = $this->leads_report_model->get_agent_effectiveness_report($start_date, $end_date, $last_action_date, $selected_sources, $selected_statuses, $attendees);
        
        

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['last_action_date'] = $last_action_date;
        $data['staff'] = $this->staff_model->get('', ['active' => 1]);
        $data['statuses'] = $this->appointments_status_model->get_all();
        $data['attendees'] =$attendees;
        $data['selected_statuses'] = $selected_statuses;
        $data['selected_sources'] = $selected_sources;

        $this->load->view('index', $data);

    }

    public function report()
    {
        // if ($this->staff_no_view_permissions) {
        //     access_denied('leads_report');
        // }

        $this->load->view('index');
    }

}