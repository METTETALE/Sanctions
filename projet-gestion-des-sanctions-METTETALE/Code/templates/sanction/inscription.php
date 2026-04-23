<?php
ob_start();

?>

<div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-gradient-to-r from-green-700 via-green-600 to-green-500 text-white p-8 rounded-lg text-center mb-16">
        <div class="flex items-center justify-center">
            <span class="text-5xl">📝</span>
            <h1 class="text-4xl font-bold">Créer un compte</h1>
        </div>
        <p class="mt-2 text-blue-100">Inscrivez-vous pour accéder au système de gestion</p>
    </div>
    <div class="max-w-3xl mx-auto">
        <div class="bg-card-light p-6 sm:p-8 rounded-b-lg shadow-lg">
            <form action="#" class="space-y-6" method="POST">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center text-sm font-medium text-text-light  mb-2" for="nom">
                            <span class="material-symbols-outlined text-lg mr-2 text-text-muted-light ">👤</span>
                            Nom
                        </label>
                        <input class="w-full bg-background-light  border border-gray-300  rounded-md focus:ring-primary focus:border-primary transition" id="nom" name="nom" placeholder="Votre nom" type="text" value="<?= $nom ?>" />
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-text-light  mb-2" for="prenom">
                            <span class="material-symbols-outlined text-lg mr-2 text-text-muted-light ">👤</span>
                            Prénom
                        </label>
                        <input class="w-full bg-background-light  border border-gray-300  rounded-md focus:ring-primary focus:border-primary transition" id="prenom" name="prenom" placeholder="Votre prénom" type="text" value="<?= $prenom ?>" />
                    </div>
                </div>
                <div>
                    <label class="flex items-center text-sm font-medium text-text-light  mb-2" for="email">
                        <span class="material-symbols-outlined text-lg mr-2 text-text-muted-light ">📧</span>
                        Adresse email
                    </label>
                    <input class="w-full bg-background-light  border border-gray-300  rounded-md focus:ring-primary focus:border-primary transition" id="email" name="email" placeholder="votre.email@exemple.com" type="email" value="<?= $email ?>" />
                </div>
                <div>
                    <label class=" flex items-center text-sm font-medium text-text-light  mb-2" for="password">
                        <span class="material-symbols-outlined text-lg mr-2 text-text-muted-light ">🔒</span>
                        Mot de passe
                    </label>
                    <input class="w-full bg-background-light  border border-gray-300  rounded-md focus:ring-primary focus:border-primary transition" id="password" name="password" placeholder="Au moins 6 caractères" type="password" />
                </div>
                <div>
                    <label class="flex items-center text-sm font-medium text-text-light  mb-2" for="password_confirmation">
                        <span class="material-symbols-outlined text-lg mr-2 text-text-muted-light">🔒</span>
                        Confirmer le mot de passe
                    </label>
                    <input class="w-full bg-background-light  border border-gray-300  rounded-md focus:ring-primary focus:border-primary transition" id="password_confirmation" name="password_confirmation" placeholder="Répétez votre mot de passe" type="password" />
                </div>
                <div>
                    <button class="w-full flex items-center justify-center gap-2 bg-green-600 text-white font-semibold py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500  transition duration-150 ease-in-out" type="submit">
                        <span class="material-symbols-outlined text-xl">✨</span>
                        Créer mon compte
                    </button>
                </div>
            </form>
            <div class="text-center mt-6 space-y-4">
                <p class="text-sm text-text-muted-light ">
                    Déjà un compte ? <a class="font-medium text-green-600 hover:text-green-500" href="index.php?action=connexion">Se connecter</a>
                </p>
                <a class="inline-flex items-center gap-2 text-sm text-text-muted-light  hover:text-text-light  transition" href="?action=index">
                    <span class="material-symbols-outlined text-lg">🔙</span>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>