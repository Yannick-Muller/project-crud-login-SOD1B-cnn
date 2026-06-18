<?php
require_once "check-login.php";
require_once "database.php";

$ID = (int)($_GET["ID"] ?? 0);

$stmt = $conn->prepare("SELECT idcountry, name, code FROM country WHERE idcountry = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Land niet gevonden.");
}
$land = $result->fetch_assoc();

$pageTitle = "Land wijzigen";
require "header.php";
?>

<h2>Land wijzigen</h2>

<?php if (isset($_SESSION["foutmelding"])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_SESSION["foutmelding"]); unset($_SESSION["foutmelding"]); ?></p>
<?php endif; ?>

<form action="cou-crud-update.php" method="post">
    <input type="hidden" name="ID" value="<?php echo (int)$land["idcountry"]; ?>">
    <p>ID: <?php echo (int)$land["idcountry"]; ?></p>
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" required
           value="<?php echo htmlspecialchars($_SESSION["ingevoerdeNaam"] ?? $land["name"]); ?>">
    <br><br>
    <label for="code">Code:</label>
    <input type="text" id="code" name="code" required
           value="<?php echo htmlspecialchars($_SESSION["ingevoerdeCode"] ?? $land["code"]); ?>">
    <?php unset($_SESSION["ingevoerdeNaam"], $_SESSION["ingevoerdeCode"]); ?>
    <br><br>
    <a href="cou-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Opslaan</button>
</form>

<?php require "footer.php"; ?>
