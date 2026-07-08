<?php
session_start();
require 'dbconnect.php';


if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header("Location: login.php");
    exit;
}


$sql = "SELECT 
            p.id,
            p.productname,
            p.price,
            c.categoryname,
            s.company AS suppliername,
            p.isactive
        FROM product p
        LEFT JOIN category c ON p.categoryid = c.id
        LEFT JOIN supplier s ON p.supplierid = s.id
        ORDER BY p.id";

$stmt = $pdo->query($sql);
$producten = $stmt->fetchAll();
?>

<h2>Onderhoud producten</h2>

<a href="pro-crud-add.php">Product toevoegen</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Prijs</th>
        <th>Categorie</th>
        <th>Leverancier</th>
        <th>Actief?</th>
        <th>Wijzigen</th>
        <th>Verwijderen</th>
    </tr>

<?php
foreach ($producten as $row) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['productname']}</td>
            <td>€ {$row['price']}</td>
            <td>{$row['categoryname']}</td>
            <td>{$row['suppliername']}</td>
            <td>" . ($row['isactive'] ? 'Ja' : 'Nee') . "</td>
            <td><a href='pro-crud-upd.php?id={$row['id']}'>Wijzigen</a></td>
            <td><a href='pro-crud-del.php?id={$row['id']}'>Verwijderen</a></td>
          </tr>";
}
?>
</table>