<?= $this->extend('user/layouts/main') ?>
<?= $this->section('content') ?>

<!-- ================= HERO + FORM ================= -->
<section class="hero" style="background-image: url('<?= base_url('assets/img/hero.png') ?>');">

  <div class="hero__overlay"></div>

  <div class="container hero__content">

    <h1 class="hero__title">
      Layanan Pengaduan Infrastruktur<br>Digital Diskominfo
    </h1>

    <div class="hero__spacer"></div>

    <!-- FORM CARD -->
    <div class="report-wrap">
      <div class="report">
        <div class="card">

          <div class="card__header">
            <h2 class="card__header-title">SAMPAIKAN LAPORAN ANDA</h2>
          </div>
          <form action="<?= site_url('lapor') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="card__body">
              <input name="judul" class="form-control" placeholder="Ketik Judul Laporan Anda *" required>
              <textarea name="isi" class="form-control" rows="5" placeholder="Ketik Isi Laporan *" required></textarea>


              <input name="tanggal_kejadian" class="form-control" placeholder="Pilih Tanggal Kejadian *" type="text"
                onfocus="this.type='date'" onblur="if(!this.value)this.type='text'" required>

              <input name="lokasi_kejadian" class="form-control" placeholder="Ketik Lokasi Kejadian *" required>

              <select name="instansi_id" class="form-control" required>
                <option disabled selected>Pilih Instansi Tujuan *</option>
                <?php foreach (($instansiList ?? []) as $i): ?>
                  <option value="<?= (int) $i['id'] ?>"><?= esc($i['nama']) ?></option>
                <?php endforeach; ?>
              </select>

              <select name="kategori_id" class="form-control" required>
                <option disabled selected>Pilih Kategori Laporan Anda *</option>
                <?php foreach (($kategoriList ?? []) as $k): ?>
                  <option value="<?= (int) $k['id'] ?>"><?= esc($k['nama']) ?></option>
                <?php endforeach; ?>
              </select>

              <!-- Upload -->
              <input id="lampiran" name="lampiran[]" type="file" accept="image/*" multiple class="hidden"
                onchange="handleLampiran(this)">


              <div id="uploadBox" class="upload-box hidden" onclick="document.getElementById('lampiran').click()">
                <p>UPLOAD LAMPIRAN (MAX 2MB)</p>
              </div>

              <div id="fileList" class="file-list"></div>

              <div class="form-actions">
                <div class="form-attachment">
                  <button type="button" onclick="toggleUploadBox()" class="link-btn">
                    <i class="bi bi-paperclip"></i>
                    Upload Lampiran
                  </button>
                </div>

                <?php if (session()->get('isLoggedIn')): ?>
                  <button type="submit" class="btn btn--primary btn--xs">LAPOR!</button>
                <?php else: ?>
                  <button type="button" class="btn btn--primary btn--xs" onclick="openLoginModal()">MASUK UNTUK
                    MELAPOR</button>
                <?php endif; ?>
          </form>

        </div>


      </div>
    </div>
  </div>
  </div>

  </div>
</section>

<!-- ================= ALUR PENGADUAN ================= -->
<section class="section">
  <div class="container">
    <h2 class="section__title">Alur Pengaduan Masyarakat</h2>
    <p class="section__desc">
      Alur penanganan dan pengaduan masyarakat yang sistematis untuk memastikan<br>
      setiap laporan di tindak lanjuti oleh instansi terkait.
    </p>

    <div class="steps">
      <div class="steps__line"></div>

      <div class="steps__grid">

        <div class="step">
          <div class="step__circle">
            <i class="bi bi-file-earmark-text step__icon"></i>
          </div>
          <h3 class="step__title">Tulis Laporan</h3>
          <p class="step__text">Tulis keluhan Anda secara jelas dan sertakan bukti pendukung jika ada.</p>
        </div>

        <div class="step">
          <div class="step__circle">
            <i class="bi bi-file-earmark-check step__icon"></i>
          </div>
          <h3 class="step__title">Verifikasi</h3>
          <p class="step__text">Tim kami akan memverifikasi data dan konten laporan anda dalam 1 x 24 jam.</p>
        </div>

        <div class="step">
          <div class="step__circle">
            <i class="bi bi-person-gear step__icon"></i>
          </div>
          <h3 class="step__title">Tindak Lanjut</h3>
          <p class="step__text">Instansi terkait akan menindaklanjuti laporan sesuai kewenangannya.</p>
        </div>

        <div class="step">
          <div class="step__circle">
            <i class="bi bi-check-circle-fill step__icon"></i>
          </div>
          <h3 class="step__title">Selesai</h3>
          <p class="step__text">Dapatkan notifikasi penyelesaian dan beri penilaian terhadap pelayanan.</p>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- ================= STATISTIK ================= -->
<section class="stats">
  <div class="container">
    <h2 class="stats__title">Statistik Pengaduan Masyarakat</h2>

    <div class="stats__row">
      <div class="stat-card">
        <div class="stat-card__num">0</div>
        <div class="stat-card__label">Diterima</div>
      </div>
      <div class="stat-card">
        <div class="stat-card__num">0</div>
        <div class="stat-card__label">Diproses</div>
      </div>
      <div class="stat-card">
        <div class="stat-card__num">0</div>
        <div class="stat-card__label">Selesai</div>
      </div>
    </div>
  </div>
</section>

<!-- ================= SCRIPT ================= -->
<script>
  let selectedFiles = [];

  function toggleUploadBox() {
    document.getElementById('uploadBox').classList.toggle('hidden');
  }

  function handleLampiran(input) {
    const maxSize = 2 * 1024 * 1024;

    Array.from(input.files).forEach(file => {
      if (file.size > maxSize) {
        alert('Ukuran file maksimal 2MB');
        return;
      }
      selectedFiles.push(file);
    });

    input.value = '';
    renderFileList();
  }

  function handleLampiran(input) {
    const maxSize = 2 * 1024 * 1024;

    // bersihkan list tampilan
    const list = document.getElementById('fileList');
    list.innerHTML = '';

    Array.from(input.files).forEach((file, i) => {
      if (file.size > maxSize) {
        alert('Ukuran file maksimal 2MB');
        input.value = ''; // reset karena ada yang kebesaran
        return;
      }

      list.innerHTML += `
        <div class="file-row">
          <span>${file.name}</span>
        </div>`;
    });

    // HAPUS BARIS INI:
    // input.value = '';
  }

  function removeFile(i) {
    selectedFiles.splice(i, 1);
    renderFileList();
  }
</script>

<?= $this->endSection() ?>