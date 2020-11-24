<?php
if (
	empty($_POST['name']) 		||
	empty($_POST['email']) 		||
	empty($_POST['phone']) 		||
	empty($_POST['message'])		
	//|| !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)				//проверка мэйла на валидность
)
   {
	echo "No arguments Provided!";
	return false;
   }
  
$feedback_name = urldecode( $_POST['name'] );
$feedback_email = urldecode( $_POST['email'] );
$feedback_phone = urldecode( $_POST['phone'] );
$feedback_message = urldecode( $_POST['message'] );

	
// create email body and send it
//$to = 'info@beer-baths.com';
$to = 'igorosincev@gmail.com';
$email_subject = "Message from website www.beer-baths.com";
$email_body = "Message from website www.beer-baths.com.<br><br>
Name: <b>$feedback_name</b><br>
Email: <b>$feedback_email</b><br>
Phone: <b>$feedback_phone</b><br>
Message: <b>$feedback_message</b><br>";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8 \r\n";
$headers .= "From: feedback@beer-baths.com\n";
$headers .= "Reply-To: $feedback_email";	
mail($to,$email_subject,$email_body,$headers);
return true;
?>