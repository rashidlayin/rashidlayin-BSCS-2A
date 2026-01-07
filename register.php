<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $event_id = $_POST['event'];

    $check_sql = "SELECT participant_id FROM participants WHERE email='$email'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        $participant_id = $row['participant_id'];
    } else {
        
        $stmt = $conn->prepare("INSERT INTO participants (full_name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        if ($stmt->execute()) {
            $participant_id = $stmt->insert_id;
        } else {
            $error_msg = "Error adding participant: " . $stmt->error;
        }
        $stmt->close();
    }

    if (!isset($error_msg)) {
        
        $check_reg = $conn->prepare("SELECT * FROM registrations WHERE event_id=? AND participant_id=?");
        $check_reg->bind_param("ii", $event_id, $participant_id);
        $check_reg->execute();
        $reg_result = $check_reg->get_result();
        if ($reg_result->num_rows > 0) {
            $error_msg = "Participant already registered for this event.";
        } else {
            
            $stmt = $conn->prepare("INSERT INTO registrations (event_id, participant_id, registration_date) VALUES (?, ?, CURDATE())");
            $stmt->bind_param("ii", $event_id, $participant_id);
            if ($stmt->execute()) {
                $success_msg = "Registered successfully!";
            } else {
                $error_msg = "Error registering for event: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_reg->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register Participant</title>
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
    input, select, button {
        padding: 10px;
        border-radius: 6px;
        font-size: 14px;
    }
    input, select {
        border: 1px solid #ccc;
        background: #fff;
        color: #000;
    }
    button {
        background: #7956f8ff;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.25s ease;
    }
    button:hover { background: #0056b3; }
    h2 { text-align: center; color: #fff; }
    .message { text-align: center; font-weight: bold; margin-top: 10px; color: #00ff00; }
    .error { color: #ff4d4d; }
</style>
</head>
<body>
<div class="overlay">
    <form method="POST">
        <h2>Register Participant</h2>
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="event" required>
            <option value="">-- Select Event --</option>
            <?php
            $events = $conn->query("SELECT * FROM events");
            while ($e = $events->fetch_assoc()) {
                echo "<option value='{$e['event_id']}'>{$e['event_name']} ({$e['event_date']})</option>";
            }
            ?>
        </select>
        <button type="submit">Register</button>

        <?php
        if (isset($success_msg)) echo "<p class='message'>{$success_msg}</p>";
        if (isset($error_msg)) echo "<p class='message error'>{$error_msg}</p>";
        ?>
    </form>
</div>
</body>
</html>
