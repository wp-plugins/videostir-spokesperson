<?php
global $wpdb;

$data = $wpdb->get_results("SELECT * FROM `" . $this->table_name . "`;", ARRAY_A);
?>

<?php include 'css-script.php'; ?>

<div class="wrap">

    <h2><img class="logo" src="<?php echo $this->logo; ?>" alt="VideoStir" />All videos <a href="admin.php?page=videostir_options_sub" class="add-new-h2" style="margin-left: 5em;">Add new</a></h2>

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
        <div style="width: 60%;float: left;">
            <table class="wp-list-table widefat fixed posts" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Pages / Post</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <?php if (count($data) > 5): ?>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Pages / Post</th>
                        <th>Actions</th>
                    </tr>    
                </tfoot>
                <?php endif; ?>

                <tbody id="the-list" class="list:post">
                    <?php
                    if (!empty($data)) {
                        foreach ($data as $video) {
                            ?>
                            <tr>
                                <td><?php echo $video['id'] ?></td>
                                <td><?php echo $video['name'] ?></td>
                                <td><?php
                    $ids = explode(',', $video['pages']);

                    foreach ($ids as $id) {
                        $p = get_post($id);
                        echo $p->post_name . ' - ' . $p->post_type . '<br/>';
                    }
                            ?></td>
                                <td>
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
                        }
                    } else {
                        ?>
                            
                        <tr>
                            <td colspan="4">No VideoStir videos found</td>
                        </tr>
                            
                        <?php
                    }
                    ?>
                </tbody>
            </table>
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