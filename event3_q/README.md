event3/
├── index.php
├── db.php
├── api/
│   └── events.php
├── js/
│   └── app.js
├── css/
│   └── style.css
└── README.md


# Event3 – Prosty CRUD z JS + PHP (bez frameworków)

Minimalistyczny system do zarządzania wydarzeniami, działający na słabym hostingu współdzielonym.

## 🔧 Technologie
- PHP 7.4+
- MySQL
- JavaScript (vanilla)
- HTML/CSS

## 📦 Funkcje
- Dodawanie wydarzeń (bez przeładowania)
- Wyświetlanie listy (dynamicznie przez JS)
- Usuwanie z potwierdzeniem
- Komunikaty sukcesu/błędu

## ⚙️ Instalacja

1. Umieść pliki na serwerze z PHP.
2. Utwórz bazę danych `event_db` i tabelę:

```sql
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL
);


📁 Struktura
event3/
├── index.php
├── db.php
├── api/events.php
├── js/app.js
├── css/style.css
└── README.md

🛡️ Bezpieczeństwo
Prepared statements
Walidacja wejścia
JSON + CORS (podstawowy)


🚀 Rozwój
Dodaj edycję (PUT)
Sortowanie po dacie
Filtrowanie
