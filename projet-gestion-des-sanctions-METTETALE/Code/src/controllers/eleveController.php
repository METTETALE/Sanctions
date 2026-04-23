<?php
require_once __DIR__ . '/../repositories/eleveRepositorie.php';
require_once __DIR__ . '/../repositories/classesRepositorie.php';
@session_start();

function listeElevesSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    $data = [
        'eleves' => getAllEleves(),
        'success' => $_SESSION['success'] ?? null
    ];

    if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
    }

    $rep->view(__DIR__ . '/../../templates/sanction/eleves.php', $data);
}

function creationEleveSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    $classes = getAllClasses();

    $data = [
        'classes' => $classes,
    ];

    if ($req->isPost()) {
        $nomEleve = $req->post('nomEleve');
        $prenomEleve = $req->post('prenomEleve');
        $dateNaissance = $req->post('dateNaissance');
        $selectedClasse = $req->post('niveau');

        $errors = [];

        if (empty($nomEleve)) {
            $errors[] = 'Le nom de l\'élève est obligatoire.';
        }

        if (empty($prenomEleve)) {
            $errors[] = 'Le prénom de l\'élève est obligatoire.';
        }

        if (empty($dateNaissance)) {
            $errors[] = 'La date de naissance est obligatoire.';
        }

        if (empty($selectedClasse)) {
            $errors[] = 'La classe est obligatoire.';
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'nomEleve' => $nomEleve,
                'prenomEleve' => $prenomEleve,
                'dateNaissance' => $dateNaissance,
                'selectedClasse' => $selectedClasse,
                'classes' => $classes,
            ];
            $rep->view(__DIR__ . '/../../templates/sanction/creationEleve.php', $data);
            return;
        } else {
            $_SESSION['success'] = 'Élève créé avec succès !';

            // Ici, vous pouvez ajouter la logique pour enregistrer l'élève dans la base de données
            newEleve($nomEleve, $prenomEleve, $dateNaissance, $selectedClasse);

            $rep->redirectTo('index?action=eleves');
            return;
        }
    }

    $rep->view(__DIR__ . '/../../templates/sanction/creationEleve.php', $data);
}
