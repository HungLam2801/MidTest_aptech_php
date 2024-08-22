<?php
$mysqli = new mysqli("localhost", "root", "", "Book");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $publish_year = $_POST['publish_year'];
    $quantity = $_POST['quantity'];

    if (!empty($title) && !empty($author_id) && !empty($category_id) && !empty($publisher) && !empty($publish_year) && !empty($quantity)) {
        // Kiểm tra xem tiêu đề đã tồn tại chưa
        $check_stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM ThongTinSach WHERE title = ?");
        $check_stmt->bind_param("s", $title);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $count_row = $check_result->fetch_assoc();

        if ($count_row['count'] > 0) {
            $error_message = "Tiêu đề sách đã tồn tại. Vui lòng chọn một tiêu đề khác.";
        } else {
            // Thêm sách vào cơ sở dữ liệu
            $stmt = $mysqli->prepare("INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siissi", $title, $author_id, $category_id, $publisher, $publish_year, $quantity);
            $stmt->execute();
            $success_message = "Bạn đã thêm sách thành công";
        }
    } else {
        $error_message = "Vui lòng điền đầy đủ thông tin";
    }
}

$authors = $mysqli->query("SELECT * FROM TacGia");
$categories = $mysqli->query("SELECT * FROM TheLoaiSach");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Thêm Sách</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Thêm Sách Mới</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post" action="addBook.php">
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="author_id" class="form-label">Tác giả:</label>
                <select id="author_id" name="author_id" class="form-select" required>
                    <?php
                    if ($authors->num_rows > 0) {
                        while($row = $authors->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["author_name"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Thể loại:</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <?php
                    if ($categories->num_rows > 0) {
                        while($row = $categories->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["category_name"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="publisher" class="form-label">Nhà xuất bản:</label>
                <input type="text" id="publisher" name="publisher" class="form-control">
            </div>

            <div class="mb-3">
                <label for="publish_year" class="form-label">Năm xuất bản:</label>
                <input type="number" id="publish_year" name="publish_year" class="form-control" min="0" required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" class="form-control" min="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Thêm Sách</button>
        </form>

        <br>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>

    <!-- Thư viện Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Đóng kết nối
$mysqli->close();
?>
