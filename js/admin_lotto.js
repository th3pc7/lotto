MainProg.config.Page_modules["admin_lotto"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.

  Main.on("submit","#new_lotto_round",function(ev){ ev.preventDefault(); add_new_round(); });
  Main.on("submit","#admit-lotto-now",function(ev){ ev.preventDefault(); admit(); });
  Main.on("click","#btn-close-this-round-lotto",function(ev){ ev.preventDefault(); close_round(); });
  Main.on("click","#btn-open-this-round-lotto",function(ev){ ev.preventDefault(); open_round(); });
  Main.on("click","#btn-success-this-round-lotto",function(ev){ ev.preventDefault(); success_round(); });

},function(Main){
  // this function run on after open this page.
},function(Main){
  // this function run on befor close this page.
});



function add_new_round() {
  if ($("#new_lotto_round input")[0].value === "") { alert("กรุณาเลือกวันที่ด้วยค่ะ"); return; }
  else {
    $.post("../actions/", { Action: "newRound", date: $("#date-new-round").val() }, function (data) {
      if (data === "pass") {
        alert("สำเร็จ");
        $("#span-date").html($("#date-new-round").val());
        $("#step-2").addClass("hide");
        $("#step-1").removeClass("hide");
        $("#admit-lotto-now input[class!='hide']").val("");
      }
      else { alert(data); }
    }).fail(function () {
      alert("ล้มเหลว - ในการเปิดรับหวยงวดใหม่");
    });
  }
}

function admit() {
  $.post("../actions/", $("#admit-lotto-now").serialize(), function (data) {
    if (data === "pass") {
      alert("สำเร็จ");
      $("#step-2").addClass("hide");
      $("#step-1").removeClass("hide");
    }
    else { alert(data); }
  }).fail(function () {
    alert("ล้มเหลว - ในการอัพเดทผลหวย");
  });
}

function close_round() {
  if(!confirm("แน่ใจหรือ จะปิดรับหวย")){ return; }
  $.post("../actions/",{ Action : "closeThisRound" }, function (data) {
    if (data === "pass") {
      alert("สำเร็จ");
    }
    else { alert(data); }
  }).fail(function () {
    alert("ล้มเหลว - ในการปิดรับหวย");
  });
}

function open_round() {
  if(!confirm("แน่ใจหรือ จะเปิดรับหวย")){ return; }
  $.post("../actions/",{ Action : "openThisRound" }, function (data) {
    if (data === "pass") {
      alert("สำเร็จ");
    }
    else { alert(data); }
  }).fail(function () {
    alert("ล้มเหลว - ในการเปิดรับหวย");
  });
}

function success_round() {
  if(!confirm("เสร็จสิ้น หวยงวดนี้ (การกระทำนี้จะย้อนกลับไม่ได้แล้วนะจ๊ะ)")){ return; }
  $.post("../actions/",{ Action : "successThisRound" }, function (data) {
    if (data === "pass") {
      alert("สำเร็จ");
      $("#date-new-round").val("");
      $("#step-1").addClass("hide");
      $("#step-2").removeClass("hide");
      $("#admit-lotto-now")[0].reset();
    }
    else { alert(data); }
  }).fail(function () {
    alert("ล้มเหลว - ในการ เสร็จสิ้นรับหวย");
  });
}
