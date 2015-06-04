<?php
global $wpdb;
?>
<?php include 'css-script.php'; ?>

<div class="wrap">
	<h2><img class="logo" src="<?php echo $this->logo; ?>" alt="VideoStir" /> Coming features -  <a href="#" style="font-size:18px" onclick="UserVoice.showPopupWidget(); return false;">Let us know what you think</a> </h2>
	<div style="width: 3%;float: left;">&nbsp;</div>
 	<div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
		<div class="inside">
			<h2 style="cursor: default;"><b>"Video Tour" around the internet - </b><a target="_blank" href="http://videostir.com/tour/go/?hash=cfde0aa1c3fd0b82b1028ca6bdf241ae&tid=777&utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin"> Live demo</a></h2>
			<a href="http://videostir.com/tour/go/?hash=cfde0aa1c3fd0b82b1028ca6bdf241ae&tid=777&utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin" onclick="window.open(this.href); return false;" ><img src="<?php bloginfo('url'); ?>/wp-content/plugins/videostir-spokesperson/img/online-presntation-tour.jpg" width="600" alt="VideoStir" /></a>
			<div>&nbsp;</div>
			<h3 style="cursor: default;">In a nutshell</h3>
			<h4 style="margin-bottom: 2px;">1. Your floating clip runs in the foreground overlay</h4>
			<p style="margin-top: 0px;">Same basic DIY floating clip that guides your viewers.</p>
			<h4 style="margin-bottom: 2px;">2. The page in the background keeps changing from page to page while the video plays</h4>
			<p style="margin-top: 0px;">You can choose to which pages to "jump" and when to jump there. You can also use plain ppt slides as your background.</p>
			<h4 style="margin-bottom: 2px;">3. Once clip is done a chosen page appears.</h4>
			<p style="margin-top: 0px;">You can take your viewers to a video guided tour around the internet or around your website pages.</p>
			<h3>Here is a live example of VideoStir's CEO guiding viewers on VideoStir website (give it a few seconds): <a target="_blank" href="http://videostir.com/tour/go/?hash=cfde0aa1c3fd0b82b1028ca6bdf241ae&tid=777&utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin">http://videostir.com/follow-me</a></h3>
		</div>
	</div>
	<div style="width: 3%;float: left;">&nbsp;</div>
 	<div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
		<div class="inside">
			<h2 style="cursor: default;"><b>Video On Image - already in Beta- </b><a target="_blank" href="http://videostir.com/video-on-image/?utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin"> start here</a></h2>
			<a href="http://videostir.com/video-on-image/?utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin" onclick="window.open(this.href); return false;" ><img src="<?php bloginfo('url'); ?>/wp-content/plugins/videostir-spokesperson/img/simple3x3-voi-tmp.jpg" width="600" alt="VideoStir" /></a>
			<div>&nbsp;</div>
			<h3 style="cursor: default;">In a nutshell</h3>
			<h4 style="margin-bottom: 2px;">1. Upload Video + Upload Image => get a Video on Image mix</h4>
			<p style="margin-top: 0px;">Same basic DIY floating clip with any image automatically mixes them both</p>
			<h4 style="margin-bottom: 2px;">2. Download the result clip to your PC</h4>
			<p style="margin-top: 0px;">Entirely automatically and takes less than 5 minutes - entirely free for now.</p>
<a href="#" style="font-size:18px" onclick="UserVoice.showPopupWidget(); return false;">Let us know what you think</a>
		</div>
	</div>
        <div style="width: 3%;float: left;">&nbsp;</div>
 	<div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
		<div class="inside">
			<h2 style="cursor: default;"><b>Play List - already in Beta</b></h2>
			<a href="http://videostir.com/?utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin" onclick="window.open(this.href); return false;" ><img src="<?php bloginfo('url'); ?>/wp-content/plugins/videostir-spokesperson/img/play-list.jpg" width="600" alt="VideoStir" /></a>
			<div>&nbsp;</div>
			<h3 style="cursor: default;">In a nutshell</h3>
			<h4 style="margin-bottom: 2px;">1. Make a few floating clips</h4>
			<h4 style="margin-bottom: 2px;">2. Load all of them to one page</h4>
                        <h4 style="margin-bottom: 2px;">2. Define in what order & logic do you want them to play</h4>
			<p style="margin-top: 0px;">One after the other? Different one per visit? randomly? and more</p>
<a href="#" style="font-size:18px" onclick="UserVoice.showPopupWidget(); return false;">Let us know what you think</a>
		</div>
	</div>
</div>

<?php

$videoRow = array(
    'position' => serialize('{"bottom": 0, "right": "350px"}'),
    'width'    => 600,
    'height'   => 338,
    'url'      => 'http://videostir.com/go/video/18c99febe2085a43b281654fb32ee226',
    'settings' => serialize(array(
        'auto-play' => true,
        'auto-play-limit' => 200,
        'disable-player-threshold' => 200,
        'playback-delay' => 0,
        'quiet' => true,
        'on-finish' => 'play-button',
        'on-click-open-url' => "http://videostir.com/?ref=from-wpp&from=login",
        "extrab" => 2,
    )),
);
echo VideoStir::createPlayerJs($videoRow).PHP_EOL;

?>        
 
<!-- uservoice feedback code with additional parameter video-hash-->
<script type="text/javascript">
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/7Vrlg3EzRUNNpScfBFqTBg.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>

