<?php
ob_start();

?>

<div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-700 text-white p-8 rounded-lg text-center mb-16">
        <div class="flex items-center justify-center">
            <span class="text-5xl">➕</span>
            <h1 class="text-4xl font-bold">Créer un élève</h1>
        </div>
        <p class="mt-2 text-blue-100">Ajouter un nouvel élève à votre établissement</p>
    </div>
    <div class="max-w-xl mx-auto">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <div class="mb-8">
                <h1 class="font-bold text-xl">Informations de l'élève</h1>
                <h2 class="font-semibold text-sm text-gray-500">Renseignez les informations nécessaires pour créer l'élève</h2>
            </div>
            <form class="space-y-6" method="POST">
                <div class="flex justify-between">
                    <div class="w-[47%]">
                        <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="email">
                            Nom <span class="text-red-600">*</span>
                        </label>
                        <input class="w-full px-4 py-2 border border-gray-300  rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50  text-gray-900  placeholder-gray-400 " id="nomEleve" name="nomEleve" placeholder="Ex: Martin" type="text" value="<?= $nomEleve ?>" />
                    </div>
                    <div class="w-[47%]">
                        <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="email">
                            Prenom <span class="text-red-600">*</span>
                        </label>
                        <input class="w-full px-4 py-2 border border-gray-300  rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50  text-gray-900  placeholder-gray-400 " id="prenomEleve" name="prenomEleve" placeholder="Ex: Jean" type="text" value="<?= $prenomEleve ?>" />
                    </div>
                </div>
                <div>
                    <label class=" flex items-center gap-2 mb-2 text-sm font-medium text-gray-700 " for=" password">
                        Date de naissance <span class="text-red-600">*</span>
                    </label>
                    <input class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-gray-900" id="dateNaissance" name="dateNaissance" type="date" value="<?= $dateNaissance ?>" />
                    <h2 class="mt-2 text-sm text-gray-500">Format: JJ-MM-AAAA</h2>
                </div>
                <div>
                    <label class=" flex items-center gap-2 mb-2 text-sm font-medium text-gray-700 " for=" password">
                        Classe <span class="text-red-600">*</span>
                    </label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-gray-900" id="niveau" name="niveau">
                        <option value="">Sélectionner une classe</option>
                        <?php foreach ($classes as $classe) : ?>
                            <option value="<?= $classe['id_classe'] ?>" <?= ($classe['id_classe'] == $selectedClasse) ? 'selected' : '' ?>><?= ucfirst($classe['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-between">
                    <a href="index.php?action=eleves" class="w-[49%] flex items-center justify-center gap-2 bg-slate-100 text-gray-600 font-bold py-3 px-4 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500  transition-colors">
                        <span class="text-base">🔙</span>
                        Retour à la liste
                    </a>
                    <button class="w-[49%] flex items-center justify-center gap-2 bg-blue-500 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500  transition-colors" type="submit">
                        <span class="material-symbols-outlined">➕</span>
                        Créer l'élève
                    </button>
                </div>
            </form>
        </div>
        <div class="flex text-blue-600 bg-slate-100 border border-slate-300 rounded-lg p-4 mt-6 gap-4">
            <div class="w-[10%]">ℹ️</div>
            <div class="w-full text-sm">
                💡 Conseil <br>
                Une fois la élève créée, vous pourrez y associer des élèves et gérer leurs sanctions.
            </div>
        </div>
    </div>
</div>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>