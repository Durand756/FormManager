<?php class FormManager
{
    private $champs = [];
    private $methode;
    private $action;
    private $erreurs = [];
    private $messages = [];
    private $form_values = [];
    private $form_id;
    private $form_classes = [];
    private $Durand = "Private";
    private $createur = "Durand [+237651104356]";
    private $signature_attendue = "bc249348112418b9ba33b5a076d94301";
    private $erreurAffichage = 'inline';
    public function __construct($action = "", $methode = "POST", $id = null, $classes = [])
    {
        $this->action = $action;
        $this->methode = strtoupper($methode);
        $this->form_id = $id;
        $this->form_classes = $classes;
    }
    public function verifierCreateur()
    {
        $hash_createur = hash('md5', $this->createur . $this->Durand);
        return $hash_createur === $this->signature_attendue;
    }
    public function ajouterChamp($nom, $label, $type = "text", $obligatoire = false, $options = [], $validationCallback = null, $id = null, $classes = [])
    {
        $this->champs[$nom] = ["label" => $label, "type" => $type, "obligatoire" => $obligatoire, "options" => $options, "validationCallback" => $validationCallback, "valeur" => isset($_POST[$nom]) ? $this->sanitize($_POST[$nom]) : "", "id" => $id, "classes" => $classes];
    }
    public function ajouterChampConfirmation($nom, $label, $nomConfirmation, $labelConfirmation, $obligatoire = false)
    {
        $this->ajouterChamp($nom, $label, 'password', $obligatoire);
        $this->ajouterChamp($nomConfirmation, $labelConfirmation, 'password', $obligatoire);
        $this->champs[$nom]['validationCallback'] = function ($valeur, $form_values) use ($nom, $nomConfirmation) {
            if (!isset($form_values[$nomConfirmation])) {
                return "Le champ de confirmation du mot de passe est manquant.";
            }
            if ($form_values[$nom] !== $form_values[$nomConfirmation]) {
                return "Les mots de passe doivent correspondre.";
            }
            return true;
        };
    }
    public function ajouterGroupeCheckbox($nom, $label, $options = [], $obligatoire = false)
    {
        $this->champs[$nom] = ["label" => $label, "type" => 'checkbox-group', "obligatoire" => $obligatoire, "options" => $options, "valeur" => isset($_POST[$nom]) ? $this->sanitize($_POST[$nom]) : []];
    }
    public function valider()
    {
        foreach ($this->champs as $nom => $champ) {
            $valeur = $champ['valeur'];
            $this->form_values[$nom] = $valeur;
            if ($champ['obligatoire'] && empty($valeur)) {
                $this->erreurs[$nom] = "{$champ['label']} est requis.";
            } elseif (!empty($valeur)) {
                if ($champ['type'] == 'email' && !filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
                    $this->erreurs[$nom] = "{$champ['label']} doit être une adresse email valide.";
                }
                if ($champ['type'] == 'number' && !is_numeric($valeur)) {
                    $this->erreurs[$nom] = "{$champ['label']} doit être un nombre.";
                }
                if ($champ['validationCallback'] && is_callable($champ['validationCallback'])) {
                    $result = call_user_func($champ['validationCallback'], $valeur, $this->form_values);
                    if ($result !== true) {
                        $this->erreurs[$nom] = $result;
                    }
                }
            }
        }
        foreach ($this->champs as $nom => $champ) {
            if (isset($champ['validationCallback']) && is_callable($champ['validationCallback'])) {
                $result = call_user_func($champ['validationCallback'], $champ['valeur'], $this->form_values);
                if ($result !== true) {
                    $this->erreurs[$nom] = $result;
                }
            }
        }
        return empty($this->erreurs);
    }
    public function setErreurAffichage($type)
    {
        if (in_array($type, ['inline', 'block'])) {
            $this->erreurAffichage = $type;
        }
    }
    public function getErreurs()
    {
        return $this->erreurs;
    }
    public function getValeursFormulaire()
    {
        return $this->form_values;
    }
    public function setMessage($message)
    {
        $this->messages[] = $message;
    }
    public function afficherMessages()
    {
        foreach ($this->messages as $message) {
            echo "<p style='color: green;'>{$message}</p>";
        }
    }
    public function afficherErreurs()
    {
        foreach ($this->erreurs as $nom => $erreur) {
            if ($this->erreurAffichage === 'block') {
                echo "<div class='erreur' id='erreur_{$nom}' style='color: red;'>{$erreur}</div>";
            } else {
                echo "<span class='erreur' id='erreur_{$nom}' style='color: red;'>{$erreur}</span>";
            }
        }
    }
    public function afficherFormulaire()
    {
        echo "<form action='{$this->action}' method='{$this->methode}' id='{$this->form_id}' class='" . implode(' ', $this->form_classes) . "'>";
        foreach ($this->champs as $nom => $champ) {
            echo "<label for='{$nom}'>{$champ['label']}</label>";
            $id = isset($champ['options']['id']) ? $champ['options']['id'] : $nom;
            if ($champ['type'] == 'select') {
                echo "<select name='{$nom}' id='{$id}'>";
                foreach ($champ['options'] as $valeur => $label) {
                    $selected = $champ['valeur'] == $valeur ? "selected" : "";
                    echo "<option value='{$valeur}' {$selected}>{$label}</option>";
                }
                echo "</select>";
            } elseif ($champ['type'] == 'checkbox-group') {
                foreach ($champ['options'] as $valeur => $label) {
                    $checked = in_array($valeur, (array)$champ['valeur']) ? "checked" : "";
                    echo "<input type='checkbox' name='{$nom}[]' value='{$valeur}' {$checked} id='{$id}_{$valeur}'>{$label}<br/>";
                }
            } else {
                echo "<input type='{$champ['type']}' name='{$nom}' id='{$id}' value='{$champ['valeur']}'/>";
            }
            if (isset($this->erreurs[$nom])) {
                echo "<span style='color: red;'>{$this->erreurs[$nom]}</span>";
            }
            echo "<br/>";
        }
        echo "<button type='submit'>Envoyer</button>";
        echo "</form>";
    }
    private function sanitize($data)
    {
        return htmlspecialchars(strip_tags($data));
    }
}
/**Created by Durand */
?>