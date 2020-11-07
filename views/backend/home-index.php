<?php echo admin_notices();?>
<ul id="list_dashboard_widget">
	<?php cle_dashboard_setup() ;?>
</ul>

<div class="clearfix"></div>
<div class="col-md-12">
	<a href="#" class="manage-dashboard-widget" data-fancybox data-src="#manage-dashboard-widget"><i class="fa fa-plus"></i> Manage Widgets</a>
</div>
<div style="display: none" id="manage-dashboard-widget">
	<form class="manage-dashboard-widget__list">
		<?php 
			$widgets 	= gets_dashboard_widget();
			$dashboard 	= get_option('dashboard', array());
		?>

		<?php foreach ($widgets as $id_widget => $widget): ?>
			<?php $widget_en = (isset($dashboard[$id_widget])) ? $dashboard[$id_widget] : 1; ?>
			<section class="wrap_widget_posts_recent">
				<div class="widget_info">
	                <i class="fas fa-edit " style="background-color: #f3c200"></i>
	                <span class="widget_name"><?php echo $widget['title'];?></span>
                </div>
                <div class="swc_wrap">
                	<?php echo _form([
                		'field' => 'dashboard['.$id_widget.']',
                		'type'  => 'switch'
                	], $widget_en );?>
                </div>
            </section>
		<?php endforeach ?>

		<div class="clearfix"></div>

		<button type="submit" class="btn btn-primary">Save</button>
	</form>
</div>

<style>
	.wrapper .content .page-content { margin-top:0; min-height: 445px;}

	.wrapper .box .box-content { height: 445px; overflow: hidden;}

	.manage-dashboard-widget {
	    display: inline-block;
	    color: #bcc3c7;
	    padding: 10px 15px;
	    font-size: 14px;
	    font-weight: 400;
	    border: 1px dashed #bcc3c7;
	    border-radius: 2px;
	    margin-bottom: 15px;
	    max-width: 155px;
	}

	#manage-dashboard-widget { min-width: 400px; max-width: 100%; }

	.wrap_widget_posts_recent {
	    height: 65px;
	    line-height: 45px;
	}
	.wrap_widget_posts_recent .widget_info { width: calc(100% - 160px); float: left; }

	.wrap_widget_posts_recent i {
	    font-size: 30px;
	    width: 45px;
	    height: 45px;
	    color: #fff;
	    line-height: 45px;
	    text-align: center;
	    float: left;
	}

	.wrap_widget_posts_recent .widget_name {
	    padding-left: 10px;
	}

	.wrap_widget_posts_recent .swc_wrap {
	    height: 20px;
	    width: 150px;
	    float: right;
	}

	.wrap_widget_posts_recent .swc_wrap label.control-label { display: none; }

	.toggle__handler { top:0; }
</style>


<script>
	(function () {
		w_width = $(document).width();
		if( w_width > 768 ) {
			Sortable.create(list_dashboard_widget, {
				animation: 200,
				onEnd: function (/**Event*/evt) {
					o = 0;
					var d = {};
					$.each($(".list-dashboard-item"), function(e) {
						i = $(this).attr("data-id");
						d[o] = i;
						o++;
					});

					$jqxhr   = $.post(base+'/ajax', { 'action':'ajax_dashboard_sort', 'data' : d }, function(data) {}, 'json');

					$jqxhr.done(function( data ) {
					    show_message(data.message, data.type);
					});
				},
			});
		}

		$('.manage-dashboard-widget__list').submit(function () {

			data = $(this).serializeJSON();

			data.action = 'ajax_dashboard_save';

			$jqxhr   = $.post(base+'/ajax', data , function(data) {}, 'json');

			$jqxhr.done(function( data ) {
			    show_message(data.message, data.type);
			});

			return false;
		});
	})();
</script>