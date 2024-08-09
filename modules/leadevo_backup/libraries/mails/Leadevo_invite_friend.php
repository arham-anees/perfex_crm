<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leadevo_invite_friend extends App_mail_template
{

    protected $for = 'staff';

    public $email;

    public $slug = 'leadevo-friend-invitation';

    public function __construct($data)
    {
        parent::__construct();

        $this->email = $data->email;
        // if(isset($this->email)){
            $this->set_merge_fields('leadevo_merge_fields', $data->name);
        // }

    }

    public function build()
    {
        $this->to($this->email);
    }
}
