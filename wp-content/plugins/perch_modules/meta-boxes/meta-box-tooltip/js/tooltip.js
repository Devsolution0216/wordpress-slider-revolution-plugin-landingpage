jQuery( function ( $ )
{
	$( '.mb-tooltip' ).tooltip();
	$( '#wpbody' ).on( 'clone', function ()
	{
		$( '.mb-tooltip' ).tooltip();
	} );
} );
