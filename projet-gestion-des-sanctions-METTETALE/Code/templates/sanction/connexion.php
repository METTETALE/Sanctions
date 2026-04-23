<?php
ob_start();
?>

<div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-700 text-white p-8 rounded-lg text-center mb-16">
        <div class="flex items-center justify-center">
            <span class="text-5xl">🔐</span>
            <h1 class="text-4xl font-bold">Connexion</h1>
        </div>
        <p class="mt-2 text-blue-100">Accédez à votre espace personnel</p>
    </div>
    <div class="max-w-xl mx-auto">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <form class="space-y-6" method="POST">
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="email">
                        <span class="text-gray-500 text-base">📧</span>
                        Adresse email
                    </label>
                    <input class="w-full px-4 py-2 border border-gray-300  rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50  text-gray-900  placeholder-gray-400 " id="email" name="email" placeholder="votre.email@exemple.com" type="email" />
                </div>
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700 " for="password">
                        <span class="text-gray-500  text-base">🔒</span>
                        Mot de passe
                    </label>
                    <input class="w-full px-4 py-2 border border-gray-300  rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50  text-gray-900  placeholder-gray-400 " id="password" name="password" placeholder="Votre mot de passe" type="password" />
                </div>
                <div>
                    <button class="w-full flex items-center justify-center gap-2 bg-blue-500 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500  transition-colors" type="submit">
                        <span class="material-symbols-outlined">🚀</span>
                        Se connecter
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600 ">
                    Pas encore de compte ? <a class="font-medium text-blue-500 hover:underline" href="index.php?action=inscription">Créer un compte</a>
                </p>
            </div>
            <div class="mt-6 border-t border-gray-200  pt-6 text-center">
                <a class="inline-flex items-center gap-2 text-sm text-gray-500  hover:text-blue-500  transition-colors" href="index.php?action=index">
                    <span class="text-base">🔙</span>
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