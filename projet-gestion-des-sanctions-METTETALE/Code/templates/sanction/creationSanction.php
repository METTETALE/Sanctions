<?php
ob_start();

$typeSanction = [
    'Avertissement',
    'Exclusion Temporaire',
    'Exclusion Parking',
    'Retenue',
]

?>

<div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-700 text-white p-8 rounded-lg text-center mb-16">
        <div class="flex items-center justify-center">
            <span class="text-5xl">⚠️</span>
            <h1 class="text-4xl font-bold">Créer une sanction</h1>
        </div>
        <p class="mt-2 text-red-100">Ajouter une nouvelle sanction à un élève</p>
    </div>
    <div class="max-w-xl mx-auto">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <div class="mb-8">
                <h1 class="font-bold text-xl">Informations de la sanction</h1>
                <h2 class="font-semibold text-sm text-gray-500">Renseignez les informations nécessaires pour créer la sanction</h2>
            </div>
            <form class="space-y-6" method="POST">
                <div class="flex justify-between">
                    <div class="w-[47%]">
                        <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="eleve">
                            Élève <span class="text-red-600">*</span>
                        </label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 bg-gray-50 text-gray-900" id="eleve" name="eleve">
                            <option value="">Sélectionner un élève</option>
                            <?php foreach ($eleves as $eleve) : ?>
                                <option value="<?= $eleve['id'] ?>" <?= ($eleve['id'] == $selectedEleve && $eleve['id'] != '') ? 'selected' : '' ?>><?= ucfirst($eleve['prenom']) ?> <?= ucfirst($eleve['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="w-[47%]">
                        <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="professeur">
                            Professeur <span class="text-red-600">*</span>
                        </label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 bg-gray-50 text-gray-900" id="professeur" name="professeur">
                            <option value="">Sélectionner un professeur</option>
                            <?php foreach ($professeurs as $professeur) : ?>
                                <option value="<?= $professeur['id'] ?>" <?= ($professeur['id'] == $selectedProfesseur && $professeur['id'] != '') ? 'selected' : '' ?>><?= ucfirst($professeur['prenom']) ?> <?= ucfirst($professeur['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="type">
                        Type de sanction <span class="text-red-600">*</span>
                    </label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 bg-gray-50 text-gray-900" id="type" name="type">
                        <option value="">Sélectionner un type de sanction</option>
                        <?php foreach ($typeSanction as $typeOption) : ?>
                            <option value="<?= $typeOption ?>" <?= ($typeOption == $type && $typeOption != '') ? 'selected' : '' ?>><?= $typeOption ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="date">
                        Date <span class="text-red-600">*</span>
                    </label>
                    <input class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 bg-gray-50 text-gray-900" id="date" name="date" type="date" value="<?= $date ?>" />
                </div>
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-medium text-gray-700" for="motif">
                        Motif <span class="text-red-600">*</span>
                    </label>
                    <textarea maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 bg-gray-50 text-gray-900 placeholder-gray-400" id="motif" name="motif" placeholder="Décrivez le motif de la sanction" rows="4"><?= $motif ?></textarea>
                </div>
                <div class="flex justify-between">
                    <a href="index.php?action=sanctions" class="w-[49%] flex items-center justify-center gap-2 bg-slate-100 text-gray-600 font-bold py-3 px-4 rounded-md hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <span class="text-base">🔙</span>
                        Retour à la liste
                    </a>
                    <button class="w-[49%] flex items-center justify-center gap-2 bg-red-500 text-white font-bold py-3 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" type="submit">
                        <span class="material-symbols-outlined">⚠️</span>
                        Créer la sanction
                    </button>
                </div>
            </form>
        </div>
        <div class="flex text-red-600 bg-slate-100 border border-slate-300 rounded-lg p-4 mt-6 gap-4">
            <div class="w-[10%]">ℹ️</div>
            <div class="w-full text-sm">
                💡 Conseil <br>
                Assurez-vous de remplir tous les champs avec les informations correctes avant de créer la sanction.
            </div>
        </div>
    </div>
</div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>