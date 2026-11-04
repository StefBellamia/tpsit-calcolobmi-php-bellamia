<?php
    $mostra_form = true;

    $ha_errore = "";

    $errore_nome = "";
    $errore_peso = "";
    $errore_altezza = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lettura dei dati inviati dal form
    $nome = isset($_POST['nome']) ? htmlspecialchars(trim($_POST['nome'])) : '';
    $pesoInput = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
    $altezzaInput = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

    if ($nome === '') {
        $nome = "NOT_VALID";
        $ha_errore = true;
        $errore_nome = "Valore non valido.";
    }

    if ($pesoInput == null || !is_numeric($pesoInput) || floatval($pesoInput) <= 0) {
        $peso = "NOT_VALID";
        $ha_errore = true;
        $errore_peso = "Valore non valido.";
    } else {
        $peso = $pesoInput;
    }

    if ($altezzaInput == null || !is_numeric($altezzaInput) || $altezzaInput <= 0) {
        $altezza_cm = "NOT_VALID";
        $ha_errore = true;
        $errore_altezza = "Valore non valido.";
    } else {
        $altezza_cm = $altezzaInput;
    }

    
    $bmi = 0;
    $fascia = '';
    $classe_card = '';

        if ($peso == "NOT_VALID" || $altezza_cm == "NOT_VALID") {
            $bmi = "NOT_VALID";
            $fascia = "NOT_VALID";
        } else {
            if ($peso > 0 && $altezza_cm > 0) {
                // Calcolo del BMI: peso / (altezza in m)^2
                $altezza_m = $altezza_cm / 100;
                $bmi = $peso / ($altezza_m * $altezza_m);

                // Determinazione della fascia e della classe CSS corrispondente
                if ($bmi < 18.5) {
                    $fascia = 'SOTTOPESO';
                    $classe_card = 'sottopeso';
                } elseif ($bmi <= 24.9) {
                    $fascia = 'NORMOPESO';
                    $classe_card = 'normopeso';
                } elseif ($bmi <= 29.9) {
                    $fascia = 'SOVRAPPESO';
                    $classe_card = 'sovrappeso';
                } else {
                    $fascia = 'OBESITÀ';
                    $classe_card = 'obesita';
                }
            }
            
        }
        if (!$ha_errore) {
            $mostra_form = false;
        
            $bmiFormatttato = ($bmi === "NOT_VALID") ? "NOT_VALID" : number_format($bmi, 2, '.', '');
            $data = date("d/m/Y,G:i:s");
            $ip = $_SERVER['REMOTE_ADDR'];

            $nomeFile = "log.csv";
            $file= fopen($nomeFile, "a");
            
            $record = implode(",", [$data, $ip, $nome, $altezza_cm, $peso, $bmiFormatttato, $fascia]);
            $record .= PHP_EOL;
            fwrite($file, $record);
            fclose($file);  
        }
        

}

?>

<?php if ($mostra_form): ?> 
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calcolatore BMI</title>
        <link rel="stylesheet" href="stile.css">
    </head> 
    <body>

        <div class="container">
            <h1>Calcolatore BMI</h1>
            <div class="card card-form">
                <form action="index.php" method="post">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" placeholder="Luca" value="<?php if (!empty($errore_nome)) { echo htmlspecialchars($nome); } ?>">
                        <?php if (!empty($errore_nome)): ?>
                            <div class="errore-messaggio">
                                <?php echo $errore_nome?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="peso">Peso (kg)</label>   <!-- Aggiungiamo un campo VALUE -->
                        <input type="text" id="peso" name="peso" step="any" placeholder="70" value="<?php if (!empty($errore_peso)) { echo htmlspecialchars($pesoInput); } ?>">
                        <?php if (!empty($errore_peso)): ?>
                            <div class="errore-messaggio">
                                <?php echo $errore_peso; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="altezza">Altezza (cm)</label>
                        <input type="text" id="altezza" name="altezza" step="any" placeholder="178" value="<?php if (!empty($errore_altezza)) { echo htmlspecialchars($altezzaInput); } ?>">
                        <?php if (!empty($errore_altezzza)): ?>
                            <div class="errore-messaggio">
                                <?php echo $errore_altezza; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn">Calcola</button>
                </form>
            </div>
        </div>

    </body>
    </html>
<?php else: ?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Il tuo BMI</title>
        <link rel="stylesheet" href="stile.css">
    </head>
    <body>

        <div class="container">
            <h1>Il tuo BMI</h1>
            
            <?php if ($nome != "NOT_VALID" && $peso != "NOT_VALID" && $bmi != "NOT_VALID"): ?>
                <div class="card card-risultato <?php echo $classe_card; ?>">
                    <div class="nome-utente"><?php echo $nome; ?></div>
                    <div class="dati-inseriti">
                        Peso <?php echo $peso; ?> kg &middot; Altezza <?php echo $altezza_cm; ?> cm
                    </div>
                    <div class="valore-bmi">
                        <?php echo number_format($bmi, 2, '.', ''); ?>
                    </div>
                    <div class="testo-fascia">
                        <?php echo $fascia; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card card-form" style="text-align: center;">
                    <p>Nessun dato valido ricevuto.</p>
                </div>
            <?php endif; ?>

            <a href="index.php" class="link-riprova">Calcola di nuovo</a>
        </div>

    </body>
    </html>
<?php endif ?>