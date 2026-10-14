<?php
include 'connect.php';

if (isset($_GET['keyword'])) {
    $tu_khoa = $conn->real_escape_string($_GET['keyword']);
    
    if (!empty($tu_khoa)) {
        $sql = "SELECT * FROM SanPham WHERE TenSanPham LIKE '%$tu_khoa%' OR ThuongHieu LIKE '%$tu_khoa%' LIMIT 5";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $anh = 'images/' . $row['HinhAnh'];
                if (!file_exists($anh) || empty($row['HinhAnh'])) {
                    $anh = 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=100&h=100&fit=crop';
                }
                
                // In ra từng dòng gợi ý gồm Ảnh, Tên và Giá
                echo '<a href="chitiet.php?id='.$row['ID_SanPham'].'" class="d-flex align-items-center text-decoration-none p-2 border-bottom suggestion-item" style="color: #333; background: #fff;">
                        <img src="'.$anh.'" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px;" class="me-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fs-6 fw-bold text-dark">'.$row['TenSanPham'].'</h6>
                            <small class="text-danger fw-bold">'.number_format($row['Gia'], 0, ',', '.').' đ</small>
                        </div>
                      </a>';
            }
        } else {
            echo '<div class="p-3 text-muted text-center">Không tìm thấy sản phẩm phù hợp!</div>';
        }
    }
}
?>