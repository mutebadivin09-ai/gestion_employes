<?php

require_once 'connexion.php';

$erreur = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Identifiant invalide.');
}

$requete = $pdo->prepare(
    'SELECT * FROM employees WHERE id = :id'
);

$requete->execute(['id' => $id]);

$employe = $requete->fetch();

if (!$employe) {
    http_response_code(404);
    exit('Employé introuvable.');
}

$nom = $employe['last_name'];
$prenom = $employe['first_name'];
$age = (string) $employe['age'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['last_name'] ?? '');
    $prenom = trim($_POST['first_name'] ?? '');
    $age = trim($_POST['age'] ?? '');

    if ($nom === '' || $prenom === '' || $age === '') {

        $erreur = 'Tous les champs sont obligatoires.';

    } elseif (
        !ctype_digit($age) ||
        (int) $age < 16 ||
        (int) $age > 100
    ) {

        $erreur = 'L’âge doit être compris entre 16 et 100 ans.';

    } elseif (strlen($nom) > 100 || strlen($prenom) > 100) {

        $erreur = 'Le nom et le prénom ne doivent pas dépasser 100 caractères.';

    } else {

        $requete = $pdo->prepare(
            'UPDATE employees
             SET last_name = :nom,
                 first_name = :prenom,
                 age = :age
             WHERE id = :id'
        );

        $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'age' => (int) $age,
            'id' => $id
        ]);

        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un employé</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <main class="container">

        <section class="card form-card">

            <h1>Modifier un employé</h1>

            <p>Modifiez les informations de l'employé.</p>

            <?php if ($erreur !== ''): ?>

                <p class="error">
                    <?= htmlspecialchars($erreur) ?>
                </p>

            <?php endif; ?>

            <form action="modifier.php" method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $id ?>"
                >

                <label for="last_name">Nom de famille</label>

                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    maxlength="100"
                    value="<?= htmlspecialchars($nom) ?>"
                    required
                >

                <label for="first_name">Prénom</label>

                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    maxlength="100"
                    value="<?= htmlspecialchars($prenom) ?>"
                    required
                >

                <label for="age">Âge</label>

                <input
                    type="number"
                    id="age"
                    name="age"
                    min="16"
                    max="100"
                    value="<?= htmlspecialchars($age) ?>"
                    required
                >

                <div class="form-actions">

                    <button type="submit" class="btn btn-primary">
                        Enregistrer les modifications
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        Annuler
                    </a>

                </div>

            </form>

        </section>

    </main>

</body>

</html>