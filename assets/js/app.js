function tambahBaris() {
    const tbody = document.getElementById("studentRows");
    const tr = document.createElement("tr");

    tr.innerHTML = `
        <td class="row-number"></td>
        <td><input name="nama[]" aria-label="Nama mahasiswa" placeholder="Nama" required></td>
        <td><input name="nim[]" aria-label="NIM mahasiswa" placeholder="NIM" required></td>
        <td><input name="tugas[]" aria-label="Nilai tugas" type="number" min="0" max="100" step="any" placeholder="0–100" required></td>
        <td><input name="uts[]" aria-label="Nilai UTS" type="number" min="0" max="100" step="any" placeholder="0–100" required></td>
        <td><input name="uas[]" aria-label="Nilai UAS" type="number" min="0" max="100" step="any" placeholder="0–100" required></td>
        <td><button type="button" class="remove" onclick="hapusBaris(this)" aria-label="Hapus baris">Hapus</button></td>
    `;

    tbody.appendChild(tr);
    perbaruiNomor();
}

function hapusBaris(button) {
    const tbody = document.getElementById("studentRows");

    if (tbody.rows.length > 1) {
        button.closest("tr").remove();
        perbaruiNomor();
    } else {
        tbody.querySelectorAll("input").forEach(input => {
            input.value = "";
        });
    }
}

function perbaruiNomor() {
    document.querySelectorAll("#studentRows .row-number").forEach((cell, index) => {
        cell.textContent = index + 1;
    });
}

function resetBaris() {
    const tbody = document.getElementById("studentRows");

    while (tbody.rows.length > 1) {
        tbody.deleteRow(1);
    }

    tbody.querySelectorAll("input").forEach(input => {
        input.value = "";
    });

    perbaruiNomor();
}
