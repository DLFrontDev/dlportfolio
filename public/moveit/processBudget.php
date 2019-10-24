<?php
$post_data = file_get_contents("php://input");
$data = json_decode($post_data, true);

$subject = "Orçamento de ".$data['form_name'];
$email = $data["form_mail"];
$message = "Cliente: ".$data['form_name']."\r\nContacto: ".$data['form_number']."\r\nData prevista: ".$data['form_date']."\r\n\r\n";
$message .= "Local de Carga: ".$data['form_load_addr']."\r\nLocalidade: ".$data['form_load_local']."\r\nCódigo Postal: ".$data['form_load_postal']."\r\nNr de Assoalhadas: ".$data['form_load_room']."\r\nPossui elevador: ".$data['form_load_elevator']."\r\n\r\n";
$message .= "Local de Descarga: ".$data['form_unload_addr']."\r\nLocalidade: ".$data['form_unload_local']."\r\nCódigo Postal: ".$data['form_unload_postal']."\r\nNr de Assoalhadas: ".$data['form_unload_room']."\r\nPossui elevador: ".$data['form_unload_elevator']."\r\n\r\n";
$message .= "Necessita Caixas: ".$data['form_boxes']."\r\nMontagem/Desmontagem de Móveis: ".$data['form_furn']."\r\n\r\nObservações: ".$data['form_comment']."\r\n\r\n";
if (strlen($data['form_other'])==0) {
	$message .= "Publicitação: ".$data['form_info']."\r\n";
} else {
	$message .= "Publicitação: ".$data['form_other']."\r\n";
}
$emailTo = "diogocardosoluis@gmail.com";

$headers = "From: ".$email. "\r\n";

mail($emailTo, $subject, $message, $headers);
?>