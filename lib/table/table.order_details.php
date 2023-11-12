<?php
/**
 * Copyright Le Minh Hoa
 *
 * Các hàm quản lý chi tiết đơn hàng
 */

function order_detailsCountProductsByOrderId($order_id) 
{
	$sql = "SELECT SUM(quantity) FROM `order_details` WHERE order_id  = '" . (int)$order_id . "'";
	
	return db_one($sql);
}