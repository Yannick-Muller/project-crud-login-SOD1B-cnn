<?php
require_once "check-login.php";
require_once "database.php";

$ID = (int)($_GET["ID"] ?? 0);

$stmt = $conn->prepare("SELECT COUNT(*) AS aantal FROM product WHERE categoryid = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$check = $stmt->get_result()->fetch_assoc();

if ($check["aantal"] > 0) {
    $_SESSION["foutmelding"] = "Deze categorie kan niet verwijderd worden, want hij wordt nog gebruikt door één of meer producten.";
    header("Location: cat-crud-get.php");
    exit;
}
$stmt->close();

$stmt = $conn->prepare("SELECT ID, name FROM category WHERE ID = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Categorie niet gevonden.");
}
$categorie = $result->fetch_assoc();

$pageTitle = "Categorie verwijderen";
require "header.php";
?>

<h2>Categorie verwijderen</h2>

<p>Weet je zeker dat je deze categorie wilt verwijderen?</p>
<table border="1" cellpadding="5">
    <tr><th>ID</th><td><?php echo (int)$categorie["ID"]; ?></td></tr>
    <tr><th>Naam</th><td><?php echo htmlspecialchars($categorie["name"]); ?></td></tr>
</table>

<form action="cat-crud-delete.php" method="post">
    <input type="hidden" name="ID" value="<?php echo (int)$categorie["ID"]; ?>">
    <br>
    <a href="cat-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Verwijder</button>
</form>

<?php require "footer.php"; ?>
