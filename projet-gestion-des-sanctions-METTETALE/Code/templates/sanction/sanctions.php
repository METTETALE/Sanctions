<?php
ob_start();

?>

<div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-700 text-white p-8 rounded-lg text-center mb-16 flex-col justify-center items-center">
        <div class="flex items-center justify-center">
            <span class="text-5xl">⚠️</span>
            <h1 class="text-4xl font-bold">Gestion des sanctions</h1>
        </div>
        <p class="my-8 text-red-100">Gérez les sanctions de votre établissement</p>
        <div class="flex gap-4 justify-center">
            <a href="index.php?action=creationSanction" class="w-[25%] flex items-center justify-center gap-2 bg-slate-100 text-gray-600 font-bold py-3 px-4 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500  transition-colors">
                <span class="material-symbols-outlined">➕</span>
                Créer une sanction
            </a>
            <a href="index.php?action=dashboard" class="w-[25%] flex items-center justify-center gap-2 bg-blue-500 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500  transition-colors">
                <span class="text-base">🏠</span>
                Tableau de bord
            </a>
        </div>
    </div>
    <div class="mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <div class="flex justify-between items-center px-6 py-4 bg-slate-100">
                <div class="text-lg font-semibold">
                    Liste des sanctions
                </div>
                <a href="index.php?action=creationSanction" class="text-sm w-[14%] flex items-center justify-center bg-red-500 text-white font-bold py-2 px-1 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500  transition-colors">
                    <span class="material-symbols-outlined">➕</span>
                    Nouvelle sanction
                </a>
            </div>
            <thead class="bg-slate-100 text-slate-400">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Date</th>
                    <th class="px-6 py-3 text-left font-semibold">Élève</th>
                    <th class="px-6 py-3 text-left font-semibold">Professeur</th>
                    <th class="px-6 py-3 text-left font-semibold">Motif</th>
                    <th class="px-6 py-3 text-left font-semibold">Type de sanction</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sanctions as $sanction) : ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            <div class="flex items-center gap-3">
                                <?= date("d/m/Y", strtotime($sanction["date"])) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            <div class="flex">
                                <div class="bg-red-300/60 rounded-lg px-2 text-red-700 flex gap-2">
                                    <?php
                                    $eleve = getEleveById($sanction['id_eleve'])
                                    ?>
                                    <span><?= $eleve["nom"] ?></span>
                                    <span><?= $eleve["prenom"] ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            <div class="flex">
                                <div class="bg-green-300/60 rounded-lg px-2 text-green  -700 flex gap-2">
                                    <?php
                                    $professeur = getProfesseurById($sanction['id_professeur'])
                                    ?>
                                    <span><?= $professeur["nom"] ?></span>
                                    <span><?= $professeur["prenom"] ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            <div class="text-ellipsis">
                                <?= $sanction['motif'] ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            <div>
                                <?= $sanction['type'] ?>
                            </div>
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