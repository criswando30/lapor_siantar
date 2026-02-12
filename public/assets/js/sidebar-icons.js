// assets/js/sidebar-icons.js
(function () {
  const menu = document.getElementById("adminSidebarMenu");
  if (!menu) return;

  // 1) Inject icon bootstrap dari data-icon
  const items = menu.querySelectorAll(".sidebar__item");
  items.forEach((a) => {
    const iconName = a.getAttribute("data-icon");
    const iconWrap = a.querySelector(".sidebar__icon");
    if (iconWrap && iconName) {
      iconWrap.innerHTML = `<i class="bi bi-${iconName}"></i>`;
    }
  });

  // 2) Auto active berdasarkan path (semirip url_is)
  // contoh: /index.php/admin/dashboard -> "admin/dashboard"
  const path = window.location.pathname.replace(/^\/+/, ""); // remove leading /
  const cleaned = path.replace(/^index\.php\/?/, ""); // remove index.php

  // reset dulu
  items.forEach((a) => a.classList.remove("is-active"));

  // cari kecocokan via data-active
  let activated = false;
  items.forEach((a) => {
    const key = a.getAttribute("data-active");
    if (!key) return;

    // aktif jika path mengandung key (biar subpage ikut aktif)
    if (cleaned.includes(key)) {
      a.classList.add("is-active");
      activated = true;
    }
  });

  // fallback: kalau gak ketemu, aktifkan item pertama
  if (!activated && items.length) items[0].classList.add("is-active");
})();
