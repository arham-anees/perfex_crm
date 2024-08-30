<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row main_row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="_buttons">

              <?php if (!empty($campaigns)): ?>
                <table class="table dt-table scroll-responsive">
                  <thead>
                    <tr>
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
                    <?php echo $campaign->name ?? 'N/A'; ?>
                    <div class="row-options">
                      <a href="<?php echo admin_url('campaigns/view/' . $campaign->id); ?>">View</a> |
                      <?php
                      $current_date = date('Y-m-d');
                      $start_date = !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                        ? date('Y-m-d', strtotime($campaign->start_date))
                        : 'N/A';

                      if (($campaign->status_id == 3) && ($start_date >= $current_date)): ?>
                        <a href="<?php echo site_url('campaigns/edit/' . $campaign->id); ?>">Edit</a> |
                      <?php endif; ?>

                      <?php if ($campaign->status_id == 3): ?>
                        <a href="<?php echo admin_url('campaigns/delete/' . $campaign->id); ?>" class="text-danger"
                          onclick="return confirm('Are you sure you want to delete this campaign ?');">Delete</a>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $campaign->description ?? 'N/A'; ?></td>
                  <td><?php echo $campaign->status_name ?? 'N/A'; ?></td>
                  <td><?php echo $campaign->budget ?? 'N/A'; ?></td>
                  <td><?php echo $campaign->deal == 1 ? 'Exclusive' : 'Non-exclusive'; ?></td>
                  <td><?php echo !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                    ? date('d M Y', strtotime($campaign->start_date))
                    : 'N/A'; ?></td>
                  <td><?php echo !empty($campaign->end_date) && strtotime($campaign->end_date) !== false
                    ? date('d M Y', strtotime($campaign->end_date))
                    : 'N/A'; ?></td>
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