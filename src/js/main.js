(function() {
    "use strict";
    document.addEventListener( 'DOMContentLoaded', function() {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            document.getElementsByTagName( 'body' )[0].classList.add( 'has-touch' );
        }

        // View all projects in list on portfolio page.
        var see_all_link = document.getElementById('see-all-projects');
        if ( see_all_link ) {
            see_all_link.addEventListener( 'click', function(e) {
                e.preventDefault();
                document.querySelector( '.portfolio .list-wrapper' ).style.height = '100%';
                see_all_link.style.display = 'none';
                return false;
            });
        }
    });

})();

