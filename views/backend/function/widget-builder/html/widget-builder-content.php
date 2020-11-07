<div class="widget-builder-edit">
	<div class="widget-builder-edit__content">
		<div class="js_widget-builder-edit_close"><i class="fal fa-times"></i></div>
        <?php admin_loading_icon();?>
		<form class="widget-builder-edit__body" id="widget-builder-edit__form">
			
		</form>
		<div class="widget-builder-edit__bottom">
			<button id="widget_builder_btn__submit" form="widget-builder-edit__form" class="btn">SAVE</button>
			<button id="widget_builder_btn__load" class="btn" style="display:none;">QUAY LẠI</button>
			<button id="widget_builder_btn__download" class="btn" style="display:none;">DOWNLOAD WIDGET</button>
		</div>
	</div>
</div>

<style>
    .tox-silver-sink, .fancybox-container { z-index: 99999; }
    body {
		transition: all 0.3s ease-out 0s;
		margin-left:0;
	}
	body.is-show {
		margin-left:420px;
	}

    /*=============== WIDGET ĐANG CHẠY ==================*/
    .js_widget_builder { position:relative; min-height:100px; }

    .js_widget_builder .js_widget_builder_content__add_top, 
    .js_widget_builder .js_widget_builder_content__add_bottom {
		position:absolute; left:49%;
		text-align:center; color:#4285F4;
		background-color:#fff; border-radius:50%;
		font-weight:bold; font-size:30px; z-index:999;
		display:none; cursor: pointer;
	}

    .js_widget_builder .js_widget_builder_content__add_top {
		top:-13px;
	}
	.js_widget_builder .js_widget_builder_content__add_bottom {
		bottom:-13px;
	}

    .js_widget_builder .js_widget_builder_content__add_top:hover, 
    .js_widget_builder .js_widget_builder_content__add_bottom:hover {
		color:red;
	}
    

    .js_widget_builder  .js_widget_builder_action {
        position:absolute; left:-3px;top:-32px;
		text-align:center;
        background-color:#4285F4;
        color:#fff;
        font-size:13px;
        z-index:999;
        overflow:hidden;
        display:none;
    }

    .js_widget_builder  .js_widget_builder_action label, .js_widget_builder  .js_widget_builder_action .btn {
        float:left;
    }

    .js_widget_builder  .js_widget_builder_action label {
        margin:0; padding:0 10px;
        height: 33px; line-height:33px;
        background-color:#3B77DB;
    }

    .js_widget_builder  .js_widget_builder_action .btn {
        border:0;
        border-radius:0;
        background-color:#4285F4;
    }

    .js_widget_builder  .js_widget_builder_action .btn.btn-red {
        color:red;
    }

    .js_widget_builder  .js_widget_builder_action .btn.btn-green {
        color:green;
    }

    .js_widget_builder:hover, .js_widget_builder.js_widget_builder_active {
		overflow: initial;
	}

	.js_widget_builder:hover [class*="_widget_border_box"],
	.js_widget_builder.js_widget_builder_active [class*="_widget_border_box"] {
		border-top:1px dashed #4285F4!important;
		position:absolute;z-index:9;
	}
	.js_widget_builder:hover ._widget_border_box_top,
	.js_widget_builder.js_widget_builder_active ._widget_border_box_top {
		top:0; left:0; width:100%;
	}
	.js_widget_builder:hover ._widget_border_box_bottom,
	.js_widget_builder.js_widget_builder_active ._widget_border_box_bottom {
		bottom:0; left:0; width:100%;
	}
	.js_widget_builder:hover ._widget_border_box_left,
	.js_widget_builder.js_widget_builder_active ._widget_border_box_left {
		top:0; left:0; height:100%;
	}
	.js_widget_builder:hover ._widget_border_box_right,
	.js_widget_builder.js_widget_builder_active ._widget_border_box_right {
		top:0; right:0; height:100%;
	}

	.js_widget_builder:hover .js_widget_builder_content__add_top, 
    .js_widget_builder:hover .js_widget_builder_content__add_bottom,
    .js_widget_builder:hover  .js_widget_builder_action,
    .js_widget_builder.js_widget_builder_active .js_widget_builder_content__add_top, 
    .js_widget_builder.js_widget_builder_active .js_widget_builder_content__add_bottom,
    .js_widget_builder.js_widget_builder_active  .js_widget_builder_action {
		display:inline-block;
	}

    .js_widget_builder_empty {
		text-align:center;
		width:300px;
		height:200px;
		margin:20px auto;
		border: 2px dashed #e5e5e5;
		background-color:#E8EDF0;
		position:relative;
		cursor: pointer;
	}
	.js_widget_builder_empty:hover {
		background-color:#4285F4;
	}
	.js_widget_builder_empty .icon-plus{
		font-size:18px;
		position:absolute;
		top:30%;
		width:100%;
		height:100%;
	}
	.js_widget_builder_empty .icon-plus i {
		font-size:50px;
	}

    /*=============== KHUNG BUILDER BÊN TRAI ==================*/

    .widget-builder-edit {
		display: block;
		position: fixed; top: 0px; left: 0px; z-index: 100;
		width: 420px; height: 100%;
		transition: all 0.3s ease-out 0s;
		visibility: hidden;
		z-index:99999;
	}
    .widget-builder-edit.is-show {
		visibility: visible;
	}

    .widget-builder-edit .widget-builder-edit__content {
		display: flex;
		justify-content: space-between;
		flex-direction: column;
		position: absolute;
		width: 100%;
		max-width: 420px;
		min-height: 100vh;
		padding:0px;
		top: 0px;
		left: 0px;
		z-index: 50;
		background: rgb(255, 255, 255);
		transition: all 0.3s ease-out 0s;
		transform: translate3d(-100%, 0, 0);
		box-shadow: 0 0 58px rgba(6,67,117,.4);
	}

	.widget-builder-edit .widget-builder-edit__content.is-show {
		transform: translate3d(0, 0, 0);
    	visibility: visible;
	}

    /* header */
    .widget-builder-edit .js_widget-builder-edit_close {
		position:absolute; right:25px; top:10px;
		z-index:99999;
		background-color:#fff;
		width:30px; height:30px; line-height:30px;
		text-align:center;
		cursor: pointer;
	}

    .widget-builder-edit .widget_header {
		display: block;
		background: #F1F5FD;
		position: absolute;
		top:0; left:0;
		width:100%;
		z-index: 50;
		padding: 20px 20px 20px 23px;
		overflow:hidden;
	}

    .widget-builder-edit .widget_header h2 { color:#637599; font-size:15px; margin:0;}

    /* body */
    .widget-builder-edit .widget-builder-edit__body {
		max-height:100vh;
		overflow:auto;
		padding-bottom:100px;
		padding-top:70px;
		position: absolute;
		width: 100%;
		top: 0;
	}

	.widget-builder-edit .widget-builder-edit__body [class*="col-md"] { width:100%; }

	.widget-builder-edit .widget-builder-edit__body label {
		color:#637599;
		font-size:12px;
	}

	.widget-builder-edit .widget-builder-edit__body .form-control {
		border: 1px solid #ccc;
    	box-shadow: none;
	}
    /* bottom */
    .widget-builder-edit__bottom {
		display: block; background: rgb(255, 255, 255);
		position: absolute;
		bottom:0; left:0;
		width:100%;
		z-index: 50; padding: 10px 23px 10px 23px; margin-bottom: 15px;
	}
	.widget-builder-edit__bottom .btn {
		width: 100%;
		display: inline-flex;
		text-transform: capitalize;
		justify-content: center;
		align-items: center;
		font-size: 15px;
		font-family: avenir-next-medium, arial;
		transition: all 0.3s ease-out 0s;
		white-space: nowrap;
		border-radius: 3px;
		cursor: pointer;
		position: relative;
		z-index: 10;
		margin-top: 0px;
		margin-bottom: 10px;
		height: 40px;
		line-height: 40px;
		padding: 0px 20px;
		background-color: rgb(255, 51, 51);
		color: rgb(255, 255, 255);
	}

    /** Service **/
	.widget_item_nosidebar {
        background-color: #33373D;
        color: #82848B;
        font-size: 15px;
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        border: 1px dashed #e5e5e5;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        padding:10px;
        margin-bottom: 10px;
    }

    .widget_item_nosidebar h3 {
        color: #fff;
        font-weight:bold;
        font-size: 15px;
        font-family:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    }
    .widget_item_nosidebar .btn-green {
        width: 100%;
        text-transform: capitalize;
        justify-content: center;
        align-items: center;
        font-size: 15px;
        font-family: avenir-next-medium, arial;
        transition: all 0.3s ease-out 0s;
        white-space: nowrap;
        border-radius: 3px;
        cursor: pointer;
        position: relative;
        z-index: 10;
        height: 40px;
        line-height: 40px;
        padding: 0px 20px;
        background-color: #1C7CF8;
        color: rgb(255, 255, 255);
    }
	.select2-container {
		z-index: 99999;
	}

    
</style>