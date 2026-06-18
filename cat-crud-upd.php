<?php
require_once "check-login.php";
require_once "database.php";

$ID = (int)($_GET["ID"] ?? 0);

$stmt = $conn->prepare("SELECT ID, name FROM category WHERE ID = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Categorie niet gevonden.");
}
$categorie = $result->fetch_assoc();

$pageTitle = "Categorie wijzigen";
require "header.php";
?>

<h2>Categorie wijzigen</h2>

<?php if (isset($_SESSION["foutmelding"])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_SESSION["foutmelding"]); unset($_SESSION["foutmelding"]); ?></p>
<?php endif; ?>

<form action="cat-crud-update.php" method="post">
    <input type="hidden" name="ID" value="<?php echo (int)$categorie["ID"]; ?>">
    <p>ID: <?php echo (int)$categorie["ID"]; ?></p>
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" required
           value="<?php echo htmlspecialchars($_SESSION["ingevoerdeNaam"] ?? $categorie["name"]); unset($_SESSION["ingevoerdeNaam"]); ?>">
    <br><br>
    <a href="cat-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Opslaan</button>
</form>

<?php require "footer.php"; ?>
