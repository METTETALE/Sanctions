<?php

require_once __DIR__ . '/../repositories/sanctionRepositorie.php';
@session_start();

function creationSanctionSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    $data = [
        'eleves' => getAllEleves(),
        'professeurs' => getAllProfesseurs(),
        'selectedEleve' => '',
        'selectedProfesseur' => '',
        'type' => '',
        'date' => '',
        'motif' => '',
        'errors' => []
    ];

    if ($req->isPost()) {
        $selectedEleve = $req->post('eleve');
        $selectedProfesseur = $req->post('professeur');
        $type = $req->post('type');
        $date = $req->post('date');
        $motif = $req->post('motif');

        $errors = [];

        if (empty($selectedEleve)) {
            $errors[] = 'L\'élève est obligatoire.';
        }

        if (empty($selectedProfesseur)) {
            $errors[] = 'Le professeur est obligatoire.';
        }

        if (empty($type)) {
            $errors[] = 'Le type de sanction est obligatoire.';
        }

        if (empty($date)) {
            $errors[] = 'La date est obligatoire.';
        }

        if (empty($motif)) {
            $errors[] = 'Le motif est obligatoire.';
        }

        if (!empty($errors)) {
            $data['errors'] = $errors;
            $data['selectedEleve'] = $selectedEleve;
            $data['selectedProfesseur'] = $selectedProfesseur;
            $data['type'] = $type;
            $data['date'] = $date;
            $data['motif'] = $motif;
        } else {
            createSanction($selectedEleve, $selectedProfesseur, $type, $date, $motif);

            $_SESSION['success'] = 'La sanction a été créée avec succès.';
            $rep->redirectTo('index?action=sanctions');
            return;
        }
    }

    $rep->view(__DIR__ . '/../../templates/sanction/creationSanction.php', $data);
}

function listeSanctionsSanction(Request $req, Response $rep)
{
    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    $sanctions = getAllSanctions();

    $data = [
        'sanctions' => $sanctions,
    ];
    $rep->view(__DIR__ . '/../../templates/sanction/sanctions.php', $data);
}
