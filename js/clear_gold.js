MainProg.config.Page_modules["clear_gold"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.
  Main.on("change","#picker-clear-gold",function(ev){
    load_new_table_clear($(ev.target).val());
  });
},function(Main){
  // this function run on after open this page.
},function(Main){
  // this function run on befor close this page.
});


function load_new_table_clear(value){
  var cached = $("#paste-table-clear").html();
  $("#paste-table-clear").html("Loadding");
  $.post("../actions/", { Action: "loadThisDate", value: value }, function(data){
    $("#paste-table-clear").html(data);
  }).fail(function(){
    alert("การโหลดข้อมูลใหม่ล้มเหลว");
    $("#paste-table-clear").html(cached);
  });
}