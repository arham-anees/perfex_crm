<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Appointly_appointment_new_appointment_submitted extends App_mail_template
{

    protected $staff;
    protected $for = 'staff';

     protected $appointment;

     public $slug = 'appointment-submitted-to-staff';

     public function __construct($staff, $appointment)
     {
          parent::__construct();

          $this->staff = $staff;
          $this->appointment = $appointment;

          // For SMS and merge fields for email
          if(isset($this->staff->staffid)){
               $this->set_merge_fields('staff_merge_fields', $this->staff->staffid);
          }
          $this->set_merge_fields('appointly_merge_fields', $this->appointment->id);
     }
     public function build()
     {
          if(isset($this->staff->email)){
          $this->to($this->staff->email);
          }
     }
}
