MainProg.config.Page_modules["agent_limit"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.
  Main.on("submit","#form-add-lotto-limit",function(ev){
      ev.preventDefault();
      var sendData = $("#form-add-lotto-limit").serialize();
      $.post("../actions/", sendData, function(data){
          if(data==="pass"){ success_add_limit(); }
          else{ alert(data); }
      }).fail(fail_add_limit);
  });
  Main.on("click",".del-lotto-limit",function(ev){
      ev.preventDefault();
      var pass = confirm("คุณต้องการจะลบเลขอั้น ?");
      if(!pass){ return; }
      var limitID = ev.target.dataset.limitid;
      $.post("../actions/", { Action:"del-limit", limitID:limitID }, function(data){
          if(data==="pass"){ alert("ลบเลขอั้นสำเร็จ"); ref_all_limit(); }
          else{ alert(data); }
      }).fail(fail_del_limit);
  });
},function(Main){
  // this function run on after open this page.
},function(Main){
  // this function run on befor close this page.
});


function success_add_limit(){
    alert("บันทึกข้อมูล สำเร็จ");
    ref_all_limit();
    reset_form_add_limit();
}

function fail_add_limit(){
    alert("บันทึกข้อมูล ผิดพลาด");
}

function reset_form_add_limit(){
    $("#form-add-lotto-limit")[0].reset();
}

function ref_all_limit(){
    $.post("../actions/", { Action: "load_all_limit" }, function(data){
        $("#tb-lotto-limit").html(data);
    }).fail(fail_load_all_limit);
}

function fail_load_all_limit(){
    alert("ไม่สามารถอัพเดทข้อมูล เลขอั้นทั้งหมด ได้ในขณะนี้");
}

function fail_del_limit(){
    alert("เกิดข้อผิดพลาด ในการลบเลขอั้น");
}