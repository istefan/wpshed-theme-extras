( function( $ ){
    function initColorPicker( widget ) {
            widget.find( '.color-picker' ).wpColorPicker( {
                change: function ( event ) {
                    var $picker = $( this );
                    _.throttle(setTimeout(function () {
                        $picker.trigger( 'change' );
                    }, 5), 250);
                },
                width: 235,
            });
    }

    function onFormUpdate( event, widget ) {
        initColorPicker( widget );
    }

    $( document ).on( 'widget-added widget-updated', onFormUpdate );

    $( document ).ready( function() {
        $( '#widgets-right .widget:has(.color-picker)' ).each( function () {
            initColorPicker( $( this ) );
        } );
    } );
}( jQuery ) );