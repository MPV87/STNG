<?php
// send.php
header('Content-Type: application/json; charset=utf-8');

function clean($v) {
  return trim(str_replace(["\r","\n"], [" "," "], (string)$v));
}

$name    = clean($_POST['Name'] ?? '');
$email   = clean($_POST['E-Mail'] ?? '');
$dose    = clean($_POST['Gewünschte Dose'] ?? '');
$land    = clean($_POST['Land'] ?? '');
$adresse = clean($_POST['Adresse'] ?? '');
$plzort  = clean($_POST['PLZ / Ort'] ?? '');
$age     = clean($_POST['18+ bestätigt'] ?? '');
$privacy = clean($_POST['Datenschutz akzeptiert'] ?? '');

if ($name==='' || $email==='' || $dose==='' || $land==='' || $adresse==='' || $plzort==='' || $age!== 'Ja' || $privacy!=='Ja') {
  http_response_code(400);
  echo json_encode(['ok'=>false,'msg'=>'Bitte alle Pflichtfelder ausfüllen.']);
  exit;
}

$to = "office@kp-plattner.at";
$subject = "STNG-Aktion";
$message =
"Neue STNG-Aktion Anmeldung:\n\n".
"Name: $name\n".
"E-Mail: $email\n".
"Gewünschte Dose: $dose\n".
"Land: $land\n".
"Adresse: $adresse\n".
"PLZ / Ort: $plzort\n".
"18+ bestätigt: $age\n".
"Datenschutz akzeptiert: $privacy\n";

$headers = "From: no-reply@{$_SERVER['HTTP_HOST']}\r\n".
           "Reply-To: $email\r\n".
           "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($to, $subject, $message, $headers);

echo json_encode(['ok'=>$sent]);
