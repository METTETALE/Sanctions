<nav class="bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400">
    <div class="container mx-auto px-6 py-2 flex justify-between items-center">
        <div class="flex items-center space-x-6">
            <a class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 rounded-md text-sm font-medium" href="index?action=dashboard">
                <span class="material-symbols-outlined text-base">🏠</span>
                Tableau de Bord
            </a>
            <a class="flex items-center gap-2 text-sm font-medium hover:bg-white/10 px-3 py-2 rounded-md transition-colors" href="index?action=sanctions">
                <span class="material-symbols-outlined text-base">⚖️</span>
                Sanctions
            </a>
            <a class="flex items-center gap-2 text-sm font-medium hover:bg-white/10 px-3 py-2 rounded-md transition-colors" href="index.php?action=eleves">
                <span class="material-symbols-outlined text-base">👥</span>
                Élèves
            </a>
            <a class="flex items-center gap-2 text-sm font-medium hover:bg-white/10 px-3 py-2 rounded-md transition-colors" href="index.php?action=professeurs">
                <span class="material-symbols-outlined text-base">👨‍🏫</span>
                Professeurs
            </a>
            <a href="index.php?action=classes" class="flex items-center gap-2 text-sm font-medium hover:bg-white/10 px-3 py-2 rounded-md transition-colors" href="#">
                <span class="material-symbols-outlined text-base">🏫</span>
                Classes
            </a>
            <a class="flex items-center gap-2 text-sm font-medium hover:bg-white/10 px-3 py-2 rounded-md transition-colors" href="#">
                <span class="material-symbols-outlined text-base">👤</span>
                Utilisateurs
            </a>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm">Bonjour, <?= $_SESSION['user_prenom'] ?></span>
            <a class="flex items-center gap-2 text-sm font-medium hover:bg-white/10 px-3 py-2 rounded-md transition-colors" href="index?action=logout">
                <span class="material-symbols-outlined text-base">🚪</span>
                Déconnexion
            </a>
        </div>
    </div>
</nav>