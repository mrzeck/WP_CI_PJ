<?php
class widget_price_range_filter extends widget {

    function __construct() {

        parent::__construct('widget_price_range_filter', 'Tìm Kiếm Theo Giá </br>(price range slider)');

        $this->tags = array('sidebar', 'footer');
        
    }

    function widget( $option ) {
        $loc='';
        $loc_get='';
        $max_price_get=0;
        $min_price_get=0;
        $sales_get=0;
        $ci=&get_instance();
        
        if ($ci->input->get('max_price')){
            $max_price_get=$ci->input->get('max_price');
        }

        if ($ci->input->get('min_price')) {
            $min_price_get=$ci->input->get('min_price');
        }
        $a=$ci->input->get();
        if (isset($a['min_price'])) {
            unset($a['min_price']);
        }
        if (isset($a['max_price'])) {
            unset($a['max_price']);
        }
        $url=base_url().'san-pham';
        if (count($a) !=0) {
            foreach ($a as $key => $val) {
                
                   $url.='?'.$key.'='.$val;
                unset($a[$key]);
                break;
                    
                
            }
            if (count($a) !=0) {
                foreach ($a as $key => $val) {
                    $url.='&'.$key.'='.$val;
                }
            }
            
            $url.='&';
        }else{
            $url.='?';
        }
        ?>
        <div class="sidebar-title"><h3><?php echo $this->name;?></h3></div>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <?php 

        $min_price = 0;
        $max_price = 300000000;

        $p_min = (int)$ci->input->get('min_price');
        $p_max = (int)$ci->input->get('max_price');

        if(is_numeric($p_min) && $p_min >= $min_price ) $min_price = $p_min;
        if(is_numeric($p_max) && $p_max > $min_price) $max_price = $p_max;
        if ($min_price > $max_price) {
            $c=$min_price;
            $min_price=$max_price;
            $max_price=$c;
        }

        ?>
        <form action="" id="search-form" method="get">
            <aside class=" widget_product_price_slide">
                <div id="slider-container"></div>
                <input disabled type="text" id="amount"/>  
                <input type="hidden" name="min_price" id="amount-min" value="<?= $p_min;?>" />
                <input type="hidden" name="max_price" id="amount-max" value="<?= $p_max;?>" />
            </aside>
            <!--==========================================================================================================================================================-->
        </form>
<style>
#amount{    width: 100%;text-align: center;margin-top: 10px;background-color: transparent;border: 0;}
</style>
<script  defer>
$(function(){
        $(document).on('ifClicked', '#search-form input', function(event) {
            if($(this).is(':checked')) {
                $(this).prop('checked', false);
            }
            else {
                $(this).prop('checked', true);
            }
            $('#search-form').submit();
        });

        jQuery('#slider-container').slider({
            range: true,
            min: 0,
            max: 300000000,
            values: [<?= $min_price ;?>, <?= $max_price ;?>],
            create: function() {
                jQuery("#amount").val("<?= number_format( $min_price ) ;?> đ - <?= number_format( $max_price ) ;?> đ");
            },
            slide: function (event, ui) {
               
                
                $("#amount").val(numberWithCommas(ui.values[0]) + " đ - " + numberWithCommas(ui.values[1])+" đ");
                var min_price = '';
                var arr_min_price = numberWithCommas(ui.values[0]);
                $.each(arr_min_price.split(','),function(i,v){
                    min_price = min_price + v;
                });
                $('#amount-min').val(parseInt(min_price));
                var max_price = '';
                var arr_max_price = numberWithCommas(ui.values[1]);
                $.each(arr_max_price.split(','),function(i,v){
                    max_price = max_price + v;
                });
                $('#amount-max').val(parseInt(max_price));
                var mi = ui.values[0];
                var mx = ui.values[1];
                filterSystem(mi, mx);         
            },
            stop: function(event, ui) {
                var mi =$('#amount-min').val();
                var mx = $('#amount-max').val();
                var url='<?=$url?>'+ 'min_price=' + mi + '&max_price=' + mx;
                 window.location.href = url;
                // $('#search-form').submit();
            }
        });
        function filterSystem(minPrice, maxPrice) {
            jQuery("#product-sorting li.system").hide().filter(function () {
                var price = parseInt($(this).data("price"), 10);
                return price >= minPrice && price <= maxPrice;
            }).show();
        }
        function numberWithCommas(number) {
            if (isNaN(number)) {
                return '';
            }

            var asString = '' + Math.abs(number),
                    numberOfUpToThreeCharSubstrings = Math.ceil(asString.length / 3),
                    startingLength = asString.length % 3,
                    substrings = [],
                    isNegative = (number < 0),
                    formattedNumber,
                    i;

            if (startingLength > 0) {
                substrings.push(asString.substring(0, startingLength));
            }

            for (i=startingLength; i < asString.length; i += 3) {
                substrings.push(asString.substr(i, 3));
            }

            formattedNumber = substrings.join(',');
            if (isNegative) {
                formattedNumber = '-' + formattedNumber;
            }

            return formattedNumber;
        };
});
</script>

        <?php
        
    }

    function form( $left = array(), $right = array()) {

        $right[] = array('field' => 'bg_color', 'label' =>'Màu nền', 'type' => 'color', 'value' => '#464646');
        $left[] = array('field' => 'min_price', 'label' =>'Min price (vnd)', 'type' => 'text', 'value' => 0);
        $left[] = array('field' => 'max_price', 'label' =>'Max price (vnd)', 'type' => 'text', 'value' => 300000000);

        parent::form($left, $right);
    }
}

register_widget('widget_price_range_filter');