<form action="" method="post" id="customer_form__created">
    <?php echo form_open();?>
    
    <div class="ui-layout customer-sections">
        <div class="col-md-12">
            <div class="ui-title-bar__group">
                <h1 class="ui-title-bar__title">Thêm mới khách hàng</h1>
                <div class="ui-title-bar__action">
                    <?php do_action('customer_created_header_action');?>
                </div>
            </div>
        </div>
    </div>

    <div class="ui-layout customer-sections">
        <div class="col-md-8">
            <?php 
            /**
             * customer_created_sections_primary
             */
            echo do_action('customer_created_sections_primary');
            ?>
        </div>
        <div class="col-md-4">
            <?php 
            /**
             * customer_created_sections_secondary
             */
            echo do_action('customer_created_sections_secondary');
            ?>
        </div>
    </div>
</form>
<style type="text/css">
	.ui-layout {
		box-sizing: border-box;
	    max-width: 1100px;
	    margin-right: auto;
	    margin-left: auto;
	    font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
	}

	.ui-layout .box {
		border-radius: 3px;
		-webkit-box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
    	box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
	}

    section.ui-layout__section {
        padding:20px;
    }

    section.ui-layout__section+section.ui-layout__section {
	    border-top: 1px solid #dfe4e8;
    }
    section.ui-layout__section~section.ui-layout__section {
        border-top: 1px solid #ebeef0;
    }
    
    section.ui-layout__section header.ui-layout__title h2 {
		font-size: 18px; font-weight: 600; line-height: 2.4rem; margin: 0;
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 auto;
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    min-width: 0;
    	max-width: 100%;
        display:inline-block;
	}
</style>