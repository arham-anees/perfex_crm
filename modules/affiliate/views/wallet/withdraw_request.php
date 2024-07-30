<table class="table table-withdraw-request">
  <thead>
    <th><?php echo _l('date'); ?></th>
    <th><?php echo _l('username'); ?></th>
    <th><?php echo _l('payment_mode'); ?></th>
    <th><?php echo _l('total'); ?></th>
    <th><?php echo _l('status'); ?></th>
    <th><?php echo _l('options'); ?></th>
  </thead>
  <tbody>
      
  </tbody>
</table>
<div class="modal fade" id="withdraw_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('withdraw'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="withdraw_detail" class="col-md-12">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="withdraw_detail_btn"></div>
            </div>
        </div>
    </div>
</div>