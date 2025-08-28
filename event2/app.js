// app.js
const api = {
  read: params => fetch("api/read.php?" + new URLSearchParams(params)).then(r=>r.json()),
  create: data => fetch("api/create.php",{method:"POST",body:JSON.stringify(data)}).then(r=>r.json()),
  update: data => fetch("api/update.php",{method:"POST",body:JSON.stringify(data)}).then(r=>r.json()),
  delete: id => fetch("api/delete.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  }).then(r=>r.json()),
};


let state = {
  page: 1, per_page: 10,
  q:"", typ:"", status:"", priorytet:"", autor:"", date_from:"", date_to:"",
};

const $ = sel => document.querySelector(sel);
const tbody = $("#tbl tbody");
const pageInfo = $("#pageInfo");

function readFilters() {
  state.q = $("#q").value.trim();
  state.typ = $("#f_typ").value.trim();
  state.status = $("#f_status").value.trim();
  state.priorytet = $("#f_priorytet").value.trim();
  state.autor = $("#f_autor").value.trim();
  state.date_from = $("#f_date_from").value;
  state.date_to = $("#f_date_to").value;
}

async function refresh() {
  readFilters();
  const data = await api.read({ ...state });
  tbody.innerHTML = "";
  for (const r of data.items) {
    const tr = document.createElement("tr");
    const termin = r.termin || "";
    tr.innerHTML = `
      <td>${r.data}</td>
      <td>${r.godzina}</td>
      <td>${escapeHtml(r.autor)}</td>
      <td><div><strong>${escapeHtml(r.temat)}</strong></div><div class="muted">${shorten(r.tresc, 160)}</div></td>
      <td>${escapeHtml(r.typ)}</td>
      <td>${escapeHtml(r.status)}</td>
      <td><span class="badge">${escapeHtml(r.priorytet)}</span></td>
      <td>${termin ?? ""}</td>
      <td>
        <button class="btn" data-edit="${r.id}">Edytuj</button>
        <button class="btn" data-del="${r.id}">Usuń</button>
      </td>
    `;
    tbody.appendChild(tr);
  }
  pageInfo.textContent = `Strona ${data.page} z ${data.pages} (rekordów: ${data.total})`;
  $("#prevPage").disabled = data.page <= 1;
  $("#nextPage").disabled = data.page >= data.pages;
}

function shorten(text, n) {
  if (!text) return "";
  return text.length > n ? text.slice(0,n-1)+"…" : text;
}
function escapeHtml(s) {
  return String(s??"").replace(/[&<>"']/g, m => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[m]));
}

// Pagination
$("#prevPage").addEventListener("click", ()=>{ state.page = Math.max(1, state.page-1); refresh(); });
$("#nextPage").addEventListener("click", ()=>{ state.page = state.page+1; refresh(); });

$("#btnFilter").addEventListener("click", ()=>{ state.page=1; refresh(); });
$("#btnReset").addEventListener("click", ()=>{
  ["#q","#f_typ","#f_status","#f_priorytet","#f_autor","#f_date_from","#f_date_to"].forEach(id => $(id).value = "");
  state.page=1; refresh();
});

// New record
const dlg = $("#dlg");
$("#btnNew").addEventListener("click", ()=>{
  $("#dlgTitle").textContent = "Nowy rekord";
  fillForm({});
  dlg.showModal();
});

// Table actions
tbody.addEventListener("click", async (ev)=>{
  const btn = ev.target.closest("button");
  if (!btn) return;
  if (btn.dataset.edit) {
    // fetch current row values from DOM (already present in table), but better read via API?
    // For simplicity, we cannot fetch a single-by-id endpoint; reuse current row content.
    const id = +btn.dataset.edit;
    // Quick way: request current page and find item
    const data = await api.read({ ...state });
    const row = data.items.find(x=>+x.id===id);
    if (!row) return;
    $("#dlgTitle").textContent = "Edytuj rekord";
    fillForm(row);
    dlg.showModal();
  } else if (btn.dataset.del) {
    const id = +btn.dataset.del;
    if (confirm("Usunąć rekord #" + id + "?")) {
      await api.delete(id);
      refresh();
    }
  }
});

// Form handlers
$("#btnCancel").addEventListener("click", ()=> dlg.close());
$("#btnSave").addEventListener("click", async (ev)=>{
  ev.preventDefault();
  const rec = collectForm();
  if (rec.id) {
    await api.update(rec);
  } else {
    await api.create(rec);
  }
  dlg.close();
  refresh();
});

function fillForm(r) {
  $("#id").value = r.id || "";
  $("#autor").value = r.autor || "";
  $("#temat").value = r.temat || "";
  $("#data").value = r.data || (new Date().toISOString().slice(0,10));
  $("#godzina").value = r.godzina || (new Date().toTimeString().slice(0,5));
  $("#tresc").value = r.tresc || "";
  $("#typ").value = r.typ || "";
  $("#status").value = r.status || "";
  $("#kontakt1").value = r.kontakt1 || "";
  $("#kontakt2").value = r.kontakt2 || "";
  $("#kontakt3").value = r.kontakt3 || "";
  $("#priorytet").value = r.priorytet || "średni";
  $("#termin").value = r.termin || "";
  $("#uwaga").value = r.uwaga || "";
  $("#notatka").value = r.notatka || "";
}

function collectForm() {
  return {
    id: $("#id").value ? +$("#id").value : undefined,
    autor: $("#autor").value.trim(),
    temat: $("#temat").value.trim(),
    data: $("#data").value,
    godzina: $("#godzina").value,
    tresc: $("#tresc").value.trim(),
    typ: $("#typ").value.trim(),
    status: $("#status").value.trim(),
    kontakt1: $("#kontakt1").value.trim() || null,
    kontakt2: $("#kontakt2").value.trim() || null,
    kontakt3: $("#kontakt3").value.trim() || null,
    priorytet: $("#priorytet").value.trim(),
    termin: $("#termin").value || null,
    uwaga: $("#uwaga").value.trim() || null,
    notatka: $("#notatka").value.trim() || null,
  };
}

// Initial load
refresh();
