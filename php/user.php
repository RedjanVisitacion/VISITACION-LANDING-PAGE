<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../html/Login.html'); exit; }
$username = htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8');
$role = htmlspecialchars($_SESSION['role'] ?? 'user', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Dashboard</title>
  <link rel="icon" href="../img/icon.png">
  <link rel="stylesheet" href="../css/styles.css?v=<?php echo filemtime(__DIR__ . '/../css/styles.css'); ?>" />
  <link rel="stylesheet" href="../css/dashboard.css?v=<?php echo filemtime(__DIR__ . '/../css/dashboard.css'); ?>" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <div class="dashboard-layout">
    <aside class="sidebar">
      <div class="brand">
        <img src="../img/logo.png" alt="logo" class="brand-logo" />
      </div>
      <nav class="menu">
        <a class="item active"><i class='bx bxs-dashboard'></i> <span>Dashboard</span></a>
        <a class="item"><i class='bx bxs-conversation'></i> <span>Messages</span></a>
        <a class="item"><i class='bx bxs-cog'></i> <span>Settings</span></a>
        <?php if ($role === 'admin'): ?>
          <a class="item" href="admin.php"><i class='bx bxs-shield'></i> <span>Admin</span></a>
        <?php endif; ?>
      </nav>
    </aside>

    <main class="content">
      <div class="topbar">
        <div class="content-header">
          <h3>Welcome back, <span class="accent"><?php echo $username; ?></span></h3>
        </div>
        <div class="topbar-user" id="userMenuBtn" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">
          <div class="topbar-name"><?php echo $username; ?></div>
          <div class="topbar-avatar"><?php echo strtoupper(substr($username,0,1)); ?></div>
          <div class="user-menu" id="userMenu" aria-label="User menu">
            <a href="#profile"><i class='bx bxs-user'></i> <span>Profile</span></a>
            <a href="logout.php"><i class='bx bxs-exit'></i> <span>Logout</span></a>
          </div>
        </div>
      </div>

      <section class="grid">
        <div class="card quick-links">
          <h2 class="card-title">Quick Actions</h2>
          <div class="tiles">
            <a class="tile" href="#"><i class='bx bxs-bell'></i><span>Notifications</span></a>
            <a class="tile" href="#"><i class='bx bxs-book-content'></i><span>Notes</span></a>
            <a class="tile" href="#"><i class='bx bxs-file-doc'></i><span>Documents</span></a>
            <a class="tile" href="#"><i class='bx bxs-bar-chart-alt-2'></i><span>Reports</span></a>
          </div>
        </div>

        <div class="card calendar-card">
          <div class="calendar-header">
            <button id="calPrev" class="cal-btn" aria-label="Previous month"><i class='bx bx-chevron-left'></i></button>
            <div id="calTitle" class="cal-title"></div>
            <button id="calNext" class="cal-btn" aria-label="Next month"><i class='bx bx-chevron-right'></i></button>
          </div>
          <div class="calendar" id="calendar"></div>
          <div class="calendar-footer">
            <button id="calToday" class="db-btn db-btn-small">Today</button>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    (function(){
      const el = {
        wrap: document.getElementById('calendar'),
        title: document.getElementById('calTitle'),
        prev: document.getElementById('calPrev'),
        next: document.getElementById('calNext'),
        today: document.getElementById('calToday')
      };
      const dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
      let view = new Date();
      view.setDate(1);

      function daysInMonth(y,m){ return new Date(y, m+1, 0).getDate(); }
      function sameDate(a,b){ return a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate(); }

      function render(){
        const y = view.getFullYear();
        const m = view.getMonth();
        const today = new Date();
        el.title.textContent = view.toLocaleString(undefined, { month: 'long', year: 'numeric' });

        const firstDow = new Date(y, m, 1).getDay();
        const total = daysInMonth(y, m);
        let html = '<div class="cal-grid">';
        for (let i=0;i<7;i++){ html += `<div class=\"cal-dow\">${dayNames[i]}</div>`; }
        for (let i=0;i<firstDow;i++){ html += '<div class=\"cal-cell cal-pad\"></div>'; }
        for (let d=1; d<=total; d++){
          const cur = new Date(y, m, d);
          const cls = ['cal-cell'];
          if (sameDate(cur, today)) cls.push('today');
          html += `<div class=\"${cls.join(' ')}\">${d}</div>`;
        }
        html += '</div>';
        el.wrap.innerHTML = html;
      }

      el.prev.addEventListener('click', function(){ view.setMonth(view.getMonth()-1); render(); });
      el.next.addEventListener('click', function(){ view.setMonth(view.getMonth()+1); render(); });
      el.today.addEventListener('click', function(){ view = new Date(); view.setDate(1); render(); });
      render();
    })();

    // User menu toggle
    (function(){
      var btn = document.getElementById('userMenuBtn');
      var menu = document.getElementById('userMenu');
      if(!btn || !menu) return;
      function open(){ menu.classList.add('open'); btn.setAttribute('aria-expanded','true'); }
      function close(){ menu.classList.remove('open'); btn.setAttribute('aria-expanded','false'); }
      btn.addEventListener('click', function(e){ e.stopPropagation(); if(menu.classList.contains('open')){ close(); } else { open(); } });
      btn.addEventListener('keydown', function(e){ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); btn.click(); } });
      document.addEventListener('click', function(e){ if(menu.classList.contains('open') && !menu.contains(e.target) && e.target!==btn && !btn.contains(e.target)) close(); });
      document.addEventListener('keydown', function(e){ if(e.key==='Escape') close(); });
    })();
  </script>
</body>
</html>
