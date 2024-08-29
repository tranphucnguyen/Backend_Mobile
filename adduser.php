<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy dữ liệu từ yêu cầu POST
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['username']) && isset($data['email']) && isset($data['location'])) {
    $username = $conn->real_escape_string($data['username']);
    $email = $conn->real_escape_string($data['email']);
    $location = $conn->real_escape_string($data['location']);

    // Thực hiện truy vấn thêm dữ liệu
    $sql = "INSERT INTO teacher (name, email, location) VALUES ('$username', '$email', '$location')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "User added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

$conn->close();