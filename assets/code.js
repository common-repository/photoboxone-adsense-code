(function($,e,f){if($==undefined||e==undefined){return false}var g='ins.'+f,$elements=$(g),$split=e.token.split('-'),$list=[],$count=0,$time=0,$options={};function writeCookie(a,b,c){var d,expires;if(c){d=new Date();d.setTime(d.getTime()+(c*24*60*60*1000));expires="; expires="+d.toGMTString()}else{expires=""}document.cookie=a+"="+b+expires+"; path=/"}function readCookie(a,b){var i,c,ca,nameEQ=a+"=";ca=document.cookie.split(';');for(i=0;i<ca.length;i++){c=ca[i];while(c.charAt(0)==' '){c=c.substring(1,c.length)}if(c.indexOf(nameEQ)==0){return c.substring(nameEQ.length,c.length)}}if(b){writeCookie(a,'')}return''}function getParameterByName(a){a=a.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]");var b=new RegExp("[\\?&]"+a+"=([^&#]*)"),results=b.exec(location.search);return results===null?"":decodeURIComponent(results[1].replace(/\+/g," "))}function setup(){var w=$(window).width(),template=get_template();$elements.each(function(a){$(this).wrap('<div class="'+f+'-widget" data-index="'+(a+1)+'"></div>');$list.push(this.outerHTML);push()});if(parseInt(e.auto)&&typeof template=='string'&&template.search(f)>-1){$('header:first, footer:last, article').each(function(){var a=$(this),cls='ads-auto-pushed',html='<div class="'+f+'-auto" data-index="'+($list.length+1)+'" style="max-width: 780px; margin: 20px auto;">'+template+'</div>';if(a.hasClass(cls)==false){a.addClass(cls);if(a.is('footer')){a.before(html)}else{a.after(html)}$list.push(template);push()}})}auto()}function auto(a){if(typeof a=='number'){$('.'+f+'-widget, .'+f+'-auto').each(function(){let widget=$(this),index=parseInt(widget.data('index'));if(index>0){push(widget,$list[index-1])}})}if($count<10){$time=parseInt(e.time);if($time<=10000){$time=15*60000}setTimeout(function(){auto($time)},$time)}}function push(a,b){var c=false,data={};if(typeof a!='undefined'&&typeof b=='string'){data=get_data_from_html(b);c=a.each(function(){hidden($(this).find('ins'))}).append(action_before(b)).find('ins').addClass('pushed')}(adsbygoogle=window.adsbygoogle||[]).push({});if(c!=false){action_after(c,data)}}function get_data_from_html(a){return $(a).data()}function set_data(a,b){let sp=$split;if(typeof b!='undefined'){if(b.adClient){sp[1]=b.adClient}if(b.adSlot){sp[2]=b.adSlot}}return $(a).attr({'data-ad-token':sp[0],'data-ad-client':sp[1],'data-ad-slot':sp[2]})}function action_before(a){if($count>1){a=set_data(a)}return a}function action_after(a,b){setTimeout(function(){set_data(a,b)},3000)}function get_template(){let html='';if(e.code!=''){html=e.code;$options=get_data_from_html(html)}if($(html).data('ad-format')!="auto"){html='<ins class="'+f+'" style="display:block" data-ad-format="auto" data-full-width-responsive="true" />';html=set_data(html);if(typeof html=='object'&&typeof html['jquery']!='undefined'){html=html[0].outerHTML}}return html}function hidden(a){if(a){$element=$(a).css({opacity:0,position:'fixed',zIndex:-9999,top:-9999,left:-9999,});setTimeout(function(){$element.remove()},3500)}}$(document).ready(function(){if($elements.length>0){setup()}$split[1]='ca-pub-'+$split[1]})})(jQuery,adsense_code,'adsbygoogle');