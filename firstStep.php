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

// cripto aes256
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
$encrypted = openssl_encrypt($EmailFirst, 'aes-256-cbc', ($SecretPW . $NumberString), 0, $iv);
$encrypted_iv = base64_encode($iv . $encrypted);


echo "Your new code to insert: " . $NumberString;

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
            window.location.href = \'subscribe.php?n=\' + inpu + \'&q='. hash('sha256', $NumberString . $SecretPW ) .'&m=' . $encrypted_iv . '\';
        }
        
        // Funzione per passare automaticamente al campo successivo dopo aver inserito un numero
        inputs.forEach((input, index) => {
            input.addEventListener(\'input\', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener(\'keydown\', (e) => {
                if (e.key === \'Backspace\' && input.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
                if (index === inputs.length - 1 && Array.from(inputs).every(i => i.value !== \'\')) {
                    const completeCode = getCode(); // Ottieni il codice completo
                    sendCode(completeCode);
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

