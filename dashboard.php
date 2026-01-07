<?php
include 'db.php';

// Handle participant registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $event_id = $_POST['event'];

    // Check if participant already exists
    $check_sql = "SELECT participant_id FROM participants WHERE email='$email'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $participant_id = $row['participant_id'];
    } else {
        // Insert new participant
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
        // Check if participant already registered for this event
        $check_reg = $conn->prepare("SELECT * FROM registrations WHERE event_id=? AND participant_id=?");
        $check_reg->bind_param("ii", $event_id, $participant_id);
        $check_reg->execute();
        $reg_result = $check_reg->get_result();
        if ($reg_result->num_rows > 0) {
            $error_msg = "Participant already registered for this event.";
        } else {
            // Register participant
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

$reg_sql = "SELECT r.registration_id, p.full_name, p.email, e.event_name, r.registration_date
            FROM registrations r
            JOIN participants p ON r.participant_id = p.participant_id
            JOIN events e ON r.event_id = e.event_id
            ORDER BY r.registration_date DESC";
$registrations = $conn->query($reg_sql);

$events = $conn->query("SELECT * FROM events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event Management Dashboard</title>
<style>
body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: url('img/bg.jpg') no-repeat center center/cover;
    margin: 0; padding: 0; color: #2c3e50;
}
.container {
    max-width: 1000px;
    margin: 30px auto;
    background: rgba(255,255,255,0.95);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
h2 { text-align: center; margin-bottom: 20px; }
form { display: flex; flex-direction: column; gap: 15px; margin-bottom: 40px; }
input, select, button {
    padding: 10px;
    border-radius: 6px;
    font-size: 14px;
    border: 1px solid #ccc;
}
button {
    background: #007bff; color: #fff; border: none;
    cursor: pointer; font-weight: 500; transition: 0.3s;
}
button:hover { background: #0056b3; }
.message { text-align: center; font-weight: bold; margin-top: 10px; color: #00aa00; }
.error { color: #ff4d4d; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { padding: 12px; border-bottom: 1px solid #ccc; text-align: left; }
th { background: #007bff; color: #fff; }
tr:nth-child(even) { background: #f2f2f2; }
</style>
</head>
<body>
<div class="container">
    <h2>Event Management Dashboard</h2>

    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="event" required>
            <option value="">-- Select Event --</option>
            <?php while($e = $events->fetch_assoc()): ?>
                <option value="<?= $e['event_id'] ?>"><?= htmlspecialchars($e['event_name']) ?> (<?= $e['event_date'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="register">Register Participant</button>
        <?php
        if (isset($success_msg)) echo "<p class='message'>{$success_msg}</p>";
        if (isset($error_msg)) echo "<p class='message error'>{$error_msg}</p>";
        ?>
    </form>

    <h2>Registered Participants</h2>
    <?php if ($registrations->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Participant Name</th>
                <th>Email</th>
                <th>Event</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $count=1; while($row = $registrations->fetch_assoc()): ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($row['full_name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['event_name']); ?></td>
                    <td><?= $row['registration_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="text-align:center; font-weight:bold;">No participants registered yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
