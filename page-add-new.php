<?php

global $wpdb;

$info = '';

$videoName = (isset($_POST['name'])) ? $_POST['name'] : '';
$embed = (isset($_POST['embed'])) ? stripslashes($_POST['embed']) : '';

if (isset($_POST['apply'])) {
    
    $errorMessages = array();
    $matches = array();

    preg_match('/\<script.+type\="text\/javascript"\>.*VideoStir\.Player\.show\((.+)\);.*\<\/script\>/s', $embed, $matches);

    $playerParams = array();
    if (count($matches)) {
        $settings = $matches[1];

        preg_match('/(".+-.+"|{.+})\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*"(http.+)"\s*,\s*({.+})/s', $settings, $matches);

        if (count($matches) != 6) {
            $errorMessages[] = 'Unknown format for "VideoStir.Player".';
        }

        $playerParams['position'] = $matches[1];
        if ($playerParams['position'][0] != '"') {
            $playerParams['position'] = json_decode($playerParams['position'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorMessages[] = 'Error parsing position object.';
            }
        }
        $playerParams['width']  = $matches[2];
        $playerParams['height'] = $matches[3];
        $playerParams['url']    = $matches[4];
        if (!filter_var($playerParams['url'], FILTER_VALIDATE_URL)) {
            $errorMessages[] = 'Clip URL is not valid.';
        }
        $playerParams['settings'] = json_decode($matches[5], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorMessages[] = 'Error parsing special parameters.';
        }
    }
    
    
    
    if (!count($errorMessages)) {
        
        $sql = $wpdb->prepare('
        INSERT INTO `'.$this->table_name.'`
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
        <div style="margin-bottom: 15px;" class="<?php echo $info['type']; ?>">
            <div class="spacer-05">&nbsp;</div>
            <?php echo $info['text']; ?>
            <div class="spacer-05">&nbsp;</div>
        </div>
    <?php } ?>

    <div id="poststuff" class="metabox-holder">
        <div style="width: 60%;float: left;">

            <div id="formdiv" class="postbox " >

                <div class="inside">
                    <form method="post" action="">
                        <h2 style="margin: 10px 0 0px;">Instructions</h2>
                        <p style="margin-top: 0;">
                            Paste the 3 lines you got from <a target="_blank" href="http://videostir.com/?utm_source=wp-plugin&utm_medium=plugin&utm_campaign=wp-plugin">videostir.com</a> in the textbox below (after transforming your clip into a floating clip).<br/>
                            Click "Next" and adjust the parameters that will appear. Choose the pages/posts that will hold the clip from the list.
                        </p>
                        
                        <div class="spacer-10">&nbsp;</div>
                        
                        <label title="Description" for="name">Name</label>
                        <div class="spacer-10">&nbsp;</div>
                        <input id="name" name="name" value="<?php echo $videoName; ?>" />
                        
                        <div class="spacer-5">&nbsp;</div>
                        
                        <label title="Description" for="embed">Embedding code</label>
                        <div class="spacer-10">&nbsp;</div>
                        <textarea style="width: 100%;" id="embed" name="embed"><?php echo stripslashes($embed); ?></textarea>
                        
                        <div class="spacer-5">&nbsp;</div>
                        
                        <p style="text-align: right;">
                            <input onclick="window.location = '<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=videostir_options' ?>'" type="button" name="cancel" value="Cancel" />
                            <input type="submit" name="apply" value="Next" />
                        </p>
                    </form>
                </div>
            </div> 

        </div>

        <div style="width: 3%;
             float: left;
             ">&nbsp;</div>
        <div style="width: 37%;
             float: left;
             ">

            <?php include 'rigth-bar.php'; ?>

        </div>
    </div>

    <br class="clear">
</div>


<?php

$videoRow = array(
    'position' => serialize('"bottom-right"'),
    'width'    => 360,
    'height'   => 200,
    'url'      => 'http://videostir.com/go/video/45b8716e84abe7bea8fa6637e7b17b48',
    'settings' => serialize(array(
        'auto-play' => true,
        'auto-play-limit' => 5,
        'disable-player-threshold' => 50,
        'playback-delay' => 0,
        'on-finish' => 'remove',
    )),
);
echo VideoStir::createPlayerJs($videoRow).PHP_EOL;

?>        
