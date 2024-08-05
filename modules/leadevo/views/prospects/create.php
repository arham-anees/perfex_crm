<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Create Prospect'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(admin_url('leadevo/prospects/create'), array('id' => 'prospect-form')); ?>
                        
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name"><?php echo _l('First Name'); ?></label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name"><?php echo _l('Last Name'); ?></label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone"><?php echo _l('Phone'); ?></label>
                                    <input type="text" class="form-control" name="phone" id="phone" required>
                                </div>
                            </div>
                        </div>
                        
              
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email"><?php echo _l('Email'); ?></label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status_id"><?php echo _l('Status'); ?></label>
                                    <select name="status_id" id="status_id" class="form-control" required>
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?php echo $status->id; ?>"><?php echo $status->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type_id"><?php echo _l('Type'); ?></label>
                                    <select name="type_id" id="type_id" class="form-control" required>
                                        <?php foreach ($types as $type): ?>
                                            <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                       
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id"><?php echo _l('Category'); ?></label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="acquisition_channel_id"><?php echo _l('Acquisition Channel'); ?></label>
                                    <select name="acquisition_channel_id" id="acquisition_channel_id" class="form-control" required>
                                        <?php foreach ($acquisition_channels as $channel): ?>
                                            <option value="<?php echo $channel->id; ?>"><?php echo $channel->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="industry_id"><?php echo _l('Industry'); ?></label>
                                    <select name="industry_id" id="industry_id" class="form-control" required>
                                        <?php foreach ($industries as $industry): ?>
                                            <option value="<?php echo $industry['id']; ?>"><?php echo $industry['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                      
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="created_at"><?php echo _l('Created At'); ?></label>
                                    <input type="date" class="form-control" name="created_at" id="created_at" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="updated_at"><?php echo _l('Updated At'); ?></label>
                                    <input type="date" class="form-control" name="updated_at" id="updated_at" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?php echo _l('Save Prospect'); ?></button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
