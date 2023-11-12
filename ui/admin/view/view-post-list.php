<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="/admin/post-add.php" data-toggle="tooltip" title="Thêm mới" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-post" formaction="/admin/post-copy.php" data-toggle="tooltip" title="Sao chép" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="Xóa" class="btn btn-danger" onclick="confirm('<?php echo 'Bạn có chắc là muốn xóa'; ?>') ? $('#form-post').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <!-- <h1>Sản Phẩm</h1>-->
      <ul class="breadcrumb">
          <li><a href="/admin.php"><?php echo 'Quản Trị'?></a></li> 
          <li><a href="/admin/post.php"><?php echo 'Sản Phẩm'?></a></li>
      </ul>
    </div>
  </div>
  <div class="container-fluid"> 
    <?php if ($_SESSION['ERROR_TEXT']) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['ERROR_TEXT']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php $_SESSION['ERROR_TEXT']=NULL;?>
    	
    <?php if ($_SESSION['SUCCESS_TEXT']) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['SUCCESS_TEXT']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } $_SESSION['SUCCESS_TEXT']=NULL;?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Các Bài Viết</h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo 'Tên Bài Viết'; ?></label>
                <input type="text" name="filter_title" value="<?php echo $filter_title; ?>" placeholder="<?php echo 'Tên Bài Viết'; ?>" id="input-name" class="form-control" />
              </div>
              <!--
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo 'Model'; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="Model" id="input-model" class="form-control" />
              </div>
              -->
            </div>
            <div class="col-sm-4">
              <!--
              <div class="form-group">
                <label class="control-label" for="input-price"><?php echo "Giá"; ?></label>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo 'Giá'; ?>" id="input-price" class="form-control" />
              </div>
              -->

              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo 'Trạng thái'; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*">--Không chọn--</option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo 'Cho phép'; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo 'Cho phép'; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo 'Không cho phép'; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo 'Không cho phép'; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
            	<div class="form-group">&nbsp;</div>
            	<div class="form-group">&nbsp;</div>
            	<div class="form-group">&nbsp;</div>
              <button type="button" id="button-filter" class="btn btn-primary pull-left"><i class="fa fa-search"></i> <?php echo 'Lọc'; ?></button>
            </div>
          </div>
        </div>
        <form action="/admin/post-delete.php" method="post" enctype="multipart/form-data" id="form-post">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-center">
                  	<a href="<?php echo $sort_post_id;?>" class="<?php if ($sort == 'post_id') {echo strtolower($order);} ?>">Id</a>
                  </td>
                  <td class="text-center"><?php echo 'Ảnh'; ?></td>
                  <td class="text-left">
                    <a href="<?php echo $sort_title; ?>" class="<?php if ($sort == 'title') {echo strtolower($order);} ?>"><?php echo 'Tên Bài Viết'; ?></a>
                  </td>

                  
                  <td class="text-left"><?php  ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php if ($sort == 'status') {echo strtolower($order);} ?>"><?php echo 'Trạng thái'; ?></a>
                  </td>
                  <td class="text-right"><?php echo 'Hành động'; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($posts) { ?>
                <?php foreach ($posts as $post) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($post['post_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $post['post_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $post['post_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td>
                  	<?php echo $post['post_id'] ?>
                  </td>
                  <td class="text-center"><?php if ($post['image']) { ?>
                    <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?>
                  </td>
                  <td class="text-left"><?php echo $post['title']; ?></td>
                  <td class="text-left"><?php echo $post['status']; ?></td>
                  <td class="text-right">
                  	<a href="<?php echo $post['edit']; ?>" data-toggle="tooltip" title="<?php echo 'Sửa';?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  	
                  	<!-- 
                  	Sau khi bấm vào nút xóa thì tích chọn tự động vào checkbox của bản ghi này, 
                  	rồi yêu cầu user xác nhận lại một lần nữa trước khi thực hiện xóa thực sự.
                  	Nếu user không muốn xóa nữa thì bỏ tích trên các hộp checkbox
                  	Sử dụng kĩ thuật: multiple attribute selector (jQuery) và hàm hệ thống confirm()
                  	 -->
                  	<button type="button" data-toggle="tooltip" title="Xóa" class="btn btn-danger" onclick="$('input[name*=\'selected\'][value=\'<?php echo $post['post_id'];?>\']').prop('checked', true);confirm('<?php echo 'Bạn có chắc là muốn xóa'; ?>') ? $('#form-post').submit() : false;$('input[name*=\'selected\']').prop('checked', false);"><i class="fa fa-trash-o"></i></button>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo 'Không tìm thấy kết quả nào'; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row"><!-- Phân Trang, xem class.Paginator.php, sys.functions.php - paginate() -->
          <div class="col-sm-6 text-left"><?php echo $web_pagination_controls; ?></div>
          <div class="col-sm-6 text-right"><?php echo $web_pagination_results; ?></div>
        </div>
      </div>
    </div>
  </div>
 </div>
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	var url = '/admin/post.php?';

	var filter_title = $('input[name=\'filter_title\']').val();

	if (filter_title) {
		url += '&filter_title=' + encodeURIComponent(filter_title);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
</script> 
  <script type="text/javascript">
$('input[name=\'filter_title\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: '/admin/post-autocomplete.php?filter_title=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['title'],
						value: item['post_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_title\']').val(item['label']);
	}
});


//input-name, input-model, input-price, input-quantity
// Sau khi người dùng nhập dữ liệu lọc trên form và ấn
// Enter thì kích hoạt sự kiện 'click' trên nút #button-filter
$("[id^='input-']").on('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-filter').trigger('click');
	}
});
</script>
</div>
