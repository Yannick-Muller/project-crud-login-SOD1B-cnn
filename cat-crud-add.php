<?php
require_once "check-login.php";

$pageTitle = "Categorie toevoegen";
include "nav.html";
?>

<h2>Categorie toevoegen</h2>

<?php if (isset($_SESSION["foutmelding"])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_SESSION["foutmelding"]); unset($_SESSION["foutmelding"]); ?></p>
<?php endif; ?>

<form action="cat-crud-adding.php" method="post">
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" required
           value="<?php echo htmlspecialchars($_SESSION["ingevoerdeNaam"] ?? ""); unset($_SESSION["ingevoerdeNaam"]); ?>">
    <br><br>
    <a href="cat-crud-get.php"><button type="button">Breek af</button></a>
    <button type="submit">Sla op</button>
</form>

