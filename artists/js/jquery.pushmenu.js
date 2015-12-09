/*!
 * jPushMenu.js
 * 1.1.1
 * @author: takien
 * http://takien.com
 * Original version (pure JS) is created by Mary Lou http://tympanus.net/
 */




 (function($) {

    // this code is used to smooth scrolling on mobile
    block = false;
    $(document).on('touchmove', function(e) {
        if(block) {
            if (!$(e.target).parents('#cabinet')[0]) {
                e.preventDefault();
            };
        }
    });

    $.fn.jPushMenu = function(customOptions) {
        var o = $.extend({}, $.fn.jPushMenu.defaultOptions, customOptions);


        // Add class to toggler
        $(this).addClass('jPushMenuBtn');

        $(this).click(function(e) {
            e.stopPropagation();

         //   $('html, body').addClass('no-scroll');

         var target     = '',
         push_direction = '';

            // Determine menu and push direction
            if ($(this).is('.' + o.showLeftClass)) {
                target         = '.cbp-spmenu-left';
                push_direction = 'toright';
            }

            if (target == '') { return; }

            $(this).toggleClass(o.activeClass);
            $(target).toggleClass(o.menuOpenClass);

            if ($(this).is('.toggle-menu') && push_direction != '') {
             $('body').toggleClass('push-toright');
             $('html, body').toggleClass('no-scroll');
         }

         if($(this).hasClass('menu-active')) {
            block = true;
        } else {
            block = false;
        }

            // Disable all other buttons
            $('.jPushMenuBtn').not($(this)).toggleClass('disabled');

            return;
        });

        var jPushMenu = {
            close: function (o) {
                $('.jPushMenuBtn,body,#cabinet')
                .removeClass('disabled ' + o.activeClass + ' ' + o.menuOpenClass + ' ' + o.pushBodyClass + '-toleft ' + o.pushBodyClass + '-toright');
                $('html, body').removeClass('no-scroll');
                $('body').focus();
            }
        }

        // Close menu on clicking outside menu
        if (o.closeOnClickOutside) {
         $(document).click(function() {
            jPushMenu.close(o);
        });
     }

        // Close menu on clicking menu link
        if (o.closeOnClickLink) {
            $('.cbp-spmenu a').on('click', function() {
                jPushMenu.close(o);
            });
        }
    };

   /*
    * In case you want to customize class name,
    * do not directly edit here, use function parameter when call jPushMenu.
    */
    $.fn.jPushMenu.defaultOptions = {
        pushBodyClass      : 'push',
        showLeftClass      : 'menu-left',
        showRightClass     : 'menu-right',
        showTopClass       : 'menu-top',
        showBottomClass    : 'menu-bottom',
        activeClass        : 'menu-active',
        menuOpenClass      : 'menu-open',
        closeOnClickOutside: true,
        closeOnClickLink   : true
    };
})(jQuery);

jQuery(document).ready(function($) {


    // helps fix buggy cabinet scrolling
    elem = document.getElementById('cabinet');

    elem.addEventListener('touchstart', function(event){
        this.allowUp = (this.scrollTop > 0);
        this.allowDown = (this.scrollTop < this.scrollHeight - this.clientHeight);
        this.prevTop = null; 
        this.prevBot = null;
        this.lastY = event.pageY;
    });

    elem.addEventListener('touchmove', function(event){
        var up = (event.pageY > this.lastY),    
        down = !up;

        this.lastY = event.pageY;

        if ((up && this.allowUp) || (down && this.allowDown)) 
            event.stopPropagation();
        else 
            event.preventDefault();
    });


    // custom scroll to scroll cabinet when scrolling on overlay.
    var fixedElement = document.getElementById('cabinet-overlay');

    function fixedScrolled(e) {
        var evt = window.event || e;
        var delta = evt.detail ? evt.detail * (-120) : evt.wheelDelta; //delta returns +120 when wheel is scrolled up, -120 when scrolled down
        $('#cabinet').scrollTop($('#cabinet').scrollTop() - delta);
    }

    var mousewheelevt = (/Gecko\//i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel";
    if (fixedElement.attachEvent) fixedElement.attachEvent("on" + mousewheelevt, fixedScrolled);
    else if (fixedElement.addEventListener) fixedElement.addEventListener(mousewheelevt, fixedScrolled, false);

});
