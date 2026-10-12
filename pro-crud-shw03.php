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
            SUM(o.amount) AS totaalAantal,
            (p.price * SUM(o.amount)) AS totaalWaarde
        FROM product p
        LEFT JOIN orderline o ON p.id = o.productid
        GROUP BY p.id, p.productname, p.price
        ORDER BY p.id";

$stmt = $pdo->query($sql);
$producten = $stmt->fetchAll();

$totaalAlles = 0;
?>

<h2>Overzicht producten + totale waarde aankopen</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Prijs</th>
        <th>Aantal gekocht</th>
        <th>Totale waarde</th>
    </tr>

<?php
foreach ($producten as $row) {

    $aantal = $row['totaalAantal'] ?? 0;
    $waarde = $row['totaalWaarde'] ?? 0;

    $totaalAlles += $waarde;

    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['productname']}</td>
            <td>€ {$row['price']}</td>
            <td>{$aantal}</td>
            <td>€ {$waarde}</td>
          </tr>";
}
?>
</table>

<h3>TOTALE WAARDE ALLE AANKOPEN: € <?= $totaalAlles ?></h3>