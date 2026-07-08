<?php
require 'dbconnect.php';

$id = $_GET['id'];

$sql = "DELETE FROM product WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->execute([':id' => $id]);

header("Location: pro-crud-get.php");
exit;