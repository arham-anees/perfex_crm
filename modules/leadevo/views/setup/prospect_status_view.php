<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <a href="<?php echo admin_url('leadEvo/prospectstatus/create'); ?>" class="btn btn-primary pull-left display-block">
                        <?php echo _l('new_prospect_status'); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Prospect Statuses'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($statuses)) : ?>
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Description'); ?></th>
                                        <th><?php echo _l('Active'); ?></th>
                                        <th><?php echo _l('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($statuses as $status) : ?>
                                        <tr>
                                            <td><?php echo $status->name; ?></td>
                                            <td><?php echo $status->description; ?></td>
                                            <td><?php echo $status->is_active ? 'Yes' : 'No'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('leadEvo/prospectstatus/edit/' . $status->id); ?>" class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadEvo/prospectstatus/delete/' . $status->id); ?>" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this prospect status?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p><?php echo _l('No prospect statuses found.'); ?></p>
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
