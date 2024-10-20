<?php
// Inclure la classe FormManager
require_once 'FormManager.php';

// Initialisation du formulaire
$form = new FormManager('index.php', 'POST', 'formulaire_id', ['form-style']);

// Ajouter des champs au formulaire
$form->ajouterChamp('email', 'Email', 'email', true, [], null, null, [], 'inline', true);
$form->ajouterChampConfirmation('mot_de_passe', 'Mot de passe', 'mot_de_passe_confirmation', 'Confirmer le mot de passe', true, 'inline', true);
$form->ajouterGroupeCheckbox('conditions', 'Accepter les conditions', ['1' => 'Oui'], true, 'block', true);

// Validation du formulaire lorsqu'il est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($form->valider()) {
        $form->setMessage("Formulaire soumis avec succès !");
    } else {
        $form->setMessage("Il y a des erreurs dans le formulaire !");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h1>Inscription</h1>

        <?php
        // Affichage des messages de succès ou d'erreur
        $form->afficherMessages();
        //$form->afficherErreurs();
        
        // Affichage du formulaire
        $form->afficherFormulaire();
        ?>
    </div>
</body>
</html>
