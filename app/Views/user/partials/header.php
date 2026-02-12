<?php
$path = trim(service('uri')->getPath(), '/');

function navClass(bool $active): string
{
  return $active
    ? 'nav__link nav__link--active'
    : 'nav__link';
}
?>

<header class="site-header">
  <div class="container site-header__inner">

    <!-- LOGO -->
    <a href="<?= site_url() ?>" class="site-logo">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="LaporSiantar Logo" />
    </a>

    <!-- NAVIGATION -->
    <nav class="site-nav" aria-label="Navigasi Utama">
      <a class="<?= navClass($path === '') ?>" href="<?= site_url() ?>">BERANDA</a>

      <a class="<?= navClass(str_starts_with($path, 'status')) ?>" href="<?= site_url('status') ?>">
        STATUS LAPORAN
      </a>

      <a class="<?= navClass(str_starts_with($path, 'tentang')) ?>" href="<?= site_url('tentang') ?>">
        TENTANG
      </a>

      <a class="<?= navClass(str_starts_with($path, 'berita')) ?>" href="<?= site_url('berita') ?>">
        BERITA
      </a>

      <?php if (session()->get('isLoggedIn')): ?>
        <div class="nav-user">
          <span class="nav-user__name">
            Halo, <?= esc(session('nama')) ?>
          </span>
          <a href="<?= site_url('logout') ?>" class="btn btn--primary btn--pill btn--sm">
            LOGOUT
          </a>
        </div>
      <?php else: ?>
        <button type="button" onclick="openLoginModal()" class="btn btn--primary btn--pill btn--sm">
          MASUK
        </button>
      <?php endif; ?>

    </nav>

    <!-- MOBILE MENU BUTTON -->
    <button class="mobile-menu-btn" type="button" aria-label="Menu">
      <span class="material-icons-round">menu</span>
    </button>

  </div>
</header>