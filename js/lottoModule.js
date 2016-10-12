function UserLottoClass(SysLotto, code, name, type, Agent = false) {
    var Me = this;

    this.code = code;
    this.name = name;
    this.type = type;
    this.Agent = Agent;
    this.Customer = [];
    this.Lotto = [];

    this.addLotto = add_lotto;

    function add_lotto(Lotto) {
        Me.Lotto.push(Lotto);
    }
    function init() {
        if (Me.Agent === false) { return; }
        Me.Agent.Customer[Me.code] = Me;
    }
    init();
}

function LottoClass(dataText, Sys_lotto) {
    var Me = this;

    this.dataText = dataText;
    this.Owner = null;
    this.code = null;
    this.numbers = null;
    this.type = null;
    this.bill = null;
    this.owner_key_forward_step = [];
    this.binggo = 0;
    this.Forward = [/* UserLotto.code : objForward, ... */];
    // var objForward = {
    //     Lotto : Me,
    //     Owner : UserLotto,
    //     Host : UserLotto,
    //     price : 0,
    //     discount : 0,
    //     reward : 0
    // };

    function init_forward(dataArray) {
        var ownerKey = dataArray.shift();
        var price = parseFloat(dataArray.shift());
        var discount = parseFloat(dataArray.shift());
        var reward = parseFloat(dataArray.shift());
        Me.Forward[ownerKey] = {
            Lotto: Me,
            Owner: Sys_lotto.UserArr[ownerKey],
            Host: Sys_lotto.UserArr[ownerKey].Agent,
            price: price,
            discount: discount,
            reward: reward
        }
        Me.owner_key_forward_step.push(ownerKey);
        Sys_lotto.UserArr[ownerKey].addLotto(Me);
        if (dataArray.length > 0 && ownerKey !== Sys_lotto.Config.main_user_code) { init_forward(dataArray); }
    }
    function init() {
        var dataArray = dataText.split(",");
        Me.Owner = Sys_lotto.UserArr[dataArray[4]];
        Me.code = dataArray.shift();
        Me.numbers = dataArray.shift();
        Me.type = dataArray.shift();
        Me.bill = dataArray.shift();
        init_forward(dataArray);
    }
    init();
}

function SysLottoClass(Config) {
    var Me = this;

    this.mainUserCode = null;
    this.UserArr = [];
    this.LottoArr = [];

    this.version = "1.5.4";
    this.Config = Config;
    this.Report = null;
    this.newUser = add_new_user;
    this.newLotto = add_new_lotto;
    this.newMapUser = paste_map_user;

    function paste_map_user(theMap) {
        theMap = theMap.split(",");
        var round = (theMap.length / Config.map_user_split_length) - 1;
        var paste = false;
        var last_user_code = false;
        for (var i = 0; i < round; i++) {
            var userCode = Config.prefix_user_code + theMap.shift();
            var userName = theMap.shift();
            if (userCode === Config.main_user_code) { paste = true; }
            if (paste === false) { continue; }
            if (typeof Me.UserArr[userCode] === "undefined") {
                add_new_user(userCode, userName, "agent", last_user_code);
            }
            last_user_code = userCode;
        }
        var userCode = Config.prefix_user_code + theMap.shift();
        var userName = theMap.shift();
        if (typeof Me.UserArr[userCode] === "undefined") { add_new_user(userCode, userName, "user", last_user_code); }
    }
    function add_new_user(code, name, type, agentCode) {
        if (agentCode === false) { Me.UserArr[code] = new UserLottoClass(Me, code, name, type, false); }
        else { Me.UserArr[code] = new UserLottoClass(Me, code, name, type, Me.UserArr[agentCode]); }
    }
    function add_new_lotto(lottotext) {
        var arrLottoText = lottotext.split(",");
        Me.LottoArr[arrLottoText[0]] = new LottoClass(lottotext, Me);
    }
    function init() {
        Me.Report = new Config.ReportClass(Me);
    }
    init();
}

function ReportClass(Main) {
    var Me = this;

    this.version = "1.6.4";
    this.getClearData = get_clear_data_from_agent_and_customer;
    this.getSumBuyData = get_sum_buy_lotto_data_from_user_code;
    this.getCustomerObjAll = get_list_customer_obj_from_agent_code;
    this.getCustomerCodeAll = get_list_customer_code_from_agent_code;
    this.getHtmlClearData = get_replace_all_clear_data;

    function get_clear_data_from_agent_and_customer(agent_code, customer_code) {
        var Ret = {
            Company: {
                sumAccept: 0,
                sumDiscount: 0,
                sumReward: 0,
                sumAll: 0
            },
            Agent: {
                sumAccept: 0,
                sumDiscount: 0,
                sumReward: 0,
                sumAll: 0
            },
            Customer: {
                name: Main.UserArr[customer_code].name,
                sumPrice: 0,
                sumDiscount: 0,
                sumReward: 0,
                sumAll: 0
            }
        };
        for (var i = 0; i < Main.UserArr[customer_code].Lotto.length; i++) {
            var TheLotto = Main.UserArr[customer_code].Lotto[i];
            if (typeof TheLotto.Forward[agent_code] === "undefined") { continue; }
            var customer_discount = TheLotto.Forward[customer_code].price * TheLotto.Forward[customer_code].discount / 100;
            var company_discount = TheLotto.Forward[agent_code].price * TheLotto.Forward[agent_code].discount / 100;
            var customer_reward = TheLotto.binggo * TheLotto.Forward[customer_code].price * TheLotto.Forward[customer_code].reward;
            var company_reward = TheLotto.binggo * TheLotto.Forward[agent_code].price * TheLotto.Forward[agent_code].reward;
            Ret.Customer.sumPrice -= TheLotto.Forward[customer_code].price;
            Ret.Customer.sumDiscount += customer_discount;
            Ret.Customer.sumReward += customer_reward;
            Ret.Company.sumAccept += TheLotto.Forward[agent_code].price;
            Ret.Company.sumDiscount -= company_discount;
            Ret.Company.sumReward -= company_reward;
            Ret.Agent.sumAccept += TheLotto.Forward[customer_code].price - TheLotto.Forward[agent_code].price;
            Ret.Agent.sumDiscount += company_discount - customer_discount;
            Ret.Agent.sumReward += company_reward - customer_reward;
        }
        Ret.Customer.sumAll = Ret.Customer.sumPrice + Ret.Customer.sumDiscount + Ret.Customer.sumReward;
        Ret.Company.sumAll = Ret.Company.sumAccept + Ret.Company.sumDiscount + Ret.Company.sumReward;
        Ret.Agent.sumAll = Ret.Agent.sumAccept + Ret.Agent.sumDiscount + Ret.Agent.sumReward;
        return Ret;
    }
    function get_sum_buy_lotto_data_from_user_code(user_code) {
        var sumPrice = 0;
        var sumDiscount = 0;
        var sumReward = 0;
        for (var i = 0; i < Main.UserArr[user_code].Lotto.length; i++) {
            var TheLotto = Main.UserArr[user_code].Lotto[i];
            sumPrice += TheLotto.Forward[user_code].price;
            sumDiscount += TheLotto.Forward[user_code].price * TheLotto.Forward[user_code].discount / 100;
            sumReward += TheLotto.binggo * TheLotto.Forward[user_code].price * TheLotto.Forward[user_code].reward;
        }
        return {
            sumPrice: sumPrice,
            sumDiscount: sumDiscount,
            sumReward: sumReward
        };
    }
    function get_list_customer_obj_from_agent_code(agent_code) {
        var arr = [];
        for (var code in Main.UserArr[agent_code].Customer) { arr.push(Main.UserArr[code]); }
        return arr;
    }
    function get_list_customer_code_from_agent_code(agent_code) {
        var arr = [];
        if (typeof Main.UserArr[agent_code] === "undefined") { return arr; }
        for (var code in Main.UserArr[agent_code].Customer) { arr.push(code); }
        return arr;
    }
    function get_replace_all_clear_data(html, ClearData) {
        html = html.replace(new RegExp("{customer-name}", "g"), ClearData.Customer.name);
        html = html.replace(new RegExp("{customer-buy}", "g"), ClearData.Customer.sumPrice.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{customer-discount}", "g"), ClearData.Customer.sumDiscount.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{customer-reward}", "g"), ClearData.Customer.sumReward.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{customer-sum}", "g"), ClearData.Customer.sumAll.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{agent-accept}", "g"), ClearData.Agent.sumAccept.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{agent-discount}", "g"), ClearData.Agent.sumDiscount.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{agent-reward}", "g"), ClearData.Agent.sumReward.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{agent-sum}", "g"), ClearData.Agent.sumAll.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{company-accept}", "g"), ClearData.Company.sumAccept.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{company-discount}", "g"), ClearData.Company.sumDiscount.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{company-reward}", "g"), ClearData.Company.sumReward.formatMoney(2, '.', ','));
        html = html.replace(new RegExp("{company-sum}", "g"), ClearData.Company.sumAll.formatMoney(2, '.', ','));
        return html;
    }
    function init() { }
    init();
}

function SaveModel(Main) {
    var Me = this;
    var saved_tag = "saved";

    this.version = "1.0.0";
    this.elm = null;
    this.arrUserMap = [];
    this.arrLotto = [];
    this.load = load_saved_from_elm;
    this.save = save_data_to_elm;

    function load_saved_from_elm() {
        var data = JSON.parse($(Me.elm).html());
        Me.arrUserMap = data.arrUserMap;
        Me.arrLotto = data.arrLotto;
    }
    function save_data_to_elm() {
        var data = {
            'arrUserMap': Me.arrUserMap,
            'arrLotto': Me.arrLotto
        };
        $(Me.elm).html(JSON.stringify(data));
    }
    function init() {
        Me.elm = $(saved_tag)[0];
    }
    init();
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


////////////////////// function program //////////////////////

// var SysLotto = new SysLottoClass({
//     main_user_code : "U1",
//     prefix_user_code : "U",
//     map_user_split_length : 2,
//     ReportClass : ReportClass
// });

// var userMap = "1,Pao,2,Jeffy,3,Satit,4,Bell";
// var userMap2 = "1,Pao,2,Jeffy,3,Satit,5,Big";
// var lottotext = "L1,758,1,1,U4,100.00,35.00,550.00,U3,5.00,35.00,550.00,U2,5.00,35.00,550.00,U1,0.00,0.00,0.00";
// var lottotext2 = "L1,758,1,1,U5,100.00,35.00,550.00,U3,5.00,35.00,550.00,U2,0.00,0.00,0.00";

// SysLotto.newMapUser(userMap);
// SysLotto.newMapUser(userMap2);
// SysLotto.newLotto(lottotext);
// SysLotto.newLotto(lottotext2);

// console.log( SysLotto.Report.getClearData("U2","U3") );
