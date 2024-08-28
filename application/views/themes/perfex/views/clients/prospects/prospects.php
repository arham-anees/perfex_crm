<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <!-- Search bar and filters -->
        <div class="clearfix"></div>

        <div class="_buttons">
            <div class="row">
                <!-- Search Bar -->
                <div class="col-md-4">
                    <a href="<?php echo site_url('prospects/create'); ?>"
                        class="tw-mb-3 mleft15 btn btn-primary pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('New Prospect'); ?>
                    </a>
                </div>

                <!-- Filters -->
                <div class="col-md-8" style="display:flex;justify-content:end">
                    <form method="GET" action="<?php echo site_url('prospects'); ?>" style="margin-right: 10px;">
                        <div class="input-group" style="width:200px">
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
                    <form method="GET" action="<?php echo site_url('prospects'); ?>">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <?php echo _l('Filter By'); ?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li><a
                                        href="<?php echo site_url('prospects?filter=active'); ?>"><?php echo _l('Active Prospects'); ?></a>
                                </li>
                                <li><a
                                        href="<?php echo site_url('prospects?filter=inactive'); ?>"><?php echo _l('Inactive Prospects'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Prospect Table -->
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <?php if (!empty($prospects)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered dt-table nowrap" id="purchased-prospects">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Status'); ?></th>
                                        <th><?php echo _l('Type'); ?></th>
                                        <th><?php echo _l('Category'); ?></th>
                                        <th><?php echo _l('Acquisition Channels'); ?></th>
                                        <th><?php echo _l('Desired Amount'); ?></th>
                                        <th><?php echo _l('Industry'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($prospects as $prospect): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? ''); ?>
                                                <div class="row-options">
                                                    <a href="<?php echo site_url('prospects/prospect/' . $prospect['id']); ?>"
                                                        class="">
                                                        View
                                                    </a> |
                                                    <a href="<?php echo site_url('prospects/edit/' . $prospect['id']); ?>"
                                                        class="">
                                                        Edit
                                                    </a> |
                                                    <a href="<?php echo site_url('prospects/delete/' . $prospect['id']); ?>"
                                                        class=""
                                                        onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                        Delete
                                                    </a>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($prospect['status'] ?? ''); ?></td>
                                            
                                            <td><?php echo htmlspecialchars($prospect['type'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect['category'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect['desired_amount'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect['industry'] ?? ''); ?></td>
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