document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets
    const tabs = document.querySelectorAll('.tab');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Fermer tous les panneaux
            document.querySelectorAll('.panel').forEach(panel => {
                panel.style.maxHeight = null;
            });
            
            // Ouvrir le panneau sélectionné
            const panel = this.nextElementSibling;
            panel.style.maxHeight = panel.scrollHeight + "px";
        });
    });

    // Animation des dropdowns
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('mouseenter', function() {
            this.querySelector('.dropdown-content').style.opacity = '1';
            this.querySelector('.dropdown-content').style.pointerEvents = 'auto';
        });

        dropdown.addEventListener('mouseleave', function() {
            this.querySelector('.dropdown-content').style.opacity = '0';
            this.querySelector('.dropdown-content').style.pointerEvents = 'none';
        });
    });

    // Gestion des formulaires
    // const forms = document.querySelectorAll('form');
    // forms.forEach(form => {
    //     form.addEventListener('submit', function(e) {
    //         e.preventDefault();
    //         const formData = new FormData(this);
    //         // À remplacer par votre logique de traitement des données
    //         console.log([...formData.entries()]);
    //         this.reset();
    //     });
    // });
});