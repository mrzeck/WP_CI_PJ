$(function() {

    console.log("%c SIEUKINHDOANH Thiết kế và phát triển website hàng đầu.", "font-size:25px; background-color: #0165bb; color: #fff;font-family: tahoma;padding:5px 10px;");

    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            if (settings.data.indexOf('csrf_test_name') === -1) {
                settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
            }
        }
    });

    //Fixed menu scroll
    var nav_m = $('.header-mobile');

    var nav_m_p = nav_m.position();

    $(window).scroll(function() {
        if ($(this).scrollTop() > nav_m_p.top) {
            nav_m.addClass('fixed');
        } else {
            nav_m.removeClass('fixed');
        }
    });

    /*********** MOBILE SEARCH ***********/
    $('.js_btn-search-mobile, .td-search-close').click(function() {
        $('body').toggleClass('td-search-opened');
        return false;
    });

    $('#td-header-search-mob').keyup(function() {
        $('.result-msg a').attr('href', domain + 'search?keyword=' + $(this).val() + '&type=products');
    });

    var typingTimer;

    var doneTypingInterval = 300; //time in ms, 5 second for example

    var $input = $('.td-search-form input[name="keyword"]');

    var search_result = [];

    //on keyup, start the countdown
    $input.on('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown 
    $input.on('keydown', function() {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping() {

        keyword = $input.val();

        if (keyword.length == 0) {

            $('#td-aj-search-mob').html('');

            return false;
        }

        if (typeof search_result[keyword] != 'undefined') {
            $('#td-aj-search-mob').html(search_result[keyword]);
        } else {

            data = {
                'keyword': $input.val(),
                'action': 'theme_ajax_product_search',
            };

            $jqxhr = $.post(base + '/ajax', data, function() {}, 'json');

            $jqxhr.done(function(data) {

                search_result[keyword] = data.data;

                $('#td-aj-search-mob').html(data.data);
            });
        }
    }

    /*********** MOBILE MENU ***********/

    $('.js_btn-menu-mobile, .td-mobile-close').click(function() {
        $('body').toggleClass('td-menu-mob-open-menu');
        return false;
    });

    $('.collapse').on('show.bs.collapse', function() {
        $('a[href="#' + $(this).attr('id') + '"]').html('<i class="fal fa-angle-down pull-right"></i>');
    });

    $('.collapse').on('hide.bs.collapse', function() {
        $('a[href="#' + $(this).attr('id') + '"]').html('<i class="fal fa-angle-right pull-right"></i>');
    });

    new WOW().init();
});



//hiển thị thông báo
function show_message(text, icon) {
    $.toast({
        heading: "Alert",
        text: text,
        position: 'top-left',
        icon: icon,
        hideAfter: 5000,
    });
}

//kiểm tra đối tượng có tồn tại không
function isset($element) {
    if (typeof $element != 'undefined')
        return true;
    return false;
}

//slider chạy dọc
function vertical(element, interval, item, direction = 'up') {
    $(element).easyTicker({
        direction: 'up',
        easing: 'swing',
        speed: 'slow',
        interval: interval,
        visible: item,
        mousePause: 1,
    });
}

//slider chạy ngang
function horizontal(element, interval, item, rep, button = '') {
    var ol = $(element).owlCarousel({
        items: item,
        margin: 10,
        loop: true,
        autoplay: true,
        autoplayTimeout: interval,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        responsive: rep
    });

    if (button != '') {
        $(button + ' .next').click(function() {
            ol.trigger('next.owl.carousel', [1000]);
        })
        $(button + ' .prev').click(function() {
            ol.trigger('prev.owl.carousel', [1000]);
        });
    }
}


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function delCookie(name) {
    document.cookie = name + ';expires= Thu, 01-Jan-1970 00:00:01 GMT;';
};


(function(window, undefined) {
    'use strict';

    /**
     * Handles managing all events for whatever you plug it into. Priorities for hooks are based on lowest to highest in
     * that, lowest priority hooks are fired first.
     */
    var EventManager = function() {
        var slice = Array.prototype.slice;

        /**
         * Maintain a reference to the object scope so our public methods never get confusing.
         */
        var MethodsAvailable = {
            removeFilter: removeFilter,
            applyFilters: applyFilters,
            addFilter: addFilter,
            removeAction: removeAction,
            doAction: doAction,
            addAction: addAction
        };

        /**
         * Contains the hooks that get registered with this EventManager. The array for storage utilizes a "flat"
         * object literal such that looking up the hook utilizes the native object literal hash.
         */
        var STORAGE = {
            actions: {},
            filters: {}
        };

        /**
         * Adds an action to the event manager.
         *
         * @param action Must contain namespace.identifier
         * @param callback Must be a valid callback function before this action is added
         * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
         * @param [context] Supply a value to be used for this
         */
        function addAction(action, callback, priority, context) {
            if (typeof action === 'string' && typeof callback === 'function') {
                priority = parseInt((priority || 10), 10);
                _addHook('actions', action, callback, priority, context);
            }

            return MethodsAvailable;
        }

        /**
         * Performs an action if it exists. You can pass as many arguments as you want to this function; the only rule is
         * that the first argument must always be the action.
         */
        function doAction( /* action, arg1, arg2, ... */ ) {
            var args = slice.call(arguments);
            var action = args.shift();

            if (typeof action === 'string') {
                _runHook('actions', action, args);
            }

            return MethodsAvailable;
        }

        /**
         * Removes the specified action if it contains a namespace.identifier & exists.
         *
         * @param action The action to remove
         * @param [callback] Callback function to remove
         */
        function removeAction(action, callback) {
            if (typeof action === 'string') {
                _removeHook('actions', action, callback);
            }

            return MethodsAvailable;
        }

        /**
         * Adds a filter to the event manager.
         *
         * @param filter Must contain namespace.identifier
         * @param callback Must be a valid callback function before this action is added
         * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
         * @param [context] Supply a value to be used for this
         */
        function addFilter(filter, callback, priority, context) {
            if (typeof filter === 'string' && typeof callback === 'function') {
                priority = parseInt((priority || 10), 10);
                _addHook('filters', filter, callback, priority, context);
            }

            return MethodsAvailable;
        }

        /**
         * Performs a filter if it exists. You should only ever pass 1 argument to be filtered. The only rule is that
         * the first argument must always be the filter.
         */
        function applyFilters( /* filter, filtered arg, arg2, ... */ ) {
            var args = slice.call(arguments);
            var filter = args.shift();

            if (typeof filter === 'string') {
                return _runHook('filters', filter, args);
            }

            return MethodsAvailable;
        }

        /**
         * Removes the specified filter if it contains a namespace.identifier & exists.
         *
         * @param filter The action to remove
         * @param [callback] Callback function to remove
         */
        function removeFilter(filter, callback) {
            if (typeof filter === 'string') {
                _removeHook('filters', filter, callback);
            }

            return MethodsAvailable;
        }

        /**
         * Removes the specified hook by resetting the value of it.
         *
         * @param type Type of hook, either 'actions' or 'filters'
         * @param hook The hook (namespace.identifier) to remove
         * @private
         */
        function _removeHook(type, hook, callback, context) {
            var handlers, handler, i;

            if (!STORAGE[type][hook]) {
                return;
            }
            if (!callback) {
                STORAGE[type][hook] = [];
            } else {
                handlers = STORAGE[type][hook];
                if (!context) {
                    for (i = handlers.length; i--;) {
                        if (handlers[i].callback === callback) {
                            handlers.splice(i, 1);
                        }
                    }
                } else {
                    for (i = handlers.length; i--;) {
                        handler = handlers[i];
                        if (handler.callback === callback && handler.context === context) {
                            handlers.splice(i, 1);
                        }
                    }
                }
            }
        }

        /**
         * Adds the hook to the appropriate storage container
         *
         * @param type 'actions' or 'filters'
         * @param hook The hook (namespace.identifier) to add to our event manager
         * @param callback The function that will be called when the hook is executed.
         * @param priority The priority of this hook. Must be an integer.
         * @param [context] A value to be used for this
         * @private
         */
        function _addHook(type, hook, callback, priority, context) {
            var hookObject = {
                callback: callback,
                priority: priority,
                context: context
            };

            // Utilize 'prop itself' : http://jsperf.com/hasownproperty-vs-in-vs-undefined/19
            var hooks = STORAGE[type][hook];
            if (hooks) {
                hooks.push(hookObject);
                hooks = _hookInsertSort(hooks);
            } else {
                hooks = [hookObject];
            }

            STORAGE[type][hook] = hooks;
        }

        /**
         * Use an insert sort for keeping our hooks organized based on priority. This function is ridiculously faster
         * than bubble sort, etc: http://jsperf.com/javascript-sort
         *
         * @param hooks The custom array containing all of the appropriate hooks to perform an insert sort on.
         * @private
         */
        function _hookInsertSort(hooks) {
            var tmpHook, j, prevHook;
            for (var i = 1, len = hooks.length; i < len; i++) {
                tmpHook = hooks[i];
                j = i;
                while ((prevHook = hooks[j - 1]) && prevHook.priority > tmpHook.priority) {
                    hooks[j] = hooks[j - 1];
                    --j;
                }
                hooks[j] = tmpHook;
            }

            return hooks;
        }

        /**
         * Runs the specified hook. If it is an action, the value is not modified but if it is a filter, it is.
         *
         * @param type 'actions' or 'filters'
         * @param hook The hook ( namespace.identifier ) to be ran.
         * @param args Arguments to pass to the action/filter. If it's a filter, args is actually a single parameter.
         * @private
         */
        function _runHook(type, hook, args) {
            var handlers = STORAGE[type][hook],
                i, len;

            if (!handlers) {
                return (type === 'filters') ? args[0] : false;
            }

            len = handlers.length;
            if (type === 'filters') {
                for (i = 0; i < len; i++) {
                    args[0] = handlers[i].callback.apply(handlers[i].context, args);
                }
            } else {
                for (i = 0; i < len; i++) {
                    handlers[i].callback.apply(handlers[i].context, args);
                }
            }

            return (type === 'filters') ? args[0] : true;
        }

        // return all of the publicly available methods
        return MethodsAvailable;

    };

    window.wp = window.wp || {};
    window.wp.hooks = window.wp.hooks || new EventManager();

})(window);