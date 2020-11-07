<?php
/**
@---version ::2.0.3
 * @param CORE 	- Cập nhật các hàm get, gets, count, insert, delete product phù hợp với wcmc cart 2.5.0
 * @param CORE 	- Cập nhật các hàm gets, count lấy dữ liệu theo taxonomy và attribiue
@---version ::2.0.2
 * @param FONTEND 	- Bổ sung biến status vào trang danh sách sản phẩm, tìm sản phẩm theo trạng thái
@---version ::2.0.1
 * @param FONTEND 	- Fix lỗi search sản phẩm
 * @param FONTEND 	- Thêm page danh sách sản phẩm theo nhà sản xuất
 * @param BACKEND 	- Fix không lưu được ảnh nhà sản xuất
 * @param BACKEND 	- Cập nhật hàm gets_product và count_product theo chuẩn cms 3.0.0
@---Template ::1.1.2
@---version ::2.0.0
 * @param BACKEND 	- Tách cài đặt sản phẩm ra khỏi woocommerce cài đặt
 * @param BACKEND 	- UPDATE lưu setting bằng ajax
 * @param CORE 	    - ADD quyền wcmc_product_setting quyền quản lý cấu hình sản phẩm.
 * @param CORE 	    - ADD function wcmc_delete_category, wcmc_delete_list_category
 * @param CORE 	    - ADD function delete_product, delete_list_product
 * @param CORE 	    - ADD function get_product_category_meta, update_product_category_meta, delete_product_category_meta
 * @param CORE 	    - MOVE các file setting (admin/views) vào thư mục admin/views/setting
 * @param CORE 	    - FIX lỗi lấy sai danh mục, sản phẩm trong một số trường hợp đặc biệt.
 * @param CORE 	    - ADD thêm mã code vào product
 * @param CORE 	    - ADD thêm quản lý nhà sản xuất
@---database ::1.3
 * @param ADD thêm table suppliers
 * @param ADD thêm trường supplier_id vào table products
 * @param ADD thêm trường code vào table products
=======================================================================================
@---version ::1.9.0
 * @param BACKEND 	- Thay đổi kiểu chọn danh mục sản phẩm thành kiểu popover (chỉ tác dụng từ cms.v.2.5.4).
 * @param CORE 	    - Thêm file wcmc-role quản lý phân quyền thông qua plugin roles editor.
@---Template ::1.1.1
* @param template/detail/related.php        Thêm class products-related	
* @param template/detail/related_slider.php Thêm button next prev
* @param template/widget/viewed_product.php Thêm class products-viewed
=======================================================================================
@---version ::1.8.9
 * @param CORE 	    - Fix lỗi hàm wcmc_get_category lấy danh mục sản phẩm thuộc đa ngôn ngữ sai.</p>

@---Template ::1.1.0
 * @param template/version.php          Thêm file version hiển thị thông tin template: 	
 * @param template/index/products.php   Thêm div row : template/index/products.php (19)
 * 
=======================================================================================
@---version ::1.8.8
 * @param CORE 	    - Fix lỗi hàm count_product sai làm phân trang bị sai.</p>
 * @param CORE 	    - Fix lỗi hàm gets_product khi truyền taxonomy vào lấy dữ liệu sai.</p>
 * @param CORE 	    - Thêm chế độ gets_product lấy sản phẩm thuộc category và taxonomy cùng lúc.</p>