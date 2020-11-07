<?php
    $top_bar_bg_color   = ( get_option('top_bar_bg_color') ) 	? 'background-color:'.get_option('top_bar_bg_color').';':'';
    
?>
<style type="text/css">
  .top-bar{<?=$top_bar_bg_color?>}  
  .top-bar img{width:100%;}
  
</style>