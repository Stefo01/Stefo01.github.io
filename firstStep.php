<?php
session_start();

// Rate limiting settings
$limit = 2;
$timeFrame = 60;
$SecretPW = "4aVer43y_S3cr3tP4.?34-de03_*aspewfj_w.34.23+2";

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

$EmailFirst = $_POST["mail"];

function generateUniqueRandomNumbers($count, $min, $max) {
    if ($count > ($max - $min + 1)) {
        throw new Exception("Count exceeds the range of unique numbers.");
    }

    $numbers = range($min, $max); // Create an array of numbers from min to max
    shuffle($numbers); // Shuffle the array to randomize
    return array_slice($numbers, 0, $count); // Return the first $count numbers
}

$randomNumbers = generateUniqueRandomNumbers(6, 0, 9);

$NumberString = implode("", $randomNumbers);

// Define the subject of the email
$subject = "Nuovo socio Airone APS";
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
                h1 {
                    color: #333;
                }
                .code {
                    display: inline-block;
                    padding: 10px 20px;
                    font-size: 24px;
                    font-weight: bold;
                    color: #fff;
                    background-color: #066023;
                    border-radius: 5px;
                    margin: 20px 0;
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
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Grazie per la tua Registrazione!</h1>
                <p>Ciao,</p>
                <p>Grazie per aver iniziato a compilare il modulo di iscrizione sul nostro sito. Siamo entusiasti di averti con noi!</p>
                <p>Per completare la registrazione, ti preghiamo di utilizzare il seguente codice numerico:</p>
                <div class="code">' . $NumberString .'</div>
                <p>Inserisci questo codice nel sito per completare la tua registrazione. Se hai domande, non esitare a contattarci.</p>
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

mail($EmailFirst, $subject, $message, $headers);

// TODO invia mail
// Define the subject of the email
// $subject = "Test Email from PHP";

// // Define the message body of the email
// $message = "Hello, this is a test email sent from a PHP script!";

// // Define the headers (e.g., From, Reply-To, etc.)
// $headers = "From: no-reply@aironeaps.it" . "\r\n" .
//            "X-Mailer: PHP/" . phpversion();

// // Use the mail() function to send the email
// if(mail($to, $subject, $message, $headers)) {
//     echo "Email sent successfully!";
// } else {
//     echo "Failed to send email.";
// }



// cripto aes256
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
$encrypted = openssl_encrypt($EmailFirst, 'aes-256-cbc', ($SecretPW . $NumberString), 0, $iv);
$encrypted_iv = base64_encode($iv . $encrypted);


echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Code</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            flex-direction: column;
            text-align: center;
        }

        .code-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }

        input[type="text"] {
            width: 50px;
            height: 50px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Nasconde le frecce degli input numerici */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .resend-btn {
            background-color: #066023;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .resend-btn:hover {
            background-color: #054118;
        }

        .resend-message {
            font-size: 14px;
            color: #777;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Verifica la tua email</h2>
    <p>Inserisci sotto il codice che ti abbiamo inviato per email <br> e alla fine premi invio</p>

    <div class="code-container">
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
    </div>

    <button class="resend-btn" onclick="resendEmail()">Reinvia il codice</button>
    <p class="resend-message" id="resendMessage"></p>

    <script>
        const inputs = document.querySelectorAll(\'input[type="text"]\');

        function getCode() {
            return Array.from(inputs).map(input => input.value).join(\'\'); // Concatena i valori
        }

        function sendCode(inpu) {
            window.location.href = \'subscribeb.php?n=\' + inpu + \'&q='. hash('sha256', $NumberString . $SecretPW ) .'&m=' . $encrypted_iv . '\';
        }
        
        // Funzione per passare automaticamente al campo successivo dopo aver inserito un numero
        inputs.forEach((input, index) => {
            input.addEventListener(\'input\', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                if (Array.from(inputs).every(i => i.value !== \'\')) {
                    const completeCode = getCode(); // Ottieni il codice completo
                    sendCode(completeCode);
                }
            });
            input.addEventListener(\'keydown\', (e) => {
                if (e.key === \'Backspace\' && input.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
        // Funzione per simulare il reinvio dell\'email
        function resendEmail() {
            document.getElementById(\'resendMessage\').innerText = \'Un nuovo codice è stato inviato alla tua email\';
            // Logica reale per reinviare l\'email dovrebbe essere aggiunta qui.
        }
    </script>

</body>
</html>'; 

// TODO invia mail

