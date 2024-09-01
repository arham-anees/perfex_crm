<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/campaign_statuses/create'); ?>"
                                class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Campaign Status'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($statuses)): ?>
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Description'); ?></th>
                                        <th><?php echo _l('Status'); ?></th>
                                        <th><?php echo _l('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($statuses as $status): ?>
                                        <tr>
                                            <td><?php echo $status->name; ?></td>
                                            <td><?php echo $status->description; ?></td>
                                            <td><?php echo $status->is_active==1?'Active':'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('leadevo/campaign_statuses/view/' . $status->id); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/campaign_statuses/edit/' . $status->id); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/campaign_statuses/delete/' . $status->id); ?>"
                                                    class="btn btn-danger btn-icon"
                                                    onclick="return confirm('Are you sure you want to delete this campaign status?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p><?php echo _l('No campaign statuses found.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>