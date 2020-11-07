<div class="wcmc-product-cart-options">
    <div class="title"> <?php echo $option['title'];?>: <span class="option-type__label option-type__selected-option"></span></div>
    <div class="options">
        <?php
        foreach ($option['items'] as $key => $attribute):

            $data = array('option' => $option, 'attribute' => $attribute );

            if($option['option_type'] == 'label')  wcmc_get_template_cart('detail/cart-option-item-label', $data);

            if($option['option_type'] == 'color')  wcmc_get_template_cart('detail/cart-option-item-color', $data);

            if($option['option_type'] == 'image')  wcmc_get_template_cart('detail/cart-option-item-image', $data);
            
        endforeach;
        ?>
    </div>
</div>