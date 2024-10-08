document.addEventListener('DOMContentLoaded', function() {
    let syncButton = document.getElementById('sync-button');
    let syncStatus = document.querySelector('.sync-status');

    syncButton.addEventListener('click', async function() {
        try {
            syncStatus.innerHTML = '<span class="sync-progress">In progress...</span>';

            let response = await fetch(admin_link + '&action=startSync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            let data = await response.json();

            if (!data.success) {
                syncStatus.innerHTML = '<span class="sync-error">Synchronization failed: ' + data.message + '</span>';
                return;
            }

            syncStatus.innerHTML = '<span class="sync-done">Synchronization successful!</span>';
        } catch (error) {
            console.error('Error:', error);
            syncStatus.innerHTML = '<span class="sync-error">An error occurred during synchronization</span>';
        }
    });
});
