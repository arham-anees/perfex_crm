<?php if (affiliate_has_permission('member', '', 'create')) { ?>
    <a href="<?php echo admin_url('affiliate/member'); ?>" class="btn btn-info pull-left mright5"><?php echo _l('new'); ?></a>
<?php } ?>
<a href="#" onclick="send_mail_members(); return false;" class="btn btn-info pull-left display-block mright5 mbot10" ><i class="fa fa-envelope"></i><?php echo ' '._l('send_mail'); ?></a>
<a href="#" onclick="toggle_chart(this);" class="btn btn-info pull-left display-block view_member_chart">
    <?php echo _l('view_member_chart'); ?>
</a>
<div id="member-table-modal">
  <?php
     $table_data = array(
      '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="affiliate-members"><label></label></div>',
       array(
         'name'=>_l('staff_dt_name'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-name')
        ),
         array(
         'name'=>_l('clients_country'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-country')
        ),
         array(
         'name'=>_l('staff_dt_email'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-email')
        ),
         array(
         'name'=>_l('username'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-username')
        ),
        array(
         'name'=>_l('sponser'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-sponser')
        ),
         array(
         'name'=>_l('staff_add_edit_phonenumber'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-phone')
        ),
        array(
         'name'=>_l('vendor'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-vendor')
        ),
        
      );

     $custom_fields = get_custom_fields('aff_member',array('show_on_table'=>1));

    foreach($custom_fields as $field){
       array_push($table_data, [
         'name' => $field['name'],
         'th_attrs' => array('data-type'=>$field['type'], 'data-custom-field'=>1)
      ]);
    }

    array_push($table_data, array(
         'name'=>_l('options'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-options')
        ));

     render_datatable($table_data,'affiliate-members');
     ?>
</div>
<div class="hide" id="member-chart-modal">
  <div id="member_chart" class="col-md-12"></div>
</div>

<div class="modal fade" id="send_mail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <?php echo form_open_multipart(admin_url('affiliate/send_mail_members'),array('id'=>'send-mail-form')); ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                <span><?php echo _l('send_mail'); ?></span>
            </h4>
        </div>
        <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <?php echo render_input('emails','send_to', '', 'text', ['readonly' => true]); ?>
              </div>
              <div class="col-md-12">
                <?php echo render_input('subject','subject'); ?>
              </div>
              <div class="col-md-12">
                <?php echo render_textarea('content','content','',array(),array(),'','tinymce') ?>
              </div>     
            </div>
        </div>
        <div class="modal-footer">
            <button type=""class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('send'); ?></button>
        </div>
    </div>
        <?php echo form_close(); ?>
    </div>
</div>
<div class="modal fade" id="add_transaction_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('transaction_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('transaction_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('affiliate/add_transaction',array('id'=>'add-transaction-modal')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                      <?php $arrAtt = array();
                        $arrAtt['data-type']='currency'; ?>
                        <?php echo render_input('amount','amount','','text', $arrAtt); ?>
                        <?php echo render_textarea('comment','comment_string'); ?>
                        <?php echo form_hidden('member_id'); ?>
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