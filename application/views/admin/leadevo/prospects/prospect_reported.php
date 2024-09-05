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
                            <form method="GET" action="<?php echo admin_url('prospects/reported'); ?>"
                                style="margin-right: 10px;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <?php echo _l('Filter By'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li>
                                            <a href="<?php echo admin_url('prospects/reported'); ?>"
                                                class="<?php echo (!isset($_GET['filter']) || empty($_GET['filter']) ? 'active' : ''); ?>">
                                                <?php echo _l('all'); ?>
                                            </a>
                                        </li>
                                        <?php foreach ($status_options as $option): ?>
                                            <li>
                                                <a href="<?php echo admin_url('prospects/reported?filter=' . urlencode($option['status'])); ?>"
                                                    class="<?php echo (isset($_GET['filter']) && $_GET['filter'] == urlencode($option['status']) ? 'active' : ''); ?>">
                                                    <?php echo htmlspecialchars($option['status']); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
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
                                <th><?php echo _l('Status'); ?></th>
                                <th><?php echo _l('Feedback'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reported_prospects as $prospect): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prospect['prospect_id'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($prospect['reason_name'] ?? 'N/A'); ?>
                                        <div class="row-options">
                                            <a href="<?php echo admin_url('prospects/view_reported/' . $prospect['prospect_id']); ?>" class="">
                                                View |
                                            </a>
                                            <a href="#" onclick="openReplaceModal(<?= $prospect['prospect_id'] ?>, <?= $prospect['campaign_id'] ?>)" class="">
                                                Replace |
                                            </a>
                                            <?php if (strtolower($prospect['status_name']) != 'rejected'):?>
                                                <a href="#" onclick="openRejectModal(<?= $prospect['prospect_id'] ?>, <?= $prospect['campaign_id'] ?>)" class="">
                                                    Reject
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($prospect['created_at'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if (isset($prospect['evidence']) && !empty($prospect['evidence'])): ?>
                                            <audio controls>
                                                <source src="<?php echo htmlspecialchars($prospect['evidence']); ?>" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        <?php else: ?>
                                            No evidence available.
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($prospect['status_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($prospect['feedback'] ?? 'N/A'); ?></td>
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


<div id="replace_prospect_modal" class="modal fade" tabindex="-1" role="dialog">
    <?php echo get_instance()->load->view('admin/leadevo/prospects/modals/replace_reported.php') ?>
</div>

<div id="reject_prospect_modal" class="modal fade" tabindex="-1" role="dialog">
    <?php echo get_instance()->load->view('admin/leadevo/prospects/modals/reject_reported.php') ?>
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

    function openRejectModal(id, campaign_id) {
        console.log(id)
        document.querySelector('#reject_prospect_modal input[name=id]').value = id;
        document.querySelector('#reject_prospect_modal input[name=campaign_id]').value = campaign_id;
        $('#reject_prospect_modal').modal('show');
    }
</script>