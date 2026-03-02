import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Déclaration de ton composant de compte à rebours
Alpine.data('countdown', (expiry) => ({
    days: 0,
    init() {
        const endDate = new Date(expiry).getTime();
        const update = () => {
            const now = new Date().getTime();
            const diff = endDate - now;

            // Calcul du nombre de jours restants
            this.days = Math.max(0, Math.floor(diff / (1000 * 60 * 60 * 24)));
        };

        update();
        setInterval(update, 60000); // Mise à jour toutes les minutes
    }
}));

Alpine.start();
