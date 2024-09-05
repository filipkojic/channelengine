document.addEventListener('DOMContentLoaded', function() {
    let syncButton = document.getElementById('sync-button');
    let syncStatus = document.querySelector('.sync-status');

    syncButton.addEventListener('click', async function() {
        try {
            // Prikaži status "In progress" pre nego što započne sinhronizacija
            syncStatus.innerHTML = '<span class="sync-progress">In progress...</span>';

            // Pošalji AJAX POST zahtev ka backendu
            let response = await fetch(admin_link + '&action=startSync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            let data = await response.json();

            // Obrada odgovora
            if (data.success) {
                syncStatus.innerHTML = '<span class="sync-done">Synchronization successful!</span>';
            } else {
                syncStatus.innerHTML = '<span class="sync-error">Synchronization failed: ' + data.message + '</span>';
            }
        } catch (error) {
            console.error('Error:', error);
            syncStatus.innerHTML = '<span class="sync-error">An error occurred during synchronization</span>';
        }
    });
});
