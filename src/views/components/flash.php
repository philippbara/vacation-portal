<?php if (!empty($_SESSION['flash_messages'])) : ?>
    <div id="flashContainer">
        <?php foreach ($_SESSION['flash_messages'] as $msg) : ?>
            <div class="toast <?= htmlspecialchars($msg['type'] ?? 'info') ?>">
                <span><?= htmlspecialchars($msg['text']) ?></span>
                <button class="closeBtn">&times;</button>
            </div>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['flash_messages']); ?>
<?php endif; ?>

<style>
#flashContainer {
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 1000;
}

.toast {
    min-width: 200px;
    padding: 12px 18px;
    border-radius: 5px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.3s, transform 0.3s;
}

/* Toast types */
.toast.success { background: #4caf50; }
.toast.error   { background: #f44336; }
.toast.info    { background: #2196f3; }

.toast.show {
    opacity: 1;
    transform: translateY(0);
}

.toast .closeBtn {
    background: none;
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toasts = document.querySelectorAll('#flashContainer .toast');

    toasts.forEach(toast => {
        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);

        // Auto-hide after 4 seconds
        setTimeout(() => toast.classList.remove('show'), 4000);

        // Close button
        toast.querySelector('.closeBtn').addEventListener('click', () => {
            toast.classList.remove('show');
        });
    });
});
</script>
