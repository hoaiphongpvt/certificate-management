/*
* TF-Numbers
* Author : Aleksej Vukomanovic
*/

//Statistics in numbers
jQuery.fn.statsCounter = function(){
	//declaring vars
    var stat       = this.find( '.statistics-inner' ).children();
    
   
    var startValue = 0;

    //iterate through every .stat class and collect values
	stat.each(
		function(){
			var count  = parseInt( jQuery( this ).data( 'count' ), 10 );
                        var orignal_number  = jQuery( this ).data( 'orignal_count' );
			var number = jQuery( this ).find( '.number' );
			var start  = 0;
			var go     = setInterval( function(){ startCounter(); },1 ); //increment value every 1ms

			function startCounter(){
				   incrementBy = Math.round( count / 90 ); //Divide inputed number by 90 to gain optimal speed (not too fast, not too slow)
				if ( count < 90 ) {
					incrementBy = Math.round( count / 5 );
				}
				if ( count < 5 ) {
					incrementBy = Math.round( count / 2 );
				}
				start = start + incrementBy;
				if ( count != 0 ) {
					jQuery( number ).text( start );
				} else {
					jQuery( number ).text( 0 );
					start = count;
				}
				//if desired number reched, stop counting
				if ( start === count ) {
					clearInterval( go );
				} else if ( start >= count ) { //or if greater than selected num - stop and return value
					clearInterval( go );
					jQuery( number ).text( orignal_number );
                                        
				}
			}//startCounter;
		}
    );//stat.each()
}//statsCounter();

jQuery( document ).ready(
    function(jQuery){

		var statistics = jQuery( '.statistics' );
		var n          = 0;

		if ( statistics.length > 0 ) {

			statistics.each(
				function(){
					var thisStats = jQuery( this );
					var statId    = thisStats.attr( 'id' );
					thisStats.addClass( 'stats-custom-' + n );

					//setting counts to 0
					if ( jQuery( '.stat' ).length > 0 ) {
						 var stat = thisStats.find( '.stat' );
                        stat.each(
							function(){
								var icon = jQuery( this ).find( 'span' );
								icon.each(
                                    function(){
                                        var val = jQuery( this ).attr( 'class' );
                                        if ( val.indexOf( '.' ) != -1 && jQuery( this ).find( 'img' ).length <= 0 ) {
                                                jQuery( this ).append( '<img src="'+val+'" />' );
                                        }
                                    }
								);
								stat.find( '.number' ).text( 0 );
							}
                        )
					}
					//animating when scrolled
					var countDone = 0;
					var cmo       = thisStats.data( 'cmo' );

					var visible = window.pageYOffset >= document.querySelector( '#' + statId ).offsetTop / 1.5
					if ( visible && countDone == 0 ) { //check if it's not already done
                            setTimeout(
                                function(){
                                    thisStats.statsCounter();
                                    countDone = 1;
                                },
								600
                            );
					}//if visible && not shown
					jQuery( window ).scroll(
                        function(){
                            var visible = window.pageYOffset >= document.querySelector( '#' + statId ).offsetTop / 1.5;

                            //if stats section visible, start the counting after 400ms
                            if ( visible && countDone == 0 ) { //check if it's not already done
                                setTimeout(
                                    function(){
                                        thisStats.statsCounter();
                                        countDone = 1;
                                    },
                                    400
                                );
                            }//if visible && not shown
                        }
					);//scroll function
				}
            );
		}
	}
);
