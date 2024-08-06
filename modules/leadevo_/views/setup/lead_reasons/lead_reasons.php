<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/lead_reasons/create'); ?>" class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Lead Reason'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($reasons)) : ?>
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
                                    <?php foreach ($reasons as $reason) : ?>
                                        <tr>
                                            <td><?php echo $reason->name; ?></td>
                                            <td><?php echo $reason->description; ?></td>
                                            <td><?php echo $reason->is_active ? 'Yes' : 'No'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('leadevo/lead_reasons/view/' . $reason->id); ?>" class="btn btn-default btn-icon">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/lead_reasons/edit/' . $reason->id); ?>" class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/lead_reasons/delete/' . $reason->id); ?>" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this lead reason?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p><?php echo _l('No lead reasons found.'); ?></p>
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
