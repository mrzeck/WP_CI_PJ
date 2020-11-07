<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class skd_mail{

	private $CI;

	public  $params  = NULL;
	
	public $user     = 'clevn.mail@gmail.com';
	
	public $pass     = 'daprsyfoznmeyqlp';
	
	public $protocol = 'smtp';
	
	public $port     = 465;
	
	public $host     = 'ssl://smtp.googlemail.com';

	function __construct($params = NULL){

		$this->CI =& get_instance();

		$this->params = $params;
	}
	/**
	 * [set_user set user]
	 * @param string $user [description]
	 */
	public function set_user($user ='')
	{
		$this->user = $user;
	}
	/**
	 * [set_pass set pass]
	 * @param string $pass [description]
	 */
	public function set_pass($pass ='')
	{
		$this->pass = $pass;
	}
	/**
	 * [set_port set port]
	 * @param string $port [description]
	 */
	public function set_port($port ='')
	{
		$this->port = $port;
	}

	/**
	 * [set_host set host]
	 * @param string $host [description]
	 */
	public function set_host($host ='')
	{
		$this->host = $host;
	}

	public function set_protocol($protocol ='')
	{
		$this->protocol = $protocol;
	}
	/**
	 * [send send mail]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function send()
	{

		if( $this->host == 'smtp.gmail.com' ) {

			return $this->Mailer_send();
		}
		else {

			return $this->ci_send();
		}
	}

	public function ci_send()
	{	
		$config = Array(
	        'protocol' 	=> $this->protocol, 
	        'smtp_host' => $this->host, 
	        'smtp_port' => $this->port, 
	        'smtp_user' => $this->user, 
	        'smtp_pass' => $this->pass,
	        'mailtype' 	=> 'html',
	        'charset' 	=> 'utf-8',
	        'newline' 	=> "\r\n",
        );

        $this->CI->load->library('email', $config);

        $this->CI->email->set_newline("\r\n");

        $this->CI->email->from($this->params['from_email'], $this->params['fullname']);

        $this->CI->email->to($this->params['to_email']);

        $this->CI->email->subject($this->params['subject']); 

        $this->CI->email->message($this->params['content']);

        stream_context_set_default([
			'ssl' => [
				'verify_peer'		=> false,
				'verify_peer_name' 	=> false,
			]
		]);

        if (!$this->CI->email->send()) {
            return $this->CI->email->print_debugger();
        }
        else
        {
          	return NULL;
        }
	}

	public function Mailer_send()
	{

		include "class.phpmailer.php"; 

		include "class.smtp.php"; 

		include "class.pop3.php";

		$mail = new PHPMailer();
		//Khai báo gửi mail bằng SMTP
		$mail->IsSMTP();
		
		$mail->SMTPDebug   = 0;
		$mail->CharSet     = "utf-8";
		$mail->Debugoutput = "html"; // Lỗi trả về hiển thị với cấu trúc HTML
		$mail->Host        = $this->host; //host smtp để gửi mail
		$mail->Port        = $this->port; // cổng để gửi mail
		$mail->SMTPSecure  = "ssl"; //Phương thức mã hóa thư - ssl hoặc tls
		$mail->SMTPAuth    = true; //Xác thực SMTP
		$mail->Username    = $this->user; // Tên đăng nhập tài khoản Gmail
		$mail->Password    = $this->pass;//$config['smtp_pass']; //Mật khẩu của gmail
		$mail->SetFrom( $this->params['from_email'] , $this->params['fullname']); // Thông tin người gửi
		$mail->AddReplyTo( $this->params['to_email'],"Reply");// Ấn định email sẽ nhận khi người dùng reply lại.
		$mail->AddAddress( $this->params['to_email'], "");//Email của người nhận
		$mail->Subject     = $this->params['subject']; //Tiêu đề của thư
		$mail->MsgHTML( $this->params['content'] ); //Nội dung của bức thư.

		//Tiến hành gửi email và kiểm tra lỗi
		if(!$mail->Send()) {
			return "Có lỗi khi gửi mail: " . $mail->ErrorInfo;
		} else {
			return true;
		}
	}

}