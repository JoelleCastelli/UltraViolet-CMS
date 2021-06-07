<?php

namespace App\Core;

use App\Core\PhpMailer\PHPMailer;
require "PHPMailer/PHPMailerAutoload.php";

class Mail
{

	private string $Host;
	private string $Port;
    private string $SMTPSecure;
	private string $Username;
	private string $Password;

	public function __construct(){
		$this->Host = 'smpt.gmail.com';
		$this->Port = 465;
        $this->SMTPSecure = 'ssl';
		$this->Username = 'ultravioletcms@gmail.com';
		$this->Password = 'Ultraviolet123';
	}

	public function setMail($to, $from, $from_name, $subject, $body) {
		$mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = $this->SMTPSecure; 
        $mail->Host = $this->Host;
        $mail->Port = $this->Port;  
        $mail->Username = $this->Username;
        $mail->Password = $this->Password;
        
        $mail->IsHTML(true);
        $mail->From=$this->Username;
        $mail->FromName=$from_name;
        $mail->Sender=$from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        if(!$mail->Send())
        {
            $error ="Please try Later, Error Occured while Processing...";
            return $error; 
        }
        else 
        {
            $error = "Thanks You !! Your email is sent.";  
            return $error;
        }
	}

}