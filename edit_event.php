<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$event_id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM events WHERE event_id = $event_id");
if ($result->num_rows == 0) {
    die("Event not found!");
}
$event = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $location   = $conn->real_escape_string($_POST['location']);

    $update = $conn->query("UPDATE events SET event_name='$event_name', event_date='$event_date', location='$location' WHERE event_id=$event_id");

    if ($update) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Failed to update event: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <style>
        body { margin:0; font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background:url('img/bg.jpg') no-repeat center center/cover; min-height:100vh; color:#2c3e50; }
        .overlay { background: rgba(0,0,0,0.17); min-height:100vh; padding:40px 20px;}
        .container { max-width:500px; margin:auto; background: rgba(94, 93, 93, 1); padding:25px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.15); color:white; }
        h1 { text-align:center; margin-bottom:20px; }
        label { display:block; margin-top:15px; font-size:14px; }
        input[type=text], input[type=date] { width:100%; padding:10px; border-radius:6px; border:none; margin-top:5px; }
        .btn { display:inline-block; padding:10px 18px; background:#007bff; color:white; border-radius:5px; margin-top:20px; cursor:pointer; text-decoration:none; }
        .btn:hover { background:#0056b3; }
        .error { color:#ff6b6b; margin-top:10px; text-align:center; }
    </style>
</head>
<body>
<div class="overlay">
    <div class="container">
        <h1>Edit Event</h1>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label for="event_name">Event Name:</label>
            <input type="text" name="event_name" id="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>

            <label for="event_date">Event Date:</label>
            <input type="date" name="event_date" id="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" value="<?= htmlspecialchars($event['location']) ?>" required>

            <button type="submit" class="btn">Update Event</button>
        </form>

        <div style="text-align:center; margin-top:15px;">
            <a href="index.php" class="btn" style="background:#28a745;">Back to Events</a>
        </div>
    </div>
</div>
</body>
</html>
