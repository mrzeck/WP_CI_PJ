<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('send_mail')) {
	function send_mail($param = array())
	{

		$default = array(
			'from_email' => '',
			'fullname'	 => '',
			'to_email'	 => '',
			'subject'	 => '',
			'content'	 => '',
		);

		$param = array_merge($default, $param);

		$config = array(
			//gửi email từ
			'from_email' => $param['from_email'],
			//tên người gửi
			'fullname'   => $param['fullname'],
			//gửi đến mail
			'to_email'   => $param['to_email'],
			//tiêu đề mail
			'subject'    => $param['subject'],
			//nội dung mail
			'content'    => $param['content'],
		);

		$CI =& get_instance();

		$CI->load->library('skd_mail');

		$mail = new skd_mail($config);

		$mail->set_user( get_option('smtp-user', $mail->user) );

		$mail->set_pass( get_option('smtp-pass', $mail->pass) );

		$mail->set_host( get_option('smtp-server', $mail->host) );

		$mail->set_port( get_option('smtp-port', $mail->port) );

		return $mail->send();
	}
}