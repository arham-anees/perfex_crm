<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Translations_model extends App_Model
{
    private $select = FALSE;
    private $sorted = FALSE;
    private $where_condition_defined = FALSE;
    private $temp_table = null;
    private $selected_lang = null;

    public function __construct(){
        parent::__construct();

        // CREATE QUERY FOR TEMP TABLE;
        // SEARCH EMPTY INDEX FROM SELECTED LANGUAGE
        $this->temp_table = $this->db->select("index")
            ->where("language", $this->selected_lang)
            ->group_start()
            ->where("value IS NULL")
            ->or_where("value=''")
            ->group_end()
            ->get_compiled_select(TRANSLATIONS_TABLE_NAME);
    }

    private function reset_vars(){
        $this->select = FALSE;
        $this->sorted = FALSE;
        $this->where_condition_defined = FALSE;
    }

    public function flush_cache(){
        $this->db->flush_cache();
        self::reset_vars();
    }

    public function set_language($lang){
        $this->selected_lang = $lang;
    }

    public function insert($insert){
        $result = $this->db->insert(TRANSLATIONS_TABLE_NAME, $insert);
        self::flush_cache();
        if($result){
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function insert_batch($insert){
        $sql = "INSERT IGNORE INTO `".TRANSLATIONS_TABLE_NAME."`";
        $_cols = [];
        foreach ($insert[0] as $cols => $val){
            $_cols[] = '`'.$cols.'`';
        }
        $sql .= '('.implode(', ', $_cols).')';
        $_vals = [];
        foreach ($insert as $item) {
            $_val = [];
            foreach ($item as $val){
                $_val[] = $val;
            }
            $_vals[] = '('.implode(', ', $this->db->escape($_val)).')';
        }
        $sql .= ' VALUES '.implode(', ', $_vals);
        $result = $this->db->query($sql);
        self::flush_cache();
        if($result){
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function update($update, $primary_id = FALSE){
        if(!$this->where_condition_defined && !$primary_id){
            return FALSE;
        }
        if($primary_id)
            $this->db->where(TRANSLATIONS_TABLE_NAME.".id", $primary_id);
        $result = $this->db->update(TRANSLATIONS_TABLE_NAME, $update);
        self::flush_cache();
        return $result;
    }

    public function filter_search($keyword){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->group_start();
        $this->db->like(TRANSLATIONS_TABLE_NAME.'.index', $keyword);
        $this->db->or_like(TRANSLATIONS_TABLE_NAME.'.value', $keyword);
        $this->db->or_where('`index` IN (SELECT `index` FROM `' .TRANSLATIONS_TABLE_NAME. '` WHERE `value` LIKE "%'.$keyword.'%")');
        $this->db->group_end();
        $this->db->stop_cache();
    }

    public function filter_show_empty(){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->group_start();
        $this->db->where("`index` IN (".$this->temp_table.")");
        $this->db->or_where('`index` NOT IN (SELECT `index` FROM `'.TRANSLATIONS_TABLE_NAME.'` WHERE `language`="'.$this->selected_lang.'")');
        $this->db->group_end();
        $this->db->stop_cache();
    }

    public function filter_module_id($keyword){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        if(is_array($keyword))
            $this->db->where_in(TRANSLATIONS_TABLE_NAME.".module_id", $keyword);
        else if(empty($keyword))
            $this->db->where(TRANSLATIONS_TABLE_NAME.".module_id IS NULL");
        else
            $this->db->where(TRANSLATIONS_TABLE_NAME.".module_id", $keyword);
        $this->db->stop_cache();
    }

    public function filter_language($keyword){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where(TRANSLATIONS_TABLE_NAME.".language", $keyword);
        $this->db->stop_cache();
    }

    public function filter_index($keyword){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where(TRANSLATIONS_TABLE_NAME.".index", $keyword);
        $this->db->stop_cache();
    }

    public function filter_file_name($keyword){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where(TRANSLATIONS_TABLE_NAME.".file_name", $keyword);
        $this->db->stop_cache();
    }

    public function filter_not_published(){
        $this->where_condition_defined = TRUE;
        $this->db->start_cache();
        $this->db->where(TRANSLATIONS_TABLE_NAME.".published", "0");
        $this->db->stop_cache();
    }

    public function sort_by($column, $direction = "ASC"){
        $this->sorted = TRUE;
        $this->db->order_by($column, $direction);
    }

    public function select($col){
        $this->select = TRUE;
        $this->db->select($col);
    }

    public function count_rows($flush_cache = FALSE){
        $count = $this->db->count_all_results(TRANSLATIONS_TABLE_NAME);
        if($flush_cache)
            self::flush_cache();
        return $count;
    }

    public function all_rows($limit = "", $start = ""){
        return self::_get_data(TRUE, $limit, $start);
    }

    public function get_detail($primary_id = FALSE){
        $limit = 1;
        if(!$this->where_condition_defined && !$primary_id)
            return FALSE;
        if($primary_id !== FALSE){
            if(is_array($primary_id)){
                $this->db->where_in(TRANSLATIONS_TABLE_NAME.".id", $primary_id);
                $limit = "";
            }
            else
                $this->db->where(TRANSLATIONS_TABLE_NAME.".id", $primary_id);
        }
        return self::_get_data(($limit === ""), $limit);
    }

    private function _get_data($all_rows = TRUE, $limit = "", $start = ""){
        if(!$this->select)
            $this->db->select([
                db_prefix()."modules.module_name",
                TRANSLATIONS_TABLE_NAME.".*"
            ]);
        if(!$this->sorted)
            $this->db->order_by(TRANSLATIONS_TABLE_NAME.".index", "ASC");
        $this->db->join(db_prefix() . "modules", db_prefix() . "modules.id=".TRANSLATIONS_TABLE_NAME.".module_id", "LEFT");
        $result = $this->db->get(TRANSLATIONS_TABLE_NAME, $limit, $start);
        self::flush_cache();
        if($all_rows)
            return $result->result_array();
        else if($result->num_rows() > 0) {
            return $result->row_array();
        }
        else
            return FALSE;
    }

    public function get_distinct_files($need_publishing = false){
        self::select([
            'MAX('.db_prefix()."modules.module_name) AS module_name",
            TRANSLATIONS_TABLE_NAME.".module_id",
            TRANSLATIONS_TABLE_NAME.".file_name",
        ]);
        if($need_publishing)
            $this->db->where("published", "0");
        $this->db->group_by(TRANSLATIONS_TABLE_NAME . '.module_id');
        $this->db->group_by(TRANSLATIONS_TABLE_NAME . '.file_name');
        $this->sorted = TRUE;
        $this->db->order_by('MIN('.TRANSLATIONS_TABLE_NAME.".index)", "ASC");
        return self::_get_data();
    }

    public function set_published($lang){
        $q = "UPDATE ".TRANSLATIONS_TABLE_NAME.' SET published=1, value=new_value, new_value=NULL WHERE language="'.$lang.'" AND published=0';
        $this->db->query($q);
    }

    public function if_require_publishing($id){
        $this->db->where(TRANSLATIONS_TABLE_NAME.".`language`=(SELECT `language` FROM ".TRANSLATIONS_TABLE_NAME." WHERE id=".$id.")");
        $this->db->where("published", "0");
        return $this->db->count_all_results(TRANSLATIONS_TABLE_NAME);
    }

    public function delete($primary_id = FALSE){
        if(!$this->where_condition_defined && !$primary_id){
            return FALSE;
        }
        if($primary_id){
            if(is_array($primary_id))
                $this->db->where_in("id", $primary_id);
            else
                $this->db->where("id", $primary_id);
        }
        $result = $this->db->delete(TRANSLATIONS_TABLE_NAME);
        self::flush_cache();
        return $result;
    }

    public function get_write_status_of_folders($selected_lang){

        self::select([
            'MAX('.db_prefix()."modules.module_name) AS module_name",
            TRANSLATIONS_TABLE_NAME.".module_id",
            'MAX('.TRANSLATIONS_TABLE_NAME.".file_name) AS file_name",
        ]);
        $this->db->where("published", "0");
        $this->db->group_by(TRANSLATIONS_TABLE_NAME . '.module_id');
        $this->sorted = TRUE;
        $this->db->order_by('MIN('.TRANSLATIONS_TABLE_NAME.".index)", "ASC");
        $distinct_files = self::_get_data();

        $result = [];
        foreach ($distinct_files as $file){
            $selected_lang_folder = 'language' . DIRECTORY_SEPARATOR . $selected_lang;
            if(empty($file['module_name']))
                $folder = APPPATH . $selected_lang_folder;
            else
                $folder = APP_MODULES_PATH . $file['module_name'] . DIRECTORY_SEPARATOR . $selected_lang_folder;
                
            if(!is_really_writable($folder)){
                $result[] = [
                    'message' => _l('language_folder_not_writable', $folder),
                    'folder' => $folder,
                    'module_id' => $file['module_id'],
                ];
            }
        }
        return $result;
    }

}
