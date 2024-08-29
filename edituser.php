<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";  // Thay đổi theo cấu hình của bạn
$username = "root";         // Thay đổi theo cấu hình của bạn
$password = "";             // Thay đổi theo cấu hình của bạn
$dbname = "myDatabase";     // Thay đổi theo cấu hình của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

// Nhận dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

// Lấy dữ liệu hiện tại của người dùng
if (isset($data->userId)) {
    $userId = $conn->real_escape_string($data->userId);

    // Lấy thông tin người dùng hiện tại
    $currentDataQuery = "SELECT name, email, location FROM teacher WHERE id=$userId";
    $result = $conn->query($currentDataQuery);

    if ($result->num_rows > 0) {
        $currentData = $result->fetch_assoc();

        // Cập nhật các trường nếu có giá trị mới
        $username = isset($data->username) && !empty($data->username) ? $conn->real_escape_string($data->username) : $currentData['name'];
        $email = isset($data->email) && !empty($data->email) ? $conn->real_escape_string($data->email) : $currentData['email'];
        $location = isset($data->location) && !empty($data->location) ? $conn->real_escape_string($data->location) : $currentData['location'];

        // Cập nhật thông tin người dùng
        $sql = "UPDATE teacher SET name='$username', email='$email', location='$location' WHERE id=$userId";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "User updated successfully"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error updating user: " . $conn->error));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "User not found"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid input"));
}

// Đóng kết nối
$conn->close();