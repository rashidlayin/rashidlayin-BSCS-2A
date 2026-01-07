<?php
include 'db.php';
$conn->query("DELETE FROM events WHERE event_id=".$_GET['id']);
header("Location: index.php");
?>
