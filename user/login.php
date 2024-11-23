<?php

include '../connection.php';

if (isset($_POST['user_email'], $_POST['user_password']) && 
    !empty($_POST['user_email']) && !empty($_POST['user_password'])) {

    if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array("login" => false, "message" => "Invalid email format."));
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT user_password FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $_POST['user_email']);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['user_password'];

            if (password_verify($_POST['user_password'], $hashed_password)) {
                echo json_encode(array("login" => true, "message" => "Login successful."));
            } else {
                echo json_encode(array("login" => false, "message" => "Invalid password."));
            }
        } else {
            echo json_encode(array("login" => false, "message" => "User not found."));
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(array("login" => false, "message" => "An error occurred.", "error" => $e->getMessage()));
    }
} else {
    echo json_encode(array("login" => false, "message" => "Required fields are missing or empty."));
}

?>
