<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leadevo_prospect_alert extends App_mail_template
{
    protected $for = 'staff';
    public $email;
    public $slug = 'leadevo-prospect-alert';

    public function __construct($data)
    {
        parent::__construct();
        // Ensure $data is an object and has the properties we're trying to access
        if (is_object($data) && isset($data->email) && isset($data->name)) {
            $this->email = $data->email;
            $this->set_merge_fields('leadevo_merge_fields', $data->name);
        } else {
            // Handle the case where $data does not have the expected structure
            // For example, you might want to log this error or throw an exception
            log_message('error', 'Invalid data structure for Leadevo_prospect_alert');
        }
    }

    public function build()
    {
        $this->to($this->email);
    }
}
