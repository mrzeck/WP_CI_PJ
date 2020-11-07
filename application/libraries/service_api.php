<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class service_api {

	public $ci      = '';
	
	public $service = SERVERAPI.'/';
	
	public $user    = '';
	
	public $token   = '';

	function __construct(){

		$this->ci =& get_instance();

		$this->user = get_option('api_user');

		$this->token = get_option('api_secret_key');
	}

	public function curl_get( $endpoint = '')
	{
		$file_headers = @get_headers($this->service);

		if(!empty($file_headers)) {

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL            => $this->service.$endpoint,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "GET",
			));
			curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
			curl_setopt( $curl, CURLOPT_USERPWD, "$this->user:$this->token");
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt( $curl, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'] . "/".SPATH."uploads/cacert.pem");

			$response = curl_exec($curl);

			$err = curl_error($curl);

			curl_close($curl);

			return $response;
		}

		return json_encode(['status' => 'error', 'message' => 'No connect to server']);
	}

	public function gets_plugin( )
	{
		$response = $this->curl_get('plugin');

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	public function get_plugin( $id = null )
	{
		$response = $this->curl_get('plugin/'.$id);

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	public function plugin_version( $name = null )
	{
		if( $name == null ) return;

		$response = $this->curl_get('plugin/version/'.$name);

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response->version;
	}

	//WIDGET
	public function gets_widget( $id = null )
	{
		if( $id ==  null)
			$response = $this->curl_get('widget');
		else
			$response = $this->curl_get( 'widget?bycategory='.$id );

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	public function get_widget( $id = null )
	{
		$response = $this->curl_get('widget/'.$id);

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	public function gets_widget_category( )
	{
		$response = $this->curl_get('widget/categories');

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	//CMS
	public function cms_version()
	{
		$response = $this->curl_get('cms/version');

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return 'error';

		return  ( have_posts($response) && isset($response->version) ) ? $response->version : 'error';
	}

	public function cms_support() {

		$response = $this->curl_get('cms/support');

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return 'error';

		return  ( have_posts($response) && isset($response->data) ) ? $response->data : (object)['status' => 'error'];
	}

	//HEDAER
	public function gets_header( $id = null )
	{
		if( $id ==  null)
			$response = $this->curl_get('header');
		else
			$response = $this->curl_get( 'header/'.$id );

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	public function get_header( $id = null )
	{
		$response = $this->curl_get('header/'.$id);

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	//HEDAER
	public function gets_navigation( $id = null )
	{
		if( $id ==  null)
			$response = $this->curl_get('navigation');
		else
			$response = $this->curl_get( 'navigation/'.$id );

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	public function get_navigation( $id = null )
	{
		$response = $this->curl_get('navigation/'.$id);

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	//HEDAER
	public function gets_top_bar( $id = null )
	{
		if( $id ==  null)
			$response = $this->curl_get('topbar');
		else
			$response = $this->curl_get( 'topbar/'.$id );

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}

	public function get_top_bar( $id = null )
	{
		$response = $this->curl_get('topbar/'.$id);

		$response = json_decode( $response );

		if( !isset($response->status) || ( $response->status == 'error' || $response->status == '' ) ) return (object)['status' => 'error'];

		return  $response;
	}
}