<?php
// Gọi tệp kết nối cơ sở dữ liệu
include 'Data_connect.php';

// Kiểm tra nếu nhận được ID sách để xóa
if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']);

    // Truy vấn xóa sách
    $sql = "DELETE FROM ThongTinSach WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        $success_message = "Xóa sách thành công!";
        echo "<script>
                alert('$success_message');
                window.location.href = 'index.php';
              </script>";
    } else {
        $error_message = "Lỗi: " . $stmt->error;
        echo "<script>
                alert('$error_message');
                window.history.back();
              </script>";
    }
    $stmt->close();
} else {
    echo "<script>
            alert('Không tìm thấy ID sách để xóa.');
            window.history.back();
          </script>";
}

// Đóng kết nối
$conn->close();
?>
