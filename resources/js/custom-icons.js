// Icônes Bootstrap pour la sidebar CRM
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que Filament charge
    setTimeout(function() {
        // Ajouter des icônes Bootstrap aux éléments de navigation
        const navItems = document.querySelectorAll('.fi-sidebar-nav-item a');

        navItems.forEach(item => {
            const text = item.textContent.trim();

            // Supprimer les icônes existantes
            const existingIcon = item.querySelector('.fi-sidebar-nav-item-icon, .bi');
            if (existingIcon) {
                existingIcon.remove();
            }

            // Créer une nouvelle icône Bootstrap
            const iconElement = document.createElement('i');
            iconElement.className = 'bi mr-2';

            let iconClass = '';
            if (text.includes('Dashboard')) {
                iconClass = 'bi-speedometer2';
            } else if (text.includes('Clients')) {
                iconClass = 'bi-building';
            } else if (text.includes('Contacts')) {
                iconClass = 'bi-people';
            } else if (text.includes('Tâches') || text.includes('Tasks')) {
                iconClass = 'bi-check2-square';
            } else if (text.includes('Historique')) {
                iconClass = 'bi-clock-history';
            }

            if (iconClass) {
                iconElement.className = `bi ${iconClass} mr-2`;
                item.insertBefore(iconElement, item.firstChild);
            }
        });
    }, 1000);
});