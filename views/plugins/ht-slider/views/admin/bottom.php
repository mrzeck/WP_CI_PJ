<style type="text/css" media="screen">

    .wrapper .content .page-content { margin-top: 5px; }

    .box-list-sliders { padding:10px; }

    .list-sliders .item {
        position: relative;
        display: inline-block;
        margin-bottom: 10px;
        width: 220px;
        height: 160px;
        margin-right: 10px;
    }

    .tls-addnewslider {
        border: 1px dashed #ddd;
        background: transparent;
        box-sizing: border-box;
        overflow: hidden;
    }

    .tls-title-wrapper {
        vertical-align: middle;
        position: absolute;
        bottom: 0px;
        color: #fff;
        padding: 5px 10px;
        width: 100%;
        line-height: 20px;
        background: #eee;
        box-sizing: border-box;
    }

    .tls-title, .tls-title a {
        color: #555;
        text-decoration: none;
        font-size: 11px;
        line-height: 20px;
        font-weight: 600;
    }

    .tls-addnewslider .tls-new-icon-wrapper {
        position: absolute;
        top: 0px;
        width: 100%;
        height: 100%;
        display: block;
        text-align: center;
        font-size: 35px;
    }

    .slider_list_add_buttons {
        display: block;
        position: absolute;
        left: 0px,top:0px;
        width: 100%;
        height: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: 40px 40px;
        margin-top: -10px;
    }

    .tls-firstslideimage {
        position: absolute;
        top: 0px: left:0px;
        width: 100%;
        height: 100%;
    }

    .add_new_slider_icon {
        background-image: url('<?= get_img_plugin_link('ht-slider','assets/images/new_slider.png');?>');
    }

    .tls-addnewslider:hover, .tls-addnewslider.active {
        border: 1px solid #242424;
    }

    .tls-addnewslider:hover .tls-title-wrapper, .tls-addnewslider.active .tls-title-wrapper {
        background: #252525;
    }
    
    .tls-addnewslider:hover .tls-title, .tls-addnewslider.active .tls-title {
        color:#fff;
    }

</style>