function mcSlides(obj,options){
    var def = {
        preload: false, play: 5000, fadeSpeed: 300, effect: 'slide', crossfade: true,
        pause: 200, hoverPause: true, generateNextPrev: true,
        next: 'mynext', prev: 'myprev', start: 0
    };
    if($.isPlainObject(options)) { $.extend(def,options); }
    obj.slides(def);
    obj.find('.myprev').css({ top:(obj.height()/2)-30 }).html('<span style="margin-left:-12px;">〈</span>');
    obj.find('.mynext').css({ top:(obj.height()/2)-30 }).html('<span style="margin-left:2px;">〉</span>');
    obj.find('.pagination').css({ left:(obj.width()/2)-(obj.find('.pagination').width()/2) }).find('a').empty();
}
