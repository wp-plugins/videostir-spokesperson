/* worppress embed js*/

var wpVersion = "2.3";
var wpVersionDate = "27.09.15"
var docReady = true;


function printVersion()
{
    console.log("wp-ws-embed version is:"+wpVersion+" from:"+wpVersionDate);
}

function loadStyle(url)
{
    var head = document.getElementsByTagName('head')[0];
    var style = document.createElement('link');
    style.href=url;
    style.type="text/css";
    style.media="screen";
    style.rel="stylesheet";
    head.appendChild(style);

        //<link href="/css/layout.css?0.10.76" media="screen" rel="stylesheet" type="text/css" />
}


function prepareSettingsForFlash(videoData)
{
    var settings=videoData.settings;

    var params=false;

    params = Object.keys(settings.params).length>1? settings.params : settings['params'][0];
                         
    if (params.disabled==='true') return;

    var position = (settings['position'][0]);
    if (position==undefined)
    {
        var alt_position = settings['position'];
        var objName = alt_position.object;
        var offsetTop = alt_position.offsetTop;
        var offsetLeft = alt_position.offsetLeft;
        var objPos = VS.jQuery('#'+objName).get(0).getBoundingClientRect();
        var top =objPos.top -(-offsetTop);
        var left =  objPos.left -(-offsetLeft);
        position={'top':top,'left':left};
    }
                        //console.log(settings);
    var vhash = videoData.hash;
    
    delete params.setid;
    params.framesToReportAll=1;

    if (settings.triggerType = 1)
    {
        params['on-click-event']=1;
    }
    var result = {};
    result.position = position;
    result.settings = settings;
    result.vhash = vhash;
    result.params = params;
    return result;
}

function prepareSettingsForHtml5(videoData)
{
    var settings=videoData.settings;
    var params=false;

    function setVideoSize(vWidth,vHeight)
    {
        var videoSize ={};
        videoSize.width=vWidth;
        videoSize.height=vHeight;
        return videoSize;
    }
    params = Object.keys(settings.params).length>1? settings.params : settings['params'][0];
    
    if (params.disabled==='true') return;
          
    var position = (settings['position'][0]);
          
    if (typeof(position)==='string')
    {
        var a = position.split('-');
        position = new Object();
        position[a[0]]=0;
        position[a[1]]=0;
    }

    var videoSize = setVideoSize(400,224);

    var vhash = videoData.videoHash;
    
    delete params.setid;
    
    var videoWidth = settings['h5_width']? settings['h5_width']:400;
    var videoHeight = settings['h5_height']? settings['h5_height']: 224;


     var properties = {
            //
            right : '',
            bottom : '',
            top:'',
            left:'',
            width : videoWidth, // Actual video width
            height :videoHeight, // Actual video height
            
            canvasWidth: videoSize.width, // Canvas width -> size of presentation
            canvasHeight: videoSize.height, // Canvas height -> size of presentation
            autoplay: params['auto-play'],
            src: vhash,
            hash:vhash,
            onfinish: params['on-finish'],
            autoplaylimit: params['auto-play-limit'],
            offsetx : 0,
            offsety : 0,
            delay : 2,
            getUserData: true,
            freeze: params['freeze'],
            onclickopenurl: params['on-click-open-url'],
            alphaRulesLink: vhash+'-mask.txt',
            onlyCross: true,
            posterImage: 'https://vs-html5.s3.amazonaws.com/newPoster.png',
            button: params['button'],
            email: params['email'],
            label: params['label'],
            target: 'prod'

        };


        for (key in position) {
            properties[key] = position[key];
        }
    return properties;    
}

var browserDetect = function()
{
    // var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    //
    //var isEdge = navigator.userAgent.indexOf('Edge') >= 0;
    //
    //var isVivaldi = navigator.userAgent.toLowerCase().indexOf('vivaldi') > -1;
    //
    //// Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
    ////var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
    //
    //var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
    //
    //var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    //// At least Safari 3+: "[object HTMLElementConstructor]"
    //var isChrome = !!window.chrome && !isOpera && !isSafari;              // Chrome 1+
    //var isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6

    var isOpera = is.opera();

    //var isEdge = navigator.userAgent.indexOf('Edge') >= 0;

    var isVivaldi = navigator.userAgent.toLowerCase().indexOf('vivaldi') > -1;

    // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
    //var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+

    var isFirefox = is.firefox();

    var isSafari = is.safari()
    // At least Safari 3+: "[object HTMLElementConstructor]"
    var isChrome = is.chrome();              // Chrome 1+
    var isIE = is.ie();

    var readyForHtml5 = function ()
    {
        return ((isChrome || isFirefox || isVivaldi))
    }

    var debugMode = function()
    {
        console.log('safari:'+isSafari);
        console.log('chrome:'+isChrome);
        console.log('ff:'+isFirefox);
        console.log('IE:'+isIE);
        console.log('vivaldi:'+isVivaldi);
        console.log('ready for html5:'+readyForHtml5());
    }
    return {readyForHtml5:readyForHtml5, debugMode:debugMode}
}

function checkBrowserForHtml5(videoData)
{
     var result = false;

     var bd = new browserDetect();

    if (bd.readyForHtml5() && videoData.settings['h5_enabled']) result = true;

    if (videoData.settings['debug_mode']) bd.debugMode();
    return result;
}

function getVsParams(embedHash)
{

    printVersion();
	var url="http://videostir.com/get-videostir/get-params/";
    //var url="http://localhost:8084/get-videostir/get-params/";

	VS.jQuery.ajax({
				type: 'get'
            ,   async: false
            ,   crossDomain:true
            ,   url: url
            ,   dataType: 'jsonp'
            ,   jsonp:'callback'
            ,  jsonpCallback:"jsonpcall"
            ,   data: {'hash': embedHash}
            ,   success: function (json) {

				var properties = null;
                videoData=json;
                if (checkBrowserForHtml5(videoData))
                {
                   properties = prepareSettingsForHtml5(videoData);
                   videostir_html5.showVideoEmbed(properties);     
                }
                else
                {
                    
                    properties = prepareSettingsForFlash(videoData);
                    VS.Player.show(properties.position, properties.settings['width'], properties.settings['height'], properties.vhash, properties.params);

                    if (properties.params.framesToReportAll)
                    {
                        VS.jQuery(document).bind("atFrame.vs-player", function(e, data) {

                        }); // end of frames events
                    }

                    if (properties.settings.triggerType = 1)
                    {
                        VS.jQuery(document).bind('onclick.vs-player', function(e, data) {
                            eval(properties.settings.js);
                        });
                    }
                }

                }
            ,   error: function() {
                    console.log('ERROR: Something went wrong with video.');
                }
            }); 
			

}