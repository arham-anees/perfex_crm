<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/explanatory_videos'); ?>" class="btn btn-primary mb-3">
                                <i class="fa fa-arrow-left tw-mr-1"></i>
                                <?php echo _l('Back to List'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <h4><?php echo _l('Edit Video'); ?></h4>

                        <?php echo form_open(admin_url('explanatory_videos/edit/' . $video['id']), ['class' => 'form-horizontal']); ?>

                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($video['name']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea name="description" id="description" class="form-control" rows="4"><?php echo htmlspecialchars($video['description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="url"><?php echo _l('URL'); ?></label>
                            <input type="url" name="url" id="url" class="form-control" value="<?php echo htmlspecialchars($video['url']); ?>">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                            <a href="<?php echo admin_url('explanatory_videos'); ?>" class="btn btn-secondary"><?php echo _l('Cancel'); ?></a>
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