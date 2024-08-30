<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/welcome.css">

<div class="welcome-container">
    <img src="{$module_dir}views/img/logo.png" alt="Channel Engine Logo" class="welcome-logo">
    <h1 class="welcome-title">Welcome to ChannelEngine</h1>
    <p class="welcome-message">Connect, sync product data to ChannelEngine and orders to your shop.</p>
    <button onclick="window.location.href='{$link->getAdminLink('AdminChannelEngine')}&action=displayLogin'" class="connect-button">Connect</button>
</div>

