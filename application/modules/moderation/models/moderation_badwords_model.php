<?php

/**
 * Moderation badwords model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
define('MODERATION_BADWORDS_TABLE', DB_PREFIX . 'moderation_badwords');

class Moderation_badwords_model extends Model {

	/**
	 * Constructor
	 *
	 * @return Moderation_type
	 */
	public $DB;
	public $CI;
	public $badwords_params_cache = array();

	public function __construct() {
		parent::Model();

		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;

		$this->DB->memcache_tables(array(MODERATION_BADWORDS_TABLE));
	}

	public function get_badwords() {
		$this->DB->select('id, original')->from(MODERATION_BADWORDS_TABLE)->order_by("search");
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			foreach ($result as $item) {
				$list[] = get_object_vars($item);
			}
			return $list;
		} else {
			return array();
		}
	}

	public function set_badword($word) {
		$errors = array();
		$word_arr = explode(" ", trim(strip_tags($word)));
		foreach ($word_arr as $word) {
			$data["original"] = trim($word);
			$$data["original"] = str_replace(array("_", ",", ":", ";"), " ", $data["original"]);
			$data["original"] = preg_replace("/[\n\t]/u", "", $data["original"]);

			$data["search"] = mb_strtoupper(preg_replace("/[^\pL\s0-9\.]/u", "", $data["original"]), 'utf8');
			if (!strlen($data["original"]) || !strlen($data["search"]))
				continue;

			$data["search_len"] = strlen($data["search"]);
			$data["search_ord"] = ord($data["search"]);

			$similar_arr = $this->get_similar_words($data["search"]);
			if (count($similar_arr) > 0) {
				$errors[] = l('badwords_in_base_exists', 'moderation') . ": " . implode(", ", $similar_arr);
			} else {
				$this->DB->insert(MODERATION_BADWORDS_TABLE, $data);
			}
		}
		$this->badwords_params_cache = array();
		return $errors;
	}

	public function remap_badwords() {
		$this->DB->select('id, search')->from(MODERATION_BADWORDS_TABLE);
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			foreach ($result as $item) {
				$data["search_len"] = strlen($item->search);
				$data["search_ord"] = ord($item->search);
				$this->DB->where("id", $item->id);
				$this->DB->update(MODERATION_BADWORDS_TABLE, $data);
			}
		}
	}

	public function delete_badword($id) {
		$this->DB->where("id", intval($id));
		$this->DB->delete(MODERATION_BADWORDS_TABLE);
		$this->badwords_params_cache = array();
	}

	public function get_similar_words($search_word) {
		$this->DB->select('id, original')->from(MODERATION_BADWORDS_TABLE)->where("search", $search_word);
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			foreach ($result as $item) {
				$list[] = $item->original;
			}
			return $list;
		} else {
			return array();
		}
	}

	public function get_badwords_params() {
		if (empty($this->badwords_params_cache)) {
			$return = array();
			$this->DB->select("DISTINCT search_len")->from(MODERATION_BADWORDS_TABLE);
			$result = $this->DB->get()->result();
			if (!empty($result)) {
				foreach ($result as $item) {
					$return["len"][] = $item->search_len;
				}
			}
			$this->DB->select("DISTINCT search_ord")->from(MODERATION_BADWORDS_TABLE);
			$result = $this->DB->get()->result();
			if (!empty($result)) {
				foreach ($result as $item) {
					$return["ord"][] = $item->search_ord;
				}
			}
		}
		$this->badwords_params_cache = $return;
		return $this->badwords_params_cache;
	}

	public function search_in_text($text) {
		$text = str_replace(array("_", ",", ":", ";"), " ", $text);
		$text = preg_replace("/[\n\t]/u", " ", $text);
		$text = mb_strtoupper(preg_replace("/[^\pL\s0-9\.]/u", "", $text), 'utf8');
		$text_array = preg_split("/[\s]+/", $text);
		$text_array_mb = mb_split(" ", $text);
		$text_array = array_unique($text_array);


		//$this->DB->select("id, search")->from(MODERATION_BADWORDS_TABLE)->where_in("search", $text_array);
		$this->DB->select("id, search")->from(MODERATION_BADWORDS_TABLE)->where_in("search", $text_array_mb);
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			$return["text"] = " " . $text . " ";
			foreach ($result as $item) {
				$return["text"] = str_replace(" " . $item->search . " ", " <span class='bwmark'>" . $item->search . "</span> ", $return["text"]);
			}
			$return["count"] = count($result);
		} else {
			$return["text"] = $text;
			$return["count"] = 0;
		}

		return $return;
	}

	public function check_badwords($mtype, $text) {
		$this->CI->load->model('moderation/models/Moderation_type_model');
		$type_data = $this->CI->Moderation_type_model->get_type_by_name($mtype);
		if (empty($type_data) || $type_data["check_badwords"] == 0) {
			return 0;
		}
		$text = str_replace(array("_", ",", ":", ";"), " ", $text);
		$text = preg_replace("/[\n\t]/u", " ", $text);
		$text = mb_strtoupper(preg_replace("/[^\pL\s0-9\.]/u", "", $text), 'utf8');
		$text_array = preg_split("/[\s]+/", $text);
		$text_array = array_unique($text_array);

		$this->DB->select("COUNT(*) AS cnt")->from(MODERATION_BADWORDS_TABLE)->where_in("search", $text_array);
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			return intval($result[0]->cnt);
		} else {
			return 0;
		}
	}

}
