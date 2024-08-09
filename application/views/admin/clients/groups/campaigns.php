<?php defined('BASEPATH') or exit('No direct script access allowed');
if (isset($client)) { ?>
    <?php if (staff_can('edit', 'campaigns')) { ?>
        <div class="row" id="campaigns_wrapper">

            <?php if (!empty($campaigns)): ?>
                <table class="table dt-table scroll-responsive">
                    <thead>
                        <tr>
                            <th><?php echo _l('name'); ?></th>
                            <th><?php echo _l('leadevo_description'); ?></th>
                            <th><?php echo _l('leadevo_isactive'); ?></th>
                            <th><?php echo _l('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($campaigns as $campaign): ?>
                            <tr>
                                <td><?php echo $campaign->name; ?></td>
                                <td><?php echo $campaign->description; ?></td>
                                <td><?php echo $campaign->is_active ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <a href="<?php echo admin_url('campaigns/view/' . $campaign->id); ?>"
                                        class="btn btn-default btn-icon">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php echo _l('leadevo_no_campaigns'); ?></p>
            <?php endif; ?>

        </div>
    <?php } ?>
<?php
}
