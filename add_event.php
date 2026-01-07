<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $date = $conn->real_escape_string($_POST['date']);
    $location = $conn->real_escape_string($_POST['location']);

    $sql = "INSERT INTO events (event_name, event_date, location) VALUES ('$name', '$date', '$location')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Event</title>
<style>
    body {
        margin: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: url("img/bg.jpg") no-repeat center center/cover;
        min-height: 100vh;
        color: #2c3e50;
    }

    .overlay {
        background: rgba(0, 0, 0, 0.17);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0; 
    }

    form {
        background: rgba(94, 93, 93, 0.95);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 350px;
        color: #fff;
        margin: auto; 
    }

    input, button {
        padding: 10px;
        border-radius: 6px;
        font-size: 14px;
    }

    input {
        border: 1px solid #ccc;
        background: #fff;
        color: #000;
    }

    button {
        background: rgba(104, 104, 241, 1);
        color: white;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.25s ease;
    }
    button:hover {
        background: #565d64ff;
    }

    h2 {
        text-align: center;
        color: #fff;
    }
</style>
</head>
<body>
<div class="overlay">
    <form method="POST" onsubmit="return validateEvent()">
        <h2>Add Event</h2>
        <input type="text" name="name" placeholder="Event" required>
        <input type="date" name="date" required>
        <input type="text" name="location" placeholder="Location" required>
        <button type="submit">Save</button>
    </form>
</div>

<script>
function validateEvent() {
    const name = document.querySelector('input[name="name"]').value.trim();
    const date = document.querySelector('input[name="date"]').value;
    const location = document.querySelector('input[name="location"]').value.trim();

    if (!name || !date || !location) {
        alert('Please fill all fields!');
        return false;
    }
    return true;
}
</script>
</body>
</html>
