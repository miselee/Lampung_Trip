document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchPendaftaran");
  const tableBody = document.querySelector("#tablePendaftaran tbody");

  if (searchInput && tableBody) {
    searchInput.addEventListener(
      "input",
      function () {
        const keyword = this.value.toLowerCase().trim();
        const rows = tableBody.querySelectorAll("tr[data-status]");

        rows.forEach(function (row) {
          const text = row.textContent.toLowerCase();
          row.style.display = !keyword || text.includes(keyword) ? "" : "none";
        });

        let visibleCount = 0;
        rows.forEach(function (row) {
          if (row.style.display !== "none") visibleCount++;
        });

        let emptyRow = tableBody.querySelector(".js-empty-row");
        if (visibleCount === 0) {
          if (!emptyRow) {
            emptyRow = document.createElement("tr");
            emptyRow.className = "js-empty-row";
            emptyRow.innerHTML =
              '<td colspan="8" style="text-align:center;padding:30px;color:#888;">' +
              '🔍 Tidak ada hasil untuk "' +
              this.value +
              '"</td>';
            tableBody.appendChild(emptyRow);
          }
        } else if (emptyRow) {
          emptyRow.remove();
        }
      }.bind(searchInput),
    );
  }

  const alerts = document.querySelectorAll(
    ".alert, .flash-success, .flash-error",
  );
  alerts.forEach(function (el) {
    setTimeout(function () {
      el.style.transition = "opacity 0.5s ease";
      el.style.opacity = "0";
      setTimeout(function () {
        el.remove();
      }, 500);
    }, 4000);
  });
});

/**
 * @param {number}
 */
function toggleTolak(id) {
  const form = document.getElementById("form-tolak-" + id);
  if (form) {
    const isHidden = form.style.display === "none" || form.style.display === "";
    form.style.display = isHidden ? "block" : "none";

    if (isHidden) {
      const textarea = form.querySelector("textarea");
      if (textarea) textarea.focus();
    }
  }
}
