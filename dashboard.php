<?php
session_start();  // Start the session to access session variables

// Check if the session variables are set
if (!isset($_SESSION['UserID']) || !isset($_SESSION['Username'])) {
    // Redirect to login page if session variables are not set
    header("Location: login.php");
    exit;
}

$username = $_SESSION['Username'];  // Retrieve the logged-in user's username
$userID = $_SESSION['UserID'];      // Retrieve the logged-in user's ID

// Database connection
$servername = "localhost";
$dbUsername = "root";
$password = "";
$dbname = "NutritionDB";

// Create connection
$conn = new mysqli($servername, $dbUsername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user profile data (Weight and Height)
$query = "SELECT Weight, Height FROM UserProfile WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$userProfile = $result->fetch_assoc();

// Determine the current week's start and end dates
$currentDate = new DateTime();
$currentDate->modify('monday this week'); // Start of the week (Monday)
$startOfWeek = $currentDate->format('Y-m-d'); // Format the date as YYYY-MM-DD

$currentDate->modify('sunday this week'); // End of the week (Sunday)
$endOfWeek = $currentDate->format('Y-m-d');

// Check if the user has a saved diet plan for the week
$query = "SELECT * FROM DietPlans WHERE UserID = ? AND WeekStartDate = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userID, $startOfWeek);
$stmt->execute();
$dietPlanResult = $stmt->get_result();

// If the diet plan for the week exists, display it; otherwise, allow the user to submit a new one
if ($dietPlanResult->num_rows > 0) {
    $dietPlan = $dietPlanResult->fetch_assoc();
} else {
    $dietPlan = null;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f4f4f4;
            height: 100vh;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #0a5247;
            color: #fff;
            padding-top: 30px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s ease;
            border-bottom: 1px solid #fff;
        }

        .sidebar a:hover {
            background-color: #bfd202;
        }

        .sidebar .logout-btn {
            margin-top: 30px;
            background-color: #ff4d4d;
            text-align: center;
            font-size: 18px;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        /* Main content styling */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: 100%;
            background-color: #fff;
            min-height: 100vh;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content-header h1 {
            font-size: 28px;
            color: #0a5247;
        }

        .section {
            margin-top: 20px;
        }

        .section h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .section p {
            font-size: 16px;
        }

        /* Diet Plan Styling */
        .diet-plan {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .diet-plan h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .diet-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .diet-table th, .diet-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .diet-table th {
            background-color: #0a5247;
            color: #fff;
        }

        .diet-table td input {
            width: 100%;
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .diet-table td span {
            display: block;
            padding: 5px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Welcome, <?php echo $username; ?></h2>
        <a href="dashboard.php?section=profile">Profile</a>
        <a href="dashboard.php?section=diet_plan">Diet Plan</a>
        <a href="dashboard.php?section=meal_planning">Meal Planning</a>
        <a href="dashboard.php?section=settings">Settings</a>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-header">
            <h1>Dashboard</h1>
        </div>

        <?php
        // Determine the section to display based on the query parameter
        $section = isset($_GET['section']) ? $_GET['section'] : 'profile';

        switch ($section) {
            case 'profile':
                echo '<div class="section">';
                echo '<h3>Your Profile</h3>';
                echo '<table class="diet-table">';
                echo '<tr><th>Username</th><td>' . $username . '</td></tr>';
                echo '<tr><th>User ID</th><td>' . $userID . '</td></tr>';
                echo '<tr><th>Weight</th><td>' . ($userProfile['Weight'] ?? 'Not Set') . ' kg</td></tr>';
                echo '<tr><th>Height</th><td>' . ($userProfile['Height'] ?? 'Not Set') . ' cm</td></tr>';
                echo '</table>';
                echo '</div>';
                break;

            case 'diet_plan':
                echo '<div class="section">';
                echo '<div class="diet-plan">';
                echo '<h3>Your Diet Plan for the Week</h3>';
                echo '<p>Week starting: ' . $startOfWeek . ' to ' . $endOfWeek . '</p>';

                // If diet plan exists, show it
                if ($dietPlan) {
                    echo '<table class="diet-table">';
                    echo '<thead>';
                    echo '<tr><th>Day</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>';
                    echo '</thead><tbody>';
                    foreach ($dietPlan['DietDetails'] as $day => $meals) {
                        echo '<tr>';
                        echo '<td>' . $day . '</td>';
                        echo '<td><span>' . $meals['breakfast'] . '</span></td>';
                        echo '<td><span>' . $meals['lunch'] . '</span></td>';
                        echo '<td><span>' . $meals['dinner'] . '</span></td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>No diet plan available for this week. You can add one below.</p>';
                    echo '<form method="POST" action="submit_diet_plan.php">';
                    echo '<table class="diet-table">';
                    echo '<thead>';
                    echo '<tr><th>Day</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>';
                    echo '</thead><tbody>';
                    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    foreach ($daysOfWeek as $day) {
                        echo '<tr>';
                        echo "<td>{$day}</td>";
                        echo '<td><input type="text" name="breakfast[' . $day . ']" value=""></td>';
                        echo '<td><input type="text" name="lunch[' . $day . ']" value=""></td>';
                        echo '<td><input type="text" name="dinner[' . $day . ']" value=""></td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                    echo '<button type="submit">Save Diet Plan</button>';
                    echo '</form>';
                }
                echo '</div>';
                echo '</div>';
                break;

            case 'meal_planning':
                echo '<div class="section">';
                echo '<h3>Meal Planning</h3>';
                echo '<p>Here you can plan your meals for the week...</p>';
                echo '</div>';
                break;

            case 'settings':
                echo '<div class="section">';
                echo '<h3>Settings</h3>';
                echo '<p>Update your account settings here...</p>';
                echo '</div>';
                break;

            default:
                echo '<div class="section">';
                echo '<h3>Your Profile</h3>';
                echo '<p>Here is your profile information...</p>';
                echo '</div>';
                break;
        }
        ?>
    </div>

</body>
</html>
