var gxml;


var calculator = {
    getCategories: function() {
        var table_id = this.id;
        var calc = this;
        var lastid = calc.getLastRowId();
        var xml = gxml;
        $(xml).find('category').each(function() {
            var $category = $(this);
            var catname = $category.attr('name');
            console.log('#' + calculator.id + 'category' + lastid);
            $('#' + table_id + 'category' + lastid).append($('<option value="' + catname + '">' + catname + '</option>'));
        });
        // $('#' + calculator.id + 'category' + lastid).bind('change', function() {
        //     var category_name = $(this).val();
        //     var id = $(this).attr('id').substring(8);
        //     getProducts(category_name, id);
        // });

        $("body").on("change", '#' + table_id + 'category' + lastid, function () {
            var category_name = $(this).val();
            var id = $(this).attr('id').substring(11);
            calc.getProducts(category_name, id);
        });
    },
    getProducts: function(category, id) {
        var table_id = this.id;
        console.log(table_id);
        var calc = this;
        var xml = gxml;
        console.log(xml);
        $('#' + table_id + 'product' + id).empty().append('<option value="-1">(Выберите)</option>');
        $(xml).find('category[name="' + category + '"] product').each(function() {
            var $product = $(this);
            var prodname = $product.attr('name');
            console.log(prodname);
            $('#' + table_id + 'product' + id).append('<option value="' + prodname + '">' + prodname + '</option>');
        });
        $('#' + table_id + 'product' + id).bind('change', function() {
            if($('#' + table_id + 'row' + (parseInt(id) + 1)).length == 0) {
                calc.addNewRow();
                calc.getCategories();
            }
        });
        $('#' + table_id + 'count' + id).bind('keyup', function() {
            calc.countInfo(id);
        });
    },
    id: null,
    init: function(id) {
        this.id = id;
        var table_id = this.id;
        var calc = this;
        $("#" + this.id + " tr").each(function() {
            var el = $(this).attr('id');
            if(undefined !== el && el !== 'total') {
                var id = el.substr(6);
                // console.log(id);
                $("body").on("change",'#' + table_id + 'product' + id, function () {
                    calc.countInfo(id);
                    if($('#' + table_id + 'row' + (parseInt(id) + 1)).length == 0) {
                        calc.addNewRow();
                        calc.getCategories();
                    }
                });
                $("body").on("keyup", '#' + table_id + 'count' + id, function () {
                    calc.countInfo(id);
                });
                $("body").on("change", '#' + table_id + 'category' + id, function () {
                    var category_name = $(this).val();
                    var id = $(this).attr('id').substring(11);
                    calc.getProducts(category_name, id);
                });
            }

        });
        var table = localStorage.getItem(this.id);
        if(null === table) {
            calc.addNewRow();
        }

        calc.getCategories();
    },
    countInfo: function(id) {
        var table_id = this.id;
        var calc = this;
        var xml = gxml;
        var count = $('#' + table_id + 'count' + id).val();
        var catname = $('#' + table_id + 'category' + id + ' option:selected').val();
        var prodname = $('#' + table_id + 'product' + id + ' option:selected').val();
        var product = $(xml).find('category[name="' + catname + '"] product[name="' + prodname + '"]');

        if (catname == -1 || prodname == -1)
            return;

        $('#' + table_id + 'protein' + id).val((product.attr('protein') * count / 100).toFixed(1));
        $('#' + table_id + 'fat' + id).val((product.attr('fat') * count / 100).toFixed(1));
        $('#' + table_id + 'carbohydrate' + id).val((product.attr('carbohydrate') * count / 100).toFixed(1));
        $('#' + table_id + 'strange' + id).val((product.attr('ccal') * count / 100).toFixed(1));

        var totcount = 0;
        var totprotein = 0;
        var totfat = 0;
        var totcarbohydrate = 0;
        var totstrange = 0;
        $('#' + table_id + ' input[class=count]').each(function() {
            if($(this).val().length != 0)
                totcount += parseFloat($(this).val());
        });
        $('#' + table_id + ' input[class=protein]').each(function() {
            if($(this).val().length != 0)
                totprotein += parseFloat($(this).val());
        });
        $('#' + table_id + ' input[class=fat]').each(function() {
            if($(this).val().length != 0)
                totfat += parseFloat($(this).val());
        });
        $('#' + table_id + ' input[class=carbohydrate]').each(function() {
            if($(this).val().length != 0)
                totcarbohydrate += parseFloat($(this).val());
        });
        $('#' + table_id + ' input[class=ccal]').each(function() {
            if($(this).val().length != 0)
                totstrange += parseFloat($(this).val());
        });

        $('#' + table_id + 'total #' + table_id + 'totcount').val(totcount.toFixed(1));
        $('#' + table_id + 'total #' + table_id + 'totprotein').val(totprotein.toFixed(1));
        $('#' + table_id + 'total #' + table_id + 'totfat').val(totfat.toFixed(1));
        $('#' + table_id + 'total #' + table_id + 'totcarbohydrate').val(totcarbohydrate.toFixed(1));
        $('#' + table_id + 'total #' + table_id + 'totccal').val(totstrange.toFixed(1));
    },

     getLastRowId: function() {
        return $('#' + this.id + ' .row:last').attr('id').substring(6);
    },

    addNewRow: function() {
        var table_id = this.id;
        var calc = this;
        var lastRow = $('#' + table_id + ' tr:last');
        $('#' + table_id + ' tr:last').remove();

        var lastid = calc.getLastRowId();

        var id = ++lastid;
        var newRow = '<tr class="row"><td><select class="category"><option value="-1">(Выберите 1)</option></select></td><td><select class="product"/></td><td><input type="text" class="count" /></td><td><input type="text" class="protein" readonly="readonly" /></td><td><input type="text" class="fat" readonly="readonly" /></td><td><input type="text" class="carbohydrate" readonly="readonly" /></td><td><input type="text" class="ccal" readonly="readonly" /></td></tr>';

        $('#' + table_id + '').append(newRow);
        var count = localStorage.getItem('count');
        if(null === count) {
            count = 0;
        }
        $('#' + table_id + ' tr:last').attr('id', table_id + 'row' + id);
        $('#' + table_id + ' tr:last td:eq(0) select').attr('id', table_id + 'category' + id);
        $('#' + table_id + ' tr:last td:eq(1) select').attr('id', table_id + 'product' + id).attr('name', table_id + 'product' + id);
        $('#' + table_id + ' tr:last td:eq(2) input').attr('id', table_id + 'count' + id).attr('name', table_id + 'count' + id);
        $('#' + table_id + ' tr:last td:eq(3) input').attr('id', table_id + 'protein' + id).attr('name', table_id + 'protein' + id).attr('value', 0);
        $('#' + table_id + ' tr:last td:eq(4) input').attr('id', table_id + 'fat' + id).attr('name', table_id + 'fat' + id).attr('value', 0);
        $('#' + table_id + ' tr:last td:eq(5) input').attr('id',table_id +  'carbohydrate' + id).attr('name', table_id + 'carbohydrate' + id).attr('value', 0);
        $('#' + table_id + ' tr:last td:eq(6) input').attr('id', table_id + 'strange' + id).attr('name', table_id + 'strange' + id).attr('value', 0);
        $('#' + table_id + '').append(lastRow);
    },

    save: function() {
        $("option:selected").attr('selected', 'selected');
        $('input').each(function() {
            $(this).attr('value', $(this).val());
        });
        $(".calc_table").each(function() {
            var id = $(this).attr('id');
            var html = $("#" + id).html();
            localStorage.setItem(id, html);
        });

    },
     calc_print: function(){
        // $("body :not(.calc)").each(function(){
        // $(this).hide();
        // });
        window.print();
    }
};
$(document).ready(function() {
    // $('.date-pick').datePicker();
    // $.get('http://easy-lose-weight.info/calc1/list.xml', function(xml) {});
    // {
    //     var xml = '<?xml version="1.0" encoding="utf-8"?>\n' +
    //         '<categories>\n' +
    //         '\t<category name="Алкогольные напитки">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Бренди" protein="0" fat="0" carbohydrate="0.5" ccal="225" />\n' +
    //         '\t\t\t<product name="Вермут" protein="0" fat="0" carbohydrate="15.9" ccal="155" />\n' +
    //         '\t\t\t<product name="Вино десертное" protein="0.5" fat="0" carbohydrate="20" ccal="172" />\n' +
    //         '\t\t\t<product name="Вино ликерное" protein="0.5" fat="0" carbohydrate="30" ccal="212" />\n' +
    //         '\t\t\t<product name="Вино полусладкое" protein="0.2" fat="0" carbohydrate="5" ccal="88" />\n' +
    //         '\t\t\t<product name="Вино полусладкое игристое" protein="0" fat="0" carbohydrate="6" ccal="69" />\n' +
    //         '\t\t\t<product name="Вино полусухое" protein="0.3" fat="0" carbohydrate="2.5" ccal="78" />\n' +
    //         '\t\t\t<product name="Вино столовое белое 11%" protein="0.2" fat="0" carbohydrate="0.2" ccal="65" />\n' +
    //         '\t\t\t<product name="Вино столовое красное 12%" protein="0.3" fat="0" carbohydrate="0.2" ccal="71" />\n' +
    //         '\t\t\t<product name="Вино сухое 12%" protein="0" fat="0" carbohydrate="2.3" ccal="76" />\n' +
    //         '                        <product name="Виски" protein="0" fat="0" carbohydrate="0.4" ccal="228" />\n' +
    //         '\t\t\t<product name="Водка" protein="0" fat="0" carbohydrate="0.1" ccal="235" />\n' +
    //         '\t\t\t<product name="Джин" protein="0" fat="0" carbohydrate="0" ccal="220" />\n' +
    //         '\t\t\t<product name="Коньяк" protein="0" fat="0" carbohydrate="0.1" ccal="239" />\n' +
    //         '\t\t\t<product name="Ликер 24%" protein="0" fat="0" carbohydrate="53" ccal="345" />\n' +
    //         '\t\t\t<product name="Ликёр кремовый" protein="3" fat="13" carbohydrate="25" ccal="327" />\n' +
    //         '\t\t\t<product name="Пиво  Портер" protein="0" fat="0" carbohydrate="4.9" ccal="61" />\n' +
    //         '\t\t\t<product name="Пиво крепкое" protein="0" fat="0" carbohydrate="3.7" ccal="60" />\n' +
    //         '\t\t\t<product name="Пиво светлое 1.8%" protein="0.2" fat="0" carbohydrate="4.3" ccal="29" />\n' +
    //         '\t\t\t<product name="Пиво светлое 2.8%" protein="0.6" fat="0" carbohydrate="4.8" ccal="37" />\n' +
    //         '\t\t\t<product name="Пиво светлое 4.5%" protein="0.6" fat="0" carbohydrate="3.8" ccal="45" />\n' +
    //         '\t\t\t<product name="Пиво темное" protein="0" fat="0" carbohydrate="3.8" ccal="36" />\n' +
    //         '\t\t\t<product name="Портвейн 20%" protein="0" fat="0" carbohydrate="13.7" ccal="67" />\n' +
    //         '\t\t\t<product name="Ром" protein="0" fat="0" carbohydrate="0" ccal="220" />\n' +
    //         '\t\t\t<product name="Шампанское" protein="0.2" fat="0" carbohydrate="5" ccal="88" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Безалкогольные напитки">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Абрикосовый компот" protein="0.5" fat="0" carbohydrate="21" ccal="85" />\n' +
    //         '\t\t\t<product name="Абрикосовый сок" protein="0.9" fat="0.1" carbohydrate="9" ccal="38" />\n' +
    //         '\t\t\t<product name="Айвовый компот" protein="0.4" fat="0" carbohydrate="20" ccal="79" />\n' +
    //         '\t\t\t<product name="Айвовый сок" protein="0.5" fat="0" carbohydrate="10.6" ccal="45" />\n' +
    //         '\t\t\t<product name="Ананасовый компот" protein="0.1" fat="0.1" carbohydrate="14" ccal="71" />\n' +
    //         '\t\t\t<product name="Ананасовый нектар" protein="0.1" fat="0" carbohydrate="12.9" ccal="54" />\n' +
    //         '\t\t\t<product name="Ананасовый сок" protein="0.3" fat="0.1" carbohydrate="11.4" ccal="48" />\n' +
    //         '\t\t\t<product name="Апельсиновый нектар" protein="0.3" fat="0" carbohydrate="10.1" ccal="43" />\n' +
    //         '\t\t\t<product name="Апельсиновый сок" protein="0.9" fat="0.2" carbohydrate="8.1" ccal="36" />\n' +
    //         '\t\t\t<product name="Бузинный сок" protein="1.1" fat="0.2" carbohydrate="5.1" ccal="27" />\n' +
    //         '\t\t\t<product name="Виноградный компот" protein="0.5" fat="0" carbohydrate="19.7" ccal="77" />\n' +
    //         '\t\t\t<product name="Виноградный сок" protein="0.3" fat="0" carbohydrate="14" ccal="54" />\n' +
    //         '\t\t\t<product name="Вишнёвый компот" protein="0.6" fat="0" carbohydrate="24.5" ccal="99" />\n' +
    //         '\t\t\t<product name="Вишнёвый нектар" protein="0.1" fat="0" carbohydrate="12" ccal="50" />\n' +
    //         '\t\t\t<product name="Вишнёвый сок" protein="0.7" fat="0" carbohydrate="10.2" ccal="47" />\n' +
    //         '\t\t\t<product name="Гранатовый сок" protein="0.3" fat="0" carbohydrate="14.5" ccal="64" />\n' +
    //         '\t\t\t<product name="Грейпфрутовый нектар" protein="0.2" fat="0" carbohydrate="10.4" ccal="44" />\n' +
    //         '\t\t\t<product name="Грейпфрутовый сок" protein="0.9" fat="0.2" carbohydrate="6.5" ccal="30" />\n' +
    //         '\t\t\t<product name="Грушевый компот" protein="0.2" fat="0" carbohydrate="18.2" ccal="70" />\n' +
    //         '\t\t\t<product name="Грушевый нектар" protein="0.1" fat="0.1" carbohydrate="8.8" ccal="37" />\n' +
    //         '\t\t\t<product name="Какао на молоке" protein="24.2" fat="17.5" carbohydrate="31.9" ccal="374" />\n' +
    //         '\t\t\t<product name="Квас хлебный" protein="0.2" fat="0" carbohydrate="5.2" ccal="27" />\n' +
    //         '\t\t\t<product name="Кисель из вишневого сока" protein="0.2" fat="0" carbohydrate="18.9" ccal="78" />\n' +
    //         '\t\t\t<product name="Кисель из клюквы" protein="0" fat="0" carbohydrate="13" ccal="53" />\n' +
    //         '\t\t\t<product name="Кисель из кураги" protein="0.4" fat="0" carbohydrate="12.9" ccal="54" />\n' +
    //         '\t\t\t<product name="Кисель из сливового варенья" protein="0.1" fat="0" carbohydrate="15.5" ccal="63" />\n' +
    //         '\t\t\t<product name="Кисель из сушеных яблок" protein="0.1" fat="0" carbohydrate="16.3" ccal="66" />\n' +
    //         '\t\t\t<product name="Кисель из яблок" protein="0.1" fat="0.1" carbohydrate="23.7" ccal="97" />\n' +
    //         '\t\t\t<product name="Кола" protein="0" fat="0" carbohydrate="10.4" ccal="42" />\n' +
    //         '\t\t\t<product name="Кофе растворимый сухой" protein="15" fat="3.5" carbohydrate="0" ccal="94" />\n' +
    //         '                        <product name="Кофе молотый" protein="13.9" fat="14.4" carbohydrate="4.1" ccal="201" />\n' +
    //         '\t\t\t<product name="Кофе с молоком" protein="0.7" fat="1" carbohydrate="11.2" ccal="58" />\n' +
    //         '\t\t\t<product name="Кофейный напиток" protein="6" fat="4.8" carbohydrate="71.2" ccal="337" />\n' +
    //         '\t\t\t<product name="Лимонад" protein="0" fat="0" carbohydrate="6.4" ccal="26" />\n' +
    //         '\t\t\t<product name="Лимонад диетический" protein="0" fat="0" carbohydrate="0" ccal="2" />\n' +
    //         '\t\t\t<product name="Лимонный сок" protein="0.9" fat="0.1" carbohydrate="3" ccal="16" />\n' +
    //         '\t\t\t<product name="Мандариновый компот" protein="0.1" fat="0" carbohydrate="18.1" ccal="69" />\n' +
    //         '\t\t\t<product name="Мандариновый сок" protein="0.8" fat="0.3" carbohydrate="8.1" ccal="36" />\n' +
    //         '\t\t\t<product name="Морковный сок" protein="1.1" fat="0.1" carbohydrate="6.4" ccal="28" />\n' +
    //         '\t\t\t<product name="Морс брусничный" protein="0.1" fat="0" carbohydrate="10.9" ccal="41" />\n' +
    //         '\t\t\t<product name="Нектар из маракуйи" protein="0.2" fat="0" carbohydrate="9.8" ccal="41" />\n' +
    //         '\t\t\t<product name="Нектариновый нектар" protein="0.1" fat="0" carbohydrate="12.8" ccal="53" />\n' +
    //         '\t\t\t<product name="Нектариновый сок" protein="0.4" fat="0" carbohydrate="8.6" ccal="37" />\n' +
    //         '\t\t\t<product name="Облепиховый сок" protein="0.6" fat="3.4" carbohydrate="4.3" ccal="52" />\n' +
    //         '\t\t\t<product name="Персиковый компот" protein="0.5" fat="0" carbohydrate="19.9" ccal="78" />\n' +
    //         '\t\t\t<product name="Персиковый нектар" protein="0.2" fat="0" carbohydrate="9" ccal="38" />\n' +
    //         '\t\t\t<product name="Персиковый сок" protein="0.9" fat="0.1" carbohydrate="9.5" ccal="40" />\n' +
    //         '\t\t\t<product name="Пиво Безалкогольное" protein="0" fat="0" carbohydrate="4.7" ccal="26" />\n' +
    //         '\t\t\t<product name="Свекольный сок" protein="1" fat="0" carbohydrate="9.9" ccal="42" />\n' +
    //         '\t\t\t<product name="Сливовый компот" protein="0.5" fat="0" carbohydrate="23.9" ccal="96" />\n' +
    //         '\t\t\t<product name="Сливовый нектар" protein="0.1" fat="0" carbohydrate="11" ccal="46" />\n' +
    //         '\t\t\t<product name="Сливовый сок" protein="0.8" fat="0" carbohydrate="9.6" ccal="39" />\n' +
    //         '\t\t\t<product name="Томатный сок" protein="1.1" fat="0.2" carbohydrate="3.8" ccal="21" />\n' +
    //         '\t\t\t<product name="Цикорий растворимый" protein="0.1" fat="0" carbohydrate="2.8" ccal="11" />\n' +
    //         '                        <product name="Чай зеленый" protein="0" fat="0" carbohydrate="0" ccal="2" />\n' +
    //         '\t\t\t<product name="Чай чёрный байховый сухой" protein="20" fat="5.1" carbohydrate="6.9" ccal="152" />\n' +
    //         '\t\t\t<product name="Чай чёрный с лимоном" protein="0.7" fat="0.8" carbohydrate="8.2" ccal="43" />\n' +
    //         '\t\t\t<product name="Чай чёрный с молоком" protein="1.2" fat="1.7" carbohydrate="6.7" ccal="46" />\n' +
    //         '\t\t\t<product name="Черешневый компот" protein="0.5" fat="0" carbohydrate="19.9" ccal="78" />\n' +
    //         '\t\t\t<product name="Черничный сок" protein="0" fat="1" carbohydrate="8" ccal="38" />\n' +
    //         '\t\t\t<product name="Черноплодно-рябиновый сок" protein="0.1" fat="0" carbohydrate="7.4" ccal="32" />\n' +
    //         '\t\t\t<product name="Черносмородиновый компот" protein="0.3" fat="0.1" carbohydrate="13.9" ccal="58" />\n' +
    //         '\t\t\t<product name="Черносмородиновый сок" protein="0.5" fat="0" carbohydrate="7.9" ccal="40" />\n' +
    //         '\t\t\t<product name="Шиповниковый сок" protein="0.1" fat="0" carbohydrate="17.6" ccal="70" />\n' +
    //         '\t\t\t<product name="Энергетический напиток" protein="0" fat="0" carbohydrate="11.6" ccal="49" />\n' +
    //         '\t\t\t<product name="Яблочный компот" protein="0.2" fat="0" carbohydrate="22.1" ccal="85" />\n' +
    //         '\t\t\t<product name="Яблочный нектар" protein="0.1" fat="0" carbohydrate="10" ccal="41" />\n' +
    //         '\t\t\t<product name="Яблочный сок" protein="0.4" fat="0.4" carbohydrate="9.8" ccal="42" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Грибы">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Белые жареные" protein="4.6" fat="11.5" carbohydrate="10.7" ccal="162" />\n' +
    //         '\t\t\t<product name="Белые маринованные" protein="3" fat="0.5" carbohydrate="2" ccal="24" />\n' +
    //         '\t\t\t<product name="Белые свежие" protein="3.7" fat="1.7" carbohydrate="3.4" ccal="30" />\n' +
    //         '\t\t\t<product name="Белые сушёные" protein="23.4" fat="6.4" carbohydrate="31" ccal="282" />\n' +
    //         '\t\t\t<product name="Вешенки свежие" protein="2.5" fat="0.3" carbohydrate="6.5" ccal="38" />\n' +
    //         '\t\t\t<product name="Грузди свежие" protein="1.8" fat="0.5" carbohydrate="0.8" ccal="16" />\n' +
    //         '\t\t\t<product name="Лисички свежие" protein="1.6" fat="1.1" carbohydrate="2.2" ccal="20" />\n' +
    //         '\t\t\t<product name="Лисички сушёные" protein="22.3" fat="7.6" carbohydrate="24.2" ccal="261" />\n' +
    //         '\t\t\t<product name="Маслята свежие" protein="2.4" fat="0.7" carbohydrate="1.7" ccal="9" />\n' +
    //         '\t\t\t<product name="Опята свежие" protein="2.2" fat="1.2" carbohydrate="2.8" ccal="17" />\n' +
    //         '\t\t\t<product name="Подберёзовики свежие" protein="2.3" fat="0.9" carbohydrate="3.7" ccal="31" />\n' +
    //         '\t\t\t<product name="Подберёзовики сушёные" protein="23.5" fat="9.2" carbohydrate="14.3" ccal="231" />\n' +
    //         '\t\t\t<product name="Подосиновики свежие" protein="3.3" fat="0.5" carbohydrate="3.7" ccal="22" />\n' +
    //         '\t\t\t<product name="Подосиновики сушёные" protein="35.4" fat="5.4" carbohydrate="33.2" ccal="315" />\n' +
    //         '\t\t\t<product name="Рыжики свежие" protein="1.9" fat="0.8" carbohydrate="2.7" ccal="17" />\n' +
    //         '\t\t\t<product name="Сморчки свежие" protein="1.7" fat="0.3" carbohydrate="4.2" ccal="27" />\n' +
    //         '\t\t\t<product name="Сыроежки свежие" protein="1.7" fat="0.7" carbohydrate="1.5" ccal="15" />\n' +
    //         '\t\t\t<product name="Трюфели свежие" protein="5.9" fat="0.5" carbohydrate="5.3" ccal="51" />\n' +
    //         '\t\t\t<product name="Шампиньоны консервированные" protein="1.6" fat="0.2" carbohydrate="0.9" ccal="12" />\n' +
    //         '\t\t\t<product name="Шампиньоны свежие" protein="4.3" fat="1" carbohydrate="1" ccal="27" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Колбасные изделия">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Колбаса вареная диетическая" protein="12.1" fat="13.5" carbohydrate="0" ccal="170" />\n' +
    //         '\t\t\t<product name="Колбаса вареная докторская" protein="12.8" fat="22.2" carbohydrate="1.5" ccal="257" />\n' +
    //         '\t\t\t<product name="Колбаса вареная любительская" protein="12.2" fat="28" carbohydrate="0" ccal="301" />\n' +
    //         '\t\t\t<product name="Колбаса вареная молочная" protein="11.7" fat="22.8" carbohydrate="0" ccal="259" />\n' +
    //         '\t\t\t<product name="Колбаса вареная московская" protein="11.5" fat="21.8" carbohydrate="2" ccal="250" />\n' +
    //         '\t\t\t<product name="Колбаса вареная русская" protein="11.8" fat="28.9" carbohydrate="0" ccal="302" />\n' +
    //         '\t\t\t<product name="Колбаса вареная свиная" protein="10.2" fat="25.1" carbohydrate="1.9" ccal="274" />\n' +
    //         '\t\t\t<product name="Колбаса вареная столичная" protein="15.1" fat="28.7" carbohydrate="0" ccal="319" />\n' +
    //         '\t\t\t<product name="Колбаса вареная столовая" protein="11.1" fat="20.2" carbohydrate="1.9" ccal="234" />\n' +
    //         '\t\t\t<product name="Колбаса вареная чайная" protein="11.7" fat="18.4" carbohydrate="1.9" ccal="216" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая закусочная" protein="15" fat="33" carbohydrate="2.3" ccal="366" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая краковская" protein="16.2" fat="44.6" carbohydrate="0" ccal="466" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая любительская" protein="17.3" fat="39" carbohydrate="0" ccal="420" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая минская" protein="17.4" fat="23" carbohydrate="2.7" ccal="287" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая московская" protein="19.1" fat="36.6" carbohydrate="0" ccal="406" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая одесская" protein="14.8" fat="38.1" carbohydrate="0" ccal="402" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая полтавская" protein="16.4" fat="39" carbohydrate="0" ccal="417" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая сервелат" protein="16.1" fat="40.1" carbohydrate="0" ccal="425" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая таллиннская" protein="17.1" fat="33.8" carbohydrate="0" ccal="373" />\n' +
    //         '\t\t\t<product name="Колбаса полукопченая украинская" protein="16.5" fat="34.4" carbohydrate="0" ccal="376" />\n' +
    //         '\t\t\t<product name="Колбаса сырокопченая зернистая" protein="9.9" fat="63.2" carbohydrate="0" ccal="608" />\n' +
    //         '\t\t\t<product name="Колбаса сырокопченая любительская" protein="20.9" fat="47.8" carbohydrate="0" ccal="514" />\n' +
    //         '\t\t\t<product name="Колбаса сырокопченая московская" protein="24.8" fat="41.5" carbohydrate="0" ccal="473" />\n' +
    //         '\t\t\t<product name="Колбаса сырокопченая свиная" protein="13" fat="57.3" carbohydrate="0" ccal="568" />\n' +
    //         '\t\t\t<product name="Колбаса сырокопченая сервелат" protein="24" fat="40.5" carbohydrate="0" ccal="461" />\n' +
    //         '\t\t\t<product name="Колбаса сырокопченая столичная" protein="24" fat="43.4" carbohydrate="0" ccal="487" />\n' +
    //         '\t\t\t<product name="Колбаски охотничьи" protein="27.4" fat="24.3" carbohydrate="0" ccal="326" />\n' +
    //         '\t\t\t<product name="Кровянка" protein="9" fat="19.5" carbohydrate="14.5" ccal="274" />\n' +
    //         '\t\t\t<product name="Салями" protein="21.6" fat="53.7" carbohydrate="1.4" ccal="568" />\n' +
    //         '\t\t\t<product name="Сардельки говяжьи" protein="11.4" fat="18.2" carbohydrate="1.5" ccal="215" />\n' +
    //         '\t\t\t<product name="Сардельки свиные" protein="10.1" fat="31.6" carbohydrate="1.9" ccal="332" />\n' +
    //         '\t\t\t<product name="Сосиски говяжьи" protein="10.4" fat="20.1" carbohydrate="0.8" ccal="226" />\n' +
    //         '\t\t\t<product name="Сосиски куриные" protein="10.8" fat="22.4" carbohydrate="4.2" ccal="259" />\n' +
    //         '\t\t\t<product name="Сосиски любительские" protein="9" fat="29.5" carbohydrate="0.7" ccal="304" />\n' +
    //         '\t\t\t<product name="Сосиски молочные" protein="11" fat="23.9" carbohydrate="1.6" ccal="266" />\n' +
    //         '\t\t\t<product name="Сосиски особые" protein="11.8" fat="24.7" carbohydrate="0" ccal="270" />\n' +
    //         '\t\t\t<product name="Сосиски русские" protein="11.3" fat="22" carbohydrate="0" ccal="243" />\n' +
    //         '\t\t\t<product name="Сосиски свиные" protein="9.5" fat="3" carbohydrate="4.3" ccal="342" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Крупы, каши">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Гречневая каша" protein="4.5" fat="2.3" carbohydrate="25" ccal="132" />\n' +
    //         '\t\t\t<product name="Гречневая каша вязкая на воде" protein="3.2" fat="0.8" carbohydrate="17.1" ccal="90" />\n' +
    //         '\t\t\t<product name="Гречневая каша из крупы ядрица" protein="3" fat="3.4" carbohydrate="14.6" ccal="101" />\n' +
    //         '\t\t\t<product name="Кукурузная крупа" protein="8.3" fat="1.2" carbohydrate="75" ccal="337" />\n' +
    //         '\t\t\t<product name="Кукурузные хлопья" protein="6.9" fat="2.5" carbohydrate="83.6" ccal="363" />\n' +
    //         '                        <product name="Кус-кус приготовленный" protein="3.7" fat="0.1" carbohydrate="22.5" ccal="110" />\n' +
    //         '                        <product name="Макаронные изделия" protein="11" fat="0.9" carbohydrate="74.2" ccal="356" />\n' +
    //         '\t\t\t<product name="Манная каша на воде" protein="2.5" fat="0.2" carbohydrate="16.8" ccal="80" />\n' +
    //         '\t\t\t<product name="Манная каша на молоке" protein="3" fat="3.2" carbohydrate="15.3" ccal="98" />\n' +
    //         '\t\t\t<product name="Манная крупа" protein="10.3" fat="1" carbohydrate="73.3" ccal="328" />\n' +
    //         '                        <product name="Мюсли с сухофруктами" protein="8.4" fat="3.4" carbohydrate="72.2" ccal="325" />\n' +
    //         '                        <product name="Мюсли с орехами" protein="11.5" fat="12.7" carbohydrate="62.9" ccal="375" />\n' +
    //         '                        <product name="Мука пшеничная" protein="11.2" fat="1.5" carbohydrate="70.4" ccal="341" />\n' +
    //         '                        <product name="Мука ржаная" protein="7.7" fat="1.2" carbohydrate="64.3" ccal="299" />\n' +
    //         '\t\t\t<product name="Овсяная каша на воде" protein="3" fat="1.7" carbohydrate="15" ccal="88" />\n' +
    //         '\t\t\t<product name="Овсяная каша на молоке" protein="3.2" fat="4.1" carbohydrate="14.2" ccal="102" />\n' +
    //         '\t\t\t<product name="Овсяная крупа" protein="11" fat="6.1" carbohydrate="65.4" ccal="303" />\n' +
    //         '\t\t\t<product name="Овсяные хлопья" protein="11.9" fat="7.2" carbohydrate="69.3" ccal="366" />\n' +
    //         '                        <product name="Отруби овсяные" protein="17.3" fat="7.0" carbohydrate="66.2" ccal="246" />\n' +
    //         '                        <product name="Отруби пшеничные" protein="15.1" fat="3.8" carbohydrate="53.6" ccal="296" />\n' +
    //         '                        <product name="Отруби ржаные" protein="11.2" fat="3.2" carbohydrate="32.0" ccal="221" />\n' +
    //         '\t\t\t<product name="Перловая каша рассыпчатая" protein="3.1" fat="0.4" carbohydrate="23" ccal="106" />\n' +
    //         '\t\t\t<product name="Перловая крупа" protein="9.3" fat="1.1" carbohydrate="73.7" ccal="320" />\n' +
    //         '\t\t\t<product name="Пшенная каша вязкая на воде" protein="3" fat="0.7" carbohydrate="17" ccal="90" />\n' +
    //         '\t\t\t<product name="Пшенная каша рассыпчатая" protein="4.7" fat="1.1" carbohydrate="26.1" ccal="135" />\n' +
    //         '\t\t\t<product name="Пшенная крупа" protein="11.5" fat="3.3" carbohydrate="69.3" ccal="348" />\n' +
    //         '\t\t\t<product name="Ржаные хлопья" protein="6.4" fat="3.2" carbohydrate="82.6" ccal="343" />\n' +
    //         '\t\t\t<product name="Рис белый" protein="6.7" fat="0.7" carbohydrate="78.9" ccal="344" />\n' +
    //         '\t\t\t<product name="Рис коричневый" protein="6.3" fat="4.4" carbohydrate="65.1" ccal="331" />\n' +
    //         '\t\t\t<product name="Рис полированный" protein="7" fat="1" carbohydrate="71.4" ccal="330" />\n' +
    //         '\t\t\t<product name="Рис рассыпчатый" protein="2.4" fat="0.2" carbohydrate="24.9" ccal="113" />\n' +
    //         '\t\t\t<product name="Рисовая каша на воде" protein="1.5" fat="0.1" carbohydrate="17.4" ccal="78" />\n' +
    //         '\t\t\t<product name="Рисовая каша на молоке" protein="2.5" fat="16" carbohydrate="3.1" ccal="97" />\n' +
    //         '\t\t\t<product name="Ячменная каша" protein="11.5" fat="2" carbohydrate="65.8" ccal="310" />\n' +
    //         '\t\t\t<product name="Ячменная крупа" protein="10" fat="1.3" carbohydrate="71.7" ccal="324" />\n' +
    //         '\t\t\t<product name="Ячменные хлопья" protein="9.8" fat="3.6" carbohydrate="79.4" ccal="355" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Масла, жиры">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Жир бараний топлёный" protein="0" fat="99.7" carbohydrate="0" ccal="897" />\n' +
    //         '\t\t\t<product name="Жир говяжий топлёный" protein="0" fat="99.7" carbohydrate="0" ccal="897" />\n' +
    //         '\t\t\t<product name="Жир из печени трески" protein="0" fat="99.8" carbohydrate="0" ccal="898" />\n' +
    //         '\t\t\t<product name="Жир кондитерский для шоколадных изделий" protein="0" fat="99.7" carbohydrate="0" ccal="897" />\n' +
    //         '\t\t\t<product name="Жир кондитерский твердый" protein="0" fat="99.8" carbohydrate="0" ccal="898" />\n' +
    //         '\t\t\t<product name="Жир костный топлёный" protein="0" fat="99.7" carbohydrate="0" ccal="897" />\n' +
    //         '\t\t\t<product name="Жир кулинарный" protein="0" fat="99.7" carbohydrate="0" ccal="897" />\n' +
    //         '\t\t\t<product name="Жир куриный" protein="0" fat="99.7" carbohydrate="0" ccal="897" />\n' +
    //         '\t\t\t<product name="Жир свиной топлёный" protein="0" fat="99.5" carbohydrate="0" ccal="880" />\n' +
    //         '\t\t\t<product name="Маргарин низкокалорийный" protein="0.5" fat="60" carbohydrate="0.7" ccal="545" />\n' +
    //         '\t\t\t<product name="Маргарин Славянский" protein="0.3" fat="82" carbohydrate="0.1" ccal="743" />\n' +
    //         '\t\t\t<product name="Маргарин сливочный" protein="0.5" fat="82" carbohydrate="0" ccal="745" />\n' +
    //         '\t\t\t<product name="Маргарин столовый молочный" protein="0.3" fat="82" carbohydrate="1" ccal="743" />\n' +
    //         '\t\t\t<product name="Маргарин Экстра" protein="0.5" fat="82" carbohydrate="1" ccal="744" />\n' +
    //         '\t\t\t<product name="Масло абрикосовое" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло арахисовое" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло горчичное" protein="0" fat="99.8" carbohydrate="0" ccal="898" />\n' +
    //         '\t\t\t<product name="Масло какао" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло кокосовое" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло конопляное" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло крестьянское несолёное" protein="0.5" fat="82.5" carbohydrate="0.8" ccal="748" />\n' +
    //         '\t\t\t<product name="Масло крестьянское солёное" protein="0.5" fat="82.5" carbohydrate="0.8" ccal="748" />\n' +
    //         '\t\t\t<product name="Масло кукурузное" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло кунжутное" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло льняное" protein="0" fat="99.8" carbohydrate="0" ccal="898" />\n' +
    //         '\t\t\t<product name="Масло маковое" protein="0" fat="99.8" carbohydrate="0" ccal="898" />\n' +
    //         '\t\t\t<product name="Масло оливковое" protein="0" fat="99.8" carbohydrate="0" ccal="898" />\n' +
    //         '\t\t\t<product name="Масло пальмовое" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло подсолнечное" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t\t<product name="Масло рапсово-соевое" protein="0" fat="100" carbohydrate="0" ccal="884" />\n' +
    //         '\t\t\t<product name="Масло рапсовое" protein="0" fat="100" carbohydrate="0" ccal="884" />\n' +
    //         '\t\t\t<product name="Масло сливочное" protein="0.5" fat="82.5" carbohydrate="0.8" ccal="748" />\n' +
    //         '\t\t\t<product name="Масло соевое" protein="0" fat="99.9" carbohydrate="0" ccal="899" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Молочные продукты">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Ацидофилин 0.1%" protein="3" fat="0.1" carbohydrate="3.9" ccal="31" />\n' +
    //         '\t\t\t<product name="Ацидофилин 1%" protein="3" fat="1" carbohydrate="4" ccal="40" />\n' +
    //         '\t\t\t<product name="Ацидофилин 3.2%" protein="2.8" fat="3.2" carbohydrate="3.8" ccal="57" />\n' +
    //         '\t\t\t<product name="Ацидофилин 3.2% сладкий" protein="2.8" fat="3.2" carbohydrate="8.6" ccal="77" />\n' +
    //         '\t\t\t<product name="Запеканка творожная" protein="17.6" fat="4.2" carbohydrate="14.2" ccal="168" />\n' +
    //         '                        <product name="Йогурт натуральный 0,1%" protein="5.0" fat="0.1" carbohydrate="3.5" ccal="38" />\n' +
    //         '                        <product name="Йогурт натуральный 0,5%" protein="5.0" fat="0.5" carbohydrate="3.5" ccal="42" />\n' +
    //         '\t\t\t<product name="Йогурт натуральный 2%" protein="4.3" fat="2" carbohydrate="6.2" ccal="60" />\n' +
    //         '\t\t\t<product name="Йогурт натуральный 3.2%" protein="5" fat="3.2" carbohydrate="3.5" ccal="66" />\n' +
    //         '\t\t\t<product name="Йогурт фруктовый 1.5%" protein="3.7" fat="1.5" carbohydrate="8.9" ccal="63" />\n' +
    //         '\t\t\t<product name="Йогурт фруктовый 3.2%" protein="5" fat="3.2" carbohydrate="8.5" ccal="85" />\n' +
    //         '\t\t\t<product name="Кефир 0%" protein="3" fat="0" carbohydrate="3.8" ccal="30" />\n' +
    //         '\t\t\t<product name="Кефир 1%" protein="2.8" fat="1" carbohydrate="4" ccal="40" />\n' +
    //         '\t\t\t<product name="Кефир 2%" protein="3.4" fat="2" carbohydrate="4.7" ccal="51" />\n' +
    //         '\t\t\t<product name="Кефир 2.5%" protein="2.8" fat="2.5" carbohydrate="3.9" ccal="50" />\n' +
    //         '\t\t\t<product name="Кефир 3.2%" protein="2.8" fat="3.2" carbohydrate="4.1" ccal="56" />\n' +
    //         '\t\t\t<product name="Кумыс из кобыльего молока" protein="2.1" fat="1.9" carbohydrate="5" ccal="50" />\n' +
    //         '\t\t\t<product name="Кумыс из коровьего молока" protein="3" fat="0.1" carbohydrate="6.3" ccal="41" />\n' +
    //         '\t\t\t<product name="Молоко 0.5%" protein="2.8" fat="0.5" carbohydrate="4.9" ccal="35" />\n' +
    //         '\t\t\t<product name="Молоко 0.7%" protein="3.3" fat="0.7" carbohydrate="4.7" ccal="38" />\n' +
    //         '\t\t\t<product name="Молоко 1.5%" protein="2.8" fat="1.5" carbohydrate="4.7" ccal="44" />\n' +
    //         '\t\t\t<product name="Молоко 2.5%" protein="2.8" fat="2.5" carbohydrate="4.7" ccal="52" />\n' +
    //         '\t\t\t<product name="Молоко 3.2%" protein="2.9" fat="3.2" carbohydrate="4.7" ccal="59" />\n' +
    //         '\t\t\t<product name="Молоко козье сырое" protein="3" fat="4.2" carbohydrate="4.5" ccal="68" />\n' +
    //         '\t\t\t<product name="Молоко коровье пастеризованное" protein="2.8" fat="3.2" carbohydrate="4.7" ccal="58" />\n' +
    //         '\t\t\t<product name="Молоко коровье сырое" protein="3.2" fat="3.6" carbohydrate="4.8" ccal="64" />\n' +
    //         '\t\t\t<product name="Молоко обезжиренное" protein="2" fat="0.1" carbohydrate="4.8" ccal="31" />\n' +
    //         '\t\t\t<product name="Молоко сгущеное без сахара" protein="6.6" fat="7.5" carbohydrate="9.4" ccal="131" />\n' +
    //         '\t\t\t<product name="Молоко сгущеное с сахаром" protein="7.2" fat="8.5" carbohydrate="56" ccal="320" />\n' +
    //         '\t\t\t<product name="Молоко сухое цельное" protein="26" fat="25" carbohydrate="37.5" ccal="476" />\n' +
    //         '\t\t\t<product name="Молоко топленое" protein="3" fat="6" carbohydrate="4.7" ccal="84" />\n' +
    //         '\t\t\t<product name="Пахта 0.5%" protein="3.4" fat="0.5" carbohydrate="4.7" ccal="37" />\n' +
    //         '\t\t\t<product name="Пахта 1%" protein="3.3" fat="1" carbohydrate="4.7" ccal="40" />\n' +
    //         '\t\t\t<product name="Простокваша 0.1%" protein="3" fat="0.1" carbohydrate="3.8" ccal="30" />\n' +
    //         '\t\t\t<product name="Простокваша 1%" protein="3" fat="1" carbohydrate="4.1" ccal="40" />\n' +
    //         '\t\t\t<product name="Простокваша 2.5%" protein="2.9" fat="2.5" carbohydrate="4.1" ccal="53" />\n' +
    //         '\t\t\t<product name="Простокваша 3.2%" protein="2.8" fat="3.2" carbohydrate="4.1" ccal="58" />\n' +
    //         '\t\t\t<product name="Ряженка 1%" protein="3" fat="1" carbohydrate="4.2" ccal="40" />\n' +
    //         '\t\t\t<product name="Ряженка 2.5%" protein="2.9" fat="2.5" carbohydrate="4.2" ccal="54" />\n' +
    //         '\t\t\t<product name="Ряженка 4%" protein="2.8" fat="4" carbohydrate="4.2" ccal="67" />\n' +
    //         '\t\t\t<product name="Ряженка 6%" protein="5" fat="6" carbohydrate="4.1" ccal="84" />\n' +
    //         '\t\t\t<product name="Сливки 10% жирности" protein="3" fat="10" carbohydrate="4" ccal="118" />\n' +
    //         '\t\t\t<product name="Сливки 20% жирности" protein="2.8" fat="20" carbohydrate="3.7" ccal="205" />\n' +
    //         '\t\t\t<product name="Сливки 30% жирности" protein="2.2" fat="30" carbohydrate="3.1" ccal="287" />\n' +
    //         '\t\t\t<product name="Сливки 35% жирности" protein="2.5" fat="35" carbohydrate="3" ccal="337" />\n' +
    //         '\t\t\t<product name="Сливки 9% жирности" protein="2.8" fat="9" carbohydrate="4" ccal="107" />\n' +
    //         '\t\t\t<product name="Сливки сухие" protein="23" fat="42.7" carbohydrate="26.3" ccal="579" />\n' +
    //         '\t\t\t<product name="Сливки сухие высокожирные" protein="10" fat="75" carbohydrate="10" ccal="753" />\n' +
    //         '\t\t\t<product name="Сметана 10% жирности" protein="3" fat="10" carbohydrate="2.9" ccal="115" />\n' +
    //         '\t\t\t<product name="Сметана 12% жирности" protein="2.7" fat="12" carbohydrate="3.9" ccal="133" />\n' +
    //         '\t\t\t<product name="Сметана 15% жирности" protein="2.6" fat="15" carbohydrate="3" ccal="158" />\n' +
    //         '\t\t\t<product name="Сметана 18% жирности" protein="2.5" fat="18" carbohydrate="3.6" ccal="184" />\n' +
    //         '\t\t\t<product name="Сметана 20% жирности" protein="2.8" fat="20" carbohydrate="3.2" ccal="206" />\n' +
    //         '\t\t\t<product name="Сметана 25% жирности" protein="2.6" fat="25" carbohydrate="2.5" ccal="248" />\n' +
    //         '\t\t\t<product name="Сметана 30% жирности" protein="2.4" fat="30" carbohydrate="3.1" ccal="294" />\n' +
    //         '\t\t\t<product name="Сметана 40% жирности" protein="2.4" fat="40" carbohydrate="2.6" ccal="381" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Мясные продукты">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Антрекот" protein="27.3" fat="31.2" carbohydrate="1.7" ccal="396" />\n' +
    //         '\t\t\t<product name="Баранина (лопатка)" protein="15.6" fat="25" carbohydrate="0" ccal="284" />\n' +
    //         '\t\t\t<product name="Баранина (окорок)" protein="18" fat="18" carbohydrate="0" ccal="232" />\n' +
    //         '\t\t\t<product name="Баранина (печень)" protein="18.7" fat="2.9" carbohydrate="0" ccal="101" />\n' +
    //         '\t\t\t<product name="Баранина (почки)" protein="13.6" fat="2.5" carbohydrate="0" ccal="77" />\n' +
    //         '\t\t\t<product name="Баранина (сердце)" protein="13.5" fat="2.5" carbohydrate="0" ccal="82" />\n' +
    //         '\t\t\t<product name="Баранина (язык)" protein="12.6" fat="16.1" carbohydrate="0" ccal="195" />\n' +
    //         '\t\t\t<product name="Баранина вареная" protein="24.6" fat="21.4" carbohydrate="0" ccal="291" />\n' +
    //         '\t\t\t<product name="Баранина жареная" protein="20" fat="24" carbohydrate="0" ccal="320" />\n' +
    //         '\t\t\t<product name="Баранина тушеная" protein="20" fat="20.9" carbohydrate="0" ccal="268" />\n' +
    //         '\t\t\t<product name="Бекон" protein="23" fat="45" carbohydrate="0" ccal="500" />\n' +
    //         '\t\t\t<product name="Бефстроганов" protein="21.9" fat="27.4" carbohydrate="5.7" ccal="355" />\n' +
    //         '\t\t\t<product name="Бифштекс" protein="27.8" fat="29.6" carbohydrate="1.7" ccal="384" />\n' +
    //         '\t\t\t<product name="Буженина вареная" protein="16.4" fat="18.3" carbohydrate="1" ccal="233" />\n' +
    //         '\t\t\t<product name="Ветчина" protein="14" fat="24" carbohydrate="0" ccal="270" />\n' +
    //         '\t\t\t<product name="Ветчина в форме" protein="22.6" fat="20.9" carbohydrate="0" ccal="278" />\n' +
    //         '\t\t\t<product name="Глухарь" protein="18" fat="20" carbohydrate="0.5" ccal="254" />\n' +
    //         '\t\t\t<product name="Говядина" protein="18.9" fat="12.4" carbohydrate="0" ccal="187" />\n' +
    //         '\t\t\t<product name="Говядина (вымя)" protein="12.3" fat="13.7" carbohydrate="0" ccal="173" />\n' +
    //         '\t\t\t<product name="Говядина (грудинка копчёная)" protein="7.6" fat="66.8" carbohydrate="0" ccal="632" />\n' +
    //         '\t\t\t<product name="Говядина (грудинка копчёно-варёная)" protein="10" fat="55" carbohydrate="0" ccal="540" />\n' +
    //         '\t\t\t<product name="Говядина (грудинка)" protein="19.3" fat="15.7" carbohydrate="0" ccal="217" />\n' +
    //         '\t\t\t<product name="Говядина (мозги)" protein="11.7" fat="8.6" carbohydrate="0" ccal="124" />\n' +
    //         '\t\t\t<product name="Говядина (печень)" protein="20" fat="3.1" carbohydrate="4" ccal="125" />\n' +
    //         '\t\t\t<product name="Говядина (почки)" protein="15.2" fat="2.8" carbohydrate="0" ccal="86" />\n' +
    //         '\t\t\t<product name="Говядина (сердце)" protein="16" fat="3.5" carbohydrate="0" ccal="96" />\n' +
    //         '\t\t\t<product name="Говядина (филейная вырезка)" protein="20.1" fat="3.5" carbohydrate="0" ccal="113" />\n' +
    //         '\t\t\t<product name="Говядина (язык)" protein="12.2" fat="10.9" carbohydrate="0" ccal="146" />\n' +
    //         '\t\t\t<product name="Говядина вареная" protein="25.8" fat="16.8" carbohydrate="0" ccal="254" />\n' +
    //         '\t\t\t<product name="Говядина жареная" protein="32.7" fat="28.1" carbohydrate="0" ccal="384" />\n' +
    //         '\t\t\t<product name="Говядина средней жирности" protein="25" fat="20" carbohydrate="0" ccal="275" />\n' +
    //         '\t\t\t<product name="Говядина тушеная" protein="16.8" fat="18.3" carbohydrate="0" ccal="232" />\n' +
    //         '\t\t\t<product name="Говяжий гуляш" protein="14" fat="9.2" carbohydrate="2.6" ccal="148" />\n' +
    //         '\t\t\t<product name="Гусь вареный" protein="19.3" fat="41.2" carbohydrate="0" ccal="447" />\n' +
    //         '\t\t\t<product name="Гусь жареный" protein="22.9" fat="58.8" carbohydrate="0" ccal="620" />\n' +
    //         '\t\t\t<product name="Индейка (грудка)" protein="19.2" fat="0.7" carbohydrate="0" ccal="84" />\n' +
    //         '\t\t\t<product name="Индейка (крылышки)" protein="16.5" fat="11.4" carbohydrate="0" ccal="168" />\n' +
    //         '\t\t\t<product name="Индейка (ножки)" protein="15.7" fat="8.9" carbohydrate="0" ccal="142" />\n' +
    //         '\t\t\t<product name="Индейка (окорочка)" protein="18.4" fat="6.4" carbohydrate="0" ccal="131" />\n' +
    //         '\t\t\t<product name="Индейка вареная" protein="25.3" fat="10.4" carbohydrate="0" ccal="195" />\n' +
    //         '\t\t\t<product name="Индейка жареная" protein="28" fat="6" carbohydrate="0" ccal="165" />\n' +
    //         '\t\t\t<product name="Котлеты из говядины" protein="18" fat="20" carbohydrate="0" ccal="260" />\n' +
    //         '\t\t\t<product name="Котлеты из телятины" protein="23" fat="31" carbohydrate="0" ccal="375" />\n' +
    //         '\t\t\t<product name="Кролик" protein="21" fat="8" carbohydrate="0" ccal="156" />\n' +
    //         '\t\t\t<product name="Кролик жареный" protein="25" fat="6" carbohydrate="0" ccal="155" />\n' +
    //         '\t\t\t<product name="Курица (грудка)" protein="21.5" fat="1.3" carbohydrate="0" ccal="99" />\n' +
    //         '\t\t\t<product name="Курица (желудки)" protein="18.2" fat="4.2" carbohydrate="0.6" ccal="114" />\n' +
    //         '\t\t\t<product name="Курица (крылышки)" protein="19.2" fat="12.2" carbohydrate="0" ccal="186" />\n' +
    //         '\t\t\t<product name="Курица (окорочка)" protein="16.8" fat="10.2" carbohydrate="0" ccal="158" />\n' +
    //         '\t\t\t<product name="Курица (печень)" protein="19.1" fat="6.3" carbohydrate="0.6" ccal="136" />\n' +
    //         '\t\t\t<product name="Курица (сердца)" protein="15.8" fat="10.3" carbohydrate="0.8" ccal="159" />\n' +
    //         '\t\t\t<product name="Курица (филе)" protein="23.1" fat="1.2" carbohydrate="0" ccal="110" />\n' +
    //         '\t\t\t<product name="Курица жареная" protein="26" fat="12" carbohydrate="0" ccal="210" />\n' +
    //         '\t\t\t<product name="Куропатка жареная" protein="29" fat="8" carbohydrate="0" ccal="250" />\n' +
    //         '\t\t\t<product name="Паштет мясной" protein="15" fat="11" carbohydrate="0" ccal="170" />\n' +
    //         '\t\t\t<product name="Перепелка" protein="18" fat="18.6" carbohydrate="0" ccal="239" />\n' +
    //         '\t\t\t<product name="Рябчик" protein="18" fat="20" carbohydrate="0.5" ccal="254" />\n' +
    //         '\t\t\t<product name="Сало" protein="2.4" fat="89" carbohydrate="0" ccal="797" />\n' +
    //         '\t\t\t<product name="Свиная рулька со шкурой" protein="18.6" fat="24.7" carbohydrate="0" ccal="294" />\n' +
    //         '\t\t\t<product name="Свинина (грудинка без кости)" protein="10.1" fat="53" carbohydrate="0" ccal="510" />\n' +
    //         '\t\t\t<product name="Свинина (грудинка с костью)" protein="21" fat="10" carbohydrate="0" ccal="174" />\n' +
    //         '\t\t\t<product name="Свинина (край на гриле)" protein="21" fat="35" carbohydrate="0" ccal="400" />\n' +
    //         '\t\t\t<product name="Свинина (лопатка)" protein="16" fat="21.7" carbohydrate="0" ccal="257" />\n' +
    //         '\t\t\t<product name="Свинина (нога жареная)" protein="27" fat="20" carbohydrate="0" ccal="290" />\n' +
    //         '\t\t\t<product name="Свинина (окорок)" protein="18" fat="21.3" carbohydrate="0" ccal="261" />\n' +
    //         '\t\t\t<product name="Свинина (отбивные на гриле)" protein="28" fat="24" carbohydrate="0" ccal="340" />\n' +
    //         '\t\t\t<product name="Свинина (ошеек)" protein="16.1" fat="22.8" carbohydrate="0" ccal="267" />\n' +
    //         '\t\t\t<product name="Свинина (печень)" protein="22" fat="3.4" carbohydrate="2.6" ccal="130" />\n' +
    //         '\t\t\t<product name="Свинина (подгрудок)" protein="7.4" fat="67.8" carbohydrate="0" ccal="630" />\n' +
    //         '\t\t\t<product name="Свинина (почки)" protein="16.8" fat="3.8" carbohydrate="0" ccal="102" />\n' +
    //         '\t\t\t<product name="Свинина (ребра)" protein="15.2" fat="29.3" carbohydrate="0" ccal="321" />\n' +
    //         '\t\t\t<product name="Свинина (сердце)" protein="16.9" fat="4.8" carbohydrate="0" ccal="165" />\n' +
    //         '\t\t\t<product name="Свинина (язык)" protein="16.5" fat="11.1" carbohydrate="0" ccal="165" />\n' +
    //         '\t\t\t<product name="Субпродукты тушеные" protein="24" fat="8" carbohydrate="0" ccal="185" />\n' +
    //         '\t\t\t<product name="Телятина (лопатка)" protein="19.9" fat="2.8" carbohydrate="0" ccal="106" />\n' +
    //         '\t\t\t<product name="Телятина (мякоть)" protein="20.5" fat="2.4" carbohydrate="0" ccal="105" />\n' +
    //         '\t\t\t<product name="Телятина (окорок)" protein="19.9" fat="3.1" carbohydrate="0" ccal="108" />\n' +
    //         '\t\t\t<product name="Телятина (печень)" protein="19.2" fat="3.3" carbohydrate="4.1" ccal="124" />\n' +
    //         '\t\t\t<product name="Тетерев" protein="18" fat="20" carbohydrate="0.5" ccal="254" />\n' +
    //         '\t\t\t<product name="Утка" protein="13.5" fat="28.6" carbohydrate="0" ccal="308" />\n' +
    //         '\t\t\t<product name="Утка вареная" protein="19.7" fat="18.8" carbohydrate="0" ccal="248" />\n' +
    //         '\t\t\t<product name="Утка жареная" protein="22.6" fat="19.5" carbohydrate="0" ccal="266" />\n' +
    //         '\t\t\t<product name="Фазан" protein="18" fat="20" carbohydrate="0.5" ccal="254" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Овощи зелень">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Баклажан" protein="1.2" fat="0.1" carbohydrate="7.1" ccal="24" />\n' +
    //         '\t\t\t<product name="Бобы" protein="6" fat="0.1" carbohydrate="8.5" ccal="57" />\n' +
    //         '\t\t\t<product name="Бобы зелёные" protein="6" fat="0.1" carbohydrate="10.5" ccal="60" />\n' +
    //         '\t\t\t<product name="Брюква" protein="1.2" fat="0.1" carbohydrate="8.9" ccal="34" />\n' +
    //         '\t\t\t<product name="Брюква запечённая" protein="2.1" fat="1.6" carbohydrate="7.5" ccal="51" />\n' +
    //         '\t\t\t<product name="Брюква тушеная" protein="1.2" fat="8.5" carbohydrate="8.6" ccal="114" />\n' +
    //         '\t\t\t<product name="Горох варёный" protein="6" fat="0" carbohydrate="9" ccal="60" />\n' +
    //         '\t\t\t<product name="Горох лущеный" protein="23" fat="1.6" carbohydrate="57.7" ccal="323" />\n' +
    //         '\t\t\t<product name="Горох сушёный" protein="20.5" fat="2" carbohydrate="53.3" ccal="298" />\n' +
    //         '\t\t\t<product name="Горошек зелёный" protein="5" fat="0.2" carbohydrate="13.8" ccal="73" />\n' +
    //         '\t\t\t<product name="Горошек зелёный консервированный" protein="3.6" fat="0.1" carbohydrate="9.8" ccal="55" />\n' +
    //         '\t\t\t<product name="Душица" protein="1.5" fat="0" carbohydrate="5" ccal="25" />\n' +
    //         '                        <product name="Имбирь (корень)" protein="1.8" fat="0.8" carbohydrate="15.8" ccal="80" />\n' +
    //         '\t\t\t<product name="Кабачок" protein="0.6" fat="0.3" carbohydrate="5.2" ccal="23" />\n' +
    //         '\t\t\t<product name="Капуста белокочанная" protein="1.8" fat="0.1" carbohydrate="6.8" ccal="27" />\n' +
    //         '\t\t\t<product name="Капуста брокколи" protein="3" fat="0.4" carbohydrate="5.2" ccal="28" />\n' +
    //         '\t\t\t<product name="Капуста брюссельская" protein="4.8" fat="0" carbohydrate="8" ccal="43" />\n' +
    //         '\t\t\t<product name="Капуста квашеная" protein="1.8" fat="0.1" carbohydrate="4.4" ccal="19" />\n' +
    //         '\t\t\t<product name="Капуста кольраби" protein="2.8" fat="0" carbohydrate="10.7" ccal="42" />\n' +
    //         '\t\t\t<product name="Капуста краснокочанная" protein="0.8" fat="0" carbohydrate="7.6" ccal="24" />\n' +
    //         '\t\t\t<product name="Капуста пекинская" protein="1.2" fat="0.2" carbohydrate="3.2" ccal="12" />\n' +
    //         '\t\t\t<product name="Капуста цветная" protein="2.5" fat="0.3" carbohydrate="5.4" ccal="30" />\n' +
    //         '\t\t\t<product name="Картофель" protein="2" fat="0.4" carbohydrate="18.1" ccal="80" />\n' +
    //         '\t\t\t<product name="Картофель варёный" protein="2" fat="0.4" carbohydrate="16.7" ccal="82" />\n' +
    //         '\t\t\t<product name="Картофель жареный" protein="2.8" fat="9.5" carbohydrate="23.4" ccal="192" />\n' +
    //         '\t\t\t<product name="Картофель молодой" protein="2.4" fat="0.4" carbohydrate="12.4" ccal="61" />\n' +
    //         '\t\t\t<product name="Картофель сладкий (батат)" protein="2" fat="0" carbohydrate="14.6" ccal="61" />\n' +
    //         '\t\t\t<product name="Картофель сушеный" protein="6.6" fat="0.3" carbohydrate="71.6" ccal="298" />\n' +
    //         '\t\t\t<product name="Кресс-салат" protein="2.3" fat="0.1" carbohydrate="1.3" ccal="11" />\n' +
    //         '                        <product name="Кукуруза вареная" protein="4.1" fat="2.3" carbohydrate="22.5" ccal="123" />\n' +
    //         '\t\t\t<product name="Кукуруза сладкая консервированная" protein="3.9" fat="1.3" carbohydrate="22.7" ccal="119" />\n' +
    //         '\t\t\t<product name="Лук зелёный" protein="1.3" fat="0" carbohydrate="4.6" ccal="19" />\n' +
    //         '\t\t\t<product name="Лук порей" protein="2" fat="0" carbohydrate="8.2" ccal="33" />\n' +
    //         '\t\t\t<product name="Лук репчатый" protein="1.4" fat="0" carbohydrate="10.4" ccal="41" />\n' +
    //         '\t\t\t<product name="Лук репчатый сушеный" protein="8.4" fat="2.8" carbohydrate="42.6" ccal="219" />\n' +
    //         '\t\t\t<product name="Маслины" protein="2.2" fat="10.5" carbohydrate="5.1" ccal="166" />\n' +
    //         '\t\t\t<product name="Морковь" protein="1.3" fat="0.1" carbohydrate="6.9" ccal="32" />\n' +
    //         '\t\t\t<product name="Морковь варёная" protein="0.8" fat="0.3" carbohydrate="5" ccal="25" />\n' +
    //         '\t\t\t<product name="Морковь жёлтая" protein="1.3" fat="0.1" carbohydrate="7.2" ccal="33" />\n' +
    //         '\t\t\t<product name="Морковь квашеная" protein="1.3" fat="0.1" carbohydrate="4.5" ccal="26" />\n' +
    //         '\t\t\t<product name="Морковь сушеная" protein="7.8" fat="0.6" carbohydrate="49.2" ccal="221" />\n' +
    //         '\t\t\t<product name="Огурец" protein="0.8" fat="0.1" carbohydrate="3" ccal="15" />\n' +
    //         '\t\t\t<product name="Огурец маринованный" protein="2.8" fat="0" carbohydrate="1.3" ccal="16" />\n' +
    //         '\t\t\t<product name="Огурец солёный" protein="0.8" fat="0.1" carbohydrate="1.7" ccal="11" />\n' +
    //         '\t\t\t<product name="Оливки" protein="0.8" fat="10.7" carbohydrate="6.3" ccal="115" />\n' +
    //         '\t\t\t<product name="Пастернак" protein="1.4" fat="0.5" carbohydrate="9.2" ccal="47" />\n' +
    //         '\t\t\t<product name="Патиссон" protein="0.6" fat="0.1" carbohydrate="4.3" ccal="19" />\n' +
    //         '\t\t\t<product name="Перец зелёный сладкий" protein="1.3" fat="0" carbohydrate="7.2" ccal="26" />\n' +
    //         '\t\t\t<product name="Перец красный сладкий" protein="1.3" fat="0" carbohydrate="5.7" ccal="27" />\n' +
    //         '\t\t\t<product name="Перец сладкий маринованный" protein="1.3" fat="0.1" carbohydrate="4.9" ccal="25" />\n' +
    //         '\t\t\t<product name="Петрушка" protein="3.7" fat="0.4" carbohydrate="7.6" ccal="47" />\n' +
    //         '\t\t\t<product name="Петрушка (корень)" protein="1.5" fat="0.6" carbohydrate="10.1" ccal="49" />\n' +
    //         '                        <product name="Помидор" protein="1.0" fat="0.2" carbohydrate="3.7" ccal="21" />\n' +
    //         '\t\t\t<product name="Ревень" protein="0.7" fat="0.1" carbohydrate="2.5" ccal="13" />\n' +
    //         '\t\t\t<product name="Редис" protein="1.2" fat="0.1" carbohydrate="3.4" ccal="19" />\n' +
    //         '\t\t\t<product name="Редька" protein="1.9" fat="0.2" carbohydrate="6.7" ccal="35" />\n' +
    //         '\t\t\t<product name="Репа" protein="1.5" fat="0.1" carbohydrate="6.2" ccal="30" />\n' +
    //         '\t\t\t<product name="Салат" protein="1.2" fat="0.3" carbohydrate="1.3" ccal="12" />\n' +
    //         '\t\t\t<product name="Свекла" protein="1.5" fat="0.1" carbohydrate="8.8" ccal="40" />\n' +
    //         '\t\t\t<product name="Свекла варёная" protein="1.8" fat="0" carbohydrate="10.8" ccal="49" />\n' +
    //         '\t\t\t<product name="Свекла квашеная" protein="1.3" fat="0" carbohydrate="6" ccal="32" />\n' +
    //         '\t\t\t<product name="Свекла сушеная" protein="9" fat="0.6" carbohydrate="56.6" ccal="254" />\n' +
    //         '\t\t\t<product name="Свекла тушеная" protein="2.7" fat="5.5" carbohydrate="12.2" ccal="106" />\n' +
    //         '\t\t\t<product name="Сельдерей" protein="0.9" fat="0.1" carbohydrate="2.1" ccal="12" />\n' +
    //         '\t\t\t<product name="Сельдерей (корень)" protein="1.3" fat="0.3" carbohydrate="6.5" ccal="32" />\n' +
    //         '\t\t\t<product name="Сельдерей (корень) сушеный" protein="7.8" fat="2" carbohydrate="36.6" ccal="186" />\n' +
    //         '\t\t\t<product name="Соя" protein="2" fat="0.1" carbohydrate="1" ccal="381" />\n' +
    //         '\t\t\t<product name="Соя (ростки)" protein="13.1" fat="6.7" carbohydrate="9.6" ccal="141" />\n' +
    //         '\t\t\t<product name="Соя (сухие семена)" protein="34.9" fat="17.3" carbohydrate="26.5" ccal="332" />\n' +
    //         '\t\t\t<product name="Спаржа" protein="1.9" fat="0.1" carbohydrate="3.1" ccal="20" />\n' +
    //         '\t\t\t<product name="Томат" protein="0.6" fat="0.2" carbohydrate="4.2" ccal="20" />\n' +
    //         '\t\t\t<product name="Томат маринованный" protein="1.7" fat="0.2" carbohydrate="1.8" ccal="15" />\n' +
    //         '\t\t\t<product name="Томат солёный" protein="1.1" fat="0.1" carbohydrate="1.6" ccal="13" />\n' +
    //         '\t\t\t<product name="Томат черри" protein="1.1" fat="2" carbohydrate="3.8" ccal="24" />\n' +
    //         '\t\t\t<product name="Топинамбур" protein="2.1" fat="0.1" carbohydrate="12.8" ccal="61" />\n' +
    //         '\t\t\t<product name="Турнепс" protein="1" fat="0" carbohydrate="6" ccal="28" />\n' +
    //         '\t\t\t<product name="Тыква" protein="1.3" fat="0.3" carbohydrate="7.7" ccal="28" />\n' +
    //         '\t\t\t<product name="Тыква жареная" protein="1.4" fat="5.5" carbohydrate="5.2" ccal="76" />\n' +
    //         '\t\t\t<product name="Укроп" protein="2.5" fat="0.5" carbohydrate="6.3" ccal="38" />\n' +
    //         '\t\t\t<product name="Фасоль белая" protein="7" fat="0.5" carbohydrate="16.9" ccal="102" />\n' +
    //         '\t\t\t<product name="Фасоль варёная" protein="7.8" fat="0.5" carbohydrate="21.5" ccal="123" />\n' +
    //         '\t\t\t<product name="Фасоль красная" protein="8.4" fat="0.3" carbohydrate="13.7" ccal="93" />\n' +
    //         '\t\t\t<product name="Фасоль стручковая" protein="2" fat="0.2" carbohydrate="3.6" ccal="24" />\n' +
    //         '\t\t\t<product name="Фасоль сухая" protein="21.1" fat="1.2" carbohydrate="41.4" ccal="265" />\n' +
    //         '\t\t\t<product name="Фенхель" protein="1.2" fat="0.2" carbohydrate="7.3" ccal="31" />\n' +
    //         '\t\t\t<product name="Хрен" protein="3.2" fat="0.4" carbohydrate="10.5" ccal="56" />\n' +
    //         '\t\t\t<product name="Цуккини" protein="1.5" fat="0.2" carbohydrate="3" ccal="16" />\n' +
    //         '\t\t\t<product name="Цуккини варёный" protein="0.8" fat="0.1" carbohydrate="2.5" ccal="13" />\n' +
    //         '\t\t\t<product name="Черемша" protein="2.4" fat="0.1" carbohydrate="6.5" ccal="35" />\n' +
    //         '\t\t\t<product name="Чеснок" protein="6.5" fat="0.5" carbohydrate="29.9" ccal="143" />\n' +
    //         '\t\t\t<product name="Чечевица (ростки)" protein="9" fat="0.6" carbohydrate="22.1" ccal="119" />\n' +
    //         '\t\t\t<product name="Чечевица варёная" protein="7.8" fat="0" carbohydrate="20.1" ccal="111" />\n' +
    //         '\t\t\t<product name="Чечевица сушёная" protein="24" fat="1.5" carbohydrate="42.7" ccal="284" />\n' +
    //         '\t\t\t<product name="Шпинат" protein="2.9" fat="0.3" carbohydrate="2" ccal="22" />\n' +
    //         '\t\t\t<product name="Щавель" protein="1.5" fat="0.3" carbohydrate="2.9" ccal="19" />\n' +
    //         '\t\t\t<product name="Эстрагон" protein="1.5" fat="0" carbohydrate="5" ccal="25" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Орехи, сухофрукты">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Арахис" protein="26.3" fat="45.2" carbohydrate="9.9" ccal="551" />\n' +
    //         '\t\t\t<product name="Арахис сушеный" protein="29.2" fat="50.2" carbohydrate="10.8" ccal="611" />\n' +
    //         '\t\t\t<product name="Бананы сушёные" protein="3.9" fat="1.8" carbohydrate="80.5" ccal="390" />\n' +
    //         '\t\t\t<product name="Бразильский орех" protein="14" fat="66" carbohydrate="12" ccal="565" />\n' +
    //         '\t\t\t<product name="Грецкий орех" protein="15.6" fat="65.2" carbohydrate="10.2" ccal="648" />\n' +
    //         '\t\t\t<product name="Груши сушёные" protein="2.3" fat="0.6" carbohydrate="62.6" ccal="249" />\n' +
    //         '\t\t\t<product name="Изюм" protein="2.9" fat="0.6" carbohydrate="66" ccal="264" />\n' +
    //         '\t\t\t<product name="Изюм кишмиш" protein="2.3" fat="0" carbohydrate="71.2" ccal="279" />\n' +
    //         '\t\t\t<product name="Каштан жареный" protein="3.2" fat="2.2" carbohydrate="33.8" ccal="182" />\n' +
    //         '\t\t\t<product name="Каштан свежий" protein="3.4" fat="3" carbohydrate="30.6" ccal="166" />\n' +
    //         '\t\t\t<product name="Кедровые орехи" protein="11.6" fat="61" carbohydrate="19.3" ccal="629" />\n' +
    //         '\t\t\t<product name="Кешью" protein="25.7" fat="54.1" carbohydrate="13.2" ccal="643" />\n' +
    //         '\t\t\t<product name="Кешью жареный" protein="17.5" fat="42.2" carbohydrate="30.5" ccal="572" />\n' +
    //         '\t\t\t<product name="Кокосовый орех" protein="3.4" fat="33.5" carbohydrate="29.5" ccal="380" />\n' +
    //         '\t\t\t<product name="Коктейль орехи и сухофрукты" protein="11.2" fat="27.9" carbohydrate="46.6" ccal="483" />\n' +
    //         '\t\t\t<product name="Кумкват сушёный" protein="3.8" fat="0" carbohydrate="80.1" ccal="284" />\n' +
    //         '\t\t\t<product name="Кунжут" protein="19.4" fat="48.7" carbohydrate="12.2" ccal="565" />\n' +
    //         '\t\t\t<product name="Курага" protein="5.2" fat="0.3" carbohydrate="51" ccal="215" />\n' +
    //         '\t\t\t<product name="Мак" protein="17.5" fat="47.5" carbohydrate="2" ccal="5" />\n' +
    //         '\t\t\t<product name="Миндаль сладкий" protein="18.6" fat="57.7" carbohydrate="16.2" ccal="645" />\n' +
    //         '\t\t\t<product name="Персик сушёный" protein="3" fat="0.4" carbohydrate="57.7" ccal="254" />\n' +
    //         '\t\t\t<product name="Рапс" protein="30.8" fat="43.6" carbohydrate="7.2" ccal="544" />\n' +
    //         '\t\t\t<product name="Семена горчицы" protein="25.8" fat="30.8" carbohydrate="23.4" ccal="474" />\n' +
    //         '\t\t\t<product name="Семечки подсолнечника" protein="20.7" fat="52.9" carbohydrate="3.4" ccal="578" />\n' +
    //         '\t\t\t<product name="Семечки тыквенные" protein="24.5" fat="45.8" carbohydrate="18" ccal="556" />\n' +
    //         '\t\t\t<product name="Смесь цукатов (кубики ананаса и папайи)" protein="2" fat="1" carbohydrate="71" ccal="301" />\n' +
    //         '\t\t\t<product name="Урюк" protein="5" fat="0.4" carbohydrate="50.6" ccal="213" />\n' +
    //         '\t\t\t<product name="Финики" protein="2.5" fat="0.5" carbohydrate="69.2" ccal="274" />\n' +
    //         '\t\t\t<product name="Фисташки" protein="20" fat="50" carbohydrate="7" ccal="556" />\n' +
    //         '\t\t\t<product name="Фундук" protein="16.1" fat="66.9" carbohydrate="9.9" ccal="704" />\n' +
    //         '\t\t\t<product name="Чернослив" protein="2.3" fat="0.7" carbohydrate="57.5" ccal="231" />\n' +
    //         '\t\t\t<product name="Шиповник сушёный" protein="3.4" fat="0" carbohydrate="21.5" ccal="110" />\n' +
    //         '\t\t\t<product name="Яблоки сушёные" protein="2.2" fat="0.1" carbohydrate="59" ccal="231" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Рыба и морепродукты">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Анчоус атлантический" protein="20.1" fat="6.1" carbohydrate="0" ccal="135" />\n' +
    //         '\t\t\t<product name="Белуга бланшированная" protein="23.3" fat="15.6" carbohydrate="0" ccal="234" />\n' +
    //         '\t\t\t<product name="Белуга свежая" protein="21" fat="0.7" carbohydrate="0" ccal="147" />\n' +
    //         '\t\t\t<product name="Вобла вяленая" protein="46.4" fat="5.5" carbohydrate="0" ccal="235" />\n' +
    //         '\t\t\t<product name="Вобла копчёная" protein="31.1" fat="6.3" carbohydrate="0" ccal="181" />\n' +
    //         '\t\t\t<product name="Вобла свежая" protein="18" fat="2.8" carbohydrate="0" ccal="97" />\n' +
    //         '\t\t\t<product name="Горбуша жареная" protein="21.2" fat="7.6" carbohydrate="2.1" ccal="168" />\n' +
    //         '                        <product name="Горбуша запеченная" protein="20.3" fat="6.5" carbohydrate="0" ccal="141" />\n' +
    //         '                        <product name="Горбуша отварная" protein="22.9" fat="7.8" carbohydrate="0" ccal="168" />\n' +
    //         '\t\t\t<product name="Горбуша свежая" protein="20.5" fat="6.5" carbohydrate="0" ccal="142" />\n' +
    //         '                        <product name="Горбуша солёная" protein="22.1" fat="9" carbohydrate="0" ccal="169" />\n' +
    //         '\t\t\t<product name="Горбуша тушеная" protein="20.6" fat="6.8" carbohydrate="0.8" ccal="154" />\n' +
    //         '                        <product name="Зубатка жареная" protein="22.2" fat="11.5" carbohydrate="4.1" ccal="209" />\n' +
    //         '\t\t\t<product name="Зубатка запеченная" protein="7.4" fat="8.8" carbohydrate="6.8" ccal="137" />\n' +
    //         '\t\t\t<product name="Зубатка отварная" protein="15.5" fat="5.8" carbohydrate="0" ccal="114" />\n' +
    //         '\t\t\t<product name="Зубатка пёстрая" protein="19.6" fat="5.3" carbohydrate="0" ccal="126" />\n' +
    //         '\t\t\t<product name="Зубатка полосатая" protein="16" fat="5" carbohydrate="0" ccal="109" />\n' +
    //         '\t\t\t<product name="Икра белужья зернистая" protein="27.2" fat="14.2" carbohydrate="0" ccal="237" />\n' +
    //         '\t\t\t<product name="Икра горбуши зернистая" protein="31.2" fat="11.7" carbohydrate="0" ccal="230" />\n' +
    //         '\t\t\t<product name="Икра кеты зернистая" protein="31.6" fat="13.8" carbohydrate="0" ccal="251" />\n' +
    //         '\t\t\t<product name="Икра лещевая пробойная" protein="24.7" fat="4.8" carbohydrate="0" ccal="142" />\n' +
    //         '\t\t\t<product name="Икра лососевая зернистая" protein="32" fat="15" carbohydrate="0" ccal="245" />\n' +
    //         '\t\t\t<product name="Икра минтаевая пробойная" protein="28.4" fat="1.9" carbohydrate="0" ccal="131" />\n' +
    //         '\t\t\t<product name="Икра осетровая зернистая" protein="28" fat="9.7" carbohydrate="0" ccal="203" />\n' +
    //         '\t\t\t<product name="Икра осетровая паюсная" protein="36" fat="10.2" carbohydrate="0" ccal="236" />\n' +
    //         '\t\t\t<product name="Икра осетровая пробойная" protein="36" fat="10.2" carbohydrate="0" ccal="235" />\n' +
    //         '\t\t\t<product name="Икра севрюжья зернистая" protein="28.4" fat="11.9" carbohydrate="0" ccal="221" />\n' +
    //         '\t\t\t<product name="Икра трески" protein="24" fat="0.2" carbohydrate="0" ccal="115" />\n' +
    //         '\t\t\t<product name="Икра щучья" protein="17.3" fat="2" carbohydrate="0" ccal="87" />\n' +
    //         '\t\t\t<product name="Кальмары жареные" protein="9.9" fat="14.4" carbohydrate="1.4" ccal="175" />\n' +
    //         '\t\t\t<product name="Кальмары отварные" protein="18" fat="4.2" carbohydrate="0" ccal="110" />\n' +
    //         '\t\t\t<product name="Кальмары свежие" protein="18" fat="0.3" carbohydrate="0" ccal="74" />\n' +
    //         '\t\t\t<product name="Камбала горячего копчения" protein="22" fat="11.6" carbohydrate="0" ccal="192" />\n' +
    //         '\t\t\t<product name="Камбала отварная" protein="18.3" fat="3.3" carbohydrate="0" ccal="103" />\n' +
    //         '\t\t\t<product name="Камбала свежая" protein="16.5" fat="1.8" carbohydrate="0" ccal="83" />\n' +
    //         '\t\t\t<product name="Карась отварной" protein="20.7" fat="2.1" carbohydrate="0" ccal="102" />\n' +
    //         '\t\t\t<product name="Карась свежий" protein="17.7" fat="1.8" carbohydrate="0" ccal="87" />\n' +
    //         '\t\t\t<product name="Карп отварной" protein="16" fat="3.7" carbohydrate="2" ccal="102" />\n' +
    //         '\t\t\t<product name="Карп свежий" protein="16" fat="5.3" carbohydrate="0" ccal="112" />\n' +
    //         '\t\t\t<product name="Кета свежая" protein="22" fat="5.6" carbohydrate="0" ccal="138" />\n' +
    //         '\t\t\t<product name="Кета соленая" protein="24.3" fat="9.6" carbohydrate="0" ccal="184" />\n' +
    //         '\t\t\t<product name="Кефаль свежая" protein="21" fat="0.4" carbohydrate="0" ccal="124" />\n' +
    //         '\t\t\t<product name="Килька балтийская" protein="14.1" fat="9" carbohydrate="0" ccal="137" />\n' +
    //         '\t\t\t<product name="Килька горячего копчения" protein="21.3" fat="8.5" carbohydrate="0" ccal="162" />\n' +
    //         '\t\t\t<product name="Килька каспийская" protein="18.5" fat="13.1" carbohydrate="0" ccal="191" />\n' +
    //         '\t\t\t<product name="Килька пряного посола" protein="14.8" fat="10.5" carbohydrate="0" ccal="154" />\n' +
    //         '\t\t\t<product name="Килька соленая" protein="17.1" fat="7.6" carbohydrate="0" ccal="137" />\n' +
    //         '\t\t\t<product name="Китовое мясо" protein="22.5" fat="3.2" carbohydrate="0" ccal="119" />\n' +
    //         '\t\t\t<product name="Корюшка" protein="15.4" fat="4.5" carbohydrate="0" ccal="102" />\n' +
    //         '\t\t\t<product name="Крабовое мясо" protein="6" fat="1" carbohydrate="10" ccal="73" />\n' +
    //         '\t\t\t<product name="Крабовые палочки" protein="17.5" fat="2" carbohydrate="0" ccal="88" />\n' +
    //         '\t\t\t<product name="Крабы консервированные" protein="18.7" fat="1.1" carbohydrate="0.1" ccal="85" />\n' +
    //         '\t\t\t<product name="Крабы отварные" protein="16" fat="3.6" carbohydrate="0" ccal="96" />\n' +
    //         '\t\t\t<product name="Краснопёрка" protein="18.3" fat="3" carbohydrate="0" ccal="100" />\n' +
    //         '\t\t\t<product name="Креветки консервированные" protein="17.8" fat="1.1" carbohydrate="0" ccal="81" />\n' +
    //         '\t\t\t<product name="Креветки отварные" protein="18.9" fat="2.2" carbohydrate="0" ccal="95" />\n' +
    //         '\t\t\t<product name="Креветки очищенные замороженные" protein="14" fat="1" carbohydrate="0" ccal="60" />\n' +
    //         '\t\t\t<product name="Креветки свежие" protein="22" fat="1" carbohydrate="0" ccal="97" />\n' +
    //         '\t\t\t<product name="Ледяная рыба" protein="17.7" fat="2.2" carbohydrate="0" ccal="90" />\n' +
    //         '\t\t\t<product name="Лемонема" protein="15.9" fat="0.4" carbohydrate="0" ccal="67" />\n' +
    //         '\t\t\t<product name="Лещ вяленый" protein="42" fat="5.9" carbohydrate="0" ccal="221" />\n' +
    //         '\t\t\t<product name="Лещ горячего копчения" protein="32.8" fat="4.5" carbohydrate="0" ccal="172" />\n' +
    //         '\t\t\t<product name="Лещ отварной" protein="20.9" fat="4.7" carbohydrate="0" ccal="126" />\n' +
    //         '\t\t\t<product name="Лещ свежий" protein="17.1" fat="4.1" carbohydrate="0" ccal="105" />\n' +
    //         '\t\t\t<product name="Лещ холодного копчения" protein="29.7" fat="4.6" carbohydrate="0" ccal="160" />\n' +
    //         '\t\t\t<product name="Лосось копчёный" protein="21.5" fat="8.4" carbohydrate="0" ccal="162" />\n' +
    //         '\t\t\t<product name="Лосось с гриля" protein="20.7" fat="22.3" carbohydrate="0.6" ccal="283" />\n' +
    //         '\t\t\t<product name="Лосось свежий" protein="19.9" fat="13.6" carbohydrate="0" ccal="201" />\n' +
    //         '\t\t\t<product name="Лосось солёный" protein="21" fat="20.5" carbohydrate="0" ccal="269" />\n' +
    //         '\t\t\t<product name="Макрель" protein="20.7" fat="3.4" carbohydrate="0" ccal="113" />\n' +
    //         '\t\t\t<product name="Мидии жареные" protein="11.2" fat="1.6" carbohydrate="0" ccal="59" />\n' +
    //         '\t\t\t<product name="Мидии отварные" protein="9.1" fat="1.5" carbohydrate="0" ccal="50" />\n' +
    //         '\t\t\t<product name="Минтай отварной" protein="17.6" fat="1" carbohydrate="0" ccal="79" />\n' +
    //         '\t\t\t<product name="Мойва весенняя" protein="13.1" fat="7.1" carbohydrate="0" ccal="116" />\n' +
    //         '\t\t\t<product name="Мойва осенняя" protein="13.6" fat="18.1" carbohydrate="0" ccal="217" />\n' +
    //         '\t\t\t<product name="Морская капуста" protein="0.9" fat="0.2" carbohydrate="0" ccal="5" />\n' +
    //         '\t\t\t<product name="Навага" protein="16.1" fat="1" carbohydrate="0" ccal="73" />\n' +
    //         '\t\t\t<product name="Навага беломорская" protein="19.2" fat="1.6" carbohydrate="0" ccal="91" />\n' +
    //         '\t\t\t<product name="Навага дальневосточная" protein="15.1" fat="0.9" carbohydrate="0" ccal="68" />\n' +
    //         '\t\t\t<product name="Налим отварной" protein="21.4" fat="0.7" carbohydrate="0" ccal="92" />\n' +
    //         '\t\t\t<product name="Налим свежий" protein="18.8" fat="0.6" carbohydrate="0" ccal="80" />\n' +
    //         '\t\t\t<product name="Окунь морской горячего копчения" protein="23.5" fat="9" carbohydrate="0" ccal="175" />\n' +
    //         '\t\t\t<product name="Окунь морской отварной" protein="19.9" fat="3.6" carbohydrate="0" ccal="112" />\n' +
    //         '\t\t\t<product name="Окунь морской свежий" protein="17.6" fat="5.2" carbohydrate="0" ccal="117" />\n' +
    //         '\t\t\t<product name="Окунь морской тушеный" protein="10.9" fat="6.6" carbohydrate="4.2" ccal="120" />\n' +
    //         '\t\t\t<product name="Окунь речной жареный" protein="20.6" fat="9.1" carbohydrate="4" ccal="180" />\n' +
    //         '\t\t\t<product name="Окунь речной запечённый" protein="6.8" fat="4.7" carbohydrate="8.4" ccal="103" />\n' +
    //         '\t\t\t<product name="Окунь речной припущенный" protein="19.5" fat="3.5" carbohydrate="0" ccal="109" />\n' +
    //         '\t\t\t<product name="Окунь речной фаршированный" protein="14.9" fat="6.1" carbohydrate="3.9" ccal="130" />\n' +
    //         '\t\t\t<product name="Осётр жареный" protein="16" fat="17.4" carbohydrate="13.2" ccal="273" />\n' +
    //         '\t\t\t<product name="Осётр отварной" protein="17.7" fat="12" carbohydrate="0" ccal="179" />\n' +
    //         '\t\t\t<product name="Осётр припущенный" protein="17.8" fat="11.9" carbohydrate="0" ccal="179" />\n' +
    //         '\t\t\t<product name="Осьминог" protein="18.2" fat="0" carbohydrate="0" ccal="73" />\n' +
    //         '\t\t\t<product name="Палтус отварной" protein="14" fat="0" carbohydrate="17.8" ccal="216" />\n' +
    //         '\t\t\t<product name="Палтус свежий" protein="18.9" fat="3" carbohydrate="0" ccal="102" />\n' +
    //         '\t\t\t<product name="Пангасиус запеченный" protein="14.0" fat="3.8" carbohydrate="2.2" ccal="105" />\n' +
    //         '                        <product name="Пикша" protein="17.2" fat="0.2" carbohydrate="0" ccal="71" />\n' +
    //         '\t\t\t<product name="Плотва свежая" protein="19" fat="0.4" carbohydrate="0" ccal="110" />\n' +
    //         '\t\t\t<product name="Раки морские вареные" protein="20.5" fat="0.7" carbohydrate="0.3" ccal="90" />\n' +
    //         '\t\t\t<product name="Раки речные вареные" protein="20.3" fat="1.3" carbohydrate="1" ccal="97" />\n' +
    //         '\t\t\t<product name="Сазан азовский" protein="18.4" fat="5.3" carbohydrate="0" ccal="121" />\n' +
    //         '\t\t\t<product name="Сазан каспийский" protein="18.2" fat="2.7" carbohydrate="0" ccal="97" />\n' +
    //         '\t\t\t<product name="Сайра бланшированная в масле" protein="18.3" fat="23.3" carbohydrate="0" ccal="283" />\n' +
    //         '\t\t\t<product name="Сайра свежая" protein="18.6" fat="12" carbohydrate="0" ccal="182" />\n' +
    //         '\t\t\t<product name="Салака копчёная" protein="25.4" fat="5.6" carbohydrate="0" ccal="152" />\n' +
    //         '\t\t\t<product name="Сардина в масле" protein="24.1" fat="13.9" carbohydrate="0" ccal="221" />\n' +
    //         '\t\t\t<product name="Сардина в томатном соусе" protein="17" fat="9.9" carbohydrate="1.4" ccal="162" />\n' +
    //         '\t\t\t<product name="Сардина отварная" protein="20.1" fat="10.8" carbohydrate="0" ccal="178" />\n' +
    //         '\t\t\t<product name="Севрюга в томатном соусе" protein="16.1" fat="11.5" carbohydrate="2.8" ccal="179" />\n' +
    //         '\t\t\t<product name="Севрюга свежая" protein="17" fat="11.8" carbohydrate="0" ccal="160" />\n' +
    //         '\t\t\t<product name="Сельдь в растительном масле" protein="16.4" fat="26.5" carbohydrate="0" ccal="301" />\n' +
    //         '\t\t\t<product name="Сельдь в сметане" protein="5.5" fat="6.2" carbohydrate="5.3" ccal="97" />\n' +
    //         '\t\t\t<product name="Сельдь в томатном соусе" protein="13.8" fat="9.7" carbohydrate="4.6" ccal="159" />\n' +
    //         '\t\t\t<product name="Сельдь горячего копчения" protein="21.8" fat="14.3" carbohydrate="0" ccal="215" />\n' +
    //         '\t\t\t<product name="Сельдь маринованная" protein="16.5" fat="12.6" carbohydrate="3.4" ccal="192" />\n' +
    //         '\t\t\t<product name="Сельдь свежая" protein="16.3" fat="10.7" carbohydrate="0" ccal="161" />\n' +
    //         '\t\t\t<product name="Сельдь соленая" protein="19.8" fat="15.4" carbohydrate="0" ccal="217" />\n' +
    //         '\t\t\t<product name="Сёмга свежая" protein="21.6" fat="6" carbohydrate="0" ccal="140" />\n' +
    //         '\t\t\t<product name="Скумбрия копченая" protein="20.7" fat="15.5" carbohydrate="0" ccal="221" />\n' +
    //         '\t\t\t<product name="Скумбрия отварная" protein="19.6" fat="14.7" carbohydrate="0" ccal="211" />\n' +
    //         '\t\t\t<product name="Сом отварной" protein="18.4" fat="13.6" carbohydrate="0" ccal="196" />\n' +
    //         '\t\t\t<product name="Сом припущенный" protein="18.2" fat="5.4" carbohydrate="0" ccal="121" />\n' +
    //         '\t\t\t<product name="Ставрида в масле" protein="15.6" fat="27.4" carbohydrate="0" ccal="309" />\n' +
    //         '\t\t\t<product name="Ставрида в томатном соусе" protein="14.8" fat="2.3" carbohydrate="7.3" ccal="110" />\n' +
    //         '\t\t\t<product name="Ставрида жареная" protein="20.3" fat="10.5" carbohydrate="3.7" ccal="190" />\n' +
    //         '\t\t\t<product name="Ставрида отварная" protein="20.6" fat="5.6" carbohydrate="0" ccal="133" />\n' +
    //         '\t\t\t<product name="Ставрида припущенная" protein="19.6" fat="4.8" carbohydrate="0" ccal="122" />\n' +
    //         '\t\t\t<product name="Ставрида холодного копчения" protein="17.1" fat="2.8" carbohydrate="0" ccal="94" />\n' +
    //         '\t\t\t<product name="Стерлядь свежая" protein="17" fat="6.1" carbohydrate="0" ccal="122" />\n' +
    //         '\t\t\t<product name="Судак отварной" protein="21.3" fat="1.3" carbohydrate="0" ccal="97" />\n' +
    //         '\t\t\t<product name="Судак припущенный" protein="19.6" fat="1.2" carbohydrate="0" ccal="89" />\n' +
    //         '\t\t\t<product name="Судак фаршированный" protein="13.7" fat="6" carbohydrate="8.7" ccal="144" />\n' +
    //         '                        <product name="Терпуг" protein="17.7" fat="3.5" carbohydrate="0" ccal="102" />\n' +
    //         '\t\t\t<product name="Толстолобик запеченный" protein="18.1" fat="5.4" carbohydrate="0" ccal="119" />\n' +
    //         '                        <product name="Треска горячего копчения" protein="26" fat="1.2" carbohydrate="0" ccal="115" />\n' +
    //         '\t\t\t<product name="Треска жареная" protein="23" fat="0.1" carbohydrate="0" ccal="111" />\n' +
    //         '\t\t\t<product name="Треска запечённая" protein="6" fat="3.7" carbohydrate="8" ccal="90" />\n' +
    //         '\t\t\t<product name="Треска копченая" protein="22.1" fat="0.5" carbohydrate="0" ccal="94" />\n' +
    //         '\t\t\t<product name="Треска отварная" protein="17.8" fat="0.7" carbohydrate="0" ccal="78" />\n' +
    //         '\t\t\t<product name="Треска с гриля" protein="22.1" fat="9.1" carbohydrate="0.6" ccal="172" />\n' +
    //         '\t\t\t<product name="Треска соленая" protein="23.1" fat="0.6" carbohydrate="0" ccal="98" />\n' +
    //         '\t\t\t<product name="Треска тушеная" protein="9.7" fat="5.1" carbohydrate="3.9" ccal="101" />\n' +
    //         '\t\t\t<product name="Тунец в растительном масле" protein="27.1" fat="9" carbohydrate="0" ccal="190" />\n' +
    //         '\t\t\t<product name="Тунец в собственном соке" protein="21" fat="1.2" carbohydrate="0" ccal="96" />\n' +
    //         '\t\t\t<product name="Тунец свежий" protein="23" fat="1" carbohydrate="0" ccal="101" />\n' +
    //         '\t\t\t<product name="Угорь копчёный" protein="17.9" fat="28.6" carbohydrate="0" ccal="326" />\n' +
    //         '\t\t\t<product name="Угорь свежий" protein="14.5" fat="30.5" carbohydrate="0" ccal="332" />\n' +
    //         '\t\t\t<product name="Устрицы свежие" protein="14" fat="0.3" carbohydrate="6" ccal="95" />\n' +
    //         '\t\t\t<product name="Форель" protein="19.2" fat="2.1" carbohydrate="0" ccal="97" />\n' +
    //         '\t\t\t<product name="Хамса соленая" protein="21.2" fat="9" carbohydrate="0" ccal="166" />\n' +
    //         '\t\t\t<product name="Хек отварной" protein="18.5" fat="2.3" carbohydrate="0" ccal="95" />\n' +
    //         '\t\t\t<product name="Хек свежий" protein="16.6" fat="2.2" carbohydrate="0" ccal="86" />\n' +
    //         '\t\t\t<product name="Шпроты" protein="17.4" fat="32.4" carbohydrate="0.4" ccal="363" />\n' +
    //         '\t\t\t<product name="Щука в томатном соусе" protein="14.2" fat="4" carbohydrate="3.6" ccal="108" />\n' +
    //         '\t\t\t<product name="Щука отварная" protein="21.3" fat="1.3" carbohydrate="0" ccal="98" />\n' +
    //         '\t\t\t<product name="Щука припущенная" protein="19.6" fat="1.2" carbohydrate="0" ccal="90" />\n' +
    //         '\t\t\t<product name="Щука фаршированная" protein="13.3" fat="6" carbohydrate="8.4" ccal="141" />\n' +
    //         '\t\t\t<product name="Язык морской" protein="10.3" fat="5.2" carbohydrate="0" ccal="88" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Сладости">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Варенье грушевое" protein="0.3" fat="0.2" carbohydrate="70.8" ccal="273" />\n' +
    //         '\t\t\t<product name="Варенье клубничное" protein="0.3" fat="0.1" carbohydrate="74" ccal="285" />\n' +
    //         '\t\t\t<product name="Варенье малиновое" protein="0.6" fat="0.2" carbohydrate="70.4" ccal="273" />\n' +
    //         '\t\t\t<product name="Варенье персиковое" protein="0.5" fat="0" carbohydrate="66.8" ccal="258" />\n' +
    //         '\t\t\t<product name="Варенье сливовое" protein="0.4" fat="0.3" carbohydrate="74.2" ccal="288" />\n' +
    //         '\t\t\t<product name="Варенье яблочное" protein="0.4" fat="0.3" carbohydrate="68.2" ccal="265" />\n' +
    //         '\t\t\t<product name="Вафли" protein="8.1" fat="19.4" carbohydrate="53.3" ccal="424" />\n' +
    //         '\t\t\t<product name="Галеты классические" protein="11.1" fat="1.2" carbohydrate="76" ccal="365" />\n' +
    //         '\t\t\t<product name="Гематоген" protein="6" fat="3" carbohydrate="75.7" ccal="354" />\n' +
    //         '\t\t\t<product name="Драже арахис с мёдом и кунжутом" protein="16.8" fat="36.4" carbohydrate="33.4" ccal="528" />\n' +
    //         '\t\t\t<product name="Драже ореховое" protein="11.9" fat="38.3" carbohydrate="44.2" ccal="552" />\n' +
    //         '\t\t\t<product name="Драже фруктовое в шоколаде" protein="3.7" fat="10.2" carbohydrate="76.3" ccal="389" />\n' +
    //         '                        <product name="Желе" protein="2.8" fat="0" carbohydrate="20.6" ccal="87" />\n' +
    //         '\t\t\t<product name="Зефир" protein="0.8" fat="0" carbohydrate="78.5" ccal="304" />\n' +
    //         '\t\t\t<product name="Карамель леденцовая" protein="0" fat="0.1" carbohydrate="96.2" ccal="362" />\n' +
    //         '\t\t\t<product name="Карамель с ликёром" protein="0" fat="0.1" carbohydrate="92.6" ccal="358" />\n' +
    //         '\t\t\t<product name="Карамель фруктовая" protein="0.1" fat="0.1" carbohydrate="92.2" ccal="357" />\n' +
    //         '\t\t\t<product name="Карамель шоколадно-ореховая" protein="1.8" fat="9.2" carbohydrate="86.8" ccal="420" />\n' +
    //         '                        <product name="Кекс" protein="6.2" fat="13.6" carbohydrate="34.4" ccal="276" />\n' +
    //         '                        <product name="Кекс с изюмом" protein="6.4" fat="17.6" carbohydrate="53.6" ccal="384" />\n' +
    //         '\t\t\t<product name="Конфеты грильяжные" protein="5.3" fat="26.9" carbohydrate="65.6" ccal="523" />\n' +
    //         '\t\t\t<product name="Конфеты кремовые" protein="7.5" fat="31.8" carbohydrate="55.8" ccal="523" />\n' +
    //         '\t\t\t<product name="Конфеты молочные" protein="2.7" fat="4.3" carbohydrate="82.3" ccal="364" />\n' +
    //         '\t\t\t<product name="Конфеты помадные" protein="2.2" fat="4.6" carbohydrate="83.6" ccal="369" />\n' +
    //         '\t\t\t<product name="Конфеты фруктовые" protein="1.6" fat="8.6" carbohydrate="75.8" ccal="369" />\n' +
    //         '\t\t\t<product name="Конфеты шоколадные" protein="4" fat="39.5" carbohydrate="54.2" ccal="569" />\n' +
    //         '                        <product name="Конфеты шоколадно-вафельные ДЖЕК" protein="5.5" fat="32.7" carbohydrate="57.1" ccal="539" />\n' +
    //         '\t\t\t<product name="Кукурузные палочки" protein="8.3" fat="1.2" carbohydrate="75" ccal="325" />\n' +
    //         '\t\t\t<product name="Кукурузные палочки сладкие" protein="4.7" fat="13" carbohydrate="82.9" ccal="467" />\n' +
    //         '\t\t\t<product name="Мармелад фруктово-ягодный" protein="0.4" fat="0" carbohydrate="76.6" ccal="293" />\n' +
    //         '\t\t\t<product name="Мармеладные дольки" protein="0" fat="0" carbohydrate="86.6" ccal="318" />\n' +
    //         '                        <product name="Мед" protein="0.8" fat="0" carbohydrate="80.3" ccal="324" />\n' +
    //         '\t\t\t<product name="Мороженое крем-брюле" protein="3.5" fat="3.5" carbohydrate="23.1" ccal="134" />\n' +
    //         '\t\t\t<product name="Мороженое молочное" protein="3.2" fat="3.5" carbohydrate="21.3" ccal="126" />\n' +
    //         '\t\t\t<product name="Мороженое ореховое" protein="5.4" fat="6.5" carbohydrate="20.1" ccal="157" />\n' +
    //         '\t\t\t<product name="Мороженое пломбир" protein="3.2" fat="15" carbohydrate="20.8" ccal="227" />\n' +
    //         '\t\t\t<product name="Мороженое сливочное" protein="3.3" fat="10" carbohydrate="19.8" ccal="179" />\n' +
    //         '\t\t\t<product name="Мороженое фруктовое" protein="3.8" fat="2.8" carbohydrate="22.2" ccal="123" />\n' +
    //         '\t\t\t<product name="Мороженое шоколадное" protein="4.2" fat="3.5" carbohydrate="23" ccal="138" />\n' +
    //         '\t\t\t<product name="Мороженое эскимо" protein="3.5" fat="20" carbohydrate="19.6" ccal="270" />\n' +
    //         '\t\t\t<product name="Пастила" protein="0.5" fat="0" carbohydrate="80.8" ccal="310" />\n' +
    //         '\t\t\t<product name="Печенье" protein="7.5" fat="11.8" carbohydrate="74.9" ccal="417" />\n' +
    //         '\t\t\t<product name="Печенье миндальное" protein="7" fat="22.7" carbohydrate="64.7" ccal="486" />\n' +
    //         '\t\t\t<product name="Печенье овсяное" protein="6.5" fat="14.4" carbohydrate="71.8" ccal="437" />\n' +
    //         '\t\t\t<product name="Печенье сдобное" protein="10.4" fat="5.2" carbohydrate="76.8" ccal="458" />\n' +
    //         '\t\t\t<product name="Пряник" protein="5.8" fat="6.5" carbohydrate="71.6" ccal="364" />\n' +
    //         '\t\t\t<product name="Пряник шоколадный" protein="9.1" fat="7.8" carbohydrate="67.2" ccal="381" />\n' +
    //         '\t\t\t<product name="Слойка Круассан с варёной сгущёнкой" protein="2.3" fat="20.4" carbohydrate="53.2" ccal="335" />\n' +
    //         '\t\t\t<product name="Слойка с сыром" protein="12.3" fat="16.5" carbohydrate="43.7" ccal="376" />\n' +
    //         '\t\t\t<product name="Слойка с шоколадом" protein="5.3" fat="14.1" carbohydrate="51.7" ccal="356" />\n' +
    //         '\t\t\t<product name="Халва подсолнечная" protein="11.6" fat="29.7" carbohydrate="54" ccal="523" />\n' +
    //         '\t\t\t<product name="Шоколад  молочный с орехами" protein="6.6" fat="40.9" carbohydrate="49.9" ccal="580" />\n' +
    //         '\t\t\t<product name="Шоколад белый" protein="4.2" fat="30.4" carbohydrate="62.2" ccal="541" />\n' +
    //         '\t\t\t<product name="Шоколад белый с кокосом" protein="7.3" fat="35" carbohydrate="54.6" ccal="562" />\n' +
    //         '\t\t\t<product name="Шоколад горький с какао" protein="6.2" fat="32.1" carbohydrate="52.6" ccal="524" />\n' +
    //         '\t\t\t<product name="Шоколад горький с лесным орехом" protein="8" fat="38.7" carbohydrate="44.7" ccal="559" />\n' +
    //         '\t\t\t<product name="Шоколад молочный" protein="6.9" fat="35.7" carbohydrate="54.4" ccal="550" />\n' +
    //         '\t\t\t<product name="Шоколад молочный с кокосом" protein="3.9" fat="24.6" carbohydrate="57.9" ccal="471" />\n' +
    //         '\t\t\t<product name="Шоколад молочный с суфле" protein="3.6" fat="16.8" carbohydrate="71.7" ccal="452" />\n' +
    //         '\t\t\t<product name="Шоколад пористый тёмный" protein="5.7" fat="28.5" carbohydrate="58.7" ccal="517" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Соусы и приправы">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Аджика" protein="1" fat="3.7" carbohydrate="5.8" ccal="59" />\n' +
    //         '\t\t\t<product name="Базилик свежий" protein="2.5" fat="0.6" carbohydrate="4.3" ccal="27" />\n' +
    //         '\t\t\t<product name="Базилик сушеный" protein="14.4" fat="4" carbohydrate="61" ccal="251" />\n' +
    //         '\t\t\t<product name="Бульонный кубик" protein="2" fat="2" carbohydrate="4" ccal="40" />\n' +
    //         '\t\t\t<product name="Ванилин" protein="0.1" fat="0.1" carbohydrate="12.7" ccal="288" />\n' +
    //         '\t\t\t<product name="Гвоздика" protein="6" fat="20.1" carbohydrate="61.2" ccal="323" />\n' +
    //         '\t\t\t<product name="Горчица" protein="5.7" fat="6.4" carbohydrate="22" ccal="162" />\n' +
    //         '\t\t\t<product name="Горчичный порошок" protein="37.1" fat="32.6" carbohydrate="11.1" ccal="378" />\n' +
    //         '\t\t\t<product name="Дрожжи" protein="12.7" fat="2.7" carbohydrate="0" ccal="75" />\n' +
    //         '\t\t\t<product name="Желатин пищевой" protein="87.2" fat="0.4" carbohydrate="0.7" ccal="355" />\n' +
    //         '\t\t\t<product name="Имбирь" protein="9.1" fat="6" carbohydrate="70.8" ccal="347" />\n' +
    //         '\t\t\t<product name="Кардамон" protein="10.8" fat="6.7" carbohydrate="68.5" ccal="311" />\n' +
    //         '\t\t\t<product name="Кетчуп" protein="1.8" fat="1" carbohydrate="22.2" ccal="93" />\n' +
    //         '\t\t\t<product name="Кинза сушёная молотая" protein="3" fat="0" carbohydrate="54.5" ccal="216" />\n' +
    //         '\t\t\t<product name="Кокосовая стружка" protein="13" fat="65" carbohydrate="14" ccal="592" />\n' +
    //         '\t\t\t<product name="Кориандр" protein="1.5" fat="0" carbohydrate="5" ccal="25" />\n' +
    //         '\t\t\t<product name="Корица" protein="3.9" fat="3.2" carbohydrate="79.8" ccal="261" />\n' +
    //         '\t\t\t<product name="Крахмал картофельный" protein="0.1" fat="0" carbohydrate="79.6" ccal="300" />\n' +
    //         '\t\t\t<product name="Крахмал кукурузный" protein="1" fat="0.6" carbohydrate="85.2" ccal="329" />\n' +
    //         '\t\t\t<product name="Куркума" protein="12.7" fat="13.8" carbohydrate="58.2" ccal="325" />\n' +
    //         '\t\t\t<product name="Лавровый лист" protein="7.6" fat="8.4" carbohydrate="75" ccal="313" />\n' +
    //         '\t\t\t<product name="Майонез диетический" protein="1.4" fat="20" carbohydrate="4.4" ccal="204" />\n' +
    //         '\t\t\t<product name="Майонез Европейский" protein="1.4" fat="72" carbohydrate="2.6" ccal="665" />\n' +
    //         '\t\t\t<product name="Майонез лёгкий" protein="0.3" fat="30" carbohydrate="5.2" ccal="299" />\n' +
    //         '\t\t\t<product name="Майонез экстра легкий" protein="1" fat="8" carbohydrate="10" ccal="110" />\n' +
    //         '\t\t\t<product name="Майоран" protein="12.7" fat="7" carbohydrate="60.6" ccal="271" />\n' +
    //         '\t\t\t<product name="Мёд натуральный" protein="0.8" fat="0" carbohydrate="81.5" ccal="309" />\n' +
    //         '\t\t\t<product name="Мускатный орех" protein="20" fat="50" carbohydrate="7" ccal="556" />\n' +
    //         '\t\t\t<product name="Мята свежая" protein="3.8" fat="0.9" carbohydrate="14.9" ccal="70" />\n' +
    //         '\t\t\t<product name="Патока" protein="0" fat="0.3" carbohydrate="78.3" ccal="296" />\n' +
    //         '\t\t\t<product name="Пектин" protein="0" fat="0" carbohydrate="89.6" ccal="336" />\n' +
    //         '\t\t\t<product name="Перец молотый красный" protein="0.7" fat="0.3" carbohydrate="4.6" ccal="21" />\n' +
    //         '\t\t\t<product name="Перец молотый чёрный" protein="11" fat="3.3" carbohydrate="64.8" ccal="255" />\n' +
    //         '\t\t\t<product name="Перец чили" protein="2" fat="0.2" carbohydrate="9.5" ccal="40" />\n' +
    //         '\t\t\t<product name="Розмарин" protein="3.3" fat="5.9" carbohydrate="20.7" ccal="131" />\n' +
    //         '\t\t\t<product name="Сахар" protein="0" fat="0" carbohydrate="99.8" ccal="374" />\n' +
    //         '\t\t\t<product name="Сахар ванильный" protein="0" fat="0" carbohydrate="14.4" ccal="56" />\n' +
    //         '\t\t\t<product name="Сахарная пудра" protein="0" fat="0" carbohydrate="99.8" ccal="374" />\n' +
    //         '\t\t\t<product name="Семена льна" protein="18.5" fat="42.5" carbohydrate="28.2" ccal="531" />\n' +
    //         '                        <product name="Соус апельсиновый" protein="0.6" fat="4.6" carbohydrate="3.1" ccal="55" />\n' +
    //         '\t\t\t<product name="Соус барбекю" protein="0.6" fat="0.7" carbohydrate="39.4" ccal="166" />\n' +
    //         '\t\t\t<product name="Соус белый" protein="1" fat="3.8" carbohydrate="4.1" ccal="56" />\n' +
    //         '\t\t\t<product name="Соус грибной" protein="1.2" fat="4.8" carbohydrate="6.2" ccal="82" />\n' +
    //         '\t\t\t<product name="Соус кисло-сладкий" protein="0.3" fat="0.8" carbohydrate="39.7" ccal="167" />\n' +
    //         '\t\t\t<product name="Соус клюквенный" protein="0.2" fat="1" carbohydrate="42.2" ccal="180" />\n' +
    //         '\t\t\t<product name="Соус луковый" protein="1.3" fat="6.1" carbohydrate="4.8" ccal="75" />\n' +
    //         '\t\t\t<product name="Соус молочный" protein="2" fat="7.1" carbohydrate="5.2" ccal="84" />\n' +
    //         '\t\t\t<product name="Соус сметанный" protein="1.9" fat="5.7" carbohydrate="5.2" ccal="78" />\n' +
    //         '\t\t\t<product name="Соус сметанный с шампиньонами" protein="0.6" fat="14.5" carbohydrate="9.1" ccal="169" />\n' +
    //         '\t\t\t<product name="Соус соевый классический" protein="3.5" fat="0" carbohydrate="11" ccal="58" />\n' +
    //         '\t\t\t<product name="Соус сырный" protein="2.5" fat="36" carbohydrate="5.5" ccal="356" />\n' +
    //         '\t\t\t<product name="Соус ткемали" protein="5" fat="40" carbohydrate="10" ccal="418" />\n' +
    //         '\t\t\t<product name="Соус томатный" protein="1.7" fat="7.8" carbohydrate="4.5" ccal="80" />\n' +
    //         '\t\t\t<product name="Соус томатный острый" protein="2.5" fat="0" carbohydrate="21.8" ccal="98" />\n' +
    //         '\t\t\t<product name="Сухари панировочные" protein="9.7" fat="1.9" carbohydrate="77.6" ccal="347" />\n' +
    //         '\t\t\t<product name="Тимьян" protein="9.1" fat="7.4" carbohydrate="63.9" ccal="276" />\n' +
    //         '\t\t\t<product name="Тмин" protein="17.8" fat="44.2" carbohydrate="22.3" ccal="375" />\n' +
    //         '\t\t\t<product name="Томатная паста" protein="5.6" fat="1.5" carbohydrate="16.7" ccal="92" />\n' +
    //         '\t\t\t<product name="Уксус 3%-ный" protein="0" fat="0" carbohydrate="3" ccal="11" />\n' +
    //         '\t\t\t<product name="Уксус белый винный" protein="0" fat="0" carbohydrate="5.9" ccal="14" />\n' +
    //         '\t\t\t<product name="Уксус яблочный" protein="0" fat="0" carbohydrate="7.2" ccal="14" />\n' +
    //         '\t\t\t<product name="Хрен тёртый с клюквой" protein="1" fat="4.1" carbohydrate="22.1" ccal="129" />\n' +
    //         '\t\t\t<product name="Хрен тёртый с лимоном" protein="2.2" fat="7.8" carbohydrate="9.6" ccal="117" />\n' +
    //         '\t\t\t<product name="Хрен тёртый с чесноком" protein="1.2" fat="4.1" carbohydrate="7.5" ccal="72" />\n' +
    //         '\t\t\t<product name="Хрен тёртый со свеклой" protein="1.1" fat="4.1" carbohydrate="7.8" ccal="73" />\n' +
    //         '\t\t\t<product name="Хрен тёртый Столовый" protein="2.2" fat="7.8" carbohydrate="9.6" ccal="117" />\n' +
    //         '\t\t\t<product name="Цедра апельсина" protein="0.9" fat="0.1" carbohydrate="3" ccal="16" />\n' +
    //         '\t\t\t<product name="Цедра лимона" protein="0.9" fat="0.1" carbohydrate="3" ccal="16" />\n' +
    //         '\t\t\t<product name="Шафран" protein="11.4" fat="5.9" carbohydrate="65.4" ccal="310" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Сыр и творог">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Сыр Адыгейский" protein="18.5" fat="14" carbohydrate="0" ccal="240" />\n' +
    //         '\t\t\t<product name="Сыр Альпийский" protein="25" fat="27" carbohydrate="0" ccal="353" />\n' +
    //         '\t\t\t<product name="Сыр Аппенцеллер" protein="24.7" fat="31.7" carbohydrate="2" ccal="403" />\n' +
    //         '\t\t\t<product name="Сыр Бри" protein="21" fat="23" carbohydrate="0" ccal="291" />\n' +
    //         '\t\t\t<product name="Сыр брынза (из коровьего молока)" protein="17.9" fat="20.1" carbohydrate="0" ccal="260" />\n' +
    //         '\t\t\t<product name="Сыр брынза (из овечьего молока)" protein="14.6" fat="25.5" carbohydrate="0" ccal="298" />\n' +
    //         '\t\t\t<product name="Сыр Гауда" protein="25" fat="27" carbohydrate="2" ccal="356" />\n' +
    //         '\t\t\t<product name="Сыр Голландский" protein="26" fat="26.8" carbohydrate="0" ccal="352" />\n' +
    //         '\t\t\t<product name="Сыр Грюйер" protein="27" fat="31" carbohydrate="0" ccal="396" />\n' +
    //         '\t\t\t<product name="Сыр Датский" protein="25" fat="24.3" carbohydrate="0" ccal="330" />\n' +
    //         '\t\t\t<product name="Сыр Деревенский" protein="10.3" fat="5.3" carbohydrate="3" ccal="103" />\n' +
    //         '\t\t\t<product name="Сыр Домашний" protein="12.7" fat="5" carbohydrate="4" ccal="113" />\n' +
    //         '\t\t\t<product name="Сыр Дор Блю" protein="21" fat="30" carbohydrate="0" ccal="354" />\n' +
    //         '\t\t\t<product name="Сыр Камамбер" protein="21" fat="23" carbohydrate="0" ccal="291" />\n' +
    //         '\t\t\t<product name="Сыр Кисломолочный" protein="31" fat="0.7" carbohydrate="0.3" ccal="133" />\n' +
    //         '\t\t\t<product name="Сыр Козий" protein="21.3" fat="21.7" carbohydrate="0.7" ccal="290" />\n' +
    //         '\t\t\t<product name="Сыр колбасный копчёный" protein="23" fat="19" carbohydrate="0" ccal="271" />\n' +
    //         '\t\t\t<product name="Сыр Копчёный" protein="27.7" fat="25.3" carbohydrate="7.3" ccal="380" />\n' +
    //         '\t\t\t<product name="Сыр Маасдам" protein="23.5" fat="26" carbohydrate="0" ccal="350" />\n' +
    //         '\t\t\t<product name="Сыр Мондзеер" protein="22" fat="20.3" carbohydrate="6.7" ccal="280" />\n' +
    //         '\t\t\t<product name="Сыр Моцарелла" protein="18" fat="24" carbohydrate="0" ccal="240" />\n' +
    //         '                        <product name="Сыр Ольтермани 9%" protein="31" fat="9" carbohydrate="0" ccal="205" />\n' +
    //         '                        <product name="Сыр Ольтермани 17%" protein="29" fat="17" carbohydrate="0" ccal="269" />\n' +
    //         '                        <product name="Сыр Ольтермани 29%" protein="25" fat="29" carbohydrate="0" ccal="361" />\n' +
    //         '                        <product name="Сыр Ольтермани 55%" protein="23" fat="34" carbohydrate="0" ccal="398" />\n' +
    //         '\t\t\t<product name="Сыр Пармезан" protein="33" fat="28" carbohydrate="0" ccal="392" />\n' +
    //         '\t\t\t<product name="Сыр Пошехонский" protein="26" fat="26.5" carbohydrate="0" ccal="350" />\n' +
    //         '\t\t\t<product name="Сыр Прибалтийский" protein="30" fat="9" carbohydrate="0" ccal="209" />\n' +
    //         '\t\t\t<product name="Сыр Раклет" protein="22.7" fat="28" carbohydrate="1" ccal="357" />\n' +
    //         '\t\t\t<product name="Сыр Рикотта" protein="11" fat="13" carbohydrate="3" ccal="174" />\n' +
    //         '\t\t\t<product name="Сыр Рокфор" protein="20" fat="28" carbohydrate="0" ccal="337" />\n' +
    //         '\t\t\t<product name="Сыр Российский" protein="24.1" fat="29.5" carbohydrate="0.3" ccal="363" />\n' +
    //         '\t\t\t<product name="Сыр Сулугуни" protein="20" fat="24" carbohydrate="0" ccal="290" />\n' +
    //         '\t\t\t<product name="Сыр Тильзитер" protein="27.8" fat="25" carbohydrate="0.1" ccal="334" />\n' +
    //         '\t\t\t<product name="Сыр Фета" protein="17" fat="24" carbohydrate="0" ccal="290" />\n' +
    //         '\t\t\t<product name="Сыр Чеддер" protein="23" fat="32" carbohydrate="0" ccal="392" />\n' +
    //         '\t\t\t<product name="Сыр Чечил" protein="19.5" fat="4" carbohydrate="0" ccal="140" />\n' +
    //         '\t\t\t<product name="Сыр Швейцарский" protein="24.9" fat="31.8" carbohydrate="0" ccal="396" />\n' +
    //         '\t\t\t<product name="Сыр Эдам" protein="24" fat="26" carbohydrate="0" ccal="330" />\n' +
    //         '\t\t\t<product name="Сыр Эдам копчёный" protein="24.8" fat="25" carbohydrate="0" ccal="334" />\n' +
    //         '\t\t\t<product name="Сырок глазированный" protein="8.5" fat="27.8" carbohydrate="32" ccal="407" />\n' +
    //         '                        <product name="Плавленный сыр Дружба" protein="17" fat="24.3" carbohydrate="3.1" ccal="299" />\n' +
    //         '\t\t\t<product name="Творог обезжиренный" protein="17.6" fat="0.6" carbohydrate="2.7" ccal="87" />\n' +
    //         '                        <product name="Творог 1% нежирный" protein="17.1" fat="1.1" carbohydrate="2.7" ccal="91" />\n' +
    //         '                        <product name="Творог 2% нежирный" protein="18" fat="2" carbohydrate="3.3" ccal="103" />\n' +
    //         '\t\t\t<product name="Творог 5% зернистый" protein="12.7" fat="5" carbohydrate="2.4" ccal="105" />\n' +
    //         '\t\t\t<product name="Творог 5% зернистый с фруктами" protein="10.3" fat="5" carbohydrate="12.5" ccal="127" />\n' +
    //         '\t\t\t<product name="Творог 5% классический" protein="16" fat="5" carbohydrate="3" ccal="121" />\n' +
    //         '\t\t\t<product name="Творог 9% классический" protein="16" fat="9" carbohydrate="3" ccal="157" />\n' +
    //         '\t\t\t<product name="Творог жирный" protein="14" fat="18" carbohydrate="2.8" ccal="232" />\n' +
    //         '\t\t\t<product name="Творог зернистый" protein="14" fat="4" carbohydrate="2" ccal="100" />\n' +
    //         '\t\t\t<product name="Творог мягкий диетический" protein="16" fat="11" carbohydrate="1" ccal="170" />\n' +
    //         '\t\t\t<product name="Творог нежирный" protein="18" fat="0.6" carbohydrate="1.8" ccal="88" />\n' +
    //         '\t\t\t<product name="Творог полужирный" protein="16.7" fat="9" carbohydrate="2" ccal="159" />\n' +
    //         '\t\t\t<product name="Творог Тофу" protein="8.1" fat="4.2" carbohydrate="0.6" ccal="73" />\n' +
    //         '\t\t\t<product name="Творожная масса" protein="7.1" fat="23" carbohydrate="27.5" ccal="341" />\n' +
    //         '\t\t\t<product name="Творожная масса с изюмом" protein="6.8" fat="21.6" carbohydrate="29.9" ccal="343" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Фрукты">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Абрикос" protein="0.9" fat="0.1" carbohydrate="10.8" ccal="41" />\n' +
    //         '                        <product name="Абрикосовые косточки" protein="25" fat="45.6" carbohydrate="2.9" ccal="517" />\n' +
    //         '\t\t\t<product name="Авокадо" protein="2" fat="20" carbohydrate="7.4" ccal="208" />\n' +
    //         '\t\t\t<product name="Айва" protein="0.6" fat="0.5" carbohydrate="9.8" ccal="40" />\n' +
    //         '\t\t\t<product name="Алыча" protein="0.2" fat="0" carbohydrate="6.9" ccal="27" />\n' +
    //         '\t\t\t<product name="Ананас" protein="0.4" fat="0.2" carbohydrate="10.6" ccal="49" />\n' +
    //         '\t\t\t<product name="Апельсин" protein="0.9" fat="0.2" carbohydrate="8.1" ccal="36" />\n' +
    //         '\t\t\t<product name="Арбуз" protein="0.6" fat="0.1" carbohydrate="5.8" ccal="25" />\n' +
    //         '\t\t\t<product name="Арбуз консервированный" protein="0.5" fat="0.1" carbohydrate="9" ccal="37" />\n' +
    //         '\t\t\t<product name="Банан" protein="1.5" fat="0.1" carbohydrate="21.8" ccal="89" />\n' +
    //         '\t\t\t<product name="Вишня" protein="0.8" fat="0.5" carbohydrate="11.3" ccal="52" />\n' +
    //         '\t\t\t<product name="Гранат" protein="0.9" fat="0" carbohydrate="13.9" ccal="52" />\n' +
    //         '\t\t\t<product name="Грейпфрут" protein="0.7" fat="0.2" carbohydrate="6.5" ccal="29" />\n' +
    //         '\t\t\t<product name="Груша" protein="0.4" fat="0.3" carbohydrate="10.9" ccal="42" />\n' +
    //         '\t\t\t<product name="Гуава" protein="0.8" fat="0" carbohydrate="0.6" ccal="57" />\n' +
    //         '\t\t\t<product name="Дыня" protein="0.6" fat="0.3" carbohydrate="7.4" ccal="33" />\n' +
    //         '\t\t\t<product name="Инжир" protein="0.7" fat="0.2" carbohydrate="13.7" ccal="49" />\n' +
    //         '\t\t\t<product name="Киви" protein="1" fat="0.6" carbohydrate="10.3" ccal="48" />\n' +
    //         '\t\t\t<product name="Кизил" protein="1" fat="0" carbohydrate="10.5" ccal="44" />\n' +
    //         '\t\t\t<product name="Лайм" protein="0.9" fat="0.1" carbohydrate="3" ccal="16" />\n' +
    //         '\t\t\t<product name="Лимон" protein="0.9" fat="0.1" carbohydrate="3" ccal="16" />\n' +
    //         '\t\t\t<product name="Манго" protein="0.5" fat="0.3" carbohydrate="11.5" ccal="67" />\n' +
    //         '\t\t\t<product name="Мандарин" protein="0.8" fat="0.2" carbohydrate="7.5" ccal="33" />\n' +
    //         '\t\t\t<product name="Нектарин" protein="0.9" fat="0.2" carbohydrate="11.8" ccal="48" />\n' +
    //         '\t\t\t<product name="Памело" protein="0.6" fat="0.2" carbohydrate="6.7" ccal="32" />\n' +
    //         '\t\t\t<product name="Папайя" protein="0.6" fat="0.1" carbohydrate="9.2" ccal="48" />\n' +
    //         '\t\t\t<product name="Персик" protein="0.9" fat="0.1" carbohydrate="11.3" ccal="46" />\n' +
    //         '\t\t\t<product name="Слива" protein="0.8" fat="0.3" carbohydrate="9.6" ccal="42" />\n' +
    //         '\t\t\t<product name="Фейхоа" protein="1" fat="1" carbohydrate="11" ccal="49" />\n' +
    //         '\t\t\t<product name="Хурма" protein="0.5" fat="0" carbohydrate="16.8" ccal="53" />\n' +
    //         '\t\t\t<product name="Черешня" protein="1.1" fat="0.4" carbohydrate="11.5" ccal="50" />\n' +
    //         '\t\t\t<product name="Шелковица" protein="0.7" fat="0" carbohydrate="13.6" ccal="52" />\n' +
    //         '\t\t\t<product name="Яблоко" protein="0.4" fat="0.4" carbohydrate="11.8" ccal="45" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Хлебобулочные изделия">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Баранки" protein="16" fat="1" carbohydrate="70" ccal="336" />\n' +
    //         '\t\t\t<product name="Батон Нарезной" protein="7.5" fat="2.9" carbohydrate="50.9" ccal="264" />\n' +
    //         '\t\t\t<product name="Батон с пшеничными отрубями" protein="9.2" fat="2.8" carbohydrate="51.4" ccal="273" />\n' +
    //         '\t\t\t<product name="Бублик" protein="16" fat="1" carbohydrate="70" ccal="336" />\n' +
    //         '\t\t\t<product name="Булочка отрубная" protein="7.8" fat="1.8" carbohydrate="43.9" ccal="220" />\n' +
    //         '\t\t\t<product name="Булочки с кунжутом" protein="9.6" fat="4.2" carbohydrate="59.5" ccal="320" />\n' +
    //         '\t\t\t<product name="Ватрушка с творожной начинкой" protein="10" fat="12.1" carbohydrate="46.1" ccal="331" />\n' +
    //         '\t\t\t<product name="Лаваш армянский" protein="7.9" fat="1" carbohydrate="47.6" ccal="236" />\n' +
    //         '\t\t\t<product name="Лепёшка ржаная" protein="8" fat="18.3" carbohydrate="44.2" ccal="376" />\n' +
    //         '\t\t\t<product name="Сушки маковые" protein="11.3" fat="4.4" carbohydrate="70.5" ccal="372" />\n' +
    //         '\t\t\t<product name="Соломка сладкая" protein="9.4" fat="6.1" carbohydrate="69.5" ccal="373" />\n' +
    //         '                        <product name="Соломка соленая" protein="10.3" fat="4.8" carbohydrate="74.2" ccal="375" />\n' +
    //         '                        <product name="Хлеб Бородинский" protein="6.9" fat="1.3" carbohydrate="40.9" ccal="208" />\n' +
    //         '\t\t\t<product name="Хлеб Дарницкий" protein="6.6" fat="1.1" carbohydrate="41" ccal="206" />\n' +
    //         '\t\t\t<product name="Хлеб многозерновой" protein="7.6" fat="2.6" carbohydrate="41.6" ccal="225" />\n' +
    //         '\t\t\t<product name="Хлеб пшенично-ржаной" protein="8.1" fat="3.4" carbohydrate="42.2" ccal="222" />\n' +
    //         '\t\t\t<product name="Хлеб Пшеничный" protein="8.1" fat="1" carbohydrate="48.8" ccal="242" />\n' +
    //         '\t\t\t<product name="Хлеб Ржаной" protein="6.6" fat="1.2" carbohydrate="34.2" ccal="165" />\n' +
    //         '\t\t\t<product name="Хлеб ржаной с цельным зерном" protein="7.8" fat="1" carbohydrate="39.6" ccal="198" />\n' +
    //         '\t\t\t<product name="Хлебцы вафельные" protein="11.4" fat="3.5" carbohydrate="70.9" ccal="368" />\n' +
    //         '\t\t\t<product name="Хлебцы ржаные" protein="11" fat="2.7" carbohydrate="58" ccal="310" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Ягоды">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Барбарис" protein="0" fat="0" carbohydrate="7.9" ccal="29" />\n' +
    //         '\t\t\t<product name="Боярышник" protein="0" fat="0" carbohydrate="14" ccal="52" />\n' +
    //         '\t\t\t<product name="Брусника" protein="0.7" fat="0.5" carbohydrate="9.6" ccal="43" />\n' +
    //         '\t\t\t<product name="Виноград" protein="0.6" fat="0.2" carbohydrate="16.8" ccal="65" />\n' +
    //         '\t\t\t<product name="Голубика" protein="1" fat="0" carbohydrate="8.2" ccal="35" />\n' +
    //         '\t\t\t<product name="Ежевика" protein="2" fat="0" carbohydrate="6.4" ccal="31" />\n' +
    //         '\t\t\t<product name="Земляника" protein="0.8" fat="0.4" carbohydrate="11.2" ccal="34" />\n' +
    //         '\t\t\t<product name="Калина" protein="0" fat="0" carbohydrate="7" ccal="26" />\n' +
    //         '\t\t\t<product name="Клубника" protein="0.6" fat="0.4" carbohydrate="7" ccal="30" />\n' +
    //         '\t\t\t<product name="Клюква" protein="0.5" fat="0" carbohydrate="6.8" ccal="26" />\n' +
    //         '\t\t\t<product name="Крыжовник" protein="0.7" fat="0.2" carbohydrate="12" ccal="43" />\n' +
    //         '\t\t\t<product name="Лимонник" protein="1" fat="0" carbohydrate="1.9" ccal="11" />\n' +
    //         '\t\t\t<product name="Малина" protein="0.8" fat="0.3" carbohydrate="14.1" ccal="42" />\n' +
    //         '\t\t\t<product name="Морошка" protein="0.8" fat="0" carbohydrate="9.8" ccal="28" />\n' +
    //         '\t\t\t<product name="Облепиха" protein="0.9" fat="2.5" carbohydrate="10.2" ccal="52" />\n' +
    //         '\t\t\t<product name="Рябина" protein="1.4" fat="0.1" carbohydrate="11.8" ccal="43" />\n' +
    //         '\t\t\t<product name="Смородина белая" protein="0.3" fat="0" carbohydrate="11.7" ccal="38" />\n' +
    //         '\t\t\t<product name="Смородина красная" protein="0.6" fat="0.2" carbohydrate="11" ccal="39" />\n' +
    //         '\t\t\t<product name="Смородина чёрная" protein="1" fat="0.2" carbohydrate="11.5" ccal="38" />\n' +
    //         '\t\t\t<product name="Черника" protein="1.1" fat="0.6" carbohydrate="11" ccal="44" />\n' +
    //         '\t\t\t<product name="Черноплодная рябина" protein="1.5" fat="0.1" carbohydrate="13.6" ccal="52" />\n' +
    //         '\t\t\t<product name="Шиповник" protein="1.6" fat="0" carbohydrate="14" ccal="51" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '\t<category name="Яйца">\n' +
    //         '\t\t<products>\n' +
    //         '\t\t\t<product name="Омлет" protein="9.6" fat="15.4" carbohydrate="1.9" ccal="184" />\n' +
    //         '\t\t\t<product name="Омлет из яичного порошка" protein="10.3" fat="17" carbohydrate="1.6" ccal="200" />\n' +
    //         '\t\t\t<product name="Омлет с сыром" protein="16.3" fat="29.7" carbohydrate="2.6" ccal="342" />\n' +
    //         '\t\t\t<product name="Омлет со взбитыми сливками" protein="6.4" fat="14.8" carbohydrate="26.2" ccal="257" />\n' +
    //         '\t\t\t<product name="Яичница глазунья" protein="15.9" fat="19.3" carbohydrate="1" ccal="241" />\n' +
    //         '\t\t\t<product name="Яичный порошок" protein="46" fat="37.3" carbohydrate="4.5" ccal="542" />\n' +
    //         '\t\t\t<product name="Яйцо индюшачье" protein="13.1" fat="11.7" carbohydrate="0.7" ccal="165" />\n' +
    //         '\t\t\t<product name="Яйцо куриное" protein="12.7" fat="10.9" carbohydrate="0.7" ccal="157" />\n' +
    //         '\t\t\t<product name="Яйцо куриное (белок сухой)" protein="73.3" fat="1.8" carbohydrate="7" ccal="336" />\n' +
    //         '\t\t\t<product name="Яйцо куриное (белок)" protein="11.1" fat="0" carbohydrate="0" ccal="44" />\n' +
    //         '\t\t\t<product name="Яйцо куриное (вареное вкрутую)" protein="12.9" fat="11.6" carbohydrate="0.8" ccal="160" />\n' +
    //         '\t\t\t<product name="Яйцо куриное (вареное всмятку)" protein="12.8" fat="11.6" carbohydrate="0.8" ccal="159" />\n' +
    //         '\t\t\t<product name="Яйцо куриное (желток сухой)" protein="34.2" fat="52.2" carbohydrate="4.4" ccal="623" />\n' +
    //         '\t\t\t<product name="Яйцо куриное (желток)" protein="16.2" fat="31.2" carbohydrate="1" ccal="352" />\n' +
    //         '\t\t\t<product name="Яйцо перепелиное" protein="11.9" fat="13.1" carbohydrate="0.6" ccal="168" />\n' +
    //         '\t\t\t<product name="Яйцо страусиное" protein="12.2" fat="11.7" carbohydrate="0.7" ccal="118" />\n' +
    //         '\t\t\t<product name="Яйцо утиное" protein="13.3" fat="14.5" carbohydrate="0.1" ccal="185" />\n' +
    //         '\t\t</products>\n' +
    //         '\t</category>\n' +
    //         '</categories>\n';
    // }
    // gxml = xml;
    // //
    // // var date = new Date();
    // // $('#date').val(date.getDate() + '/' + parseInt(date.getMonth() + 1) + '/' + date.getFullYear());
    // //
    // // var table = localStorage.getItem('t01');
    // // if(null !== table) {
    // //     $("#t01").html(table);
    // // }
    // // // addNewRow();
    // // calculator.init('t01', xml);
    // // if(null === table) {
    // //     calculator.addNewRow();
    // // }
    // //
    // // calculator.getCategories();
    //
    // start('t01', xml);
    // // start('t02', xml);
    // // start('t03', xml);
    // // start('t04', xml);
    // // start('t05', xml);
    // // start('t06', xml);
    //
    // $("body").on("change", "input, select", function () {
    //     calculator.save();
    // });


});

function start(id, xml) {
    var table = localStorage.getItem(id);
    if(null !== table) {
        $("#" + id).html(table);
    }
    // addNewRow();
    calculator.init(id, xml);

}
// function getCategories() {
//     var lastid = getLastRowId();
//     var xml = gxml;
//     $(xml).find('category').each(function() {
//         var $category = $(this);
//         var catname = $category.attr('name');
//         $('#' + calculator.id + 'category' + lastid).append($('<option value="' + catname + '">' + catname + '</option>'));
//     });
//     // $('#' + calculator.id + 'category' + lastid).bind('change', function() {
//     //     var category_name = $(this).val();
//     //     var id = $(this).attr('id').substring(8);
//     //     getProducts(category_name, id);
//     // });
//
//     $("body").on("change", '#' + calculator.id + 'category' + lastid, function () {
//         var category_name = $(this).val();
//         var id = $(this).attr('id').substring(8);
//         getProducts(category_name, id);
//     });
// };


// function getProducts(category, id) {
//     var xml = gxml;
//     $('#' + calculator.id + 'product' + id).empty().append('<option value="-1">(Р’С‹Р±РµСЂРёС‚Рµ РёР· СЃРїРёСЃРєР°)</option>');
//     $(xml).find('category[name="' + category + '"] product').each(function() {
//         var $product = $(this);
//         var prodname = $product.attr('name');
//         $('#' + calculator.id + 'product' + id).append('<option value="' + prodname + '">' + prodname + '</option>');
//     });
//     $('#' + calculator.id + 'product' + id).bind('change', function() {
//         countInfo(id);
//         if($('#row' + (parseInt(id) + 1)).length == 0) {
//             addNewRow();
//             getCategories();
//         }
//     });
//     $('#' + calculator.id + 'count' + id).bind('keyup', function() {
//         countInfo(id);
//     });
// }
//
// // $("body").on("change",'.product', function () {
// //     var id = $(this).val();
// //     countInfo(id);
// //     $("body").on("change",'#' + calculator.id + 'product' + id, function () {
// //         countInfo(id);
// //         if($('#row' + (parseInt(id) + 1)).length == 0) {
// //             addNewRow();
// //             getCategories();
// //         }
// //     });
//     // if($('#row' + (parseInt(id) + 1)).length == 0) {
//     //     addNewRow();
//     //     getCategories();
//     // }
// // });
// $("tr").each(function() {
//     var el = $(this).attr('id');
//     if(undefined !== el && el !== 'total') {
//         console.log(el);
//         var id = el.substr(3);
//         $("body").on("change",'#' + calculator.id + 'product' + id, function () {
//             countInfo(id);
//             if($('#row' + (parseInt(id) + 1)).length == 0) {
//                 addNewRow();
//                 getCategories();
//             }
//         });
//         $("body").on("keyup", '#' + calculator.id + 'count' + id, function () {
//             countInfo(id);
//         });
//         $("body").on("change", '#' + calculator.id + 'category' + id, function () {
//             var category_name = $(this).val();
//             var id = $(this).attr('id').substring(8);
//             getProducts(category_name, id);
//         });
//         // $("body").on("change", '#' + calculator.id + 'category' + lastid, function () {
//         //     var category_name = $(this).val();
//         //     var id = $(this).attr('id').substring(8);
//         //     getProducts(category_name, id);
//         // });
//     }
//
// })
//
// $("body").on("keyup", '.count', function () {
//     var id = $(this).attr('id');
//     countInfo(id);
// });
//
// function countInfo(id) {
//     var xml = gxml;
//     var count = $('#' + calculator.id + 'count' + id).val();
//     var catname = $('#' + calculator.id + 'category' + id + ' option:selected').val();
//     var prodname = $('#' + calculator.id + 'product' + id + ' option:selected').val();
//     var product = $(xml).find('category[name="' + catname + '"] product[name="' + prodname + '"]');
//
//     if (catname == -1 || prodname == -1)
//         return;
//
//     $('#' + calculator.id + 'protein' + id).val((product.attr('protein') * count / 100).toFixed(1));
//     $('#' + calculator.id + 'fat' + id).val((product.attr('fat') * count / 100).toFixed(1));
//     $('#' + calculator.id + 'carbohydrate' + id).val((product.attr('carbohydrate') * count / 100).toFixed(1));
//     $('#' + calculator.id + 'strange' + id).val((product.attr('ccal') * count / 100).toFixed(1));
//
//     var totcount = 0;
//     var totprotein = 0;
//     var totfat = 0;
//     var totcarbohydrate = 0;
//     var totstrange = 0;
//     $('#' + calculator.id + ' input[class=count]').each(function() {
//         if($(this).val().length != 0)
//             totcount += parseFloat($(this).val());
//     });
//     $('#' + calculator.id + ' input[class=protein]').each(function() {
//         if($(this).val().length != 0)
//             totprotein += parseFloat($(this).val());
//     });
//     $('#' + calculator.id + ' input[class=fat]').each(function() {
//         if($(this).val().length != 0)
//             totfat += parseFloat($(this).val());
//     });
//     $('#' + calculator.id + ' input[class=carbohydrate]').each(function() {
//         if($(this).val().length != 0)
//             totcarbohydrate += parseFloat($(this).val());
//     });
//     $('#' + calculator.id + ' input[class=ccal]').each(function() {
//         if($(this).val().length != 0)
//             totstrange += parseFloat($(this).val());
//     });
//
//     $('#' + calculator.id + 'total #totcount').val(totcount.toFixed(1));
//     $('#' + calculator.id + 'total #totprotein').val(totprotein.toFixed(1));
//     $('#' + calculator.id + 'total #totfat').val(totfat.toFixed(1));
//     $('#' + calculator.id + 'total #totcarbohydrate').val(totcarbohydrate.toFixed(1));
//     $('#' + calculator.id + 'total #totccal').val(totstrange.toFixed(1));
// }
//
// function getLastRowId() {
//     return $('#' + calculator.id + ' .row:last').attr('id').substring(3);
// }
//
// function addNewRow() {
//     var lastRow = $('#' + calculator.id + ' tr:last');
//
//     $('#' + calculator.id + ' tr:last').remove();
//
//     var lastid = getLastRowId();
//     var id = ++lastid;
//     var newRow = '<tr class="row"><td><select class="category"><option value="-1">(Выберите 1)</option></select></td><td><select class="product"/></td><td><input type="text" class="count" /></td><td><input type="text" class="protein" readonly="readonly" /></td><td><input type="text" class="fat" readonly="readonly" /></td><td><input type="text" class="carbohydrate" readonly="readonly" /></td><td><input type="text" class="ccal" readonly="readonly" /></td></tr>';
//
//     $('#' + calculator.id + '').append(newRow);
//     var count = localStorage.getItem('count');
//     if(null === count) {
//         count = 0;
//     }
//     $('#' + calculator.id + ' tr:last').attr('id', 'row' + id);
//     $('#' + calculator.id + ' tr:last td:eq(0) select').attr('id', 'category' + id);
//     $('#' + calculator.id + ' tr:last td:eq(1) select').attr('id', 'product' + id).attr('name', 'product' + id);
//     $('#' + calculator.id + ' tr:last td:eq(2) input').attr('id', 'count' + id).attr('name', 'count' + id);
//     $('#' + calculator.id + ' tr:last td:eq(3) input').attr('id', 'protein' + id).attr('name', 'protein' + id).attr('value', 0);
//     $('#' + calculator.id + ' tr:last td:eq(4) input').attr('id', 'fat' + id).attr('name', 'fat' + id).attr('value', 0);
//     $('#' + calculator.id + ' tr:last td:eq(5) input').attr('id', 'carbohydrate' + id).attr('name', 'carbohydrate' + id).attr('value', 0);
//     $('#' + calculator.id + ' tr:last td:eq(6) input').attr('id', 'strange' + id).attr('name', 'strange' + id).attr('value', 0);
//     $('#' + calculator.id + '').append(lastRow);
// }
//
//
// function save() {
//     $("option:selected").attr('selected', 'selected');
//     $('input').each(function() {
//         $(this).attr('value', $(this).val());
//     })
//     var html = $("#tblcalc").html();
//     localStorage.setItem('tblcalc', html);
// }
//
// function calc_print(){
//     // $("body :not(.calc)").each(function(){
//     // $(this).hide();
//     // });
//     window.print();
// }