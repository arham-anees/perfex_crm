<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="panel_s">
                    <div class="panel-body">
                    <div class="_buttons">
                            <a href="<?php echo admin_url('affiliate_training_videos'); ?>" class="btn btn-primary mb-3">
                                <i class="fa fa-arrow-left tw-mr-1"></i>
                                <?php echo _l('Back to List'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <h4><?php echo _l('Edit Video'); ?></h4>
                        <?php echo form_open(admin_url('affiliate_training_videos/edit/' . $video['id'])); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($video['name']??'N/A'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="url"><?php echo _l('URL'); ?></label>
                            <input type="text" id="url" name="url" class="form-control" value="<?php echo htmlspecialchars($video['url']??'N/A'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($video['description']??'N/A'); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="video_order"><?php echo _l('video_order'); ?></label>
                            <input type="number" id="video_order" name="video_order" class="form-control" value="<?php echo htmlspecialchars($video['video_order']??'N/A'); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
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
