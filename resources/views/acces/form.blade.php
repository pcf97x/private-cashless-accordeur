<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accès – Accordeur</title>
</head>
<body>

<h1>Demande d’accès</h1>

<form method="POST" action="/acces">
    @csrf

    <label>Prénom</label><br>
    <input type="text" name="firstname" required><br><br>

    <label>Nom</label><br>
    <input type="text" name="lastname" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Téléphone</label><br>
    <input type="text" name="phone"><br><br>

    <label>Motif</label><br>
    <select name="purpose">
        <option value="resident">Résident</option>
        <option value="visiteur">Visiteur</option>
        <option value="autre">Autre</option>
    </select><br><br>

    <button type="submit">Valider</button>
</form>

</body>
</html>
