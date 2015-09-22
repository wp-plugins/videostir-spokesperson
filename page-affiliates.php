<?php
global $wpdb;
?>
<?php include 'css-script.php'; ?>

<div class="wrap">
	<h2><img class="logo" src="<?php echo $this->logo; ?>" alt="VideoStir" /> Affiliate plan </h2>
	<div style="width: 3%;float: left;">&nbsp;</div>
 	<div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
		<div class="inside">
			<h2 style="cursor: default;"><b>Join VideoStir's Affiliate Plan - earn money right away. </b><a href="#" style="font-size:18px" onclick="UserVoice.showPopupWidget(); return false;">(Contact us)</a></h2>
			<div>&nbsp;</div>
			<a href="http://videostir.com/affiliates/join-program?utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin" onclick="window.open(this.href); return false;" ><img src="<?php bloginfo('url'); ?>/wp-content/plugins/videostir-spokesperson/img/728x90.gif"  alt="VideoStir" /></a>
			<div>&nbsp;</div>
			<h3 style="cursor: default;">In a nutshell</h3>
			<h4 style="margin-bottom: 2px;">1. <a  href='#' onclick="window.open('http://videostir.com/auth/login')">Login</a> to your VideoStir account. If you do not have an account
                <a  href='#' onclick="window.open('http://videostir.com/auth/register/?page=wp-aff')">register now! </a></h4>
			<!--<p style="margin-top: 0px;">Let us know who you are and we will activate your affiliate account. You will get your own affiliate link and additional info.</p>-->
			<h4 style="margin-bottom: 2px;">2. Once logged in click on the "Join Now" button and tell us a bit about yourself and how you intend to promote VideoStir.</h4>
			<p style="margin-top: 0px;margin-left:20px;">- How do you plan to tell the world about us (by using your website / blog / Facebook / LinkedIn, email, etc)?</p>
			<p style="margin-top: 0px;margin-left:20px;">- Estimate the amount of relevant customers you can reach in a month. What type of customers are they?</p>
			<p style="margin-top: 0px;margin-left:50px;"><b><a href="#" onclick="window.open('http://videostir.com/affiliates/join-program');">Join Us</a></b></p>
			<h4 style="margin-bottom: 2px;">3. Read our affiliate plan terms and conditions.</h4>
			<p style="margin-top: 0px;"></p>
			<h4>You can tell us a bit about yourself using this <a href="#" style="font-size:14px" onclick="UserVoice.showPopupWidget(); return false;"> link</a></h4>
			<h4>For business inquiries  contact - gabriel [@] videostir.com </h4>		
		</div>
	</div>
	
	<div>
		<div class="pricing-row"><strong>Simple process</strong> &mdash; No fuss. Register, get a link, collect payments</div>
		<div class="pricing-row"><strong>In house monitoring page</strong> &mdash; A live page where you can see your leads and what they do. </div>
		<div class="pricing-row"><strong>Marketing material</strong> &mdash; You can use VideoStir service for marketing our product. We will help you out with content and clips.</div>
		<div class="pricing-row"><strong>Support</strong> &mdash; Our support team will be there to help you with any question you have.</div> 
   </div>
   
 	
</div>
<?php

$videoRow = array(
    'position' => serialize('{"bottom": 0, "right": "350px"}'),
    'width'    => 440,
    'height'   => 247,
    'url'      => 'http://videostir.com/go/video/54e5852ee33308ee78b747f9704458c1',
    'settings' => serialize(array(
        'auto-play' => true,
        'auto-play-limit' => 2,
        'disable-player-threshold' => 2,
        'playback-delay' => 0,
        'on-finish' => 'play-button',
        'on-click-open-url' => "http://videostir.com/?ref=from-wpp&from=login",
        "extrab" => 2,
    )),
);
echo VideoStir::createPlayerJs($videoRow).PHP_EOL;

?>        
 <style>
 .pricing-row {
    background: transparent url('<?php bloginfo('url'); ?>/wp-content/plugins/videostir-spokesperson/img/pricing-row.png') 0 bottom no-repeat;
    width: 520px;
    height: 41px;
    padding: 15px 10px 10px 70px;
    color: #89b13e;
}
</style>
 
 
<!-- uservoice feedback code with additional parameter video-hash-->
<script type="text/javascript">
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/7Vrlg3EzRUNNpScfBFqTBg.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>

