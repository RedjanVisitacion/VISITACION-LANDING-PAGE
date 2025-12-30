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
  <title>Messages</title>
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
        <a class="item" href="user.php"><i class='bx bxs-dashboard'></i> <span>Dashboard</span></a>
        <a class="item active" href="messages.php"><i class='bx bxs-conversation'></i> <span>Messages</span></a>
        <a class="item" href="#settings"><i class='bx bxs-cog'></i> <span>Settings</span></a>
        <?php if ($role === 'admin'): ?>
          <a class="item" href="admin.php"><i class='bx bxs-shield'></i> <span>Admin</span></a>
        <?php endif; ?>
      </nav>
    </aside>

    <main class="content">
      <div class="topbar">
        <div class="topbar-left">
          <span class="topbar-title">Messages</span>
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
        <div class="card" style="grid-column: 1 / 2;">
          <h2 class="card-title">Compose Message</h2>
          <form id="msgForm">
            <div style="display:flex;flex-direction:column;gap:10px;">
              <textarea id="msgText" name="content" rows="4" maxlength="2000" placeholder="Type your message..." style="width:100%;padding:12px;border:1px solid #eee;border-radius:8px;resize:vertical" required></textarea>
              <div style="display:flex;justify-content:flex-end;gap:10px;align-items:center;">
                <button type="submit" class="db-btn db-btn-small">Send</button>
              </div>
            </div>
          </form>
          <div id="msgStatus" style="margin-top:10px;color:var(--p-color);"></div>
        </div>

        <div class="card calendar-card" style="grid-column: 2 / 3;">
          <div class="calendar-header">
            <div class="cal-title">Your Messages</div>
          </div>
          <div class="calendar" id="msgList" style="padding:0;">
            <div style="padding:12px;color:#666;">Loading...</div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    (function(){
      var btn = document.getElementById('userMenuBtn');
      var menu = document.getElementById('userMenu');
      if(btn && menu){
        function open(){ menu.classList.add('open'); btn.setAttribute('aria-expanded','true'); }
        function close(){ menu.classList.remove('open'); btn.setAttribute('aria-expanded','false'); }
        btn.addEventListener('click', function(e){ e.stopPropagation(); if(menu.classList.contains('open')){ close(); } else { open(); } });
        btn.addEventListener('keydown', function(e){ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); btn.click(); } });
        document.addEventListener('click', function(e){ if(menu.classList.contains('open') && !menu.contains(e.target) && e.target!==btn && !btn.contains(e.target)) close(); });
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') close(); });
      }
    })();

    async function loadMessages(){
      try {
        const res = await fetch('message_list.php', { credentials: 'same-origin' });
        const data = await res.json();
        const list = document.getElementById('msgList');
        if(!data.success){ list.innerHTML = '<div style="padding:12px;color:#c00;">'+(data.message||'Failed to load')+'</div>'; return; }
        if(!data.items || !data.items.length){ list.innerHTML = '<div style="padding:12px;color:#666;">No messages yet.</div>'; return; }
        list.innerHTML = data.items.map(function(m){
          var d = new Date(m.created_at.replace(' ','T'));
          var when = d.toLocaleString();
          var txt = (m.content||'').replace(/&/g,'&amp;').replace(/</g,'&lt;');
          return '<div class="activity-item" style="border-bottom:1px solid #eee;padding:12px 16px;display:flex;gap:12px;align-items:flex-start">'
              + '<div class="activity-icon" style="background:#f3f3f3;color:var(--blue)"><i class="bx bxs-message"></i></div>'
              + '<div><div class="announcement-title" style="margin:0 0 4px 0">'+when+'</div><div class="announcement-content">'+txt+'</div></div>'
              + '</div>';
        }).join('');
      } catch(err){
        document.getElementById('msgList').innerHTML = '<div style="padding:12px;color:#c00;">Network error.</div>';
      }
    }

    document.getElementById('msgForm').addEventListener('submit', async function(e){
      e.preventDefault();
      var txt = document.getElementById('msgText');
      var status = document.getElementById('msgStatus');
      var val = txt.value.trim();
      if(val===''){ status.textContent = 'Please enter a message.'; return; }
      status.textContent = 'Sending...';
      const body = new URLSearchParams();
      body.set('content', val);
      try{
        const res = await fetch('message_send.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
          credentials: 'same-origin'
        });
        const data = await res.json();
        if(data.success){
          status.style.color = 'green';
          status.textContent = 'Message sent.';
          txt.value='';
          loadMessages();
        } else {
          status.style.color = '#c00';
          status.textContent = data.message || 'Failed to send.';
        }
      }catch(err){
        status.style.color = '#c00';
        status.textContent = 'Network error.';
      }
    });

    loadMessages();
  </script>
</body>
</html>
