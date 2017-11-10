// JavaScript Document
/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Stéphane Nahmani (sholby@sholby.net). */
(function($) {
        $.datepicker.regional['eng'] = {
                renderer: $.ui.datepicker.defaultRenderer,
                monthNames: ['January','February','March','April','May','June',
		            'July','August','September','October','November','December'],
                monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
		            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            		dayNamesMin: ['Su','Mo','Tu','We','Th','Fr','Sa'],
		            dateFormat: 'yy-mm-dd',
                firstDay: 1,
                prevText: '&#x3c;Prev', prevStatus: '',
                prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
                nextText: 'Next&#x3e;', nextStatus: '',
                nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
                currentText: 'Today', currentStatus: '',
                todayText: 'Today', todayStatus: '',
                clearText: '-', clearStatus: '',
                closeText: 'Done', closeStatus: '',
                yearStatus: '', monthStatus: '',
                weekHeader: 'Wk', weekStatus: '',
                dayStatus: 'DD d MM',   
                defaultStatus: '',
                isRTL: false
        };
        $.extend($.datepicker.defaults, $.datepicker.regional['eng']);
})(jQuery); 