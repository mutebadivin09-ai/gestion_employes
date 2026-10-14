<?php

require_once 'connexion.php';

$requete = $pdo->query(
    'SELECT * FROM employees ORDER BY id DESC'
);

$employes = $requete->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des employés</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <main class="container">

        <header class="header">
            <div>
                <h1>Gestion des employés</h1>
                <p>Gérez facilement les employés de votre entreprise.</p>
            </div>

            <a href="ajouter.php" class="btn btn-primary">
                Ajouter un employé
            </a>
        </header>

        <section class="card">

            <h2>Liste des employés</h2>

            <?php if (count($employes) > 0): ?>

                <div class="table-container">

                    <table>

                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Âge</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($employes as $employe): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($employe['last_name']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($employe['first_name']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string) $employe['age']) ?> ans
                                    </td>

                                    <td class="actions">

                                        <a
                                            href="modifier.php?id=<?= (int) $employe['id'] ?>"
                                            class="btn btn-edit"
                                        >
                                            Modifier
                                        </a>

                                        <form
                                            action="supprimer.php"
                                            method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cet employé ?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $employe['id'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-delete"
                                            >
                                                Supprimer
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <p class="empty">
                    Aucun employé enregistré pour le moment.
                </p>

            <?php endif; ?>

        </section>

        <footer class="footer">
            Gestion des employés · PHP &amp; MySQL
        </footer>

    </main>

</body>

</html>