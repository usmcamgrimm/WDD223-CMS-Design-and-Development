<?php
/**
* Widgets factory : registered the different widgetized areas
* The default widget areas are defined as properties of the CZR_utils class in class-fire-utils.php
* sidebar_widgets for left and right sidebars
* footer_widgets for the footer
* The widget area are then fired in the class below
*
*/
if ( ! class_exists( 'CZR_widgets' ) ) :
  class CZR_widgets {
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $widgets;

    function __construct () {
      self::$instance =& $this;
      //widgets actions
      add_action( 'widgets_init'                    , array( $this , 'czr_fn_widgets_factory' ) );
    }

    /******************************************
    * REGISTER WIDGETS
    ******************************************/
    /**
    * Registers the widget areas
    * hook : widget_init
    *
    * @package Customizr
    * @since Customizr 3.0
    */
    function czr_fn_widgets_factory() {
      //default Customizr filtered args
      $default                  = apply_filters( 'czr_default_widget_args' ,
                                array(
                                  'name'                    => '',
                                  'id'                      => '',
                                  'description'             => '',
                                  'class'                   => '',
                                  'before_widget'           => '<aside id="%1$s" class="widget %2$s">',
                                  'after_widget'            => '</aside>',
                                  'before_title'            => '<h3 class="widget-title">',
                                  'after_title'             => '</h3>',
                                )
      );

      //gets the filtered default values
      $footer_horizontal_widgets = apply_filters( 'czr_footer_horizontal_widgets'  , CZR_init::$instance -> footer_horizontal_widgets );
      $footer_widgets            = apply_filters( 'czr_footer_widgets'             , CZR_init::$instance -> footer_widgets );
      $sidebar_widgets           = apply_filters( 'czr_sidebar_widgets'            , CZR___::$instance -> sidebar_widgets );
      $widgets                   = apply_filters( 'czr_default_widgets'            , array_merge( $sidebar_widgets , $footer_horizontal_widgets, $footer_widgets ) );

      $this->widgets             = $widgets;

      //declares the arguments array
      $args                      = array();

      //fills in the $args array and registers sidebars
      foreach ( $widgets as $id => $infos) {
          $default = apply_filters( "czr_default_widget_args_{$id}", $default );

          foreach ( $default as $key => $default_value ) {
            if ('id' == $key ) {
              $args[$key] = $id;
            }
            else if ( 'name' == $key || 'description' == $key) {
              $args[$key] = !isset($infos[$key]) ? $default_value : call_user_func( '__' , $infos[$key] , 'customizr' );
            }
            else {
              $args[$key] = !isset($infos[$key]) ? $default_value : $infos[$key];
            }
          }
        //registers sidebars
        register_sidebar( $args );
      }
    }
  }//end of class
endif;