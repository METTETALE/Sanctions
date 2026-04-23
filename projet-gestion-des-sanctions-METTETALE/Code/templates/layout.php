<?php
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Application de Gestion des Sanctions</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-slate-100 text-gray-700">
    <div class="min-h-screen flex flex-col">
        <header class="shadow-sm bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📋</span>
                        <span class="text-xl font-bold text-blue-600">Gestion des Sanctions</span>
                    </div>
                    <div class="hidden md:block text-gray-500 ">
                        Application Vie Scolaire
                    </div>
                </div>
            </div>
        </header>

        <?php if (isset($_SESSION['user_id'])) {
            include __DIR__ . '/navbar/navbarUser.php';
        } else {
            include __DIR__ . '/navbar/navbarVisitor.php';
        }
        ?>

        <main>
            <div class="pt-4">

                <?php if (isset($errors) and $errors != []): ?>
                    <?php foreach ($errors as $error): ?>
                        <div class="bg-red-400 rounded-sm px-4 py-2 text-center"><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="bg-green-400 rounded-sm px-4 py-2 text-center"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

            </div>

            <?= $content ?? '' ?>
        </main>
        <footer class="bg-gray-800  text-gray-300">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl">📋</span>
                            <h3 class="text-lg font-semibold text-white">Gestion des Sanctions</h3>
                        </div>
                        <p class="text-sm text-gray-400">Application de gestion de la vie scolaire pour le suivi des sanctions et incidents.</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl">🔗</span>
                            <h3 class="text-lg font-semibold text-white">Liens utiles</h3>
                        </div>
                        <ul class="space-y-2 text-sm">
                            <li><a class="text-gray-400 hover:text-white transition-colors" href="">Documentation</a></li>
                            <li><a class="text-gray-400 hover:text-white transition-colors" href="">Support</a></li>
                            <li><a class="text-gray-400 hover:text-white transition-colors" href="">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl">ℹ️</span>
                            <h3 class="text-lg font-semibold text-white">Informations</h3>
                        </div>
                        <p class="text-sm text-gray-400">Développé dans le cadre du BTS SIO - Projet CCF 2025</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

</body>

</html>