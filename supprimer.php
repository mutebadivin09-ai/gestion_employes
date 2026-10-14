<?php

require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    exit('Méthode non autorisée.');

}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {

    http_response_code(400);

    exit('Identifiant invalide.');

}

$requete = $pdo->prepare(
    'DELETE FROM employees WHERE id = :id'
);

$requete->execute(['id' => $id]);

header('Location: index.php');

exit;