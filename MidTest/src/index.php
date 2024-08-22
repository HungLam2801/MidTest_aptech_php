<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Danh Sách Sách</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        h1 {
            margin-top: 20px; /* Căn giữa tiêu đề */
            text-align: center;
            color: blue;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 0 4px;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

    <!-- Thanh điều hướng -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light bg-dark p-3">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Lam Xuan Hung</a>
            <button  class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span style="background-color:white"  class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="addBook.php">Thêm Sách</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="addAuthor.php">Tác Giả</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="addCategory.php">Thể Loại</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort by
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="index.php?sort=high_to_low">High to Low</a></li>
                            <li><a class="dropdown-item" href="index.php?sort=low_to_high">Low to High</a></li>
                            <li><a class="dropdown-item" href="index.php?sort=author_name">Author Name</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <form class="d-flex" method="GET" action="index.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </nav>

    <h1>Library Management</h1>
    <!-- Nội dung chính -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <table class="table table-dark table-hover" style="font-size: x-large;">
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Thể loại</th>
                        <th>Nhà xuất bản</th>
                        <th>Năm xuất bản</th>
                        <th>Số lượng</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                    <?php
                    include 'Data_connect.php'; // Kết nối cơ sở dữ liệu

                    // Xác định số trang và trang hiện tại
                    $items_per_page = 5;
                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                    $start = ($page - 1) * $items_per_page;

                    // Xác định tiêu chí sắp xếp
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'high_to_low';
                    $sort_sql = '';

                    // Xử lý tiêu chí sắp xếp
                    switch ($sort) {
                        case 'low_to_high':
                            $sort_sql = 'ORDER BY ThongTinSach.publish_year ASC';
                            break;
                        case 'high_to_low':
                            $sort_sql = 'ORDER BY ThongTinSach.publish_year DESC';
                            break;
                        case 'author_name':
                            $sort_sql = 'ORDER BY TacGia.author_name ASC';
                            break;
                        default:
                            $sort_sql = 'ORDER BY ThongTinSach.publish_year DESC'; // Sắp xếp mặc định
                            break;
                    }

                    // Xử lý tìm kiếm
                    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                    $search_sql = $search ? "AND ThongTinSach.title LIKE '%$search%'" : '';

                    // Lấy tổng số mục
                    $total_sql = "SELECT COUNT(*) AS total FROM ThongTinSach WHERE 1 $search_sql";
                    $total_result = $conn->query($total_sql);
                    $total_row = $total_result->fetch_assoc();
                    $total_items = $total_row['total'];
                    $total_pages = ceil($total_items / $items_per_page);

                    // Lấy dữ liệu cho trang hiện tại
                    $sql = "SELECT ThongTinSach.id, ThongTinSach.title, TacGia.author_name, TheLoaiSach.category_name, ThongTinSach.publisher, ThongTinSach.publish_year, ThongTinSach.quantity
                            FROM ThongTinSach
                            JOIN TacGia ON ThongTinSach.author_id = TacGia.id
                            JOIN TheLoaiSach ON ThongTinSach.category_id = TheLoaiSach.id
                            WHERE 1 $search_sql
                            $sort_sql
                            LIMIT $start, $items_per_page";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["title"] . "</td>";
                            echo "<td>" . $row["author_name"] . "</td>";
                            echo "<td>" . $row["category_name"] . "</td>";
                            echo "<td>" . $row["publisher"] . "</td>";
                            echo "<td>" . $row["publish_year"] . "</td>";
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td><a href='editBook.php?id=" . $row["id"] . "' class='btn btn-warning'>Sửa</a></td>";
                            echo "<td><a href='deleteBook.php?id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Không có dữ liệu</td></tr>";
                    }

                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="pagination justify-content-center">
                    <?php
                    if ($total_pages > 1) {
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = $i == $page ? 'active' : '';
                            echo "<a class='$active' href='index.php?page=$i&search=" . urlencode($search) . "&sort=$sort'>$i</a>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
