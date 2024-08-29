<?php
header("Access-Control-Allow-Origin: *"); // Cho phép tất cả các nguồn truy cập
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



// Kết nối tới cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydatabase"; // Thay đổi tên cơ sở dữ liệu thành tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID của giáo viên từ tham số URL
if (isset($_GET['id'])) {
    $teacherId = intval($_GET['id']);

    // Truy vấn thông tin chi tiết giáo viên từ cơ sở dữ liệu
    $sql = "SELECT id, name, email, location FROM teacher WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teacherId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Chuyển đổi dữ liệu thành JSON
        $teacherDetails = $result->fetch_assoc();
        echo json_encode($teacherDetails);
    } else {
        echo json_encode(["error" => "Teacher not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request"]);
}

$conn->close();