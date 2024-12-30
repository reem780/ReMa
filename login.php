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

// Initialize variables
$username = $password = $error = "";

// Login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if username and password exist in the POST array
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        // Fetch user from database
        $query = "SELECT * FROM Users WHERE Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['Password'])) {
                session_start();
                $_SESSION['UserID'] = $user['UserID'];
                $_SESSION['Username'] = $user['Username'];
                $_SESSION['Role'] = $user['Role'];
                header("Location: dashboard.php"); // Redirect to dashboard after login
                exit;
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "User not found!";
        }
        $stmt->close();
    } else {
        $error = "Please fill in both fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <h1>Login</h1>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>
