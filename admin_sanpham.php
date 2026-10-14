<?php
include 'header.php';
if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] !== 'Admin') {
    echo "<script>alert('⛔ Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit;
}

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}

// ==========================================
// XÓA SẢN PHẨM
// ==========================================
if (isset($_GET['delete_id'])) {
    $id_del = $_GET['delete_id'];
    $sql_del = "DELETE FROM SanPham WHERE ID_SanPham = '$id_del'";
    if ($conn->query($sql_del) === TRUE) {
        echo "<script>alert('✅ Đã xóa sản phẩm thành công!'); window.location='admin_sanpham.php';</script>";
    } else {
        echo "<script>alert('⛔ Không thể xóa! Sản phẩm này đã nằm trong hóa đơn của khách.'); window.location='admin_sanpham.php';</script>";
    }
}

// ==========================================
// THÊM SẢN PHẨM (CÓ UPLOAD ẢNH TỪ MÁY)
// ==========================================
if (isset($_POST['them_sp'])) {
    $ten = $conn->real_escape_string($_POST['ten']);
    $gianhap = $_POST['gianhap'];
    $gia = $_POST['gia'];
    $tonkho = $_POST['tonkho'];
    $loaida = $conn->real_escape_string($_POST['loaida']);
    $thuonghieu = $conn->real_escape_string($_POST['thuonghieu']);
    $danhmuc = $_POST['danhmuc'];
    $mota = $conn->real_escape_string($_POST['mota']);

    $hinhanh = "";
    if(isset($_FILES['hinhanh_upload']) && $_FILES['hinhanh_upload']['error'] == 0) {
        $hinhanh = time() . '_' . basename($_FILES['hinhanh_upload']['name']);
        $target_dir = "images/";
        $target_file = $target_dir . $hinhanh;
        move_uploaded_file($_FILES['hinhanh_upload']['tmp_name'], $target_file);
    }

    $conn->query("INSERT INTO SanPham (TenSanPham, GiaNhap, Gia, SoLuongTon, LoaiDa, ThuongHieu, ID_DanhMuc, HinhAnh, MoTa) VALUES ('$ten', '$gianhap', '$gia', '$tonkho', '$loaida', '$thuonghieu', '$danhmuc', '$hinhanh', '$mota')");
    echo "<script>window.location='admin_sanpham.php';</script>";
}

// ==========================================
// SỬA SẢN PHẨM (CÓ CẬP NHẬT ẢNH)
// ==========================================
if (isset($_POST['sua_sp'])) {
    $id_sua = $_POST['id_sua'];
    $ten = $conn->real_escape_string($_POST['ten']);
    $gianhap = $_POST['gianhap'];
    $gia = $_POST['gia'];
    $tonkho = $_POST['tonkho'];
    $loaida = $conn->real_escape_string($_POST['loaida']);
    $thuonghieu = $conn->real_escape_string($_POST['thuonghieu']);
    $danhmuc = $_POST['danhmuc'];
    $mota = $conn->real_escape_string($_POST['mota']);
    
    $hinhanh = $conn->real_escape_string($_POST['hinhanh_cu']); 

    if(isset($_FILES['hinhanh_upload']) && $_FILES['hinhanh_upload']['error'] == 0) {
        $hinhanh_moi = time() . '_' . basename($_FILES['hinhanh_upload']['name']);
        $target_file = "images/" . $hinhanh_moi;
        if(move_uploaded_file($_FILES['hinhanh_upload']['tmp_name'], $target_file)) {
            $hinhanh = $hinhanh_moi; 
        }
    }

    $conn->query("UPDATE SanPham SET TenSanPham='$ten', GiaNhap='$gianhap', Gia='$gia', SoLuongTon='$tonkho', LoaiDa='$loaida', ThuongHieu='$thuonghieu', ID_DanhMuc='$danhmuc', HinhAnh='$hinhanh', MoTa='$mota' WHERE ID_SanPham='$id_sua'");
    echo "<script>window.location='admin_sanpham.php';</script>";
}

$danh_muc_options = "";
$res_dm = $conn->query("SELECT * FROM DanhMuc");
while($row_dm = $res_dm->fetch_assoc()) {
    $danh_muc_options .= "<option value='".$row_dm['ID_DanhMuc']."'>".$row_dm['TenDanhMuc']."</option>";
}
?>

<div class="container-fluid px-4 my-5 pt-4 pb-5" style="min-height: 60vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="brand-text" style="color: #d9534f; font-weight: 500;">Quản Lý Sản Phẩm</h2>
        
        <div class="position-relative w-25">
            <form action="admin_sanpham.php" method="GET" class="d-flex">
                <input type="text" name="search" id="adminInputSearch" autocomplete="off" class="form-control me-2" placeholder="Tìm tên SP, Thương hiệu..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="btn btn-outline-dark">Tìm</button>
            </form>
            <div id="adminSearchBox" class="position-absolute shadow rounded bg-white overflow-hidden border w-100 mt-1" style="z-index: 999; max-height: 350px; overflow-y: auto; display: none;"></div>
        </div>

        <div>
            <button class="btn btn-success me-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalThem">+ Thêm Sản Phẩm</button>
            <a href="admin.php" class="btn btn-outline-dark">← Về Dashboard</a>
        </div>
    </div>

    <div class="table-responsive shadow-sm bg-white p-3 rounded border">
        <table class="table table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Hình Ảnh</th>
                    <th class="text-start">Tên Sản Phẩm</th>
                    <th>Thương Hiệu</th>
                    <th>Giá Bán</th>
                    <th>Tồn Kho</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM SanPham ";
                if (!empty($search_query)) {
                    $sql .= " WHERE TenSanPham LIKE '%$search_query%' OR ThuongHieu LIKE '%$search_query%' ";
                }
                $sql .= " ORDER BY ID_SanPham DESC";

                $result = $conn->query($sql);
                if($result && $result->num_rows > 0) {
                    $stt = $result->num_rows; 
                    while($row = $result->fetch_assoc()) {
                        $anh = 'images/' . $row['HinhAnh'];
                        if(!file_exists($anh) || empty($row['HinhAnh'])) $anh = 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=100&h=100&fit=crop';
                        
                        $tonKho = $row['SoLuongTon'];
                        $badgeTonKho = ($tonKho > 10) ? 'bg-success' : 'bg-danger';
                        
                        // BƯỚC 1: Mã hóa các ký tự đặc biệt (' ", <, >)
                        $ten_escaped = htmlspecialchars($row['TenSanPham'], ENT_QUOTES, 'UTF-8');
                        $loaida_escaped = htmlspecialchars($row['LoaiDa'], ENT_QUOTES, 'UTF-8');
                        $thuonghieu_escaped = htmlspecialchars($row['ThuongHieu'] ?? '', ENT_QUOTES, 'UTF-8');
                        $hinhanh_escaped = htmlspecialchars($row['HinhAnh'], ENT_QUOTES, 'UTF-8');
                        $mota_escaped = htmlspecialchars($row['MoTa'] ?? '', ENT_QUOTES, 'UTF-8');

                        // BƯỚC 2: TRỊ LỖI XUỐNG DÒNG (THAY \n BẰNG KÝ TỰ \\n ĐỂ JS HIỂU ĐƯỢC)
                        $ten_escaped = str_replace(array("\r\n", "\r", "\n"), "\\n", $ten_escaped);
                        $mota_escaped = str_replace(array("\r\n", "\r", "\n"), "\\n", $mota_escaped);
                ?>
                <tr>
                    <td class="fw-bold"><?php echo $stt; ?></td> 
                    <td><img src="<?php echo $anh; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
                    <td class="text-start fw-bold"><?php echo $row['TenSanPham']; ?></td>
                    <td><span class="badge bg-secondary"><?php echo $row['ThuongHieu'] ?? 'Khác'; ?></span></td>
                    <td style="color: #9a7b4f; font-weight: bold;"><?php echo number_format($row['Gia'], 0, ',', '.'); ?> đ</td>
                    <td><span class="badge fs-6 <?php echo $badgeTonKho; ?>"><?php echo $tonKho; ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="suaSanPham(
                            '<?php echo $row['ID_SanPham']; ?>', '<?php echo $ten_escaped; ?>',
                            '<?php echo $row['GiaNhap']; ?>', '<?php echo $row['Gia']; ?>', '<?php echo $row['SoLuongTon']; ?>',
                            '<?php echo $loaida_escaped; ?>', '<?php echo $thuonghieu_escaped; ?>',
                            '<?php echo $row['ID_DanhMuc']; ?>', '<?php echo $hinhanh_escaped; ?>',
                            '<?php echo $mota_escaped; ?>'
                        )">Sửa</button>
                        <a href="admin_sanpham.php?delete_id=<?php echo $row['ID_SanPham']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
                <?php 
                        $stt--; 
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center py-4 text-danger fw-bold'>Không tìm thấy sản phẩm nào!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ==========================================
     MODAL THÊM SẢN PHẨM 
========================================== -->
<div class="modal fade" id="modalThem" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Thêm Sản Phẩm Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="admin_sanpham.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body row">
                    <div class="col-md-12 mb-3"><label class="fw-bold">Tên Sản Phẩm:</label><input type="text" name="ten" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Giá Nhập:</label><input type="number" name="gianhap" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Giá Bán:</label><input type="number" name="gia" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Số Lượng Tồn:</label><input type="number" name="tonkho" class="form-control" required></div>
                    
                    <div class="col-md-4 mb-3"><label class="fw-bold">Thương Hiệu:</label><select name="thuonghieu" class="form-select"><option value="Cocoon">Cocoon</option><option value="Dr.Ceutics">Dr.Ceutics</option><option value="Emmié">Emmié</option><option value="Skin Heritage">Skin Heritage</option><option value="Khác">Khác...</option></select></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Loại Da:</label><input type="text" name="loaida" class="form-control"></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Danh Mục:</label><select name="danhmuc" class="form-select"><?php echo $danh_muc_options; ?></select></div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-danger">Tải Hình Ảnh Lên:</label>
                        <input type="file" name="hinhanh_upload" class="form-control" accept="image/*" required>
                    </div>
                    
                    <div class="col-md-12 mb-3"><label class="fw-bold">Mô tả:</label><textarea name="mota" class="form-control" rows="3"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="them_sp" class="btn btn-success">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL SỬA SẢN PHẨM 
========================================== -->
<div class="modal fade" id="modalSua" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Sửa Thông Tin Sản Phẩm</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="admin_sanpham.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body row">
                    <input type="hidden" name="id_sua" id="edit_id">
                    <input type="hidden" name="hinhanh_cu" id="edit_hinhanh_cu">

                    <div class="col-md-12 mb-3"><label class="fw-bold">Tên Sản Phẩm:</label><input type="text" name="ten" id="edit_ten" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Giá Nhập:</label><input type="number" name="gianhap" id="edit_gianhap" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Giá Bán:</label><input type="number" name="gia" id="edit_gia" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Số Lượng Tồn:</label><input type="number" name="tonkho" id="edit_tonkho" class="form-control" required></div>
                    
                    <div class="col-md-4 mb-3"><label class="fw-bold">Thương Hiệu:</label><select name="thuonghieu" id="edit_thuonghieu" class="form-select"><option value="Cocoon">Cocoon</option><option value="Dr.Ceutics">Dr.Ceutics</option><option value="Emmié">Emmié</option><option value="Skin Heritage">Skin Heritage</option><option value="Khác">Khác...</option></select></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Loại Da:</label><input type="text" name="loaida" id="edit_loaida" class="form-control"></div>
                    <div class="col-md-4 mb-3"><label class="fw-bold">Danh Mục:</label><select name="danhmuc" id="edit_danhmuc" class="form-select"><?php echo $danh_muc_options; ?></select></div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-primary">Thay đổi ảnh (Bỏ trống nếu muốn giữ nguyên ảnh cũ):</label>
                        <input type="file" name="hinhanh_upload" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="col-md-12 mb-3"><label class="fw-bold">Mô tả:</label><textarea name="mota" id="edit_mota" class="form-control" rows="3"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="sua_sp" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function suaSanPham(id, ten, gianhap, gia, tonkho, loaida, thuonghieu, danhmuc, hinhanh, mota) {
    document.getElementById('edit_id').value = id; 
    
    // Xử lý lại các dấu xuống dòng và ký tự HTML
    let parser = new DOMParser();
    
    // Đổ dữ liệu vào input tên
    let ten_sach = parser.parseFromString(ten, 'text/html').body.textContent;
    document.getElementById('edit_ten').value = ten_sach; 
    
    document.getElementById('edit_gianhap').value = gianhap; 
    document.getElementById('edit_gia').value = gia; 
    document.getElementById('edit_tonkho').value = tonkho; 
    
    document.getElementById('edit_loaida').value = parser.parseFromString(loaida, 'text/html').body.textContent; 
    document.getElementById('edit_thuonghieu').value = parser.parseFromString(thuonghieu, 'text/html').body.textContent; 
    document.getElementById('edit_danhmuc').value = danhmuc; 
    document.getElementById('edit_hinhanh_cu').value = parser.parseFromString(hinhanh, 'text/html').body.textContent; 
    
    // Đổ dữ liệu vào textarea mô tả
    let mota_sach = parser.parseFromString(mota, 'text/html').body.textContent;
    document.getElementById('edit_mota').value = mota_sach;
    
    var myModal = new bootstrap.Modal(document.getElementById('modalSua')); 
    myModal.show();
}

// SCRIPT AJAX TÌM KIẾM
document.addEventListener("DOMContentLoaded", function() {
    const inputSearch = document.getElementById('adminInputSearch');
    const suggestBox = document.getElementById('adminSearchBox');

    if(inputSearch) {
        inputSearch.addEventListener('keyup', function() {
            let keyword = this.value.trim();
            if (keyword.length > 0) {
                fetch('ajax_timkiem.php?keyword=' + encodeURIComponent(keyword))
                    .then(response => response.text())
                    .then(data => {
                        suggestBox.innerHTML = data;
                        suggestBox.style.display = 'block';
                    });
            } else {
                suggestBox.innerHTML = '';
                suggestBox.style.display = 'none';
            }
        });
    }
});
</script>

<style>
.suggestion-item:hover { background-color: #f8f9fa !important; }
</style>

<?php include 'footer.php'; ?>