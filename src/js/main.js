(function() {
    "use strict";
    document.addEventListener( 'DOMContentLoaded', function() {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            document.getElementsByTagName( 'body' )[0].classList.add( 'has-touch' );
        }
    });
})();

