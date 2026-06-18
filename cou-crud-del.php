<?php
require_once "check-login.php";
require_once "database.php";

$ID = (int)($_GET["ID"] ?? 0);

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS aantal
     FROM product p
     JOIN supplier s ON p.supplierid = s.ID
     WHERE s.countryid = ?"
);
$stmt->bind_param("i", $ID);
$stmt->execute();
$check = $stmt->get_result()->fetch_assoc();

if ($check["aantal"] > 0) {
    $_SESSION["foutmelding"] = "Dit land kan niet verwijderd worden, want het wordt via een leverancier nog gebruikt door één of meer producten.";
    header("Location: cou-crud-get.php");
    exit;
}
$stmt->close();

$stmt = $conn->prepare("SELECT idcountry, name, code FROM country WHERE idcountry = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Land niet gevonden.");
}
$land = $result->fetch_assoc();

$pageTitle = "Land verwijderen";
require "header.php";
?>

<h2>Land verwijderen</h2>

<p>Weet je zeker dat je dit land wilt verwijderen?</p>
<table border="1" cellpadding="5">
    <tr><th>ID</th><td><?php echo (int)$land["idcountry"]; ?></td></tr>
    <tr><th>Naam</th><td><?php echo htmlspecialchars($land["name"]); ?></td></tr>
    <tr><th>Code</th><td><?php echo htmlspecialchars($land["code"]); ?></td></tr>
</table>

<form action="cou-crud-delete.php" method="post">
    <input type="hidden" name="ID" value="<?php echo (int)$land["idcountry"]; ?>">
    <br>
    <a href="cou-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Verwijder</button>
</form>

<?php require "footer.php"; ?>
