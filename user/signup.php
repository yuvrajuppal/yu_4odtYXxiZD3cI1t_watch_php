<?php

include '../connection.php';


if (
    isset($_POST['user_id'], $_POST['user_name'], $_POST['user_email'], $_POST['user_password']) &&
    !empty($_POST['user_id']) && !empty($_POST['user_name']) && !empty($_POST['user_email']) && !empty($_POST['user_password'])
) {
    if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array("signup" => false, "message" => "Invalid email format."));
        exit();
    }

    $userpassword = password_hash($_POST['user_password'], PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (user_id, user_name, user_email, user_password) VALUES (?, ?, ?, ?)");

        $stmt->bind_param("ssss", $_POST['user_id'], $_POST['user_name'], $_POST['user_email'], $userpassword);

        if ($stmt->execute()) {
            echo json_encode(array("signup" => true));
        } else {
            echo json_encode(array("signup" => false, "message" => "Failed to insert data."));
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(array("signup" => false, "message" => "An error occurred.", "error" => $e->getMessage()));
    }
} else {
    echo json_encode(array("signup" => false, "message" => "Required fields are missing or empty."));
}
