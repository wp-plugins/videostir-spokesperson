<?php

global $wpdb;

$info = '';

$videoName = (isset($_POST['name'])) ? $_POST['name'] : 'VideoStir clip';
$embed = (isset($_POST['embed'])) ? stripslashes($_POST['embed']) : '';

if (isset($_POST['apply'])) {
    
    $errorMessages = array();
    $matches = array();

    if (strlen($embed) < 16) {
        $errorMessages[] = 'Code is empty.';
    } else {
        preg_match('/\<script\>\s*VS\.Player\.show\((.+)\);\s*\<\/script\>/s', $embed, $matches);
    }

    $playerParams = array();
    if (count($matches)) {
        $settings = $matches[1];

        preg_match('/(".+"|{.+})\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*"(\w+)"\s*,\s*({.+})/s', $settings, $matches);

        if (count($matches) != 6) {
            $errorMessages[] = 'Unknown format for "VS.Player".';
        }

        $playerParams['position'] = $matches[1];
        if ($playerParams['position'][0] != '"') {
            $playerParams['position'] = json_decode($playerParams['position'], true);
            if ($playerParams['position'] == null) {
                $errorMessages[] = 'Error parsing position object.';
            }
        }
        
        $playerParams['width']  = $matches[2];
        $playerParams['height'] = $matches[3];
        
        $playerParams['url']    = $matches[4];
        if (!ctype_alnum($playerParams['url']) || strlen($playerParams['url']) !== 32) {
            $errorMessages[] = 'Clip ID is not valid.';
        }
        $playerParams['settings'] = json_decode($matches[5], true);
        if ($playerParams['settings'] == null) {
            $errorMessages[] = 'Error parsing special parameters.';
        }
    }
    
    if (!count($errorMessages)) {
        
        $sql = $wpdb->prepare('
        INSERT INTO `'.VideoStir::getTableName().'`
        (
            `name`
        ,   `pages`
        ,   `active`
        
        ,   `position`
        ,   `width`
        ,   `height`
        ,   `url`
        ,   `settings`
        ) VALUES (
            %s
        ,   %s
        ,   %d
        
        ,   %s
        ,   %d
        ,   %d
        ,   %s
        ,   %s
        )', 
                
            $videoName
        ,   ''
        ,   1
                
        ,   serialize($playerParams['position'])
        ,   $playerParams['width']
        ,   $playerParams['height']
        ,   $playerParams['url']
        ,   serialize($playerParams['settings'])
        );

        $wpdb->query($sql);
        
        ?>
        <div style="margin-bottom: 15px;" class="updated">
            <div class="spacer-05">&nbsp;</div>
            Has added a new video.<br/>
            Redirect to edit.
            <div class="spacer-05">&nbsp;</div>
        </div>
        <script type="text/javascript">
        <!--
        window.location = "<?php echo get_bloginfo('url').'/wp-admin/admin.php?page=videostir_options_sub&action=edit&id='.$wpdb->insert_id; ?> "
        //-->
        </script>
        <?php
    } else {
        $info['type'] = 'Error';
        $info['text'] = 'Please paste the correct code.<br/>'.implode('<br/>', $errorMessages);
    }
}
?>

<?php include 'css-script.php'; ?>

<div class="wrap">

    <h2><img class="logo" src="<?php echo $this->logo; ?>" alt="VideoStir" /> Add new video</h2>

    <?php if ($info != '') { ?>
        <div style="margin-bottom: 15px; color: #c00;" class="messages <?php echo $info['type']; ?>">
            <div class="spacer-05">&nbsp;</div>
            <?php echo $info['text']; ?>
            <div class="spacer-05">&nbsp;</div>
        </div>
    <?php } ?>

    <div id="poststuff" class="metabox-holder">
        <div style="width: 60%;float: left;">

            <div id="formdiv" class="postbox " >

                <div class="inside">
                    <form method="post" action="" onsubmit="return videostirValidateNewVideo();">
                        <h2 style="margin: 10px 0 0px;">Instructions</h2>
                        <p style="margin: 0;">
                            Paste the 3 lines you got from <a target="_blank" href="http://videostir.com/?utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin">videostir.com</a> after transforming your clip into a floating clip in the textbox below.<br/>
                            Click "Next" to adjust the parameters that will appear and choose the pages/posts that will hold the clip from the list.
                        </p>
                        
                        <br/>
                        
                        <label>Name<br/><input id="name" name="name" value="<?php echo $videoName; ?>" /></label>
                        
                        <div class="spacer-5">&nbsp;</div>
                        
                        <label>3 lines of embedding code<br/><textarea style="width: 100%;" rows="4" id="embed" name="embed"><?php echo stripslashes($embed); ?></textarea></label>
                        
                        <p style="text-align: right;">
                            <button type="button" onclick="window.location='<?php echo get_bloginfo('url').'/wp-admin/admin.php?page=videostir_options' ?>'">Cancel</button>
                            <button type="submit"><strong>Next</strong></button>
                            <input type="hidden" name="apply" />
                        </p>
                    </form>
                </div>
                
            </div> 

            <div id="formdiv" class="postbox">
                <h3 style="cursor: default;">Tutorial &mdash; How to use this plugin</h3>
                <iframe title="YouTube video player" class="youtube-player" type="text/html" width="100%" height="300" src="http://www.youtube.com/embed/byWDi50sFGM?theme=light&color=white&showinfo=0&controls=1&wmode=transparent&rel=0" frameborder="0" allowFullScreen></iframe>
            </div>

        </div>

        <div style="width: 3%; float: left;">&nbsp;</div>
        <div style="width: 37%;float: left;">
            <?php include 'rigth-bar.php'; ?>
        </div>
        
    </div>

    <br class="clear">
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
