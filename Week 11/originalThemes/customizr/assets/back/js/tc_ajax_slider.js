/**
 * Ajax scripts for slider
 *
 * @package Customizr
 * @since Customizr 1.0
 */
var CzrSlider;
( function($) {

  "use strict";
  $( function(){
    CzrSlider = function() {
        //attribute valorization
        this.$body                    = $('body');
        this._action                  = 'slider_action';
        this._nonce                   = SliderAjax.SliderCheckNonce;

        this.$_slider_section_box     = $('#slider_sectionid');
        this.$_slider_fields_box      = $('#slider-fields-box');
        this.$_tc_post_id             = $('input#tc_post_id');

        //context: attachment or post?
        this._check_field             = 'slider_check_field';
        this._context                 = 'attachment';
        if ( $('input#post_slider_check_field').length > 0 ) {
          this._check_field           = 'post_slider_check_field';
          this._context               = 'post';
        }

        this.$_slider_check_field     = $('input#' + this._check_field);
        this._color_picker_on         = 'post' == this.$_context;

        if ( this._color_picker_on )
          this._color_picker_func = ( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ) ? 'wpColorPicker' : 'farbtastic' ;

        // initial data to send over $_POST, the acual sent data will be an extension of this
        this._data                   = {
              'action'             : this._action,
              'tc_post_id'         : this.$_tc_post_id.val(),
              'SliderCheckNonce'   : this._nonce,
              'tc_post_type'       : this._context
        };

        //event connecting
        this.eventListeners();
        //init sortable, other ext plugins already initialized
        this._init_sortable();
        //init multipicker
        this._init_multipicker();
        //init checkboxes
        this._init_checkboxes();
    };

    $.extend( CzrSlider.prototype, {
      eventListeners : function(){
        var self = this;

        // elements events

        //enable slider
        this.$body.on('change', 'input#' + this._check_field, function(){
           self.ajax( self._build_data('enable') );

        //select different slider
        }).on( 'change', 'select#post_slider_field', function(){
           self.ajax( self._build_data('select_slider') );

        //sort slides
        }).on( 'sortupdate', '#slider_sectionid #sortable',function() {
           self.ajax( self._build_data('reorder_slides'), '_reorder_slides_response' );

        //create new slider
        }).on('click', '#tc_create_slider', function(){
           self.ajax( self._build_data('new_slider') );

        //delete slider
        }).on('click', '#delete-slider', function(){
           self.ajax( self._build_data('delete_slider') );
        });

      },

      ajax : function( _data, _callback ){
        var self = this;
        this.$_slider_fields_box.find('.spinner').show();

        $.post(
          ajaxurl,//global var declared by WP when is_admin() => ajaxurl = '/wp-admin/admin-ajax.php',
          _data,
          function( response ){
            if ( _callback ){
              self[_callback]( response );
              return;
            }
            self._default_response( response );
          }
        );//end $.post
      },

      //handle ajax default response
      _default_response : function( response ){
        this.$_slider_fields_box.empty().append(response);
        this.$_slider_fields_box.find('.spinner').hide();
        this._init_ext_plugins();
      },

      //handle reordering slides ajax response
      _reorder_slides_response : function(){
        var slider_update = $( '<div/>' ).addClass( 'updated' )
          .css( 'opacity' ,0)
          .html( '<div class="message">Slider updated</div>' )
          .appendTo( '#update-status' );
          slider_update.animate({opacity:0.9}, function(){
          slider_update.delay(1200).fadeOut(function(){
              slider_update.remove();
            });
          });
        this.$_slider_fields_box.find('.spinner').hide();
        this._init_sortable();
      },

      //build the data to pass over $_POST depending on the event
      _build_data : function( _event ){
        var _data = {};

        switch ( _event ) {
          case 'new_slider':
          case 'select_slider':
            _data = $.extend( {}, this._data, {
              'post_slider_name'    : $( 'select#post_slider_field').val(),
              'new_slider_name'     : $( 'input#slider_field' ).val(),
            });
            if ( 'attachment' == this._context )
              $.extend( _data, this._data, {
                'slide_title_field'            : $( 'input#slide_title_field' ).val(),
                'slide_text_field'             : $( 'textarea#slide_text_field' ).val(),
                'slide_color_field'            : $( 'input#slide_color_field' ).val(),
                'slide_button_field'           : $( 'input#slide_button_field' ).val(),
                'slide_link_field'             : $( 'select#slide_link_field' ).val(),
                'slide_custom_link_field'      : $( 'input#slide_custom_link_field').val(),
                'slide_link_target_field'      : $( 'input#slide_link_target_field').is(':checked') ? 1 : '',
                'slide_link_whole_slide_field' : $( 'input#slide_link_whole_slide_field').is(':checked') ? 1 : '',
             });
          break;
          case 'delete_slider':
            _data = $.extend( {}, this._data, {
              'delete_slider'       : true,
              'currentpostslider'   : $( 'select#post_slider_field' ).val(),
              //reset new_slider_name if needed
              'new_slider_name'     : null,
            });
          break;
          case 'reorder_slides':
            _data = $.extend( {}, this._data, {
              //position array
              'newOrder'            : this.$_slider_section_box.find('#sortable').sortable( 'toArray' ).toString(),
              //current post slider
              'currentpostslider'   : $( 'select#post_slider_field' ).val()
            });
          break;
          default :
            _data = this._data;

        }
        _data[this._check_field] = this.$_slider_check_field.is(':checked') ? 1 : '';
        return _data;
      },

      //init external plugins such as sortable, color pickers, iphone check
      _init_ext_plugins : function(){
        this._init_color_picker();
        this._init_checkboxes();
        this._init_sortable();
        this._init_multipicker();
      },

      //init color picker
      _init_color_picker : function(){
        if ( this._color_picker_on ){
          var $_slide_color_field = $('#slide_color_field');
          switch ( this._color_picker_func ){
            case 'farbtastic' :
              $('#colorfield').farbtastic( $_slide_color_field );
              break;

            default:
              $_slide_color_field.wpColorPicker();
          }
        }
      },

      //init checkboxes
      _init_checkboxes : function(){
        var _checkwraper_selector = '.czr-toggle-check',
            _checkboxes_selector  = _checkwraper_selector + ' input[type="checkbox"]';

        var _do_ = function( $checkboxes ) {
            $checkboxes.each( function() {
              var $input        = $(this),
                  $checkWrapper = $input.closest( _checkwraper_selector );

              $checkWrapper.toggleClass( 'is-checked', $input.is(':checked') );
              $checkWrapper.find('svg').remove();
              $checkWrapper.append(
                    ! $input.is(':checked') ? '<svg class="czr-toggle-check__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>' : '<svg class="czr-toggle-check__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>'
              );
            });
        };

        this.$_slider_section_box.on( 'change', _checkboxes_selector, function() {
          _do_( $(this) );
        });
        _do_( $( _checkboxes_selector ) );
      },

      //init sortable
      _init_sortable : function(){
        this.$_slider_section_box.find( '#sortable' ).sortable({
          placeholder: "ui-state-highlight",
        }).disableSelection();
      },
      //init czrSelect2 multipicker
      _init_multipicker : function(){
        if ( typeof $.fn.czrSelect2 !== 'function' ) return;
        this.$_slider_section_box.find('select.czr_multiple_picker, select.tc_multiple_picker').czrSelect2({
          closeOnSelect: false,
          formatSelection: tcEscapeMarkup
        });
        function tcEscapeMarkup(obj) {
          //trim dashes
          return obj.text.replace(/\u2013|\u2014/g, "");
        }
      }
    });
    new CzrSlider();
  });

})(jQuery);
