<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leadevo_merge_fields extends App_merge_fields
{

    public function build()
    {
        return [
            [
                'name'      => 'Name',
                'key'       => '{name}',
                'available' => [
                    'leadevo',
                ],
            ]
        ];
    }

    /**
     * Merge field for appointments
     *
     * @param mixed $appointment_id
     *
     * @return array
     */
    public function format($data)
    {
        $fields = [];

        $fields['{name}'] = $data;
      
        return $fields;
    }

}
