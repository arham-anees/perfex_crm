<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4><?php echo _l('crm_link_details'); ?></h4>
                <table class="table table-bordered">
                    <tr>
                        <th><?php echo _l('id'); ?></th>
                        <td><?= $link['id']; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo _l('name'); ?></th>
                        <td><?= $link['name']; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo _l('description'); ?></th>
                        <td><?= $link['description']; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo _l('link'); ?></th>
                        <td><?= $link['links']; ?></td>
                    </tr>
                </table>
                <a href="<?php echo site_url('crm'); ?>" class="btn btn-default"><?php echo _l('back_to_list'); ?></a>
            </div>
        </div>
    </div>
</div>
