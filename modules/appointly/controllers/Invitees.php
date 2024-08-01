<?php defined('BASEPATH') or exit('No direct script access allowed');

class Invitees extends AdminController
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
            'dates' => ['Saturday, July 27, 2024'],
            'hashes'=>[['hash'=>
            '']],
            'timezone' => 'Pakistan, Maldives Time',
            'appointment'=>['name'=>'test', 'subject'=>'test subject','dates' => ['Saturday, July 27, 2024'],'hashes'=>['']]
        ];

        $this->load->view('forms/invitees', $data);
    }
}
