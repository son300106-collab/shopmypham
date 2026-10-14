<?php
include 'header.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] !== 'Admin') {
    echo "<script>alert('⛔ Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit;
}

// ==========================================
// XỬ LÝ CẬP NHẬT QUYỀN (THĂNG/HẠ QUYỀN)
// ==========================================
if (isset($_GET['doiquyen']) && isset($_GET['id_user'])) {
    $id_doiquyen = (int)$_GET['id_user'];
    
    // BẢO VỆ BOSS: Không cho phép thao tác đổi quyền tài khoản ID = 1
    if ($id_doiquyen === 1) {
        echo "<script>alert('⛔ Không thể thay đổi quyền của Boss!'); window.location.href='admin_taikhoan.php';</script>";
        exit;
    }

    $quyen_moi = $conn->real_escape_string($_GET['doiquyen']); // Nhận giá trị 'Admin' hoặc 'KhachHang'
    
    // Cập nhật quyền mới vào Database
    $sql_update_quyen = "UPDATE NguoiDung SET VaiTro = '$quyen_moi' WHERE ID_User = $id_doiquyen";
    
    if ($conn->query($sql_update_quyen) === TRUE) {
        // Cập nhật thành công, tải lại trang để thấy thay đổi
        echo "<script>window.location.href='admin_taikhoan.php';</script>";
        exit;
    } else {
        echo "<script>alert('Lỗi khi cập nhật quyền!');</script>";
    }
}

// ==========================================
// XỬ LÝ KHÓA TÀI KHOẢN (NẾU CÓ)
// ==========================================
if (isset($_GET['action']) && $_GET['action'] == 'khoa' && isset($_GET['id_user'])) {
    $id_khoa = (int)$_GET['id_user'];
    
    // BẢO VỆ BOSS: Không cho phép khóa ID = 1
    if ($id_khoa === 1) {
        echo "<script>alert('⛔ Dám khóa tài khoản của Boss à? Không thể thực hiện!'); window.location.href='admin_taikhoan.php';</script>";
        exit;
    }
    
    // Code xử lý khóa tài khoản của đại ca (nếu có) viết ở đây
    // ...
}

// Xử lý tìm kiếm
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}
?>

<div class="container my-5 pt-4 pb-5" style="min-height: 60vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="brand-text" style="color: #d9534f; font-weight: 500;">Tài Khoản Hệ Thống</h2>
        
        <!-- THÊM Ô TÌM KIẾM TÀI KHOẢN CÓ GỢI Ý -->
        <?php
        $sg_tk = $conn->query("SELECT DISTINCT HoTen, Email FROM NguoiDung");
        ?>
        <datalist id="suggestTK">
            <?php 
            if($sg_tk) {
                while($r = $sg_tk->fetch_assoc()) {
                    echo "<option value='".htmlspecialchars($r['HoTen'])."'>"; 
                    echo "<option value='".htmlspecialchars($r['Email'])."'>"; 
                }
            }
            ?>
        </datalist>

        <form action="admin_taikhoan.php" method="GET" class="d-flex w-25">
            <input type="text" name="search" list="suggestTK" autocomplete="off" class="form-control me-2" placeholder="Tìm tên KH hoặc Email..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-outline-dark">Tìm</button>
        </form>

        <a href="admin.php" class="btn btn-outline-dark">← Về Dashboard</a>
    </div>

    <div class="table-responsive shadow-sm bg-white p-3 rounded border">
        <table class="table table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th class="text-start">Họ và Tên</th>
                    <th>Email Đăng Nhập</th>
                    <th>Phân Quyền</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Áp dụng điều kiện tìm kiếm vào SQL
                $sql = "SELECT * FROM NguoiDung ";
                if (!empty($search_query)) {
                    $sql .= " WHERE HoTen LIKE '%$search_query%' OR Email LIKE '%$search_query%' ";
                }
                $sql .= " ORDER BY ID_User ASC"; // Cho Boss hiển thị lên đầu

                $result = $conn->query($sql);
                if($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $is_boss = ($row['ID_User'] == 1);
                        $is_admin = ($row['VaiTro'] == 'Admin');
                        
                        // Cài đặt nhãn quyền hạn
                        if ($is_boss) {
                            $role_badge = 'bg-dark';
                            $role_text = 'Boss';
                        } else {
                            $role_badge = $is_admin ? 'bg-danger' : 'bg-primary';
                            $role_text = $row['VaiTro'];
                        }
                ?>
                <tr>
                    <td class="fw-bold"><?php echo $row['ID_User']; ?></td>
                    <td class="text-start fw-bold"><?php echo $row['HoTen']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><span class="badge <?php echo $role_badge; ?> px-3 py-1"><?php echo $role_text; ?></span></td>
                    <td>
                        <?php if ($is_boss): ?>
                            <!-- Hiển thị text báo hiệu đây là Boss thay vì nút -->
                            <span class="text-muted fw-bold fst-italic" style="font-size: 0.85rem;">Bất khả xâm phạm</span>
                        <?php else: ?>
                            <!-- Nút Thăng / Hạ Quyền -->
                            <?php if (!$is_admin): ?>
                                <!-- Đang là Khách Hàng -> Nút nâng cấp thành Admin -->
                                <a href="?doiquyen=Admin&id_user=<?php echo $row['ID_User']; ?>" class="btn btn-sm btn-success text-white px-2" onclick="return confirm('Cấp quyền Admin cho <?php echo $row['HoTen']; ?>?');">
                                    Cấp Admin
                                </a>
                            <?php else: ?>
                                <!-- Đang là Admin -> Nút hạ cấp xuống Khách Hàng -->
                                <a href="?doiquyen=KhachHang&id_user=<?php echo $row['ID_User']; ?>" class="btn btn-sm btn-warning text-dark px-2" onclick="return confirm('Hạ quyền Admin của <?php echo $row['HoTen']; ?>?');">
                                    Hạ Quyền
                                </a>
                            <?php endif; ?>

                            <!-- Nút Khóa -->
                            <a href="?action=khoa&id_user=<?php echo $row['ID_User']; ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Khóa tài khoản này?');">Khóa</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-4 text-danger fw-bold'>Không tìm thấy tài khoản nào phù hợp!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>