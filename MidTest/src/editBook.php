<?php
include 'Data_connect.php'; // Kết nối cơ sở dữ liệu

// Xử lý cập nhật sách
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $publish_year = $_POST['publish_year'];
    $quantity = $_POST['quantity'];

    // Cập nhật thông tin sách
    $update_sql = "UPDATE ThongTinSach SET title=?, author_id=?, category_id=?, publisher=?, publish_year=?, quantity=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("siissii", $title, $author_id, $category_id, $publisher, $publish_year, $quantity, $id);

    if ($stmt->execute()) {
        $success_message = "Cập nhật sách thành công!";
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
}

// Lấy thông tin sách để sửa
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM ThongTinSach WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
}

// Lấy danh sách tác giả và thể loại cho dropdown
$authors_sql = "SELECT id, author_name FROM TacGia";
$authors_result = $conn->query($authors_sql);

$categories_sql = "SELECT id, category_name FROM TheLoaiSach";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sửa Thông Tin Sách</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Sửa Thông Tin Sách</h1>

        <!-- Hiển thị thông báo nếu có -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post" action="editBook.php">
            <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu Đề:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo $book['title']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="author_id" class="form-label">Tác Giả:</label>
                <select id="author_id" name="author_id" class="form-select" required>
                    <?php while ($author = $authors_result->fetch_assoc()): ?>
                        <option value="<?php echo $author['id']; ?>" <?php echo $book['author_id'] == $author['id'] ? 'selected' : ''; ?>>
                            <?php echo $author['author_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Thể Loại:</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <?php while ($category = $categories_result->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $book['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo $category['category_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="publisher" class="form-label">Nhà Xuất Bản:</label>
                <input type="text" id="publisher" name="publisher" class="form-control" value="<?php echo $book['publisher']; ?>">
            </div>
            <div class="mb-3">
                <label for="publish_year" class="form-label">Năm Xuất Bản:</label>
                <input type="number" id="publish_year" name="publish_year" class="form-control" value="<?php echo $book['publish_year']; ?>">
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Số Lượng:</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo $book['quantity']; ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Cập Nhật</button>
        </form>

        <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
