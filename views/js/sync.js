document.addEventListener('DOMContentLoaded', function() {
    let syncButton = document.getElementById('sync-button');

    syncButton.addEventListener('click', function() {
        // Pošalji AJAX POST zahtev ka backendu
        fetch(admin_link + '&action=startSync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Obrada odgovora
                if (data.success) {
                    document.querySelector('.sync-status').innerHTML = '<span class="sync-done">Synchronization successful!</span>';
                } else {
                    document.querySelector('.sync-status').innerHTML = '<span class="sync-error">Synchronization failed: ' + data.message + '</span>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.querySelector('.sync-status').innerHTML = '<span class="sync-error">An error occurred during synchronization</span>';
            });
    });
});
