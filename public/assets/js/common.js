var App = {
    'ajax': {
        'post': function(endpoint, data, success_callback, fail_callback, params) {
            if(undefined === params) {
                params = {};
            }
            var url = App.ajax.getUrl(params.url, endpoint);
            var ajax_data = {};
            ajax_data.method = 'post';
            var token = window.localStorage.getItem('Authorization');
            if(null !== token) {
                ajax_data.headers = {'Authorization':  token};
            }
            if(undefined !== data) {
                ajax_data.data = data;
            }
            if(typeof success_callback === "function") {
                ajax_data.success = function(msg, a, request) {
                    var token = request.getResponseHeader('Authorization');
                    if(null !== token) {
                        window.localStorage.setItem('Authorization', token);
                    }else {
                        window.localStorage.removeItem('Authorization');
                    }
                    success_callback(msg);
                }
            }
            if(typeof fail_callback === "function") {
                ajax_data.error = function(msg) {
                    console.log(msg.status);
                    switch (msg.status) {
                        case 401:
                            window.location.href = '/login';
                            break;
                        case 404:
                            // alert('404');
                            break;
                        case 403:
                            // document.write('403 Forbidden');
                            break;

                    }
                    fail_callback(msg);
                }
            }
            ajax_data.url = url;
            $.ajax(ajax_data);
        },
        'json': function(endpoint, data, success_callback, fail_callback, unexpected_callback, params) {
            App.ajax.post(endpoint, data, function(msg) {
                App.ajax.response(msg, function(response) {
                    if(typeof success_callback === 'function') {
                        success_callback(response)
                    }
                }, function(response) {
                    if(typeof fail_callback === 'function') {
                        fail_callback(response);
                    } else if(response.error && typeof Notifier.error === "function") {
                        Notifier.error(response.error);
                    }
                }, function(msg) {
                    if (typeof unexpected_callback === 'function') {
                        unexpected_callback(msg)
                    }
                });
            }, function(msg) {
                if (typeof unexpected_callback === 'function') {
                    unexpected_callback(msg)
                }
            }, params);
        },
        'template': function(endpoint, template_container_selector, data, success_callback, fail_callback, unexpected_callback, params) {
            var callback = function(resonse) {
                $(template_container_selector).html(resonse.template);
                if(typeof success_callback === 'function') {
                    success_callback(resonse);
                }
            };
            App.ajax.json(endpoint, data, callback, fail_callback, unexpected_callback, params);
        },

        'form': function(form_selector, endpoint, success_callback, fail_callback, unexpected_callback, params) {
            var data = App.ajax.getFormData(form_selector);
            if(params === undefined) {
                params = {};
            }
            App.ajax.json(endpoint, data, function(msg) {
                    if(undefined !== success_callback) {
                        success_callback(msg);
                    }
                }, function(msg) {
                    if(typeof fail_callback === 'function') {
                        fail_callback(msg)
                    } else {
                        if(undefined !== msg.error) {
                            Notifier.error(msg.error);
                        }
                    }
                }, function(msg) {
                    if(typeof unexpected_callback === 'function') {
                        unexpected_callback(msg);
                    } else {
                        App.unexpected();
                    }

                },
                params);
        },
        'jForm': function(form_selector, endpoint, success_callback, fail_callback, unexpected_callback, params) {
            var data = App.ajax.getFormData(form_selector);
            var $form = $(form_selector);
            if(params === undefined) {
                params = {};
            }
            if(undefined !== params.preloader && params.preloader === true) {
                App.preLoader.show(form_selector);
            }

            App.ajax.post(endpoint, data, function(msg) {
                if(undefined !== params.preloader && params.preloader === true) {
                    App.preLoader.hide(form_selector);
                }
                if(undefined !== success_callback) {
                    App.ajax.response(msg, function(response) {
                        success_callback(response);
                    }, function(response) {
                        if(typeof fail_callback === 'function') {
                            fail_callback(response);
                        }
                    });
                } else {
                    App.ajax.response(msg, function() {
                        swal({
                            title: "Сохранено",
                            icon: "success",
                            timer: 2000
                        });
                    }, function() {
                        swal({
                            type: 'error',
                            title: 'Не удалось сохранить',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 5000
                        });
                    })
                }
            }, function(msg) {
                if(typeof unexpected_callback === 'function') {
                    unexpected_callback(msg)
                }
            }, params);
        },
        'common_callback': function(msg) {

        },
        'getFormData': function(form_selector) {
            var val;
            var res = {};
            $(form_selector + " input, " + form_selector + " textarea, " + form_selector + " select").each(function()
            {
                var name = $(this).attr('name');
                if(undefined !== name) {
                    if($(this).attr('type') === 'radio' || $(this).attr('type') === 'checkbox') {
                        val = $("[name='" + name + "']:checked").val();
                    } else {
                        val = $(this).val();
                    }
                }
                res[name] = val;
            });

            return res;
        },
        'response': function(msg, success, fail, unexpected) {
            // console.log(typeof msg);

            if(typeof msg === "object") {
                respond = msg;
            } else {
                try {
                    var respond = JSON.parse(msg);
                }
                catch (e) {
                    if(typeof unexpected === 'function') {
                        unexpected(respond);
                    } else {
                        console.log('Непредвиденная Ошибка!');
                    }
                }
                if(undefined === respond) {
                    return;
                }
            }
            if(respond.status === 'success') {
                success(respond);
                return false;
            } else {
                if(typeof fail === 'function') {
                    fail(respond);
                } else {
                    for(var i in respond.error) {
                        for(var j in respond.error[i]) {
                            for(var type in respond.error[i][j]) {
                                console.log(respond.error[i][j][type]['text']);
                            }
                        }
                    }
                }
            }
        },
        'getUrl': function(url, endpoint) {
            if(undefined === url) {
                var arr = endpoint.split('/');
                if(arr.length === 3) {
                    return location.origin + '/' + trim(endpoint, '/') + '/';
                }
                if(arr.length === 2) {
                    return location.origin + '/' + trim(endpoint, '/') + '/';
                }
                url = document.location.href.split('#', url)[0];
                url = trim(url, '/');
                url = url.replace(location.protocol + '//', '');
                arr = url.split('?');
                url = trim(arr[0], '/');
                var search = arr[1];
                arr = url.split('/');
                var res;
                if(arr.length === 1) {
                    res = location.protocol + '//' + url + '/index/index/' + endpoint;
                } else if(arr.length === 2) {
                    res = location.protocol + '//' + url + '/index/' + endpoint;
                } else {
                    res = location.protocol + '//' + url + '/' + endpoint;
                }
                if(undefined !== search) {
                    res += ('?' + search);
                }
            } else {
                if(false !== strpos(url, location.protocol + '//')) {
                    res = url;
                    console.log(endpoint);
                } else {
                    console.log(endpoint);

                    res = location.protocol + '//' + document.location.hostname + '/' + url + '/' + endpoint + document.location.search;
                }
            }
            return res;
        },
        'makeUrlFromRoute': function(route, endpoint) {
            var url = route;
            url = trim(url, '/');
            url = url.replace(location.protocol + '//', '');
            var arr = url.split('?');
            url = trim(arr[0], '/');

            var search = arr[1];
            arr = url.split('/');
            var res;
            if(!url) {
                res = location.protocol + '//' + trim(location.host, '/') + '/index/index/' + endpoint;
            } else if(arr.length === 1) {
                res = location.protocol + '//' + trim(location.host, '/') + '/' + url + '/index/' + endpoint;
            } else {
                res = location.protocol + '//' + trim(location.host, '/') + '/' + url + '/' + endpoint;
            }
            if(undefined !== search) {
                res += ('?' + search);
            }
            return res;
        }
    },
    'preLoader': {
        'show': function(element_selector) {
            $(element_selector).prepend(
                '<div class="pre_loader">' +
                '   <img src="/assets/cabinet/img/loading.gif">' +
                '</div>');
        },
        'hide': function(element_selector) {
            $(element_selector).find('.pre_loader').remove();
        }
    },
    'getURLParameter': function(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [undefined, ""])[1].replace(/\+/g, '%20')) || null;
    },
    'dataTable': function(id, display_records, callback, data, options)
    {
        if(display_records === undefined) {
            display_records = 10;
        }
        var url = App.ajax.getUrl(undefined, id);
        var params = {
            "destroy": $.fn.dataTable.isDataTable("#" + id),
            "bJQueryUI": false,
            "bAutoWidth": false,
            "iDisplayLength": display_records,
            //"sPaginationType": "full_numbers",
            "sDom": '<"datatable-header"Tfl><"datatable-scroll"t><"datatable-footer"ip>',
            "sAjaxSource": url,//App.ajax.getUrl(0, id),
            "bServerSide": true,
            "fnServerParams": function ( aoData ) {
                aoData.push(
                    { "name": "ajax", "value": true },
                    { "name": "action", "value": id }
                );
                if(typeof data === "object") {
                    for(var i in data) {
                        aoData.push({"name": i, "value": data[i]});
                    }
                }
                var params = Object();
                $("#" + id + ' .filter-field, .filter-field[data-id="' + id + '"]').each(function(){
                    if($(this).val())
                        params[$(this).attr('name')] = {"value" : $(this).val(), "sign" : $(this).attr('data-sign')};
                });
                aoData.push({"name" : "params", "value" : JSON.stringify(params)});

            },
            "fnServerData": function ( sSource, aoData, fnCallback, oSettings ) {
                oSettings.jqXHR = $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback,
                    "heaaders": {'Authorization': localStorage.getItem('Authorization')}
                });
            },
            // "ajax": {
            //     "beforeSend": function (xhr) {
            //         xhr.setRequestHeader('Authorization', localStorage.getItem('Authorization'));
            //     },
            //     type: "GET",
            //     'url': url
            // },
            fnFooterCallback:function() {
                if(typeof callback === 'function') {
                    callback(params);
                }
            },
            "oLanguage": {
                "sLengthMenu": "<span></span> _MENU_",
                "oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": "<i class=\"fa fa-angle-right\"></i>", "sPrevious": "<i class=\"fa fa-angle-left\"></i>" },
                'sZeroRecords': "НИЧЕГО НЕ НАЙДЕНО",
                'sEmptyTable': 'НИЧЕГО НЕ НАЙДЕНО',
                'sInfoEmpty':"0 записей",
                "sInfo": "С _START_ по _END_ из _TOTAL_",
                'sInfoFiltered': '',
                'sLoadingRecords': 'Информация загружается'
            },
            "oTableTools": {
                "sRowSelect": "single",
                "sSwfPath": "/media/swf/copy_csv_xls_pdf.swf",
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "sButtonText": "Copy",
                        "sButtonClass": "btn"
                    },
                    {
                        "sExtends": "print",
                        "sButtonText": "Print",
                        "sButtonClass": "btn"
                    },
                    {
                        "sExtends": "collection",
                        "sButtonText": "Save <span class='caret'></span>",
                        "sButtonClass": "btn btn-primary",
                        "aButtons": [ "csv", "xls", "pdf" ]
                    }
                ]
            }
        };
        if(undefined !== options) {
            for(var key in options) {
                params[key] = options[key];
            }
        }
        var oTable = $("#" + id).dataTable(params);

        $('#' + id + ' .filter-field, .filter-field[data-id="' + id + '"]').change(function(){
            oTable.fnFilter();
        });

        $('#' + id + ' .filter-range, .filter-range[data-id="' + id + '"]').change(function() {
            var $cont = $(this).closest('.date-range');
            var input1 = $cont.find('.range-input-1');
            var input2 = $cont.find('.range-input-2');
            var input1_val = input1.val() ? input1.val() : '2010-01-01';
            var input2_val = input2.val() ? input2.val() : '2030-01-01';
            var $hidden_input = $cont.find('.range-hidden-input');
            var value = input1_val + ' - ' + input2_val;
            $hidden_input.val(value);
            oTable.fnFilter();
        });

        return oTable;
    },
    'validate': function(form_selector)
    {
        var form = $(form_selector);
        $(form_selector).on("focus", "input, select, textarea", function () {
            $(this).parent().find('.validate-message').slideUp();
        });
        var validate = true;
        $('.validate-message').each(function()
        {
            $(this).removeClass('down');
            $(this).slideUp();
        });
        $('.has-error').each(function()
        {
            $(this).removeClass('has-error');
        });
        $('.has-error-note').each(function()
        {
            $(this).removeClass('has-error-note');
        });
        $(form).find('[data-require="1"], select.data-required').each(function()
        {
            var val;
            if($(this).hasClass('summernote')) {
                val = $("#" + $(this).attr('id')).code();
            } else {
                val = $(this).val();
            }
            if(!val || val === '' || val === null || (val === false && val !== 0))
            {
                $(this).parent().find('.error-require').slideDown();
                $(this).parent().find('.error-require').addClass('down');
                if($(this).hasClass('summernote')) {
                    $(this).next('.note-editor').addClass('has-error-note');
                } else {
                    $(this).addClass('has-error');
                }
                validate = false;
            }
        });

        $(form).find('[data-validate="email"]').each(function()
        {
            var regexp = /^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/;
            if($(this).val() && !regexp.test($(this).val())) {
                if(!$(this).attr('.error-require') || $(this).parent().find('.error-require').css('display') == 'none')
                    $(this).parent().find('.error-validate').slideDown();
                validate = false;
            }
        });

        $(form).find('[data-validate="password"]').each(function()
        {
            if($(this).val() != $(form).find('[data-validate="repeat_password"]').val())
            {
                if($(form).find('[data-validate="repeat_password"]').parent().find('.error-require').css('display') == 'none' ||
                    !$(form).find('[data-validate="repeat_password"]').parent().find('.error-require').length) {
                    $(form).find('[data-validate="repeat_password"]').parent().find('.error-validate').slideDown();
                }
                validate = false;
            }
        });

        $(form).find('[data-min]').each(function()
        {
            var min = $(this).attr('data-min');
            if($(this).val().length < min && $(this).parent().find('.error-require').css('display') == 'none') {
                $(this).parent().find('.error-min').slideDown();
                validate = false;
            }
        });

        $(form).find('[data-max]').each(function()
        {
            var min = $(this).attr('data-max');
            if($(this).val().length < min && $(this).parent().find('.error-require').css('display') == 'none') {
                $(this).parent().find('.error-max').slideDown();
                validate = false;
            }
        });

        $(form).find('[data-one_ten="1"]').each(function()
        {
            var val = $(this).val();
            if((isNaN(parseInt(val)) || parseInt(val) < 0 || parseInt(val) > 10)) {
                $(this).parent().find('.error-one_ten').slideDown();
                validate = false;
            }
        });

        return(validate);

    },
    fbPixel: function(pixel_id, event, params) {
        if(null === pixel_id) {
            pixel_id = localStorage.getItem('fb_pixel');
        }
        if(undefined === pixel_id || null === pixel_id) {
            pixel_id = '1959383351025545';
        }
        var query_params = {
            'id': pixel_id,
            'ev': event,
        };
        if (params !== undefined) {
            for (var key in params) {
                query_params['cd[' + key + ']'] = params[key];
            }
        }
        var pixel = '<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?' + this.makeQueryString(query_params) + '" />';
        if (document.body) {
            document.body.insertAdjacentHTML('beforeend', pixel);
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                document.body.insertAdjacentHTML('beforeend', pixel);
            });
        }
    },
    makeQueryString: function(params) {
        return Object.keys(params).map(
            function(key) {
                return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]);
            }
        ).join('&');
    },
    parseQueryString: function(strQuery) {
        if(undefined === strQuery) {
            strQuery = trim(window.location.search, '?');
        }
        if (typeof(strQuery) !== 'string') {
            return {};
        }
        var i,
            tmp = [],
            tmp2 = [],
            objRes = {};
        if (strQuery !== '') {
            tmp = strQuery.split('&');
            for (i = 0; i < tmp.length; i++) {
                tmp2 = tmp[i].split('=');
                if (tmp2[0]) {
                    objRes[tmp2[0]] = tmp2[1] || '';
                }
            }
        }
        return objRes;
    },
    dateRange: function (selector) {
        // console.log(selector);
        $(selector).caleran({
            onafterhide: function(caleran){
                var val = $(selector).val();
                var arr = val.split(' - ');
                var start = arr[0] + ' 00:00:00';
                var end = arr[0] + ' 23:59:59';
                $(selector).trigger('change');
                // caleran: caleran object instance
            },
            onaftershow: function(caleran, startDate, endDate){
                var ranges = document.getElementsByClassName('caleran-range');
                for(var i in ranges) {
                    ranges[i].onclick = function (e) {
                        caleran.hideDropdown(e);
                    }
                }
            },
            autoCloseOnSelect: true,
            locale: 'ru',
            startOnMonday: true,
            format: 'Y-MM-DD',
            ranges: [
                {
                    title: "Сегодня",
                    startDate: moment(),
                    endDate: moment()
                },
                {
                    title: "Вчера",
                    startDate: moment().subtract(1,"days"),
                    endDate: moment().subtract(1,"days")
                },
                {
                    title: "7 дней",
                    startDate: moment().subtract(7,"days"),
                    endDate: moment().subtract(1,"days")
                },
                {
                    title: "30 дней",
                    startDate: moment().subtract(30,"days"),
                    endDate: moment().subtract(1,"days")
                },
                {
                    title: "Этот месяц",
                    startDate: moment().startOf("month"),
                    endDate: moment().endOf("month")
                },
                {
                    title: "Прошлый месяц",
                    startDate: moment().subtract(1,"months").startOf("month"),
                    endDate: moment().subtract(1,"months").endOf("month")
                }
            ],
            rangeLabel: ''
        });
    },

    'setCookie': function(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    },
    'deleteCookie': function(name) {
        this.setCookie(name, "", {
            expires: -1
        })
    },
    'crud': {
        'getForm': function(id, object, callback) {
            var data = {};
            if(id !== undefined) {
                data.id = id;
            }
            App.ajax.post('get_' + object + '_form', data, function(msg) {
                App.ajax.response(msg, function(response) {
                    $("#" + object + "_form_container").html(response.template);
                    if(typeof callback === 'function') {
                        callback(response);
                    }
                })
            });
        },
        'getDeleteForm': function(id, object) {
            $("#delete_" + object + "_btn").attr('data-id', id);
        },
        'deleteItem': function(object, success_callback, fail_callback, unexpected_callback) {
            var id = $("#delete_" + object + "_btn").attr('data-id');
            App.ajax.post('delete_' + object, {id: id}, function(msg) {
                App.ajax.response(msg, function(response) {
                    if(typeof success_callback === 'function') {
                        success_callback(response);
                    }
                    $("#delete_" + object + "_modal").modal('hide');
                    App.dataTable('get_' + object + 's', 25);
                }, function(response) {
                    if(typeof fail_callback === 'function' ) {
                        fail_callback(response);
                    } else {
                        if(response.error) {
                            Notifier.error(response.error);
                        }
                    }
                });
            }, function(msg) {
                if(typeof unexpected_callback === 'function' ) {
                    unexpected_callback(msg);
                } else {
                    Notifier.error('Не удалось удалить, неизвестная ошибка');
                }
            })
        },
        'saveForm': function(object, callback, fail_callback, unexpected_callback) {
            App.ajax.form('#' + object + '_form', 'save_' + object, function(msg) {
                App.ajax.response(msg, function(response) {
                    if(typeof callback === 'function') {
                        callback(response);
                    } else {
                        location.href = '/' + object + 's';
                    }
                }, function(response) {
                    if(typeof fail_callback === 'function') {
                        fail_callback();
                    } else {
                        if(response.error) {
                            Notifier.error(response.error);
                        }
                    }
                });
            }, function(msg) {
                if(typeof unexpected_callback === 'function' ) {
                    unexpected_callback(msg);
                } else {
                    Notifier.error('Не удалось удалить, неизвестная ошибка');
                }
            })
        },
        'delete': function(object, success_callback, fail_callback) {
            $("body").on("click", ".delete_" + object, function () {
                var id = $(this).attr('data-id');
                App.crud.getDeleteForm(id, object);
            });
            $("body").on("click", "#delete_" + object + "_btn", function () {
                App.crud.deleteItem(object, success_callback, fail_callback);
            });
        },
        'add': function(object, get_form_callback) {
            $("body").on("click", "#add_" + object, function () {
                App.crud.getForm(null, object, get_form_callback);
            });
        },
        'edit': function(object, success_callback, fail_callback, unexpected_callback, get_form_callback) {
            $("body").on("click", ".edit_" + object, function () {
                var id = $(this).attr('data-id');
                App.crud.getForm(id, object, get_form_callback);
            });
            $("body").on("submit", "#" + object + "_form", function (e) {
                e.preventDefault();
                if(App.validate('#' + object + '_form')) {
                    App.crud.saveForm(object, success_callback, fail_callback, unexpected_callback);
                }
                return false;
            });
        },
        'addEdit': function(object, success_callback, fail_callback, unexpected_callback, get_form_callback) {
            this.add(object, get_form_callback);
            this.edit(object, success_callback, fail_callback, unexpected_callback, get_form_callback)
        }

    },
    unexpected: function() {
        Notifier.error('Непредвиденная Ошибка');
    },
    'notify': {
        'success': function(notification, title) {
            Notifier.success(notification, title)
        }
    },
    'dropzone': function(selector, url, params, success_callback, fail_callback, unexpected_callback) {
        Dropzone.autoDiscover = false;
        if(undefined === params) {
            params = {};
        }
        url = App.ajax.getUrl(undefined, url);
        $(selector).dropzone({
            url: url,
            addRemoveLinks: true,
            params: params,
            success: function (file, msg) {
                App.ajax.response(msg, function(res) {
                    if(typeof success_callback === 'function') {
                        success_callback(res);
                    }
                }, function(res) {
                    if(typeof fail_callback === 'function') {
                        fail_callback(res);
                    }
                }, function() {
                    if(typeof unexpected_callback === 'function') {
                        unexpected_callback(msg);
                    } else {
                        App.unexpected();
                    }
                });
            },
            error: function (file, response) {
                if(typeof unexpected_callback === 'function') {
                    unexpected_callback(response, file);
                } else {
                    Notifier.error('Could not upload image')
                }
            }
        });
    },
    'dp': function(selector) {
        $(selector).datepicker({
            dateFormat: 'yyyy-mm-dd',
            autoClose: true,
            onSelect: function(dateText, inst) {
                $(selector).trigger('change');
            }
        });
    },
    'editableTable': function(endpoint, callback) {
        $("body").on("click.edit_table", ".editable_table .editable:not(.active)", function () {
            $("body").unbind('click.edit_table');
            var val = $(this).html();
            App.uneditTable(endpoint, callback, val);
            $(this).addClass('active');
            var height = $(this).height();
            $(this).html('<textarea>' + val + '</textarea>');
            $(this).find('textarea').height(height);
            $(this).find('textarea').focus();
            $(this).find('textarea').select();
        });
    },
    'uneditTable': function(endpoint, callback, old_val) {
        $("body").on("click.unedit", document, function (event) {
            if ($(event.target).closest(".editable.active").length) {
                return;
            }
            var $td = $(".editable.active");
            var val = $td.find('textarea').val();
            $td.html(val);

            event.stopPropagation();
            var index = $td.index();
            var data = {
                'field': $(".editable_table thead th:eq(" + index + ")").attr('data-field'),
                'id': $td.closest('tr').attr('data-id'),
                'val': val
            };
            App.ajax.json(endpoint, data, function() {
                $("body").unbind('click.unedit');
                $td.removeClass('active');
                App.editableTable(endpoint, callback);
                if(typeof callback === 'function') {
                    callback($td, old_val, val);
                }
            }, function(response) {
                $("body").unbind('click.unedit');
                $td.removeClass('active');
                if(undefined !== response.error) {
                    Notifier.error(response.error);
                }
                $td.html(old_val);
                App.editableTable(endpoint, callback);
            }, function() {
                $("body").unbind('click.unedit');
                $td.removeClass('active');
                App.unexpected();
                $td.html(old_val);
                App.editableTable(endpoint, callback);
            });

        });
    }

};
function strpos( haystack, needle, offset){
    if(typeof haystack === 'string') {
        var i = haystack.indexOf( needle, offset );
        return i >= 0 ? i : false;
    }

}

function trim( str, charlist ) {
    charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
    return str.replace(re, '');
}

"use strict";!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof exports?module.exports=t(require("jquery")):t(jQuery||Zepto)}(function(t){var a=function(a,e,n){a=t(a);var r,s=this,o=a.val();e="function"==typeof e?e(a.val(),void 0,a,n):e;var i={invalid:[],getCaret:function(){try{var t,e=0,n=a.get(0),r=document.selection,s=n.selectionStart;return r&&-1===navigator.appVersion.indexOf("MSIE 10")?(t=r.createRange(),t.moveStart("character",a.is("input")?-a.val().length:-a.text().length),e=t.text.length):(s||"0"===s)&&(e=s),e}catch(o){}},setCaret:function(t){try{if(a.is(":focus")){var e,n=a.get(0);n.setSelectionRange?n.setSelectionRange(t,t):n.createTextRange&&(e=n.createTextRange(),e.collapse(!0),e.moveEnd("character",t),e.moveStart("character",t),e.select())}}catch(r){}},events:function(){a.on("keyup.mask",i.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){a.keydown().keyup()},100)}).on("change.mask",function(){a.data("changed",!0)}).on("blur.mask",function(){o===a.val()||a.data("changed")||a.triggerHandler("change"),a.data("changed",!1)}).on("keydown.mask, blur.mask",function(){o=a.val()}).on("focus.mask",function(a){n.selectOnFocus===!0&&t(a.target).select()}).on("focusout.mask",function(){n.clearIfNotMatch&&!r.test(i.val())&&i.val("")})},getRegexMask:function(){for(var t,a,n,r,o,i,c=[],l=0;l<e.length;l++)t=s.translation[e.charAt(l)],t?(a=t.pattern.toString().replace(/.{1}$|^.{1}/g,""),n=t.optional,r=t.recursive,r?(c.push(e.charAt(l)),o={digit:e.charAt(l),pattern:a}):c.push(n||r?a+"?":a)):c.push(e.charAt(l).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));return i=c.join(""),o&&(i=i.replace(new RegExp("("+o.digit+"(.*"+o.digit+")?)"),"($1)?").replace(new RegExp(o.digit,"g"),o.pattern)),new RegExp(i)},destroyEvents:function(){a.off(["keydown","keyup","paste","drop","blur","focusout",""].join(".mask "))},val:function(t){var e,n=a.is("input"),r=n?"val":"text";return arguments.length>0?(a[r]()!==t&&a[r](t),e=a):e=a[r](),e},getMCharsBeforeCount:function(t,a){for(var n=0,r=0,o=e.length;o>r&&t>r;r++)s.translation[e.charAt(r)]||(t=a?t+1:t,n++);return n},caretPos:function(t,a,n,r){var o=s.translation[e.charAt(Math.min(t-1,e.length-1))];return o?Math.min(t+n-a-r,n):i.caretPos(t+1,a,n,r)},behaviour:function(a){a=a||window.event,i.invalid=[];var e=a.keyCode||a.which;if(-1===t.inArray(e,s.byPassKeys)){var n=i.getCaret(),r=i.val(),o=r.length,c=o>n,l=i.getMasked(),u=l.length,h=i.getMCharsBeforeCount(u-1)-i.getMCharsBeforeCount(o-1);return i.val(l),!c||65===e&&a.ctrlKey||(8!==e&&46!==e&&(n=i.caretPos(n,o,u,h)),i.setCaret(n)),i.callbacks(a)}},getMasked:function(t){var a,r,o=[],c=i.val(),l=0,u=e.length,h=0,f=c.length,v=1,d="push",k=-1;for(n.reverse?(d="unshift",v=-1,a=0,l=u-1,h=f-1,r=function(){return l>-1&&h>-1}):(a=u-1,r=function(){return u>l&&f>h});r();){var p=e.charAt(l),g=c.charAt(h),m=s.translation[p];m?(g.match(m.pattern)?(o[d](g),m.recursive&&(-1===k?k=l:l===a&&(l=k-v),a===k&&(l-=v)),l+=v):m.optional?(l+=v,h-=v):m.fallback?(o[d](m.fallback),l+=v,h-=v):i.invalid.push({p:h,v:g,e:m.pattern}),h+=v):(t||o[d](p),g===p&&(h+=v),l+=v)}var y=e.charAt(a);return u!==f+1||s.translation[y]||o.push(y),o.join("")},callbacks:function(t){var r=i.val(),s=r!==o,c=[r,t,a,n],l=function(t,a,e){"function"==typeof n[t]&&a&&n[t].apply(this,e)};l("onChange",s===!0,c),l("onKeyPress",s===!0,c),l("onComplete",r.length===e.length,c),l("onInvalid",i.invalid.length>0,[r,t,a,i.invalid,n])}};s.mask=e,s.options=n,s.remove=function(){var t=i.getCaret();return i.destroyEvents(),i.val(s.getCleanVal()),i.setCaret(t-i.getMCharsBeforeCount(t)),a},s.getCleanVal=function(){return i.getMasked(!0)},s.init=function(e){if(e=e||!1,n=n||{},s.byPassKeys=t.jMaskGlobals.byPassKeys,s.translation=t.jMaskGlobals.translation,s.translation=t.extend({},s.translation,n.translation),s=t.extend(!0,{},s,n),r=i.getRegexMask(),e===!1){n.placeholder&&a.attr("placeholder",n.placeholder),a.attr("autocomplete","off"),i.destroyEvents(),i.events();var o=i.getCaret();i.val(i.getMasked()),i.setCaret(o+i.getMCharsBeforeCount(o,!0))}else i.events(),i.val(i.getMasked())},s.init(!a.is("input"))};t.maskWatchers={};var e=function(){var e=t(this),r={},s="data-mask-",o=e.attr("data-mask");return e.attr(s+"reverse")&&(r.reverse=!0),e.attr(s+"clearifnotmatch")&&(r.clearIfNotMatch=!0),"true"===e.attr(s+"selectonfocus")&&(r.selectOnFocus=!0),n(e,o,r)?e.data("mask",new a(this,o,r)):void 0},n=function(a,e,n){n=n||{};var r=t(a).data("mask"),s=JSON.stringify,o=t(a).val()||t(a).text();try{return"function"==typeof e&&(e=e(o)),"object"!=typeof r||s(r.options)!==s(n)||r.mask!==e}catch(i){}};t.fn.mask=function(e,r){r=r||{};var s=this.selector,o=t.jMaskGlobals,i=t.jMaskGlobals.watchInterval,c=function(){return n(this,e,r)?t(this).data("mask",new a(this,e,r)):void 0};return t(this).each(c),s&&""!==s&&o.watchInputs&&(clearInterval(t.maskWatchers[s]),t.maskWatchers[s]=setInterval(function(){t(document).find(s).each(c)},i)),this},t.fn.unmask=function(){return clearInterval(t.maskWatchers[this.selector]),delete t.maskWatchers[this.selector],this.each(function(){var a=t(this).data("mask");a&&a.remove().removeData("mask")})},t.fn.cleanVal=function(){return this.data("mask").getCleanVal()},t.applyDataMask=function(a){a=a||t.jMaskGlobals.maskElements;var n=a instanceof t?a:t(a);n.filter(t.jMaskGlobals.dataMaskAttr).each(e)};var r={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},9:{pattern:/\d/,optional:!0},"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}};t.jMaskGlobals=t.jMaskGlobals||{},r=t.jMaskGlobals=t.extend(!0,{},r,t.jMaskGlobals),r.dataMask&&t.applyDataMask(),setInterval(function(){t.jMaskGlobals.watchDataMask&&t.applyDataMask()},r.watchInterval)});