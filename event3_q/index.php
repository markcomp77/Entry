<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Event3 – Zarządzanie wydarzeniami</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Wydarzenia</h1>
        
        <div id="message"></div>

        <form id="add-event-form">
            <input type="text" name="title" placeholder="Tytuł wydarzenia" required>
            <input type="date" name="event_date" required>
            <button type="submit">Dodaj</button>
        </form>

        <ul id="events-list">
            <li><em>Ładowanie...</em></li>
        </ul>
    </div>

    <script src="js/app.js"></script>
</body>
</html>
