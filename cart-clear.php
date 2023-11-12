<?php
/**
 * Copyright Le Minh Hoa
 *
 * Trang xóa dữ liệu giỏ hàng
 */
// Cấu hình hệ thống
include_once 'configs.php';

// Thư viện hàm
include_once 'cart-session.php';

cartClear();

echo 'Giỏ hàng đã được xóa';