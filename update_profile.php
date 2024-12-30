<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "NutritionDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session
$userID = $_SESSION['UserID'];

// Fetch user profile data (Weight and Height)
$query = "SELECT Weight, Height FROM UserProfile WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$userProfile = $result->fetch_assoc();

// Handle form submission to update weight and height
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $weight = trim($_POST['weight']);
    $height = trim($_POST['height']);

    // Update the weight and height in the database
    if ($userProfile) {
        $updateQuery = "UPDATE UserProfile SET Weight = ?, Height = ? WHERE UserID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("dii", $weight, $height, $userID);
    } else {
        $insertQuery = "INSERT INTO UserProfile (UserID, Weight, Height) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("dii", $userID, $weight, $height);
    }

    if ($stmt->execute()) {
        echo "<p class='success'>Profile updated successfully!</p>";
    } else {
        echo "<p class='error'>Error updating profile.</p>";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Include Font Awesome -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #bfd202, #0a5247);
            height: 100vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
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

        .input-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .input-wrapper i {
            margin-right: 10px;
            color: #bfd202; /* Icon color */
        }

        .input-wrapper input {
            flex-grow: 1; /* Allow input to take remaining space */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Profile</h1>
        <form method="POST" action="">
            <div class="input-wrapper">
                <i class="fas fa-weight"></i> <!-- Font Awesome weight icon -->
                <label for="weight">Weight (kg):</label>
                <input type="number" step="0.1" name="weight" id="weight" value="<?php echo htmlspecialchars($userProfile['Weight'] ?? ''); ?>" required>
            </div>

            <div class="input-wrapper">
                <i class="fas fa-ruler-vertical"></i> <!-- Font Awesome height icon -->
                <label for="height">Height (cm):</label>
                <input type="number" step="0.1" name="height" id="height" value="<?php echo htmlspecialchars($userProfile['Height'] ?? ''); ?>" required>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
