<div class='cmi-box-style-4 phone-<?php echo $style['cmi_position'];?>'>
    <ul>
        <?php if( have_posts($style['icon']) ) {?>
            <?php foreach ($style['icon'] as $key => $icon): ?>
                <li class="<?php echo $icon['class'];?> wow <?php echo $style['cmi_animate'];?>" data-wow-delay="<?php echo $key/2;?>s"><a href="<?php echo $icon['url'];?>" target="_blank">
                    <?php get_img($icon['image'], $icon['alt']);?>
                </a></li>
            <?php endforeach ?>
        <?php } ?>
    </ul>
</div>