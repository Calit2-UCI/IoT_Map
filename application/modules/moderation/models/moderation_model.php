<?php
/**
* Moderation Model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/


/*
*
* модель никогда не меняет статуса уже активного элемента
* каждый вызов add_moderation_item добавляет запись в соотв с типом мод.объекта
*
*/

define('MODERATION_ITEMS_TABLE', DB_PREFIX.'moderation_items');

class Moderation_model extends Model
{
	/**
	 * Constructor
	 *
	 * @return Moderation_type
	 */

	var $DB;
	var $CI;
	var $types;

	function Moderation_model()
	{
		parent::Model();

		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->CI->load->model('moderation/models/Moderation_type_model');
		$this->CI->load->model('moderation/models/Moderation_badwords_model');
	}

	function get_moderation_type($type_name){
		if(!isset($this->types[$type_name])){
			$type_data = $this->CI->Moderation_type_model->get_type_by_name($type_name);
			if(!is_array($type_data) || !count($type_data)) return false;
			$this->types[$type_data["id"]] = $type_data;
			$this->types[$type_name] = $type_data;
		}
		return $this->types[$type_name];
	}

	function get_moderation_type_by_id($type_id){
		if(!isset($this->types[$type_id])){
			$type_data = $this->CI->Moderation_type_model->get_type_by_id($type_id);
			if(!is_array($type_data) || !count($type_data)) return false;
			$this->types[$type_id] = $type_data;
			$this->types[$type_data["name"]] = $type_data;
		}
		return $this->types[$type_id];
	}

	function get_moderation_type_status($type_name){
		$type_data = $this->get_moderation_type($type_name);
		switch($type_data["mtype"]){
			case "0": $status = 1; break;
			case "1": $status = 1; break;
			case "2": $status = 0; break;
		}
		return $status;
	}

	function add_moderation_item($type_name, $obj_id){
		$type_data = $this->get_moderation_type($type_name);
		if($type_data["mtype"] == 0) return false;
		$type_id = intval($type_data["id"]);

		/// если объект уже занесен то обновляем дату
		if($this->isset_moderation_item($type_name, $obj_id)){
			$attrs["date_add"] = date("Y-m-d H:i:s");
			$this->DB->where('id_type', $type_id);
			$this->DB->where('id_object', $obj_id);
			$this->DB->update(MODERATION_ITEMS_TABLE, $attrs);
		}else{
			$attrs["date_add"] = date("Y-m-d H:i:s");
			$attrs["id_type"] = $type_id;
			$attrs["id_object"] = $obj_id;
			$this->DB->insert(MODERATION_ITEMS_TABLE, $attrs);
		}
		return true;
	}

	function isset_moderation_item($type_name, $obj_id){
		$type_data = $this->get_moderation_type($type_name);
		$type_id = intval($type_data["id"]);

		$this->DB->select('COUNT(*) AS cnt')->from(MODERATION_ITEMS_TABLE)->where('id_type', $type_id)->where('id_object', $obj_id);
		$result = $this->DB->get()->result();
		if(!empty($result) && intval($result[0]->cnt))
			return true;
		else
			return false;
	}

	function get_moderation_item($id){
		$id = intval($id);
		if(!$id) return false;
		$this->DB->select('id, id_type, id_object, date_add')->from(MODERATION_ITEMS_TABLE)->where("id", $id);
		$result = $this->DB->get()->result();
		if(!empty($result))
			return get_object_vars($result[0]);
		else
			return false;
	}

	function delete_moderation_item_by_id($id){
		$id = intval($id);
		if(!$id) return false;
		$this->DB->delete(MODERATION_ITEMS_TABLE, array('id' => $id));
		return;
	}
	
	function delete_moderation_items_by_type_id($type_id){
		if (!intval($type_id)){
			return false;
		}
		$this->DB->where('id_type', $type_id);
		$this->DB->delete(MODERATION_ITEMS_TABLE);
		return;
	} 

	function delete_moderation_item_by_obj($type_name, $obj_id){
		$type_data = $this->get_moderation_type($type_name);
		$type_id = intval($type_data["id"]);

		if(is_array($obj_id) && count($obj_id)){
			$obj_id_arr = $obj_id;
		}elseif(is_numeric($obj_id) && $obj_id > 0){
			$obj_id_arr[] = intval($obj_id);
		}else{
			return false;
		}

		$this->DB->where('id_type', $type_id);
		$this->DB->where_in('id_object', $obj_id_arr);
		$this->DB->delete(MODERATION_ITEMS_TABLE);
		return;
	}

	function get_moderation_list_count($type_name=""){
		if($type_name){
			$type_data = $this->get_moderation_type($type_name);
			$this->DB->where('id_type', $type_data["id"]);
		}
		$this->DB->select('COUNT(*) AS cnt')->from(MODERATION_ITEMS_TABLE);
		$result = $this->DB->get()->result();
		if(!empty($result))
			return intval($result[0]->cnt);
		else
			return 0;
	}

	function get_moderation_list($type_name="", $page=null, $list_per_page=null, $parse_html=true){
		$this->DB->select('id, id_type, id_object, date_add')->from(MODERATION_ITEMS_TABLE);
		if($type_name){
			$type_data = $this->get_moderation_type($type_name);
			$this->DB->where('id_type', $type_data["id"]);
		}

		$this->DB->order_by("date_add DESC");
		if(!is_null($page) && !is_null($list_per_page)){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($list_per_page, $list_per_page*($page-1));
		}

		$result = $this->DB->get()->result();
		if(empty($result)) return false;
		
		foreach($result as $item){
			$type = $this->get_moderation_type_by_id($item->id_type);
			$item->type_name = $type["name"];
			$item->type = $type;
			$object_ids[$item->type_name][] = $item->id_object;
			if(strlen($type["view_link"])){
				$item->view_link = site_url().$type["view_link"].$item->id_object ;
			}
			if(strlen($type["edit_link"])){
				$item->edit_link = site_url().$type["edit_link"].$item->id_object ;
			}
			if(strlen($type["method_delete_object"])){
				$item->avail_delete = true;
			}
			if(strlen($type["method_mark_adult"])){
				$item->mark_adult = true;
			}
			if(strlen($type["method_set_status"]) && intval($type["allow_to_decline"]) ){
				$item->avail_decline = true;
			}
			$list[] = get_object_vars($item);
		}
		
		if($parse_html && isset($object_ids) && is_array($object_ids)){
			foreach($object_ids as $type_name => $ids){

				/// получем параметры типа
				$type = $this->types[$type_name];

				/// подключаем модель
				$model_name = ucfirst($type["model"]);
				$model_path = strtolower($type["module"]."/models/").$model_name;
				$this->CI->load->model($model_path);

				/// получаем данные обектов по ids (возвращается массив: id_object => object_data)
				$objects_data[$type_name] = $this->CI->$model_name->$type["method_get_list"]($ids);
			}
			
			foreach($list as $key => $item){
				if(isset($objects_data[$item["type_name"]][$item["id_object"]])){
					/// assign в шаблон, складываем html в переменную
					$this->CI->template_lite->assign('data', $objects_data[$item["type_name"]][$item["id_object"]]);
					$list[$key]["html"] = $this->CI->template_lite->fetch($item["type"]["template_list_row"], 'admin',  $item["type"]["module"]);
				}
			}
		}

		return $list;
	}

}