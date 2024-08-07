<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Start of panel body -->
                        <div class="_buttons">
                            <div class="row">
                                <!-- Search Bar -->
                                <div class="col-md-6">
                                    <form method="GET" action="<?php echo admin_url('leadevo/client/prospectAlert'); ?>">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="<?php echo _l('Search Prospect Alerts'); ?>" value="<?php echo isset($search) ? $search : ''; ?>">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                                <!-- Filters -->
                                <div class="col-md-6 text-right">
                                    <form method="GET" action="<?php echo admin_url('leadevo/client/prospectAlert'); ?>">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <?php echo _l('Filter By'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <!-- Active Prospect Alerts -->
                                                <li>
                                                    <a href="<?php echo admin_url('leadevo/client/prospectAlert?filter=active&search=' . urlencode($search ?? '')); ?>">
                                                        <?php echo _l('Active Prospect Alerts'); ?>
                                                    </a>
                                                </li>
                                                <!-- Inactive Prospect Alerts -->
                                                <li>
                                                    <a href="<?php echo admin_url('leadevo/client/prospectAlert?filter=inactive&search=' . urlencode($search ?? '')); ?>">
                                                        <?php echo _l('Inactive Prospect Alerts'); ?>
                                                    </a>
                                                </li>
                                                <!-- All Prospect Alerts -->
                                                <li>
                                                    <a href="<?php echo admin_url('leadevo/client/prospectAlert?filter=all&search=' . urlencode($search ?? '')); ?>">
                                                        <?php echo _l('All Prospect Alerts'); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/client/prospectAlert/create'); ?>" class="tw-mb-3 mleft15 btn btn-primary pull-left display-block ">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Prospect Alert'); ?>
                            </a>
                        </div>

                        <!-- Prospect alerts Table -->


                        <div class="col-md-12">
                            <div class="panel_s">
                                <div class="panel-body">
                                    <?php if (!empty($prospect_alerts)) : ?>
                                        <table class="table dt-table scroll-responsive">
                                            <thead>
                                                <tr>
                                                    <th><?php echo _l('Alert Name'); ?></th>
                                                    <th><?php echo _l('Prospect Category'); ?></th>
                                                    <th><?php echo _l('Email Notification'); ?></th>
                                                    <th><?php echo _l('Phone'); ?></th>
                                                    <th><?php echo _l('Status'); ?></th>
                                                    <th><?php echo _l('Actions'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($prospect_alerts as $prospect_alert) : ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($prospect_alert['alert_name'] ?? ''); ?></td>
                          

                                                        <td><?php echo htmlspecialchars($prospect_alert['prospect_category'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($prospect_alert['email'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($prospect_alert['phone'] ?? ''); ?></td>
                                                        <td>
                                                            <a href="<?php echo admin_url('leadevo/client/prospectAlert/toggleStatus/' . $prospect_alert['id']); ?>" class="status-toggle">
                                                                <?php echo htmlspecialchars($prospect_alert['status']) ? '<i class="fas fa-toggle-on fa-2x"></i>' : '<i class="fas fa-toggle-off fa-2x"></i>'; ?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo admin_url('leadevo/client/prospectAlert/edit/' . $prospect_alert['id']); ?>" class="btn btn-default btn-icon">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a href="<?php echo admin_url('leadevo/client/prospectAlert/delete/' . $prospect_alert['id']); ?>" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else : ?>
                                        <p><?php echo _l('No prospects found.'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- End of panel body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
