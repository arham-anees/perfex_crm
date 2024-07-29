<?php if (affiliate_has_permission('settings', '', 'create')) { ?>
    <a href="#" onclick="new_program_category(); return false;" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
<?php } ?>
<table class="table table-program-category">
  <thead>
    <th><?php echo _l('name'); ?></th>
    <th><?php echo _l('options'); ?></th>
  </thead>
  <tbody>
    
  </tbody>
</table>
<div class="modal fade" id="program_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('program_category_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('program_category_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('affiliate/program_category',array('id'=>'program-category-modal')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('name','name'); ?>
                        <?php echo form_hidden('id'); ?>
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