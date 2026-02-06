<footer class="bg-light border-top pt-5">
  <div class="container py-4">

    <div class="row g-4 align-items-start">
      <!-- KIRI -->
      <div class="col-12 col-lg-5">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="LaporSiantar" class="mb-3" style="height:40px;">

        <p class="text-secondary small mb-4" style="max-width: 420px;">
          Layanan Aspirasi dan Pengaduan<br class="d-none d-md-block">
          Masyarakat Online Pematangsiantar
        </p>

        <div class="d-flex flex-column gap-2">
          <div class="d-flex align-items-start gap-2">
            <i class="bi bi-geo-alt-fill text-secondary mt-1"></i>
            <p class="mb-0 small text-secondary">
              Jl. Merdeka No.4, Proklamasi, Kec. Siantar Bar., Kota Pematang Siantar, Sumatera Utara 21145.
            </p>
          </div>

          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope-fill text-secondary"></i>
            <a class="small text-secondary text-decoration-none footer-link2"
              href="mailto:pengaduan@pematangsiantar.go.id">
              pengaduan@pematangsiantar.go.id
            </a>
          </div>

          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-telephone-fill text-secondary"></i>
            <a class="small text-secondary text-decoration-none footer-link2" href="tel:062221234">
              (0622) 21234
            </a>
          </div>
        </div>
      </div>

      <!-- TENGAH -->
      <div class="col-12 col-md-6 col-lg-3">
        <h6 class="footer-title mb-3">TAUTAN CEPAT</h6>

        <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
          <li>
            <a class="footer-link" href="<?= site_url() ?>">
              BERANDA
            </a>
          </li>

          <li>
            <a class="footer-link" href="<?= site_url('status') ?>">
              STATUS PENGADUAN
            </a>
          </li>

          <li>
            <a class="footer-link" href="<?= site_url('tentang') ?>">
              TENTANG
            </a>
          </li>

          <li>
            <a class="footer-link" href="https://pematangsiantar.go.id/" target="_blank" rel="noopener noreferrer">
              WEBSITE RESMI PEMATANGSIANTAR
            </a>
          </li>
        </ul>

      </div>

      <!-- KANAN -->
      <div class="col-12 col-md-6 col-lg-4">
        <h6 class="footer-title mb-3">SOSIAL MEDIA</h6>

        <div class="d-flex align-items-center gap-2 flex-wrap">
          <a class="social-btn" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a class="social-btn" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a class="social-btn" href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a class="social-btn" href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
          <a class="social-btn" href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>
    </div>

    <!-- GARIS + COPYRIGHT TENGAH -->
    <div class="pt-4 mt-4 border-top text-center">
      <div class="d-inline-flex align-items-center gap-2 small text-secondary">
        <span class="footer-dot">©</span>
        <span>2026. DISKOMINFO PEMATANGSIANTAR</span>
      </div>
    </div>
  </div>
</footer>

<style>
  /* Judul kolom + garis biru pendek (rapih) */
  .footer-title {
    font-weight: 800;
    letter-spacing: .05em;
    font-size: .85rem;
    text-transform: uppercase;
    display: inline-block;
    position: relative;
    padding-bottom: .4rem;
    margin-bottom: 1rem;
  }

  .footer-title::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 110px;
    height: 3px;
    background: #1d84e4;
    border-radius: 99px;
  }

  /* Link quick */
  .footer-link {
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
  }

  .footer-link:hover {
    color: #1d84e4;
    text-decoration: underline;
  }

  /* Link email/phone hover */
  .footer-link2:hover {
    color: #1d84e4 !important;
    text-decoration: underline !important;
  }

  /* Ikon sosial: ukuran sama, seimbang */
  .social-btn {
    width: 36px;
    height: 36px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #4b5563;
    color: #fff;
    text-decoration: none;
    transition: transform .12s ease, background .12s ease;
  }

  .social-btn:hover {
    background: #111827;
    transform: translateY(-1px);
  }

  .social-btn i {
    font-size: 16px;
    line-height: 1;
  }

  /* simbol © agar rapi */
  .footer-dot {
    width: 22px;
    height: 22px;
    border: 1px solid #ced4da;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
  }
</style>