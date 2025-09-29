<header style="display:flex; justify-content:space-between; align-items:center; padding:10px; background:#eee;">
    <h1><?= htmlspecialchars($pageTitle ?? 'Vacation Portal') ?></h1>

    <?php if (!empty($_SESSION['user'])): ?>
    <div style="position:relative; display:inline-block;">
        <!-- Profile Button -->
        <button id="profileBtn" style="background:none; border:none; cursor:pointer;">
            <img src="/img/default-profile.png" alt="Profile" style="width:40px; height:40px; border-radius:50%;">
        </button>

        <!-- Dropdown Menu -->
        <ul id="profileDropdown" style="display:none; position:absolute; right:0; top:50px; list-style:none; margin:0; padding:0; background:#fff; border:1px solid #ccc; min-width:180px; box-shadow:0 2px 5px rgba(0,0,0,0.2); z-index:100;">
            <!-- Username at the top, non-clickable -->
            <li style="padding:10px; font-weight:bold; border-bottom:1px solid #ccc; color:#333;">
                <?= htmlspecialchars($_SESSION['user']['username']) ?>
            </li>
            <li><a href="/logout" style="display:block; padding:10px; text-decoration:none; color:#000;">Logout</a></li>
        </ul>
    </div>
    <?php endif; ?>
</header>

<script>
    const btn = document.getElementById('profileBtn');
    const menu = document.getElementById('profileDropdown');

    if (btn) { // only attach if button exists
        btn.addEventListener('click', () => {
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    }
</script>
