var uniques;function do_fetch_impression(ok){var adsparam={'ads_id' : ok.qno,'got_private_key' : bakpau.got_private_key,'ads_path' : window.parent.location.pathname,'uniques' : uniques};jQuery.ajax({type: "POST",url:"http://www.gotcha.in/embed/gogetgotcha/add_impression.php",data:adsparam,async:false});}function validateEmail(e){var t=/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)jQuery/;if(t.test(e)){return true}else{return false}}reSortObject=function(){return Math.floor(Math.random()*4-1)};var udin,singkong;var tomat,apel,wortel,power,intro,ahoy;suju=function(){singkong=Math.floor(Math.random()*4)+1;intro.html("loading...");var e;var t=0;jQuery.ajax({url:"http://www.gotcha.in/embed/gogetgotcha/getpict.php",type:"POST",dataType:"json",data:bakpau}).done(function(n){if(n.length <= 1){intro.text("This website is not authorized with Gotcha.");}else{udin=n;udin.sort(reSortObject);jQuery(".tomat").remove();for(var r=0;r<udin.length-1;r++){if(udin[r].question!=""){if(udin[r].go_target_url!=null){intro.html("<a href='"+udin[r].go_target_url+"' target='_blank'>"+udin[r].question+"</a>");}else{intro.html(udin[r].question);}ahoy=r}tomat=jQuery("<div>",{"class":"tomat"});tomat.attr("id",r);if(r==0){tomat.css({background:"url(http://www.gotcha.in/embed/content/"+udin[r].go_picture+") no-repeat","background-size":"100% 100%"});jQuery("#captcha").append(tomat);e=jQuery("#"+r).position().left}else{t=r*70+e;tomat.css({left:t,background:"url(http://www.gotcha.in/embed/content/"+udin[r].go_picture+") no-repeat","z-index":"999","background-size":"100% 100%"});jQuery("#captcha").append(tomat)}jQuery("#"+tomat.attr("id")).on("mouseover",function(){if(udin[jQuery(this).attr("id")].go_banner != null){do_fetch_impression(udin[jQuery(this).attr("id")]);jQuery("#gotcha-banner-container", top.document).fadeIn().html("<img style='max-height:85%;' src='http://www.gotcha.in/embed/content/ads-banner/"+udin[jQuery(this).attr("id")].go_banner+"'>");}else{jQuery("#gotcha-banner-container", top.document).fadeOut().html("");}});jQuery("#"+tomat.attr("id")).on("mouseout",function(){jQuery("#gotcha-banner-container", top.document).fadeOut().html("");});jQuery("#"+tomat.attr("id")).on("dragmove touchmove",function(e){e.preventDefault();if(e.originalEvent.touches==undefined||e.originalEvent.changedTouches==undefined){var t=e;var n=parseInt(t.clientY);var r=parseInt(t.clientX);}else{var t=e.originalEvent.touches[0]||e.originalEvent.changedTouches[0];var n=parseInt(t.screenY);var r=parseInt(t.screenX)}var i=parseInt(jQuery(this).position().top);var s=parseInt(jQuery(this).position().left);var o=parseInt(jQuery(this).width());var u=parseInt(jQuery(this).height());var a=n-u/2;var f=r-o/2;var l=a+u;var c=f+o;jQuery(this).css({top:a,left:f})});jQuery("#"+tomat.attr("id")).on("dragend touchend",function(e){jQuery(this).css({"background-color":"transparent"});var t=parseInt(jQuery(this).position().top);var n=parseInt(jQuery(this).position().left);var r=parseInt(jQuery(this).width());var i=parseInt(jQuery(this).height());var s=t+i;var o=n+r;var u=parseInt(apel.position().top);var a=parseInt(apel.position().left);var f=parseInt(apel.width());var l=parseInt(apel.height());var c=u+l;var h=a+f;if(u<t&&c>s&&a<n&&h>o){if(jQuery(this).attr("id")==ahoy){jQuery('#got_val').val('mantapdit!');jQuery("#gotcha_val", top.document).val('mantapdit!'); /*jQuery('#signup_submit').removeAttr("disabled");*/}else{jQuery('#got_val').val('saladit!');jQuery("#gotcha_val", top.document).val('saladit!');/*jQuery('#signup_submit').attr('disabled','disabled');jQuery("#wortel").trigger("mousedown")*/}}
else{jQuery('#got_val').val('');jQuery("#gotcha_val", top.document).val('');}})}}wortel.appendTo(jQuery("#captcha"))}).fail(function(){udin=["","pants","dress","hat","wallet"];intro.html("Drag the <strong>"+udin[singkong]+"</strong> into the box");jQuery(".tomat").remove();for(var n=0;n<udin.length-1;n++){tomat=jQuery("<div>",{"class":"tomat"});var r=[];tomat.attr("id",udin[n+1]);if(n==0){tomat.css({background:"url(http://www.gotcha.in/embed/content/"+udin[n+1]+".png) no-repeat","background-size":"100% 100%"});jQuery("#captcha").append(tomat);e=jQuery("#"+udin[n+1]).position().left}else{t=n*70+e;tomat.css({left:t,background:"url(http://www.gotcha.in/embed/content/"+udin[n+1]+".png) no-repeat","background-size":"100% 100%"});jQuery("#captcha").append(tomat)}r[n]=udin[n+1];jQuery("#"+tomat.attr("id")).on("dragmove touchmove",function(e){e.preventDefault();if(e.originalEvent.touches==undefined||e.originalEvent.changedTouches==undefined){var t=e;var n=parseInt(t.clientY);var r=parseInt(t.clientX)}else{var t=e.originalEvent.touches[0]||e.originalEvent.changedTouches[0];var n=parseInt(t.screenY);var r=parseInt(t.screenX)}var i=parseInt(jQuery(this).position().top);var s=parseInt(jQuery(this).position().left);var o=parseInt(jQuery(this).width());var u=parseInt(jQuery(this).height());var a=n-u/2;var f=r-o/2;var l=a+u;var c=f+o;jQuery(this).css({top:a,left:f})});jQuery("#"+tomat.attr("id")).on("dragend touchend",function(e){jQuery(this).css({"background-color":"transparent"});var t=parseInt(jQuery(this).position().top);var n=parseInt(jQuery(this).position().left);var r=parseInt(jQuery(this).width());var i=parseInt(jQuery(this).height());var s=t+i;var o=n+r;var u=parseInt(apel.position().top);var a=parseInt(apel.position().left);var f=parseInt(apel.width());var l=parseInt(apel.height());var c=u+l;var h=a+f;if(u<t&&c>s&&a<n&&h>o){if(jQuery(this).attr("id")==udin[singkong]){jQuery('#got_val').val('mantapdit!');jQuery("#gotcha_val", top.document).val('mantapdit!');}else{jQuery('#got_val').val('saladit!');jQuery("#gotcha_val", top.document).val('saladit!');jQuery("#wortel").trigger("mousedown")}}})}});};start=function(){apel=jQuery("<div>",{id:"apel"});bingo=jQuery("#bingo");intro=jQuery("<p>",{id:"intro"});power=jQuery("<p>",{id:"power",html:"<a href='http://www.gotcha.in/?ref="+window.location.host+"' target='_blank'>Powered by Gotcha (beta)</a>"});wortel=jQuery("<div>",{id:"wortel"});apel.appendTo(jQuery("#captcha"));apel.before("<p id='go_caption'>Drag the right image into GBox</p>");suju();intro.appendTo(jQuery("#captcha"));wortel.appendTo(jQuery("#captcha"));power.appendTo(jQuery("#captcha"));wortel.on("mousedown touchstart",function(){uniques=Math.floor(Math.random()*99999999999999999);suju();jQuery('#got_val').val('')});};monyong=function(){uniques=Math.floor(Math.random()*99999999999999999);var e=0;start();};jQuery(document).ready(monyong)