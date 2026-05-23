if (typeof BASE_URL === "undefined") {
  window.BASE_URL = "/Lampung_Trip/";
}

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");

  if (searchInput) {
    searchInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();

        const q = this.value.trim();

        if (q) {
          window.location.href =
            BASE_URL + "user/destinasi?q=" + encodeURIComponent(q);
        }
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const jumlahInput = document.querySelector('input[name="jumlah_orang"]');

  const estimasi = document.getElementById("estimasiTotal");

  if (jumlahInput && estimasi) {
    jumlahInput.addEventListener("input", function () {
      const harga = window.TRIP_HARGA || 0;

      const total = harga * (parseInt(this.value) || 1);

      estimasi.innerText = "Rp " + total.toLocaleString("id-ID");
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const cards = document.getElementById("cards");
  const prev = document.getElementById("prevBtn");
  const next = document.getElementById("nextBtn");

  if (cards && prev && next) {
    next.addEventListener("click", function () {
      cards.scrollBy({
        left: 300,
        behavior: "smooth",
      });
    });

    prev.addEventListener("click", function () {
      cards.scrollBy({
        left: -300,
        behavior: "smooth",
      });
    });
  }
});

function bukaOverlay() {
  const overlay = document.getElementById("daftar-overlay");

  if (overlay) {
    overlay.classList.add("show");
    document.body.style.overflow = "hidden";

    hitungTotal();
  }
}

function tutupOverlay() {
  const overlay = document.getElementById("daftar-overlay");

  if (overlay) {
    overlay.classList.remove("show");
    document.body.style.overflow = "";
  }
}

function hitungTotal() {
  const jumlahInput = document.getElementById("jumlahOrang");

  if (!jumlahInput) return;

  const jumlah = parseInt(jumlahInput.value) || 1;

  const harga = typeof TRIP_HARGA !== "undefined" ? parseInt(TRIP_HARGA) : 0;

  const total = harga * jumlah;

  const lblJumlah = document.getElementById("lblJumlah");

  const lblTotal = document.getElementById("lblTotal");

  if (lblJumlah) {
    lblJumlah.textContent = jumlah + " Orang";
  }

  if (lblTotal) {
    lblTotal.textContent = "Rp " + total.toLocaleString("id-ID");
  }
}

function changeImg(el) {
  const img =
    document.getElementById("heroImg") || document.getElementById("mainImage");

  if (img) {
    if (img.tagName === "IMG") {
      img.src = el.src;
    } else {
      img.style.backgroundImage = "url('" + el.src + "')";
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // tombol daftar
  const btnDaftar = document.getElementById("btnDaftar");

  if (btnDaftar) {
    btnDaftar.addEventListener("click", bukaOverlay);
  }

  const btnClose = document.getElementById("btnTutupOverlay");

  if (btnClose) {
    btnClose.addEventListener("click", tutupOverlay);
  }

  const overlay = document.getElementById("daftar-overlay");

  if (overlay) {
    overlay.addEventListener("click", function (e) {
      if (e.target === overlay) {
        tutupOverlay();
      }
    });
  }

  const jumlahInput = document.getElementById("jumlahOrang");

  if (jumlahInput) {
    jumlahInput.addEventListener("input", hitungTotal);
  }
});
