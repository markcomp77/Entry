<?php
// index.php
require_once __DIR__ . "/db_config.php";

// If not logged, show login form
$logged_in = isset($_SESSION["user_id"]);
$username = $_SESSION["username"] ?? null;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Event2</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="topbar">
    <h1>Event2</h1>
    <nav>
      <?php if ($logged_in): ?>
        <span>Zalogowany: <strong><?php echo htmlspecialchars($username); ?></strong></span>
        <a class="btn" href="logout.php">Wyloguj</a>
      <?php endif; ?>
    </nav>
  </header>

  <?php if (!$logged_in): ?>
    <main class="auth-container">
      <form class="card" method="post" action="login.php" autocomplete="on">
        <h2>Logowanie</h2>
        <label>Użytkownik
          <input type="text" name="username" required />
        </label>
        <label>Hasło
          <input type="password" name="password" required />
        </label>
        <button class="btn primary" type="submit">Zaloguj</button>
        <p class="muted">Domyślne konto po instalacji: <code>admin</code> / <code>admin</code> (zmień od razu).</p>
      </form>
    </main>
  <?php else: ?>
    <main class="container">
      <section class="filters card">
        <h2>Filtry i wyszukiwanie</h2>
        <div class="grid">
          <label>Szukaj (autor/temat/tresc)
            <input id="q" type="search" placeholder="fraza..." />
          </label>
          <label>Typ
            <input id="f_typ" type="text" placeholder="np. telefon, mail..." />
          </label>
          <label>Status
            <input id="f_status" type="text" placeholder="np. w trakcie, zamknięte..." />
          </label>
          <label>Priorytet
            <input id="f_priorytet" type="text" placeholder="np. wysoki/średni/niski" />
          </label>
          <label>Autor
            <input id="f_autor" type="text" placeholder="np. Marek" />
          </label>
          <label>Data od
            <input id="f_date_from" type="date" />
          </label>
          <label>Data do
            <input id="f_date_to" type="date" />
          </label>
        </div>
        <div class="actions">
          <button id="btnFilter" class="btn">Filtruj</button>
          <button id="btnReset" class="btn">Wyczyść</button>
          <button id="btnNew" class="btn primary">+ Nowy</button>
        </div>
      </section>

      <section class="list card">
        <div class="list-head">
          <h2>Wydarzenia</h2>
          <div class="pager">
            <button id="prevPage" class="btn">&laquo; Prev</button>
            <span id="pageInfo"></span>
            <button id="nextPage" class="btn">Next &raquo;</button>
          </div>
        </div>
        <table id="tbl" class="table">
          <thead>
            <tr>
              <th>Data</th>
              <th>Godz.</th>
              <th>Autor</th>
              <th>Temat</th>
              <th>Typ</th>
              <th>Status</th>
              <th>Priorytet</th>
              <th>Termin</th>
              <th>Akcje</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </section>
    </main>

    <!-- Modal -->
    <dialog id="dlg">
      <form id="frm" method="dialog">
        <h3 id="dlgTitle">Nowy rekord</h3>
        <input type="hidden" id="id" />
        <div class="grid two">
          <label>Autor<input id="autor" required /></label>
          <label>Temat<input id="temat" required /></label>
          <label>Data<input id="data" type="date" required /></label>
          <label>Godzina<input id="godzina" type="time" required /></label>
          <label>Typ<input id="typ" required /></label>
          <label>Status<input id="status" required /></label>
          <label>Kontakt 1<input id="kontakt1" /></label>
          <label>Kontakt 2<input id="kontakt2" /></label>
          <label>Kontakt 3<input id="kontakt3" /></label>
          <label>Priorytet<input id="priorytet" required /></label>
          <label>Termin<input id="termin" type="date" /></label>
          <label>Uwaga<input id="uwaga" /></label>
        </div>
        <label>Treść<textarea id="tresc" rows="4" required></textarea></label>
        <label>Notatka<textarea id="notatka" rows="3"></textarea></label>
        <menu>
          <button id="btnCancel" value="cancel">Anuluj</button>
          <button id="btnSave" class="primary" value="default">Zapisz</button>
        </menu>
      </form>
    </dialog>
    <script src="app.js"></script>
  <?php endif; ?>
</body>
</html>
