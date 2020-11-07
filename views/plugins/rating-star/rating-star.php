<?php
/**
Plugin name     : Rating Star
Plugin class    : rating_star
Plugin uri      : http://sikido.vn
Description     : Với ứng dụng Rating Star, bạn có thể thu được nhiều đánh giá hơn bất kì một cách thức nào khác. Chúng đã được duyệt kỹ lưỡng trước khi được hiển thị trên website của bạn
Author          : Nguyễn Hữu Trọng
Version         : 1.1.1
*/
define( 'RATING_STAR_NAME', 'rating-star' );

define( 'RATING_STAR_PATH', plugin_dir_path( RATING_STAR_NAME ) );

class rating_star {

    private $name = 'rating_star';

    public  $ci;

    function __construct() {
        $this->ci =&get_instance();
    }

    public function active() {
			rating_star_database_table_create();
		}

    public function uninstall() {
			rating_star_database_table_drop();
		}
}

include 'rating-star-database.php';

include 'rating-star-helper.php';

include 'rating-star-template.php';

include 'rating-star-ajax.php';

if(is_admin()) {

	include 'rating-star-admin.php';
}
else {

	if ( ! function_exists( 'rating_star_style_header' ) ) {

		function rating_star_style_header() {

			cle_register_style('rating-star', RATING_STAR_PATH.'jquery-bar-rating/themes/fontawesome-stars.css');
		}

		add_action('cle_enqueue_style','rating_star_style_header');
	}

	if ( ! function_exists( 'rating_star_style_footer' ) ) {

		function rating_star_style_footer() {

			cle_register_script('rating-star', RATING_STAR_PATH.'jquery-bar-rating/jquery.barrating.min.js');
		}

		add_action('cle_enqueue_script', 'rating_star_style_footer');
	}

}

