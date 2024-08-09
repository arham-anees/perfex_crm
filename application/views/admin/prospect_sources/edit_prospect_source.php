<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body text-left">
                        <h4><?php echo _l('Edit Prospect Source'); ?></h4>
                        <hr class="hr-panel-heading">

                        <?php echo form_open(admin_url('Prospect_sources/edit/' . $prospect_source['id']), ['method' => 'POST']); ?>
                            <div class="form-group text-left">
                                <label for="name"><?php echo _l('Name'); ?></label>
                                <input type="text" name="name" class="form-control" id="name" value="<?php echo set_value('name', $prospect_source['name']); ?>">
                            </div>
                            <div class="form-group text-left">
                                <label for="email"><?php echo _l('Description'); ?></label>
                                <input type="text" name="description" class="form-control" id="description" value="<?php echo set_value('description', $prospect_source['description']); ?>">
                            </div>
                            <div class="form-group text-left">
                                <button type="submit" class="btn btn-primary"><?php echo _l('Save'); ?></button>
                                <a href="<?php echo admin_url('Prospect_sources'); ?>" class="btn btn-info"><?php echo _l('Cancel'); ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
