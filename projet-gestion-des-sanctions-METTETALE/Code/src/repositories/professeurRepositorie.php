<?php

function newProfesseur($nomProfesseur, $prenomProfesseur, $matiere)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return false;
    }

    $sql = "INSERT INTO professeurs (nom, prenom, matiere)
            VALUES (:nomProfesseur, :prenomProfesseur, :matiere)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nomProfesseur' => $nomProfesseur,
            ':prenomProfesseur' => $prenomProfesseur,
            ':matiere' => $matiere,
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'insertion du professeur : " . $e->getMessage());
        return false;
    }
}

function getAllProfesseurs()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return [];
    }

    $sql = "SELECT * FROM professeurs ORDER BY nom, prenom";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des professeurs : " . $e->getMessage());
        return [];
    }
}

function getNombreProfesseurs()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM professeurs";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des professeurs : " . $e->getMessage());
        return 0;
    }
}

function getProfesseurById($id)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return [];
    }

    $sql = "SELECT *
            FROM professeurs
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}
