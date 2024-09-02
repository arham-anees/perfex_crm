<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Translations extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // IF MODULE DISABLED THEN SHOW 404
        if (!defined('TRANSLATIONS_MODULE_NAME'))
            show_404();
        $this->load->model("Translations_model");
        $this->load->helper("security");
    }

    public function index()
    {
        if (!has_permission('translations', '', 'view')) {
            access_denied('translations');
        }

        $default_lang = "english";
        $selected_lang = get_option('active_language');
        if ($this->input->get("lang"))
            $selected_lang = $this->input->get("lang");
        $this->Translations_model->set_language($selected_lang);

        // English is not included here
        $available_languages = $this->app->get_available_languages();
        $data['selected_lang'] = $selected_lang;
        $data['available_languages'] = $available_languages;

        $show_empty_only = $this->input->get("show_empty_only");
        $show_updated_only = $this->input->get("show_updated_only");
        $data['filter_modules'] = $filter_modules = $this->input->get("filter_module");
        $keyword = trim($this->input->get("type_to_search") ?? '');
        $data['sort_by'] = $sort_by = $this->input->get("sort_by");

        $this->Translations_model->filter_language($default_lang);
        $is_searching = false;
        if ($show_empty_only == "1") {
            $is_searching = true;
            $this->Translations_model->filter_show_empty();
        }
        if ($show_updated_only == "1") {
            $is_searching = true;
            $this->Translations_model->filter_not_published();
        }
        if (is_array($filter_modules) && count($filter_modules) > 0) {
            $is_searching = true;
            $this->Translations_model->filter_module_id($filter_modules);
        }
        if ($keyword != "") {
            $is_searching = true;
            $this->Translations_model->filter_search($keyword);
        }
        $total_rows = $this->Translations_model->count_rows();
        if (!$is_searching && $total_rows == 0) {
            $this->Translations_model->flush_cache();
            $data['no_menu'] = true;
            $data['selected_lang'] = $default_lang;
            return $this->no_string_found($data);
        }

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = admin_url("translations");
        $config["total_rows"] = $total_rows;
        $config["per_page"] = 300;
        $config["num_links"] = 5;
        $config["use_page_numbers"] = TRUE;
        $config["page_query_string"] = TRUE;
        $config["query_string_segment"] = "page";
        $config["reuse_query_string"] = TRUE;
        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';
        $config["prev_tag_open"] = $config["next_tag_open"] = $config["first_tag_open"] = $config["last_tag_open"] = $config["num_tag_open"] = '<li class="paginate_button">';
        $config["prev_tag_close"] = $config["next_tag_close"] = $config["first_tag_close"] = $config["last_tag_close"] = $config["num_tag_close"] = '</li>';
        $config["cur_tag_open"] = '<li class="paginate_button active"><a href="#">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $data["links"] = $this->pagination->create_links();

        if (!in_array($sort_by, ["asc", "desc"])) {
            $sort_by = "asc";
        }
        $this->Translations_model->sort_by("index", $sort_by);
        $data["strings"] = $this->Translations_model->all_rows($config["per_page"], $config["per_page"] * ($page - 1));

        // CHECK IF FILE NEEDS TO PUBLISH AGAIN
        $this->Translations_model->filter_not_published();
        $this->Translations_model->filter_language($selected_lang);
        $data["needs_publish"] = $this->Translations_model->count_rows(true);

        // GET VALUES FROM SELECTED LANGUAGE
        $this->Translations_model->filter_language($selected_lang);
        $selected_strings = $this->Translations_model->all_rows();
        if (count($selected_strings) == 0) {
            $this->Translations_model->flush_cache();
            return $this->no_string_found($data);
        }

        $data["selected_strings"] = [];
        foreach ($selected_strings as $string) {
            $index = $string['index'];
            $data["selected_strings"][$index] = [
                "id" => $string['id'],
                "value" => $string['value'],
                "new_value" => $string['new_value'],
            ];
        }
        $folder_permissions = $this->Translations_model->get_write_status_of_folders($selected_lang);
        $data['folder_permissions'] = $folder_permissions;

        $data['modules'] = $this->get_modules();

        $data['title'] = _l('language_editing', ucfirst($selected_lang));
        $this->load->view('template', $data);
    }

    private function no_string_found($data)
    {
        $data['title'] = _l('language_editing', ucfirst($data['selected_lang']));
        $this->load->view('no_string_found', $data);
    }

    public function save_one()
    {
        if (!has_permission('translations', '', 'edit')) {
            access_denied('translations');
        }
        $id = $this->input->post("id");
        $index = $this->input->post("index");
        $value = $this->input->post("value");
        $value = htmlspecialchars_decode($value);
        $lang = $this->input->post("lang");

        if (trim($value) != "") {
            $response['success'] = false;
            $response['html'] = "";
            $response['needs_publishing'] = false;
            if (intval($id) > 0) {
                $insert['new_value'] = trim($value);
                $insert['published'] = "0";
                $updated = $this->Translations_model->update($insert, $id);
                $response['success'] = $updated;
            } else {
                $this->Translations_model->filter_language("english");
                $this->Translations_model->filter_index($index);
                $eng_string = $this->Translations_model->get_detail();
                $insert['language'] = $lang;
                $insert['index'] = $index;
                $insert['new_value'] = trim($value);
                $insert['module_id'] = $eng_string['module_id'];
                if (empty($insert['module_id']))
                    $insert['file_name'] = $lang . "_lang.php";
                else
                    $insert['file_name'] = $eng_string['file_name'];
                $insert['published'] = "0";
                $id = $this->Translations_model->insert($insert);
                $response['success'] = $id;
            }
            if ($response['success']) {
                $require_publish = $this->Translations_model->if_require_publishing($id);
                if ($require_publish > 0)
                    $response['needs_publishing'] = true;
                $lang = $this->Translations_model->get_detail($id);
                $response['html'] = '<span class="text-warning">' . _l('language_value_updated') . '</span> - <a href="javascript: return false;" data-toggle="popover" data-html="true" data-content="' . htmlspecialchars($lang['value']) . '" data-placement="top">' . _l('language_original_value') . '</a>';
                if (has_permission('translations', '', 'edit')) {
                    $response['html'] .= '<a href="#" class="pull-right" onclick="undo_one(this, \'' . _l('language_msg_value_reverted') . '\'); return false;" data-id="' . $id . '">' . _l('language_label_undo') . '</a>';
                }
            }
            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode($response));
        }
    }

    public function undo_one($id)
    {
        if (!has_permission('translations', '', 'edit')) {
            access_denied('translations');
        }

        if (intval($id) > 0) {
            $insert['new_value'] = null;
            $insert['published'] = "1";
            $updated = $this->Translations_model->update($insert, $id);
            $response['success'] = false;
            $response['value'] = false;
            $response['needs_publishing'] = false;
            if ($updated) {
                $response['success'] = true;
                $lang = $this->Translations_model->get_detail($id);
                $response['value'] = $lang['value'];
                $require_publish = $this->Translations_model->if_require_publishing($id);
                if ($require_publish > 0)
                    $response['needs_publishing'] = true;
            }
            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode($response));
        }
    }

    public function publish_file()
    {
        if (!has_permission('translations', '', 'edit')) {
            access_denied('translations');
        }
        $selected_lang = $this->input->get("lang");
        if ($selected_lang != "") {
            $selected_lang_folder = 'language' . DIRECTORY_SEPARATOR . $selected_lang;

            // GET FILES ONLY WHICH NEEDS TO BE PUBLISHED AGAIN
            $distinct_files = $this->Translations_model->get_distinct_files(true);
            foreach ($distinct_files as $file) {
                if (empty($file['module_name']))
                    $folder = APPPATH . $selected_lang_folder;
                else
                    $folder = APP_MODULES_PATH . $file['module_name'] . DIRECTORY_SEPARATOR . $selected_lang_folder;
                if (!is_really_writable($folder)) {
                    set_alert('danger', _l('language_folder_not_writable', $folder));
                    redirect(admin_url('translations?lang=' . $selected_lang));
                    return;
                }
                $selected_lang_file = $folder . DIRECTORY_SEPARATOR . $file['file_name'];

                if (!is_dir($folder)) {
                    mkdir($folder);
                }
                if (file_exists($selected_lang_file)) {
                    unlink($selected_lang_file);
                }
                file_put_contents($selected_lang_file, '<?php' . "\n");

                $this->Translations_model->filter_language($selected_lang);
                $this->Translations_model->filter_file_name($file['file_name']);
                $this->Translations_model->filter_module_id($file['module_id']);
                $strings_to_update = $this->Translations_model->all_rows();

                foreach ($strings_to_update as $string) {
                    $key = $string['index'];
                    $value = !empty($string['new_value']) ? $string['new_value'] : $string['value'];
                    if (trim($value) != "") {
                        $content = '$lang["' . $key . '"] = "' . str_replace('% ', '&percnt; ', addcslashes($value, '"\\')) . '";' . "\n";
                        file_put_contents($selected_lang_file, $content, FILE_APPEND);
                    }
                }
            }
            // UPDATE PUBLISHED FLAGS
            $this->Translations_model->set_published($selected_lang);

            set_alert('success', _l('language_msg_file_published', ucfirst($selected_lang)));
            redirect(admin_url('translations?lang=' . $selected_lang));
        }
    }

    public function add()
    {
        if (!has_permission('translations', '', 'create')) {
            access_denied('translations');
        }
        // English is not included here
        $available_languages = $this->app->get_available_languages();
        if (($key = array_search('english', $available_languages)) !== false) {
            unset($available_languages[$key]);
        }
        $data['available_languages'] = $available_languages;

        if (!empty($this->input->post())) {
            $index = $this->input->post("index");
            $module_id = $this->input->post("module_id");
            $value = $this->input->post("value");
            $file_name = null;
            if (intval($module_id) > 0)
                $file_name = $this->get_module_first_file_name($module_id, "english");

            $this->Translations_model->filter_language("english");
            $this->Translations_model->filter_index($index);
            $exists = $this->Translations_model->count_rows(TRUE);
            if ($exists > 0) {
                set_alert("warning", _l("language_msg_index_exists", [$index]));
                redirect(admin_url("translations/add"));
                exit;
            }
            $insert_batch = [];
            foreach ($value as $lang => $val) {
                if (trim($val) != "") {
                    $insert['language'] = $lang;
                    $insert['index'] = $index;
                    $insert['value'] = $val;
                    $insert['module_id'] = $module_id;
                    $insert['file_name'] = $file_name;
                    $insert_batch[] = $insert;
                }
            }
            $inserted = false;
            if (count($insert_batch) > 0) {
                $db_debug = $this->db->db_debug; //save setting
                $this->db->db_debug = FALSE; //disable debugging for queries
                $inserted = $this->Translations_model->insert_batch($insert_batch);
                $this->db->db_debug = $db_debug; //restore setting
            }
            if ($inserted) {
                set_alert("success", _l("language_msg_new_added"));
                if ($this->input->post("save_and_add")) {
                    redirect(admin_url("translations/add"));
                } else {
                    redirect(admin_url("translations"));
                }
            } else {
                set_alert("warning", _l("language_msg_not_inserted"));
            }
        }

        $data['module_name'] = "";
        $data['module_ids'] = "";
        $data['modules'] = $this->get_modules();

        $data['title'] = _l('language_title_add_new');
        $this->load->view('new_template', $data);
    }

    private function get_module_first_file_name($module_id, $lang)
    {
        $module = $this->get_modules($module_id);
        $folder = APP_MODULES_PATH . $module['module_name'] . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang;
        if (!is_dir($folder))
            return null;
        $files = scandir($folder);
        foreach ($files as $file) {
            if (in_array($file, [".", ".."]))
                continue;
            return $file;
        }
        return null;
    }

    public function pull_from_files()
    {
        if (!has_permission('translations', '', 'edit')) {
            access_denied('translations');
        }
        $selected_lang = "english";
        if ($this->input->get("lang"))
            $selected_lang = $this->input->get("lang");

        $perfex_version = $this->app->get_current_db_version();
        $modules = $this->get_modules();
        $this->Translations_model->filter_language($selected_lang);
        $this->Translations_model->delete();

        $selected_lang_folder = 'language' . DIRECTORY_SEPARATOR . $selected_lang;
        // SCAN AND ADD FROM APPLICATION FOLDER
        $this->scan_and_insert_files($selected_lang, APPPATH . $selected_lang_folder, null, $perfex_version);
        //SCAN AND ADD FROM MODULE FOLDERS
        foreach ($modules as $module) {
            $folder = APP_MODULES_PATH . $module['module_name'] . DIRECTORY_SEPARATOR . $selected_lang_folder;
            $this->scan_and_insert_files($selected_lang, $folder, $module['id'], $module['installed_version']);
        }

        set_alert("success", _l("language_msg_files_imported", ucfirst($selected_lang)));
        redirect(admin_url("translations?lang=" . $selected_lang));
    }

    private function scan_and_insert_files($selected_lang, $folder, $module_id = null, $version = null)
    {
        if (is_dir($folder)) {
            $files = scandir($folder);
            foreach ($files as $file) {
                if (in_array($file, [".", ".."]))
                    continue;

                // UNSET OLD ARRAY TO AVOID DUPLICATE ENTRIES
                unset($lang);
                // INCLUDE AND PUSH CODE TO DB
                $selected_lang_path = $folder . DIRECTORY_SEPARATOR . $file;
                include($selected_lang_path);
                $insert_batch = [];
                foreach ($lang as $i => $l) {
                    $insert['language'] = $selected_lang;
                    $insert['index'] = $i;
                    $insert['value'] = $l;
                    $insert['module_id'] = $module_id;
                    $insert['file_name'] = $file;
                    $insert['published'] = "1";
                    $insert_batch[] = $insert;
                    if (count($insert_batch) == 50) {
                        $this->Translations_model->insert_batch($insert_batch);
                        $insert_batch = [];
                    }
                }
                if (count($insert_batch) > 0) {
                    $this->Translations_model->insert_batch($insert_batch);
                }
            }
        }
    }

    private function get_modules($id = "")
    {
        if (!empty($id) && intval($id) > 0)
            $this->db->where("id", $id);
        $modules = $this->db->order_by("module_name")->get(db_prefix() . 'modules')->result_array();
        foreach ($modules as $index => $module) {
            $modules[$index]['module_name_formatted'] = beautify_module_name($module['module_name']);
        }
        if (!empty($id) && intval($id) > 0 && count($modules) > 0)
            return $modules[0];
        else
            return $modules;
    }

    public function attempt_change_permissions()
    {
        $folder = $this->input->post('folder');
        $updated = chmod($folder, 0777);
        if ($updated)
            $response = [
                "success" => true,
                "message" => _l('language_permission_successful', [$folder]),
            ];
        else
            $response = [
                "success" => false,
                "message" => _l('language_permission_failed', [$folder]),
            ];
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
