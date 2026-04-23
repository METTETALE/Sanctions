<?php
ob_start();
?>

<div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <section class="text-white rounded-lg p-8 md:p-12 text-center shadow-lg bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 ">
        <div class="flex justify-center items-center gap-4 mb-4">
            <span class="material-icons-outlined text-4xl md:text-5xl">🎓</span>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold">Application de Gestion des Sanctions</h1>
        </div>
        <p class="mt-2 text-lg text-gray-100">Système de gestion de la vie scolaire pour le lycée</p>
    </section>
    <section class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-md p-8 flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-full bg-gray-100  flex items-center justify-center mb-6">
                <span class="text-3xl">🔐</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900  mb-2">Connexion</h2>
            <p class="text-gray-600  mb-6 max-w-xs">Accédez à votre espace personnel pour gérer les sanctions</p>
            <a class="bg-primary bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg flex items-center justify-center gap-2 transition-colors duration-300" href="index.php?action=connexion">
                🚀 Se connecter
            </a>
        </div>
        <div class="bg-white rounded-lg shadow-md p-8 flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-full bg-green-100  flex items-center justify-center mb-6">
                <span class="text-3xl">📝</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900  mb-2">Créer un compte</h2>
            <p class="text-gray-600  mb-6 max-w-xs">Inscrivez-vous pour accéder au système de gestion</p>
            <a class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg flex items-center justify-center gap-2 transition-colors duration-300" href="?action=inscription">
                ✨ S'inscrire
            </a>
        </div>
    </section>
    <section class="mt-16 bg-white rounded-lg shadow-md p-8 md:p-12">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900  flex items-center justify-center gap-3">
                🚀 À propos de l'application
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gray-100  flex items-center justify-center mb-4">
                    <span class="text-3xl">⚖️</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800  mb-2">Gestion des Sanctions</h3>
                <p class="text-gray-600 ">Enregistrez et suivez les sanctions des élèves de manière efficace</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gray-100  flex items-center justify-center mb-4">
                    <span class="text-3xl">👥</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800  mb-2">Gestion des Élèves</h3>
                <p class="text-gray-600 ">Centralisez les informations des élèves et leurs classes</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gray-100  flex items-center justify-center mb-4">
                    <span class="text-3xl">📊</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800  mb-2">Tableau de Bord</h3>
                <p class="text-gray-600 ">Visualisez les statistiques et l'activité récente</p>
            </div>
        </div>
    </section>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>