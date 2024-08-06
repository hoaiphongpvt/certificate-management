<?php
//add to menu dashboard
if ( is_admin() ) {
	add_action( 'admin_menu', 'tf_num_licns' );

}

function tf_num_licns() {
	// subpage of edit.php?post_type=tf_stats
	add_submenu_page( '', 'Licenses', 'Licenses', 'manage_options', 'ed-licencing', 'tf_numbers_license_page' );
}

function tf_numbers_license_page() {
	?>
  <div class="wrap">
    <h2><?php esc_html_e( 'Addons Licenses' ); ?></h2>
    <form method="post" action="options.php">

      <?php settings_fields( 'edd_sample_license' ); ?>
      <?php settings_fields( 'tf_contr_license' ); ?>

      <table class="form-table">
        <tbody>
          <?php do_action( 'tf_license_row' ); ?>

        </tbody>
      </table>
      <?php //submit_button(); ?>
      <a id="lic-subm" style="display: inline-block;margin-top: 30px;padding: 5px 20px;background: rgb(43, 162, 13);border-radius: 3px;color: #fff;cursor: pointer">Save</a>
    </form>
	<?php
}

function tf_numbers_reg() {
	// creates our settings in the options table
	register_setting( 'edd_sample_license', 'edd_sample_license_key', 'tf_numbers_sant_license' );
	register_setting( 'tf_contr_license', 'tf_controller_license_key', 'tf_numbers_sant_license' );
}
add_action( 'admin_init', 'tf_numbers_reg' );

function tf_numbers_sant_license( $new ) {
	$old = get_option( 'edd_sample_license_key' );
	if ( $old && $old != $new ) {
		delete_option( 'edd_sample_license_status' );
	}
	return $new;
}

function tf_numbers_activate_license( $name, $license, $addon_key ) {

    $api_params = array(
		'edd_action' => 'activate_license',
		'license'    => $license,
		'item_name'  => rawurlencode( $name ),
		'url'        => home_url(),
    );

    $response = wp_remote_post(
        TF_STORE_URL,
        array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params,
        )
    );

    if ( is_wp_error( $response ) ) {
		return false;
    }

    $license_data = json_decode( wp_remote_retrieve_body( $response ) );

    update_option( $addon_key, $license_data->license );

}

function tf_numbers_check_license( $addon_key, $name ) {
    $store_url  = 'http://themeflection.com';
    $item_name  = $name;
    $license    = get_option( $addon_key );
    $api_params = array(
        'edd_action' => 'check_license',
        'license'    => $license,
        'item_name'  => rowurlencode( $item_name ),
    );
    $response   = wp_remote_get(
        add_query_arg( $api_params, $store_url ),
        array(
			'timeout'   => 15,
			'sslverify' => false,
        )
    );
    if ( is_wp_error( $response ) ) {
        return false;
    }
    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
    $addon_key    = str_replace( '_key', '_status', $addon_key );
    if ( $license_data->license == 'expired' ) {
        update_option( $addon_key, 'expired' );
    } elseif ( $license_data->license == 'invalid' ) {
        update_option( $addon_key, 'invalid' );
    } elseif ( $license_data->license == 'inactive' ) {
        update_option( $addon_key, 'inactive' );
    }

    tf_numbers_activate_license( $name, $license, $addon_key );
}

function tf_license_check() {
	$check      = false;
	$addons     = array();
	$auto       = get_option( 'tf_auto_increment_license_key' );
	$iconizer   = get_option( 'tf_iconizer_license_key' );
	$controller = get_option( 'tf_controller_license_key' );
	$animator   = get_option( 'tf_animator_license_key' );
	$currencies = get_option( 'tf_currencies_license_key' );
	$parallax   = get_option( 'tf_parallax_license_key' );
	$bundle     = get_option( 'tf_bundle_license_key' );
	$data       = array(
		'auto'       => array(
			'name' => 'Auto Increment Addon',
			'key'  => 'tf_auto_increment_license_key',
		),
		'controller' => array(
			'name' => 'Controller Addon',
			'key'  => 'tf_controller_license_key',
		),
		'iconizer'   => array(
			'name' => 'iconizer tf numbers addon',
			'key'  => 'tf_iconizer_license_key',
		),
		'animator'   => array(
			'name' => 'Animator TF Numbers Addon',
			'key'  => 'tf_animator_license_key',
		),
		'currencies' => array(
			'name' => 'Currencies TF Numbers Addon',
			'key'  => 'tf_currencies_license_key',
		),
		'parallax'   => array(
			'name' => 'Parallax TF Numbers Addon',
			'key'  => 'tf_parallax_license_key',
		),
		'bundle'     => array(
			'name' => 'Tf Numbers Addons Bundle',
			'key'  => 'tf_bundle_license_key',
		),
	);
	if ( get_option( 'tf_currencies_license_key' ) || get_option( 'tf_controller_license_key' ) || get_option( 'tf_animator_license_key' ) || get_option( 'tf_iconizer_license_key' ) || get_option( 'tf_parallax_license_key' ) || get_option( 'tf_bundle_license_key' ) ) {
		$check = true;
		if ( $iconizer ) {
			array_push( $addons, 'iconizer' );
		}
		if ( $auto ) {
			array_push( $addons, 'auto' );
		}
		if ( $controller ) {
			array_push( $addons, 'controller' );
		}
		if ( $animator ) {
			array_push( $addons, 'animator' );
		}
		if ( $parallax ) {
			array_push( $addons, 'parallax' );
		}
		if ( $currencies ) {
			array_push( $addons, 'currencies' );
		}
		if ( $bundle ) {
			array_push( $addons, 'bundle' );
		}
	}

	if ( $check ) {
		foreach ( $addons as $addon ) {
			$addon_key = $data[ $addon ]['key'];
			$name      = $data[ $addon ]['name'];
			tf_numbers_check_license( $addon_key, $name );
		}
	}

}
$retrieved_nonce = filter_input( INPUT_POST, '_wpnonce' );
if ( isset( $_GET['page'] ) && $_GET['page'] === 'ed-licencing' && wp_verify_nonce( $retrieved_nonce, 'tf_contr_license-options' ) ) {
	add_action( 'admin_init', 'tf_license_check' );
}

function tf_numb_save_licenses() {
    $retrieved_nonce = filter_input( INPUT_POST, '_wpnonce' );
    if ( ! wp_verify_nonce( $retrieved_nonce, 'tf_contr_license-options' ) ) {
			die( esc_html__( 'Failed security check', 'tf-numbers' ) );
	}
        $data = esc_html( wp_unslash( isset( $_POST['data'] ) ) )? esc_html( wp_unslash( isset( $_POST['data'] ) ) ) : '';
	if ( $data && current_user_can( 'manage_options' ) ) {
		$licence_keys = array( 'tf_auto_increment_license_key', 'tf_iconizer_license_key', 'tf_controller_license_key', 'tf_animator_license_key', 'tf_currencies_license_key', 'tf_parallax_license_key', 'tf_bundle_license_key' );
		foreach ( $data as $addon ) {
			if ( in_array( $addon['key'], $licence_keys, true ) ) {
				update_option( $addon['key'], $addon['val'] );
			}
		}
	}

    wp_die();
}
add_action( 'wp_ajax_tf_numb_save_licenses', 'tf_numb_save_licenses' );

function tf_license_ajax() {
	?>
    <script type="text/javascript">
       jQuery(document).ready(function($){
          $data = {};
          $('#lic-subm').on('click', function(){
              $('.regular-text').each(function(){
                 var $this = $(this);
                 var $val = $this.val();
                 var $key = $this.attr('name');
                 $data[$key] = {
                  val: $val,
                  key: $key
                 }
              });
              $('#lic-subm').text('Saving...');
              var data = {
                'action': 'tf_numb_save_licenses',
                'data': $data
              }
              jQuery.post(ajaxurl, data, function(response) {
                location.reload(true);
              })
          });
       });
    </script>
	<?php
}
add_action( 'admin_footer', 'tf_license_ajax' );
