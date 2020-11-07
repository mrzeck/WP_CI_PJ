<?php
/**
@---version ::2.5.1
* @param FRONTEND - CHANGE phương thức thông báo lỗi trang checkout
* @param FRONTEND - ADD thông báo lỗi cho thông tin giao hàng khi chọn giao hàng tới địa chỉ khác
* @param FRONTEND - ADD filter "checkout_fields_rules" quản lý các rule filters.
@---version ::2.5.0
* @param CORE 	    - CHANGE dữ liệu table variation được gợp chung vào table product
* @param CORE 	    - CHANGE dữ liệu table metadata variation được gợp chung vào metadata product
* @param CORE 	    - xóa table variation và variation meta
* @param CORE 	    - FIX lỗi đặt hàng email
* @param CORE 	    - FIX sữa attribute không load được ngôn ngữ 2
@---template ::1.4.0
 * @param detail/cart-variations.php  sữa lỗi $option['name'] thành $option['title']
 * @param detail/ajax_price_variation.php  sữa các biến _price, _price_sale thành price, price_sale
@---version ::2.4.1
 * @param CORE 	    - Fix insert_attribute đa ngôn ngữ bị lỗi
@---version ::2.4.0
 * @param CORE 	    - Fix wcmc_get_order khi truyền operator
 * @param CORE 	    - Thay đổi phương thức xử lý đơn hàng đồng bộ và dễ sử dụng hơn
 *                  wcmc_get_order                      => get_order
 *                  wcmc_gets_order                     => gets_order
 *                  wcmc_count_order                    => count_order
 *                  wcmc_update_order                   => update_order
 *                  wcmc_delete_order_by_id             => delete_order_by_id
 *                  wcmc_get_item_order                 => get_order_item
 *                  wcmc_gets_item_order                => gets_order_item
 *                  wcmc_delete_order_item_by           => delete_order_item_by
 *                  woocommerce_order_status            => order_status
 *                  woocommerce_order_status_label      => order_status_label
 *                  woocommerce_order_status_color      => order_status_color
 * @param CORE 	    - Tách cài đặt đơn hàng ra khỏi cài đặt sản phẩm
 * @param CORE 	    - Thêm quyền wcmc_order_setting quyền quản lý cấu hình đơn hàng.
 * @param CORE 	    - Fix lỗi khi up từ phiên bản 1.1 lên phiên bản mới bị mất thông tin khách hàng.
 * @param CORE 	    - Chuyển option thành attributes
 * @param CORE 	    - Tích hợp các phương thức lấy tỉnh thành quận huyện
 * @param CORE 	    - Tích hợp vận chuyển vào woocomerce cart
 * @param CORE 	    - Tích hợp tỉnh thành, quận huyện vào thông tin khách hàng.
 * @param BACKEND 	- Thêm cấu hình gửi email cho đơn hàng.
 * @param FRONTEND 	- Thêm tỉnh thành quận huyện vào các trường đặt hàng.
 @--template ::1.3.3
 * @param heading-bar.php   sữa lỗi css logo, đường dẫn logo.
 * @param admin/order/detail/sidebar-customer   Thêm thông tin tỉnh thành quận huyện.
@---version ::2.3.3
 * @param CORE 	    - Thêm phương thức operator vào wcmc_gets_order và wcmc_get_order
 * @param CORE 	    - Thêm hàm count_customer điếm số khách hàng
 * @param BACKEND 	- Thêm dashboard thống kê đơn hàng.
 * @param BACKEND 	- Điều chỉnh tìm kiếm đơn hàng từ điều kiện POST thành GET
@--template ::1.3.1
* @param admin/order/html-order-index   Đổi form tìm kiếm submit post thành submit get
@---version ::2.3.2
 * @param CORE 	    - Thêm function wcmc_get_template_version lấy version template
 * @param CORE 	    - Chuyển 1 số hàm wcmc_order_status từ admin order vào order helper.
 * 
@--template ::1.3.0
 * @param cart/...              Thay đổi giao diện trang giỏ hàng 
 * @param cart/cart-heading.php Thêm heading cho trang giỏ hàng
 * @param checkout/...          Thay đổi giao diện trang thanh toán
 * @param empty.php             Thay đổi giao diện trang empty
 * @param admin/order           Đổi các trường search thông tin đơn hàng có thể tùy biến.
 * @param Bỏ thao tác cập nhật thủ công thành ajax
@---version ::2.3.1
 * @param FRONTEND 	- Fix lỗi không có sản phẩm vẫn đặt hàng được.
 * @param BACKEND 	- Cập nhật sắp xếp thứ tự option item.
 * @param BACKEND 	- Thêm chức năng tạo đơn hàng trong admin.
 * @param BACKEND 	- Thêm chức năng quản lý khách hàng.
 *                      + Tự động thêm khách hàng khi đặt hàng
 *                      + Tự động cộng tiền cho khách hàng khi đơn hàng hoàn thành.
 *                      + Xem thông tin khách hàng.
 *                      + Cập nhật thông tin khách hàng
 *                      + Thêm khách hàng.
 *                      + Kích hoạt tài khoản
 *                      + Đổi mật khẩu tài khoản
 *                      + Block tài khoản //TODO
 * @param BACKEND 	- Thêm chức năng phân trang đơn hàng.
 * @param CORE 	    - Thêm filter wcmc_page_success_token giúp cập nhật token ở trang hoàn thành đơn hàng khi cần thiết.
 * @param CORE 	    - Fix hàm insert_order bị lỗi thay đổi status đơn hàng về chờ xét duyệt khi update mà không có tham số status.
 * @param CORE 	    - Fix hàm insert_order bị lỗi không update được người tạo đơn hàng.
 * @param CORE 	    - Thêm hàm wcmc_print_notice_label lấy message thông báo không template.
 * @param CORE 	    - Thêm hàm wcmc_count_order đếm số đơn hàng.
 * @param CORE 	    - Thêm file wcmc-role quản lý phân quyền thông qua plugin roles editor.
 * @param CORE 	    - Thêm một số quyền mới
 *                      + customer: customer_list           - xem danh sách khách hàng
 *                      + customer: customer_active         - Kích hoạt tài khoản khách hàng (tạo user và pass đăng nhập)
 *                      + customer: customer_add            - Thêm khách hàng mới
 *                      + customer: customer_edit           - Cập nhật thông tin khách hàng
 *                      + customer: customer_reset_password - Đặt lại password cho tài khoản khách hàng.
 * 
 *                      + order: wcmc_order_add - Thêm mới đơn hàng
 *                      + order: wcmc_order_copy - Nhân bản đơn hàng
 * 
 *                      + option: wcmc_attrattributes_add - Thêm mới tùy chọn.
@--template ::1.2.2
 * @param admin/order/html-order-index.php Thêm filter admin_order_action_bar_heading dưới đơn hàng. 
 * @param admin/order/save Thêm template của chức năng thêm, nhân bản order
 *        admin/order/html-order-save.php
 *        admin/order/save/product-items.php        //Danh sách sản phẩm
 *        admin/order/save/customer.php             //Khung chứ thông tin billing và shipping
 *        admin/order/save/customer-infomation.php  //thông tin billing và shipping
 *        admin/order/save/payments.php             //Danh sách hình thức thanh toán
 *        admin/order/save/amount-review.php        //Xem trước các phí ship, khuyến mãi, thanh toán
 * @param notices/eror.php Chỉnh sữa đồng bộ với thông báo error hệ thống.
 * @param admin/order/html-order-index.php Bổ sung phân trang.
@--database ::1.6
* @param cle_users Thêm colum order_total :: tính tổng tiền đã chi tiêu
* @param cle_users Thêm colum order_count :: tính tổng số đơn hàng cho user
* @param cle_users Thêm colum customer    :: thành viên là khách hàng
==========================================================================================================================
@---version ::2.3.0
 * @param CORE 	    - Thêm hàm order_detail_billing_info, order_detail_shipping_info để get dữ liệu khách hàng của đơn hàng.
 * @param BACKEND 	- Thêm chức năng in đơn hàng ở trang chi tiết đơn hàng.
@--template ::1.2.1
 * @param admin/order/detail/note.php               Fix lỗi không hiển thị hình thức thanh toán                       
 * @param admin/order/html-order-print.php          Add file template giao diện khi in đơn hàng                      
 * @param admin/order/detail/sidebar-customer.php   Đổi cách lấy thông tin khách hàng để bên thứ 3 có thể can thiệp   
 * 
 * 
 * 
 * 
@---version ::2.2.9
 * @param BACKEND 	- Fix không search được đơn hàng.
 * @param BACKEND 	- Thêm filter woocommerce_order_index_args giúp cập nhật điều kiện lấy đơn hàng khi cần thiết.
 * @param BACKEND 	- Thêm điều kiện tìm kiếm đơn hàng theo trạng thái.
 * @param CORE 	    - Fix hàm wcmc_gets_order khi lấy đơn hàng theo điều kiện meta_query với compare là LIKE sinh ra lỗi.
 * @param CORE 	    - Fix đa ngôn ngữ không nhận ngôn ngữ ở chi tiết đơn hàng.
@--template ::1.2.0
 * @param admin/order/html-order-index.php  Add trường search trạng thái đơn hàng
 * @param version.php                       Thêm file version hiển thị thông tin template	
 * 
 * */