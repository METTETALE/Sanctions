<?php
require_once __DIR__ . '/../repositories/professeurRepositorie.php';
@session_start();

function listeProfesseursSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    $data = [
        'professeurs' => getAllProfesseurs(),
        'success' => $_SESSION['success'] ?? null
    ];

    if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
    }

    $rep->view(__DIR__ . '/../../templates/sanction/professeurs.php', $data);
}

function creationProfesseurSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    if ($req->isPost()) {
        $nomProfesseur = $req->post('nomProfesseur');
        $prenomProfesseur = $req->post('prenomProfesseur');
        $matiere = $req->post('matiere');
        $errors = [];

        if (empty($nomProfesseur)) {
            $errors[] = 'Le nom du professeur est obligatoire.';
        }

        if (empty($prenomProfesseur)) {
            $errors[] = 'Le prénom du professeur est obligatoire.';
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'nomProfesseur' => $nomProfesseur,
                'prenomProfesseur' => $prenomProfesseur,
            ];
            $rep->view(__DIR__ . '/../../templates/sanction/creationProfesseur.php', $data);
            return;
        } else {
            $_SESSION['success'] = 'Professeur créé avec succès !';

            // Ici, vous pouvez ajouter la logique pour enregistrer le professeur dans la base de données
            newProfesseur($nomProfesseur, $prenomProfesseur, $matiere);

            $rep->redirectTo('index?action=professeurs');
            return;
        }
    }

    $rep->view(__DIR__ . '/../../templates/sanction/creationProfesseur.php', $data ?? []);
}
