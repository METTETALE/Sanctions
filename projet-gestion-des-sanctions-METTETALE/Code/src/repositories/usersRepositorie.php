<?php

require_once __DIR__ . '/../config/database.php';

function newUser($email, $name, $surname, $passwordHash)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return false;
    }

    $sql = "INSERT INTO users (email, name, surname, password_hash)
            VALUES (:email, :name, :surname, :password_hash)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':email' => $email,
            ':name' => $name,
            ':surname' => $surname,
            ':password_hash' => $passwordHash
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'insertion de l'utilisateur : " . $e->getMessage());
        return false;
    }
}

function getAllMail()
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return [];
    }

    $sql = "SELECT email FROM users";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des emails : " . $e->getMessage());
        return [];
    }
}

function getUserByEmail($email)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return null;
    }

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        return null;
    }
}

function getIdByEmail($email)
{
    $pdo = getDatabaseConnection();
    if ($pdo === false) {
        return null;
    }

    $sql = "SELECT id_user FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'ID utilisateur : " . $e->getMessage());
        return null;
    }
}
