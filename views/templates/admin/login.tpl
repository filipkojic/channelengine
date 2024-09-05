<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/login.css">

<div class="login-container">
    <div class="login-header">
        <h2>Login to ChannelEngine</h2>
        <a href="{$module_dir}"><img src="{$module_dir}views/img/close.png" alt="Close"></a>
    </div>

    <p class="login-message">Please enter account data:</p>
    <form id="login-form" method="post" action="{$link->getAdminLink('AdminChannelEngine')}&action=handleLogin">
        <div class="form-group">
            <label for="account_name">Account name</label>
            <input type="text" name="account_name" id="account_name">
        </div>
        <div class="form-group">
            <label for="api_key">Api key</label>
            <input type="text" name="api_key" id="api_key">
        </div>
        <button type="submit" class="login-button">Connect</button>
    </form>
</div>

<script src="{$module_dir}views/js/login.js"></script>