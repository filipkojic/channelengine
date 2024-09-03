document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('sync-button').addEventListener('click', function() {
        // Display loading message or spinner
        //alert('Synchronization started...');

        // Make an AJAX request to start synchronization
        fetch(admin_link + '&action=startSync', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Synchronization completed successfully.');
                } else {
                    alert('An error occurred during synchronization.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            });
    });
});
