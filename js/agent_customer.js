MainProg.config.Page_modules["agent_customer"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.
  Main.on("submit","#form-agent-add-user",function(ev){
      ev.preventDefault();
      var sendData = $("#form-agent-add-user").serialize();
      $.post("../actions/", sendData, function(data){
          if(data==="pass"){ success_add_user(); }
          else{ alert(data); }
      }).fail(fail_add_user);
  });
  Main.on("click","#ref-all-customer",function(ev){
      ev.preventDefault();
      ref_all_customer();
  });
  Main.on("click",".a-set-percent",function(ev){
      ev.preventDefault();
      setPercentCustomer(ev.target.dataset.customerid, ev.target.innerText, ev.target);
  });
  Main.on("click",".a-set-credit",function(ev){
      ev.preventDefault();
      setCreditCustomer(ev.target.dataset.customerid, ev.target.innerText, ev.target);
  });
  Main.on("click","#bind-set-cal a",function(ev){
      ev.preventDefault();
      setCal(ev.target.dataset.id,ev.target.dataset.type,ev.target.dataset.mode,ev.target.innerText,ev.target);
  });
  Main.on("click",".tsf-credit",function(ev){
        ev.preventDefault();
        transfer(ev.target.dataset.iduser);
    });
},function(Main){
  // this function run on after open this page.
  close_add_user_form();
},function(Main){
  // this function run on befor close this page.
});


function setCal(user_id, type_id, mode, old_val, elm){
    var new_set = prompt("กรุณาใส่จำนวนที่ต้องการ",old_val);
    if(new_set===null){ return; }
    new_set = parseFloat(new_set);
    $.post("../actions/",{ Action: "setCal", user:user_id, value:new_set, type:type_id, filed:mode },function(data){
        if(data==="pass"){ elm.innerText = new_set.formatMoney(2,'.',','); alert("สำเร็จ"); }
        else{ alert(data); }
    }).fail(function(data){
        alert("เกิดข้อผิดพลาด กรุณาลองใหม่ภายหลัง");
    });
}
function setCreditCustomer(id, old_credit, elm){
    var new_credit = prompt("ปรับเครดิต",old_credit);
    if(new_credit===null){ return; }
    old_credit = old_credit.replace(new RegExp(",", "g"),"");
    new_credit = new_credit.replace(new RegExp(",", "g"),"");
    if(parseFloat(new_credit)===parseFloat(old_credit)){ }
    else{
        $.post("../actions/",{ Action: "setCredit", credit: new_credit, customer_id: id },function(data){
            if(data==="pass"){
                alert("สำเร็จ");
                elm.innerText = parseFloat(new_credit).formatMoney(2,'.',',');
            }
            else{ alert(data); }
        }).fail(function(){ alert("การปรับเครดิตล้มเหลว ลองใหม่อีกครั้งภายหลัง"); });
    }
}
function setPercentCustomer(id, old_percent, elm){
    var new_percent = prompt("ตั้งค่าเปอร์เซน",old_percent);
    if(new_percent===null){ return; }
    if(parseFloat(new_percent)===parseFloat(old_percent)){ }
    else{
        $.post("../actions/",{ Action: "setPercent", percent: new_percent, customer_id: id },function(data){
            if(data==="pass"){
                alert("สำเร็จ");
                elm.innerText = parseFloat(new_percent).formatMoney(2,'.',',');
            }
            else{ alert(data); }
        }).fail(function(){ alert("การตั้งค่าเปอร์เซ็นล้มเหลว ลองใหม่อีกครั้งภายหลัง"); });
    }
}
function ref_all_customer(){
    $.post("../actions/", { Action: "load_all_customer" }, function(data){
        $("#paste-tb-customer").html(data);
    }).fail(fail_load_all_customer);
}
function ref_all_customer_setting(){
    $.post("../actions/", { Action: "load_all_customer_st" }, function(data){
        $("#paste-tb-customer-setting").html(data);
    }).fail(fail_load_all_customer);
}
function fail_load_all_customer(){
    alert("load all customer fail.");
}
function open_add_user_form(){
    alert("open form");
}
function close_add_user_form(){
    $("#form-agent-add-user")[0].reset();
}
function success_add_user(){
    alert("Success add user");
    close_add_user_form();
    ref_all_customer();
    ref_all_customer_setting();
}
function fail_add_user(){
    alert("การเชื่อมต่อมีปัญหา กรุณาลองใหม่ภายหลังค่ะ");
}
function transfer(numID){
    if(!confirm("ต้องการจะเคลียร์ยอด ?")) {
        return;
    }
    $.post("../actions/",{ Action: "transfer", user:numID },function(data){
        ref_all_customer();
        if(data==="pass"){ alert("สำเร็จ"); }
        else{ alert(data); }
    }).fail(function(data){
        alert("เกิดข้อผิดพลาด กรุณาลองใหม่ภายหลัง");
    });
}