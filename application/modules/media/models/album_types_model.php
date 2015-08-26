<?php

namespace Pg\Modules\Media\Models;

/**
 * Album types model
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Chernov <mchernov@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
 * */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('ALBUM_TYPES_TABLE', DB_PREFIX . 'album_types');

class Album_types_model extends \Model
{

    protected $CI;
    protected $DB;
    private $fields = array(
        'id', 'gid', 'gid_upload_type', 'file_count', 'gid_upload_video', 'video_count'
    );

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->DB = &$this->CI->db;
        $this->DB->memcache_tables(array(ALBUM_TYPES_TABLE));
    }

    public function get_album_type_by_gid($gid)
    {
        $result = $this->DB->select(implode(", ", $this->fields))
                        ->from(ALBUM_TYPES_TABLE)
                        ->where("gid", $gid)
                        ->get()->result_array();
        if (empty($result)) {
            return false;
        } else {
            return $result[0];
        }
    }

}
