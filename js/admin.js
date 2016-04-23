( function( $ ) {
	// "use strict";

	// Make Sections Sortable
	$( '.wte-sidebars-post-body' ).sortable( {
		placeholder: 'wte-sidebars-highlight',
	} );


	// Add Section
	$( '.add-section' ).on( 'click', function() {

		var section 			= $( '.default-section .widget:last' ).clone(),
			container 			= $( '.wte-sidebars-post-body' );
		
		section.appendTo( container );

		var coloned_item 		= container.find( '.widget:last' ),
			section_name 		= wte_vars.custom_sidebars,
			section_id 			= wte_vars.theme_slug + '_custom_sidebars_' + ( new Date().getTime() + '' ).substr( 3,7 ),
			sidebar_id 			= wte_vars.theme_slug + '_custom_widgets_' + ( new Date().getTime() + '' ).substr( 3,7 );

		// Set section ID
		coloned_item.find( '.wte-section' ).val( section_id );

		// Set Section and Sidebar Title
		coloned_item.attr( 'data-name', section_name );
		coloned_item.find( 'h3.section-title' ).text( section_name );

		// Update name attributes
		coloned_item.find( '.sidebar-s-name' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][name]' )
			.val( section_name + ' - 1' );

		coloned_item.find( '.sidebar-s-id' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][id]' )
			.val( sidebar_id );

		coloned_item.find( '.sidebar-s-grid' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][grid]' );

		coloned_item.find( '.sidebar-s-first' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][first]' );

		coloned_item.find( '.sidebar-s-before_title' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][before_title]' )
			.val( '<h2>' );

		coloned_item.find( '.sidebar-s-after_title' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][after_title]' )
			.val( '</h2>' );

		return false;
	});


	// Add Sidebar
	$( document.body ).on( 'click', '.add-sidebar', function() {

		var sidebar 			= $( '.default-section .widget-container:last' ).clone(),
			container 			= $( this ).parent().parent().parent( '.widgets-holder' );

		sidebar.appendTo( container );

		var divs 				= container.find( '.widget-container' ).size(),
			coloned_item 		= container.find( '.widget-container:last' ),
			sidebar_name 		= wte_vars.custom_sidebars + ' - ' + divs,
			section_id 			= coloned_item.parent().parent().find( '.wte-section' ).val(),
			sidebar_id 			= wte_vars.theme_slug + '_custom_widgets_' + ( new Date().getTime() + '' ).substr( 3,7 );

		// Update name attributes
		coloned_item.find( '.sidebar-s-name' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][name]' )
			.val( sidebar_name );

		coloned_item.find( '.sidebar-s-id' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][id]' )
			.val( sidebar_id );

		coloned_item.find( '.sidebar-s-grid' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][grid]' );

		coloned_item.find( '.sidebar-s-first' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][first]' );

		coloned_item.find( '.sidebar-s-before_title' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][before_title]' )
			.val( '<h2>' );

		coloned_item.find( '.sidebar-s-after_title' )
			.attr( 'name', 'sidebar['+ section_id +']['+ sidebar_id +'][after_title]' )
			.val( '</h2>' );

		return false;
	});


	// Delete Section
    $( document.body ).on( 'click', '.remove-section', function() {

		if( $( '.wte-sidebars-post-body .widget' ).size() == 1 ) {
			alert( wte_vars.min_sections );	
		}
		else {
			if( confirm( wte_vars.delete_section ) ) {
				$( this ).parent().parent().parent().slideUp( 300, function() {
					$( this ).remove();
				});
			}
		}
		return false;
    });


	// Delete Sidebar
    $( document.body ).on( 'click', '.remove-sidebar', function() {

		if( $( this ).parent().parent().parent().find( '.widget-container' ).size() == 1 ) {
			alert( wte_vars.min_sidebars );	
		}
		else {
	    	if ( confirm( wte_vars.delete_sidebar ) ) {
				$( this ).parent().parent().slideUp( 300, function() {
					$( this ).remove();
				});
	    	}
		}
		return false;
    });

	// Open / Close Widget
    $( document.body ).on( 'click', '.widget .widget-action', function() {

    	var current_widget = $( this ).parent().parent().parent();

        current_widget.toggleClass( 'open' );

		if ( current_widget.hasClass( 'open' ) ) {
			current_widget.find( '.widget-inside' ).first().slideDown( 200 );
		} else {
			current_widget.find( '.widget-inside' ).first().slideUp( 200 );
		}
        return false;
    });


    // Change grid
    $( document.body ).on( 'change', '.widget-grid', function() {

        var value = $( this ).val(),
        	widget_container = $( this ).parent().parent();

		if ( widget_container.hasClass( 'first' ) ) {
			widget_container.attr( 'class', 'widget-container first ' + value  );
		} else {
			widget_container.attr( 'class', 'widget-container ' + value );
		}
    });

    // Add First class
    $( document.body ).on( 'change', '.widget-first', function() {

        var value = $( this ).val(),
        	widget_container = $( this ).parent().parent();

		if ( widget_container.hasClass( 'first' ) && value == '' ) {
			widget_container.removeClass( 'first' );
		} else {
			widget_container.addClass( 'first' );
		}

    });

    // Set Before / After Title Headings
    $( document.body ).on( 'change', '.h-select', function() {

        var value = $( this ).val().replace( '<','</' ),
        	h_title = $( this ).next( '.h-title' );

		h_title.val( value );
    });


} )( jQuery );