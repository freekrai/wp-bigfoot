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
    private $footnotes = array();
    private $option_name = 'wp_bigfoot';
    private $db_version = 1;
    private $placement = 'content';

	public $shared_post;

	function __construct(){
    	add_action('init', array($this, 'init'));
		add_action('wp_footer', array($this, 'footer'));
	}

	function init() {
		global $current_user;
		add_action('admin_menu', array($this, 'add_admin_pages'));

		add_action( 'add_meta_boxes', array($this,'cd_meta_box_add') );

		add_shortcode('footnote', array($this,'shortcode_footnote') );
        add_filter( 'the_content', array($this, 'the_content' ), 12 );

		$this->admin_page_init();
	}
	function footer(){
/*
?>
		<script>$.bigfoot({ actionOriginalFN: "ignore"});</script>
<?php
*/
	}

	function admin_page_init() {
		wp_enqueue_script('jquery');
		if( is_admin() ){
			wp_enqueue_style('wp-bigfoot', plugin_dir_url( __FILE__ ) . 'css/wp-bigfoot.css');
		}else{
			wp_enqueue_script( 'bigfoot', plugin_dir_url( __FILE__ ) . 'js/bigfoot.min.js', 'jquery', '1.4.0', true );
			wp_enqueue_script( 'wp-bigfoot', plugin_dir_url( __FILE__ ) . 'js/wp-bigfoot.js', 'jquery', '1.4.0', true );
			wp_enqueue_style('wp-bigfoot', plugin_dir_url( __FILE__ ) . 'css/bigfoot-default.css');
		}
	}

	function add_admin_pages(){
#		add_submenu_page("edit.php", __('Footnotes', 'wpbigfoot'), __('Footnotes', 'wpbigfoot'), 'edit_posts', __FILE__, array($this, 'output_existing_menu_sub_admin_page'));
	}

	function shortcode_footnote( $atts, $content=NULL ){
        global $id;
		if ( null === $content )	return;
		if ( ! isset( $this->footnotes[$id] ) ) $this->footnotes[$id] = array();
		$this->footnotes[$id][] = $content;
		$count = count( $this->footnotes[$id] );
		return '<a href="#footnote-' . $count . '-' . $id . '" ' . 'id="note-' . $count . '-' . $id . '" ' . 'rel="footnote">' . $count . '</a>';
	}
	
	function the_content($content) {
        return $this->get_footnotes( $content );
	}

    function get_footnotes( $content ) {
        global $id;
        if ( empty( $this->footnotes[$id] ) )	return $content;
		$footnotes = $this->footnotes[$id];
		if( count($footnotes) ){
			$content .= '<div class="footnotes">';
			$content .= '<hr />';
			$content .= '<ol>';
			foreach ( $footnotes as $number => $footnote ): 
				$number++;
				$content .= '<li id="footnote-'.$number.'-'.$id.'" class="footnote">';
				$content .= '<p>';
				$content .= $footnote;
				$content .= '<a href="#note-'.$number.'-'.$id.'" class="footnote-return">&#8617;</a>';
				$content .= '</p>';
				$content .= '</li><!--/#footnote-'.$number.'.footnote-->';
			endforeach;
			$content .= '</ol>';
			$content .= '</div><!--/#footnotes-->';
		}
        return $content;
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
