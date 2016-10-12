MainProg.config.Page_modules["risk"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.
},function(Main){
  // this function run on after open this page.
  var data = keep_data_lotto_me_accept();
  update_table_risk(data);
},function(Main){
  // this function run on befor close this page.
});


var set_limit = 9999999;

function keep_data_lotto_me_accept(){
    var all_lotto = [];
    for(forward in data_stack){
        forward = data_stack[forward];
        forward = forward.split(",");
        if(typeof all_lotto[forward[2]] === "undefined"){ all_lotto[forward[2]] = []; }
        if(typeof all_lotto[forward[2]][forward[1]] === "undefined"){
            all_lotto[forward[2]][forward[1]] = new Lottos(forward[1],forward[2],parseFloat(forward[forward.indexOf(me_code)-3])-parseFloat(forward[forward.indexOf(me_code)+1]));
        }
        else{
            all_lotto[forward[2]][forward[1]].add_price(parseFloat(forward[forward.indexOf(me_code)-3])-parseFloat(forward[forward.indexOf(me_code)+1]));
        }
    }
    return all_lotto;
}

function update_table_risk(data){
    $("#tb-risk").html("");
    $("#tb-risk-sc").html("");
    for(lotto_type in data){
        var counts = 0;
        $("#tb-risk").append(getTypeName(lotto_type)+"<br>");
        $("#tb-risk-sc").append(getTypeName(lotto_type)+"<br>");
        for(numbers in data[lotto_type]){
            var lotto_obj = data[lotto_type][numbers];
            lotto_obj.price = lotto_obj.price.formatMoney(2,".",",");
            $("#tb-risk").append(lotto_obj.numbers+" - ฿ "+lotto_obj.price+"<br>");
            var sc = lotto_obj.price - set_limit;
            sc = sc.formatMoney(2,".",",");
            if(sc > 0){ $("#tb-risk-sc").append(lotto_obj.numbers+" - ฿ "+sc+"<br>"); counts++; }
        }
        if(counts===0){ $("#tb-risk-sc").append("-- ไม่มี --<br>"); }
        $("#tb-risk").append("<br>");
        $("#tb-risk-sc").append("<br>");
    }
}

function getTypeName(id){
    if(id==="1"){ return "3 ตัวบน"; }
    if(id==="2"){ return "3 ตัวล่าง"; }
    if(id==="3"){ return "3 ตัวโต๊ด"; }
    if(id==="4"){ return "2 ตัวบน"; }
    if(id==="5"){ return "2 ตัวล่าง"; }
    if(id==="6"){ return "2 ตัวโต๊ด"; }
    if(id==="7"){ return "1 ตัวบน"; }
    if(id==="8"){ return "1 ตัวล่าง"; }
}

function Lottos(numbers, type, price){
    var me = this;
    this.price = null;
    this.numbers = null;
    this.type = null;

    this.add_price = add_price;

    function add_price(price){
        me.price += price;
    }
    function init(){
        me.numbers = numbers;
        me.type = type;
        me.price = price;
    }
    init();
}

function fn_btn_click(){
  set_limit = parseFloat($("#inp-cal").val());
  var data = keep_data_lotto_me_accept();
  update_table_risk(data);
}

function load_new_data(){
    var cached = $("#paste-tabl-risk").html();
    $("#paste-tabl-risk").html("Loadding");
    $.post("../actions/", { Action: "load_data_risk" }, function(data){
        $("#paste-tabl-risk").html(data);
        var data = keep_data_lotto_me_accept();
        update_table_risk(data);
    }).fail(function(){ alert("การโหลดล้มเหลว"); $("#paste-tabl-risk").html(cached); });
}