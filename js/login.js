MainProg.config.Page_modules["login"] = new PageJS(MainProg, function(Main){
  // this function run first time on load this file success.
  Main.on("submit","#form-login-grand",function(){
    $.post("login",{user:$("#username").val(), pass:$("#password").val()},function(data){
      if(data[0]==="p"&&data[1]==="a"&&data[2]==="s"&&data[3]==="s"&&data[4]===","){
        data = data.split(",");
        window.location.reload();
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
