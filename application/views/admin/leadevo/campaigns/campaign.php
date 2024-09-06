<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
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
<div id="wrapper">
  <div class="content">
    <div class="row main_row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <form id="filterForm" action="" method="post">
            <?php $csrf = $this->security->get_csrf_hash(); ?>
            <div class="row">
              <div class="col-md-4">
                  <div class="filter-group">
                      <label for="budget_range_start"><?php echo _l('Budget Range start'); ?></label>
                      <input type="text" id="budget_range_start" name="budget_range_start" class="filter-input"  value="<?= !empty($_POST['budget_range_start']) ? $_POST['budget_range_start']:''?>">
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="budget_range_end"><?php echo _l('Budget Range end'); ?></label>
                        <input type="text" id="budget_range_end" name="budget_range_end" class="filter-input" value="<?= !empty($_POST['budget_range_end']) ? $_POST['budget_range_end']:''?>">
                    </div>
                </div>
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
                           <?php foreach ($statuses as $status){
                              // var_dump($status->name);
                              // exit;
                            ?>
                                <option value="<?php echo $status->id; ?>" <?php echo isset($_POST['status'])&& $_POST['status']==$status->id ?'selected':''?>><?php echo $status->name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="deal"><?php echo _l('Deal'); ?></label>
                        <select id="deal" name="deal" class="filter-input">
                          <option value="">Select Deal</option>
                              <option value="1" <?=$this->input->post('deal')=='1' ?'selected':''?>>Exclusive
                              </option>
                                <option value="0" <?=$this->input->post('deal')=='0' ?'selected':''?>>Non-Exclusive
                              </option>
                           
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
        <hr class="hr-panel-heading" />
            <div class="_buttons">

              <?php if (!empty($campaigns)): ?>
                <table class="table dt-table scroll-responsive">
                  <thead>
                    <tr>
                      <th><?php echo _l('id'); ?></th>
                      <th><?php echo _l('name'); ?></th>
                      <th><?php echo _l('description'); ?></th>
                      <th><?php echo _l('status'); ?></th>
                      <th><?php echo _l('budget'); ?></th>
                      <th><?php echo _l('deal'); ?></th>
                      <th><?php echo _l('Start Date'); ?></th>
                      <th><?php echo _l('End Date'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($campaigns as $campaign): ?>
                      <tr>
                        <td>
                          <?php echo $campaign->id ?? '-'; ?>
                        </td>
                        <td>
                          <?php echo $campaign->name ?? '-'; ?>
                          <div class="row-options">
                            <a href="<?php echo admin_url('campaigns/view/' . $campaign->id); ?>">View</a>
                            <?php if (isset($campaign->invoice_id)): ?> | <a
                                href="<?php echo site_url('invoice/' . $campaign->invoice_id . '/' . $campaign->invoice_hash); ?>">Invoice</a>
                            <?php endif; ?>
                            <?php
                            $current_date = date('Y-m-d');
                            $start_date = !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                              ? date('Y-m-d', strtotime($campaign->start_date))
                              : 'N/A';

                            if (($campaign->status_id == 3)): ?>
                              | <a href="<?php echo admin_url('campaigns/edit/' . $campaign->id); ?>">Edit</a>
                            <?php endif; ?>

                            <?php if ($campaign->status_id == 3): ?>
                              | <a href="<?php echo admin_url('campaigns/delete/' . $campaign->id); ?>" class="text-danger"
                                onclick="return confirm('Are you sure you want to delete this campaign ?');">Delete</a>
                            </div>
                          <?php endif; ?>
                        </td>
                        <td><?php echo $campaign->description ?? '-'; ?></td>
                        <td><?php echo $campaign->status_name ?? '-'; ?>
                          <?php if ($campaign->invoice_status == 1): ?>
                            <br><span class="text-danger" style="font-size:11px">Invoice is pending</span>
                          <?php endif; ?>
                        </td>
                        <td><?php echo $campaign->budget ?? '0'; ?></td>
                        <td><?php echo $campaign->deal == 1 ? 'Exclusive' : 'Non-exclusive'; ?></td>
                        <td><?php echo !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                          ? date('d M Y', strtotime($campaign->start_date))
                          : '-'; ?></td>
                        <td><?php echo !empty($campaign->end_date) && strtotime($campaign->end_date) !== false
                          ? date('d M Y', strtotime($campaign->end_date))
                          : '-'; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php else: ?>
                <p><?php echo _l('No campaigns found.'); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<?php init_tail(); ?>

</body>

</html>