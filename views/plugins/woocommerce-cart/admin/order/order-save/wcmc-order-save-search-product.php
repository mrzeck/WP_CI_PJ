<?php
//================================ search products ===========================================//
function wcmc_order_product_search($object, $keyword) {
	return gets_product([
		'where_like' => [ 'title' => array($keyword), ]
	]);
}

add_filter('input_popover_order_search_product_search', 'wcmc_order_product_search', 10, 2);


function wcmc_pr_template_item($item, $active = '') {

    $item->variation = 0;

    $products_variations = gets_variations(['product' => $item->id]);

    $object = array();

    if( have_posts($products_variations) ) {

        foreach ($products_variations as $variation) {

            $variation->price 		= $variation->_price;

            $variation->price_sale 	= $variation->_price_sale;

            if( !empty($variation->_image) ) $variation->image = $variation->_image;

            $attr_name = '';

            foreach ($variation->items as $attr_id) {

                $attr = get_attribute_item($attr_id);

                if( have_posts($attr)) {
                    $attr_name .= $attr->title .' / ';
                }
            }

            $variation->attr_name = trim( $attr_name, ' / ');

            if( empty($variation->_stock ) ) $variation->_stock = 0;
            
            if( $variation->_stock > 0 ) {
                $variation->_status = 'instock';
            }
            else {
                $variation->_status = 'outofstock';
            }

            $item->variation = $variation->id;
            
            $variation->id   = $item->id;

            $object[] = (object)array_merge((array)$item, (array)$variation);
        }

    } else {
        $object[] = $item;
    }

    $str = '';

    foreach ($object as $item) {

        $item->image = get_img_fontend_link($item->image);

        $str .= '
        <li class="option option-'.$item->id.' '.$active.'" data-key="'.$item->id.'" data-product="'.htmlentities(json_encode($item)).'">
            <div class="item-pr">
                <div class="item-pr__img">
                    <img src="'.$item->image.'">
                </div>
                <div class="item-pr__title">
                    <span>'.$item->title.((!empty($item->attr_name)) ? ' <small style="font-size:11px;color: #29bc94;">'.$item->attr_name.'</small>' : '').'</span>
                </div>
                <div class="item-pr__price">
                    <span>'.number_format($item->price).'đ</span>
                </div>
            </div>
        </li>';
    }

    

    return $str;
}

function wcmc_order_product_search_template($str, $item, $active) {
    return wcmc_pr_template_item($item, $active);
}

add_filter('input_popover_order_search_product_search_template', 'wcmc_order_product_search_template', 10, 3);