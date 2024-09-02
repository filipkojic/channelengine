document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login-form');
    const accountNameInput = document.getElementById('account_name');
    const apiKeyInput = document.getElementById('api_key');

    form.addEventListener('submit', function (event) {
        let isValid = true;

        if (accountNameInput.value.trim() === '') {
            alert('Please enter an account name.');
            accountNameInput.focus();
            isValid = false;
        }

        if (apiKeyInput.value.trim() === '') {
            alert('Please enter an API key.');
            apiKeyInput.focus();
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
