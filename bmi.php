<?php
// Lettura dei dati inviati dal form
$nome = isset($_POST['nome']) ? htmlspecialchars(trim($_POST['nome'])) : '';
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
$altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

$bmi = 0;
$fascia = '';
$classe_card = '';

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
?>
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
        
        <?php if ($peso > 0 && $altezza_cm > 0): ?>
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

        <a href="form.html" class="link-riprova">Calcola di nuovo</a>
    </div>

</body>
</html>