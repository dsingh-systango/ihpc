<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header();

/*
* Start: Creating a query argument based on request parameters
*/
$search_args = array('post_type' => 'companies');
if( !empty($_REQUEST['model']) ){
	$search_args['tax_query'] = array(array('taxonomy'=>'companiestax','field'=>'id','terms'=> $_REQUEST['model'],'operator'=>'IN'));
}
if( !empty($_REQUEST['location']) ){
	$search_args['meta_key'] 	= 'company_location';
	$search_args['meta_value'] = $_REQUEST['location'];	
}
if( !empty($_REQUEST['company_name']) ){
	global $wpdb;
	$mypostids = $wpdb->get_col("select ID from $wpdb->posts where post_title LIKE '%".$_REQUEST['company_name']."%' ");
	//$search_args['title'] = $_REQUEST['company_name'];
	$search_args['post__in'] = $mypostids;
}
if( !empty($_REQUEST['model']) || !empty($_REQUEST['location']) || !empty($_REQUEST['company_name']) ){
	$GLOBALS['wp_query'] = new WP_Query( $search_args );	
}
/*
* End;
*/
?>
	<?php if ( have_posts() ) : ?>
		<h1>Browse Companies</h1>
	<?php endif; ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="search-box ">
				<?php $companycount = $GLOBALS['wp_query']->post_count; ?>
				<label>All companies <?php echo $companycount; ?> </label>
				<form action="" method="post" class="clearfix">
					<div class="search-for-car clearfix">
						<div class="inner-search">
							<div class="col-lg-12 col-md-12  col-sm-12 col-xs-12">
								<input required="required" name="company_name" id="company_name" class="form-control search-input width-100" placeholder="Company Name" type="text" />
							</div>
						</div>
						<input value="" class="btn-style inner-search-button " type="submit" data-toggle="modal" data-target="#reviews-by-location" />
					</div>
				</form>
			</div>
			<div class="clearfix"></div>
			<strong class="red_bold">Most complained about</strong> 
		</div>
	</div>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		if ( have_posts() ) : ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				//get_template_part( 'template-parts/post/content', get_post_format() );
				get_template_part( 'template-parts/post/content', 'company' );
			endwhile;
			the_posts_pagination( array(
				'prev_text' => ihpc_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'ihpc' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'ihpc' ) . '</span>' . ihpc_get_svg( array( 'icon' => 'arrow-right' ) ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'ihpc' ) . ' </span>',
			) );
		else :
			get_template_part( 'template-parts/post/content', 'none' );
		endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
$args = array('hide_empty' => FALSE,'taxonomy' => 'companiestax');

$terms = get_terms($args);
//echo '<pre>';
//print_r($terms);

?>

<?php get_footer();
