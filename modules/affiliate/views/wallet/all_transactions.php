<div class="row">
  <div class="col-md-3">
    <?php  echo render_select('member_filter', $members, array('id', 'firstname', 'lastname'), 'member', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
    </div>
  <div class="col-md-3">
<?php
      $prioritys = [0 => ['id' => '3', 'name' => _l('unpaid')],
                    1 => ['id' => '1', 'name' => _l('waiting')],
                    2 => ['id' => '2', 'name' => _l('paid')],
                   ];
      echo render_select('status', $prioritys, array('id', 'name'), 'status', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
    </div>
  <div class="col-md-3">
    <?php echo render_date_input('from_date','from_date'); ?>
  </div>
  <div class="col-md-3">
    <?php echo render_date_input('to_date','to_date'); ?>
  </div>
</div>
<table class="table table-all-transaction">
  <thead>
    <th><?php echo _l('date'); ?></th>
    <th><?php echo _l('user'); ?></th>
    <th><?php echo _l('commission'); ?></th>
    <th><?php echo _l('type'); ?></th>
    <th><?php echo _l('status'); ?></th>
    <th><?php echo _l('options'); ?></th>
  </thead>
  <tbody>
    
  </tbody>
</table>