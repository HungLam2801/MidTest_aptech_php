<?php
// Kết nối cơ sở dữ liệu
include 'Data_connect.php';

// Xử lý xóa tác giả
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Kiểm tra có sách nào liên kết với tác giả này không
    $check_sql = "SELECT COUNT(*) AS count FROM ThongTinSach WHERE author_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        $error_message = "Không thể xóa tác giả này vì vẫn còn sách liên kết.";
    } else {
        // Xóa tác giả nếu không có sách liên kết
        $delete_sql = "DELETE FROM TacGia WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success_message = "Xóa tác giả thành công!";
        } else {
            $error_message = "Lỗi: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Xử lý thêm tác giả
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $author_name = $_POST['author_name'];

    $sql = "INSERT INTO TacGia (author_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $author_name);

    if ($stmt->execute()) {
        $success_message = "Thêm tác giả thành công!";
    } else {
        $error_message = "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}

// Lấy danh sách tác giả
$sql = "SELECT * FROM TacGia";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Quản lý Tác Giả</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Quản lý Tác Giả</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="mb-4">
            <h2>Thêm Tác Giả Mới</h2>
            <form method="post" action="addAuthor.php">
                <div class="mb-3">
                    <label for="author_name" class="form-label">Tên tác giả:</label>
                    <input type="text" id="author_name" name="author_name" class="form-control" required>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Thêm Tác Giả</button>
            </form>
        </div>

        <h2>Danh Sách Tác Giả</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Tên Tác Giả</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- <td><?php echo $row['id']; ?></td> -->
                        <td><?php echo $row['author_name']; ?></td>
                        <td>
                            <!-- Xóa tác giả -->
                            <a href="addAuthor.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa tác giả này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
