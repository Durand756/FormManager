![Form](https://github.com/user-attachments/assets/b53a6c06-bfec-4e19-a443-d89f7c1a0ad4)
# 👁‍🗨FormManager - Bibliothèque PHP de Gestion de Formulaires

## Description

`FormManager` est une bibliothèque PHP qui facilite la création et la gestion de formulaires HTML. Elle permet d'ajouter des champs de formulaire avec validation, d'afficher des erreurs et des messages de manière flexible, et de personnaliser l'apparence des champs avec des classes CSS et des identifiants uniques.

Cette bibliothèque est conçue pour les développeurs PHP qui cherchent à gagner du temps et à améliorer la sécurité et la structure de leurs formulaires.

## Fonctionnalités

- **Création de formulaires dynamiques** : Ajoutez des champs avec des types divers tels que texte, email, mot de passe, etc.
- **Validation des champs** : Validation des données avec des règles standard et personnalisées.
- **Affichage flexible des erreurs** : Vous pouvez choisir où afficher les erreurs pour chaque champ.
- **Gestion des messages de succès** : Permet d'afficher des messages de succès après une soumission valide.
- **Personnalisation complète** : Chaque champ peut avoir un ID et des classes CSS personnalisés.

## Installation

1. Clonez ou téléchargez ce repository.
2. Assurez-vous que PHP est installé sur votre serveur local.
3. Incluez simplement le fichier `FormManager.php` dans votre projet.

```php
require_once 'FormManager.php';
```

## Utilisation

### Initialisation du Formulaire

Pour commencer, créez une instance de la classe `FormManager` en spécifiant l'action du formulaire, la méthode de soumission, et optionnellement un ID ou des classes CSS.

#### Syntaxe :

```php
$form = new FormManager($action = "", $methode = "POST", $id = null, $classes = []);
```

#### Exemple :

```php
$form = new FormManager('index.php', 'POST', 'formulaire_id', ['form-style']);
```

### Ajouter un Champ

Vous pouvez ajouter des champs de formulaire avec `ajouterChamp()`. Cette fonction accepte plusieurs arguments pour personnaliser le champ.

#### Syntaxe :

```php
$form->ajouterChamp($nom, $label, $type = "text", $obligatoire = false, $options = [], $validationCallback = null, $id = null, $classes = []);
```

#### Exemple :

```php
$form->ajouterChamp('email', 'Email', 'email', true, ['placeholder' => 'Votre email'], null, 'email_input', ['input-class']);
```

- **$nom** : Le nom du champ (doit être unique dans le formulaire).
- **$label** : Le texte du label pour le champ.
- **$type** : Le type du champ (`text`, `email`, `password`, etc.).
- **$obligatoire** : Indique si le champ est requis (`true` ou `false`).
- **$options** : Options supplémentaires comme `id`, `placeholder`, etc.
- **$validationCallback** : Fonction de validation personnalisée (voir ci-dessous).
- **$id** : Identifiant HTML pour le champ.
- **$classes** : Classes CSS à appliquer au champ.

### Ajouter un Champ de Confirmation (Mot de Passe)

Pour ajouter un champ de confirmation (comme un mot de passe et sa confirmation), vous pouvez utiliser la fonction `ajouterChampConfirmation()`.

#### Syntaxe :

```php
$form->ajouterChampConfirmation($nom, $label, $nomConfirmation, $labelConfirmation, $obligatoire = false);
```

#### Exemple :

```php
$form->ajouterChampConfirmation('mot_de_passe', 'Mot de passe', 'mot_de_passe_confirmation', 'Confirmer le mot de passe', true);
```

### Validation des Champs

Pour valider les champs après la soumission du formulaire, utilisez la méthode `valider()`. Elle retourne `true` si tous les champs sont valides, sinon elle retourne `false`.

#### Exemple :

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($form->valider()) {
        echo "Formulaire soumis avec succès !";
    } else {
        echo "Il y a des erreurs dans le formulaire.";
    }
}
```

### Ajouter un Groupe de Cases à Cocher

Pour ajouter un groupe de cases à cocher (checkboxes), vous pouvez utiliser la fonction `ajouterGroupeCheckbox()`.

#### Syntaxe :

```php
$form->ajouterGroupeCheckbox($nom, $label, $options = [], $obligatoire = false);
```

#### Exemple :

```php
$form->ajouterGroupeCheckbox('conditions', 'Accepter les conditions', ['1' => 'Oui'], true);
```

### Affichage des Erreurs

Chaque champ peut afficher une erreur lorsqu'une validation échoue. Par défaut, les erreurs sont affichées sous chaque champ. Vous pouvez personnaliser où afficher chaque erreur en utilisant `setErrorLocation()`.

#### Exemple :

```php
$form->setErrorLocation('email', 'section_email_error');  // Affiche l'erreur dans un endroit spécifique
```

#### Fonction pour afficher les erreurs par champ :

```php
$form->afficherErreursParChamp($champ);
```

#### Exemple d'affichage des erreurs spécifiques :

```php
<form action="index.php" method="POST">
    <label for="email">Email</label>
    <input type="email" name="email" id="email_input" value="<?= htmlspecialchars($form->getValeursFormulaire()['email'] ?? '') ?>"/>
    <div id="email_error">
        <?php $form->afficherErreursParChamp('email'); ?>
    </div>
</form>
```

### Messages de Succès

Après une soumission réussie, vous pouvez afficher un message de succès avec la fonction `setMessage()`.

#### Exemple :

```php
$form->setMessage("Formulaire soumis avec succès !");
$form->afficherMessages();  // Affiche le message de succès
```

### Exemple Complet de Formulaire

Voici un exemple complet de formulaire avec validation, erreurs spécifiques, et un message de succès :

```php
<?php
require_once 'FormManager.php';

$form = new FormManager('index.php', 'POST', 'formulaire_id', ['form-style']);
$form->ajouterChamp('email', 'Email', 'email', true, ['placeholder' => 'Votre email'], null, 'email_input', ['input-class']);
$form->ajouterChampConfirmation('mot_de_passe', 'Mot de passe', 'mot_de_passe_confirmation', 'Confirmer le mot de passe', true);
$form->ajouterGroupeCheckbox('conditions', 'Accepter les conditions', ['1' => 'Oui'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($form->valider()) {
        $form->setMessage("Formulaire soumis avec succès !");
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

        <!-- Affichage des erreurs spécifiques pour chaque champ -->
        <div id="email_error">
            <?php $form->afficherErreursParChamp('email'); ?>
        </div>
        <div id="mot_de_passe_error">
            <?php $form->afficherErreursParChamp('mot_de_passe'); ?>
        </div>
        
        <!-- Affichage du message de succès -->
        <?php $form->afficherMessages(); ?>

        <!-- Formulaire -->
        <?php $form->afficherFormulaire(); ?>
    </div>
</body>
</html>
```

### Conclusion

- **Personnalisation des Erreurs** : Vous pouvez choisir d'afficher les erreurs à un emplacement spécifique du formulaire avec la méthode `setErrorLocation()`.
- **Validation** : Utilisez des fonctions de validation personnalisées ou standard pour valider les données des champs.
- **Messages de Succès** : Vous pouvez afficher des messages de succès après la soumission du formulaire.

## Contact

*Créer par Durand [+237651104356]*

