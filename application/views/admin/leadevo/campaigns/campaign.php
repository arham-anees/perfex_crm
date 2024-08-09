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
                    <th><?php echo _l('Active'); ?></th>
                    <th><?php echo _l('Actions'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($campaigns as $campaign): ?>
                    <tr>
                      <td><?php echo $campaign->name; ?></td>
                      <td><?php echo $campaign->description; ?></td>
                      <td><?php echo $campaign->is_active ? 'Yes' : 'No'; ?></td>
                      <td>
                        <a href="<?php echo admin_url('leadevo/campaigns/view/' . $campaign->id); ?>"
                          class="btn btn-default btn-icon">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a href="<?php echo admin_url('leadevo/campaigns/edit/' . $campaign->id); ?>"
                          class="btn btn-default btn-icon">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a href="<?php echo admin_url('leadevo/campaigns/delete/' . $campaign->id); ?>"
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
