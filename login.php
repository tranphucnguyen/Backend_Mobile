<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Đọc và kiểm tra dữ liệu JSON
$data = json_decode(file_get_contents("php://input"));

if (is_null($data)) {
    echo json_encode(["status" => "error", "message" => "No data received"]);
    exit();
}

// Kiểm tra xem thuộc tính email và password có tồn tại không
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Invalid data received"]);
    exit();
}
$email = $conn->real_escape_string($data->email);
$password = $conn->real_escape_string($data->password);

$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Người dùng tồn tại
    echo json_encode(["status" => "success", "message" => "Login successful"]);
} else {
    // Người dùng không tồn tại
    echo json_encode(["status" => "error", "message" => "User not found"]);
}
// Tiếp tục xử lý logic đăng nhập...
$conn->close();