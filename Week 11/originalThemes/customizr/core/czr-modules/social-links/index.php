<?php
function czr_fn_register_social_links_module( $args ) {
    $defaults = array(
        'setting_id' => '',

        'base_url_path' => '',//PC_AC_BASE_URL/inc/czr-modules/social-links/
        'version' => '',

        'option_value' => array(), //<= will be used for the dynamic registration

        'setting' => array(),
        'control' => array(),
        'section' => array(), //array( 'id' => '', 'label' => '' ),

        'sanitize_callback' => '',
        'validate_callback' => ''
    );
    $args = wp_parse_args( $args, $defaults );

    if ( ! isset( $GLOBALS['czr_base_fmk_namespace'] ) ) {
        error_log( __FUNCTION__ . ' => global czr_base_fmk not set' );
        return;
    }

    $czrnamespace = $GLOBALS['czr_base_fmk_namespace'];
    //czr_fn\czr_register_dynamic_module
    $CZR_Fmk_Base_fn = $czrnamespace . 'CZR_Fmk_Base';
    if ( ! function_exists( $CZR_Fmk_Base_fn) ) {
        error_log( __FUNCTION__ . ' => Namespace problem => ' . $CZR_Fmk_Base_fn );
        return;
    }


    $CZR_Fmk_Base_fn() -> czr_pre_register_dynamic_setting( array(
        'setting_id' => $args['setting_id'],
        'module_type' => 'czr_social_module',
        'option_value' => ! is_array( $args['option_value'] ) ? array() : $args['option_value'],

        'setting' => $args['setting'],

        'section' => $args['section'],

        'control' => $args['control']
    ));

    // czr_fn\czr_register_dynamic_module()
    $CZR_Fmk_Base_fn() -> czr_pre_register_dynamic_module( array(

        'dynamic_registration' => true,
        'module_type' => 'czr_social_module',

        'customizer_assets' => array(
            'control_js' => array(
                // handle + params for wp_enqueue_script()
                // @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
                'czr-social-links-module' => array(
                    'src' => sprintf(
                        '%1$s/assets/js/%2$s',
                        $args['base_url_path'],
                        '_2_7_socials_module.js'
                    ),
                    'deps' => array('customize-controls' , 'jquery', 'underscore'),
                    'ver' => ( defined('WP_DEBUG') && true === WP_DEBUG ) ? time() : $args['version'],
                    'in_footer' => true
                )
            ),
            'localized_control_js' => array(
                'deps' => 'czr-customizer-fmk',
                'global_var_name' => 'socialModuleLocalized',
                'params' => array(
                    //Social Module
                    'defaultSocialColor' => 'rgb(90,90,90)',
                    'defaultSocialSize'  => 14,
                    'i18n' => array(
                        'Rss' => __('Rss', 'customizr'),
                        'Select a social icon' => __('Select a social icon', 'customizr'),
                        'Follow us on' => __('Follow us on', 'customizr'),
                        'Done !' => __('Done !', 'customizr'),
                        'New Social Link created ! Scroll down to edit it.' => __('New Social Link created ! Scroll down to edit it.', 'customizr'),
                    )
                    //option value for dynamic registration
                )
            )
        ),

        'tmpl' => array(
            'pre-item' => array(
                'social-icon' => array(
                    'input_type'  => 'select',
                    'title'       => __('Select an icon', 'customizr')
                ),
                'social-link'  => array(
                    'input_type'  => 'text',
                    'title'       => __('Social link url', 'customizr'),
                    'notice_after'      => __('Enter the full url of your social profile (must be valid url).', 'customizr'),
                    'placeholder' => __('http://...,mailto:...,...', 'customizr')
                )
            ),
            'mod-opt' => array(
                'social-size' => array(
                    'input_type'  => 'number',
                    'title'       => __('Size in px', 'customizr'),
                    'step'        => 1,
                    'min'         => 5,
                    'transport' => 'postMessage'
                )
            ),
            'item-inputs' => array(
                'social-icon' => array(
                    'input_type'  => 'select',
                    'title'       => __('Social icon', 'customizr')
                ),
                'social-link'  => array(
                    'input_type'  => 'text',
                    'title'       => __('Social link', 'customizr'),
                    'notice_after'      => __('Enter the full url of your social profile (must be valid url).', 'customizr'),
                    'placeholder' => __('http://...,mailto:...,...', 'customizr')
                ),
                'title'  => array(
                    'input_type'  => 'text',
                    'title'       => __('Title', 'customizr'),
                    'notice_after'      => __('This is the text displayed on mouse over.', 'customizr'),
                ),
                'social-color'  => array(
                    'input_type'  => 'color',
                    'title'       => sprintf( '%1$s <i>%2$s %3$s</i>', __('Icon color', 'customizr'), __('default:', 'customizr'), 'rgba(255,255,255,0.7)' ),
                    'notice_after'      => __('Set a unique color for your icon.', 'customizr'),
                    'transport' => 'postMessage'
                ),
                'social-target' => array(
                    'input_type'  => 'check',
                    'title'       => __('Link target', 'customizr'),
                    'notice_after'      => __('Check this option to open the link in a another tab of the browser.', 'customizr'),
                    'width-100'   => true
                )
            )
        )
    ));
}//ac_register_social_links_module()





/////////////////////////////////////////////////////////////////
// SANITIZATION
/***
* Social Module sanitization/validation
**/
function czr_fn_sanitize_callback__czr_social_module( $socials ) {
  // error_log( 'IN SANITIZATION CALLBACK' );
  // error_log( print_r( $socials, true ));
  if ( empty( $socials ) )
    return array();

  //sanitize urls and titles for the db
  foreach ( $socials as $index => &$social ) {
    if ( ! is_array( $social ) || ! ( array_key_exists( 'social-link', $social) &&  array_key_exists( 'title', $social) ) )
      continue;

    $social['social-link']  = esc_url_raw( $social['social-link'] );
    $social['title']        = esc_attr( $social['title'] );
  }
  return $socials;
}

function czr_fn_validate_callback__czr_social_module( $validity, $socials ) {
  // error_log( 'IN VALIDATION CALLBACK' );
  // error_log( print_r( $socials, true ));
  $ids_malformed_url = array();
  $malformed_message = __( 'An error occurred: malformed social links', 'customizr');

  if ( empty( $socials ) )
    return array();


  //(
  //     [0] => Array
  //         (
  //             [is_mod_opt] => 1
  //             [module_id] => tc_social_links_czr_module
  //             [social-size] => 15
  //         )

  //     [1] => Array
  //         (
  //             [id] => czr_social_module_0
  //             [title] => Follow us on Renren
  //             [social-icon] => fa-renren
  //             [social-link] => http://customizr-dev.dev/feed/rss/
  //             [social-color] => #6d4c8e
  //             [social-target] => 1
  //         )
  // )
  //validate urls
  foreach ( $socials as $index => $item_or_modopt ) {
    if ( ! is_array( $item_or_modopt ) )
      return new WP_Error( 'required', $malformed_message );

    //should be an item or a mod opt
    if ( ! array_key_exists( 'is_mod_opt', $item_or_modopt ) && ! array_key_exists( 'id', $item_or_modopt ) )
      return new WP_Error( 'required', $malformed_message );

    //if modopt case, skip
    if ( array_key_exists( 'is_mod_opt', $item_or_modopt ) )
      continue;

    if ( $item_or_modopt['social-link'] != esc_url_raw( $item_or_modopt['social-link'] ) )
      array_push( $ids_malformed_url, $item_or_modopt[ 'id' ] );
  }

  if ( empty( $ids_malformed_url) )
    return null;

  return new WP_Error( 'required', __( 'Please fill the social link inputs with a valid URLs', 'customizr' ), $ids_malformed_url );
}

