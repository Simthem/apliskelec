$('.nav-pills a:last').on('click', function() {
    $('.nav-pills a:first').removeClass('active'); // remove active class from tabs
    $(this).addClass('active'); // add active class to clicked tab
    $('.tab-content #tab1').hide(); // hide all tab content
    //$('#' + $(this).data(id)).show(); // show the tab content with matching id
    $('.tab-content #tab2').show();
});

$('.nav-pills a:first').on('click', function() {
    $('.nav-pills a:last').removeClass('active'); // remove active class from tabs
    $(this).addClass('active'); // add active class to clicked tab
    $('.tab-content #tab2').hide(); // hide all tab content
    //$('#' + $(this).data(id)).show(); // show the tab content with matching id
    $('.tab-content #tab1').show();
});

function checkForm(){
    if(document.getElementById('name').value == ""){
        alert('Vous devez indiquer un libellé obligatoirement !');
        return false;
    } else if (document.getElementById('contact_name').value == "") {
        alert('Vous devez indiquer un nom de contact obligatoirement !')
    } else {
    document.getElementById('add_trouble').submit();
    }
}

!function(a){
    function b(){
        g.hasClass(k)?h.toggleClass(l):h.toggleClass(m),q&&g.one("transitionend",function(){
            q.focus()
        })
    }
    function c(){
        g.hasClass(k)?h.removeClass(l):h.removeClass(m)
    }
    function d(){
        g.hasClass(k)?(h.addClass(l),g.animate({left:"0px"},r),i.animate({left:s},r),j.animate({left:s},r)):(h.addClass(m),g.animate({right:"0px"},r),i.animate({right:s},r),j.animate({right:s},r)),q&&q.focus()
    }
    function e(){
        g.hasClass(k)?(h.removeClass(l),g.animate({left:"-"+s},r),i.animate({left:"0px"},r),j.animate({left:"0px"},r)):(h.removeClass(m),g.animate({right:"-"+s},r),i.animate({right:"0px"},r),j.animate({right:"0px"},r))
    }
    function f(){
        a(t).addClass(v),a(t).on("click",function(b){
            var c=a(this);c.hasClass(v)?(c.siblings(t).addClass(v).removeClass(u),c.removeClass(v).addClass(u)):c.addClass(v).removeClass(u),b.stopPropagation()
        })
    }
    var g=a(".menu"),h=a("body"),i=a("#container"),j=a(".push"),k="left-menu",l="left-open",m="right-open",n=a(".site-overlay"),o=a(".menu-btn, .menu-link"),p=a(".menu-btn"),q=a(g.data("focus")),r=200,s=g.width()+"px",t=".pushy-submenu",u="pushy-submenu-open",v="pushy-submenu-closed";
    a(t);
    a(document).keyup(function(a){27==a.keyCode&&(h.hasClass(l)||h.hasClass(m))&&(w?c():(e(),x=!1),p&&p.focus())});
    var w=function(){
        var a=document.createElement("p"),b=!1,c={webkitTransform:"-webkit-transform",OTransform:"-o-transform",msTransform:"-ms-transform",MozTransform:"-moz-transform",transform:"transform"};
        if(null!==document.body){
            document.body.insertBefore(a,null);
            for(var d in c)void 0!==a.style[d]&&(a.style[d]="translate3d(1px,1px,1px)",b=window.getComputedStyle(a).getPropertyValue(c[d]));
            return document.body.removeChild(a),void 0!==b&&b.length>0&&"none"!==b
        }
        return!1
    }();
    
    if(w)f(),o.on("click",function(){b()}),n.on("click",function(){b()});
    else{
        h.addClass("no-csstransforms3d"),g.hasClass(k)?g.css({left:"-"+s}):g.css({right:"-"+s}),i.css({"overflow-x":"hidden"});
        var x=!1;f(),o.on("click",function(){
            x?(e(),x=!1):(d(),x=!0)
        }),n.on("click",function(){
            x?(e(),x=!1):(d(),x=!0)
        })
    }
}(jQuery);