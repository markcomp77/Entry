# Pliki:


event3-sqlite/
├── index.php
├── db.php                 ← teraz z SQLite
├── data/events.db         ← baza danych (utworzy się automatycznie)
├── api/events.php         ← bez zmian (używa db.php)
├── js/app.js
├── css/style.css
└── README.md


# Event3 – CRUD z SQLite (wersja rozwojowa)

Minimalistyczna aplikacja do zarządzania wydarzeniami z użyciem **SQLite** – idealna do prototypowania, nauki i uruchomienia bez serwera bazodanowego.

## 🔧 Technologie
- PHP 7.4+ (z `pdo_sqlite`)
- SQLite (plik `data/events.db`)
- JavaScript (vanilla)
- HTML/CSS

## 📦 Funkcje
- Dodawanie wydarzeń (bez przeładowania)
- Dynamiczne ładowanie listy
- Usuwanie z potwierdzeniem
- Komunikaty
- Zero konfiguracji bazy

## ⚙️ Instalacja

1. Upewnij się, że masz włączony `pdo_sqlite` w PHP.
2. Skopiuj wszystkie pliki.
3. Uruchom serwer:
   ```bash
   php -S localhost:8000
