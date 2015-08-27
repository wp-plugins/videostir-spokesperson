<?php
global $wpdb;

$data = $wpdb->get_results('SELECT * FROM `'.VideoStir::getTableName().'`', ARRAY_A);
?>

<?php include 'css-script.php'; ?>

<div class="wrap">

    <h2><img class="logo" src="<?php echo $this->logo; ?>" alt="VideoStir" />All videos <input onclick="window.location = 'admin.php?page=videostir_options_sub'" type="button" class="nbutton" name="no" value="ADD NEW" /> </h2>
    <?php if (isset($_GET['info'])) { ?>
        <div style="margin-bottom: 15px;" class="updated">
            <div class="spacer-05">&nbsp;</div>
            <?php
            switch ($_GET['info']) {
                case 1:
                    echo 'Video deleted.';
                    break;
                case 2:
                    echo 'Video enabled.';
                    break;
                case 3:
                    echo 'Video disabled.';
                    break;
            }
            ?>
            <div class="spacer-05">&nbsp;</div>
        </div>
    <?php } ?>

    <div id="poststuff" class="metabox-holder">
        <div style="width: 58%;float: left;border: 1px solid rgba(0,0,0,0.25); box-shadow: 0 5px 15px rgba(0,0,0,0.15);">
            <table class="wp-list-table widefat fixed posts" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 20px;">ID</th>
						<th>Video hash</th>
                        <th>Video name</th>
                        <th>Pages / Post</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>

                <?php if (count($data) > 5): ?>
                <tfoot>
                    <tr>
                        <th style="width: 20px;">ID</th>
						<th>Video hash</th>
                        <th>Video name</th>
                        <th>Pages / Post</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>    
                </tfoot>
                <?php endif; ?>

                <tbody id="the-list" class="list:post">
                    <?php
                    if (!empty($data)) {
                        foreach ($data as $video) {
                            ?>
                            <tr>
                                <td style="border-bottom-width: 0;">#<?php echo $video['id'] ?></td>
								<td style="border-bottom-width: 0;"><?php echo substr($video['url'], 0,5) ?></td>
                                <td style="border-bottom-width: 0;"><?php echo $video['name'] ?></td>
								<td>
									<?php
										$ids = (strlen($video['pages'])) ? explode(',', $video['pages']) : array();
										if (count($ids)) {
											foreach ($ids as $id) {
												if (intval($id) === 0) {
													echo '(Page) Home<br/>';
												} else {
													$p = get_post($id);
													echo '('.ucfirst($p->post_type).') ';
													echo $p->post_title.'<br/>';
												}
											}
										}
									?>
								</td>
                                <td style="border-bottom-width: 0;">
                                    <?php echo '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=videostir_options_sub&action=edit&id=' . $video['id'] . '">edit</a>'; ?> 
                                    - 
                                    <?php echo '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=videostir_options_sub&action=delete&id=' . $video['id'] . '">delete</a>'; ?>
                                    -
                                    <?php
                                    if ($video['active'] == 0) {
                                        echo '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=videostir_options_sub&action=active&active=1&id=' . $video['id'] . '">enable</a>';
                                    } else {
                                        echo '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=videostir_options_sub&action=active&active=0&id=' . $video['id'] . '">disable</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            /*
                            $ids = (strlen($video['pages'])) ? explode(',', $video['pages']) : array();
                            
                            if (count($ids)) {
                                echo '<tr>';
                                echo '<td>&nbsp;</td>';
                                echo '<td colspan="3">';
                                foreach ($ids as $id) {
                                    if (intval($id) === 0) {
                                        echo '(Page) Home<br/>';
                                    } else {
                                        $p = get_post($id);
                                        echo '('.ucfirst($p->post_type).') ';
                                        echo $p->post_title.'<br/>';
                                    }
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
							*/
                        }
                    } else {
                        ?>
                            
                        <tr>
                            <td colspan="4" style="color: #c00;">No videos yet</td>
                        </tr>
                            
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            
            <?php if (!count($data)): ?>
            
            <br/><br/>
            
            <div id="formdiv" class="postbox">
                <h3 style="cursor: default;">Tutorial &mdash; How to use this plugin</h3>
                <iframe title="YouTube video player" class="youtube-player" type="text/html" width="100%" height="550" src="http://www.youtube.com/embed/_jmNZoMLFlc?theme=light&color=white&showinfo=0&controls=1&wmode=transparent&rel=0" frameborder="0" allowFullScreen></iframe>
            </div>
            
            <?php endif; ?>
            
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