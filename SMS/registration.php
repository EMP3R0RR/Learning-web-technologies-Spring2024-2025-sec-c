<?php
session_start();

// Check session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Session expired");
    exit();
}
$_SESSION['last_activity'] = time();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please log in");
    exit();
}

// Check if role is set and valid
$valid_roles = ['student', 'teacher', 'admin'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $valid_roles)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Invalid role");
    exit();
}

// Check if user is a student
if ($_SESSION['role'] !== 'student') {
    $profile_pages = [
        'student' => 'StudentProfile.php',
        'teacher' => 'teacherprofile.php',
        'admin' => 'AdminProfile.php'
    ];
    header("Location: " . $profile_pages[$_SESSION['role']] . "?error=Unauthorized access");
    exit();
}

// Check if user has visited their profile
if (!isset($_SESSION['visited_profile']) || $_SESSION['visited_profile'] !== true) {
    header("Location: StudentProfile.php?error=Visit profile first");
    exit();
}
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "school_db";
// $port = 3307;

// try {
//     $conn = mysqli_connect($servername, $username, $password, $dbname, $port);
//     if (!$conn) {
//         throw new Exception("Cannot connect to database: " . mysqli_connect_error());
//     }
//     mysqli_set_charset($conn, "utf8mb4");

//     // Fetch all courses
//     $courses_query = "SELECT course_id, course_name, price, schedule FROM courses";
//     $courses_result = $conn->query($courses_query);
//     if (!$courses_result) {
//         throw new Exception("Error fetching courses: " . $conn->error);
//     }
//     $courses = [];
//     while ($row = $courses_result->fetch_assoc()) {
//         $courses[] = $row;
//     }

    // Handle form submission
    $error_message = '';
    $success_message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $semester = trim($_POST['semester'] ?? '');
        $course_ids = $_POST['courses'] ?? [];
        $selected_courses = (int)($_POST['selected_courses'] ?? 0);
        $total_cost = (float)($_POST['total_cost'] ?? 0);

        if (empty($semester)) {
            $error_message = "Please select a semester.";
        } elseif (empty($course_ids)) {
            $error_message = "Please select at least one course.";
        } elseif ($selected_courses <= 0 || $total_cost <= 0) {
            $error_message = "Invalid course selection or total cost.";
        } else {
          $success_message = "Courses registered successfully!";
            // Check for existing enrollments
            // $student_id = $_SESSION['user_id'];
            // $existing_courses = [];
            // foreach ($course_ids as $course_id) {
            //     $stmt = $conn->prepare("SELECT enrolment_id FROM enrolment WHERE student_id = ? AND course_id = ? AND semester = ?");
            //     $stmt->bind_param("iis", $student_id, $course_id, $semester);
            //     $stmt->execute();
            //     $result = $stmt->get_result();
            //     if ($result->num_rows > 0) {
            //         $existing_courses[] = $course_id;
            //     }
            //     $stmt->close();
            // }

            // if (!empty($existing_courses)) {
            //     $error_message = "You are already enrolled in course IDs: " . implode(", ", $existing_courses) . " for $semester.";
            // } else {
            //     // Insert new enrollments
            //     $conn->begin_transaction();
            //     try {
            //         foreach ($course_ids as $course_id) {
            //             $stmt = $conn->prepare("INSERT INTO enrolment (student_id, course_id, semester, selected_courses, total_cost) VALUES (?, ?, ?, ?, ?)");
            //             $stmt->bind_param("iisid", $student_id, $course_id, $semester, $selected_courses, $total_cost);
            //             if (!$stmt->execute()) {
            //                 throw new Exception("Error enrolling in course ID $course_id: " . $stmt->error);
            //             }
            //             $stmt->close();
            //         }
            //         $conn->commit();
            //         $success_message = "Courses registered successfully!";
            //     } catch (Exception $e) {
            //         $conn->rollback();
            //         $error_message = "Enrollment failed: " . $e->getMessage();
            //     }
            // }
        }
    }

    // $conn->close();
  // catch (Exception $e) {
  //   if (isset($conn) && $conn) {
  //       $conn->close();
  //   }
  //   ob_end_clean();
  //   header("Location: registration.php?error=" . urlencode("Database error: " . $e->getMessage()));
  //   exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Course Registration</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0ecff, #f3f9ff);
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      width: 100%;
      height: 85px;
      background-color: #004080;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 2rem;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .header-left {
      display: flex;
      align-items: center;
    }

    header h1 {
      font-size: 1.5rem;
    }

    .header-right {
      display: flex;
      gap: 1rem;
    }

    .header-right button {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 6px;
      background-color: #ffffff;
      color: #004080;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .header-right button:hover {
      background-color: #cce0ff;
    }

    .dashboard {
      display: flex;
      height: calc(100% - 85px);
    }

    .sidediv {
      width: 250px;
      background-color: #003366;
      color: white;
      padding: 2rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .sidediv button {
      padding: 0.8rem 1rem;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      cursor: pointer;
      text-align: left;
      transition: background-color 0.3s, transform 0.2s;
    }

    .sidediv button:hover {
      background-color: #0059b3;
      transform: translateX(10px);
    }

    .maindiv {
      flex: 1;
      padding: 2rem;
      background-color: #ffffff;
      overflow-y: auto;
    }

    .form-container {
      background-color: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5rem;
    }

    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f0f0f0;
    }

    select {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-top: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .regbtn {
      margin-top: 1rem;
      width: 100%;
      padding: 12px;
      font-size: 1rem;
      background-color: #004080;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .regbtn button :hover {
      background-color:rgb(103, 136, 167);
    }

    .total-box {
      margin-top: 2rem;
      padding: 1rem;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1.1rem;
      color: #004080;
    }
  </style>
</head>
<body>

    <header>
    <div class="header-left">
      <h1>Welcome to the Dashboard</h1>
    </div>

    <div class="header-right">
       <form action="" method="POST">
        <button type="submit">🔔 Notifications</button>
        </form>
        <form action="logout.php" method="POST">
        <button type="submit">Logout</button>
        </form>
    </div>
  </header>

  <div class="dashboard">
    <div class="sidediv">
      <button onclick="window.location.href='studentprofile.php'">Profile</button>
      <button onclick="window.location.href='grades.php'">Grades</button>
      <button onclick="window.location.href='registration.php'">Registration</button>
      <button onclick="window.location.href='payment.php'">Payment</button>
    </div>

    <div class="maindiv">
      <main class="form-container">
        <h2>Course Registration</h2>
        <?php if ($error_message): ?>
          <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
          <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
       <form id="registrationForm" action= "registration.php" method="POST">
          <table>
            <thead>
              <tr>
                <th>Select</th>
                <th>Course</th>
                <th>Price</th>
                <th>Schedule</th>
              </tr>
            </thead>
            <tbody>
              <!-- <?php foreach ($courses as $course): ?> -->
                <tr>
                  <td>
                    <input type="checkbox" class="course-checkbox" name="courses[]" 
                           value="<?php echo htmlspecialchars($course['course_id']); ?>" 
                           data-price="<?php echo htmlspecialchars($course['price']); ?>" 
                           id="course_<?php echo htmlspecialchars($course['course_id']); ?>">
                  </td>
                  <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                  <td>$<?php echo number_format($course['price'], 2); ?></td>
                  <td><?php echo htmlspecialchars($course['schedule']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <label for="semester">Select Semester:</label>
          
          <select id="semester">
            <option value="">-- Select --</option>
            <option value="spring">Spring 2025</option>
            <option value="summer">Summer 2025</option>
            <option value="fall">Fall 2025</option>
          </select>
              

          <input type="hidden" id="selected_courses" name="selected_courses" value="0">
          <input type="hidden" id="total_cost" name="total_cost" value="0">

          <button type="submit" class="regbtn">Register Selected Courses</button>
        </form>

        <div class="total-box">
            Selected Courses: <span id="selectedCount">0</span><br>
            Total Cost: $<span id="totalCost">0</span>
        </div>
      </main>
    </div>
  </div>

  <script>
    const checkboxes = document.querySelectorAll(".course-checkbox");
    const totalCostSpan = document.getElementById("totalCost");
    const selectedCountSpan = document.getElementById("selectedCount");
    const selectedCoursesInput = document.getElementById("selected_courses");
    const totalCostInput = document.getElementById("total_cost");

    function updateCourseSummary() {
      let total = 0;
      let count = 0;

      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          total += parseFloat(checkbox.dataset.price);
          count++;
        }
      });

      totalCostSpan.textContent = total.toFixed(2);
      selectedCountSpan.textContent = count;
      selectedCoursesInput.value = count;
      totalCostInput.value = total.toFixed(2);
    }

    checkboxes.forEach(cb => {
      cb.addEventListener("change", updateCourseSummary);
    });

    document.getElementById("registrationForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const semester = document.getElementById("semester").value;
      const selectedCourses = Array.from(checkboxes).filter(cb => cb.checked);

      if (selectedCourses.length === 0) {
        alert("Please select at least one course.");
        return;
      }

      if (!semester) {
        alert("Please select a semester.");
        return;
      }

      // Submit form
      this.submit();
    });
  </script>

</body>
</html>
