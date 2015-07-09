<?php
global $wpdb;

$info = '';

if (isset($_POST['update'])) {

    $errorMessages = array();

    $arraypages = array();
    if (isset($_POST["pages"]) && is_array($_POST["pages"]) && count($_POST["pages"]) > 0) {
        foreach ($_POST["pages"] as $selpage) {
            $arraypages[] = $selpage;
        }
    }

    $pages = implode(',', $arraypages);
  //  var_dump($pages);
    
    $val1 = (int) $_POST['val1'];
    $val2 = (int) $_POST['val2'];
    switch ($_POST['position']) {
        case 'top-left':
            $playerPosition = '"'.$_POST['position'].'"';
            if ($val1 || $val2) {
                $playerPosition = array('top' => $val1 ? $val1 : 0, 'left' => $val2 ? $val2 : 0);
            }
            break;
            
        case 'top-right':
            $playerPosition = '"'.$_POST['position'].'"';
            if ($val1 || $val2) {
                $playerPosition = array('top' => $val1 ? $val1 : 0, 'right' => $val2 ? $val2 : 0);
            }
            break;
            
        case 'bottom-right':
            $playerPosition = '"'.$_POST['position'].'"';
            if ($val1 || $val2) {
                $playerPosition = array('bottom' => $val1 ? $val1 : 0, 'right' => $val2 ? $val2 : 0);
            }
            break;
            
        case 'bottom-left':
            $playerPosition = '"'.$_POST['position'].'"';
            if ($val1 || $val2) {
                $playerPosition = array('bottom' => $val1 ? $val1 : 0, 'left' => $val2 ? $val2 : 0);
            }
            break;
	case 'center':
            $playerPosition = '"'.$_POST['position'].'"';
	    // there is no option to set exact distance from center position
            break;
    }
    
    $width = (int) $_POST['width'];
    if ($width < 50 || $width > 3000) {
        $errorMessages[] = 'Clip width '.$width.' is out of range (50&ndash;3000).';
    }
    $height = (int) $_POST['height'];
    if ($height < 50 || $height > 3000) {
        $errorMessages[] = 'Clip height '.$height.' is out of range (50&ndash;3000).';
    }
    
    if (!ctype_alnum($_POST['url']) || strlen($_POST['url']) !== 32) {
        $errorMessages[] = 'Clip ID is not valid. It should be alphanumeric value 32 symbols long that you can only get from VideoStir site.';
    }
    
    $playerParams = array();
    
    $playerParams['auto-play'] = ($_POST['auto-play'] == 'yes') ? true : false;
    $playerParams['quiet'] = ($_POST['quiet'] == 'yes') ? true : false;
    
    $playerParams['playback-delay'] = ($_POST['playback-delay']) ? (int) $_POST['playback-delay'] : 0;

    $playerParams['auto-play-limit'] = ($_POST['auto-play-limit']) ? (int) $_POST['auto-play-limit'] : 0;
    
    if ((int) $_POST['disable-player-threshold'] > 0) {
        $playerParams['disable-player-threshold'] = (int) $_POST['disable-player-threshold'];
    }
    
    if (!empty($_POST['on-finish'])) {
        switch ($_POST['on-finish']) {
            case 'play-button':
            case 'remove':
            case 'blank':
                $playerParams['on-finish'] = $_POST['on-finish'];
                break;
        }
    }
    
    if ((int) $_POST['rotation']) {
        $playerParams['rotation'] = (int) $_POST['rotation'];
    }
    
    if ((int) $_POST['zoom'] != 100) {
      //  $playerParams['zoom'] = round((int) $_POST['zoom'] / 100, 1);
        $playerParams['zoom'] = round((int) $_POST['zoom'],1);
    }
    
    if ((int) $_POST['freeze'] > 0) {
        $playerParams['freeze'] = (int) $_POST['freeze'];
    }
    
    if (!empty($_POST['on-click-open-url']) && strpos($_POST['on-click-open-url'], 'http') === false) {
        $_POST['on-click-open-url'] = 'http://'.$_POST['on-click-open-url'];
    }
    if (filter_var($_POST['on-click-open-url'], FILTER_VALIDATE_URL) !== false && in_array($_POST['on-click-open-url-target'], array('blank', 'self'))) {
        $playerParams['on-click-open-url'] = $_POST['on-click-open-url'];
        $playerParams['on-click-open-url-target'] = $_POST['on-click-open-url-target'];
    }
    
    
    if (!empty($_POST['on-click-event'])) {
        $playerParams['on-click-event'] = $_POST['on-click-event'];
    }
    
    if (!empty($_POST['youtube'])) {
        $playerParams['youtube'] = $_POST['youtube'];
    }
    
    if (!count($errorMessages)) {
        
           $checkSql = $wpdb->prepare('describe `'.VideoStir::getTableName().'` `pages`','');
          $data = $wpdb->get_results($checkSql, ARRAY_A);

          //var_dump($data[0]['Type']);  
          if ($data[0]['Type']!='text')
          {
            $alterSql = $wpdb->prepare('ALTER TABLE `'.VideoStir::getTableName().'` MODIFY pages TEXT','');
            $wpdb->query($alterSql);       
          }
        

        $sql = $wpdb->prepare('
        UPDATE
            `'.VideoStir::getTableName().'`
        SET
            `pages` = %s
        ,   `position` = %s
        ,   `width` = %d
        ,   `height` = %d
        ,   `url` = %s
        ,   `settings` = %s
        WHERE
            `id` = %d
        LIMIT 1
        ', 

            $pages
        ,   serialize($playerPosition)
        ,   $_POST['width']
        ,   $_POST['height']
        ,   $_POST['url']
        ,   serialize($playerParams)
        ,   $_GET['id']
        );

//var_dump($sql);



        $wpdb->query($sql);

        $info['type'] = 'updated';
        $info['text'] = 'Floating clip parameters updated.';
        
    } else {
        $info['type'] = 'error';
        $info['text'] = 'Errors found.<br/>'.implode('<br/>', $errorMessages);
    }
}

if (isset($_POST['change-name'])) {
    $sql = $wpdb->prepare('UPDATE `'.VideoStir::getTableName().'` SET `name` = %s WHERE `id` = %d LIMIT 1', $_POST['name'], $_GET['id']);
    $wpdb->query($sql);
    
    $info['type'] = 'updated';
    $info['text'] = 'VideoStir clip name has been updated.';
}



$sql  = $wpdb->prepare('SELECT * FROM `'.VideoStir::getTableName().'` WHERE `id` = %d LIMIT 1', $_GET['id']);
$data = $wpdb->get_results($sql, ARRAY_A);

$excludepages = array();
$arraypages = explode(',', $data[0]['pages']);
//$excludepages = array_merge($excludepages, $arraypages);

$video = $data[0];

$embedCode = '';
if (!empty($data)) {
    $embedCode = VideoStir::createPlayerJs($data[0]);
    $playerPosition = unserialize($data[0]['position']);
    $playerParams = unserialize($data[0]['settings']);
    if (isset($playerParams['zoom'])) {
       // $playerParams['zoom'] = round($playerParams['zoom'] * 100, 0);
    }
} else {
    $playerPosition = '"bottom-right"';
    $playerParams = array();
}

?>

<?php include 'css-script.php'; ?>

<div class="wrap">

    <h2><img class="logo" src="<?php echo $this->logo; ?>" alt="VideoStir" />Edit video</h2>

    <?php if ($info != '') {
        ?>
        <div style="margin-bottom: 15px;" class="messages <?php echo $info['type']; ?>">
            <div class="spacer-05">&nbsp;</div>
            <?php echo $info['text']; ?>
            <div class="spacer-05">&nbsp;</div>
        </div>
    <?php } ?>

    <div id="poststuff" class="metabox-holder" >
        <div style="width: 60%;float: left;">

            <div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
                <h3 style="cursor: default;">VideoStir clip</h3>
                <div class="inside">
                    <form method="post" action="">
                        <div class="spacer-10">&nbsp;</div>
                        <label for="name">Name</label> 
                        <input id="name" name="name" value="<?php echo $video['name']; ?>" style="width: 200px;" />
                        <input type="submit" name="change-name" class="nbutton" value="SAVE NAME" />
                    </form>
                </div>
            </div> 
            <form method="post" action="" onsubmit="return validateVideoStirEditForm();">
                <div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
                    <h3 style="cursor: default;">Choose the pages/posts for the clip to run on</h3>
                    <div class="inside frm">
                            <div class="spacer-10">&nbsp;</div>

                            <strong>Pages</strong><input type="checkbox" id='check_all' style = "margin-left:30px" onclick="changePages(this,'pages');"><span> select / unselect all pages </span>
                            <div class="posts-container">
                                <?php
                                $apages = explode(',', $video['pages']);
                                if (get_option('page_on_front') == 0) {
                                    if (!in_array(0, $excludepages)) {
                                        ?>

                                        <label class="selpage">
                                                                                    <input type="checkbox" class="pages" name="pages[]" id="home" value="0" <?php echo (in_array('0', $apages)) ? 'checked="checked"' : ''; ?> /> 
                                                                                    Home
                                                                            </label>

                                        <?php
                                    }
                                }
                                $pages = get_pages();

                                foreach ($pages as $page) {
                                    if (!in_array($page->ID, $excludepages) && $page->ID != 0) {
                                        ?>

                                                                            <div class="spacer-05">&nbsp;</div>
                                        <label class="selpage">
                                                                                    <input type="checkbox" class="pages" name="pages[]" id="p-<?php echo $page->post_title; ?>" value="<?php echo $page->ID; ?>" <?php echo (in_array($page->ID, $apages)) ? 'checked="checked"' : ''; ?>  />
                                                                                    <?php echo $page->post_title; ?>
                                                                            </label>

                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="spacer-10">&nbsp;</div>

                            <strong>Posts</strong><input style='margin-left:30px' type="checkbox" id='check_all' onclick="changePages(this,'posts');"><span> select / unselect all posts </span>
                            <div class="posts-container">
                                <?php
                                $apages = explode(',', $video['pages']);
                                $posts = get_posts(array('numberposts' => -1));

                                foreach ($posts as $post) {
                                    if (!in_array($post->ID, $excludepages)) {
                                        ?>

                                        <div class="spacer-05">&nbsp;</div>
                                        <label class="selpage">
                                                                                    <input type="checkbox" class="posts" name="pages[]" id="p-<?php echo $post->post_title; ?>" value="<?php echo $post->ID; ?>" <?php echo (in_array($post->ID, $apages)) ? 'checked="checked"' : ''; ?>  />
                                                                                    <?php echo $post->post_title; ?>
                                                                            </label>

                                        <?php
                                    }
                                }
                                ?>
                            </div>
                    </div>
                </div>
                <div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
                    <h3 style="cursor: default;">Define player parameters</h3>
                    <div class="inside frm">

                            <p style="text-align: right; float : right;">
                                <input type="submit" name="update" class="nbutton" value="APPLY" style="font-size : 100%; font-weight:bold;height : 30px; width : 80px;"/>
                                <br/>
                                <span>and you are done</span>
                            </p>
                            <strong>Position</strong><span class="help" title="Player position on page. Number of pixels from selected corner. Example: Bottom/Right 100x200 - will place clip 100px from bottom and 200px from right. Leave 0 (zero) values in case you want clip to appear in the selected corner.">?</span>
                            <div class="spacer-10">&nbsp;</div>
                            <?php

                            if (is_array($playerPosition)) {
                                if (isset($playerPosition['top']) && isset($playerPosition['left'])) {
                                    $val1 = $playerPosition['top'];
                                    $val2 = $playerPosition['left'];
                                    $playerPosition = '"top-left"';
                                } else if (isset($playerPosition['top']) && isset($playerPosition['right'])) {
                                    $val1 = $playerPosition['top'];
                                    $val2 = $playerPosition['right'];
                                    $playerPosition = '"top-right"';
                                } else if (isset($playerPosition['bottom']) && isset($playerPosition['left'])) {
                                    $val1 = $playerPosition['bottom'];
                                    $val2 = $playerPosition['left'];
                                    $playerPosition = '"bottom-left"';
                                } else if (isset($playerPosition['bottom']) && isset($playerPosition['right'])) {
                                    $val1 = $playerPosition['bottom'];
                                    $val2 = $playerPosition['right'];
                                    $playerPosition = '"bottom-right"';
                                }
                            } else if (is_string($playerPosition)) {
                                switch ($playerPosition) {
                                    case '"bottom-right"':
                                    case '"bottom-left"':
                                    case '"top-left"':
                                    case '"top-right"':
				    case '"center"':
                                        $val1 = 0;
                                        $val2 = 0;
                                        break;
                                }
                            }

                            ?>
                            <select id="position" name="position">
                                <option <?php if ($playerPosition == '"bottom-right"') echo 'selected="selected"'; ?> value="bottom-right">Bottom / Right</option>
                                <option <?php if ($playerPosition == '"bottom-left"')  echo 'selected="selected"'; ?> value="bottom-left">Bottom / Left</option>
                                <option <?php if ($playerPosition == '"top-left"')     echo 'selected="selected"'; ?> value="top-left">Top / Left</option>
                                <option <?php if ($playerPosition == '"top-right"')    echo 'selected="selected"'; ?> value="top-right">Top / Right</option>
				<option <?php if ($playerPosition == '"center"') echo 'selected="selected"'; ?> value="center">Center</option>
                            </select>
                            <input style="width: 12%;" name="val1" id="val1" value="<?php echo $val1 ? $val1 : '0' ?>" /> x <input style="width: 12%;" name="val2" id="val2" value="<?php echo $val2 ? $val2 : '0'?>" />
                            <div class="spacer-5">&nbsp;</div>



                            <strong>Dimensions</strong>
                            <div class="spacer-10">&nbsp;</div>

                            <label for="width">Width <span class="help" title="Player width in pixels">(?)</span></label>
                            <input id="width" name="width" value="<?php echo $video['width'] ?>" />
                            <div class="spacer-05">&nbsp;</div>

                            <label for="height">Height <span class="help" title="Player height in pixels">(?)</span></label>
                            <input name="height" id="height" value="<?php echo $video['height'] ?>" />
                            <div class="spacer-5">&nbsp;</div>



                            <label for="url">Clip ID <span class="help" title="Unique clip ID as given by VideoStir system after creating the floating clip. For example: 0ba20ab3a3daa3f5bcceb9c87ff4f777">(?)</span></label>
                            <input style="width: 50%;" id="url" name="url" value="<?php echo $video['url'] ?>" />
                            <div class="spacer-05">&nbsp;</div>



                            <strong>Settings</strong>
                            <div class="spacer-10">&nbsp;</div>

                            <label for="auto-play">Automatic play <span class="help" title="Yes - Will start clip automatically when player is ready. No - will display a big play button and will only play once visitor clicks on it.">(?)</span></label>
                            <select name="auto-play" id="auto-play">
                                <option <?php if ($playerParams['auto-play'])  echo 'selected="selected"'; ?> value="yes">Yes</option>
                                <option <?php if (!$playerParams['auto-play']) echo 'selected="selected"'; ?> value="no">No</option>
                            </select>
                            <div class="spacer-05">&nbsp;</div>

                            <label for="freeze">Freeze play at frame <span class="help" title="Freezes the clip at frame X and show a big play button. Visitor can click on play button to get the clip running again.">(?)</span></label>
                            <input name="fre-eze" id="freeze" value="<?php echo $playerParams['freeze'] ? $playerParams['freeze'] : '' ?>" />
                            <div class="spacer-05">&nbsp;</div>

                            <label for="on-click-open-url">"Click on me" URL <span class="help" title="When viewer clicks on clip player will open this link.">(?)</span></label>
                            <input style="width: 70%;" id="on-click-open-url" name="on-click-open-url" value="<?php echo $playerParams['on-click-open-url'] ?>" />
                            <br/>
                            <label>&nbsp; <span class="help" title="Once using the previous parameter: Click on me URL. New Window - Will open a new window/tab with the defined internet address. Same Window - will redirect to defined internet address on the currnet page (overwrite existing page).">(?)</span></label>
                            <select name="on-click-open-url-target" id="on-click-open-url-target">
                                <option <?php if ($playerParams['on-click-open-url-target'] == 'blank')  echo 'selected="selected"'; ?> value="blank">New window</option>
                                <option <?php if ($playerParams['on-click-open-url-target'] == 'self') echo 'selected="selected"'; ?> value="self">Same window</option>
                            </select>
                            <div class="spacer-10">&nbsp;</div>
							<label for="quiet">Start in silent mode <span class="help" title="Yes - Will start clip in mute/quiet mode until viewer clicks on a big play button, which will start over clip with audio. No - Will start clip with audio based on other settings you define">(?)</span></label>
                            <select name="quiet" id="quiet">
                                <option <?php if ($playerParams['quiet'])  echo 'selected="selected"'; ?> value="yes">Yes</option>
                                <option <?php if (!$playerParams['quiet']) echo 'selected="selected"'; ?> value="no">No</option>
                            </select>
							<div class="spacer-05">&nbsp;</div>
                            <label for="playback-delay">Playback delay <span class="help" title="Will start playing only when X seconds have passed after player loaded">(?)</span></label>
                            <input name="playback-delay" id="playback-delay" value="<?php echo $playerParams['playback-delay'] ?>" />
                            <div class="spacer-05">&nbsp;</div>

                            <label for="auto-play-limit">Autoplay limit <span class="help" title="Disable auto play after X times per visitor. Once visitor visits the page for more than X times he will only see a big play button that can be activated.">(?)</span></label>
                            <input name="auto-play-limit" id="auto-play-limit" value="<?php echo $playerParams['auto-play-limit'] ?>" />
                            <div class="spacer-05">&nbsp;</div>

                            <label for="disable-player-threshold">Appearance limit <span class="help" title="Will not play or even load the clip after X times a specific visitor visits the page.">(?)</span></label>
                            <input name="disable-player-threshold" id="disable-player-threshold" value="<?php echo $playerParams['disable-player-threshold'] ?>" />
                            <div class="spacer-05">&nbsp;</div>

                            <label for="on-finish">When clip ends <span class="help" title="What should happen when clip playback is finished. Show play button - will allow user to click on a big play button in order to replay. Remove player - will remove the player object.">(?)</span></label>
                            <select name="on-finish" id="on-finish">
                                <option <?php if ($playerParams['on-finish'] == '') echo 'selected="selected"'; ?> value="">Do nothing</option>
                                <option <?php if ($playerParams['on-finish'] == 'play-button' || $playerParams['on-finish'] == 'blank') echo 'selected="selected"'; ?> value="play-button">Show play button</option>
                                <option <?php if ($playerParams['on-finish'] == 'remove') echo 'selected="selected"'; ?> value="remove">Remove player</option>
                            </select>
                            <div class="spacer-05">&nbsp;</div>
                            <p style="text-align: right;  float : right;">
                                <input type="submit" name="update" class="nbutton" value="APPLY" style="font-size : 100%; font-weight:bold;height : 30px; width : 80px;"/>
                                <br/>
                                <span>and you are done</span>
                            </p>
                            <label for="rotation">Rotation <span class="help" title="Rotates clip in player X degrees clockwise">(?)</span></label>
                            <input name="rotation" id="rotation" value="<?php echo $playerParams['rotation'] ? $playerParams['rotation'] : 0 ?>" />
                            <div class="spacer-05">&nbsp;</div>


                            <label for="zoom">Zoom <span class="help" title="Zoom clip (in or out). 100 means no zoom, 50 means half size. Use 120 for example to zoom in so that edges of the clip will disappear ni case they show door edges or other unwanted objects.">(?)</span></label>
                            <input name="zoom" id="zoom" value="<?php echo $playerParams['zoom'] ? $playerParams['zoom'] : 100 ?>" />
                            <div class="spacer-05">&nbsp;</div>

                            
                            <label for="youtube">Youtube clip ID <span class="help" title="Optional Youtube clip id, for a clip that will be shown instead of floating clip when watching from iphone/ipad. Example for a clip id is: hzy6lmnAezk">(?)</span></label>
                            <input style="width: 30%;" id="youtube" name="youtube" value="<?php echo $playerParams['youtube']? $playerParams['youtube'] : "" ?>" />
                            <div class="spacer-05">&nbsp;</div>
                            <p> * Note - There are more possible settings - let us know if you want your clip to do more things for you</p>
                            <div class="spacer-05">&nbsp;</div>
                    </div>
                </div>
                <div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
                    <h3 style="cursor: default;">Advanced parameters (optional for developers)</h3>
                    <div class="inside">
                            <label for="on-click-event">Run custom JavaScript on Click</label>
                            <br/>
                            <textarea rows="8" cols="78" id="on-click-event" name="on-click-event" ><?php echo empty($playerParams['on-click-event']) ? '' : stripcslashes($playerParams['on-click-event']) ?></textarea>
                            
                            <div class="spacer-10">&nbsp;</div>

                            
                            <p style="text-align: right;">
                                <input type="submit" name="update" class="nbutton" value="APPLY" style="font-size : 100%; font-weight:bold;height : 30px; width : 80px;"/>
                                <br/>
                                <span>and you are done</span>
                            </p>
                        
                    </div>
                </div>
            </form>
            
            <div id="formdiv" class="postbox " style="border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);" >
                <h3 style="cursor: default;">VideoStir embed code (read-only, no need to copy)</h3>
                <div class="inside">
                    <textarea style="width: 100%; height: 70px;" id="embed" readonly="readonly" name="embed"><?php echo $embedCode; ?></textarea>
                </div>
            </div> 
            
        </div>

        <div style="width: 5%; float: left;">&nbsp;</div>
        <div style="width: 35%; float: left;">

            <?php include 'rigth-bar.php'; ?>

        </div>
    </div>

    <br class="clear">
</div>
<script type="text/javascript">
    function changePages(source,clName)
    {
       
        
        checkboxes = document.getElementsByClassName(clName);
        console.log(checkboxes);
        for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
  }
    }
</script>
