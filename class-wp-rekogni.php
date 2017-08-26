<?php

use Aws\Rekognition\RekognitionClient;

class WP_Rekogni {
	
	private $admin;

	const SLUG = 'wp_rekogni';
	const TEXT_DOMAIN = 'wp-rekogni';
	
	private $option = array();
	
	private $assign_setting = array();
	
	public function __construct() {
		$this->admin = New WP_Rekogni_Admin();		
		$this->option = get_option( self::SLUG, array( 'region' => '', 'key' => '', 'secret' => '' ) );
		$this->assign_setting = $this->get_assign_setting();
	}
	
	public function get_assign_setting() {
		return apply_filters( 'wp_rekogni_assign_setting', array( 'post' => 'post_tag' ) );
	}
	
	public function enable_options() {
		if ( count($this->option) == 0 ) {
			return false;
		}
		foreach ( $this->option as $val ) {
			if ( $val == '' ) {
				return false;
			}
		}
		return true;
	}

	public function register_hooks() {

		add_action( 'plugins_loaded',	array( $this, 'plugins_loaded' ) );		
		
		add_action( 'admin_init', array( $this->admin, 'admin_init' ) );
		add_action( 'admin_menu', array( $this->admin, 'admin_menu' ) );
		
		
		if ( ! $this->enable_options() ) {
			return;
		}
		$post_types = array_keys( $this->assign_setting );
		foreach ( $post_types as $post_type ) {
			add_filter( 'bulk_actions-edit-'.$post_type, array( $this, 'bulk_actions_edit' ), 10, 2 );
			add_filter( 'handle_bulk_actions-edit-'.$post_type, array( $this, 'handle_bulk_actions_edit' ), 10, 3 );
		}
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 10, 2 );
	}
	
	public function plugins_loaded(){
		load_plugin_textdomain( self::SLUG, false, dirname( plugin_basename( __FILE__ ) ).'/languages');
	}
	
	public function bulk_actions_edit( $bulk_actions ) {
		$bulk_actions['assign_tags_by_image_rekognition'] = __( 'Assign Tags by Image Rekognition', self::TEXT_DOMAIN );
		return $bulk_actions;
	}
	
	public function handle_bulk_actions_edit( $redirect_to, $doaction, $post_ids ) {
		if ( $doaction !== 'assign_tags_by_image_rekognition' ) {
			return $redirect_to;
		}
		$success_count = 0;
		foreach ( $post_ids as $post_id ) {
			$target_file_path = get_attached_file( get_post_thumbnail_id( $post_id) );
			if ( $target_file_path == '' ) {
				continue;
			}
			$taxonomy = $this->assign_setting[ get_post_type($post_id) ];
			if ( $this->assign_tags( $post_id, $taxonomy, $target_file_path ) ) {
				$success_count++;
			}
		}
		$redirect_to = add_query_arg( 'assign_tags_by_image_rekognition', $success_count, $redirect_to );
		return $redirect_to;		
	}

	public function admin_notices() {
		if ( ! empty( $_REQUEST['assign_tags_by_image_rekognition'] ) ) {
			$assigned = intval( $_REQUEST['assign_tags_by_image_rekognition'] );
			print '<div id="message" class="updated fade"><p>'.sprintf( __( 'Assigned Tags to %s Post(s)', self::TEXT_DOMAIN ), $assigned ).'</p></div>';
		}
	}
	
	public function assign_tags($post_id, $taxonomy, $image ) {
		try {
			$client = new RekognitionClient(
				array(
					'version'     => 'latest',
					'region'      => $this->option['region'],
					'credentials' => array(
						'key'     => $this->option['key'],
						'secret'  => $this->option['secret'],
					),
				)
			);
			$result = $client->detectLabels(
				array(
					'Image' => array(
						'Bytes' => file_get_contents( $image )
					),
					'MaxLabels'     => apply_filters( 'wp_rekogni_MaxLabels', 10 ),
					'MinConfidence' => apply_filters( 'wp_rekogni_MinConfidence', 50) ,
				)
			);

			$tags = array();
			foreach ( $result['Labels'] as $label ) {
				$tags[] = $label['Name'];
			}
			$tagged = wp_set_post_terms( $post_id, $tags, $taxonomy, true );
			return ( ! is_wp_error( $tagged ) );
		} catch (Exception $ex) {
			return false;
		}
	}
}
