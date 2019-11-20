

let hexToRgba = function(hex, opacity) {
    let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    let rgb = result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;

    return 'rgba(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ', ' + opacity + ')';
};

setTimeout(function() {
    $('.page-loader-wrapper').fadeOut();
}, 50);

$(document).ready(function() {
    /** Constant div card */
    const DIV_CARD = 'div.card';

    /** Initialize tooltips */
    $('[data-toggle="tooltip"]').tooltip();

    /** Initialize popovers */
    $('[data-toggle="popover"]').popover({
        html: true
    });

    /** Function for remove card */
    $('[data-toggle="card-remove"]').on('click', function(e) {
        let $card = $(this).closest(DIV_CARD);

        $card.remove();

        e.preventDefault();
        return false;
    });

    /** Function for collapse card */
    $('[data-toggle="card-collapse"]').on('click', function(e) {
        let $card = $(this).closest(DIV_CARD);

        $card.toggleClass('card-collapsed');

        e.preventDefault();
        return false;
    });

    /** Function for fullscreen card */
    $('[data-toggle="card-fullscreen"]').on('click', function(e) {
        let $card = $(this).closest(DIV_CARD);

        $card.toggleClass('card-fullscreen').removeClass('card-collapsed');

        e.preventDefault();
        return false;
    });

    /**  */
    if ($('[data-sparkline]').length) {
        let generateSparkline = function($elem, data, params) {
            $elem.sparkline(data, {
                type: $elem.attr('data-sparkline-type'),
                height: '100%',
                barColor: params.color,
                lineColor: params.color,
                fillColor: 'transparent',
                spotColor: params.color,
                spotRadius: 0,
                lineWidth: 2,
                highlightColor: hexToRgba(params.color, .6),
                highlightLineColor: '#666',
                defaultPixelsPerValue: 5
            });
        };

        require(['sparkline'], function() {
        $('[data-sparkline]').each(function() {
            let $chart = $(this);

            generateSparkline($chart, JSON.parse($chart.attr('data-sparkline')), {
            color: $chart.attr('data-sparkline-color')
            });
        });
        });
    }

    /**  */
    if ($('.chart-circle').length) {
        require(['circle-progress'], function() {
        $('.chart-circle').each(function() {
            let $this = $(this);

            $this.circleProgress({
            fill: {
                color: buzzer.colors[$this.attr('data-color')] || buzzer.colors.blue
            },
            size: $this.height(),
            startAngle: -Math.PI / 4 * 2,
            emptyFill: '#F4F4F4',
            lineCap: 'round'
            });
        });
        });
    }

    // Mega menu
	 $('a.mega_menu').on('click', function() {
        $('.mega_menubar').toggleClass('open vivify pullDown');
    });

    // right side bar
	 $('a.settingbar').on('click', function() {
        $('.right_sidebar').toggleClass('open');
    });
    
    //Skin changer
	$('.choose-skin li').on('click', function() {
	    var $body = $('body');
	    var $this = $(this);
  
	    var existTheme = $('.choose-skin li.active').data('theme');
	    $('.choose-skin li').removeClass('active');
	    $body.removeClass('theme-' + existTheme);
	    $this.addClass('active');
	    $body.addClass('theme-' + $this.data('theme'));
    });    

    // (Optional) Active an item if it has the class "is-active"	
    $(".accordion2 > .accordion-item.is-active").children(".accordion-panel").slideDown();
    
    $(".accordion2 > .accordion-item").click(function() {
        // Cancel the siblings
        $(this).siblings(".accordion-item").removeClass("is-active").children(".accordion-panel").slideUp();
        // Toggle the item
        $(this).toggleClass("is-active").children(".accordion-panel").slideToggle("ease-out");
    });

    // table-filter js
    $(document).ready(function () {
        $('.star').on('click', function () {
            $(this).toggleClass('star-checked');
        });

        $('.ckbox label').on('click', function () {
            $(this).parents('tr').toggleClass('selected');
        });

        $('.btn-filter').on('click', function () {
            var $target = $(this).data('target');
            if ($target != 'all') {
                $('.table tr').css('display', 'none');
                $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
            } else {
                $('.table tr').css('display', 'none').fadeIn('slow');
            }
        });
    });
	
	// ThemeMakker website live chat js 
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/59f5afbbbb0c3f433d4c5c4c/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
    
});

require(['sparkline', 'jquery'], function(sparkline, $) {
    
    // block-header bar chart js
    $('.bh_visitors').sparkline('html', {
        type: 'bar',
        height: '16px',
        barColor: '#467fcf',
        barWidth: 5,
    });
    $('.bh_visits').sparkline('html', {
        type: 'bar',
        height: '16px',
        barColor: '#5eba00',
        barWidth: 5,
    });
    $('.bh_chats').sparkline('html', {
        type: 'bar',
        height: '16px',
        barColor: '#f1c40f',
        barWidth: 5,
    });
});
require(['datepicker', 'jquery'], function(datepicker, $) {
    
    $('.datepicker').datepicker();

    $('.datepicker').datepicker({
        format: 'mm/dd/yyyy',
        startDate: '-3d'
    });
    $('.datepicker').datepicker({
        startDate: '-3d'
    });
    $.fn.datepicker.defaults.format = "mm/dd/yyyy";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });

    // Date picker
    $('.inline-datepicker').datepicker({
        todayHighlight: true
    });    
});
require(['jquery_multiselect', 'jquery'], function(jquery_multiselect, $) {
   
    // Multiselect
    $('#multiselect1, #multiselect2, #single-selection, #multiselect5, #multiselect6').multiselect({
        maxHeight: 300
    });
    //Multi-select
    $('#optgroup').multiSelect({ selectableOptgroup: true });

    $('#multiselect3-all').multiselect({
        includeSelectAllOption: true,
    });

    $('#multiselect4-filter').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 200
    });

    $('#multiselect-size').multiselect({
        buttonClass: 'btn btn-default btn-sm'
    });

    $('#multiselect-link').multiselect({
        buttonClass: 'btn btn-link'
    });

    $('#multiselect-color').multiselect({
        buttonClass: 'btn btn-primary'
    });

    $('#multiselect-color2').multiselect({
        buttonClass: 'btn btn-success'
    });
});
require(['bootstrap_colorpicker', 'jquery'], function(bootstrap_colorpicker, $){

    $('.colorpicker').colorpicker();

});
require(['inputmask', 'jquery'], function(inputmask, $){
    
    var $demoMaskedInput = $('.demo-masked-input');
    //Date
    $demoMaskedInput.find('.date').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });
    //Time
    $demoMaskedInput.find('.time12').inputmask('hh:mm t', { placeholder: '__:__ _m', alias: 'time12', hourFormat: '12' });
    $demoMaskedInput.find('.time24').inputmask('hh:mm', { placeholder: '__:__ _m', alias: 'time24', hourFormat: '24' });
    //Date Time
    $demoMaskedInput.find('.datetime').inputmask('d/m/y h:s', { placeholder: '__/__/____ __:__', alias: "datetime", hourFormat: '24' });
    //Mobile Phone Number
    $demoMaskedInput.find('.mobile-phone-number').inputmask('+99 (999) 999-99-99', { placeholder: '+__ (___) ___-__-__' });
    //Phone Number
    $demoMaskedInput.find('.phone-number').inputmask('+99 (999) 999-99-99', { placeholder: '+__ (___) ___-__-__' });
    //Dollar Money
    $demoMaskedInput.find('.money-dollar').inputmask('99,99 $', { placeholder: '__,__ $' });
    //IP Address
    $demoMaskedInput.find('.ip').inputmask('999.999.999.999', { placeholder: '___.___.___.___' });
    //Credit Card
    $demoMaskedInput.find('.credit-card').inputmask('9999 9999 9999 9999', { placeholder: '____ ____ ____ ____' });
    //Email
    $demoMaskedInput.find('.email').inputmask({ alias: "email" });
    //Serial Key
    $demoMaskedInput.find('.key').inputmask('****-****-****-****', { placeholder: '____-____-____-____' });

});
require(['maskedinput', 'jquery'], function(maskedinput, $){

    // Masked Inputs
    $('#phone').mask('(999) 999-9999');
    $('#phone-ex').mask('(999) 999-9999? x99999');
    $('#tax-id').mask('99-9999999');
    $('#ssn').mask('999-99-9999');
    $('#product-key').mask('a*-999-a999');

});
require(['jquery', 'selectize'], function ($, selectize) {
    $(document).ready(function () {
        $('#input-tags').selectize({
            delimiter: ',',
            persist: false,
            create: function (input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#select-beast').selectize({});

        $('#select-users').selectize({
            render: {
                option: function (data, escape) {
                    return '<div>' +
                        '<span class="image"><img src="' + data.image + '" alt=""></span>' +
                        '<span class="title">' + escape(data.text) + '</span>' +
                        '</div>';
                },
                item: function (data, escape) {
                    return '<div>' +
                        '<span class="image"><img src="' + data.image + '" alt=""></span>' +
                        escape(data.text) +
                        '</div>';
                }
            }
        });

        $('#select-countries').selectize({
            render: {
                option: function (data, escape) {
                    return '<div>' +
                        '<span class="image"><img src="' + data.image + '" alt=""></span>' +
                        '<span class="title">' + escape(data.text) + '</span>' +
                        '</div>';
                },
                item: function (data, escape) {
                    return '<div>' +
                        '<span class="image"><img src="' + data.image + '" alt=""></span>' +
                        escape(data.text) +
                        '</div>';
                }
            }
        });
    });
});