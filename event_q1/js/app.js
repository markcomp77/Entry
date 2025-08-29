// js/app.js
const API_URL = 'api/events.php';

async function loadEvents() {
    try {
        const response = await fetch(API_URL);
        const data = await response.json();
        const list = document.getElementById('events-list');
        list.innerHTML = '';

        if (!data.success) {
            list.innerHTML = `<li class="error">Błąd: ${data.error}</li>`;
            return;
        }

        if (data.data.length === 0) {
            list.innerHTML = '<li><em>Brak wydarzeń</em></li>';
            return;
        }

        data.data.forEach(event => {
            const li = document.createElement('li');
            li.innerHTML = `
                ${event.title} (${event.event_date})
                <button onclick="deleteEvent(${event.id})" class="delete-btn">Usuń</button>
            `;
            list.appendChild(li);
        });
    } catch (err) {
        document.getElementById('events-list').innerHTML = 
            '<li class="error">Błąd połączenia z serwerem</li>';
    }
}

async function addEvent(event) {
    event.preventDefault();
    const form = event.target;
    const title = form.title.value.trim();
    const date = form.event_date.value;

    if (!title || !date) {
        alert('Wszystkie pola są wymagane');
        return;
    }

    const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, event_date: date })
    });

    const result = await response.json();

    if (result.success) {
        form.reset();
        loadEvents();
        showMessage('Wydarzenie dodane!', 'success');
    } else {
        showMessage('Błąd: ' + result.error, 'error');
    }
}

async function deleteEvent(id) {
    if (!confirm('Na pewno usunąć?')) return;

    const response = await fetch(`${API_URL}?id=${id}`, { method: 'DELETE' });
    const result = await response.json();

    if (result.success) {
        loadEvents();
        showMessage('Usunięto!', 'success');
    } else {
        showMessage('Błąd: ' + result.error, 'error');
    }
}

function showMessage(text, type) {
    const msg = document.getElementById('message');
    msg.textContent = text;
    msg.className = type;
    setTimeout(() => msg.className = '', 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    loadEvents();
    const form = document.getElementById('add-event-form');
    if (form) form.addEventListener('submit', addEvent);
});
