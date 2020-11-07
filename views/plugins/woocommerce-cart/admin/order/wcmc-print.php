<?php
function wcmc_order_detail_print( $order ) {
	?>
	<button type="button" class="btn btn-default" onclick="PrintElem('order-print')"><i class="fal fa-print"></i> In đơn hàng</button>

	<div class="hidden">
        <?php wcmc_get_template_cart('admin/order/html-order-print', array('order' => $order)); ?>
	</div>

    <script>
        function PrintElem(elem)
        {
            var mywindow = window.open('', 'PRINT');

            mywindow.document.write('<html><head><title>' + document.title  + '</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }
    </script>

	<?php
}

add_action( 'order_detail_header_action', 'wcmc_order_detail_print' );