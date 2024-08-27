<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Information Details'); ?></h4>
                <hr class="hr-panel-heading" />
                
                <div class="form-group">
                    <label for="info key"><?php echo _l('Information Key'); ?></label>
                    <p><?php echo $informationpoint->info_key; ?></p>
                </div>

                <div class="form-group">
                    <label for="info"><?php echo _l('Information'); ?></label>
                    <p><?php htmlspecialchars($informationpoint->info); ?></p> 
                </div>

                <div class="form-group">
                    <a href="<?php echo admin_url('information/edit/' . $informationpoint->id); ?>" class="btn btn-primary"> 
                        <?php echo _l('Edit'); ?>
                    </a>
                    <a href="<?php echo admin_url('information'); ?>" class="btn btn-default">
                        <?php echo _l('Back'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
