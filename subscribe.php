<?php

$SecretPW = "4aVer43y_S3cr3tP4.?34-de03_*aspewfj_w.34.23+2";

$Number = $_GET["n"];
$HashCode = $_GET["q"];
$Mail = $_GET["m"];

if(hash('sha256', $Number . $SecretPW ) != $HashCode){
    echo "C'è stato un errore. Per favore torna indietro e riprova. ";
    echo '<a href="nuovoSocio.html">Pagina iniziale</a>';
}else{
    
    echo '  <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <meta http-equiv="X-UA-Compatible" content="ie=edge" />
                <title>Registrazione</title>
                <!---Custom CSS File--->
                <link rel="stylesheet" href="css/style2.css" />
            </head>
            <body>
                <section class="container">
                <header>Registrazione nuovo socio</header>
                <form id="myForm" action="submit.php" class="form" method="POST">
                    <div class="input-box">
                    <label>Nome completo</label>
                    <input type="text" placeholder="Nome e Cognome" name="name" required />
                    </div>

                    <div class="input-box">
                    <label>Luogo di Nascita</label>
                    <input type="text" placeholder="Luogo di Nascita" name="luogoNascita" required />
                    </div>
                    <input type="text" style="display: none;" name="mail" value="' . $Mail . '" required/>
                    <input type="text" style="display: none;" name="number" value="' . $Number . '" required/>
                    <input type="text" style="display: none;" name="hash" value="' . $HashCode . '" required/>
                    <div class="input-box">
                    <label>Codice Fiscale</label>
                    <input type="text" placeholder="Inserisci Codice Fiscale" name="CodeF" required />
                    </div>

                    <div class="column">
                    <div class="input-box">
                        <label>Numero di telefono</label>
                        <input type="text" placeholder="(+39) 1234567890" name="phoneNum" required />
                    </div>
                    <div class="input-box">
                        <label>Data di nascita</label>
                        <input type="date" placeholder="Enter birth date" name="NascitaDate" required />
                    </div>
                    </div>
                    <div class="gender-box">
                    <h3>Genere</h3>
                    <div class="gender-option">
                        <div class="gender">
                        <input type="radio" id="check-male" name="gender" value="Maschio" checked />
                        <label for="check-male">Maschio</label>
                        </div>
                        <div class="gender">
                        <input type="radio" id="check-female" name="gender" value="Femmina" />
                        <label for="check-female">Femmina</label>
                        </div>
                        <div class="gender">
                        <input type="radio" id="check-other" name="gender" value="Altro" />
                        <label for="check-other">Altro</label>
                        </div>
                    </div>
                    </div>
                    <div class="input-box address">
                        <label>Indirizzo di residenza</label>
                        <input type="text" placeholder="Inserisci l\'indirizzo di residenza" name="indirizzoRes" required />
                        <div class="column">
                            <input type="text" placeholder="Stato" name="Stato" required />
                            <input type="text" placeholder="Città" name="Citta" required />
                        </div>
                        <div class="column">
                            <input type="text" placeholder="Regione" name="Regione" required />
                            <input type="number" placeholder="Codice Postale" name="PostalCode" required />
                        </div>
                    </div>
                    <div class="input-box">
                    <label>Attività lavorativa</label>
                    <input type="text" placeholder="inserisci di cosa ti occupi" name="Attivita" required />
                    </div>
                    <button>Invia</button>
                </form>
                </section>
            </body>
            <script>
                document.getElementById(\'myForm\').addEventListener(\'submit\', function(event) {
                    
                    event.preventDefault(); 

                    // Mostra il messaggio di conferma
                    const confirmation = confirm(\'Sei sicuro di voler inviare il modulo?\');

                    if (confirmation) {
                        this.submit(); // Invia il modulo
                    } else {
                        alert(\'Invio annullato.\');
                    }
                });
            </script>
            </html>';
}
