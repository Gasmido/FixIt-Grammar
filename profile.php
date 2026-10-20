<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Form Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    
    // Handle File Upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        
        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, profile_pic = ? WHERE user_id = ?");
            $stmt->bind_param("sssi", $first_name, $last_name, $file_name, $user_id);
        }
    } else {
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $first_name, $last_name, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['name'] = $first_name; // Update session name
        $message = "Profile updated successfully! ✨";
    } else {
        $message = "Error updating profile.";
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$profile_image = !empty($user['profile_pic']) ? "uploads/" . $user['profile_pic'] : "https://cdn-icons-png.flaticon.com/512/149/149071.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Grammar Quest</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366F1;
            --secondary: #06B6D4;
            --bg-color: #F8FAFC;
            --text-main: #1E293B;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 30px 30px;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar inherited from Dashboard */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar h2 {
            margin: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.6rem;
            font-weight: 800;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            width: 90%;
        }

        .profile-card {
            background: white;
            padding: 40px;
            border-radius: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
            border: 1px solid #F1F5F9;
        }

        .profile-pic-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
        }

        .profile-pic-preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .upload-label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .upload-label:hover { transform: scale(1.1); background: var(--secondary); }

        input[type="file"] { display: none; }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-main);
            font-size: 0.9rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border-radius: 15px;
            border: 2px solid #F1F5F9;
            font-size: 1rem;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input[type="text"]:focus {
            border-color: var(--primary);
            outline: none;
            background: #F8FAFF;
        }

        .save-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
            margin-top: 10px;
        }

        .save-btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
        }

        .alert {
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            background: #F0FDF4;
            color: #166534;
            font-weight: 600;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #64748B;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2><i class="fa-solid fa-graduation-cap"></i> Grammar Quest</h2>
    <a href="dashboard.php" class="back-link" style="margin:0"><i class="fa-solid fa-house"></i> Home</a>
</div>

<div class="container">
    <div class="profile-card">
        <h2>Your Hero Profile 👤</h2>
        <p style="color: #64748B; margin-bottom: 30px;">Customize your explorer details below.</p>

        <?php if($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="profile-pic-container">
                <img src="<?php echo $profile_image; ?>" class="profile-pic-preview" id="preview">
                <label for="file-upload" class="upload-label">
                    <i class="fa-solid fa-camera"></i>
                </label>
                <input type="file" id="file-upload" name="profile_pic" accept="image/*" onchange="previewImage(event)">
            </div>

            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email (Locked)</label>
                <input type="text" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background: #F1F5F9; cursor: not-allowed;">
            </div>

            <button type="submit" class="save-btn">Update Profile ✨</button>
        </form>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>