<div class="col-md-2 widget-service-kho-sidebar">
	<ul class="sb-categories">
		<li class="sb-cat active"><a href="#" class="widget-cat-item" data-id="0" >Tất Cả Widget</a></li>
		<?php foreach ($categories as $category): ?>
		<li class="sb-cat"><a href="#" class="widget-cat-item" data-id="<?php echo $category->id;?>"><?php echo $category->name;?></a></li>
		<?php endforeach ?>
	</ul>
</div>
<div class="col-md-10 widget-service-kho-list">
	<div class="row" id="widget-service-kho-list__item">
		<?php foreach ($widgets as $item): ?>
			<?php get_instance()->template->render_include('ajax-page/widget_service_item', array( 'item' => $item ) );?>
		<?php endforeach ?>
	</div>
</div>

<script type="text/javascript">
	$('#widget_server_search').on('keyup', function () {

	    var value = this.value;

	    $('#widget-service-kho-list__item .wg-item').parent().hide().each(function () {
	        if ($(this).find('.widget-name').text().toUpperCase().search(value.toUpperCase()) > -1) {
	            $(this).parent().add(this).show();
	        }
	    });
	});
</script>

<style type="text/css">
	.widget-service-kho-sidebar {
	    padding: 0;

	}
	.widget-service-kho-sidebar ul.sb-categories {
	    list-style: none;
	    padding: 0;
	    margin: 0;
	}
	.widget-service-kho-sidebar ul.sb-categories li {
	    transition: text-indent 0.2s;
	    padding-top: 12px;
	    padding-bottom: 12px;
	    padding-left: 35px;
	}
	.widget-service-kho-sidebar ul.sb-categories li a {
	    text-transform: capitalize;
	    font-size: 14px;
	    color: #666;
	    position: relative;
	    display: block;
	}
	.widget-service-kho-sidebar ul.sb-categories li.active {
	    background-color: #EF3C26;
	}
	.widget-service-kho-sidebar ul.sb-categories li.active a {
	    color: #fff;
	}

	.widget-service-kho-list {
	    float: right;
	    border-left: 1px solid rgba(32,48,60,.1);
	    padding-left: 4rem;
	    padding-right: 4rem;
	    padding-bottom: 6rem;
	    padding-top: 0px;
	    overflow: auto;
    	height: calc(100vh - 50px);
    	padding-top: 10px;
	}
	
	.widget-service-kho-list .wg-item {
		overflow: hidden; margin-bottom: 25px;
		border-radius:10px;
		box-shadow: 0 6px 10px rgba(0, 0, 0, 0.08), 0 0 6px rgba(0, 0, 0, 0.05);
		transition: 0.3s transform cubic-bezier(0.155, 1.105, 0.295, 1.12), 0.3s box-shadow, 0.3s -webkit-transform cubic-bezier(0.155, 1.105, 0.295, 1.12);
	}

	.widget-service-kho-list .wg-item:hover {
		transform: scale(1.05);
  		box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
	}

	
	.widget-service-kho-list .wg-item .img {
		position: relative;
	    overflow: hidden;
	    height: 115px;
	    text-align: center;
	}

	.widget-service-kho-list .wg-item .img img{
		display: inline-block;
    	width: 100%; max-height:100%;
	}
	
	.widget-service-kho-list .wg-item .title { padding:10px; position: relative; z-index: 999; }
	.widget-service-kho-list .wg-item .title h3 {
		text-align: center;
	    padding: 0 0 0 15px;
	    text-transform: uppercase;
	    font-size: 11px;
	    font-weight: 300;
	    margin: 15px 0 5px 0;
	    line-height: 1.3;
	    color: #333;

		text-overflow: ellipsis;
		line-height: 25px;
		-webkit-line-clamp: 1;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		overflow:hidden;
	}
	.widget-service-kho-list .wg-item .title p.author {
		text-align: center; margin-bottom: 15px; color:#8d8d8d;
	}

	.widget-service-kho-list .wg-item .title .btn { padding:10px; transition: all 0.5s; }

	@media(max-width: 1300px) {
		.widget-service-kho-list .wg-item .title h3 { font-size: 12px; }
		.widget-service-kho-list .wg-item .img { height: 115px }
	}
	@media(max-width: 1300px) {
		.widget-service-kho-list .wg-item .title h3 { font-size: 10px; }
		.widget-service-kho-list .wg-item .img { height: 95px }
	}
</style>