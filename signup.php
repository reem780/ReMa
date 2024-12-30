<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "NutritionDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Signup logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM Users WHERE Username = ? OR Email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='error'>Username or email already exists!</p>";
    } else {
        // Insert new user into the database
        $insertQuery = "INSERT INTO Users (Username, Password, Email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            // Start session and auto-login user
            session_start();
            $_SESSION['UserID'] = $conn->insert_id;  // Get the inserted user's ID
            $_SESSION['Username'] = $username;
            $_SESSION['Role'] = 'User'; // Assuming the default role is 'User'

            // Redirect to dashboard.php after successful signup and login
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #bfd202, #0a5247);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            animation: backgroundAnimation 10s infinite alternate;
        }

        @keyframes backgroundAnimation {
            0% {
                background: linear-gradient(135deg, #bfd202, #0a5247);
            }
            100% {
                background: linear-gradient(135deg, #0a5247, #bfd202);
            }
        }

        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            letter-spacing: 1px;
            color: #bfd202;
        }

        .container form label {
            display: block;
            text-align: left;
            margin-top: 15px;
        }

        .container form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .container form input:focus {
            outline: none;
            box-shadow: 0 0 5px #bfd202;
        }

        .container button {
            margin-top: 20px;
            padding: 10px 15px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            background: #0a5247;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container button:hover {
            background: #bfd202;
        }

        .success, .error {
            margin-top: 20px;
            font-size: 14px;
            color: #ffd700;
        }

        .error {
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
