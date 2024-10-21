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
    echo "C'è stato un errore. Per favore torna indietro e riprova.";
    echo '<a href="nuovoSocio.html">Pagina iniziale</a>';
}else{
    $Mail = decrypt_aes($Mail, ($SecretPW . $Number));

    // Define the subject of the email
    $subject = "Nuova richiesta tesseramento AIRONE";
    // Define the message body of the email
    $message = '<!DOCTYPE html>
            <html lang="it">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Parametri PHP</title>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                <h3>Ciao, una nuova richiesta di tesseramento in arrivo:</h3>
                <p><br>' . $NomeCompleto . '|' . $gender . '|' . $CodeF . '|' . $luogoNascita . '|' . $NascitaDate . '|' . $phoneNum . '|' . $Mail . '|' . $indirizzoRes . '|' . $PostalCode . '|' . $Citta . '|' . $Regione . '|' . $Stato . '|' . $Attivita . '<br></p>
                <table>
                    <thead>
                        <tr>
                            <th>Nome Campo</th>
                            <th>Valore</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mail</td>
                            <td>'.$Mail.'</td>
                        </tr>
                        <tr>
                            <td>Nome Completo</td>
                            <td>'.$NomeCompleto.'</td>
                        </tr>
                        <tr>
                            <td>Luogo di Nascita</td>
                            <td>'.$luogoNascita.'</td>
                        </tr>
                        <tr>
                            <td>Codice Fiscale</td>
                            <td>'.$CodeF.'</td>
                        </tr>
                        <tr>
                            <td>Numero di Telefono</td>
                            <td>'.$phoneNum.'</td>
                        </tr>
                        <tr>
                            <td>Data di Nascita</td>
                            <td>'.$NascitaDate.'</td>
                        </tr>
                        <tr>
                            <td>Genere</td>
                            <td>'.$gender.'</td>
                        </tr>
                        <tr>
                            <td>Indirizzo Residenza</td>
                            <td>'.$indirizzoRes.'</td>
                        </tr>
                        <tr>
                            <td>Stato</td>
                            <td>'.$Stato.'</td>
                        </tr>
                        <tr>
                            <td>Città</td>
                            <td>'.$Citta.'</td>
                        </tr>
                        <tr>
                            <td>Provincia</td>
                            <td>'.$Regione.'</td>
                        </tr>
                        <tr>
                            <td>Codice Postale</td>
                            <td>'.$PostalCode.'</td>
                        </tr>
                        <tr>
                            <td>Attività</td>
                            <td>'.$Attivita.'</td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>';
    // Define the headers (e.g., From, Reply-To, etc.)
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@aironeaps.it" . "\r\n";

    mail("info@aironeaps.it", $subject, $message, $headers);



    $subject = "Richiesta inviata con successo!";
    // Define the message body of the email
    $message = '<!DOCTYPE html>
            <html lang="it">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Grazie per la Registrazione</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    }
                    p {
                        color: #555;
                        line-height: 1.6;
                    }
                    footer {
                        margin-top: 20px;
                        font-size: 12px;
                        color: #777;
                    }
                        table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <p>Ciao '.$NomeCompleto.', grazie per la tua Registrazione!</p>
                    <p>La tua richiesta è stata ricevuta e sarà ora esaminata dal nostro consiglio di amministrazione. Siamo entusiasti di averti con noi e ci impegniamo a fornirti un\'esperienza di alta qualità.</p>
                    <p>Riceverai via email il resoconto finale della tua richiesta, quindi assicurati di controllare la tua casella di posta.</p>
                    <p>Di seguito la tabella con le informazioni personali che ci hai fornito: </p>
                    <table>
                        <thead>
                            <tr>
                                <th>Nome Campo</th>
                                <th>Valore</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mail</td>
                                <td>'.$Mail.'</td>
                            </tr>
                            <tr>
                                <td>Nome Completo</td>
                                <td>'.$NomeCompleto.'</td>
                            </tr>
                            <tr>
                                <td>Luogo di Nascita</td>
                                <td>'.$luogoNascita.'</td>
                            </tr>
                            <tr>
                                <td>Codice Fiscale</td>
                                <td>'.$CodeF.'</td>
                            </tr>
                            <tr>
                                <td>Numero di Telefono</td>
                                <td>'.$phoneNum.'</td>
                            </tr>
                            <tr>
                                <td>Data di Nascita</td>
                                <td>'.$NascitaDate.'</td>
                            </tr>
                            <tr>
                                <td>Genere</td>
                                <td>'.$gender.'</td>
                            </tr>
                            <tr>
                                <td>Indirizzo Residenza</td>
                                <td>'.$indirizzoRes.'</td>
                            </tr>
                            <tr>
                                <td>Stato</td>
                                <td>'.$Stato.'</td>
                            </tr>
                            <tr>
                                <td>Città</td>
                                <td>'.$Citta.'</td>
                            </tr>
                            <tr>
                                <td>Provincia</td>
                                <td>'.$Regione.'</td>
                            </tr>
                            <tr>
                                <td>Codice Postale</td>
                                <td>'.$PostalCode.'</td>
                            </tr>
                            <tr>
                                <td>Attività</td>
                                <td>'.$Attivita.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>Se hai domande o necessiti di ulteriori informazioni, non esitare a contattarci.</p>
                    <footer>
                        <p>Team di Supporto<br>info@aironeaps.it</p>
                    </footer>
                </div>
            </body>
            </html>';

    // Define the headers (e.g., From, Reply-To, etc.)
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@aironeaps.it" . "\r\n";

    mail($Mail, $subject, $message, $headers);

    header('Location: nuovoSocioThanks.html');
    exit();
}

