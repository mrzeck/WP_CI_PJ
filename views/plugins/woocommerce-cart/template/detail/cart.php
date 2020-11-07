<?php
    $meta_options = woocommerce_product_data_variations();

    $data_product_variations = array();

    $variations = gets_variations( ['product' => $object->id, 'where' => array('status' => 'public')] );

    if( have_posts($variations) ) {

        $item_variations = array();

        foreach ($variations as $variation) {

            if(!isset($variation->items)) continue;

            foreach ($variation->items as $id_option => $id_item ) {

                $item_variations[$id_option][] = $id_item;
            }
        }

        foreach ($meta_options as $key_options => &$options ) {

            foreach ($options['items'] as $key_item => $items ) {
                if( in_array( $items->id, $item_variations[$options['id']] ) === false ) unset($options['items'][$key_item]);
            }
        }

        if( have_posts($meta_options) ) {

            foreach ($meta_options as $key_meta_options => $meta_option ) {

                $data_product_variations[$meta_option['id']] = array();

                foreach ( $meta_option['items'] as $key => $item ) {

                    foreach ($variations as $variation) {

                        if(!isset($variation->items)) continue;

                        if( in_array($item->id, $variation->items) !== false ) {

                            foreach ($variation->items as $id_option => $id_item ) {

                                if( $id_item == $item->id ) continue;

                                $data_product_variations[$meta_option['id']][$item->id][$id_option][] = $id_item;
                            }
                        }
                        
                    }
                }
                
            }
        }
    }
?>
<?php if(have_posts($meta_options)) { ?>
<form class="wcm-box-options" id="wcmc-form-options" data-product-option="<?php echo htmlspecialchars(json_encode($data_product_variations));?>" data-product-variations="<?php echo htmlspecialchars(json_encode($variations));?>">
<?php 
foreach ($meta_options as $key => $option):
	woocommerce_product_show_variations($option);
endforeach; ?>
</form>
<?php } ?>

<div class="wcmc-alert-product"></div>

<div class="wcmc-product-cart">
    <div class="quantity-title" style="overflow: hidden;height: auto;">
        <div class="pull-left"><?php echo __('Số lượng', 'wcmc_soluong');?> :</div>
    </div>
    <div style="overflow: hidden;">
        <div class="addtocart_quantity">
            <input id="quantity" type="number" name="quantity" value="1" min="1" class="form-control quantity-selector">
        </div>
        <div class="addtocart_button">
            <button class="btn btn-lg btn-effect-default btn-red button_cart wcmc_add_to_cart" data-id="<?php echo $object->id;?>">
                <span><?php echo __('Thêm vào giỏ hàng', 'wcmc_add_to_cart');?></span>
            </button>
            <button class="btn btn-lg btn-effect-default btn-green button_cart button_cart_green wcmc_add_to_cart_now" data-id="<?php echo $object->id;?>">
                <span><?php echo __('Mua Ngay', 'wcmc_add_to_cart_now');?></span>
            </button>
        </div>
    </div>
</div>