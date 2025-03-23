<?php
if (! defined('BASEPATH'))
exit('No direct script access allowed');
class Pixel_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_pixel($data) {
        $this->db->insert('pixel', $data);
    }
}
?>