<?php
require 'dbconnect.php';
session_start();

// Alleen voor ingelogde beheerders
if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'admin') {
    header("Location: beh-log.php");
    exit;
}

// Producten ophalen voor de keuzelijst
$stmt = $db->prepare("SELECT id, productname, price FROM product ORDER BY productname");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Openstaande bestellingen ophalen voor de keuzelijst
$stmt = $db->prepare("SELECT purchase.id, purchase.purchasedate, client.last_name
                       FROM purchase
                       JOIN client ON client.id = purchase.clientid
                       ORDER BY purchase.id DESC");
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelregel toevoegen</title>
</head>
<body>

<h1>Bestelregel toevoegen</h1>

<form method="post" action="pro-ord-adding.php">
    <label>Bestelling:</label><br>
    <select name="purchaseid" required>
        <?php foreach ($purchases as $p): ?>
            <option value="<?= $p['id'] ?>">
                #<?= $p['id'] ?> - <?= htmlspecialchars($p['last_name']) ?> (<?= $p['purchasedate'] ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Product:</label><br>
    <select name="productid" id="productid" required>
        <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
                <?= htmlspecialchars($p['productname']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Prijs:</label><br>
    <input type="number" step="0.01" name="price" id="price" required><br><br>

    <label>Aantal:</label><br>
    <input type="number" name="quantity" min="1" value="1" required><br><br>

    <button type="submit">Opslaan</button>
</form>

<a href="pro-ord-get.php">
    <button>Annuleren</button>
</a>

<script>
    // Prijs automatisch invullen op basis van gekozen product (blijft aanpasbaar)
    const productSelect = document.getElementById('productid');
    const priceInput = document.getElementById('price');

    function fillPrice() {
        const option = productSelect.options[productSelect.selectedIndex];
        priceInput.value = option.getAttribute('data-price');
    }

    productSelect.addEventListener('change', fillPrice);
    fillPrice();
</script>

</body>
</html>
