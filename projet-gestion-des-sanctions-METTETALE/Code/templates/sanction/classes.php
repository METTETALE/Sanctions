<?php
ob_start();

$niveauxColors = [
    "Seconde" => "blue",
    "Premiere" => "green",
    "Terminale" => "purple",
    "BTS" => "orange"
];
?>

<div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-700 text-white p-8 rounded-lg text-center mb-16 flex-col justify-center items-center">
        <div class="flex items-center justify-center">
            <span class="text-5xl">🏫</span>
            <h1 class="text-4xl font-bold">Gestion des classes</h1>
        </div>
        <p class="my-8 text-blue-100">Gérez les classes de votre établissement</p>
        <div class="flex gap-4 justify-center">
            <a href="index.php?action=creationClasse" class="w-[25%] flex items-center justify-center gap-2 bg-slate-100 text-gray-600 font-bold py-3 px-4 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500  transition-colors">
                <span class="material-symbols-outlined">➕</span>
                Créer la classe
            </a>
            <a href="index.php?action=dashboard" class="w-[25%] flex items-center justify-center gap-2 bg-blue-500 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500  transition-colors">
                <span class="text-base">🏠</span>
                Tableau de bord
            </a>
        </div>
    </div>
    <div class="mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <div class="flex justify-between items-center px-6 py-4 bg-slate-100">
                <div class="text-lg font-semibold">
                    Liste des classes
                </div>
                <a href="index.php?action=creationClasse" class="text-sm w-[12%] flex items-center justify-center bg-blue-500 text-white font-bold py-2 px-1 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500  transition-colors">
                    <span class="material-symbols-outlined">➕</span>
                    Nouvelle classe
                </a>
            </div>
            <thead class="bg-slate-100 text-slate-400">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Nom de la classe</th>
                    <th class="px-6 py-3 text-left font-semibold">Niveau</th>
                    <th class="px-6 py-3 text-left font-semibold">Créée le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $classe) : ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-300/60 rounded-lg py-2 px-3 text-blue-700">
                                    <?= htmlspecialchars(strtoupper(substr($classe['nom'], 0, 2))) ?>
                                </div>
                                <div>
                                    <?= htmlspecialchars($classe['nom']) ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-<?= $niveauxColors[$classe['niveau']] ?>-300/60 rounded-lg px-2 text-<?= $niveauxColors[$classe['niveau']] ?>-700 text-sm">
                                    <?= htmlspecialchars(ucfirst($classe['niveau'])) ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <i class="fa-solid fa-calendar mr-2"></i>
                            <?= date('d/m/Y \à H:i', strtotime($classe['date_creation'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>