
<div class="clearfix"></div>
<div class="footer-top">
  <?php dynamic_sidebar('footer-top');?> 
</div>
<footer>
	<div class="container"> <div class="row"><?php dynamic_sidebar('footer-main');?></div> </div>
</footer>
<div class="footer-bottom">
  <div class="container">
   <p><a href="http://vitechcenter.com">© <?php echo date("Y"); ?> <?php echo get_option('general_label'); ?> - Thiết kế bởi vitechcenter.com</a></p>
 </div>
</div>
<div id="fb-root"></div>
<script type='text/javascript' defer>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.4&appId=879572492127382";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<?php echo do_action('cle_footer');?>

<a href="#" class="cd-top text-replace js-cd-top go-top"><i class="fad fa-arrow-alt-to-top"></i></a>
<style>
  .cd-top{position:fixed;bottom:10px;right:20px;background-color:#DEDEDE;color:#fff;z-index:9999;padding:5px 10px;opacity: 1;display:none;   box-shadow: 3px 3px 9px rgba(0,0,0,.5);font-size: 30px;}
  .cmi-box-style-3{bottom:245px;}
</style>
<script>
  $(document).ready(function($) {
    $(window).scroll(function(){
      if ($(this).scrollTop() > 1000 ) {
        $('.go-top').fadeIn(500);
      } else {
        $('.go-top').fadeOut(500);
      }
    });
    $('.go-top').click(function(e){
      e.preventDefault();
      $('html,body').animate({scrollTop: 0}, 3000);
    });
  });
</script>

<?php 

$cookie     = removeHtmlTags( $this->input->get( 'cookie-id' ) );
if (!empty($cookie)) {

  if (!isset($_COOKIE['user_affiliate'])) {
   
    setcookie('user_affiliate', $cookie, time() + 60*60*24*30);
  }
}
// setcookie('user_affiliate', $cookie, time() - 60*60*24*30);
//  $a=$_COOKIE['user_affiliate'];
// debug($a);
?>