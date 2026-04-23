<?php
require_once __DIR__ . '/../repositories/classesRepositorie.php';
@session_start();

function creationClasseSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    if ($req->isPost()) {
        $nomClasse = $req->post('nomClasse');
        $niveau = $req->post('niveau');

        $errors = [];

        if (empty($nomClasse)) {
            $errors[] = 'Le nom de la classe est obligatoire.';
        }

        if (empty($niveau)) {
            $errors[] = 'Le niveau est obligatoire.';
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'nomClasse' => $nomClasse,
                'selectedNiveau' => $niveau
            ];
            $rep->view(__DIR__ . '/../../templates/sanction/creationClasse.php', $data);
            return;
        } else {
            $_SESSION['success'] = 'Classe créée avec succès !';

            newClasse($nomClasse, $niveau);
            $rep->redirectTo('index?action=classes');
            return;
        }
    }

    $rep->view(__DIR__ . '/../../templates/sanction/creationClasse.php');
}

function listeClassesSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    $classes = getAllClasses();

    $data = [
        'classes' => $classes,
        'success' => $_SESSION['success'] ?? null
    ];

    if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
    }

    $rep->view(__DIR__ . '/../../templates/sanction/classes.php', $data);
}
