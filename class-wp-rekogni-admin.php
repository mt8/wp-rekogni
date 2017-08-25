<?php 

class WP_Rekogni_Admin {
	
	const SLUG = 'wp_rekogni';
	const TEXT_DOMAIN = 'wp-rekogni';
	
	private $options_default = array(
		'region'  => '',
		'key'     => '',
		'secret'  => '',
	);
	public function admin_init() {
		$key     = self::SLUG;
		$group   = $key . '_group';
		$section = $key . '_section'; 
		register_setting( $group, $key, array( $this, 'register_setting' ) );
		add_settings_section( $section, __( 'WP Rekogni', self::TEXT_DOMAIN ) , array( $this, 'add_settings_section' ), $key );
		add_settings_field( 'region', __( 'Region', self::TEXT_DOMAIN ), array( $this, 'add_settings_field_region' ), $key, $section );
		add_settings_field( 'key'   , __( 'Access Key', self::TEXT_DOMAIN )   , array( $this, 'add_settings_field_key' ), $key, $section );
		add_settings_field( 'secret', __( 'Secret Access Key', self::TEXT_DOMAIN ) , array( $this, 'add_settings_field_secret' ), $key, $section );
	}
	public function add_settings_section() {
	?>
	<strong><?php _e( 'Please Input Your AWS IAM settings.', self::TEXT_DOMAIN ); ?></strong>
	<?php
	}
	public function register_setting( $input ) {
		if ( ! isset( $input['region'] ) || trim( $input['region'] ) == '' )
			$input['region'] = '';
		if ( ! isset( $input['key'] ) || trim( $input['key'] ) == '' )
			$input['key'] = '';
		if ( ! isset( $input['secret'] ) || trim( $input['secret'] ) == '' )
			$input['secret'] = '';
		return $input;
	}
	public function add_settings_field_region() {
	?>
	<input type="text" id="region" name="<?php echo esc_attr( self::SLUG ) ?>[region]" class="regular-text" value="<?php echo esc_attr( $this->option( 'region' ) ) ?>" />
	<?php
	}
	public function add_settings_field_key() {
	?>
	<input type="text" id="key" name="<?php echo esc_attr( self::SLUG ) ?>[key]" class="regular-text" value="<?php echo esc_attr( $this->option( 'key' ) ) ?>" />
	<?php
	}
	public function add_settings_field_secret() {
	?>
	<input type="password" id="secret" name="<?php echo esc_attr( self::SLUG ) ?>[secret]" class="regular-text" value="<?php echo esc_attr( $this->option( 'secret' ) ) ?>" />
	<?php
	}
	public function admin_menu() {
		add_menu_page(
			self::SLUG,
			__( 'WP Rekogni', self::TEXT_DOMAIN ),
			'manage_options',
			__FILE__, 
			array( $this, 'add_options_page' ),
			'dashicons-tag'
		);
	}
	public function add_options_page() {
	?>
	<div class="wrap">
		<h2></h2>
		<form method="POST" action="options.php">
			<?php do_settings_sections( self::SLUG ); ?>
			<?php settings_fields( self::SLUG . '_group' ); ?>			
			<?php submit_button(); ?>
		</form>		
	</div>
	<?php
	}
	public function option( $key) {
		$option = get_option( self::SLUG, $this->options_default );
		if ( is_array( $option ) && array_key_exists( $key, $option ) ) {
			return $option[$key];
		} else {
			return '';
		}
	}

}