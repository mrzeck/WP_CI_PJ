<div class="col-md-8 col-md-offset-2 about-wrapper">
	<h1>Chào mừng bạn tới với CMS vitechcenter <?php echo cms_info('version');?></h1>
	<h2>Xin cảm ơn vì đã cập nhật lên phiên bản mới nhất! CMSvitechcenter <?php echo cms_info('version');?> sẽ có nhiều bước cải tiến quan trọng trong Tuỳ chỉnh giao diện và giúp bạn an tâm khỏi code lỗi.</h2>
	<hr />
	<div role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active">
				<a href="#home" aria-controls="home" role="tab" data-toggle="tab">CÓ GÌ MỚI</a>
			</li>
			<li role="presentation">
				<a href="#2_5" aria-controls="2_5" role="tab" data-toggle="tab"> v2.5.x</a>
			</li>
			<li role="presentation">
				<a href="#2_4" aria-controls="2_4" role="tab" data-toggle="tab"> v2.4.x</a>
			</li>
		</ul>
	
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="home">
				<div class="col-md-10 col-md-offset-1">
					<h4><strong>3.0.6</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- UPDATE hàm container_box của widget có thể truyền padding và margin với đơn vị % thay thế cho đơn vị px.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD filter "manager_page_input" giúp có thể custom form page.</p>
					<p><span class="h-a-core">MODEL</span>    				- Lấy dữ liệu hàm fgets_object_category sai khi mặc định ngôn ngữ thứ 2 trở đi là tiếng anh.</p>
					<p><span class="h-a-core">MODEL</span>    				- Tham số groupby không nhận.</p>
					<h4><strong>3.0.5</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- FIX register_admin_subnav lỗi không tạo được menu con khi parent key có dấu "-".</p>
					<p><span class="h-a-core">CORE</span>    				- FIX admin_nav không sắp xếp được với vị trí của post</p>
					<p><span class="h-a-core">CORE</span>    				- FIX admin_nav phân quyền cho custom post type không tác dụng</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE phương thức "tax_query" vào function gets_post giúp lấy post theo một hoặc nhiều taxonomy</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE phương thức "tax_query" vào function count_post giúp đếm số post theo một hoặc nhiều taxonomy</p>
					<p><span class="h-a-core">MODEL</span>    				- FIX hàm gets_data không trả về câu lệnh sql cho phương thức meta_query</p>

					<h4><strong>3.0.4</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- ADD phương thức update vào widget giúp custom dữ liệu lưu vào database.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE khai báo view bằng từ khóa "view-name".</p>
					<p><span class="h-a-core">CORE</span>    				- CHANGE phương thức backend products::index.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX class skd_post_list_table lấy taxonomy đúng cho custom post type</p>
					
					<h4><strong>3.0.3</strong></h4>
					<p><span class="h-a-core">MODEL</span>    				- Fix lỗi fgets_object_category, count_object_category không nhận điều kiện where.</p>
					<p><span class="h-a-core">MODEL</span>    				- Fix lỗi count_object_category điếm sai số khi dữ liệu có thùng rác.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix lỗi thư viện phân trang bị lỗi khi tổng dữ liệu bằng 0.</p>
					<p><span class="h-a-core">BACKEND</span>    			- ADD phân trang cho trang thành viên.</p>
					<p><span class="h-a-core">OPTION BUILDER</span>    		- Fix lỗi không lưu được dữ liệu khi ở trang con.</p>
					<h4><strong>3.0.2</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- Fix lỗi upload không cập nhật phiên bản.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix lỗi gallery upload file không được khi có 2 gallery.</p>
					<p>THEME 2.1.4 - Thêm css button effect, css cho button red, blue, green, white.</p>
					<h4><strong>3.0.1</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- UPDATE Driver CI version 2.x lên CI 3.x.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE Model hỗ trợ mysql 5.7.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE fgets_object_category thành join giúp lấy số lượng lớn dữ liệu.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm count_object_category.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm count_object_category vào count_data.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm count_object_category vào count_data.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD params "sql" vào model trả về câu lệnh sql.</p>
					<p>THEME 2.1.3 - FIX lỗi favicon khi chưa chọn.</p>
					<h4><strong>3.0.0</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- FIX lỗi hàm fgets_where_like đa ngôn ngữ.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX lỗi lấy sai danh mục tìm kiếm trong taxonomy.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX lỗi phân quyền xóa trang nội dung.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX lỗi skd_post_list_table lấy sai danh mục khi post_type không có danh mục.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE MY_Model bổ sung hàm get_data, gets_data, count_data giúp đồng bộ các hàm get, gets.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE chuyển cơ chế get metadata theo join table (Giảm số request lên server).</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE hàm gets_user, gets_post, gets_post_category, gets_page chuyển sử dụng gets_data của MY_Model.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE hàm get_user, get_post, get_post_category, get_page chuyển sử dụng get_data của MY_Model.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE hàm count_user, count_post, count_page chuyển sử dụng count_data của MY_Model.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE hàm insert_post có thể cập nhật trường public</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm get_post_meta, update_post_meta, delete_post_meta.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm get_page_meta, update_page_meta, delete_page_meta.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm get_post_category_meta, update_post_category_meta, delete_post_category_meta.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm count_post_category, delete_list_category, .</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm delete_list_page, delete_list_post.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD hàm delete_gallery, delete_gallery_by_post.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE lưu thông tin đăng nhập bằng cookie.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE File htaccess tăng thời gian lưu cache.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX insert_gallery không cập nhật được type.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE trình update core tự động xóa các file dư thừa của phiên bản củ.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE password user không mã hóa bằng username giúp khả năng mở rộng tăng cao.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD gallery cho danh mục bài viết.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD roles - tách phân quyền những chức năng mặc định của cms ra khỏi plugin.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD roles - thêm quyền view_pages xem list bài viết (tách khỏi edit page).</p>
					<p><span class="h-a-core">CORE</span>    				- ADD roles - thêm quyền add_pages thêm trang nội dung.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD roles - thêm quyền add_posts thêm bài viết.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD roles - thêm quyền view_posts xem list bài viết (tách khỏi edit post).</p>
					<p><span class="h-a-core">CORE</span>    				- ADD roles - thêm quyền view_posts xem list bài viết (tách khỏi edit post).</p>
					<p><span class="h-a-core">CORE</span>    				- CHANGE đổi service dịch vụ và api service.</p>
					<p><span class="h-a-core">CORE</span>    				- ADD tạo ảnh watermark</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE cải tiến function get_option và function update_option</p>
					<p><span class="h-a-core">FONTAWESOME</span>    		- UPDATE phiên bản 5.8 lên 5.11 pro</p>
					<p><span class="h-a-backend">BACKEND</span>    			- FIX ghi file log hành động sai.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- FIX click vào sữa danh mục ở trang bài viết bị lỗi.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- ADD ghi file log hành động thêm bài viết, danh mục, trang nội dung.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- ADD ghi file log hành động xóa bài viết, danh mục, trang nội dung.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE admin navigation icon mới.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE admin gallery giao diện mới.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE admin action log giao diện mới.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE admin user giao diện mới.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- ADD page cài đặt hệ thống tổng hợp.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE chuyển cài đặt smtp vào cài đặt hệ thống.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE chuyển cài đặt thông tin liên hệ vào cài đặt hệ thống.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- ADD trạng thái hệ thống (public, close, close-home, password).</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE tinymce editer thêm font family mới.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- FIX một số lỗi giao diện mobile.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE giao diện thêm gallery object mới.</p>

					<p><span class="h-a-backend">FRONTEND</span>    		- ADD chức năng widget bulder giúp thao tác với widget ngay tại giao diện người dùng.</p>
					<p><span class="h-a-backend">FRONTEND</span>    		- ADD chức năng theme option cấu hình giao diện ngay tại giao diện người dùng.</p>
					<p><span class="h-a-backend">FRONTEND</span>    		- ADD minify css và minify js file.</p>
					<p><span class="h-a-backend">FRONTEND</span>    		- UPDATE giao diện trang 404.</p>

					<p>THEME 2.1.3 - REMOVE cấu hình thông tin liên hệ.</p>
					<p>THEME 2.1.3 - REMOVE cấu hình mạng xã hội google+.</p>
					<p>THEME 2.1.3 - UPDATE đồng bộ font backend và fontend.</p>
					<p>THEME 2.1.3 - UPDATE tách cấu hình fonts chữ ra quản lý riêng.</p>
					<p>THEME 2.1.3 - UPDATE chuyển cấu hình mạng xã hội vào hệ thống.</p>
					<p>THEME 2.1.3 - ADD cấu hình hệ thống sử dụng gallery.</p>
					<p>THEME 2.1.3 - CHANGE đổi giao diện đăng ký thành viên.</p>
					<p>THEME 2.1.3 - CHANGE đổi giao diện đăng nhập thành viên.</p>
					
					<p><span class="h-a-database">DATABASE 2019.1</span>    - UPDATE content chuyển thành kiểu LONGTEXT.</p>
					<p><span class="h-a-database">DATABASE 2019.1</span>    - UPDATE index cho row option_name cho table system.</p>
					<p><span class="h-a-database">DATABASE 2019.1</span>    - UPDATE index cho row title, slug cho table post.</p>
					<p><span class="h-a-database">DATABASE 2019.1</span>    - UPDATE index cho row title, slug cho table page.</p>
					<p><span class="h-a-database">DATABASE 2019.1</span>    - UPDATE index cho row slug cho table routes.</p>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="2_5">
				<div class="col-md-10 col-md-offset-1">
					<h4><strong>2.5.8</strong></h4>
					<p>REPONSIZEFILEMANAGER	- Fix lỗi không kéo được xuống khi upload hình quá nhiều.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- FIX không edit được dữ liệu category.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- FIX đường dẫn category sau khi thêm load bằng ajax bị sai.</p>


					<h4><strong>2.5.7</strong></h4>
					<p>REPONSIZEFILEMANAGER	- Upload phiên bản.</p>
					<p>REPONSIZEFILEMANAGER	- Upload plugin tương thích với tinymce 5.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX tương thích PHP 7.2 .</p>
					<p><span class="h-a-core">CORE</span>    				- ADD Thêm hàm count_user (user_helper) .</p>
					<p><span class="h-a-core">CORE</span>    				- ADD Thêm hàm count_where_like (My_model) .</p>
					<h4><strong>2.5.6</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- FIX lỗi không cập nhật được public cho danh mục.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX lỗi lưu danh mục bằng ajax.</p>
					<p><span class="h-a-core">CORE</span>    				- FIX tương thích PHP 7.2 .</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE xóa cache danh mục khi cập nhật tình trạng ẩn hiện.</p>
					<h4><strong>2.5.5</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- UPDATE plugin tinymce lên phiên bản 5.0.5.</p>
					<p><span class="h-a-core">CORE</span>    				- UPDATE plugin Font Awesome lên phiên bản 5.8.2 Pro</p>
					<p><span class="h-a-backend">BACKEND</span>    			- REMOVE cấu hình color ra khỏi cấu hình soạn thảo.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- REMOVE widget dashboard site info.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE giao diện thêm thành viên.</p>
					<p><span class="h-a-backend">BACKEND</span>    			- UPDATE thêm danh mục bằng ajax.</p>
					

					<p>THEME 2.1.2 - Fix hiển thị icon share zalo trong trang chi tiết bài viết bị lỗi khi không cài plugin wcmc</p>
					<p>THEME 2.1.2 - Fix lỗi lưu layout khi không cài pligin wcmc</p>

					<h4><strong>2.5.4</strong></h4>
					<p><span class="h-a-core">CORE</span>    				- Thêm kiểu dữ liệu popover (radio muti cách tân).</p>
					<p><span class="h-a-core">CORE</span>    				- Thêm hàm user_add_role thêm nhóm quyền hạn cho thành viên mà vẫn giữ các nhóm quyền trước đó.</p>
					<p><span class="h-a-core">CORE</span>    				- Cập nhật thư viện phân trang không hiển thị khi tổng số trang bằng 1.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix có thể toàn quyền tùy chỉnh các cột table trong page, post, post category.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix lỗi không tạo rules cho input select sẽ bị lỗi validate.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix hàm insert_user không cập nhật user trash.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix hàm insert_user lỗi khi set role cho user mới.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix hàm username_exists không kiểm tra username của user trash.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix hàm email_exists không kiểm tra email của user trash.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix hàm gets_user lỗi khi get dữ liệu bằng meta_query có điều kiện LIKE.</p>
					<p><span class="h-a-core">CORE</span>    				- Fix lỗi <b>đa ngôn ngữ</b> trên 3 ngôn ngữ lấy dữ liệu bị sai.</p>
					<p><span class="h-a-core">CORE</span>    				- Lội bỏ $this->ci ra khỏi thư viện <b>template</b>.</p>
					<p><span class="h-a-core">CORE</span>    				- Thư viện nhận biến [paging] ưu tiên [page] tránh xung đột khi phân trang ở plugin</p>
					<p>REPONSIZEFILEMANAGER	- Tắt chức năng chỉnh sữa hình ảnh AVIARY (api bị xóa).</p>
					<p>REPONSIZEFILEMANAGER - Fix lỗi tên file sau khi upload.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi trường thêm taxonomy thành input popover.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi giao diện trang đăng nhập.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi giao diện phân trang.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi hiển thị tiêu đề page trang danh sách và thêm action "admin_page_action_bar_heading" dưới tiều đề page</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi hiển thị tiêu đề page trang cập nhật và thêm action "admin_page_save_action_bar_heading" dưới tiều đề page</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi hiển thị tiêu đề post trang danh sách và thêm action "admin_post_{post_type}_action_bar_heading" dưới tiều đề post</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi hiển thị tiêu đề post trang cập nhật và thêm action "admin_post_save_{post_type}_action_bar_heading" dưới tiều đề post</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Thay đổi hiển thị tiêu đề post category trang cập nhật và thêm action "admin_post_category_save_{cate_type}_action_bar_heading" dưới tiều đề post category</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Fix lỗi mất ô thêm menu.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Fix lỗi khi xóa hết page (còn dữ liệu thùng rác) không thể truy cập được thùng rác để xóa.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Fix lỗi khi xóa hết post (còn dữ liệu thùng rác) không thể truy cập được thùng rác để xóa.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Fix lối root không thể reset mật khẩu nhân viên.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Cập nhật chức năng khi edit post ở page nào sẽ quay về page đó ( phiên bản trước quay về page 1 ).</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Cập nhật giao diện trang chi tiết thành viên.</p>
					<p><span class="h-a-backend">BACK-END</span>     		- Navigation thành viên khi click vào chuyển đền list hoặc hồ sơ tùy quyền (phiên bản củ mặc định vào hồ sơ nhân viên).</p>
					<p>THEME 2.1.1 - Fix hiển thị tiêu đề và breadcrumb của page khi chọn layout fullwidth không banner</p>
					<p>THEME 2.1.1 - Chỉnh sửa lại giao diện đăng ký thành viên.</p>
					<p>THEME 2.1.1 - Fix lỗi không hiển thị thông báo thành công hay thất bại khi cập nhật thông tin tài khoản.</p>

					<h4><strong>2.5.3</strong></h4>
					<p><span class="h-a-backend">BACK-END</span>    	- Không hiển thị thông tin gallery sau khi lưu <b>(Hưng)</b>.</p>
					<p><span class="h-a-backend">BACK-END</span>    	- Bổ sung trường dữ liệu bài viết nổi bật (status == 1).</p>
					<p><span class="h-a-backend">BACK-END</span>    	- Cập nhật giao diện thông báo.</p>
					<p><span class="h-a-backend">BACK-END</span>    	- Fix lỗi tìm kiếm danh mục bài viết (Hiếu).</p>
					<p><span class="h-a-backend">BACK-END</span>    	- Fix lỗi hiển thị code error php khi không có tham số post_type và cate_type.</p>
					<p><span class="h-a-core">CORE</span>    		- Fix lỗi không xóa được taxonomy trong chi tiết object.</p>
					<p><span class="h-a-core">CORE</span>    		- Fix lỗi lấy dữ liệu post theo điều kiện LIKE metabox của post.</p>
					<p>THEME 2.1.0	- Cập nhật hiệu ứng loading image khi sử dụng lazyload</p>
					<p>THEME 2.1.0	- Cập nhật chức năng theme-layout</p>
					<p>THEME 2.1.0	- Gộp chức năng theme header và theme layout lại thành một.</p>
					<p>THEME 2.1.0	- Cập nhật giao diện thông báo.</p>
					<p>THEME 2.1.0	- Cập css style cho button next prev của widget product.</p>

					<p><strong>2.5.2</strong></p>
					<p><span class="h-a-backend">BACK-END</span>    	- Tắt tính năng kéo thả widget dashboard ở trang chủ khi ở màng hình mobile.</p>
					<p><span class="h-a-backend">BACK-END</span>    	- Thay đổi giao diện edit menu.</p>
					<p><span class="h-a-backend">BACK-END</span>    	- Thêm trường sắp xếp thứ tự vào taxonomy category</p>
					<p><span class="h-a-core">CORE</span>    		- Thêm điều kiện where_in cho hàm gets_user.</p>
					<p><span class="h-a-core">CORE</span>    		- Fix lỗi sau khi xóa thành viên, thành viên tạo sau trùng username sẽ bị lỗi.</p>
					<p><span class="h-a-core">CORE</span>    		- Fix lỗi hàm breadcrumb bị sai trên đa ngôn ngữ.</p>
					<p><span class="h-a-core">CORE</span>    		- Fix lỗi hàm get_img không sử dụng được khi có ssl.</p>
					
					
					<p><strong>2.5.1</strong></p>
					<p><span class="h-a-core">CORE</span>    	   - Fix (gets_post_category) khi truyền biến tree có kèm params thì params bị mất tác dụng.</p>
					<p><span class="h-a-core">CORE</span>    	   - Fix (gets_post_category) khi truyền biến mutilevel bằng 0 không lấy được dữ liệu.</p>
					<p>UPDATE      - Fix không đăng nhập được admin khi update từ phiên bản 2.2.0 trở xuống.</p>
					<p><span class="h-a-backend">BACK-END</span>    - Fix một số lỗi ở cấu hình soạn thảo.</p>
					<p><span class="h-a-backend">BACK-END</span>    - Fix lỗi khi cài plugin đa ngôn ngữ tên tab ngôn ngữ bị lỗi.</p>


					<p><strong>2.5.0</strong></p>
					<p>THEME 2.0.0 - Thêm chức năng thư viện HEADER</p>
					<p>THEME 2.0.0 - Thêm chức năng thư viện NAVIGATION</p>
					<p>THEME 2.0.0 - Thêm chức năng thư viện TOP BAR</p>
					<p>THEME 2.0.0 - Fix search mobile click "xem tất cả kết quả" bị lỗi</p>
					<p><span class="h-a-backend">BACK-END</span>    - Fix khi sửa menu nhiều lần bị chòng nền đen.</p>
					<p><span class="h-a-backend">BACK-END</span>    - Fix css index chèn lên menu.</p>
					<p><span class="h-a-core">CORE</span>    	   - Fix đa ngôn ngữ ajax không chuyển được sang ngôn ngữ khác.</p>
					<p>FRONT-END   - Fix lưu lại ngôn ngữ trước đó khi url không có định danh ngôn ngữ (vi, en ..).</p>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="2_4">
				<div class="col-md-10 col-md-offset-1">
					<p><strong>2.4.4</strong></p>

					<p><span class="h-a-backend">BACK-END</span>		- Fixed lỗi không thêm được hình ảnh vào menu.</p>
					<p><span class="h-a-backend">BACK-END</span>		- Bổ sung auto load hình khi điền link ảnh không thuộc website.</p>
					<p><span class="h-a-backend">BACK-END</span>		- Bổ sung auto load hình youtube ở input files.</p>
					<p><span class="h-a-backend">BACK-END</span>		- Fixed lỗi không thêm được thư viện ảnh đầu tiên.</p>
					<p><span class="h-a-backend">BACK-END</span>		- Fixed "Hồ sơ của bạn" lưu chưa hiển thị lập tức thông tin mới.</p>
					<p><span class="h-a-core">CORE</span>			- Fixed insert_user không lưu được firstname.</p>


					<p><strong>2.4.3</strong></p>
					<p><span class="h-a-backend">BACK-END</span>		- Fixed lỗi gallery không lưu được </p>
					<p><span class="h-a-backend">BACK-END</span>		- Bổ sung xóa cache metabox</p>
					<p><span class="h-a-backend">BACK-END</span>		- Bổ sung sắp xếp gallery bằng kéo thả</p>
					<p><span class="h-a-backend">BACK-END</span>		- Cập nhật sắp xếp dashboard bằng việc kéo thả</p>
					<p><span class="h-a-backend">BACK-END</span>		- Cập nhật bật tắt dashboard widget</p>
					<p><span class="h-a-core">CORE</span>			- Fix lỗi hàm cập nhật gallery (insert_gallery) bắt buộc có giá trị của value</p>
					<p><span class="h-a-core">CORE</span>			- Thêm hàm get_dashboard_widget và gets_dashboard_widget</p>
					<p>THEME 1.9	- Cập nhật menu và search mobile mới</p>
					<p><strong>2.4.2</strong></p>
					<h3>QUẢN LÝ TRÌNH SOAN THẢO LINH ĐỘNG</h3>
					<?php get_img('http://developers.vitechcenter.net/about/2.4.2/tinymce_manager.png');?>

					<h3>TRÌNH QUẢN LÝ CACHE DỮ LIỆU</h3>
					<?php get_img('http://developers.vitechcenter.net/about/2.4.2/cache_manager.png');?>

					<h3>NHẬT KÝ HOẠT ĐỘNG</h3>
					<?php get_img('http://developers.vitechcenter.net/about/2.4.2/log_manager.png');?>
					<p></p>


					<p><strong>2.4.0</strong></p>
					<h3>NÂNG CẤP TRÌNH QUẢN LÝ FILE "RESPONSIVE FILEMANAGER v 9.14.0"</h3>
					<p>Từ việc upload từ version 9.11.3 lên version 9.14.0 mang lại cho trình quản lý file những cải tiến mạnh mẽ với nhiều thay đổi quan trọng như thay đổi cơ chế upload File bằng Java thay bằng HTML5, cho phép chọn nhiều File để thao tác, Fix nhiều lỗi bảo mật nguy hiểm, 
					Tự động xóa dấu khi tên File hoặc thư mục có dấu tiếng việt khi upload</p>
					<?php get_img('http://developers.vitechcenter.net/about/2.4.0/filemanager.png');?>
					
					<div class="row">
						<div class="col-md-7">
							<h3>UPLOAD TINYMCE KHÔNG LO MẤT ẢNH</h3>
							<p>Ở các phiên bản trước việc upload ảnh trong trình Tinymce rất khó chịu với việc duy chuyển hay thay đổi domain vì url hình ảnh được lưu là cục bộ và đi chết với domain, 
							giờ đây việc upload ảnh trong trình upload Tinymce không còn phải lo lắng về vấn đề domain nữa, tất cả đã hoàn toàn trở thành ảnh linh động không lo domain khi chết ảnh sẽ chết theo.
							Bạn có thể duy chuyển source qua nhiều domain khác nhau mà không sợ mất ảnh.</p>
						</div>
						<div class="col-md-5">
							<BR /><BR /><BR />
							<?php get_img('http://developers.vitechcenter.net/about/2.4.0/tinymce.PNG');?></div>
					</div>

					<div class="row">
						<div class="col-md-7">
							<?php get_img('http://developers.vitechcenter.net/about/2.4.0/gallery-mutile.gif');?>
						</div>
						<div class="col-md-5">
							<h3>UPLOAD MULTIPLE GALLERY</h3>
							<p>Nhờ vào việc có thể chọn nhiều ảnh cũng một lúc trong phiên bản mới của trình quản lý file giờ đây bạn đã có thể thêm nhiều ảnh chỉ với 1 lần upload hình ảnh duy nhất.</p>
						</div>
					</div>

					<h3>SẮP XẾP CÁC FILE TRONG THƯ VIỆN BẰNG VIỆC KÉO THẢ</h3>
					<p>Ở các phiên bản trước việc sắp xếp các file trong thư viện là không thể với phiên bản 2.4.0 chức năng này đã hoàn thiện và được đưa vào sử dụng.</p>
					<?php get_img('http://developers.vitechcenter.net/about/2.4.0/gallery-sort.gif');?>

					<h3>THAY ĐỔI THAO TÁC XỬ LÝ WIDGET</h3>
					<p>Thay thế các thao tác phức tạp bằng button ( Thêm mới, duy chuyển ) bằng công nghệ kéo thảo widget giúp việc sử dụng widget trở nên dễ dàng và thuận tiện hơn.</p>
					<?php get_img('http://developers.vitechcenter.net/about/2.4.0/widget-ux.gif');?>
					
				</div>

				<div class="col-md-10 col-md-offset-1">
					<p><strong>2.4.3</strong></p>
					<p><span class="h-a-backend">BACK-END</span>		- Fixed lỗi gallery không lưu được </p>
					<p><span class="h-a-core">CORE</span>			- Fix lỗi hàm cập nhật gallery (insert_gallery) bắt buộc có giá trị của value</p>
					<p><span class="h-a-core">CORE</span>			- Thêm hàm get_dashboard_widget và gets_dashboard_widget</p>
					<h4>CHỈNH SỬA CÁC FUNCTION GALLERY</h4>
					<p>Nhằm giúp các lập trình viên dễ dàng tương tác với gallery, trong phiên bản này mọi option của gallery đã được chuyển thành metadata và thêm nhiều hàm mới nhằm đáp ứng sự linh hoạt trong việc tùy biến, phát triển gallery.</p>
					<div class="row">
						<div class="col-md-6">
							<h5>Các hàm mới:</h5>
							<p>insert_gallery</p>
							<p>_get_gallery</p>
							<p>gets_gallery</p>
							<p>get_gallery_meta</p>
							<p>update_gallery_meta</p>
							<p>delete_gallery_meta</p>
						</div>
						<div class="col-md-6">
							<h5>Các hàm bị loại bỏ:</h5>
							<p>add_gallery</p>
							<p>update_gallery</p>
							<p>get_gallery_product_image</p>
						</div>
					</div>


					<h4>CATEGORY POST</h4>

					<p>Giờ đây category cũng đã được áp dụng cơ chế cache giúp giảm tải hiệu quả việc truy xuất dữ liệu bên cạnh đó hàm gets_post_category được cải tiến mạnh mẽ</p>
					<p>Biến $args bổ sung thêm : mutilevel hỗ trợ lấy dữ liệu theo dạng cha con</p>
					

					<h4>BUG</h4>
					<p>Sửa nhiều bug ở nhiều hàm. nhớ không nỗi mà liệt kê ra.</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"> </div>
<BR /><BR /><BR />
<BR /><BR /><BR />
<BR /><BR /><BR />
<BR /><BR /><BR />
<BR /><BR /><BR />
<style type="text/css">
	.about-wrapper h2 {
		font-weight: 400;
	    line-height: 1.6em;
	    font-size: 19px;
		color: #555d66;
	}

	.about-wrapper h3 {
	    margin: 40px 0 .6em;
	    font-size: 2.7em;
	    line-height: 1.3;
	    font-weight: 300;
	    text-align: center;
	}

	.about-wrapper p {
		font-size: 1em;
		line-height: 2em;
		text-align: justify;
	}

	.about-wrapper img { max-width: 100%; box-shadow: 5px 5px 5px #ccc; }

	.tab-pane { overflow: hidden; }

	.h-a-core { color:red;font-weight:bold; }
	.h-a-backend { color:green;font-weight:bold; }
</style>