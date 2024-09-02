<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php if (!isset($no_menu)){ ?>
            <div class="col-md-3">
                <?php $this->load->view("lang_sidebar"); ?>
            </div>
            <div class="col-md-9">
                <?php } else { ?>
                <div class="col-md-12">
                    <?php } ?>
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4 class="no-margin inline-block">
                                <?php echo html_escape($title); ?>
                            </h4>
                            <hr>
                            <div class="text-center mtop20 mbot20">
                                <h2><?php echo _l('language_msg_file_import_error', html_escape(ucfirst($selected_lang))); ?></h2>
                                <?php if (has_permission('translations', '', 'edit')) { ?>
                                    <a href="<?php echo admin_url("translations/pull_from_files?lang=" . urlencode($selected_lang)); ?>"
                                       class="btn btn-info"><?php echo _l('language_pull_from_file_btn', $selected_lang); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

</body>
</html>
