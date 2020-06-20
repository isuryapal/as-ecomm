<?php
	/*
	 * v1 - basic email class
	 * v1.1 - removed support for ADMIN email
	 *
	 *
	 * TEMPLATE CODE TO IMPLEMENT CLASS
		$newCustomerDetails = $user->addSomething($_POST);
		include_once("member-register-email.inc.php");
		include_once("Email.class.php");
		$emailObj = new Email();
		$emailObj->setAddress($email);
		$emailObj->setSubject("Welcome to example.com");
		$emailObj->setEmailBody($emailMsg);
		$emailObj->sendEmail();
	 *
	 */
	class Email{
		private $to;
		// private $admin = ADMIN_EMAIL; // DEPRECATED
		private $admin = '';
		private $from = "noreply@fourwalls.in";
		private $subject;
		function setAddress($to){
			$this->to = $to;
		}
		function setAdminAddress($to){
			$this->admin = $to;
		}
		function setFromAddress($from){
			$this->from = $from;
		}
		function setSubject($subject){
			$this->subject = $subject;
		}
		function setEmailBody($msg){
			$this->msg = $msg;
		}
		function sendEmail(){
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html;charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$this->from."\r\n";
			if(isset($this->admin) && !empty($this->admin)){
				$headers .= 'Bcc: '.$this->admin."\r\n";
				// $headers .= 'CC: '.$this->admin."\r\n";
			}

			// echo '<pre>';
			// print_r( $this->to );
			// print_r( $this->subject );
			// print_r( $this->msg );
			// print_r( $headers );
			// echo '</pre>';
			// die();
			if(!isset($this->to) || empty($this->to)){
				return false;
			}
			if(!isset($this->subject) || empty($this->subject)){
				return false;
			}
			if(!isset($this->msg) || empty($this->msg)){
				return false;
			}
			if( mail($this->to, $this->subject, $this->msg, $headers) ){
				return true;
			} else {
				return false;
			}
		}
	}
	
?>