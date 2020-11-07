<form action="san-pham" method="get" class="form-horizontal" role="form" id="woocommerce-filter-form">
	<?php do_action('woocommerce_filter_html');?>
</form>

<style type="text/css">
ul.wcmc-filter-list {
	margin: 0;
    padding: 0;
    border: 0;
    list-style: none outside;
}

ul.wcmc-filter-list li, ul.wcmc-filter-list li {
    margin: 0 10px;
    padding: 2px 0;
    border: none;
}
ul.wcmc-filter-list li a, ul.wcmc-filter-list li a {
    position: relative;
    padding-left: 30px;
    border-bottom: 0;
    display: inline-block;
}
ul.wcmc-filter-list li a::before, ul.wcmc-filter-list li a::before {
    content: '';
    font-size: 15px;
    color: #222;
    position: absolute;
    top: 1px;
    left: 0;
    width: 20px;
    height: 20px;
    border-radius: 3px;
    border: 1px solid #999;
}

ul.wcmc-filter-list li a:active::before, ul.wcmc-filter-list li a:focus::before, ul.wcmc-filter-list li a:hover::before, ul.wcmc-filter-list li.chosen a::before, ul.wcmc-filter-list li a:active::before, ul.wcmc-filter-list li a:focus::before, ul.wcmc-filter-list li a:hover::before, ul.wcmc-filter-list li.chosen a::before {
    font-family: "Font Awesome 5 Free"; font-weight: 900; content: "\f00c";
    color: #e03232;
    font-size: 15px;
    position: absolute;
    top: 1px;
    left: 0;
    border-color: #e03232;
    line-height: 20px;
    text-align: center;
}
</style>

<style type="text/css">
#woocommerce-filter-form .box-option-color { width:60px; height: 30px; display: inline-block; text-indent: -9999px; }
#woocommerce-filter-form .box-option-label { width:60px; height: 30px; display: inline-block; border:1px solid #f2f2f2; color:#333; line-height: 30px; text-align: center; }
#woocommerce-filter-form label.wcmc-label-attribute { cursor: pointer; }
#woocommerce-filter-form label.wcmc-label-attribute.chosen { border:2px dotted red; }
</style>