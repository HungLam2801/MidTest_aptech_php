<?php
// Kết nối cơ sở dữ liệu
include 'Data_connect.php';

// Xử lý xóa thể loại
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Kiểm tra có sách nào liên kết với thể loại này không
    $check_sql = "SELECT COUNT(*) AS count FROM ThongTinSach WHERE category_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        $error_message = "Không thể xóa thể loại này vì vẫn còn sách liên kết.";
    } else {
        // Xóa thể loại nếu không có sách liên kết
        $delete_sql = "DELETE FROM TheLoaiSach WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success_message = "Xóa thể loại thành công!";
        } else {
            $error_message = "Lỗi: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Xử lý thêm thể loại
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $category_name = $_POST['category_name'];

    $sql = "INSERT INTO TheLoaiSach (category_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_name);

    if ($stmt->execute()) {
        $success_message = "Thêm thể loại thành công!";
    } else {
        $error_message = "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}

// Lấy danh sách thể loại
$sql = "SELECT * FROM TheLoaiSach";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Quản lý Thể Loại</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Quản lý Thể Loại</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="mb-4">
            <h2>Thêm Thể Loại Mới</h2>
            <form method="post" action="addCategory.php">
                <div class="mb-3">
                    <label for="category_name" class="form-label">Tên Thể Loại:</label>
                    <input type="text" id="category_name" name="category_name" class="form-control" required>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Thêm Thể Loại</button>
            </form>
        </div>

        <h2>Danh Sách Thể Loại</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tên Thể Loại</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['category_name']; ?></td>
                        <td>
                            <!-- Xóa thể loại -->
                            <a href="addCategory.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại này?');">Xóa</a>
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
