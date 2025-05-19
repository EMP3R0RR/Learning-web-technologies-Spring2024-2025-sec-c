<?php
// signup.php

// Initialize error message
$error_message = '';

// Connect to the database using mysqli (same as login.php)
// $servername = "localhost";
// $username = "root"; // MySQL username (default for XAMPP)
// $password = ""; // MySQL password (empty for default XAMPP)
// $dbname = "school_db";
// $port = 3307; 

// $conn = mysqli_connect($servername, $username, $password, $dbname, $port);
// if (!$conn) {
//     $error_message = "Cannot connect to database: " . mysqli_connect_error();
// }
// else {
    
//     mysqli_set_charset($conn, "utf8mb4");
// }

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $fname = isset($_POST['fname']) ? trim($_POST['fname']) : '';
    $lname = isset($_POST['lname']) ? trim($_POST['lname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
    $bloodGroup = isset($_POST['bloodGroup']) ? trim($_POST['bloodGroup']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';

    // Validation
    if (empty($fname) || !preg_match("/^[a-zA-Z ]{2,50}$/", $fname)) {
        $error_message = "First name must be 2-50 letters";
    } elseif (empty($lname) || !preg_match("/^[a-zA-Z ]{2,50}$/", $lname)) {
        $error_message = "Last name must be 2-50 letters";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
    } elseif ($age < 15 || $age > 100) {
        $error_message = "Age must be between 15 and 100";
    } elseif (empty($bloodGroup) || !in_array($bloodGroup, ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])) {
        $error_message = "Invalid blood group";
    } elseif (empty($department) || !in_array($department, ['CSE', 'BBA', 'EEE', 'ENGLISH', 'DATA SCIENCE', 'LLB'])) {
        $error_message = "Invalid department";
    } elseif (empty($address) || strlen($address) < 5 || strlen($address) > 255) {
        $error_message = "Address must be 5-255 characters";
    } elseif (empty($password) || strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters";
    } elseif ($password !== $confirm) {
        $error_message = "Passwords do not match";
    } else {
      header("Location: login.php" );
        // Check if email already exists
        // $query = "SELECT id FROM users WHERE email = ?";
        // $stmt = mysqli_prepare($conn, $query);
        // mysqli_stmt_bind_param($stmt, "s", $email);
        // mysqli_stmt_execute($stmt);
        // $result = mysqli_stmt_get_result($stmt);
        // if (mysqli_num_rows($result) > 0) {
        //     $error_message = "Email already registered";
        // }
        // mysqli_stmt_close($stmt);
    }

   
    // if (!$error_message) {
        
    //     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    //     $role = 'student'; 

        
    //     mysqli_begin_transaction($conn);
    //     try {
            
    //         $query = "INSERT INTO users (role, email, password) VALUES (?, ?, ?)";
    //         $stmt = mysqli_prepare($conn, $query);
    //         mysqli_stmt_bind_param($stmt, "sss", $role, $email, $hashed_password);
    //         mysqli_stmt_execute($stmt);
    //         $user_id = mysqli_insert_id($conn); 
    //         mysqli_stmt_close($stmt);

    //         $query = "INSERT INTO students ( id, first_name, last_name, email, age, blood_group, department, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    //         $stmt = mysqli_prepare($conn, $query);
    //         mysqli_stmt_bind_param($stmt, "isssisss", $user_id, $fname, $lname, $email, $age, $bloodGroup, $department, $address);
    //         mysqli_stmt_execute($stmt);
    //         mysqli_stmt_close($stmt);

            
    //         mysqli_commit($conn);

    //         header("Location: login.php");
    //         exit();
    //     } catch (Exception $e) {
            
    //         mysqli_rollback($conn);
    //         $error_message = "Signup failed: ";
    //     }
    // }
}

// Close the database connection
// if ($conn) {
//     mysqli_close($conn);
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #e0ecff, #f3f9ff);
      color: #333;
      line-height: 1.6;
      min-height: 100vh;
    }

    .landing-header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
    }

    header {
      background-color: #004080;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    header h1 {
      font-size: 1.8rem;
      cursor: pointer;
    }

    nav {
      display: flex;
      gap: 1rem;
    }

    nav button {
      background: transparent;
      border: 1px solid white;
      padding: 0.4rem 1rem;
      color: white;
      font-weight: 500;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s, color 0.3s;
    }

    nav button:hover {
      background-color: white;
      color: #004080;
    }

    .page-layout {
      display: flex;
      justify-content: space-between;
      gap: 2rem;
      margin: 8rem auto 2rem;
      padding: 2rem;
      max-width: 1100px;
      background-color: #ffffffd9;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    .form-container,
    .side-container {
      flex: 1;
      padding: 1rem;
    }

    .form-container h2,
    .side-container h2 {
      text-align: center;
      color: #004080;
      margin-bottom: 1rem;
    }

    form label {
      display: block;
      margin-top: 12px;
      font-weight: 500;
      color: #003366;
    }

    input, select {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .row-fields {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
    }

    .field {
      flex: 1;
    }

    button[type="submit"] {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #0059b3;
    }

    .error {
      color: red;
      font-size: 0.9rem;
      margin-top: 5px;
    }

    .info-section {
      margin-bottom: 1.5rem;
    }

    .info-section h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      color: #003366;
    }

    .info-section ul {
      list-style: disc;
      padding-left: 1.5rem;
    }

    .info-section li {
      margin-bottom: 0.5rem;
      color: #444;
    }

    @media (max-width: 768px) {
      .page-layout {
        flex-direction: column;
      }

      nav {
        flex-direction: column;
        margin-top: 1rem;
      }

      nav button {
        width: 100%;
        text-align: left;
      }

      .row-fields {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <!-- Landing Header -->
  <div class="landing-header">
    <header>
      <h1 onclick="window.location.href='landingpage.php'">Student Management System</h1>
      <nav>
        <button onclick="window.location.href='login.php'">LOGIN</button>
        <button onclick="window.location.href='signup.php'">SIGNUP</button>
        <button onclick="window.location.href='aboutus.php'">TEAM</button>
        <button onclick="window.location.href='landingpage.php'">BACK</button>
      </nav>
    </header>
  </div>

  <!-- Sign Up Form and Info Panel -->
  <div class="page-layout">
    <!-- Sign Up Form -->
    <div class="form-container suform-container" id="signup">
      <h2>Sign Up</h2>
      <?php if ($error_message): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
      <?php endif; ?>
      <form id="signupForm" action="signup.php" method="POST">
        <div class="error" id="errorMsg"></div>

        <label for="fname">First Name</label>
        <input type="text" id="fname" name="fname" required value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>" />

        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lname" required value="<?php echo isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; ?>" />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />

        <label for="age">Age</label>
        <input type="number" id="age" name="age" required value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>" />

        <div class="row-fields">
          <div class="field">
            <label for="bloodGroup"><strong>Blood Group</strong></label>
            <select id="bloodGroup" name="bloodGroup" required>
              <option value="">Select</option>
              <option value="A+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'A+' ? 'selected' : ''; ?>>A+</option>
              <option value="A-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'A-' ? 'selected' : ''; ?>>A-</option>
              <option value="B+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'B+' ? 'selected' : ''; ?>>B+</option>
              <option value="B-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'B-' ? 'selected' : ''; ?>>B-</option>
              <option value="O+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'O+' ? 'selected' : ''; ?>>O+</option>
              <option value="O-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'O-' ? 'selected' : ''; ?>>O-</option>
              <option value="AB+" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'AB+' ? 'selected' : ''; ?>>AB+</option>
              <option value="AB-" <?php echo isset($_POST['bloodGroup']) && $_POST['bloodGroup'] === 'AB-' ? 'selected' : ''; ?>>AB-</option>
            </select>
          </div>
          <div class="field">
            <label for="department"><strong>Department</strong></label>
            <select id="department" name="department" required>
              <option value="">Select</option>
              <option value="CSE" <?php echo isset($_POST['department']) && $_POST['department'] === 'CSE' ? 'selected' : ''; ?>>CSE</option>
              <option value="BBA" <?php echo isset($_POST['department']) && $_POST['department'] === 'BBA' ? 'selected' : ''; ?>>BBA</option>
              <option value="EEE" <?php echo isset($_POST['department']) && $_POST['department'] === 'EEE' ? 'selected' : ''; ?>>EEE</option>
              <option value="ENGLISH" <?php echo isset($_POST['department']) && $_POST['department'] === 'ENGLISH' ? 'selected' : ''; ?>>ENGLISH</option>
              <option value="DATA SCIENCE" <?php echo isset($_POST['department']) && $_POST['department'] === 'DATA SCIENCE' ? 'selected' : ''; ?>>DS</option>
              <option value="LLB" <?php echo isset($_POST['department']) && $_POST['department'] === 'LLB' ? 'selected' : ''; ?>>LLB</option>
            </select>
          </div>
        </div>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" />

        <label for="password">Password</label>
        <input type="text" id="password" name="password" required />

        <label for="confirm">Confirm Password</label>
        <input type="text" id="confirm" name="confirm" required />

        <button type="submit">Sign Up</button>
      </form>
    </div>

    <!-- Info Panel -->
    <div class="side-container">
      <div class="info-container" id="signup-info">
        <h2>Why Sign Up?</h2>
        <div class="info-section">
          <h3>🎓 Create Your Academic Profile</h3>
          <ul>
            <li>Track your academic history and performance.</li>
            <li>Join classes and access materials.</li>
          </ul>
        </div>
        <div class="info-section">
          <h3>📋 Secure Enrollment</h3>
          <ul>
            <li>Verified identity for smooth communication.</li>
            <li>Role-specific features tailored to your needs.</li>
          </ul>
        </div>
        <div class="info-section">
          <h3>💡 Personalized Experience</h3>
          <ul>
            <li>Custom notifications and reminders.</li>
            <li>Access to grades, attendance, and reports.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <script>

    function validation() {

    const signupForm = document.getElementById("signupForm");
    
    if (signupForm){
        signupForm.addEventListener("submit", function(e) {
            e.preventDefault();
    const fname = document.getElementById("fname").value.trim();
    const lname = document.getElementById("lname").value.trim();
    const email = document.getElementById("email").value.trim();
    const age = document.getElementById("age").value.trim();
    const bloodGroup = document.getElementById("bloodGroup").value;
    const department = document.getElementById("department").value;
    const address = document.getElementById("address").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirm = document.getElementById("confirm").value.trim();

    const errorMsg = document.getElementById("errorMsg");
    errorMsg.textContent = ""; 

    
    if (
      !fname || !lname || !email || !age || !bloodGroup || !department ||
      !address || !password || !confirm
    ) {
      errorMsg.textContent = "Please fill out all fields.";
      return;
    }

    
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email)) {
      errorMsg.textContent = "Please enter a valid email address.";
      return;
    }

    
    if (password !== confirm) {
      errorMsg.textContent = "Passwords do not match."
      return;
    }

    
    alert("Signup successful!");
    location.reload();
    

        });
    }

    window.location.href="login.php"

   
}  
    
  </script>
</body>
</html>