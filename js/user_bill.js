MainProg.config.Page_modules["user_bill"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.
  Main.on("change","#picker-date-bill", date_picker_bill);
},function(Main){
  // this function run on after open this page.
},function(Main){
  // this function run on befor close this page.
});


function date_picker_bill() {
    var cached = $("#paste-bill").html();
    $("#paste-bill").html("Loadding");
    $.post("../actions/", { Action: "loadBill", value: $("#picker-date-bill").val() }, function (data) {
        $("#paste-bill").html(data);
    }).fail(function () {
        alert("การโหลดข้อมูลใหม่ล้มเหลว");
        $("#paste-bill").html(cached);
    });
}