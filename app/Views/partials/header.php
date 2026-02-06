<?php
$path = trim(service('uri')->getPath(), '/');

// helper kecil biar class rapi
function navClass(bool $active): string
{
  return $active
    ? 'text-sm font-bold text-primary'
    : 'text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-primary transition-colors';
}
?>

<header
  class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
  <div class="container mx-auto px-6 h-20 flex items-center justify-between">

    <!-- LOGO -->
    <a href="<?= site_url() ?>" class="flex items-center h-full">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="LaporSiantar Logo"
        class="h-[90%] w-auto max-w-[220px] object-contain" />
    </a>

    <!-- NAVIGATION -->
    <nav class="hidden md:flex items-center space-x-8">
      <a class="<?= navClass($path === '') ?>" href="<?= site_url() ?>">BERANDA</a>

      <a class="<?= navClass(str_starts_with($path, 'status')) ?>" href="<?= site_url('status') ?>">
        STATUS LAPORAN
      </a>

      <a class="<?= navClass(str_starts_with($path, 'tentang')) ?>" href="<?= site_url('tentang') ?>">
        TENTANG
      </a>

      <button type="button" onclick="openLoginModal()"
        class="px-6 py-2 text-sm font-bold bg-primary text-white rounded-full hover:bg-secondary transition-all">
        MASUK
      </button>

    </nav>

    <!-- MOBILE MENU BUTTON -->
    <button class="md:hidden text-gray-600 dark:text-gray-300" type="button" aria-label="Menu">
      <span class="material-icons-round text-3xl">menu</span>
    </button>

  </div>
</header>