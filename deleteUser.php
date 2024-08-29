<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Thông số kết nối với cơ sở dữ liệu của bạn
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Lấy ID người dùng từ yêu cầu POST
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra xem dữ liệu 'id' có tồn tại không
if (isset($data['id']) && is_numeric($data['id'])) {
    $id = $data['id'];

    // Xóa người dùng từ cơ sở dữ liệu
    $sql = "DELETE FROM teacher WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
        }

        // Đóng statement
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
}

// Đóng kết nối
$conn->close();