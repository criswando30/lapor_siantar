<?php
$loginError   = session()->getFlashdata('login_error');
$loginSuccess = session()->getFlashdata('login_success');
?>

<!-- OVERLAY -->
<div id="loginModal" class="modal" aria-hidden="true">

  <!-- Backdrop -->
  <div class="modal__backdrop" onclick="closeLoginModal()" aria-hidden="true"></div>

  <!-- Modal Box -->
  <div class="modal__panel modal-login" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">

    <!-- Header -->
    <div class="modal__header modal-login__header">
      <h2 id="loginModalTitle" class="modal__title modal-login__title">MASUK</h2>

      <button type="button" class="modal__close modal-login__close" onclick="closeLoginModal()" aria-label="Tutup">
        ✕
      </button>
    </div>

    <!-- Body -->
    <div class="modal__body modal-login__body">

      <?php if (!empty($loginError)): ?>
        <div class="status-alert status-alert--danger" style="margin-bottom:1rem;">
          <?= esc($loginError) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($loginSuccess)): ?>
        <div class="status-alert status-alert--success" style="margin-bottom:1rem;">
          <?= esc($loginSuccess) ?>
        </div>
      <?php endif; ?>

      <form action="<?= site_url('login') ?>" method="post" class="modal-form modal-login__form">
        <?= csrf_field() ?>

        <div class="modal-field login-field">
          <label class="modal-label" for="login_identifier">
            Email, No. telp, atau username
          </label>
          <input
            id="login_identifier"
            name="identifier"
            type="text"
            value="<?= old('identifier') ?>"
            placeholder=""
            class="modal-input"
            required
            autocomplete="username"
          />
        </div>

        <div class="modal-field login-field">
          <div class="modal-row">
            <label class="modal-label" for="login_password">Password</label>
            <a href="<?= site_url('forgot-password') ?>" class="modal-link">
              Lupa password?
            </a>
          </div>

          <input
            id="login_password"
            name="password"
            type="password"
            placeholder=""
            class="modal-input"
            required
            autocomplete="current-password"
          />
        </div>

        <button type="submit" class="btn btn--primary btn-login">
          MASUK
        </button>
      </form>

    </div>

    <!-- Footer -->
    <div class="modal-login__footer">
      <p>Anda belum memiliki akun?</p>
      <a href="<?= site_url('register') ?>" class="modal-foot__link">
        DAFTAR SEKARANG
      </a>
    </div>

  </div>
</div>

<script>
  function openLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');

    document.body.classList.add('no-scroll');

    setTimeout(() => {
      const idn = document.getElementById('login_identifier');
      if (idn) idn.focus();
    }, 50);
  }

  function closeLoginModal() {
    const modal = document.getElementById('loginModal');
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');

    document.body.classList.remove('no-scroll');
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLoginModal();
  });
</script>

<?php if (!empty($loginError)): ?>
  <script>
    window.addEventListener('load', () => openLoginModal());
  </script>
<?php endif; ?>
