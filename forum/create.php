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

if (!$session->isLoggedIn() || !isset($_GET['id']))
    header("Location: ".site_url('/forum/'));

$cat = $forum->getCatInfo($_GET['id']);

if (!$cat)
    header("Location: ".site_url('/forum/'));

?>

<div class="forum wide" >

    <div class="head" >
        <h2 class="head_title" >Creating Thread in <?php echo $forum->washText($cat['name']); ?></h2>
    </div>

    <div class="booster"></div>

        <form class="thread_form" action="scripts/thread.php" method="post" >
            <input type="hidden" name="cat" value="<?php echo $cat['ID']; ?>" />
            <div class="title" >
                <p><label for="thread_title" >Thread Title</label></p>
                <input type="text" id="thread_title" name="title" class="input" />
            </div>
            <div class="content" >
                <p><label for="thread_content">Thread Contents</label></p>
                <textarea id="thread_content" name="content" class="input" ></textarea>
            </div>
            <div class="thread_submit">
                <input type="submit" id="thread_submit" />
            </div>
        </form>

    <div class="booster" ></div>

</div>