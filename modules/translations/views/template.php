<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-3">
                <?php $this->load->view("lang_sidebar"); ?>
            </div>
            <div class="col-md-9">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin inline-block">
                            <?php echo html_escape($title); ?>
                        </h4>
                        <?php if (has_permission('translations', '', 'edit')) { ?>
                        <a href="<?php echo admin_url("translations/pull_from_files?lang=".urlencode($selected_lang)); ?>" onclick="return confirm('<?php echo _l('language_pull_file_warning'); ?>');" class="btn btn-default pull-right mright5"><?php echo _l('language_pull_from_file_btn', $selected_lang); ?></a>
                        <?php } ?>
                        <?php if (has_permission('translations', '', 'create')) { ?>
                        <a href="<?php echo admin_url("translations/add"); ?>" class="btn btn-info pull-right"><?php echo _l('language_add_new_string_btn'); ?></a>
                        <?php } ?>
                        <hr class="hr-panel-heading"/>
                        <?php if(is_array($folder_permissions) && count($folder_permissions) > 0){ 
                            foreach($folder_permissions as $permission){
                            ?>
                            <div class="alert alert-danger" id="warning_<?php echo html_escape($permission['module_id']); ?>'"><?php echo html_escape($permission['message']);?>
                                <a href="#" onclick="attempt_change('<?php echo html_escape($permission['folder']); ?>', 'warning_<?php echo html_escape($permission['module_id']); ?>'); return false;" class="pull-right"><?php echo _l('language_attempt_change'); ?></a>
                            </div>
                        <?php } } ?>
                        <div class="alert alert-warning <?php if($needs_publish == 0) echo html_escape('hide'); ?>" id="needs_publishing_warning"><?php echo _l('language_msg_file_need_publishing'); ?> <a href="<?php echo admin_url("translations/publish_file?lang=".urlencode($selected_lang)); ?>" onclick="return confirm('<?php echo _l('language_publish_warning'); ?>');"><?php echo _l('language_publish_now'); ?></a></div>
                        <div class="row">
                            <div class="col-md-12">
                                <form method="get">
                                    <input type="hidden" name="lang" value="<?php echo html_escape($selected_lang); ?>">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php echo render_input("type_to_search", "language_label_search", $this->input->get("type_to_search"), 'text', ["disabled" => true], [], '', '', ['<div class="input-group-addon" id="search-input-addon"><i class="fa fa-search"></i></div>']); ?>

                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="show_empty_only" id="show_empty_only" value="1" <?php if($this->input->get("show_empty_only") == "1") echo html_escape('checked'); ?>>
                                                <label for="show_empty_only"><?php echo _l('language_label_filter_empty'); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo render_select("filter_module[]", $modules, ['id', 'module_name_formatted'], 'language_label_filter_modules', $filter_modules, ["multiple" => true, "data-none-selected-text" => _l("language_label_all_modules")], [], '', '', false); ?>

                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="show_updated_only" id="show_updated_only" value="1" <?php if($this->input->get("show_updated_only") == "1") echo html_escape('checked'); ?>>
                                                <label for="show_updated_only"><?php echo _l('language_label_filter_updated'); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo render_select("sort_by", [["id" => "asc", "name" => _l('language_label_sort_by_index')." &lt;"._l('order_ascending')."&gt;"],["id" => "desc", "name" => _l('language_label_sort_by_index')." &lt;"._l('order_descending')."&gt;"],], ['id', 'name'], 'language_label_sort_by', $sort_by, [], [], '', '', false); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-info mtop25"><?php echo _l('apply'); ?></button>
                                            <a href="<?php echo admin_url('translations'); ?>" class="btn btn-info mtop25"><?php echo _l('clear'); ?></a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-12">
                                <hr/>
                                <div class="text-right"><?php echo trim($links); ?></div>
                                <div class="row" id="language_strings_container">
                                    <?php
                                    $show_empty_only = $this->input->get("show_empty_only");
                                    $sr = 0;
                                    $sr2 = 0;
                                    foreach ($strings as $string) {
                                        $input_id = "";
                                        $input_value = "";
                                        $input_new_value = "";
                                        $key = $string['index'];
                                        $is_readonly = ' onfocus="save_initial_value(this);" onblur="save_one(this, \''.html_escape($selected_lang).'\', \''._l('language_msg_value_saved').'\');"';
                                        if (!has_permission('translations', '', 'edit'))
                                            $is_readonly = ' readonly';
                                        if(isset($selected_strings[$key])){
                                            $input_id = $selected_strings[$key]["id"];
                                            $input_value = $selected_strings[$key]["value"];
                                            $input_new_value = $selected_strings[$key]["new_value"];
                                        }
                                        if($show_empty_only == "1" && $input_value != ""){
                                            continue;
                                        }
                                        $module_name = $string['module_name'];
                                        $sr++;
                                        $sr2++;
                                        ?>
                                        <div class="col-md-6 col-lg-4 field_wrapper">
                                            <div class="mbot15">
                                                <p class="no-margin keyword">
                                                    <?php echo html_escape($key) ?><br>
                                                    <span class="bold">(<a href="#" <?php if (has_permission('translations', '', 'edit')) { echo 'class="editable" data-lang="'.$selected_lang.'" data-message="'._l('language_msg_value_saved').'"'; } ?>><?php echo html_escape($string['value']); ?></a>)</span>
                                                </p>
                                                <input type="text" name="<?php echo html_escape($key); ?>" class="form-control" value="<?php echo !empty($input_new_value) ? htmlspecialchars($input_new_value) : htmlspecialchars($input_value); ?>" data-id="<?php echo html_escape($input_id); ?>" data-index="<?php echo html_escape($key); ?>"<?php echo trim($is_readonly); ?>>

                                                <p class="updated_wrapper">
                                                    <?php if(!empty($input_new_value)){ ?>
                                                        <span class="text-warning"><?php echo _l('language_value_updated'); ?></span> -
                                                        <a href="javascript: return false;" data-toggle="popover" data-html="true" data-content="<?php echo htmlspecialchars($input_value); ?>" data-placement="top"><?php echo _l('language_original_value'); ?></a>
                                                        <?php
                                                        if (has_permission('translations', '', 'edit')){ ?>
                                                            <a href="#" class="pull-right" onclick="undo_one(this, '<?php echo _l('language_msg_value_reverted'); ?>'); return false;" data-id="<?php echo html_escape($input_id); ?>"><?php echo _l('language_label_undo'); ?></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </p>

                                                <?php if(!empty($module_name)){ ?>
                                                <p class="mbot5"><?php echo _l('language_module_name').' - '.beautify_module_name($module_name); ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php if($sr2 == 2){ $sr2 = 0; ?>
                                        <div class="clearfix visible-md"></div>
                                        <?php } ?>
                                        <?php if($sr == 3){ $sr = 0; ?>
                                        <div class="clearfix visible-lg"></div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="text-right"><?php echo trim($links); ?></div>
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
