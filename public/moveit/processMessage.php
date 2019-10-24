<?php
$post_data = file_get_contents("php://input");
$data = json_decode($post_data, true);

$subject = "Mensagem de ".$$data['form_name'];
$email = $data["form_mail"];
$message = "Cliente: ".$data['form_name']."\r\nContacto: ".$data['form_number']."\r\nPaís: ".$data['form_country']."\r\n\r\n".$data['form_message'];
$emailTo = "diogocardosoluis@gmail.com";

$headers = "From: ".$email. "\r\n";

mail($emailTo, $subject, $message, $headers);
?>