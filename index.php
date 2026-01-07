<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EVENT MANAGEMENT SYSTEM</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    
        * { box-sizing: border-box; margin:0; padding:0; }

        html, body {
            height: 100%;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: url("img/bg.jpg") no-repeat center center/cover;
            background-attachment: fixed;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.11);
            position: fixed;
            top:0; left:0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 90%;
            max-width: 850px;
            background: rgba(94, 93, 93, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transform: translateY(-20px);
        }

        .title-box {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .title-box h1 {
            color: rgba(219, 232, 233, 1);
            border: 3px solid #e0dfd5ff;
            border-radius: 10px;
            padding: 15px 25px;
            background: rgba(0,0,0,0.3);
            text-align: center;
        }

        h2 {
            text-align:center;
            color:#fff;
            margin-bottom: 15px;
        }

        .nav-buttons {
            text-align:center;
            margin-bottom:20px;
        }

        .btn {
            display:inline-block;
            padding:10px 18px;
            background:#007bff;
            color:white;
            text-decoration:none;
            border-radius:5px;
            margin:5px;
            transition:all 0.25s ease;
        }

        .btn:hover { transform: translateY(-2px); }
        .btn-secondary { background:#28a745; }

        table {
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        table th, table td {
            padding:10px;
            border:1px solid #ccc;
            text-align:center;
        }

        table th {
            background:#3a4a5c;
            color:white;
        }

        /* ICON BUTTONS */
        .icon-btn {
            font-size: 18px;
            margin: 0 8px;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .edit-icon {
            color: #ffc107;
        }

        .edit-icon:hover {
            color: #ffdb4d;
            transform: scale(1.2);
        }

        .delete-icon {
            color: #ff4d4d;
        }

        .delete-icon:hover {
            color: #ff1a1a;
            transform: scale(1.2);
        }

        @media (max-width: 600px) {
            .container { padding: 15px; }
            .btn { padding:8px 14px; margin:3px; }
        }
    </style>
</head>

<body>
<div class="overlay">
    <div class="container">

        <div class="title-box">
            <h1>EVENT MANAGEMENT SYSTEM</h1>
        </div>

        <div class="nav-buttons">
            <a href="add_event.php" class="btn">Add Event</a>
            <a href="register.php" class="btn btn-secondary">Register</a>
            <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
        </div>

        <h2>Events</h2>

        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['event_name']}</td>
                        <td>{$row['event_date']}</td>
                        <td>{$row['location']}</td>
                        <td>
                            <a href='edit_event.php?id={$row['event_id']}' class='icon-btn edit-icon' title='Edit'>
                                <i class='fas fa-pen-to-square'></i>
                            </a>
                            <a href='delete.php?id={$row['event_id']}' class='icon-btn delete-icon' title='Delete'
                               onclick=\"return confirm('Are you sure you want to delete this event?');\">
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No events found</td></tr>";
            }
            ?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
