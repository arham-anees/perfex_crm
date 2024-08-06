<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Start of panel body -->

                <!-- Search bar and filters -->
                <div class="_buttons">
                    <div class="row">
                        <!-- Search Bar -->
                        <div class="col-md-6">
                            <form method="GET" action="<?php echo admin_url('leadevo/prospects'); ?>">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="<?php echo _l('Search Prospects'); ?>"
                                        value="<?php echo isset($search) ? $search : ''; ?>">
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
                            <form method="GET" action="<?php echo admin_url('leadevo/prospects'); ?>">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown">
                                        <?php echo _l('Filter By'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li><a
                                                href="<?php echo admin_url('leadevo/prospects?filter=active'); ?>"><?php echo _l('Active Prospects'); ?></a>
                                        </li>
                                        <li><a
                                                href="<?php echo admin_url('leadevo/prospects?filter=inactive'); ?>"><?php echo _l('Inactive Prospects'); ?></a>
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
                    <a href="<?php echo admin_url('leadevo/client/prospect/create'); ?>"
                        class="tw-mb-3 mleft15 btn btn-primary pull-left display-block ">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('New Prospect'); ?>
                    </a>
                </div>
                <!-- Prospect Table -->
                <div class="col-md-12">
                    <div class="panel_s">
                        <div class="panel-body">
                            <?php if (!empty($prospects)): ?>
                                <table class="table dt-table scroll-responsive">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Prospect Name'); ?></th>
                                            <th><?php echo _l('Status'); ?></th>
                                            <th><?php echo _l('Type'); ?></th>
                                            <th><?php echo _l('Category'); ?></th>
                                            <th><?php echo _l('Acquisition Channel'); ?></th>
                                            <th><?php echo _l('Industry'); ?></th>
                                            <th><?php echo _l('Actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prospects as $prospect): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['status'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['type'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['category'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['industry'] ?? ''); ?></td>

                                                <td>
                                                    <a href="<?php echo admin_url('leadevo/client/prospect/view/' . $prospect['id']); ?>"
                                                        class="btn btn-default btn-icon">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('leadevo/client/prospect/edit/' . $prospect['id']); ?>"
                                                        class="btn btn-default btn-icon">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('leadevo/client/prospect/delete/' . $prospect['id']); ?>"
                                                        class="btn btn-danger btn-icon"
                                                        onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
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