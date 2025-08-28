function showForm() {
    document.getElementById('form-container').style.display = 'block';
    document.getElementById('event-form').reset();
    document.getElementById('event-form').action = 'api/create.php';
}

function hideForm() {
    document.getElementById('form-container').style.display = 'none';
}

function editRecord(id) {
    fetch(`api/read.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('form-container').style.display = 'block';
            const form = document.getElementById('event-form');
            form.action = 'api/update.php';
            form.innerHTML += `<input type="hidden" name="id" value="${data.id}">`;
            form.querySelector('[name="autor"]').value = data.autor;
            form.querySelector('[name="temat"]').value = data.temat;
            form.querySelector('[name="data"]').value = data.data;
            form.querySelector('[name="godzina"]').value = data.godzina;
            form.querySelector('[name="tresc"]').value = data.tresc;
            form.querySelector('[name="typ"]').value = data.typ;
            form.querySelector('[name="status"]').value = data.status;
            form.querySelector('[name="kontakt1"]').value = data.kontakt1 || '';
            form.querySelector('[name="kontakt2"]').value = data.kontakt2 || '';
            form.querySelector('[name="kontakt3"]').value = data.kontakt3 || '';
            form.querySelector('[name="priorytet"]').value = data.priorytet;
            form.querySelector('[name="termin"]').value = data.termin || '';
            form.querySelector('[name="uwaga"]').value = data.uwaga || '';
            form.querySelector('[name="notatka"]').value = data.notatka || '';
        });
}

function deleteRecord(id) {
    if (confirm('Czy na pewno chcesz usunąć ten rekord?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'api/delete.php';
        form.innerHTML = `<input type="hidden" name="id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}
