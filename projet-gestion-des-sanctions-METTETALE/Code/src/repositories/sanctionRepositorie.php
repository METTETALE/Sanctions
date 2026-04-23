<?php

require_once __DIR__ . '/../config/database.php';

function createSanction($eleveId, $professeurId, $type, $date, $motif)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return false;
    }

    $sql = "INSERT INTO sanctions (id_eleve, id_professeur, date, type, motif)
            VALUES (:eleve_id, :professeur_id, :date_sanction, :type, :motif)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':eleve_id' => $eleveId,
            ':professeur_id' => $professeurId,
            ':type' => $type,
            ':date_sanction' => $date,
            ':motif' => $motif,
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'insertion de la sanction : " . $e->getMessage());
        return false;
    }
}

function getAllSanctions()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return [];
    }

    $sql = "SELECT *
            FROM sanctions
            ORDER BY date DESC";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des sanctions : " . $e->getMessage());
        return [];
    }
}

function getNombreSanctions()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM sanctions";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des sanctions : " . $e->getMessage());
        return 0;
    }
}
