<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rema</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            overflow-x: hidden;
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 100vh; /* Full screen height */
            overflow-y: scroll;
        }

        .image {
            position: relative;
            width: 100%;
            height: 100vh; /* Full screen height for each image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Image backgrounds */
        .image:nth-child(1) {
            background-image: url('imgs/Home.png');
        }

        .image:nth-child(2) {
            background-image: url('imgs/Our Philosophy.png');
        }

        .image:nth-child(3) {
            background-image: url('imgs/3.png');
        }

        .image:nth-child(4) {
            background-image: url('imgs/Our Professionals.png');
        }

        .image:nth-child(5) {
            background-image: url('imgs/Our Services.png');
        }

        .image:nth-child(6) {
            background-image: url('imgs/6.png');
        }

        .image:nth-child(7) {
            background-image: url('imgs/Testimonials.png');
        }

        .image:nth-child(8) {
            background-image: url('imgs/Get In Touch.png');
        }

        /* Login and Signup buttons only on the first image */
        .top-bar {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .top-bar button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .login-btn {
            background-color: #bfd202;
        }

        .signup-btn {
            background-color: #0a5247;
        }

        .top-bar button:hover {
            opacity: 0.8;
        }

        /* Overlay text for images (optional) */
       /* .image-text {
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 10;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 10px 20px;
            border-radius: 5px; 
        }*/
    </style>
</head>
<body>
    <div class="image-container">
        <!-- First image with buttons -->
        <div class="image">
            <div class="top-bar">
                <form action="login.php" method="POST">
                    <button class="login-btn">Login</button>
                </form>
                <form action="signup.php" method="GET">
                    <button class="signup-btn">Sign Up</button>
                </form>
            </div>
          
        </div>

        <!-- Remaining images -->
        <div class="image"></div>
        <div class="image"></div>
        <div class="image"></div>
        <div class="image"></div>
        <div class="image"></div>
        <div class="image"></div>
        <div class="image"></div>
    </div>
</body>
</html>
