<?php defined('BASEPATH') or exit('No direct script access allowed');

class calender extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($username = '')
    {
        $data = [
            'eventName' => 'Test event',
            'organizer' => 'Arham Anees',
            'startTime' => '12:30am',
            'endTime' => '1:00am',
            'date' => 'Saturday, July 27, 2024',
            'timezone' => 'Pakistan, Maldives Time',
        ];

        $this->load->view('forms/calender', $data);
    }
}
