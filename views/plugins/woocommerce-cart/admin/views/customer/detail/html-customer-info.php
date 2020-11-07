<div class="box">
	<div class="box-content">
			<section class="ui-layout__section">
                <header class="ui-layout__title"><h2>Thông tin liên hệ</h2></header>
            </section>
			<section class="ui-layout__section">
                <p class=""><span><?php echo $customer->lastname;?></span></p>
                <p class=""><span><?php echo $customer->phone;?></span></p>
                <p class=""><span><?php echo $customer->email;?></span></p>
                <p class=""><span><?php echo get_user_meta($customer->id, 'address', true);?></span></p>

                <?php
                    $city       = get_user_meta($customer->id, 'city', true);
                    $districts  = get_user_meta($customer->id, 'districts', true);
                ?>
                <p class=""><span><?php echo wcmc_shipping_states_provinces($city);?></span></p>
                <p class=""><span><?php echo wcmc_shipping_states_districts($city, $districts);?></span></p>
			</div>
	</div>

</div>