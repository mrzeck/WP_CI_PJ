<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends MY_Model {
	function __construct() {
		parent::__construct('post','categories');
	}
}