<div class="form-group">
    <label class="sr-only" for="">label</label>
    <?php $actions = woocommerce_order_action();  ?>
    <select name="action" class="form-control">
        <?php foreach ($actions as $key => $action): ?>
            <?php if( have_posts($action) ) { ?>
            <?php foreach ($action['value'] as $k => $value): ?>
            <option value="<?php echo $k;?>"><?php echo $value;?></option>
            <?php endforeach ?>
            <?php } else { ?>
            <option value="<?php echo $key;?>"><?php echo $action;?></option>
            <?php } ?>
        <?php endforeach ?>
    </select>
</div>