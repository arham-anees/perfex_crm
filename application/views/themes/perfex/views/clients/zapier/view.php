<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4><?php echo _l('leadevo_zapier_webhook_details'); ?></h4>
                <table class="table table-bordered">
                    <tr>
                        <th><?php echo _l('id'); ?></th>
                        <td><?php echo htmlspecialchars($webhook->id); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo _l('name'); ?></th>
                        <td><?php echo htmlspecialchars($webhook->name ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo _l('leadevo_description'); ?></th>
                        <td><?php echo htmlspecialchars($webhook->description ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo _l('leadevo_zapier_webhook'); ?></th>
                        <td><?php echo htmlspecialchars($webhook->webhook ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo _l('Status'); ?></th>
                        <td><?php echo $webhook->status == 1 ? 'Active' : 'Inactive'; ?></td>
                    </tr>
                </table>
                <a href="<?php echo site_url('clients/zapier'); ?>" class="btn btn-default"><?php echo _l('back_to_list'); ?></a>
            </div>
        </div>
    </div>
</div>
