document.querySelectorAll('.add-event-btn').forEach(button => {
    button.addEventListener('click', function() {
        const name = button.getAttribute('data-name');
        const startDate = button.getAttribute('data-start-date');
        const endDate = button.getAttribute('data-end-date');

        fetch(createEventUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: name,
                start_date: startDate,
                end_date: endDate
            }),
        })
        .then(response => {
            if (response.ok) {
                console.log('Événement créé avec succès !');
                // Rafraîchir la page ou actualiser la vue du calendrier si nécessaire
            } else {
                console.error('Erreur lors de la création de l\'événement');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la création de l\'événement:', error);
        });
    });
});
