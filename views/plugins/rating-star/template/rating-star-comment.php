<div class="rating-star-comment">
    <?php foreach ($rating_star_comments as $key => $item) { ?>
    <div class="rating-star-comment-item">
        <div class="rating-star-comment__main">
            <div class="rating-star-comment__main_top">
                <span class="rating-star-comment__author-name"><?php echo $item->name;?></span>
                <span class="rating-star-comment__title"><?php echo $item->title;?></span>
            </div>
            
            <div class="rating-star-comment__message">
                <div class="rating-star-comment__rating"><?php product_rating_star_template(1,$item->star, $setting_color['star']['detail']);?></div>
                <span><?php echo $item->message;?></span>
            </div>
            <div class="rating-star-comment__time"><?php echo $item->created;?></div>
        </div>
    </div>
    <?php } ?>
</div>


<style>
    .rating-star-comment .rating-star-comment-item {
        border-bottom: 1px solid rgba(0,0,0,.09);
        padding: 1rem 0;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        padding-left: 1.25rem;
    }
    .rating-star-comment .rating-star-comment__main {
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -moz-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .rating-star-comment .rating-star-comment__author-name {
        text-decoration: none;
        color: rgba(0,0,0,.8);
        font-size: 14px;
        font-weight:bold;
    }
    .rating-star-comment .rating-star-comment__title {
        font-size: 12px;color:#2ba832;
    }
    .rating-star-comment .rating-star-comment__rating {
        margin-top: 0; display:inline-block; padding-right:20px;font-size:10px;line-height:10px;
    }
    .rating-star-comment__content {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -moz-box-orient: vertical;
        -moz-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -moz-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .rating-star-comment__time {
        margin-top: .75rem;
        font-size: 10px;
        color: rgba(0,0,0,.54);
    }
</style>