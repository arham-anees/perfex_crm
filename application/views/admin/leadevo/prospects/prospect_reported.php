<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
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
                            <form method="GET" action="<?php echo site_url('prospects/reported'); ?>"
                                style="margin-right: 10px;">
                                <div class="input-group" style="width:200px">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="<?php echo _l('Search Reported Prospects'); ?>"
                                        value="<?php echo isset($search) ? $search : ''; ?>">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                            <form method="GET" action="<?php echo site_url('prospects/reported'); ?>">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown">
                                        <?php echo _l('Filter By'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li><a
                                                href="<?php echo site_url('prospects/reported?filter=active'); ?>"><?php echo _l('Active Reported Prospects'); ?></a>
                                        </li>
                                        <li><a
                                                href="<?php echo site_url('prospects/reported?filter=inactive'); ?>"><?php echo _l('Inactive Reported Prospects'); ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reported Prospects Table -->
                <div class="col-md-12">
                    <div class="panel_s">
                        <div class="panel-body">
                            <?php if (!empty($reported_prospects)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered dt-table nowrap" id="reported-prospects">
                                        <thead>
                                            <tr>
                                                <th><?php echo _l('Prospect'); ?></th>
                                                <th><?php echo _l('Reason'); ?></th>
                                                <th><?php echo _l('Created At'); ?></th>
                                                <th><?php echo _l('Evidence'); ?></th>
                                                <th><?php echo _l('Actions'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reported_prospects as $prospect): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($prospect['prospect_id'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($prospect['reason_name'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($prospect['created_at'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($prospect['evidence'] ?? ''); ?></td>
                                                    <td>
                                                        <a href="<?php echo admin_url('prospects/view/' . $prospect['prospect_id']); ?>"
                                                            class="">
                                                            View
                                                        </a>
                                                        <a href="#"
                                                            onclick="openReplaceModal(<?= $prospect['prospect_id'] ?>, <?= $prospect['campaign_id'] ?>)"
                                                            class="">
                                                            Replace
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p><?php echo _l('No reported prospects found.'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="replace_prospect_modal" class="modal fade" tabindex="-1" role="dialog">
    <?php echo get_instance()->load->view('admin/leadevo/prospects/modals/replace_reported.php') ?>
</div>

<?php init_tail(); ?>
<script>
    $(document).ready(function () {
        // Check if DataTable is already initialized before initializing
        if (!$.fn.DataTable.isDataTable('#reported-prospects')) {
            $('#reported-prospects').DataTable();
        }

        setTimeout(() => {
            $('#reported-prospects_wrapper').removeClass('table-loading');
        }, 100);

    });
    function openReplaceModal(id, campaign_id) {
        console.log(id)
        document.querySelector('#replace_prospect_modal input[name=id]').value = id;
        document.querySelector('#replace_prospect_modal input[name=campaign_id]').value = campaign_id;
        $('#replace_prospect_modal').modal('show');
    }
</script>