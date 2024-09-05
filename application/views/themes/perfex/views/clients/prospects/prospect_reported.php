<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
     .filters {
        background-color: rgb(255, 255, 255);
        color: rgba(0, 0, 0, 0.87);
        box-shadow: rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.14) 0px 2px 2px 0px, rgba(0, 0, 0, 0.12) 0px 1px 5px 0px;
        position: sticky;
        z-index: 1;
        top: 5%;
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px;
        padding: 10px 16px 18px;
        margin: 20px 0;

    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .lead-card {
        display: flex;
        background-color: rgb(240, 240, 241);
        color: rgba(0, 0, 0, 0.87);
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
        border-radius: 20px;
        overflow: hidden;
        padding: 16px;
        margin: 10px 0;
    }

    .fullscreenBtn {
        padding: 5px 10px !important;
        font-size: 1.2rem !important;
    }
    ._buttons a {
    margin-left: 0px !important;
    /* padding: 0; */
    margin-bottom: 10px;
}

</style>
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
            </div>
        </div>

        <!-- Reported Prospects Table -->
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <form id="filterForm" action="" method="post">
                            <?php $csrf = $this->security->get_csrf_hash(); ?>
                            <div class="row">
                             
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="start_date"><?php echo _l('From'); ?></label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value=" <?= !empty($_POST['start_date'])?date('d-m-Y', strtotime($_POST['start_date'])):''?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="end_date"><?php echo _l('To'); ?></label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value=" <?=!empty($this->input->post('end_date')) ? $this->input->post('end_date '):''?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="type"><?php echo _l('Status'); ?></label>
                                        <select id="type" name="status" class="filter-input">
                                          <option value="">Select Status</option>
                                           <?php foreach ($status_options as $status): ?>
                                                <option value="<?php echo $status['id'] ?>" <?=$this->input->post('status')==$status['id'] ?'selected':''?>><?php echo $status['status'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                
                                <div class="col-md-4">
                                    <!-- <button class="btn regular_price_btn">
                                        <div class="button-content">
                                            <i class="fa fa-shopping-cart"></i>
                                            <div class="text-container">
                                                <span class="bold-text">$345-$563 Buy lead</span>
                                                <span class="small-text">regular price</span>
                                            </div>
                                        </div>
                                    </button> -->
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                                </div>

                         
                            <div style="height:20px">
                                <input type="submit" value="Apply Filters" class="btn btn-info pull-right">
                            </div>
                    </form>
                    <hr class="hr-panel-heading" style="margin: 1.25rem 0" />
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
                                                <div class="row-options"><a
                                                        href="<?php echo site_url('prospects/view_reported/' . $prospect['prospect_id']); ?>"
                                                        class="">
                                                        View
                                                    </a></div>
                                            </td>
                                            <td><?php echo htmlspecialchars($prospect['created_at'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php if (isset($prospect['evidence']) && !empty($prospect['evidence'])): ?>
                                                    <audio controls>
                                                        <source src="<?php echo htmlspecialchars($prospect['evidence']); ?>"
                                                            type="audio/mpeg">
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
    </div>
</div>
<script>
    $('#reported-prospects').DataTable();
</script>