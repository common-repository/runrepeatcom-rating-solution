<?php
/*
Plugin Name: RunRepeat.com Rating Solution
Plugin URI: http://runrepeat.com/
Description: just use the shortcode [runrepeatratings] anywhere you want to display rating, for more details go to Tools > RunRepeat Solutions
Version: 1.2.4
Author: RunRepeat.com
Author URI: http://runrepeat.com/
Copyright: RunRepeat.com
*/


class RRsolutions {
	public $plugin;

	public function __construct() {
		$this->plugin = plugin_basename( __FILE__ );
		add_filter( "plugin_action_links_$this->plugin", array($this, 'rr_plugin_action_links') );
		add_action( 'admin_menu', array($this, 'rr_add_admin_menu') );
		add_action( 'admin_init', array($this, 'rr_rating_sc_tinymce_button') );
		foreach ( array( 'post.php', 'post-new.php' ) as $hook ) {
			add_action( "admin_print_scripts-$hook", array($this, 'rr_add_admin_js') );
		}
	}

	function rr_plugin_action_links( $links ) {
		$settings_link = '<a href="tools.php?page='. $this->plugin  .'">How to</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	function rr_add_admin_menu() {
		add_submenu_page( 'tools.php', 'RunRepeat Solutions', 'RunRepeat Solutions', 'manage_options', __FILE__, array($this, 'rr_make_options_page') );
	}

	function rr_make_options_page() {
		?>
		<div class="wrap">
			<h1>RunRepeat Runnig shoe reviews</h1>
			<p>To display the Shoe review on your page just insert the shortcode <b>[runrepeatratings]</b> anywhere on te page where you want to display the rating. A special button is added on the editor [RR] that can
				be used to add the shortcode on your page<br>
				<img src="<?php echo plugins_url( '/', __FILE__ ); ?>/img/screenshot-1.png">
			</p>
			<p>By default the post name is used to match the shoe with our database* but this can be changed from post name to page title by adding the attribute [runrepeatratings name='page_title']. You do not need to
				add the actual title. You can also request a <a href="https://en.wikipedia.org/wiki/Web_crawler" target="_blank">page crawl</a> by adding the attribute [runrepeatratings name='do_crawl']</p>
			<p><b>[runrepeatratings name='do_crawl']</b> is good to use when you have special needs and shoe title have to be extracted from other parts of the site. In this case please contact us at the email address <a href="mailto:webmaster@runrepeat.com">webmaster@runrepeat.com</a></p>
			<p>You can also manually specify the shoe name <b>[runrepeatratings name='<code>Shoe name here</code>']</b></p>
			<p>To manually insert the code (not recommended) on your page use <a href="http://solutions.runrepeat.com/rating/documentation/">this documentation</a>.</p>
		</div>
		<?php
	}

	function rr_rating_sc_tinymce_button() {
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array($this, 'rr_rating_sc_register_tinymce_button') );
			add_filter( 'mce_external_plugins', array($this, 'rr_rating_sc_add_tinymce_button') );
		}
	}

	function rr_rating_sc_register_tinymce_button( $buttons ) {
		array_push( $buttons, "button_green" );
		return $buttons;
	}

	function rr_rating_sc_add_tinymce_button( $plugin_array ) {
		$plugin_array['my_button_script'] = plugins_url( '/js/review_sc_button.js', __FILE__ );
		return $plugin_array;
	}


	function rr_add_admin_js() {
		?>
		<script type='text/javascript'>
			var rr_plugin = {
				'url': '<?php echo plugins_url( '/', __FILE__ ); ?>',
			};
		</script>
		<?php
	}

}

if(is_admin()){
	$RRsolutions = new RRsolutions();
}

add_shortcode( 'runrepeatratings', function ( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'name' => '',
		'id'   => '', //Not yet implemented
	), $atts ) );

	if ( $name == 'page_title' ) {
		$t = "encodeURIComponent(document.title)";
	} elseif ( $name == 'do_crawl' ) {
		$t = "do_crawl";
	} elseif ( $name ) {
		$t = rawurlencode( $name );
	} else {
		global $post;
		$t = rawurlencode( $post->post_title );
	}
	?>
	<!-- RunRepeat.com Shoe review -->
	<div id="rr_rating" data-name="<?php echo $t; ?>"></div>
	<!--END RunRepeat.com Shoe review -->
	<?php
} );

function rr_include_scripts() {
	wp_enqueue_script( 'RRsolutions', plugins_url( '/', __FILE__ ) . '/js/rr_solutions.js', array(), '1.2', true );
}

add_action( 'wp_enqueue_scripts', 'rr_include_scripts' );