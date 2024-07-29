<?php if (affiliate_has_permission('member', '', 'create')) { ?>
<a href="#" onclick="new_admin(); return false;" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
<?php } ?>
<table class="table table-affiliate-admin">
  <thead>
    <th><?php echo _l('staff_dt_name'); ?></th>
    <th><?php echo _l('staff_dt_email'); ?></th>
    <th><?php echo _l('phone'); ?></th>
    <th><?php echo _l('options'); ?></th>
  </thead>
  <tbody>
  </tbody>
</table>

<div class="modal fade" id="admin_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('add_admin'); ?></span>
                    <span class="edit-title"></span>
                </h4>
            </div>
            <?php echo form_open('admin/affiliate/add_affiliate_admin',array('id'=>'add-admin-modal')); ?>
            <?php echo form_hidden('id'); ?>

            <div class="modal-body">
                <div class="row">
                  <div class="col-md-12" id="select_staff">
                    <?php echo render_select('staff', $staffs, array('staffid', 'firstname', 'lastname'), 'staff'); ?>
                  </div>
                  <div class="col-md-12 hide" id="staff_name">
                  </div>
                  <div class="col-md-12">
                    
                  <div class="table-responsive">
                    <table class="table table-bordered roles no-margin">
                      <thead>
                        <th>Feature</th>
                        <th>Capabilities</th>
                      </thead>
                      <tbody>
                        <tr>
                          <td><b><?php echo _l('als_dashboard'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="dashboard_view" name="permissions[dashboard][]" value="view">
                              <label for="dashboard_view">View(Global)</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b><?php echo _l('members'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="member_view" name="permissions[member][]" value="view">
                              <label for="member_view">View(Global)</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="member_create" name="permissions[member][]" value="create">
                              <label for="member_create">Create</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="member_edit" name="permissions[member][]" value="edit">
                              <label for="member_edit">Edit</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="member_delete" name="permissions[member][]" value="delete">
                              <label for="member_delete">Delete</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="member_approval" name="permissions[member][]" value="approval">
                              <label for="member_approval">Approval</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b><?php echo _l('affiliate_program'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="affiliate_program_view" name="permissions[affiliate_program][]" value="view">
                              <label for="affiliate_program_view">View(Global)</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="affiliate_program_create" name="permissions[affiliate_program][]" value="create">
                              <label for="affiliate_program_create">Create</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="affiliate_program_edit" name="permissions[affiliate_program][]" value="edit">
                              <label for="affiliate_program_edit">Edit</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="affiliate_program_delete" name="permissions[affiliate_program][]" value="delete">
                              <label for="affiliate_program_delete">Delete</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b><?php echo _l('affiliate_orders'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="affiliate_orders_view" name="permissions[affiliate_orders][]" value="view">
                              <label for="affiliate_orders_view">View(Global)</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="affiliate_orders_create" name="permissions[affiliate_orders][]" value="create">
                              <label for="affiliate_orders_create">Create invoice</label>
                            </div>
                            <div class="checkbox hide">
                              <input type="checkbox" class="capability" id="affiliate_orders_approval" name="permissions[affiliate_orders][]" value="approval">
                              <label for="affiliate_orders_approval">Approval</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b><?php echo _l('affiliate_logs'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="affiliate_logs_view" name="permissions[affiliate_logs][]" value="view">
                              <label for="affiliate_logs_view">View(Global)</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b><?php echo _l('wallet'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="wallet_view" name="permissions[wallet][]" value="view">
                              <label for="wallet_view">View(Global)</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="wallet_create" name="permissions[wallet][]" value="create">
                              <label for="wallet_create">Create</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="wallet_delete" name="permissions[wallet][]" value="delete">
                              <label for="wallet_delete">Delete</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="wallet_approval" name="permissions[wallet][]" value="approval">
                              <label for="wallet_approval">Approval</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b><?php echo _l('reports'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="reports_view" name="permissions[reports][]" value="view">
                              <label for="reports_view">View(Global)</label>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><b><?php echo _l('settings'); ?></b></td>
                          <td>
                            <div class="checkbox">
                              <input data-can-view="" type="checkbox" class="capability" id="settings_view" name="permissions[settings][]" value="view">
                              <label for="settings_view">View(Global)</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="settings_create" name="permissions[settings][]" value="create">
                              <label for="settings_create">Create</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="settings_edit" name="permissions[settings][]" value="edit">
                              <label for="settings_edit">Edit</label>
                            </div>
                            <div class="checkbox">
                              <input type="checkbox" class="capability" id="settings_delete" name="permissions[settings][]" value="delete">
                              <label for="settings_delete">Delete</label>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>