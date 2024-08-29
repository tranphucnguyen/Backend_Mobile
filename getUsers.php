<?php
header('Content-Type: application/json');

// Thay đổi các thông số kết nối với cơ sở dữ liệu của bạn
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Truy vấn cơ sở dữ liệu
$sql = "SELECT id, name, email FROM teacher";
$result = $conn->query($sql);

// Kiểm tra và trả về dữ liệu dưới dạng JSON
if ($result->num_rows > 0) {
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode([]);
}

// Đóng kết nối
$conn->close();