<?php
ob_start();
?>

<div class="container mx-auto px-6 py-8">
    <section class="bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600 p-12 rounded-lg mb-8">
        <div class="flex justify-center items-center gap-4 mb-4">
            <span class="material-symbols-outlined text-6xl">🎓</span>
            <div>
                <h2 class="text-3xl font-bold">Bienvenue, <?= $user_name . " " . $user_prenom ?> !</h2>
                <p class="text-blue-200">Tableau de bord de gestion des sanctions scolaires</p>
            </div>
        </div>
        <div class="flex justify-center items-center gap-4 mt-6">
            <a href="index?action=sanctions" class="bg-white text-blue-500 font-semibold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-base">⚖️</span>
                Gérer les Sanctions
            </a>
            <a href="index.php?action=eleves" class="bg-blue-400  font-semibold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-blue-500 transition-colors">
                <span class="material-symbols-outlined text-base">👥</span>
                Voir les Élèves
            </a>
            <a href="index.php?action=classes" class="bg-purple-500  font-semibold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-purple-600 transition-colors">
                <span class="material-symbols-outlined text-base">🏫</span>
                Gérer les Classes
            </a>
            <a class="bg-red-500  font-semibold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-red-600 transition-colors" href="index?action=logout">
                <span class="material-symbols-outlined text-base">🚪</span>
                Déconnexion
            </a>
        </div>
    </section>
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-blue-500">⚖️</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Sanctions</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $nombreSanctions ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-green-500">👥</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Élèves</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $nombreEleves ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-purple-500">👨‍🏫</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Professeurs</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $nombreProfesseurs ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-orange-500">
            <div class="flex items-center gap-4">
                <div class="bg-orange-100 p-3 rounded-lg">
                    <span class="material-symbols-outlined text-2xl text-orange-500">🏫</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Classes</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $nombreClasses ?? 0 ?></p>
                </div>
            </div>
        </div>
    </section>
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-900">
                <span class="material-symbols-outlined text-yellow-500 text-2xl">🗲</span>
                Accès Rapide
            </h3>
            <div class="space-y-4">
                <a class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" href="index.php?action=creationSanction">
                    <div class="bg-red-100 p-2 rounded-lg"><span class="material-symbols-outlined text-red-500">➕</span></div>
                    <div>
                        <p class="font-medium text-gray-900">Nouvelle Sanction</p>
                        <p class="text-sm text-gray-500">Enregistrer un nouvel incident</p>
                    </div>
                </a>
                <a class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" href="index.php?action=creationEleve">
                    <div class="bg-blue-100 p-2 rounded-lg"><span class="material-symbols-outlined text-blue-500">👤</span></div>
                    <div>
                        <p class="font-medium text-gray-900">Nouvel Élève</p>
                        <p class="text-sm text-gray-500">Ajouter un élève au système</p>
                    </div>
                </a>
                <a class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" href="index.php?action=creationProfesseur">
                    <div class="bg-green-100 p-2 rounded-lg"><span class="material-symbols-outlined text-green-500">👨‍🏫</span></div>
                    <div>
                        <p class="font-medium text-gray-900">Nouveau Professeur</p>
                        <p class="text-sm text-gray-500">Enregistrer un enseignant</p>
                    </div>
                </a>
                <a class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" href="index.php?action=creationClasse">
                    <div class="bg-purple-100 p-2 rounded-lg"><span class="material-symbols-outlined text-purple-500">🏫</span></div>
                    <div>
                        <p class="font-medium text-gray-900">Nouvelle Classe</p>
                        <p class="text-sm text-gray-500">Créer une nouvelle classe</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-900">
                <span class="material-symbols-outlined text-blue-500">👤</span>
                Informations Utilisateur
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-lg"><span class="material-symbols-outlined text-sm text-blue-500">👤</span></div>
                        <p class="text-sm text-gray-500">Nom complet</p>
                    </div>
                    <p class="font-medium text-sm text-gray-900"><?= $user_name . " " . $user_prenom ?></p>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 p-2 rounded-lg"><span class="material-symbols-outlined text-sm text-green-500">📧</span></div>
                        <p class="text-sm text-gray-500">Email</p>
                    </div>
                    <p class="font-medium text-sm text-gray-900"><?= $user_email ?></p>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-100 p-2 rounded-lg"><span class="material-symbols-outlined text-sm text-purple-500">👤</span></div>
                        <p class="text-sm text-gray-500">Service</p>
                    </div>
                    <p class="font-medium text-sm text-purple-500">Vie Scolaire</p>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-gray-100 p-8 rounded-lg">
        <h3 class="text-lg font-semibold text-center mb-6 flex items-center justify-center gap-2 text-gray-900">
            🚀
            Guide de Démarrage Rapide
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="mx-auto bg-blue-100 h-12 w-12 flex items-center justify-center rounded-full text-2xl font-bold text-blue-500 mb-3">1️⃣</div>
                <h4 class="font-semibold mb-1 text-gray-900">Configurez les Classes</h4>
                <p class="text-sm text-gray-600">Créez les classes de votre établissement pour organiser les élèves</p>
            </div>
            <div>
                <div class="mx-auto bg-blue-100 h-12 w-12 flex items-center justify-center rounded-full text-2xl font-bold text-blue-500 mb-3">2️⃣</div>
                <h4 class="font-semibold mb-1 text-gray-900">Ajoutez les Élèves</h4>
                <p class="text-sm text-gray-600">Enregistrez les élèves et associez-les à leurs classes respectives</p>
            </div>
            <div>
                <div class="mx-auto bg-blue-100 h-12 w-12 flex items-center justify-center rounded-full text-2xl font-bold text-blue-500 mb-3">3️⃣</div>
                <h4 class="font-semibold mb-1 text-gray-900">Gérez les Sanctions</h4>
                <p class="text-sm text-gray-600">Enregistrez et suivez les sanctions des élèves de manière efficace</p>
            </div>
        </div>
    </section>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>