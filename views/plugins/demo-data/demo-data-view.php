<div class="clearfix"> </div>
<form id="dmd_form_post" class="dmd_form" data-form="post">
	<div class="col-md-12 box">
		<div class="col-md-6 box-content">
			<div class="ajax-load-qa">&nbsp;</div>
			<h4>Tạo Post</h4>
			<hr />
			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"> <label>Số bài viết</label><p>Số bài viết cho từng danh mục.</p> </div>
				<div class="col-md-8">
					<input type="number" name="post[num]" class="form-control" value="20" required>
				</div>
			</div>

			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"> <label>Kích thước ảnh</label> <p>Width x Height </p> </div>
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-6"><input type="number" name="post[width]" class="form-control" value="200" required></div>
						<div class="col-md-6"><input type="number" name="post[height]" class="form-control" value="200" required></div>
					</div>
				</div>
			</div>

			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"> <label>Loại dữ liệu</label> </div>
				<div class="col-md-8">
					<select name="post[type]" class="form-control" required="required">
						<option value="0">Tất cả</option>
						<option value="fashion">Thời trang</option>
						<option value="technology">Công nghệ</option>
						<option value="land">Bất động sản</option>
					</select>
				</div>
			</div>

			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"></div>
				<div class="col-md-8 text-right">
					<button type="submit" class="btn btn-green">Tạo</button>
				</div>
			</div>
		</div>
	</div>
</form>

<?php if( class_exists('woocommerce') ) :?>
<form id="dmd_form_product" class="dmd_form" data-form="product">
	<div class="ajax-load-qa">&nbsp;</div>
	<div class="col-md-12 box">
		<div class="col-md-6 box-content">
			<h4>Tạo Sản Phẩm</h4>
			<hr />
			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"> <label>Số sản phẩm</label><p>Số sản phẩm cho từng danh mục.</p> </div>
				<div class="col-md-8">
					<input type="number" name="product[num]" class="form-control" value="5" required>
				</div>
			</div>

			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"> <label>Kích thước ảnh</label> <p>Width x Height </p> </div>
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-6"><input type="number" name="product[width]" class="form-control" value="200" required></div>
						<div class="col-md-6"><input type="number" name="product[height]" class="form-control" value="250" required></div>
					</div>
				</div>
			</div>

			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"> <label>Giá - Giá khuyến mãi</label> <p>Không điền sẽ random giá </p> </div>
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-6"><input type="number" name="product[price]" class="form-control" value="0" required></div>
						<div class="col-md-6"><input type="number" name="product[price_sale]" class="form-control" value="0" required></div>
					</div>
				</div>
			</div>

			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"> <label>Loại dữ liệu</label> </div>
				<div class="col-md-8">
					<select name="product[type]" class="form-control" required="required">
						<option value="0">Tất cả</option>
						<option value="fashion">Thời trang</option>
						<option value="technology">Công nghệ</option>
					</select>
				</div>
			</div>

			<div class="col-md-12" style="margin-bottom: 10px;">
				<div class="col-md-4"></div>
				<div class="col-md-8 text-right">
					<button type="submit" class="btn btn-green">Tạo</button>
				</div>
			</div>
		</div>
	</div>
</form>
<?php endif;?>

<style type="text/css">
	.dmd_form { position: relative; overflow: hidden; }
</style>

<script type="text/javascript">
	$(function() {
		$('.dmd_form').submit(function() {

			var data 		= $(this).serializeJSON();

			data.action     =  'dmd_ajax_create_data';

			data.data     	=  $(this).attr('data-form');

			load = $(this).find('.ajax-load-qa'); load.show();

			$jqxhr   = $.post(base+'/ajax', data, function() {}, 'json');

			$jqxhr.done(function( data ) {
				load.hide();
	  			show_message(data.message, data.status);
			});

			return false;

		});
	});
</script>