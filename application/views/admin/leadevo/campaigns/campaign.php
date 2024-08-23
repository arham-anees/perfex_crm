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
                      <th><?php echo _l('Name'); ?></th>
                      <th><?php echo _l('Description'); ?></th>
                      <th><?php echo _l('Start Date'); ?></th>
                      <th><?php echo _l('End Date'); ?></th>
                      <th><?php echo _l('Budget'); ?></th>
                      <th><?php echo _l('Actions'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($campaigns as $campaign): ?>
                      <tr>
                        <td><?php echo $campaign->name ?? 'N/A'; ?></td>
                        <td><?php echo $campaign->description ?? 'N/A'; ?></td>
                        <td><?php echo !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                          ? date('d M Y', strtotime($campaign->start_date))
                          : 'N/A'; ?></td>
                        <td><?php echo !empty($campaign->end_date) && strtotime($campaign->end_date) !== false
                          ? date('d M Y', strtotime($campaign->end_date))
                          : 'N/A'; ?></td>
                  <td><?php echo $campaign->budget??'N/A'; ?></td>
                        <td>
                          <a href="<?php echo admin_url('campaigns/view/' . $campaign->id); ?>"
                            class="btn btn-default btn-icon">
                            <i class="fa fa-eye"></i>
                          </a>
                          <a href="<?php echo admin_url('campaigns/edit/' . $campaign->id); ?>"
                            class="btn btn-default btn-icon">
                            <i class="fa fa-pencil"></i>
                          </a>
                          <a href="<?php echo admin_url('campaigns/delete/' . $campaign->id); ?>"
                            class="btn btn-danger btn-icon"
                            onclick="return confirm('Are you sure you want to delete this campaign ?');">
                            <i class="fa fa-remove"></i>
                          </a>
                        </td>
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