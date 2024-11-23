<?php 

include "../connection.php";

$userEmail = isset($_POST['user_email']) ? $_POST['user_email'] : '';

$stmt = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$resultQuery = $stmt->get_result();

if ($resultQuery->num_rows > 0) {
    echo json_encode(array('emailfound' => true));
} else {
    echo json_encode(array("emailfound" => false));
}

$stmt->close();

?>
