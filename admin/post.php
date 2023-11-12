<?php
/**
 * Copyright Le Minh Hoa
 *
 * Trang quản lý bài viết
 */
// Cấu hình hệ thống
include_once '../configs.php';

// Thư viện hàm
include_once '../lib/tool.image.php';
include_once '../lib/table/table.post.php';


/* Hiển Thị Danh Mục Bài Viết. (làm mẫu cho các phần khác)
- Kiểm tra đăng nhập và quyền
- Tạo điều hướng ruột bánh mỳ.
- Tạo các đường link liên quan đến các hành động Add, Delete, Copy, Repair.
- Truy vấn các bản ghi trong tầng cơ sở dữ liệu để gửi sang tầng giao diện html.
 (Có thể tinh chỉnh dữ liệu thô, thêm bớt các cột của bản ghi)
- Có thể thêm đường link Edit để khi bấm vào thì chỉnh sửa bản ghi theo id.
- Phân Trang.
- Gửi dữ liệu sang tầng giao diện.
- Hiển thị toàn trang (tựa đề, nội dung riêng, menu hiện thời, bố cục toàn trang).
 */
 
check_login();


// Bắt các tham số lọc kết quả tìm kiếm yêu cầu từ phía trình duyệt,
// các tham số này có thể nằm trong url hoặc form gửi lên.
// $_REQUEST có thể bắt các tham số theo cả 2 phương thức GET và POST.
$filter_title  = isset($_GET['filter_title'])  ? $_GET['filter_title']  : null;
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : null;

// (Bắt)Tiếp nhận các tham số 'cột sắp xếp', 'thứ tự sắp xếp', 'phân trang' được yêu cầu từ phía trình duyệt,
// các tham số này có thể nằm trong url hoặc form gửi lên.
// Các tham số này sẽ được dùng để nhúng vào các câu sql truy vấn,
// đồng thời cũng được gửi ngược sang view html để làm một số việc như là so sánh hoặc gán đường link vào tên cột của bảng.
//  Ví dụ:
//   		http://localhost:82/admin/category?sort=sort_order&order=DESC&page=2
// Nếu phía trình duyệt không chỉ rõ thì gán giá trị mặc định cho các tham số đó:
// ví dụ:
// sort = name (sắp xếp theo cột: 'tên Bài Viết')
// order = ASC (thứ tự sắp xếp: tăng dần)
// page = 1 (Trang hiện thời, trang đầu tiên trong phân trang)
$sort  = isset($_GET['sort'])  ? $_GET['sort']  : "name";
$order = isset($_GET['order']) ? $_GET['order'] : "ASC";
$page  = isset($_GET['page'])  ? $_GET['page']  : 1;

//$url = '?';
$url = ''; // Lưu các tham số sắp xếp, phân trang, tìm kiếm vào link edit, delete
           // để sau khi edit, delete xong thì chúng vẫn còn trên trang list

if (isset($_GET['filter_title'])) 
{
	// Giải mã thực thể html,
	// tiếp tục mã hóa lại lần nữa theo lược đồ mã hóa
	// giành cho địa chỉ web url, tránh vi phạm từ khóa của
	// một url
	$url .= '&filter_title=' . urlencode(html_entity_decode($_GET['filter_title'], ENT_QUOTES, 'UTF-8'));
}


if (isset($_GET['filter_status'])) 
{
	$url .= '&filter_status=' . $_GET['filter_status'];
}

$url .= isset($_GET['sort'])  ? '&sort='  . $_GET['sort']   : "";
$url .= isset($_GET['order']) ? '&order=' . $_GET['order'] : "";
$url .= isset($_GET['page'])  ? '&page='  . $_GET['page']   : "";

//if($url=='?') $url = '';

// có gửi các tham số sort, order, page vào các đường link delete, copy ?

// Truy vấn cơ sở dữ liệu để phân trang, biến mảng này sẽ được sử dụng bên view-html (đầu vào cho vòng lặp foreach)
$posts = array();

// tiêu chí truy vấn
$filter_data = array(
	'filter_title'	  => $filter_title,
	'filter_status'   => $filter_status,
	'sort'            => $sort,
	'order'           => $order,
	'start'           => ($page - 1) * settings('config_limit_admin'), 
	'limit'           => settings('config_limit_admin')	// 15-20 Bài Viết trên một trang
);

// đếm tổng số bản ghi phù hợp tiêu chí tìm kiếm
$post_total = postGetTotal($filter_data);

// lấy ra dữ liệu của tất cả các bản ghi phù hợp tiêu chí tìm kiếm
$results = postGetAll($filter_data);

// Thêm các thông tin cần thiết khác vào kết quả truy vấn
// Gán các đường link vào các nút edit, delete
// để khi bấm vào thì thao tác/can thiệp đúng item theo id
foreach ($results as $result) 
{
	if (is_file(DIR_IMAGE . $result['image'])) 
	{
		// Nếu Bài Viết không có hình đại diện...
		$image = img_resize($result['image'], 40, 40);
	} 
	else 
	{
		// ...thì dùng hình rỗng đã được đặt sẵn trong thư mục ảnh DIR_IMAGE
		$image = img_resize('no_image.png', 40, 40);
	}

	$posts[] = array(
		'post_id' => $result['post_id'],
		'image'      => $image,
		'title'       => $result['title'],
		'status'     => ($result['status']) ? "Cho Phép" : "Không Cho Phép",
		'edit'       => '/admin/post-edit.php?post_id='.$result['post_id'].$url,
		'delete'     => '/admin/post-delete.php?post_id='.$result['post_id'].$url
	);
}

// Các mục được chọn để xóa
// Khi việc xóa gặp trục trặc (ví dụ: ko có quyền, v.v..), thì các
// ô checkbox được giữ nguyên, người dùng không phải tích lại
if ( isset($_POST['selected']))  
{ 
	$selected = (array)$_POST['selected'];
}
else 
{
	$selected = array();
}

//  Tạo đường link gắn vào các cột của bảng kết quả ở tầng giao diện html
//  Mỗi đường link chứa tham số về trật tự sắp xếp và lọc khi truy vấn,
//  vì vậy khi user bấm vào tên cột, kiểu sắp xếp khác sẽ được thực hiện
//  Nếu url mà user đang xem là ASC(tăng) thì sẽ bị đổi lại thành DESC (giảm)
//  và ngược lại.
$url = ''; // Lưu các tham số phân trang, sắp xếp, tìm kiếm vào link của các Table Head (tên cột của bảng)

if (isset($_GET['filter_title'])) 
{
     $url .= '&filter_title=' . urlencode(html_entity_decode($_GET['filter_title'], ENT_QUOTES, 'UTF-8'));
}

if (isset($_GET['filter_status'])) 
{
     $url .= '&filter_status=' . $_GET['filter_status'];
}

$url .= ($order == 'ASC') ? '&order=DESC' : '&order=ASC';
$url .= isset($_GET['page']) ? '&page=' . $_GET['page'] : '';

//  Các đường link gắn vào cột giao diện html
//  Bấm vào đường link nào thì sắp xếp theo cột đấy.
//  Ví dụ: sắp xếp theo name, model, price, ...
$sort_post_id = '/admin/post.php?sort=p.post_id' . $url;
$sort_title   = '/admin/post.php?sort=p.title' . $url;
$sort_status  = '/admin/post.php?sort=p.status' . $url;
$sort_order   = '/admin/post.php?sort=p.sort_order' . $url;

//  Khởi tạo đối tượng phân trang (Pagination Object).
//  Trong đường link phân trang phải có tham số:
//  - page: trang hiện tại
//  có thể có:
//  - sort: sắp xếp theo cột nào (mặc định = name)
//  - order: trật tự sắp xếp là gì (mặc định = ASC)
//  Exam:
$url = '?';

if (isset($_GET['filter_title'])) 
{
     $url .= '&filter_title=' . urlencode(html_entity_decode($_GET['filter_title'], ENT_QUOTES, 'UTF-8'));
}

if (isset($_GET['filter_status'])) 
{
     $url .= '&filter_status=' . $_GET['filter_status'];
}

$url .= isset($_GET['sort'])  ? '&sort=' . $_GET['sort']   : "";
$url .= isset($_GET['order']) ? '&order=' . $_GET['order'] : "";

if($url=='?') $url = '';

// Không thêm thông tin phân trang vào $url vì việc này
// được thực hiện bởi hàm phân trang paginate()

paginate($post_total, $page,settings('config_limit_admin'), "/admin/post.php".$url);


// Nội dung riêng của trang:
$web_title = "Bài Viết";
$web_content = "../ui/admin/view/view-post-list.php";
$active_page_admin = ACTIVE_PAGE_ADMIN_POST;



check_file_layout($web_layout_admin, $web_content);

// được đặt vào bố cục chung của toàn site:
include_once $_SERVER["DOCUMENT_ROOT"]."/".$web_layout_admin;
