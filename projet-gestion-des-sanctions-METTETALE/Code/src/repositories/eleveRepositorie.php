<?php

require_once __DIR__ . '/../config/database.php';

function newEleve($nomEleve, $prenomEleve, $dateNaissance, $idClasse)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return false;
    }

    $sql = "INSERT INTO eleves (nom, prenom, date_naissance, id_classe)
            VALUES (:nomEleve, :prenomEleve, :dateNaissance, :id_classe)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nomEleve' => $nomEleve,
            ':prenomEleve' => $prenomEleve,
            ':dateNaissance' => $dateNaissance,
            ':id_classe' => $idClasse,
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'insertion de l'élève : " . $e->getMessage());
        return false;
    }
}

function getAllEleves()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return [];
    }

    $sql = "SELECT e.*, c.nom AS classe, c.niveau
            FROM eleves e
            LEFT JOIN classes c ON e.id_classe = c.id_classe
            ORDER BY e.nom, e.prenom";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des élèves : " . $e->getMessage());
        return [];
    }
}

function getNombreEleves()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM eleves";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des élèves : " . $e->getMessage());
        return 0;
    }
}

function getEleveById($id)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return [];
    }

    $sql = "SELECT *
            FROM eleves
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}
