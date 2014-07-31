<?php
/**
 * Copyright 2014 Matthew David Ball (numbers@cynicode.co.uk)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//ini_set('display_errors','1');

$home_dir = getcwd()."/../";

define('WP_USE_THEMES',false);
require_once $home_dir."wp-blog-header.php";
require_once $home_dir."wp-forum-config.php";
require_once $home_dir."classes/SessionManager.php";
require_once $home_dir."classes/ForumManager.php";
require_once $home_dir."classes/JBBCode/Parser.php";
require_once $home_dir."classes/PostBBCodes.php";

if ($_GET["mode"]=="installed") {
    $test = new ForumManager($home_dir,CY_FORUM_PREFIX,$table_prefix);
    if (!$test->installed()) {
        header("Location: ".site_url('/wp-forum-install.php'));
    }
} else {
    if (!defined("CY_FORUM_PREFIX") || defined("CY_DB_WAITING") ) {
        if ( $_GET['mode'] != "install" ) {
            header("Location: ".site_url('/wp-forum-install.php'));
        }
    }
}

if (!defined("CY_FORUM_PREFIX"))
    define("CY_FORUM_PREFIX","");

$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
$parser->addCodeDefinitionSet(new PostBBCodes());
$session = new SessionManager();
$forum = new ForumManager($home_dir, CY_FORUM_PREFIX, $table_prefix);

$limit = 10;
$range = 2;
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;

$loc = "<a href='index.php'>Forum</a>";

if ($_GET['mode']=="cat") {

    $cat = $forum->getCatInfo($_GET['id']);
    $loc .= " / <a href='index.php?mode=cat&id=".$_GET['id']."'>".$forum->washText($cat['name'])."</a>";

}

if ($_GET['mode']=="thread") {

    $thread = $forum->getThreadInfo($_GET['id']);
    $cat = $forum->getCatInfo($thread['cat']);
    $loc .= " / <a href='index.php?mode=cat&id=".$cat['ID']."'>".$forum->washText($cat['name'])."</a>";
    $loc .= " / <a href='index.php?mode=thread&id=".$_GET['id']."'>".$forum->washText($thread['name'])."</a>";

}

?>

<link type="text/css" rel="stylesheet" href="<?php echo site_url('/forum/css/style.css'); ?>" />
<script type="text/javascript" src="<?php echo site_url('/forum/js/essentials.js');?>"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js" ></script>
<script type="text/javascript" src="<?php echo site_url('/forum/js/insert.js'); ?>" ></script>

<?php

get_header();

?>

<div class="booster" ></div>

<div class="topbar" >

    <div class="forum_location" ><?php echo $loc; ?></div>

    <?php if ($session->isLoggedIn()) { ?>
        Logged in as <?php echo $session->getUserName(); ?> |
        <span class="mature" ></span> |
        <?php if ($session->canAdmin()) { ?>
            <a href="index.php?mode=admin">Admin Panel</a> |
        <?php } ?>

        <a href="<?php echo site_url('/wp-login.php'); ?>?action=logout&redirect_to=<?php echo site_url('/forum/');
        ?>">Log Out</a>
    <?php } else { ?>
        <a href="index.php?mode=login" >Log In</a> |
        <a href="<?php echo site_url('/wp-login.php'); ?>?action=register&redirect_to=<?php echo site_url('/forum/');
        ?>">Register</a>
    <?php } ?>

</div>

<div class="booster" ></div>

<div id="spacing" >

    <?php
        switch ($_GET['mode']) {

            case "cat" :
                require_once $home_dir."forum/category.php";
                break;

            case "thread" :
                require_once $home_dir."forum/thread.php";
                break;

            case "login" :
                require_once $home_dir."forum/login.php";
                break;

            case "create" :
                require_once $home_dir."forum/create.php";
                break;

            case "edit" :
                require_once $home_dir."forum/edit.php";
                break;

            case "admin" :
                require_once $home_dir."forum/admin.php";
                break;

            case "install" :
                require_once $home_dir."forum/install.php";
                break;

            case "installed" :
                require_once $home_dir."forum/installed.php";
                break;

            default : {
                require_once $home_dir."forum/home.php";
                break;
            }

        }
    ?>

</div>

<div class="booster" ></div>

<?php

get_footer();