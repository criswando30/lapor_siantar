<?= $this->extend('user/layouts/auth') ?>
<?= $this->section('content') ?>

<section class="auth-page" style="background:#1d84e4;">
  <div class="auth-logo">
    <img src="<?= base_url('assets/img/logowhite.png') ?>" alt="Lapor E-Gov Siantar" class="auth-logo__img" />
  </div>

  <div class="auth-card">
    <div class="auth-card__head">
      <h1 class="auth-title">Daftar Akun</h1>
    </div>

    <div class="auth-card__body">
      <?php
      $error = session()->getFlashdata('register_error');
      $success = session()->getFlashdata('register_success');
      $validation = $validation ?? session('validation');
      $errors = session('errors');
      ?>

      <?php if (!empty($success)): ?>
        <div class="status-alert status-alert--success">
          <?= esc($success) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="status-alert status-alert--danger">
          <?= esc($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($validation && method_exists($validation, 'listErrors')): ?>
        <?= $validation->listErrors('list') ?>
      <?php endif; ?>

      <?php if (is_array($errors) && !empty($errors)): ?>
        <div class="status-alert status-alert--danger">
          <ul class="auth-error-list">
            <?php foreach ($errors as $e): ?>
              <li><?= esc($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="auth-info">
        <div class="auth-info__row">
          <span class="auth-info__icon">⚠</span>
          <div>
            <div class="auth-info__title">Mengapa kami meminta data ini?</div>
            <p class="auth-info__text">
              Data digunakan untuk kebutuhan identifikasi pelapor dan tindak lanjut laporan gangguan layanan e-Government.
            </p>
          </div>
        </div>
      </div>

      <form action="<?= site_url('register') ?>" method="post" class="auth-form">
        <?= csrf_field() ?>

        <div class="auth-grid">

          <div class="auth-field">
            <label class="auth-label" for="nama">Nama Lengkap*</label>
            <input id="nama" name="nama" value="<?= old('nama') ?>" required class="auth-input"
              placeholder="Nama Lengkap" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="no_hp">No. HP Aktif*</label>
            <input id="no_hp" name="no_hp" value="<?= old('no_hp') ?>" required class="auth-input"
              placeholder="08xxxxxxxxxx" />
          </div>

          <div class="auth-field auth-field--wide">
            <label class="auth-label" for="email">Email*</label>
            <input id="email" name="email" type="email" value="<?= old('email') ?>" class="auth-input"
              placeholder="email@contoh.com" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="password">Password*</label>
            <input id="password" name="password" type="password" required class="auth-input" placeholder="Password" />
          </div>

          <div class="auth-field">
            <label class="auth-label" for="password_confirm">Konfirmasi Password*</label>
            <input id="password_confirm" name="password_confirm" type="password" required class="auth-input"
              placeholder="Ulangi Password" />
          </div>

        </div>

        <div class="auth-actions">
          <button type="submit" class="btn btn--primary auth-submit">
            DAFTAR
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="auth-back">
    <a href="<?= site_url() ?>" class="btn auth-back__btn">
      KEMBALI KE HALAMAN DEPAN
    </a>
  </div>
</section>

<?= $this->endSection() ?>
