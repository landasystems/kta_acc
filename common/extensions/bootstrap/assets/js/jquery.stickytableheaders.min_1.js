(function(c,h){function j(e,f){var a=this;a.$el=c(e);a.el=e;a.$el.bind("destroyed",c.proxy(a.teardown,a));a.$window=c(h);a.$clonedHeader=null;a.$originalHeader=null;a.isCloneVisible=!1;a.leftOffset=null;a.topOffset=null;a.init=function(){a.options=c.extend({},k,f);a.$el.each(function(){var d=c(this);d.css("padding",0);a.$originalHeader=c("thead:first",this);a.$clonedHeader=a.$originalHeader.clone();c("tr.filters",a.$clonedHeader).length&&(c("tr.filters",a.$clonedHeader).remove(),c("th",a.$originalHeader).each(function(d){var b=
c(this);d=c("th",a.$originalHeader).eq(d);b.css("width",d.width())}));a.$clonedHeader.addClass("tableFloatingHeader");a.$clonedHeader.css({position:"fixed",top:0,"z-index":1,display:"none","background-color":"#fff"});a.$originalHeader.addClass("tableFloatingHeaderOriginal");a.$originalHeader.after(a.$clonedHeader);c("th",a.$clonedHeader).on("click."+b,function(){var d=c("th",a.$clonedHeader).index(this);c("th",a.$originalHeader).eq(d).click()});d.on("sortEnd."+b,a.updateWidth)});a.updateWidth();a.toggleHeaders();
a.bind()};a.destroy=function(){a.$el.unbind("destroyed",a.teardown);a.teardown()};a.teardown=function(){c.removeData(a.el,"plugin_"+b);a.unbind();a.$clonedHeader.remove();a.$originalHeader.removeClass("tableFloatingHeaderOriginal");a.$originalHeader.css("visibility","visible");a.el=null;a.$el=null};a.bind=function(){a.$window.on("scroll."+b,a.toggleHeaders);a.$window.on("resize."+b,a.toggleHeaders);a.$window.on("resize."+b,a.updateWidth)};a.unbind=function(){a.$window.off("."+b,a.toggleHeaders);a.$window.off("."+
b,a.updateWidth);a.$el.off("."+b);a.$el.find("*").off("."+b)};a.toggleHeaders=function(){a.$el.each(function(){var d=c(this),b=isNaN(a.options.fixedOffset)?a.options.fixedOffset.height():a.options.fixedOffset,e=d.offset(),f=a.$window.scrollTop()+b,h=a.$window.scrollLeft(),g=null;if(f>e.top&&f<e.top+d.height()){if(d=e.left-h,!a.isCloneVisible||!(d===a.leftOffset&&b===a.topOffset))g=c("tr.filters",a.$originalHeader),g.length&&g.insertAfter(a.$clonedHeader.children().eq(0)),a.$clonedHeader.css({top:b,
"margin-top":0,left:d,display:"block"}),a.$originalHeader.css("visibility","hidden"),a.isCloneVisible=!0,a.leftOffset=d,a.topOffset=b}else a.isCloneVisible&&(g=c("tr.filters",a.$clonedHeader),a.$clonedHeader.css("display","none"),g.length&&g.insertAfter(a.$originalHeader.children().eq(0)),a.$originalHeader.css("visibility","visible"),a.isCloneVisible=!1)})};a.updateWidth=function(){c("th",a.$clonedHeader).each(function(b){var e=c(this);b=c("th",a.$originalHeader).eq(b);this.className=b.attr("class")||
"";e.css("width",b.width())});a.$clonedHeader.css("width",a.$originalHeader.width())};a.init()}var b="stickyTableHeaders",k={fixedOffset:0};c.fn[b]=function(e){return this.each(function(){var f=c.data(this,"plugin_"+b);f?"string"===typeof e&&f[e].apply(f):"destroy"!==e&&c.data(this,"plugin_"+b,new j(this,e))})}})(jQuery,window);