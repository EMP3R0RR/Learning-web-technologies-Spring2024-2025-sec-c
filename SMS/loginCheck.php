<?php
// // $servername = "localhost";
// // $username = "root"; 
// // $password = ""; 
// // $dbname = "school_db";
// // $port = 3307; 


// // $conn = mysqli_connect($servername, $username, $password, $dbname, $port);


// // if (!$conn) {
// //     $error_message = "Cannot connect to database: " . mysqli_connect_error();
// // } 

// 
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error_message)) {
//    
//     $role = isset($_POST['role']) ? trim($_POST['role']) : '';
//     $email = isset($_POST['email']) ? trim($_POST['email']) : '';
//     $password = isset($_POST['password']) ? trim($_POST['password']) : '';

//    
//     $valid_roles = ['student', 'teacher', 'admin'];
//     if (empty($role) || !in_array($role, $valid_roles)) {
//         $error_message = "Please select a valid role";
//     } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $error_message = "Please enter a valid email";
//     } elseif (empty($password)) {
//         $error_message = "Please enter a password";
//     } else {
//          
//         $query = "SELECT id, role, email, password FROM users WHERE email = ? AND role = ?";
//         $stmt = mysqli_prepare($conn, $query);
//         if ($stmt) {
//             mysqli_stmt_bind_param($stmt, "ss", $email, $role);
//             mysqli_stmt_execute($stmt);
//             $result = mysqli_stmt_get_result($stmt);
//             $user = mysqli_fetch_assoc($result);
//             mysqli_stmt_close($stmt);

//             if ($user && password_verify($password, $user['password'])) {
//                 // Credentials are correct, start session
//                 $_SESSION['user_id'] = $user['id'];
//                 $_SESSION['role'] = $user['role'];
//                 $_SESSION['email'] = $user['email'];

//                 
//                 session_regenerate_id(true);

//                 
//                 $role_pages = [
//                     'student' => 'StudentProfile.php',
//                     'teacher' => 'teacherprofile.php',
//                     'admin' => 'admindashboard.php'
//                 ];
//                 header("Location: " . $role_pages[$role]);
//                 exit();
//             } else {
//                 $error_message = "Invalid email, password, or role";
//             }
//         } else {
//             $error_message = "Database query error: " . mysqli_error($conn);
//         }
//     }
// }

// 
// if ($conn) {
//     mysqli_close($conn);
// }

session_start();


$error_message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    

    
    $valid_roles = ['student', 'teacher', 'admin'];
    if (empty($role) || !in_array($role, $valid_roles)) {
        $error_message = "Please select a valid role";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email";
    } else {
        
        
        $_SESSION['user_id'] = 1; 
        $_SESSION['role'] = $role;
        $_SESSION['email'] = $email;
        $_SESSION['last_activity'] = time(); 

        
        session_regenerate_id(true);

        
        $role_pages = [
            'student' => 'StudentProfile.php',
            'teacher' => 'teacherprofile.php',
            'admin' => 'admindashboard.php'
        ];
        header("Location: " . $role_pages[$role] . "?welcome=Login successful");
        exit();
    }
}
?>
