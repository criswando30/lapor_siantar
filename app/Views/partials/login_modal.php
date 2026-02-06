<?php
$loginError   = session()->getFlashdata('login_error');
$loginSuccess = session()->getFlashdata('login_success');
?>

<!-- OVERLAY -->
<div
  id="loginModal"
  class="fixed inset-0 z-[999] hidden items-center justify-center px-4"
  aria-hidden="true"
>
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black/40" onclick="closeLoginModal()"></div>

  <!-- Modal Box -->
  <div class="relative w-full max-w-sm bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="relative px-5 py-4 border-b border-gray-100">
      <!-- Judul center -->
      <h2 class="absolute inset-0 flex items-center justify-center text-sm font-extrabold tracking-widest text-gray-800 uppercase">
        MASUK
      </h2>

      <!-- Tombol close -->
      <button
        type="button"
        class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700"
        onclick="closeLoginModal()"
        aria-label="Tutup"
      >
        ✕
      </button>
    </div>

    <!-- Body -->
    <div class="p-5">
      <?php if (!empty($loginError)): ?>
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
          <?= esc($loginError) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($loginSuccess)): ?>
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
          <?= esc($loginSuccess) ?>
        </div>
      <?php endif; ?>

      <form action="<?= site_url('login') ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>

        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1" for="login_identifier">
            Email / No. Telp / Username
          </label>
          <input
            id="login_identifier"
            name="identifier"
            type="text"
            value="<?= old('identifier') ?>"
            placeholder="Masukkan email / no. telp / username"
            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
            required
            autocomplete="username"
          />
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label class="block text-xs font-semibold text-gray-700 mb-1" for="login_password">Password</label>
            <a href="<?= site_url('forgot-password') ?>" class="text-xs text-blue-600 hover:underline">
              Lupa password?
            </a>
          </div>
          <input
            id="login_password"
            name="password"
            type="password"
            placeholder="Masukkan password"
            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200"
            required
            autocomplete="current-password"
          />
        </div>

        <button
          type="submit"
          class="w-full py-2.5 bg-primary text-white text-sm font-extrabold rounded-md hover:bg-secondary transition"
        >
          MASUK
        </button>
      </form>

      <div class="mt-4 text-center text-xs text-gray-600">
        Anda belum memiliki akun?
        <a href="<?= site_url('register') ?>" class="text-blue-600 font-bold hover:underline">
          DAFTAR SEKARANG
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  function openLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.setAttribute('aria-hidden', 'false');

    // lock scroll (biar UX modal lebih rapi)
    document.body.classList.add('overflow-hidden');

    // fokus ke identifier
    setTimeout(() => {
      const idn = document.getElementById('login_identifier');
      if (idn) idn.focus();
    }, 50);
  }

  function closeLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modal.setAttribute('aria-hidden', 'true');

    // unlock scroll
    document.body.classList.remove('overflow-hidden');
  }

  // ESC untuk menutup
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLoginModal();
  });
</script>

<?php if (!empty($loginError)): ?>
  <script>
    // kalau login gagal, otomatis buka modal agar error terlihat
    window.addEventListener('load', () => openLoginModal());
  </script>
<?php endif; ?>
