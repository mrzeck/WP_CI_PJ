<div class="item">
    <div class="img">     
        <a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= get_img($val->image,$val->title, array());?></a>
    </div>
    <div class="title">
        <h3><a href="<?= get_url($val->slug);?>" title="<?= $val->title;?>"><?= $val->title;?></a></h3>
        <!-- <div class="excerpt"><?= str_word_cut(removeHtmlTags($val->excerpt), 20);?></div> -->
    </div>
</div>