<?= $this->extend('user/layouts/main') ?>
<?= $this->section('content') ?>

<section class="hero" style="background-image: url('<?= base_url('assets/img/hero.png') ?>');">
  <div class="hero__overlay"></div>

  <div class="container hero__content">

    <h1 class="hero__title">
      Layanan Laporan Gangguan<br>Produk e-Government Diskominfo
    </h1>

    <div class="hero__spacer"></div>

    <div class="report-wrap">
      <div class="report">
        <div class="card">

          <div class="card__header">
            <h2 class="card__header-title">SAMPAIKAN LAPORAN GANGGUAN ANDA</h2>
          </div>

          <form action="<?= site_url('lapor') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="card__body">

              <!-- KATEGORI -->
              <select name="kategori_id" id="kategori_id" class="form-control" required>
                <option value="" disabled selected>Pilih Kategori *</option>
                <?php foreach (($kategoriList ?? []) as $k): ?>
                  <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
                <?php endforeach; ?>
              </select>

              <!-- SUB KATEGORI -->
              <select name="sub_kategori_id" id="sub_kategori_id" class="form-control" required disabled>
                <option value="" disabled selected>Pilih Sub Kategori *</option>
              </select>

              <!-- DESKRIPSI -->
              <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan gangguan yang terjadi *"
                required><?= old('deskripsi') ?></textarea>

              <!-- TANGGAL -->
              <input name="tanggal_kejadian" class="form-control" placeholder="Pilih Tanggal Kejadian *" type="text"
                onfocus="this.type='date'" onblur="if(!this.value)this.type='text'" required
                value="<?= old('tanggal_kejadian') ?>">

              <!-- LOKASI -->
              <input name="lokasi" class="form-control" placeholder="Lokasi *" required value="<?= old('lokasi') ?>">

              <input name="alamat_lengkap" class="form-control" placeholder="Alamat lengkap (opsional)"
                value="<?= old('alamat_lengkap') ?>">

              <!-- HIDDEN LAT LNG -->
              <input type="hidden" name="latitude" id="latitude" value="<?= old('latitude') ?>">
              <input type="hidden" name="longitude" id="longitude" value="<?= old('longitude') ?>">

              <div class="form-control" style="background:#f3f4f6;">
                <strong>Koordinat:</strong>
                <span id="coordText">Belum dipilih</span>
              </div>

              <!-- MAP -->
              <div class="form-control" style="padding:0; overflow:hidden;">
                <div id="map" style="height:320px;"></div>
              </div>

              <div style="display:flex; gap:.5rem;">
                <button type="button" class="btn btn--primary btn--xs" onclick="useMyLocation()">
                  Gunakan Lokasi Saya
                </button>
                <button type="button" class="btn btn--primary btn--xs" onclick="clearLocation()"
                  style="background:#6b7280;">
                  Hapus Lokasi
                </button>
              </div>

              <div class="auth-grid" style="margin-top:.5rem;">
                <div class="auth-field">
                  <label class="auth-label" for="kelurahan">Kelurahan/Desa</label>
                  <input id="kelurahan" name="kelurahan" class="auth-input" value="<?= old('kelurahan') ?>" readonly>
                </div>

                <div class="auth-field">
                  <label class="auth-label" for="kecamatan">Kecamatan</label>
                  <input id="kecamatan" name="kecamatan" class="auth-input" value="<?= old('kecamatan') ?>" readonly>
                </div>
              </div>


              <!-- UPLOAD -->
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
                  <button type="submit" class="btn btn--primary btn--xs">
                    KIRIM LAPORAN
                  </button>
                <?php else: ?>
                  <button type="button" class="btn btn--primary btn--xs" onclick="openLoginModal()">
                    MASUK UNTUK MELAPOR
                  </button>
                <?php endif; ?>
              </div>

            </div>
          </form>

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

<!-- STATISTIK -->
<section class="stats">
  <div class="container">
    <h2 class="stats__title">Statistik Laporan</h2>

    <div class="stats__row">
      <div class="stat-card">
        <div class="stat-card__num"><?= $stat['laporan_diterima'] ?? 0 ?></div>
        <div class="stat-card__label">Diterima</div>
      </div>
      <div class="stat-card">
        <div class="stat-card__num"><?= $stat['dalam_proses'] ?? 0 ?></div>
        <div class="stat-card__label">Diproses</div>
      </div>
      <div class="stat-card">
        <div class="stat-card__num"><?= $stat['selesai'] ?? 0 ?></div>
        <div class="stat-card__label">Selesai</div>
      </div>
    </div>
  </div>
</section>

<!-- ================= BERITA TERBARU ================= -->
<section class="section">
  <div class="container">
    <h2 class="section__title" style="text-align:center;">Berita Terbaru</h2>

    <div style="display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:18px; margin-top:18px;">
      <?php foreach (($beritaTerbaru ?? []) as $b): ?>
        <?php
          // penting: samakan cara bangun URL gambar
          // kalau DB menyimpan path "uploads/berita/xxx.jpg" -> pakai base_url($b['gambar'])
          // kalau DB menyimpan nama file "xxx.jpg" -> pakai base_url('uploads/berita/'.$b['gambar'])

          $g = trim($b['gambar'] ?? '');
          $img = '';
          if ($g !== '') {
            $img = (strpos($g, 'uploads/') !== false)
              ? base_url($g)
              : base_url('uploads/berita/'.$g);
          }
        ?>

        <a href="<?= site_url('berita/'.$b['slug']); ?>" style="text-decoration:none; color:inherit;">
          <div style="border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; background:#fff;">
            <div style="height:160px; background:#f3f4f6;">
              <?php if ($img !== ''): ?>
                <img src="<?= $img; ?>" alt="<?= esc($b['judul']); ?>"
                  style="width:100%; height:100%; object-fit:cover; display:block;">
              <?php endif; ?>
            </div>

            <div style="padding:12px 12px 14px;">
              <div style="font-weight:900; line-height:1.3;">
                <?= esc($b['judul']); ?>
              </div>

              <?php if (!empty($b['ringkas'])): ?>
                <div style="margin-top:6px; color:#6b7280; font-size:13px; line-height:1.4;">
                  <?= esc(mb_strimwidth($b['ringkas'], 0, 90, '...')); ?>
                </div>
              <?php endif; ?>

              <div style="margin-top:10px; color:#6b7280; font-size:12px;">
                <?= esc($b['tanggal_publish'] ?: $b['created_at']); ?>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:18px;">
      <a href="<?= site_url('berita'); ?>" class="btn btn--primary btn--xs" style="text-decoration:none;">
        Lihat selengkapnya →
      </a>
    </div>

  </div>
</section>

<!-- responsive cepat -->
<style>
  @media (max-width: 900px){
    .container > div[style*="grid-template-columns:repeat(3"]{ grid-template-columns:repeat(2, minmax(0,1fr)) !important; }
  }
  @media (max-width: 520px){
    .container > div[style*="grid-template-columns:repeat(3"]{ grid-template-columns:repeat(1, minmax(0,1fr)) !important; }
  }
</style>

<!-- LEAFLET -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  /* ===========================
     Upload Lampiran
  =========================== */
  function toggleUploadBox() {
    document.getElementById('uploadBox').classList.toggle('hidden');
  }

  function handleLampiran(input) {
    const maxSize = 2 * 1024 * 1024;
    const list = document.getElementById('fileList');
    list.innerHTML = '';

    for (const file of input.files) {
      if (file.size > maxSize) {
        alert('Ukuran file maksimal 2MB');
        input.value = '';
        return;
      }

      list.innerHTML += `<div class="file-row"><span>${file.name}</span></div>`;
    }
  }

  /* ===========================
     Sub Kategori AJAX
  =========================== */
  document.getElementById('kategori_id').addEventListener('change', async function () {
    const sub = document.getElementById('sub_kategori_id');
    sub.innerHTML = '<option>Memuat...</option>';
    sub.disabled = true;

    const res = await fetch("<?= site_url('subkategori') ?>?kategori_id=" + this.value);
    const data = await res.json();

    sub.innerHTML = '<option value="" disabled selected>Pilih Sub Kategori *</option>';
    data.forEach(row => {
      const opt = document.createElement('option');
      opt.value = row.id;
      opt.textContent = row.nama_subkategori;
      sub.appendChild(opt);
    });

    sub.disabled = false;
  });

  /* ===========================
     MAP
  =========================== */
  const DEFAULT_LAT = 2.9618;
  const DEFAULT_LNG = 99.0620;

  const latInput = document.getElementById('latitude');
  const lngInput = document.getElementById('longitude');
  const coordText = document.getElementById('coordText');

  const map = L.map('map').setView([DEFAULT_LAT, DEFAULT_LNG], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  let marker = null;

  function setMarker(lat, lng) {
    latInput.value = lat.toFixed(8);
    lngInput.value = lng.toFixed(8);
    coordText.textContent = latInput.value + ', ' + lngInput.value;

    if (marker) {
      marker.setLatLng([lat, lng]);
    } else {
      marker = L.marker([lat, lng], { draggable: true }).addTo(map);
      marker.on('dragend', function () {
        const pos = marker.getLatLng();
        setMarker(pos.lat, pos.lng);
      });
    }
    reverseGeocode(lat, lng);
  }

  map.on('click', e => setMarker(e.latlng.lat, e.latlng.lng));

  function useMyLocation() {
    navigator.geolocation.getCurrentPosition(pos => {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      map.setView([lat, lng], 16);
      setMarker(lat, lng);
    });
  }

  function clearLocation() {
    latInput.value = '';
    lngInput.value = '';
    coordText.textContent = 'Belum dipilih';
    if (marker) {
      map.removeLayer(marker);
      marker = null;
    }
  }
  async function reverseGeocode(lat, lng) {
  try {
    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}`;
    const res = await fetch(url, {
      headers: {
        "Accept": "application/json"
      }
    });
    const data = await res.json();

    const addr = data.address || {};

    // Kelurahan/Desa (kadang disebut village/suburb/neighbourhood)
    const kelurahan =
      addr.village || addr.suburb || addr.neighbourhood || addr.hamlet || addr.quarter || '';

    // Kecamatan (sering muncul sebagai city_district / district / county)
    const kecamatan =
      addr.city_district || addr.district || addr.county || addr.state_district || '';

    // Kota/Kabupaten
    const kota =
      addr.city || addr.town || addr.municipality || addr.county || '';

    // Provinsi
    const provinsi = addr.state || '';

    // Isi input tampilan
    const kelEl = document.getElementById('kelurahan');
    const kecEl = document.getElementById('kecamatan');
    if (kelEl) kelEl.value = kelurahan;
    if (kecEl) kecEl.value = kecamatan;

    // Isi alamat_lengkap untuk disimpan ke DB (boleh kamu formatkan)
    const alamatEl = document.querySelector('input[name="alamat_lengkap"]');
    if (alamatEl) {
      const bagian = [
        kelurahan ? `Kel. ${kelurahan}` : null,
        kecamatan ? `Kec. ${kecamatan}` : null,
        kota ? kota : null,
        provinsi ? provinsi : null
      ].filter(Boolean);

      // kalau ada display_name dari nominatim, itu lebih lengkap:
      // alamatEl.value = data.display_name || bagian.join(', ');
      alamatEl.value = bagian.join(', ');
    }
  } catch (e) {
    // kalau gagal, biarkan user isi manual
    console.warn('Reverse geocode gagal:', e);
  }
}

</script>



<?= $this->endSection() ?>