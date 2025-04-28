<?php 
$unreadCount = session()->get('unreadCount') ?? 0;
?>

<?php if ($unreadCount > 0): ?>
    <span class="notification-badge"><?= $unreadCount ?></span>
<?php endif; ?> 