<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <!-- Search bar and filters -->
        <div class="clearfix"></div>

        <div class="_buttons">
            <div class="row">
                <!-- Search Bar -->
                <div class="col-md-4">
                    <!-- Optionally add a button or functionality here -->
                </div>

                <!-- Filters -->
                <div class="col-md-8" style="display:flex;justify-content:end">
                    <form method="GET" action="<?php echo site_url('prospect_alerts'); ?>" style="margin-right: 10px;">
                        <div class="input-group" style="width:200px">
                            <input type="text" name="search" class="form-control"
                                placeholder="<?php echo _l('Search Prospect Alerts'); ?>"
                                value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <form method="GET" action="<?php echo site_url('prospect_alerts'); ?>">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <?php echo _l('Filter By'); ?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li><a
                                        href="<?php echo site_url('prospect_alerts?filter=all'); ?>"><?php echo _l('All'); ?></a>
                                </li>
                                <li><a
                                        href="<?php echo site_url('prospect_alerts?filter=active'); ?>"><?php echo _l('Active'); ?></a>
                                </li>
                                <li><a
                                        href="<?php echo site_url('prospect_alerts?filter=inactive'); ?>"><?php echo _l('Inactive'); ?></a>
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
            <a href="<?php echo site_url('prospect_alerts/create'); ?>"
                class="tw-mb-3 mleft15 btn btn-primary pull-left display-block ">
                <i class="fa-regular fa-plus tw-mr-1"></i>
                <?php echo _l('New Prospect Alert'); ?>
            </a>
        </div>


        <!-- Prospect Table -->
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <?php if (!empty($alerts)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered dt-table nowrap" id="purchased-prospects">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('id'); ?></th>
                                        <th><?php echo _l('name'); ?></th>
                                        <th><?php echo _l('leadevo_alerts_category'); ?></th>
                                        <th><?php echo _l('leadevo_alerts_type'); ?></th>
                                        <th><?php echo _l('settings_email'); ?></th>
                                        <th><?php echo _l('settings_sales_phonenumber'); ?></th>
                                        <th><?php echo _l('ticket_dt_status'); ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alerts as $alert): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($alert['id']); ?></td>
                                            <td><?php echo htmlspecialchars($alert['name'] ?? ''); ?>
                                                <div class="row-options">

                                                    <a href="<?php echo site_url('prospect_alerts/edit/' . $alert['id']); ?>"
                                                        class="">
                                                        Edit
                                                    </a> |
                                                    <a href="<?php echo site_url('prospect_alerts//delete/' . $alert['id']); ?>"
                                                        class="text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                        Delete
                                                    </a>
                                                    <?php if ($alert['status'] == 0) { ?>
                                                        | <a href="<?php echo site_url('prospect_alerts/activate/' . $alert['id']); ?>"
                                                            class=""
                                                            onclick="return confirm('Are you sure you want to activate this alert?');">
                                                            Activate
                                                        </a>
                                                    <?php } else if ($alert['status'] == 1) { ?>
                                                            | <a href="<?php echo site_url('prospect_alerts/deactivate/' . $alert['id']); ?>"
                                                                class="text-danger"
                                                                onclick="return confirm('Are you sure you want to deactivate this alert?');">
                                                                Deactivate
                                                            </a>
                                                    <?php } ?>
                                                </div>
                                            </td>

                                            <td><?php echo htmlspecialchars($alert['prospect_category'] ?? '-'); ?></td>
                                            <td><?php echo $alert['is_exclusive'] == 1 ? 'Exclusive' : 'Non-Exclusive' ?></td>
                                            <td><?php echo htmlspecialchars($alert['email'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($alert['phone'] ?? ''); ?></td>
                                            <td><?php echo $alert['status'] ? _l('Active') : _l('Inactive'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p><?php echo _l('No prospects found.'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#purchased-prospects').DataTable();
</script>