<?php
require_once __DIR__ . '/../repositories/usersRepositorie.php';
require_once __DIR__ . '/../repositories/classesRepositorie.php';
require_once __DIR__ . '/../repositories/eleveRepositorie.php';
require_once __DIR__ . '/../repositories/professeurRepositorie.php';
@session_start();

/**
 * Affiche la liste de tous les films
 */
function indexSanction(Request $req, Response $rep)
{
    if (isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=dashboard');
        return;
    }

    $data = [
        'success' => $_SESSION['success'] ?? null
    ];

    if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
    }

    $rep->view(__DIR__ . '/../../templates/sanction/index.php', $data);
}

function inscriptionSanction(Request $req, Response $rep)
{

    if (isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=dashboard');
        return;
    }

    $data = [
        'nom' => '',
        'prenom' => '',
        'email' => ''
    ];
    if ($req->isPost()) {
        // Récupération des données du formulaire
        $nom = $req->post('nom');
        $prenom = $req->post('prenom');
        $email = $req->post('email');
        $password = $req->post('password');
        $password_confirmation = $req->post('password_confirmation');

        // Validation des données (exemple simple)
        if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($password_confirmation)) {
            $rep->redirectTo('index?action=inscription');
            $errors[] = 'Tous les champs sont obligatoires.';
        }

        if ($nom < 2 || strlen($nom) > 50) {
            $errors[] = 'Le nom doit contenir entre 2 et 50 caractères.';
        }

        if ($prenom < 2 || strlen($prenom) > 50) {
            $errors[] = 'Le prénom doit contenir entre 2 et 50 caractères.';
        }

        if (($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) || in_array($email, getAllMail())) {
            $errors[] = 'L\'adresse email n\'est pas valide.';
        }

        if ($password !== $password_confirmation) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une majuscule.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
        }
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial.';
        }

        // Ici, vous pouvez ajouter la logique pour enregistrer l'utilisateur dans la base de données
        if (!empty($errors)) {
            $data = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'errors' => $errors
            ];
            $rep->view(__DIR__ . '/../../templates/sanction/inscription.php', $data);
            return;
        } else {

            $_SESSION['success'] = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';

            newUser($email, $nom, $prenom, password_hash($password, PASSWORD_BCRYPT));

            $_SESSION['user_id'] = getIdByEmail($email);
            $_SESSION['user_name'] = $nom;
            $_SESSION['user_prenom'] = $prenom;
            $_SESSION['user_email'] = $email;

            $rep->redirectTo('index?action=dashboard');
            return;
        }
        // Redirection vers la page de connexion avec un message de succès

    }

    $rep->view(__DIR__ . '/../../templates/sanction/inscription.php', $data);
}

function connexionSanction(Request $req, Response $rep)
{
    if (isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=dashboard');
        return;
    }

    $data = [
        'email' => '',
        'success' => $_SESSION['success'] ?? null
    ];

    if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
    }

    if ($req->isPost()) {
        $email = $req->post('email');
        $password = $req->post('password');

        if (empty($email) || empty($password)) {
            $errors[] = 'Tous les champs sont obligatoires.';
        }

        $currentUser = getUserByEmail($email);

        if (($currentUser === null or !password_verify($password, $currentUser['password_hash'])) and !(empty($email) || empty($password))) {
            $errors[] = 'Email ou Mot de passe incorrect.';
        }

        if (!empty($errors)) {
            $data = [
                'email' => $email,
                'errors' => $errors
            ];
            $rep->view(__DIR__ . '/../../templates/sanction/connexion.php', $data);
            return;
        } else {

            // Ici, vous pouvez ajouter la logique pour gérer la session utilisateur
            $_SESSION['user_id'] = $currentUser['id_user'];
            $_SESSION['user_name'] = $currentUser['name'];
            $_SESSION['user_prenom'] = $currentUser['surname'];
            $_SESSION['user_email'] = $currentUser['email'];

            $rep->redirectTo('index?action=dashboard');
            return;
        }
    }

    // Logique de connexion (vérification des identifiants, gestion de la session, etc.)
    $rep->view(__DIR__ . '/../../templates/sanction/connexion.php', $data);
}

function dashboardSanction(Request $req, Response $rep)
{

    if (!isset($_SESSION['user_id'])) {
        $rep->redirectTo('index?action=connexion');
        return;
    }

    $data = [
        'user_name' => $_SESSION['user_name'],
        'user_prenom' => $_SESSION['user_prenom'],
        'user_email' => $_SESSION['user_email'],
        'nombreEleves' => getNombreEleves(),
        'nombreClasses' => getNombreClasses(),
        'nombreProfesseurs' => getNombreProfesseurs(),
        'nombreSanctions' => getNombreSanctions(),
    ];

    $rep->view(__DIR__ . '/../../templates/sanction/dashboard.php', $data);
}

function logoutSanction(Request $req, Response $rep)
{

    // Détruire toutes les variables de session
    $_SESSION = [];

    // Détruire la session
    session_destroy();
    session_start();

    $_SESSION["success"] = 'Déconnexion réussie ! À bientôt.';

    // Rediriger vers la page d'accueil ou de connexion
    $rep->redirectTo('index?action=connexion');
}
