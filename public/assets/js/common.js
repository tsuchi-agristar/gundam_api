// click effect
$(function() {
	$('.button_shadow01').on('click', function(e){
    $(this).addClass('button_action')
  });
});

// $(function(){
//   $(".tapArea").on("click", function(){
// 			$('.loadWrap img').css("animation", "shake 0.3s");
// 			var copied = $('.loadWrap').clone(true);
// 			$('.loadWrap').before(copied);
// 			$("." + $('.loadWrap').attr("class") + ":last").remove();
//       alert('クリック');
//   });
// });
// $(function(){
//   $("#tapBtn").on("click", function(){
// 			$('.loadWrap img').css("animation", "shake 0.3s");
// 			var copied = $('.loadWrap').clone(true);
// 			$('.loadWrap').before(copied);
// 			$("." + $('.loadWrap').attr("class") + ":last").remove();
//       alert('クリック');
//   });
// });
// font
(function(d) {
    var config = {
      kitId: 'edx6ebx',
      scriptTimeout: 3000,
      async: true
    },
    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
  })(document);

$(function() {

  let root = document.documentElement;
  document.body.onload = ()=>{
      document.body.style.minHeight = $(window).height() + 'px';
      root.style.setProperty('--main-height', $(window).height() + 'px');
  }
  let timer = null;
  $(window).on('resize',function() {
      clearTimeout( timer );
      timer = setTimeout(function() {
              document.body.style.minHeight = $(window).height() + 'px';
                root.style.setProperty('--main-height', $(window).height() + 'px');
      }, 300 );
  });
});
   // ローディング TOPのみ
   window.onload = function() {
    const spinner = document.getElementById('loading');
    spinner.classList.add('loaded');
  }