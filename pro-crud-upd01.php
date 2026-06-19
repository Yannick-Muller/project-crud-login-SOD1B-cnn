<?php
session_start();
require 'config.php';

// Alleen ingelogde beheerder
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: login.php");
    exit;
}
?>

<h2>Inactiveren product</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Prijs</th>
        <th>Actief</th>
        <th>Actie</th>
    </tr>

<?php
$sql = "SELECT id, productname, price, isactive FROM product";
$stmt = $pdo->query($sql);

foreach ($stmt as $row) {
    $status = ($row['isactive'] == "J") ? "Actief" : "Niet actief";

    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['productname']}</td>
            <td>{$row['price']}</td>
            <td>$status</td>
            <td>
                <form action='pro-crud-upd01a.php' method='post'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <button type='submit'>(de)Activeren</button>
                </form>
            </td>
          </tr>";
}
?>
</table>