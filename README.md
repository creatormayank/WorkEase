# WorkEase (Title)
lakshita added


display image

<?php
require 'db_connection.php';

$providerId = 1; // Example ProviderID
$stmt = $conn->prepare("SELECT ID1, ID2 FROM ServiceProvider WHERE ProviderID = ?");
$stmt->bind_param("i", $providerId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id1Path, $id2Path);
$stmt->fetch();

echo "<h3>ID Proofs</h3>";
echo "<img src='$id1Path' alt='ID1' style='max-width: 200px; display: block;'><br>";
echo "<img src='$id2Path' alt='ID2' style='max-width: 200px; display: block;'><br>";
?>