<?php
/*
Plugin Name: WP-Bigfoot
Plugin URI: https://github.com/freekrai/wp-bigfoot
Description: Easier footnotes for your site, and jQuery Bigfoot for cooler effects
Author: Roger Stringer
Version: 1.0
Author URI: http://rogerstringer.com
*/ 

class WP_Bigfoot	{
	public $shared_post;

	function __construct(){
    	add_action('init', array($this, 'init'));
		add_action('wp_footer', array($this, 'flush_ob_end'));
	}

	function init() {
		global $current_user;
		ob_start( array($this,"output_callback") );

		add_action('admin_menu', array($this, 'add_admin_pages'));

		add_action( 'add_meta_boxes', array($this,'cd_meta_box_add') );

		$this->admin_page_init();
	}

	function output_callback($buffer){
		return $buffer;
	}

	function flush_ob_end(){
		ob_end_flush();
	}

	function admin_page_init() {
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'wp-bigfoot', plugin_dir_url( __FILE__ ) . 'js/wp-bigfoot.js', 'jquery', '1.4.0', true );
		wp_enqueue_style('wp-bigfoot', plugin_dir_url( __FILE__ ) . 'css/wp-bigfoot.css');
	}

	function add_admin_pages(){
#		add_submenu_page("edit.php", __('WP BigFoot', 'wpbigfoot'), __('WP BigFoot', 'wpbigfoot'), 'edit_posts', __FILE__, array($this, 'output_existing_menu_sub_admin_page'));
	}

	function process_post_options($params) {
		global $current_user;
	}

	function process_delete($key) {
		wp_delete_post( $post->ID );
	}

	function redirect($url){
		wp_redirect( $url );
		exit;
	}

	function output_existing_menu_sub_admin_page(){
?>
<div id="col-container">
	<div id="col-right">
		<div class="col-wrap">
			<div class="wrap">
				<h2><?php esc_attr_e('WP BigFoot', 'wpbigfoot'); ?></h2>
				<br />
				<br />
			</div>
		</div>
	</div>
	<div id="col-left">
		<div class="col-wrap">
		</div>
	</div><!-- /col-left -->
</div>
<?php
	}
	function cd_meta_box_add(){
#		add_meta_box( 'my-meta-box-id', 'WP BigFoot', array($this,'cd_meta_box_cb'), 'post', 'side', 'core' );
	}
	function cd_meta_box_cb( $post ){
?>
		<a class="button" href="edit.php?page=<?php echo plugin_basename(__FILE__); ?>&postid=<?php echo $post->ID?>" target="_blank">Share Draft</a><br />
		<br />
		<small>This will open a new window to the <em>WP BigFoot</em> area where you can create a new Shared Draft Link</small>
<?php
	}
}
new WP_Bigfoot();
