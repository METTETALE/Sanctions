<?php

require_once __DIR__ . '/../config/database.php';

function newClasse($nom, $niveau)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return false;
    }

    $sql = "INSERT INTO classes (nom, niveau)
            VALUES (:nom, :niveau)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nom' => $nom,
            ':niveau' => $niveau,
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'insertion de la classe : " . $e->getMessage());
        return false;
    }
}
function getAllClasses()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return [];
    }

    $sql = "SELECT * FROM classes";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des classes : " . $e->getMessage());
        return [];
    }
}

function getNombreClasses()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM classes";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du nombre de classes : " . $e->getMessage());
        return 0;
    }
}
