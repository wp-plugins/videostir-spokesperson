<?php
global $wpdb;

if (isset($_POST['yes'])) {

    $sql = $wpdb->prepare('UPDATE `'.VideoStir::getTableName().'` SET `active` = %d WHERE `id` = %d LIMIT 1', $_GET['active'], $_GET['id']);
    $wpdb->query($sql);

    if ($_GET['active'] == 1) {
        $info = 2;
    } else {
        $info = 3;
    }
    ?>
    <div style="margin-bottom: 15px;" class="updated">
        <div class="spacer-05">&nbsp;</div>
        Updating...
        <div class="spacer-05">&nbsp;</div>
    </div>
    <script type="text/javascript">
        <!--
        window.location = "<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=videostir_options&info=' . $info; ?> "
        //-->
    </script>
    <?php
}
?>

<?php include 'css-script.php'; ?>

<div class="wrap">

    <h2><img class="logo" src="<?php echo $this->logo; ?>" alt="VideoStir" />  <?php if($_GET['active'] == 1){ echo "Enable"; }else{ echo "Disable";} ?> video</h2>
    <div id="poststuff" class="metabox-holder">
        <div style="width: 60%;float: left;">
            <div id="formdiv" class="postbox " >
                <h3 style="cursor: default;">VideoStir</h3>
                <div class="inside">
                    <form method="post" action="">
                        <div class="spacer-10">&nbsp;</div>
                        <label title="Description" for="name">Are you sure?</label>
                        <input type="submit" class="nbutton" name="yes" value="YES" /> <input onclick="window.location = '<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=videostir_options' ?>'" type="button" class="nbutton" name="no" value="NO" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="width: 3%;float: left;">&nbsp;</div>
    <div style="width: 37%;float: left;">
        <?php include 'rigth-bar.php'; ?>
    </div>
</div>