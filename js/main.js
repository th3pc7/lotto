function Main(config){
    var Me = this;
    var Main = this;
    var Logger = new Log(this);
    var cachEvName = [];

    this.config = config;
    this.log = Logger.writeLog;
    this.Page = new Page(this);
    this.on = add_events;
    this.off = remove_events;

    function remove_events(ev_name, selector){
        if(typeof cachEvName[ev_name+"_"+selector] === "undefined"){
            Main.log("Cant remove events header - " + ev_name + "_" + selector + ". Because this undefined");
            return;
        }
        delete cachEvName[ev_name+"_"+selector];
        $(document).off(ev_name, selector);
        Main.log("Success remove events header - " + ev_name + "_" + selector);
    }
    function add_events(ev_name, selector, fn){
        if(typeof cachEvName[ev_name+"_"+selector] === "undefined"){
            $(document).on(ev_name, selector, function(ev){
                fn(ev);
            });
            cachEvName[ev_name+"_"+selector]=true;
            Main.log("Success on new events header - " + ev_name + "_" + selector);
        }
        else{ Main.log("######## Error developper add same event listener."); alert("ERROR - Same event listener header."); }
    }
    function init(){
        Me.Page.loadPageJS();
    }
    init();

}

function Page(Main){
    var Me = this;
    var scriptLoaded = [];

    this.loadPageJS = load_page_js;
    this.nowUseScript = "";
    this.customLoadUrl = load_page_html;

    function load_page_html(url, notPushHistoryState){
        Main.log("Doing Page.load_page_html('"+url+"')");
        show_page_loading();
        $.post(url, {custom_load:"true"}, function(data){
            toggle_close_page_script();
            $("#"+Main.config.id_paste_cs_load).html(data);
            if(notPushHistoryState!==true){
                Main.log("Push history url : " + url);
                window.history.pushState(url, "Lotto " + url, url);
            }
            load_page_js();
            close_page_loading();
            Main.log("Success Page.load_page_html('"+url+"')");
        }).fail(function(){
            Main.log("Fail Page.load_page_html('"+url+"')");
            show_custom_load_fail();
            close_page_loading();
        });
    }
    function show_custom_load_fail(){
        Main.log("Do show_custom_load_fail()");
        $("#"+Main.config.id_paste_cs_load).html("Error");
    }
    function show_page_loading(){
        Main.log("Do show_page_loading()");
        $("#"+Main.config.id_paste_cs_load).html("Loading");
    }
    function close_page_loading(){
        Main.log("Do close_page_loading()");
    }
    function toggle_close_page_script(){
        if(useScript===false){ return; }
        Main.log("Do toggle_close_page_script()");
        Main.config.Page_modules[useScript].close();
    }
    function load_page_js(){
        Main.log("Get use page js script.");
        if(useScript===false){
            Main.log("This page not use script");
            Me.nowUseScript = false;
        }
        else if(scriptLoaded.indexOf(useScript)===-1){
            Main.log("Loading new page js script.");
            $.getScript(Main.config.base_url + "js/" + useScript + ".js", function(){
                scriptLoaded.push(useScript);
                Me.nowUseScript = useScript;
                Main.log("Success Load new page js script.");
                Main.config.Page_modules[useScript].start();
            });
        }
        else{
            Main.log("Use old js script.");
            Main.config.Page_modules[useScript].start();
        }
    }
    function init(){
        Main.log("Init custom load event");
        $(document).on("click", function(ev){
            if($(ev.target).hasClass(Main.config.class_link_custom_load)){
                ev.preventDefault();
                Main.log("--");
                Main.log("--");
                Main.log("User click a custom load link.");
                load_page_html(ev.target.href);
            }
            else{ }
        });
        Main.log("Init window popstate event");
        window.addEventListener('popstate', function(event){
            var data = event.state;
            Main.Page.customLoadUrl(data, true);
        });
        Main.log("Success init Page Class.");
    }
    init();
}

function PageJS(Main, fn_init, fn_start, fn_close){
    var Me = this;

    this.start = function(){
        Main.log("Run page start script js.");
        fn_start(Main);
    }
    this.close = function(){
        Main.log("Run page close script js.");
        fn_close(Main);
    }
    function init(){
        Main.log("Run page init script js.");
        fn_init(Main);
    }
    init();
}

function Log(Main){
    var Me = this;
    this.writeLog = function(msg){
        if(Main.config.enable_log===true){
            console.log(msg);
        }
    }
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

//////////////////////////////////////////////////

var Modules = [];
var config = {
    enable_log : false,
    base_url : "http://localhost/lotto/",
    id_paste_cs_load : "body",
    class_link_custom_load : "csLoad",
    Page_modules : Modules
};

var MainProg = new Main(config);
