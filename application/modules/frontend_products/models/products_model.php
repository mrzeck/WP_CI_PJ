<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class products_model extends MY_Model {

	function __construct() {
		parent::__construct('products', 'products_categories');
	}
}

/* End of file modelName.php */
/* Location: ./application/models/modelName.php */