MainProg.config.Page_modules["login"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.
  Main.on("click",".btn-login",function(){
    $.post("login",{user:$("#input-user").val(), pass:$("#input-pass").val()},function(data){
      if(data[0]==="p"&&data[1]==="a"&&data[2]==="s"&&data[3]==="s"&&data[4]===","){
        data = data.split(",");
        window.location.href = data[1] + "/";
      }
      else{ alert(data); }
    });
  });
},function(Main){
  // this function run on after open this page.
  $("#app-nav-page").hide();
},function(Main){
  // this function run on befor close this page.
  $("#app-nav-page").show();
});
