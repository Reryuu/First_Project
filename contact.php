<?php 
/**
 * Copyright Le Minh Hoa
 *
 * Trang Liên Hệ (góp ý, phản hồi, khiếu nại, hẹn gặp)
 */
// Cấu hình hệ thống
include_once 'configs.php';

// Thư viện hàm
include_once 'lib/table/table.contact.php';

//if ( $_SERVER['REQUEST_METHOD'] == "POST" && validateContact())  
if ( $_SERVER['REQUEST_METHOD'] == "POST")  
{ 
	
	$contact_id = contactAdd($_POST);
	
	// Thông báo sửa thành công
	$web_content = "view/view-contact-success.php";
	
}
else 
{
	$web_content = "view/view-contact.php";
}

// Nội dung riêng của trang...
$web_title = "Liên Hệ";

// ...được đặt vào bố cục chung của toàn site.
include_once $web_layout;

