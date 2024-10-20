<?php


session_start();

// Rate limiting settings
$limit = 1;
$timeFrame = 60;
$SecretPW = "4aVer43y_S3cr3tP4.?34-de03_*aspewfj_w.34.23+2";

function decrypt_aes($encrypted_iv, $key) {
    // Decodifica Base64 e separa IV dal testo criptato
    $encrypted_iv = base64_decode($encrypted_iv);
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($encrypted_iv, 0, $iv_length);
    $encrypted = substr($encrypted_iv, $iv_length);

    // Decripta la stringa
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    return $decrypted;
}

// Check for blocked IPs
$blocked_ips = ['192.168.1.1', '203.0.113.5'];
if (in_array($_SERVER['REMOTE_ADDR'], $blocked_ips)) {
    header('HTTP/1.1 403 Forbidden');
    echo "Access denied.";
    exit;
}

// Rate limiting logic
if (!isset($_SESSION['request_count'])) {
    $_SESSION['request_count'] = 0;
    $_SESSION['first_request_time'] = time();
}

if (time() - $_SESSION['first_request_time'] < $timeFrame) {
    $_SESSION['request_count']++;
} else {
    $_SESSION['request_count'] = 1;
    $_SESSION['first_request_time'] = time();
}

if ($_SESSION['request_count'] > $limit) {
    header('HTTP/1.1 429 Too Many Requests');
    echo "You have exceeded the request limit. Please try again later.";
    exit;
}

// Logging the request
file_put_contents('request_log.txt', date('Y-m-d H:i:s') . " - " . $_SERVER['REMOTE_ADDR'] . PHP_EOL, FILE_APPEND);


$Number = $_POST["number"];
$HashCode = $_POST["hash"];
$Mail = $_POST["mail"];
$NomeCompleto = $_POST["name"];
$luogoNascita = $_POST["luogoNascita"];
$CodeF = $_POST["CodeF"];
$phoneNum = $_POST["phoneNum"];
$NascitaDate = $_POST["NascitaDate"];
$gender = $_POST["gender"];
$indirizzoRes = $_POST["indirizzoRes"];
$Stato = $_POST["Stato"];
$Citta = $_POST["Citta"];
$Regione = $_POST["Regione"];
$PostalCode = $_POST["PostalCode"];
$Attivita = $_POST["Attivita"];


if(hash('sha256', $Number . $SecretPW ) != $HashCode){
    echo "C'è stato un errore. Per favore torna indietro e riprova. ";
    echo '<a href="nuovoSocio.html">Pagina iniziale</a>';
}else{
    $Mail = decrypt_aes($Mail, ($SecretPW . $Number));
    echo $Mail;
    echo $Attivita;

    header('Location: nuovoSocioThanks.html');
    exit();
}

