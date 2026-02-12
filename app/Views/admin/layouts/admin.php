<!-- layouts/admin.php -->
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Admin'); ?></title>

  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css'); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
  <div class="admin-body">

    <aside class="sidebar">
      <div class="sidebar__brand">
        <div class="sidebar__brand-title">LaporSiantar</div>
      </div>

      <nav class="sidebar__menu" id="adminSidebarMenu">
        <a class="sidebar__item" href="<?= base_url('admin/dashboard'); ?>"
           data-icon="grid" data-active="admin/dashboard">
          <span class="sidebar__icon"></span>
          <span class="sidebar__text">Dashboard</span>
        </a>

        <a class="sidebar__item" href="<?= base_url('admin/pengaduan'); ?>"
           data-icon="file-earmark-text" data-active="admin/pengaduan">
          <span class="sidebar__icon"></span>
          <span class="sidebar__text">Manajemen Pengaduan</span>
        </a>

        <a class="sidebar__item" href="<?= base_url('admin/users'); ?>"
           data-icon="people" data-active="admin/users">
          <span class="sidebar__icon"></span>
          <span class="sidebar__text">Manajemen User</span>
        </a>

        <a class="sidebar__item" href="<?= base_url('admin/berita'); ?>"
           data-icon="newspaper" data-active="admin/berita">
          <span class="sidebar__icon"></span>
          <span class="sidebar__text">Manajemen Berita</span>
        </a>

        <a class="sidebar__item" href="<?= base_url('admin/analitik'); ?>"
           data-icon="bar-chart" data-active="admin/analitik">
          <span class="sidebar__icon"></span>
          <span class="sidebar__text">Laporan & Analitik</span>
        </a>
      </nav>

      <div class="sidebar__bottom">
        <div class="sidebar__user">
          <div class="sidebar__user-left">
            <div class="sidebar__avatar"></div>
            <div class="sidebar__user-meta">
              <div class="sidebar__user-name"><?= esc($userName ?? 'Admin'); ?></div>
              <div class="sidebar__user-role"><?= esc($userRole ?? 'Super Admin'); ?></div>
            </div>
          </div>

          <a class="sidebar__logout" href="<?= base_url('logout'); ?>" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
          </a>
        </div>
      </div>
    </aside>

    <main class="admin-main">
      <?= $this->renderSection('content'); ?>
    </main>

  </div>

  <script src="<?= base_url('assets/js/sidebar-icons.js'); ?>"></script>
</body>
</html>
