-- schema.sql (optional reference)
CREATE TABLE IF NOT EXISTS records (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    autor TEXT NOT NULL,
    temat TEXT NOT NULL,
    data DATE NOT NULL,
    godzina TIME NOT NULL,
    tresc TEXT NOT NULL,
    typ TEXT NOT NULL,
    status TEXT NOT NULL,
    kontakt1 TEXT,
    kontakt2 TEXT,
    kontakt3 TEXT,
    priorytet TEXT NOT NULL,
    termin DATE,
    uwaga TEXT,
    notatka TEXT
);

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
);
