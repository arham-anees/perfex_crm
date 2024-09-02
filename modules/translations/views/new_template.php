<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo html_escape($title); ?>
                        </h4>
                        <hr class="hr-panel-heading"/>
                        <?php echo form_open($this->uri->uri_string(), ["id" => "form_add_string"]); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_input('index', 'language_label_index', isset($template) ? $template->name : ""); ?>

                                <?php echo render_select("module_id", $modules, ['id', 'module_name_formatted'], 'language_label_select_module', '', ["data-none-selected-text" => _l("language_label_no_module")]); ?>

                                <hr/>
                                <h4 class="bold font-medium">English</h4>
                                <?php echo render_input('value[english]', 'language_label_value', isset($template) ? $template->value : ""); ?>
                                <?php foreach ($available_languages as $language) {
                                        $lang_used = false;
                                        if (get_option('active_language') == $language || total_rows('tblstaff', array('default_language' => $language)) > 0 || total_rows('tblclients', array('default_language' => $language)) > 0) {
                                            $lang_used = true;
                                        }
                                        $hide_template_class = '';
                                        if ($lang_used == false) {
                                            $hide_template_class = 'hide';
                                        }
                                        ?>
                                        <hr/>
                                        <h4 class="font-medium pointer bold"
                                            onclick='slideToggle("#temp_<?php echo addslashes($language); ?>");'><?php echo html_escape(ucfirst($language)); ?></h4>
                                        <?php
                                        echo xss_clean('<div id="temp_' . $language . '" class="' . $hide_template_class . '">');
                                        echo render_input('value['.$language.']', 'language_label_value', isset($template) ? $template->value : "");
                                        echo xss_clean('</div>');
                                } ?>
                                <div class="btn-bottom-toolbar text-right">
                                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                    <button type="submit" class="btn btn-info" name="save_and_add" value="1"><?php echo _l('submit_and_new'); ?></button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>

<?php init_tail(); ?>

</body>
</html>
