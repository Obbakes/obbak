<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BaseController extends CI_Controller
{
	 

	function __construct()
	{
		parent::__construct();
	 

	}
	public function setAlertError($message){
		$this->setAdvice("aviso_error",$message);
	}
	public function setAlertWarning($message){
		$this->setAdvice("aviso_warning",$message);
	}
	public function setAlertInfo($message){
		$this->setAdvice("aviso_info",$message);
	}
	public function setAlertOk($message){
		$this->setAdvice("aviso_ok",$message);
	}
	public function setAdvertError($message){
		$this->setAdvice("aviso_error",$message);
	}
	public function setAdvertWarning($message){
		$this->setAdvice("aviso_warning",$message);
	}
	public function setAdvertInfo($message){
		$this->setAdvice("aviso_info",$message);
	}
	public function setAdvertOk($message){
		$this->setAdvice("aviso_ok",$message);
	}
	public function setAdviceError($message){
		$this->setAdvice("aviso_error",$message);
	}
	public function setAdviceWarning($message){
		$this->setAdvice("aviso_warning",$message);
	}
	public function setAdviceInfo($message){
		$this->setAdvice("aviso_info",$message);
	}
	public function setAdviceOk($message){
		$this->setAdvice("aviso_ok",$message);
	}
	private function setAdvice($type,$message){
		$this->load->library('session');
		$this->session->set_userdata(array("setAdvice".$type => $message));
	}
 
}

?>