<?php
	/*
	 * v1 - basic OTP class
	 *
	 * TEMPLATE CODE TO IMPLEMENT CLASS
		include_once("Otp.class.php");
		$otpObj = new OTP();
		$newOTP = $otpObj->generateOTP(4);
		$otpObj->setAddress($contact);
		$otpObj->setOTPMessage($msg);
		$otpObj->sendOTP();
	 *
	 */
	class OTP{
		private $to;
		private $admin = '';
		private $defaultFrom = "TXTLCL";
		private $from = '';
		private $userName = "info@fourwalls.in";
		private $password = "15648ff31c110233efc1be8bff997c69bf4fceb8";

		function __construct(){
			$this->from = $this->defaultFrom;
		}
		
		function setAddress($to){
			$this->to = $to;
		}
		function setAdminAddress($to){
			$this->admin = $to;
		}
		function setFromAddress($from){
			$this->from = $from;
		}
		function sendAsTransactionalSMS(){
			$this->from = "FWALLS"; //  for fourwalls.in
		}
		function setOTPMessage($msg){
			$this->msg = $msg;
		}
		function sendOTP(){
			if(PROJECTSTATUS!="LIVE"){ // DO NOT WASTE SMS
				return true;
			}

			if(!isset($this->to) || empty($this->to)){
				return false;
			}
			if(!isset($this->msg) || empty($this->msg)){
				return false;
			} else {
				$this->msg = urlencode($this->msg);
			}

			// UNCOMMENT
			$smsURL = "http://api.textlocal.in/send/?username=".$this->userName."&hash=".$this->password."&sender=".$this->from."&numbers=91".$this->to."&message=".$this->msg;
			// UNCOMMENT

			/* {"balance":1009,
			 "batch_id":247242130,
			 "cost":1,
			 "num_messages":1,
			 "message":{
					"num_parts":1,
					"sender":"TXTLCL",
					"content":"Your 4 Walls account registration OTP is 4635"
			 },
			 "receipt_url":"",
			 "custom":"",
			 "messages":[{"id":"129612329","recipient":918108734228}],
			 "status":"success"
			 } */

			$responseStr = file_get_contents($smsURL);
			$responseArr = json_decode($responseStr, true);
			// == TEST ==
				// echo "<pre>";
				// print_r($responseArr);
				// echo "</pre>";
			// == TEST ==
			if($responseArr["status"]=="success"){
				// send a copy to ADMIN
				if(isset($this->admin) && !empty($this->admin)){
					// UNCOMMENT
					$smsURL = "http://api.textlocal.in/send/?username=".$this->userName."&hash=".$this->password."&sender=".$this->from."&numbers=91".$this->admin."&message=".$this->msg;
					// UNCOMMENT
					$responseStr = file_get_contents($smsURL);
					$this->from = $this->defaultFrom; // RESET SMS TO PROMOTIONAL
				}
				return true;
			} else {
				return false;
			}
		}
		function generateOTP($length){
			$id = substr(str_shuffle("12345678901234567890"), 0, $length);
			return $id;
		}
	}
	
?>