<?php

/**
 * Moderation type Model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 * */
/*
 *
 * ТИП МОДЕРИРУЕМОГО ОБЪЕКТА:
 * 1. название,
 * 2. настройка(как модерировать),
 *     три типа модерации:
 *       премодерация (2) - при добавлении статус ставится 0 затем уже только уведомления что контент менялся,
 *       постмодерация (1) - добавляется сразу, но при каждом изменении уходит уведомление об изменении,
 *       без модерации (0) - тип заведен, но возможно нужно отключить модерацию вообще
 *       без модерации, и снаружи не видно что есть тип (-1) - тип заведен как в случае с юзерами только для общего хранения настройки проверки badwords
 * 3. название модели/модуля(video) - как название папки модуля , инклудим шаблон используя эту строку
 * 4. путь к модели (video/models/Video_model - чтобы подключать не только дефолтные модели)
 * 5. view_link, edit_link - для связей с просмотром и редактированием (скидываем эти действия на модули соотв объектов, за это модерация не отвечает)
 * 6. метод получения списка объектов (по id) (ids) (модели, которую завели выше)  --- ничего не знаем о тех данных,
 *     которые есть в объекте, поэтому спрашиваем модель, должен возвращать массив где ключи - id объектов
 * 7. шаблон строки в списке (лежит в папке модуля объекта) --- нет общего шаблона для отображения preview разных объектов (так чтобы можно было апрувить
 *     без просмотра например, какие данные для этого нужны известно опять-таки только на уровне модели объекта), assign в data
 * 8. метод установки статуса (id, status) - чтобы апрувить
 * 9. метод удаления объекта (id) - чтобы удалить:)
 * 10. check_badwords- проверять или нет бэдвордс, функция все равно одна для всех типов, туда передается только текст, но она проверяет нужно делать проверку или нет
 *
 * TODO: рассылать или нет оповещения админу , по каждому конкретному типу
 *
 */

define('MODERATION_TYPE_TABLE', DB_PREFIX . 'moderation_type');

class Moderation_type_model extends Model {

	/**
	 * Constructor
	 *
	 * @return Moderation_type
	 */
	public $DB;
	public $CI;
	public $types_cache;

	public function __construct() {
		parent::Model();

		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->types_cache["id"] = $this->types_cache["name"] = array();

		$this->DB->memcache_tables(array(MODERATION_TYPE_TABLE));
	}

	public function get_type_by_id($type_id) {
		if (!isset($this->types_cache["id"][$type_id]) || empty($this->types_cache["id"][$type_id])) {
			$type_id = intval($type_id);
			if (!$type_id)
				return false;

			$this->DB->select('id, name, mtype, view_link, edit_link, module, model, method_get_list, method_set_status, allow_to_decline, method_delete_object, method_mark_adult, template_list_row, date_add, check_badwords')->from(MODERATION_TYPE_TABLE)->where('id', $type_id);

			//_compile_select;
			$result = $this->DB->get()->result();
			if (!empty($result)) {
				$rt = get_object_vars($result[0]);
				$this->types_cache["id"][$rt["id"]] = $rt;
				$this->types_cache["name"][$rt["name"]] = $rt;
			} else {
				$this->types_cache["id"][$type_id] = array();
			}
		}
		return $this->types_cache["id"][$type_id];
	}

	public function get_type_by_name($name) {

		if (!isset($this->types_cache["name"][$name]) || empty($this->types_cache["name"][$name])) {
			$this->DB->select('id, name, mtype, view_link, edit_link, module, model, method_get_list, method_set_status, allow_to_decline, method_delete_object, method_mark_adult, template_list_row, date_add, check_badwords')->from(MODERATION_TYPE_TABLE);
			
			if(is_array($name)){
				$params["where_in"]["name"] = $name;
			}else{
				$params["where_in"]["name"] = array(''=>$name);
			}
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}

			//_compile_select;
			$result = $this->DB->get()->result();
			if (!empty($result)) {
				$rt = get_object_vars($result[0]);
				$this->types_cache["name"][$rt["name"]] = $rt;
				$this->types_cache["id"][$rt["id"]] = $rt;
			} else {
				$this->types_cache["name"][$name] = array();
			}
		}
		return $this->types_cache["name"][$name];
	}

	public function get_types() {
		$this->DB->select('id, name, mtype, view_link, edit_link, module, model, method_get_list, method_set_status, allow_to_decline, method_delete_object, method_mark_adult, template_list_row, date_add, check_badwords')->from(MODERATION_TYPE_TABLE)->order_by('id ASC');
		$result = $this->DB->get()->result();
		if (!empty($result)) {
			foreach ($result as $res_obj) {
				$rt = get_object_vars($res_obj);
				$this->types_cache["name"][$rt["name"]] = $rt;
				$this->types_cache["id"][$rt["id"]] = $rt;
				$res[] = $rt;
			}
			return $res;
		} else {
			return false;
		}
	}

	public function save_type($type_id, $attrs, $langs = array()) {
		if (!is_array($attrs) || !count($attrs)) {
			return false;
		}
		$type_id = intval($type_id);
		if (!$type_id) {
			$attrs["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(MODERATION_TYPE_TABLE, $attrs);
			$insId = $this->DB->query('SELECT LAST_INSERT_ID() as last_id')->result();
			$type_id = $insId[0]->last_id;
		} else {
			$this->DB->where('id', $type_id);
			$this->DB->update(MODERATION_TYPE_TABLE, $attrs);
		}

		if (!empty($langs) && !empty($attrs["name"])) {
			$lang_ids = array_keys($this->CI->pg_language->languages);
			$this->CI->pg_language->pages->set_string_langs('moderation', "mtype_" . $attrs["name"], $langs, $lang_ids);
		}

		return $type_id;
	}

	public function delete_type($type_id) {
		$type_id = intval($type_id);
		if (!$type_id) {
			return false;
		}
		$type_data = $this->get_type_by_id($type_id);

		$this->DB->delete(MODERATION_TYPE_TABLE, array('id' => $type_id));
		unset($this->types_cache["id"][$type_id]);

		$this->CI->load->model('Moderation_model');
		$this->CI->Moderation_model->delete_moderation_items_by_type_id($type_id);

		$this->CI->pg_language->pages->delete_string('moderation', "mtype_" . $type_data["name"]);
		return;
	}

	public function update_langs($data, $langs_file) {
		foreach ($data as $mtype) {
			$this->CI->pg_language->pages->set_string_langs('moderation', 'mtype_' . $mtype['name'], $langs_file['mtype_' . $mtype['name']], array_keys($langs_file['mtype_' . $mtype['name']]));
		}
	}

	public function export_langs($data, $langs_ids) {
		$gids = array();
		foreach ($data as $mtype) {
			$gids[] = 'mtype_' . $mtype['name'];
		}
		return $this->CI->pg_language->export_langs('moderation', $gids, $langs_ids);
	}

}
