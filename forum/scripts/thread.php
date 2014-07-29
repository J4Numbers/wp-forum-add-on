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

$session = new SessionManager();
$forum = new ForumManager($home_dir, CY_FORUM_PREFIX, $table_prefix);

if (!$session->isLoggedIn())
    header("Location: ".site_url("/forum/index.php?mode=login"));

$thread = $forum->addThreadToCat(
      $_POST['cat'],
      $session->getUserId(),
      $_POST['title']
);

$forum->addPostToThread($thread, $session->getUserId(), $_POST['content']);

header("Location: ". site_url('/forum/index.php?mode=thread&id='.$thread) );