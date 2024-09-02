document.addEventListener('DOMContentLoaded', function() {
    let connectButton = document.getElementById('connect-button');
    connectButton.addEventListener('click', function() {
        window.location.href = admin_link + '&action=displayLogin';
    });
});
