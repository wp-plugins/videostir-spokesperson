/* worppress embed js*/
/* ver: 1.0 */



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


function getVsParams(embedHash)
{

	//url="http://localhost:8084/get-videostir/get-params/"
	url="http://videostir.com/get-videostir/get-params/"
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

						settings=json.settings;

						//var params =(settings['params'][0]);

                         var params=false;

                         if (Object.keys(settings.params).length>1)
                         {
                             params =settings.params;
                         }
                         else
                         {
                             params =(settings['params'][0]);
                         }
                         console.log(settings);
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
						var vhash = json.hash;
                        if (params.disabled==='true') return;
						delete params.setid;
                        params.framesToReportAll=1;

                        if (settings.triggerType = 1)
                        {
                            params['on-click-event']=1;
                        }

                        VS.Player.show(position, settings['width'], settings['height'], vhash, params);

                        if (params.framesToReportAll)
                        {
                            VS.jQuery(document).bind("atFrame.vs-player", function(e, data) {

                            }); // end of frames events
                        }

                        if (settings.triggerType = 1)
                        {
                            VS.jQuery(document).bind('onclick.vs-player', function(e, data) {
                                eval(settings.js);
                            });
                        }

                }
            ,   error: function() {
                    console.log('ERROR: Something went wrong with video.');
                }
            }); 
			

}