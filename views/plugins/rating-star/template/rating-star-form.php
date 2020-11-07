<?php
    $object_type = (isset($object->post_type))? 'post_'.$object->post_type : 'product';

    $rating_star_setting = get_rating_star_setting();

    $setting_form = $rating_star_setting['form'];

    $setting_color = $rating_star_setting['color'];

    $rating_star_comments = gets_rating_star([
        'where' => [
            'object_id'     => $object->id,
            'object_type'   => $object_type,
            'status'        => 'public'
        ]
    ]);

    $rating_star = get_metadata($object_type, $object->id, 'rating_star', true );
	
	$total_star = (isset($rating_star['star'])) ? $rating_star['star'] : 0;

    $total_count = (isset($rating_star['count'])) ? $rating_star['count'] : 0;
?>

<div class="rating-star-form">

    <div class="rating-star-review">
        <div class="rsr__left">
            <b><?php echo ($total_count != 0)?$total_star/$total_count:0;?> <i class="fas fa-star"></i></b>
        </div>
        <div class="rsr__right">
            <?php for ( $i = 5; $i > 0 ; $i--) { ?> 
            <?php 
                if($total_count == 0) {
                    $count      = 0;
                    $percent    = 0;
                }
                else {
                    $count = count_rating_star(['where' => ['star' => $i, 'object_type' => $object_type, 'object_id' => $object->id]]);
                    $percent = $count/$total_count*100;
                }
            ?>
            <div class="r">
                <span class="t"><?php echo $i;?> <i class="fas fa-star"></i></span>
                <div class="bgb"> <div class="bgb-in" style="width: <?php echo $percent;?>%"></div> </div>
                <span class="c"><strong><?php echo $count;?></strong> đánh giá</span>
            </div>
            <?php } ?>
        </div>
        <div class="rsr__right">
            <a href="" class="show_rating_reviews__form">Gửi đánh giá của bạn</a>
        </div>
    </div>

    <?php echo notice('success', 'hello');?>

    <?php echo notice('error', 'hello');?>

    <form method="POST" id="rating-reviews__form" autocomplete="off" style="display:none;">

        <input name="object_id" type="hidden" value="<?php echo $object->id;?>">

        <input name="object_type" type="hidden" value="<?php echo $object_type;?>">

        <div class="ips">
            <span>Chọn đánh giá của bạn</span>

            <select name="rating" id="rating">
                <option value="1" data-html="không thích">1</option>
                <option value="2" data-html="Tạm được">2</option>
                <option value="3" data-html="Bình thường">3</option>
                <option value="4" data-html="Rất tốt">4</option>
                <option value="5" data-html="Tuyệt vời quá">5</option>
            </select>
        </div>
        

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <textarea name="rating_star_message" class="form-control" rows="5" required placeholder="Nội dung"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <?php if($setting_form['name']['required'] != 'hiden') {?>
                    <div class="form-group col-md-6">
                        <input name="rating_star_name" type="text" class="form-control" placeholder="<?php echo $setting_form['name']['label'];?>" <?php if($setting_form['name']['required'] == 'required') echo 'required';?>>
                    </div>
                    <?php } ?>

                    <?php if($setting_form['email']['required'] != 'hiden') {?>
                    <div class="form-group col-md-6">
                        <input name="rating_star_email" type="email" class="form-control" placeholder="<?php echo $setting_form['email']['label'];?>" <?php if($setting_form['email']['required'] == 'required') echo 'required';?>>
                    </div>
                    <?php } ?>
                    
                    <?php if($setting_form['title']['required'] != 'hiden') {?>
                    <div class="form-group col-md-6">
                        <input name="rating_star_title" type="text" class="form-control" placeholder="<?php echo $setting_form['title']['label'];?>" <?php if($setting_form['title']['required'] == 'required') echo 'required';?>>
                    </div>
                    <?php } ?>

                    <div class="form-group col-md-6">
                        <button type="submit" class="btn btn-primary btn-block">Gửi</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<?php include 'rating-star-comment.php';?>

<style>
    .rating-star-form {
        margin: 0 auto;
        background-color:#fff;
        padding:10px;
        margin-top: 10px;
        border: solid 1px #eee;
        border-radius:10px;
    }

    .rating-star-form .rating-star-review {
        height: 150px;
        box-sizing: border-box;
        overflow:hidden;
    }
    
    .rating-star-form .rating-star-review .rsr__left {
        width: 20%;
        float: left;
        border-right: solid 1px #eee;
        padding-top: 51px;
        height: 150px;
        text-align: center;
        box-sizing: border-box;
    }

    .rating-star-form .rating-star-review .rsr__left b {
        font-size: 40px;
        color: #fd9727;
        line-height: 40px;
    }
    .rating-star-form .rating-star-review .rsr__left b i {
        vertical-align: initial;
        width: 32px;
        height: 32px;
    }

    .rating-star-form .rating-star-review .rsr__right {
        font-size: 13px;
        overflow: hidden;
        box-sizing: border-box;
        padding: 10px 0;
        width: 40%;
        float: left;
        border-right: solid 1px #eee;
        height: 150px;
    }

    .rating-star-form .rating-star-review .rsr__right .r {
        padding: 1px 20px;
    }
    .rating-star-form .rating-star-review .rsr__right span.t {
        display: inline-block;
        color: #333;
    }
    .rating-star-form .rating-star-review .rsr__right span.t i {
        width: 12px;
        height: 12px;
        display: inline-block;
    }
    .rating-star-form .rating-star-review .rsr__right .bgb {
        width: 55%;
        background-color: #e9e9e9;
        height: 5px;
        display: inline-block;
        margin: 0 10px;
        border-radius: 5px;
    }
    .rating-star-form .rating-star-review .rsr__right .bgb .bgb-in {
        background-color: #f25800;
        background-image: linear-gradient(90deg,#ff7d26 0%,#f25800 97%);
        height: 5px;
        border-radius: 5px 0 0 5px;
        max-width: 100%;
    }
    .rating-star-form .rating-star-review .rsr__right a {
        display: block;
        width: 200px;
        margin: 41px auto 0;
        padding: 10px;
        color: #fff;
        background-color: #288ad6;
        border-radius: 5px;
        text-align: center;
        box-sizing: border-box;
    }

    .rating-star-form .toast { display:none; }

    .br-theme-fontawesome-stars .br-widget a { font-family: "Font Awesome 5 Pro"; font-weight: 900; }
    .br-theme-fontawesome-stars .br-widget a:after {
        content:'\f005'; font-weight: 400;
    }
    .br-theme-fontawesome-stars .br-widget a.br-selected:after {
        color: <?php echo $setting_color['star']['form'];?>;
    }
    .br-theme-fontawesome-stars .br-widget a.br-active:after {
        color: <?php echo $setting_color['star']['form'];?>;
    }
    .rating-star-form .ips { margin:10px 0; }
    .rating-star-form .ips span,
    .rating-star-form .ips div { display:inline-block; }
    .rating-star-form .ips span { padding-right:10px;}

    .rating-star-form .form-control {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        list-style: none;
        position: relative;
        padding: 7px 13px;
        width: 100%;
        font-size: 13px;
        line-height: 1.5;
        color: rgba(0, 0, 0, 0.65);
        background-color: #fff;
        background-image: none;
        border: 1px solid #e4e9f0!important;
        border-radius: 4px!important;
        transition: all .3s;
        box-shadow: none;
    }

    .rating-star-form input.form-control { height:45px; }

    .rating-star-form .btn {
        padding:10px; background-color:#2AAAE0;
    }

    @media(max-width:768px) {
        .rating-star-form .rating-star-review { height:auto; }
        .rating-star-form .rating-star-review .rsr__left { width:100%; }
        .rating-star-form .rating-star-review .rsr__right { 
            width:100%;padding-top: 20px;
            padding-bottom: 20px;
            height: auto;
        }
        
        .rating-star-form .rating-star-review .rsr__right .bgb { width:46%;}
        .rating-star-form .rating-star-review .rsr__right a { margin-top:0px;}
    }

</style>

<script type="text/javascript">
    $(function(){

        $('.show_rating_reviews__form').click(function(){

            $('#rating-reviews__form').toggle();

            return false;
        });

        $('#rating').barrating({
            theme: 'fontawesome-stars',
            showSelectedRating: false
        });

        $('#rating-reviews__form').submit(function(){

            var data = $(this).serializeArray();

            data.push({
                'name'	:'action',
                'value' :'ajax_rating_star_save'
            });

            $jqxhr = $.post(base+'/ajax', data, function(){}, 'json');

            $jqxhr.done(function(data){

                if(data.type == 'success') {

                    $('.rating-star-form .toast').hide();

                    $('.rating-star-form .toast--'+data.type+' .toast__content .toast__message').html(data.message);

                    $('.rating-star-form .toast--'+data.type+'').show();

                    $('#rating-reviews__form').trigger('reset');
                }
            });

            return false;
        });

    })
</script>