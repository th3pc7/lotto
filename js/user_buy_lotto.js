MainProg.config.Page_modules["user_buy_lotto"] = new PageJS(MainProg, function (Main) {
    // this function run first time on load this file success.
    Array.prototype.getUnique = function () {
        var u = {}, a = [];
        for (var i = 0, l = this.length; i < l; ++i) {
            if (u.hasOwnProperty(this[i])) {
                continue;
            }
            a.push(this[i]);
            u[this[i]] = 1;
        }
        return a;
    }
    Number.prototype.formatMoney = function (c, d, t) {
        var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    }
    Main.on("change","#picker-date-bill2", date_picker_bill2);
}, function (Main) {
    // this function run on after open this page.
    var mainprog = new MainProgramBuyLotto({
        config_form_buy_lotto: {
            init_div_id: "tb-buy",
            prefix_row_id: "row_b_lotto_",
            prefix_input_class: "input_b_lotto_",
            head_fields: ["เลข", "บน", "โต๊ด", "ล่าง", "กลับบน", "กลับล่าง", "รวม"],
            min_price: 10,
            fields_read_only: [6],
            field_sum_row: 6,
            subfix_sum_field: " บาท",
            field_multiple_number: [4, 5],
            field_width: "80px",
            custom_field_width_by_selector: {".show_sum": "110px", "#head-span-group .head-span:last-child": "110px"},
            numLengthAndDisabledField: {
                "1": [1, 3],
                "2": [1, 2, 3, 4, 5],
                "3": [1, 2, 3, 4, 5]
            },
            numLengthColor: {
                "1": "#ffc7c7",
                "2": "#fff4a7",
                "3": "#97ff97"
            },
            start_num_row: 10,
            num_add_row_btn: 5,
            input_step: 50
        },
        sender: {
            url_server: "../actions/"
        }
    });
}, function (Main) {
    // this function run on befor close this page.
});


function MainProgramBuyLotto(main_config) {
    var me = this;
    this.form = new FormBuyLotto(me, main_config.config_form_buy_lotto);
    this.sendDataToServer = function (bill_data) {
        if (bill_data.data.length === 0) {
            alert("ไม่พบข้อมูลที่จะซื้อ");
            return;
        }
        var str = "";
        for (var i = 0; i < bill_data.data.length; i++) {
            if (bill_data.data[i].price < main_config.config_form_buy_lotto.min_price) {
                continue;
            }
            str += "," + bill_data.data[i].lottoNumber + "," + bill_data.data[i].type + "," + bill_data.data[i].price;
        }
        str = encodeURIComponent(bill_data.note) + str;
        me.form.disabledBTN();
        $.post(main_config.sender.url_server, {Action: "buy_lotto", data: str}, function (data) {
            if (data === "pass") {
                alert("ทำรายการสำเร็จ");
                me.form.clear();
                me.form.enableBTN();
                date_picker_bill2();
            }
            else {
                alert(data);
                me.form.enableBTN();
            }
        }).fail(function () {
            alert("เกิดข้อผิดพลาด !!! ระหว่างการส่งข้อมูล");
            me.form.enableBTN();
        });
    }
}

function FormBuyLotto(main, config) {
    var me = this;
    this.elm = null;
    this.rows = [];
    this.defaultNumAddRow = config.num_add_row_btn;
    me.addRow = addRowBuyLotto;
    me.setUI = setOutPut;
    this.clear = clearAllData;
    this.keepData = keepDataForm;
    this.disabledBTN = function () {
        me.btnAddRow.disabled();
        me.btnClear.disabled();
        me.btnConfirm.disabled();
    }
    this.enableBTN = function () {
        me.btnAddRow.enable();
        me.btnClear.enable();
        me.btnConfirm.enable();
    }
    this.checkSome = checkSomeNumber;

    function keepDataForm() {
        var data = [];
        for (var i = 0; i < me.rows.length; i++) {
            if (me.rows[i].inputs[0].elm.value.length > 0) {
                var lottoNumber = me.rows[i].inputs[0].elm.value;
                var lottoNumberLength = lottoNumber.length;
                for (var ii = 1; ii < config.head_fields.length; ii++) {
                    if (!isNaN(parseInt(me.rows[i].inputs[ii].elm.value))) {
                        var price = parseInt(me.rows[i].inputs[ii].elm.value);
                        if (price < config.min_price) {

                            alert("แถวที่ " + (i + 1) + " ใส่ราคาไม่ถูกต้อง ราคาควรใส่ไม่ต่ำกว่า " + config.min_price + " บาท");
                            me.rows[i].elm.style.backgroundColor = "red";

                            return;
                        }
                        switch (ii) {
                            case 1:
                                if (lottoNumberLength === 1) {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 7
                                    });
                                }
                                else if (lottoNumberLength === 2) {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 4
                                    });
                                }
                                else {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 1
                                    });
                                }
                                break;
                            case 2:
                                if (lottoNumberLength === 2) {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 6
                                    });
                                }
                                else {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 3
                                    });
                                }
                                break;
                            case 3:
                                if (lottoNumberLength === 1) {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 8
                                    });
                                }
                                else if (lottoNumberLength === 2) {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 5
                                    });
                                }
                                else {
                                    data.push({
                                        lottoNumber: lottoNumber,
                                        price: price,
                                        type: 2
                                    });
                                }
                                break;
                            case 4:
                                if (lottoNumberLength === 2) {
                                    data.push({
                                        lottoNumber: lottoNumber[0] + lottoNumber[1],
                                        price: price,
                                        type: 4
                                    });
                                    if (lottoNumber[0] != lottoNumber[1]) {
                                        data.push({
                                            lottoNumber: lottoNumber[1] + lottoNumber[0],
                                            price: price,
                                            type: 4
                                        });
                                    }
                                }
                                else {
                                    var checkSome = checkSomeNumber(lottoNumber);
                                    if (checkSome === false) {
                                        data.push({
                                            lottoNumber: lottoNumber[0] + lottoNumber[1] + lottoNumber[2],
                                            price: price,
                                            type: 1
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[0] + lottoNumber[2] + lottoNumber[1],
                                            price: price,
                                            type: 1
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[1] + lottoNumber[0] + lottoNumber[2],
                                            price: price,
                                            type: 1
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[1] + lottoNumber[2] + lottoNumber[0],
                                            price: price,
                                            type: 1
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[2] + lottoNumber[1] + lottoNumber[0],
                                            price: price,
                                            type: 1
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[2] + lottoNumber[0] + lottoNumber[1],
                                            price: price,
                                            type: 1
                                        });
                                    }
                                    else if (checkSome.length === 2) {
                                        data.push({
                                            lottoNumber: checkSome[0] + checkSome[1] + checkSome[1],
                                            price: price,
                                            type: 1
                                        });
                                        data.push({
                                            lottoNumber: checkSome[1] + checkSome[0] + checkSome[1],
                                            price: price,
                                            type: 1
                                        });
                                        data.push({
                                            lottoNumber: checkSome[1] + checkSome[1] + checkSome[0],
                                            price: price,
                                            type: 1
                                        });
                                    }
                                    else {
                                        data.push({
                                            lottoNumber: lottoNumber,
                                            price: price,
                                            type: 1
                                        });
                                    }
                                }
                                break;
                            case 5:
                                if (lottoNumberLength === 2) {
                                    data.push({
                                        lottoNumber: lottoNumber[0] + lottoNumber[1],
                                        price: price,
                                        type: 5
                                    });
                                    if (lottoNumber[0] != lottoNumber[1]) {
                                        data.push({
                                            lottoNumber: lottoNumber[1] + lottoNumber[0],
                                            price: price,
                                            type: 5
                                        });
                                    }
                                }
                                else {
                                    var checkSome = checkSomeNumber(lottoNumber);
                                    if (checkSome === false) {
                                        data.push({
                                            lottoNumber: lottoNumber[0] + lottoNumber[1] + lottoNumber[2],
                                            price: price,
                                            type: 2
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[0] + lottoNumber[2] + lottoNumber[1],
                                            price: price,
                                            type: 2
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[1] + lottoNumber[0] + lottoNumber[2],
                                            price: price,
                                            type: 2
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[1] + lottoNumber[2] + lottoNumber[0],
                                            price: price,
                                            type: 2
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[2] + lottoNumber[1] + lottoNumber[0],
                                            price: price,
                                            type: 2
                                        });
                                        data.push({
                                            lottoNumber: lottoNumber[2] + lottoNumber[0] + lottoNumber[1],
                                            price: price,
                                            type: 2
                                        });
                                    }
                                    else if (checkSome.length === 2) {
                                        data.push({
                                            lottoNumber: checkSome[0] + checkSome[1] + checkSome[1],
                                            price: price,
                                            type: 2
                                        });
                                        data.push({
                                            lottoNumber: checkSome[1] + checkSome[0] + checkSome[1],
                                            price: price,
                                            type: 2
                                        });
                                        data.push({
                                            lottoNumber: checkSome[1] + checkSome[1] + checkSome[0],
                                            price: price,
                                            type: 2
                                        });
                                    }
                                    else {
                                        data.push({
                                            lottoNumber: lottoNumber,
                                            price: price,
                                            type: 2
                                        });
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
        var bill_data = {
            note: document.getElementById("note-bill-input").value,
            data: data
        };
        main.sendDataToServer(bill_data);
    }

    function checkSomeNumber(lottoNumber) {
        var ret = false;
        if (lottoNumber[0] === lottoNumber[1] && lottoNumber[0] === lottoNumber[2]) {
            ret = lottoNumber[0];
        }
        else if (lottoNumber[0] === lottoNumber[1]) {
            ret = lottoNumber[2] + lottoNumber[0];
        }
        else if (lottoNumber[0] === lottoNumber[2]) {
            ret = lottoNumber[1] + lottoNumber[0];
        }
        else if (lottoNumber[1] === lottoNumber[2]) {
            ret = lottoNumber[0] + lottoNumber[1];
        }
        return ret;
    }

    function clearAllData() {
        me.elm = null;
        me.rows = [];
        $("#" + config.init_div_id).html("");
        init(config);
    }

    function setOutPut() {
        $(".tb-set-width").css({width: config.field_width});
        for (var query in config.custom_field_width_by_selector) {
            $(query).css({width: config.custom_field_width_by_selector[query]});
        }
    }

    function addRowBuyLotto() {
        me.rows.push(new RowBuyLotto(main, config, me.rows.length));
    }

    function focusNextRow(nowRowNumber) {
        if (nowRowNumber + 1 === me.rows.length) {
            addRowBuyLotto();
            me.setUI();
        }
        me.rows[nowRowNumber + 1].inputs[0].elm.focus();
    }

    function init(config) {
        var strHTML = "";
        strHTML += '<div id="head-span-group">';
        strHTML += '<span style="width:40px;display:inline-block;text-align:right;">&nbsp;</span>';
        for (var i = 0; i < config.head_fields.length; i++) {
            strHTML += '<div class="head-span tb-set-width">' + config.head_fields[i] + '</div>';
        }
        strHTML += '</div>';
        $("#" + config.init_div_id).html(strHTML);

        strHTML = '<div id="row-buy-group"></div><div id="tb-btn-group"><span style="width:40px;display:inline-block;text-align:right;">&nbsp;</span></div><div id="note-bill"><span style="width:40px;display:inline-block;text-align:right;">&nbsp;</span>หมายเหตุ : <div class="input-control text required"> <input id="note-bill-input" type="text"></div></div>';
        $("#" + config.init_div_id).append(strHTML);
        me.elm = document.getElementById("row-buy-group");
        for (var i = 0; i < config.start_num_row; i++) {
            me.addRow();
        }
        setOutPut();
        me.btnAddRow = new ButtonAddRow(me);
        me.btnClear = new ButtonClear(me);
        me.btnConfirm = new ButtonConfirm(me);
        me.elm.onkeyup = function (events) {
            var rowNumber = events.target.parentElement.id.replace(config.prefix_row_id, "");
            var inputNumber = events.target.classList[2].replace(config.prefix_input_class, "");
            if (events.keyCode === 13) {
                focusNextRow(parseInt(rowNumber));
            }
        }
    }

    init(config);
}

function ButtonAddRow(tbForm) {
    var me = this;
    this.form = tbForm;
    this.btn = document.createElement("button");
    var text = document.createTextNode("เพิ่มช่อง");
    //this.btn.className("button");
    // this.btn.setAttribute("class", "button primary");
    this.btn.appendChild(text);


    this.disabled = function () {
        me.btn.disabled = true;
    }
    this.enable = function () {
        me.btn.disabled = false;
    }
    this.btn.onclick = function () {
        for (var i = 0; i < me.form.defaultNumAddRow; i++) {
            me.form.addRow();
        }
        me.form.setUI();
    }

    document.getElementById("tb-btn-group").appendChild(this.btn);
}

function ButtonConfirm(tbForm) {
    var me = this;
    this.form = tbForm;
    this.btn = document.createElement("button");
    var text = document.createTextNode("บันทึก");
    // this.btn.setAttribute("class", "button success");
    this.btn.appendChild(text);

    this.disabled = function () {
        me.btn.disabled = true;
    }
    this.enable = function () {
        me.btn.disabled = false;
    }

    this.btn.onclick = function () {
        me.form.keepData();
    }

    document.getElementById("tb-btn-group").appendChild(this.btn);
}

function ButtonClear(tbForm) {
    var me = this;
    this.form = tbForm;
    this.btn = document.createElement("button");
    var text = document.createTextNode("ล้างข้อมูล");
    // this.btn.setAttribute("class", "button danger");
    this.btn.appendChild(text);

    this.disabled = function () {
        me.btn.disabled = true;
    }
    this.enable = function () {
        me.btn.disabled = false;
    }

    this.btn.onclick = function () {
        me.form.clear();
    }

    document.getElementById("tb-btn-group").appendChild(this.btn);
}

function RowBuyLotto(main, config, numbers) {
    var me = this;
    this.inputs = [];
    this.elm = null;
    this.setInputsFormLottoNumber = setDisabledInputs;
    this.setBgColor = setBackgroundColor;
    this.sumRowPrice = sumRowPrice;

    function sumRowPrice() {
        var sum = 0;
        me.inputs[config.field_sum_row].elm.innerHTML = sum;
        for (var i = 1; i < me.inputs.length; i++) {
            var now = parseInt(me.inputs[i].elm.value);
            if (!isNaN(now)) {
                var multi = 1;
                if (config.field_multiple_number.indexOf(i) !== -1) {
                    multi = get_multi(me.inputs[0].elm.value);
                }
                sum += now * multi;
            }
        }
        if (sum === 0) {
            if (me.inputs[0].elm.value !== "") {
                me.inputs[config.field_sum_row].elm.innerHTML = "ไม่ได้ใส่ราคา";
            }
            else {
                me.inputs[config.field_sum_row].elm.innerHTML = "";
            }
        }
        else {
            me.inputs[config.field_sum_row].elm.innerHTML = sum.formatMoney(0, ".", ",") + config.subfix_sum_field;
        }
    }

    function get_multi(numbers) {
        var same = 2;
        var text = numbers.split("");
        text = text.getUnique();
        same = numbers.length - text.length + 1;
        return factorial(numbers.length) / factorial(same);
    }

    function factorial(num) {
        if (num < 0) {
            return -1;
        }
        else if (num == 0) {
            return 1;
        }
        var tmp = num;
        while (num-- > 2) {
            tmp *= num;
        }
        return tmp;
    }

    function setBackgroundColor() {
        var typeLength = me.inputs[0].elm.value.length;
        if (typeLength === 0) {
            me.elm.style.backgroundColor = "";
        }
        else {
            me.elm.style.backgroundColor = config.numLengthColor["" + typeLength];
        }
    }

    function setDisabledInputs() {
        switch (me.inputs[0].elm.value.length) {
            case 0:
                for (var i = 1; i < config.head_fields.length; i++) {
                    if (config.fields_read_only.indexOf(i) !== -1) {
                        continue;
                    }
                    me.inputs[i].elm.value = "";
                    me.inputs[i].elm.disabled = true;
                }
                break;
            default:
                for (var i = 1; i < config.head_fields.length; i++) {
                    if (config.fields_read_only.indexOf(i) !== -1) {
                        continue;
                    }
                    if (config.numLengthAndDisabledField["" + me.inputs[0].elm.value.length].indexOf(i) === -1) {
                        me.inputs[i].elm.value = "";
                        me.inputs[i].elm.disabled = true;
                    } else {
                        me.inputs[i].elm.disabled = false;
                    }
                }
        }
    }

    function init(config, numbers) {
        var strHTML = '<div id="' + config.prefix_row_id + numbers + '" class="tb-row"></div>';
        $("#row-buy-group").append(strHTML);
        me.elm = document.getElementById(config.prefix_row_id + numbers);
        $("#" + config.prefix_row_id + numbers).html('<span style="width:40px;display:inline-block;text-align:right;">' + (numbers + 1) + '.&nbsp;</span>');
        for (var i = 0; i < config.head_fields.length; i++) {
            if (config.fields_read_only.indexOf(i) !== -1) {
                me.inputs.push(new InputBuyLotto(main, config, numbers, me.inputs.length, true));
            }
            else {
                me.inputs.push(new InputBuyLotto(main, config, numbers, me.inputs.length));
            }
        }
        setDisabledInputs();
    }

    init(config, numbers);
}

function InputBuyLotto(main, config, row_numbers, numbers, read_only=false) {
    var me = this;
    this.elm = null;

    function checkRowInput(setInput=true) {
        if (setInput) {
            setInputLottoNumber();
        }
        main.form.rows[row_numbers].setInputsFormLottoNumber();
        main.form.rows[row_numbers].setBgColor();
        sumRowPrice();
    }

    function sumRowPrice() {
        main.form.rows[row_numbers].sumRowPrice();
    }

    function setInputLottoNumber() {
        var lottoNumber = $.trim(me.elm.value);
        lottoNumber = lottoNumber.replace(/[^0-9\.]+/g, '');
        if (lottoNumber.length > 3) {
            lottoNumber = lottoNumber.slice(lottoNumber.length - 3, 4);
        }
        me.elm.value = lottoNumber;
    }

    function checkGold() {
        var lottoNumber = $.trim(me.elm.value);
        lottoNumber = lottoNumber.replace(/[^0-9\.]+/g, '');
        me.elm.value = lottoNumber;
        checkRowInput(false);
        sumRowPrice();
    }

    function init(config) {
        if (numbers === 0) {
            $("#" + config.prefix_row_id + row_numbers).append('<input class="tb-input tb-set-width ' + config.prefix_input_class + numbers + '" type="text">');
            me.elm = document.getElementById(config.prefix_row_id + row_numbers).getElementsByClassName(config.prefix_input_class + numbers)[0];
            me.elm.onkeyup = checkRowInput;
            me.elm.onchange = sumRowPrice;
        }
        else if (numbers === config.field_sum_row) {
            $("#" + config.prefix_row_id + row_numbers).append('<div class="tb-input tb-set-width ' + config.prefix_input_class + numbers + '" type="number" step="' + config.input_step + '" min="0"></div>');
            me.elm = document.getElementById(config.prefix_row_id + row_numbers).getElementsByClassName(config.prefix_input_class + numbers)[0];
            if (read_only === true) {
                me.elm.type = "text";
                me.elm.className += " show_sum";
                me.elm.style.textAlign = "right";
                me.elm.readOnly = true;
            }
            me.elm.onkeyup = checkGold;
            me.elm.onchange = sumRowPrice;
        }
        else {
            $("#" + config.prefix_row_id + row_numbers).append('<input class="tb-input tb-set-width ' + config.prefix_input_class + numbers + '" type="number" step="' + config.input_step + '" min="0">');
            me.elm = document.getElementById(config.prefix_row_id + row_numbers).getElementsByClassName(config.prefix_input_class + numbers)[0];
            me.elm.onkeyup = checkGold;
            me.elm.onchange = sumRowPrice;
        }
    }

    init(config);
}

function date_picker_bill2() {
    var cached = $("#paste-bill2").html();
    $("#paste-bill2").html("Loadding");
    $.post("../actions/", { Action: "loadBill", value: $("#picker-date-bill2").val() }, function (data) {
        $("#paste-bill2").html(data);
    }).fail(function () {
        alert("การโหลดข้อมูลใหม่ล้มเหลว");
        $("#paste-bill2").html(cached);
    });
}