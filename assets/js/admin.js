document.addEventListener("DOMContentLoaded", function () {
  const search = document.getElementById("searchPendaftaran");
  const tbody = document.querySelector("#tablePendaftaran tbody");

  if (search && tbody) {
    search.addEventListener("input", function () {
      const keyword = this.value.toLowerCase();

      tbody.querySelectorAll("tr").forEach(function (row) {
        const text = row.textContent.toLowerCase();

        row.style.display = text.includes(keyword) ? "" : "none";
      });
    });
  }
});

function toggleRejectForm(id) {
  const box = document.getElementById("rejectBox_" + id);
  const btn = document.getElementById("btnReject_" + id);

  box.classList.toggle("active");

  if (box.classList.contains("active")) {
    btn.style.display = "none";
  } else {
    btn.style.display = "inline-block";
  }
}
