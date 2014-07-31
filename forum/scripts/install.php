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

$home_dir = getcwd()."/../../";

define('WP_USE_THEMES',false);
require_once $home_dir."wp-blog-header.php";
require_once $home_dir."wp-forum-config.php";
require_once $home_dir."classes/ForumManager.php";
require_once $home_dir."classes/SessionManager.php";

if (defined('CY_FORUM_PREFIX') && !defined('CY_DB_WAITING'))
    header("Location: ".site_url("/forum/"));

$f_prefix = defined('CY_DB_WAITING') ? CY_FORUM_PREFIX : $_POST['prefix'];

$session = new SessionManager();
$forum = new ForumManager($home_dir,$f_prefix,$table_prefix);

try {
    if (!defined('CY_DB_WAITING')) {
        if (!is_writable(site_url('/wp-forum-config.php'))) {
            die("
                <p>File was not writable. Please add in the following lines to 'wp-forum-config.php':</p>
                <code>
                    define('CY_FORUM_PREFIX','".$_POST['prefix']."');".PHP_EOL."
                    define('CY_DB_WAITING','1');
                </code>
                <p>Once this has been done, go back to 'wp-forum-install.php' to continue.");
        } else {

            $handle = fopen(site_url('/wp-forum-config.php'),'a');

            fwrite($handle, PHP_EOL."define('CY_FORUM_PREFIX','".$_POST['prefix']."');");
            fwrite($handle, PHP_EOL."define('CY_DB_WAITING','1');");

        }
    }

    $forum->install(file_get_contents(site_url('/forum/sql/retrofit.sql')));

} catch (PDOException $e) {

    die($e->getMessage());

}

header("Location: ". site_url('/forum/index.php?mode=installed') );