<?php
require_once "check-login.php";

$pageTitle = "Land toevoegen";
include "nav.html";
?>

<h2>Land toevoegen</h2>

<?php if (isset($_SESSION["foutmelding"])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_SESSION["foutmelding"]); unset($_SESSION["foutmelding"]); ?></p>
<?php endif; ?>

<form action="cou-crud-adding.php" method="post">
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" required
           value="<?php echo htmlspecialchars($_SESSION["ingevoerdeNaam"] ?? ""); ?>">
    <br><br>
    <label for="code">Code:</label>
    <input type="text" id="code" name="code" required
           value="<?php echo htmlspecialchars($_SESSION["ingevoerdeCode"] ?? ""); ?>">
    <?php unset($_SESSION["ingevoerdeNaam"], $_SESSION["ingevoerdeCode"]); ?>
    <br><br>
    <a href="cou-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Sla op</button>
</form>

<?php require "footer.php"; ?>
