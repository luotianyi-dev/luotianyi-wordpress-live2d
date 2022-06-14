String.prototype.renderTip = function (context) {
    var tokenReg = /(\\)?\{([^\{\}\\]+)(\\)?\}/g;
    return this.replace(tokenReg, function (word, slash1, token, slash2) {
        if (slash1 || slash2) {
            return word.replace('\\', '');
        }
        var variables = token.replace(/\s/g, '').split('.');
        var currentObject = context;
        var i, length, variable;
        for (i = 0, length = variables.length; i < length; ++i) {
            variable = variables[i];
            currentObject = currentObject[variable];
            if (currentObject === undefined || currentObject === null) return '';
        }
        return currentObject;
    });
};

function showMessage(text, timeout){
    if(Array.isArray(text)) text = text[Math.floor(Math.random() * text.length + 1)-1];
    console.log(`[${(new Date()).getTime()}][LibreLive2D/Message/Show] ${text}`);
    $('.message').stop();
    $('.message').html(text).fadeTo(200, 1);
    if (timeout === null) timeout = 5000;
       $('.hide-button').css("top",$("#landlord .message").height() - 30 + "px");
        $('.switch-button').css("top",$("#landlord .message").height() - 5 + "px");
		$('.sing-button').css("top",$("#landlord .message").height() - 5 + "px");
    hideMessage(timeout);
}

function hideMessage(timeout){
    $('.message').stop().css('opacity',1);
    if (timeout === null) timeout = 5000;
    $('.message').delay(timeout).fadeTo(200, 0);
}

function initTip(){
    $.ajax({
        cache: true,
        url: `${LIVE2D_OPTIONS.baseUrl}/data/custom_messages.json`,
        dataType: "json",
        success: function (result){
            $.each(result.mouseover, function (index, tips){
                $(tips.selector).mouseover(function (){
                    var text = tips.text;
                    if(Array.isArray(tips.text)) text = tips.text[Math.floor(Math.random() * tips.text.length + 1)-1];
                    text = text.renderTip({text: $(this).text()});
                    showMessage(text, 3000);
                });
            });
            $.each(result.click, function (index, tips){
                $(tips.selector).click(function (){
                    var text = tips.text;
                    if(Array.isArray(tips.text)) text = tips.text[Math.floor(Math.random() * tips.text.length + 1)-1];
                    text = text.renderTip({text: $(this).text()});
                    showMessage(text, 3000);
                });
            });
        }
    });
}

function initSpecialTip() {
    if(!LIVE2D_OPTIONS.specialTip) return;
    var re = /x/;
    console.log(re);
    re.toString = function() {
        showMessage('哈哈，你打开了控制台，是想要看看我的秘密吗？', 5000);
        return '';
    };

    $(document).on('copy', function (){
        showMessage('你都复制了些什么呀？转载要记得加上出处哦！', 5000);
    });
}

function initTimeTip() {
    var text;
    if (window.location.pathname == '/') {
        var now = (new Date()).getHours();
        if (now > 23 || now <= 5) {
            text = '你是夜猫子呀？这么晚还不睡觉，明天起的来嘛？';
        } else if (now > 5 && now <= 7) {
            text = '早上好！一日之计在于晨，美好的一天就要开始了！';
        } else if (now > 7 && now <= 11) {
            text = '上午好！工作顺利嘛，不要久坐，多起来走动走动哦！';
        } else if (now > 11 && now <= 14) {
            text = '中午了，工作了一个上午，现在是午餐时间！';
        } else if (now > 14 && now <= 17) {
            text = '午后很容易犯困呢，今天的运动目标完成了吗？';
        } else if (now > 17 && now <= 19) {
            text = '傍晚了！窗外夕阳的景色很美丽呢，最美不过夕阳红~~';
        } else if (now > 19 && now <= 21) {
            text = '晚上好，今天过得怎么样？';
        } else if (now > 21 && now <= 23) {
            text = '已经这么晚了呀，早点休息吧，晚安~~';
        } else {
            text = '嗨~ 快来逗我玩吧！';
        }
    }else {
        text = '欢迎阅读<span style="color:#66ccff;">「 ' + document.title.split(' - ')[0] + ' 」</span>';
    }
    showMessage(text, 12000);
}

function initLyric(){
    if (!LIVE2D_OPTIONS.lyric) return;
    window.setInterval(() => {
        $.getJSON(`${LIVE2D_OPTIONS.baseUrl}/data/lyric.json`,function(result){
            const lyricConfig = result[parseInt(Math.random() * result.length)];
            showMessage(`${lyricConfig.content.join("<br>")}<br>——${lyricConfig.author}《${lyricConfig.source}》`, 5000);
        });
    }, 30000);
}

function initLive2dMessage (){
    $('.hide-button').fadeOut(0).on('click', () => {
        $('#landlord').remove();
    });
    $('.switch-button').fadeOut(0).on('click', () => {
        $("#live2d").animate({opacity:'0'},100);
        setTimeout("ChangePoi()",100);
    });
	$('.sing-button').fadeOut(0).on('click', () => {
        //$("#sing").animate({opacity:'0'},100);
        //setTimeout("sing()",100);
    });
    $('#landlord').hover(() => {
        $('.hide-button').css("top",$("#landlord .message").height() - 30 + "px");
        $('.switch-button').css("top",$("#landlord .message").height() - 5 + "px");
		$('.sing-button').css("top",$("#landlord .message").height() - 5 + "px");
        $('.hide-button').fadeIn(200);
        $('.switch-button').fadeIn(200);
		$('.sing-button').fadeIn(200);
    }, () => {
        $('.hide-button').fadeOut(200);
        $('.switch-button').fadeOut(200);
		$('.sing-button').fadeOut(200);
    })
}

function onLive2DLoad(content){
    console.log(`[${(new Date()).getTime()}][LibreLive2D] ${content}`);
    $("#live2d").animate({opacity:'1'},100);
}

function initLive2dModel(){
    loadlive2d('live2d', `${LIVE2D_OPTIONS.baseUrl}/model/${LIVE2D_OPTIONS.model}/model.json`, onLive2DLoad("Live2D 加载完成"));
}

function changeLive2dModel(){
    loadlive2d('live2d', `${live2d_Path}model.json.php`, onLive2DLoad("Live2D 更换完成"));
}

(() => {
    initTip();
    initSpecialTip();
    initTimeTip();
    initLyric();
    initLive2dMessage();
    setTimeout(initLive2dModel, 500);
})();

const live2dPlayer = {
    playerId: 'LibreLive2D_song-player_' + (Math.ceil(Math.random()*1000000000)).toString() + (new Date()).getTime().toString(),
};
document.getElementById("sing").innerHTML=`<audio src="blob://${live2dPlayer.playerId}" id="${live2dPlayer.playerId}" controls="controls" hidden="true">`;
$.getJSON(`${LIVE2D_OPTIONS.baseUrl}/data/songs.json`,(songs) => (live2dPlayer.songs = songs));
live2dPlayer.musicPlayer = () => (document.getElementById(live2dPlayer.playerId))
live2dPlayer.isSinging = () => (live2dPlayer.musicPlayer().src !== `blob://${live2dPlayer.playerId}`)
live2dPlayer.setSource = (song) => (live2dPlayer.musicPlayer().src = song)
live2dPlayer.stop = () => (live2dPlayer.setSource(`blob://${live2dPlayer.playerId}`))
live2dPlayer.sing = () => {
    var i = parseInt(Math.random() * live2dPlayer.songs.length);
    console.log(`[${(new Date()).getTime()}][LibreLive2D/Music] 正在播放 [ ${live2dPlayer.songs[i]["name"]} ] i=${i} ${live2dPlayer.songs[i]["url"]}`)
    live2dPlayer.setSource(live2dPlayer.songs[i]["url"]);
    showMessage(`正在播放 [ ${live2dPlayer.songs[i]["name"]} ]`, 5000);
    live2dPlayer.musicPlayer().play();
    live2dPlayer.musicPlayer().loop = false;
}
live2dPlayer.musicPlayer().addEventListener("ended", function() {
    console.log(`[${(new Date()).getTime()}][LibreLive2D/Music] 一首歌唱完啦！还要在唱一首吗？`)
    live2dPlayer.sing();
})
document.getElementById("sing-button").addEventListener('click', function() {
    if (live2dPlayer.isSinging()) {
        live2dPlayer.stop();
        document.getElementById("sing-button").innerHTML = "唱歌";
    } else {
        live2dPlayer.sing();
        document.getElementById("sing-button").innerHTML = "暂停";
    }
})
