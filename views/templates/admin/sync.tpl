<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/sync.css">

<div class="sync-container">
    <img src="{$module_dir}views/img/logo.png" alt="Channel Engine Logo" class="sync-logo">
    <h1 class="sync-title">Sync Status:</h1>
    <p class="sync-status">
        <span class="sync-done">Done</span> |
        <span class="sync-progress">In progress</span> |
        <span class="sync-error">Error</span>
    </p>
    <button id="sync-button" class="sync-button">Synchronize</button>
</div>

<script>
    let admin_link = '{$link->getAdminLink('AdminChannelEngine')}';
</script>

<script src="{$module_dir}views/js/sync.js"></script>