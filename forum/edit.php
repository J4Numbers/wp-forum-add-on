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

if (!$session->canEditPost($_GET['id'],$forum))
    header("Location: ".site_url('/forum/'));

$post = $forum->getPostInfo($_GET['id']);
$thread = $forum->getThreadInfo($post['thread']);

?>

<div class="forum wide" >

    <div class="head" >
        <h2 class="head_title" >Edit Post</h2>
        <p class="head_desc" >from thread '<?php echo $forum->washText($thread['name']); ?>'</p>
    </div>

    <div class="booster"></div>

    <form class="edit_form" action="scripts/post.php" method="post" >
        <input type="hidden" name="id" value="<?php echo $post['ID']; ?>" />
        <input type="hidden" name="thread" value="<?php echo $thread['ID']; ?>" />

        <?php if ($forum->isOriginalPost($post['ID'],$thread['ID'])) { ?>

            <div class="edit_title" >
                <p><label for="edit_title">Thread Title</label></p>
                <input id="edit_title" name="title" class="input" value="<?php
                echo $forum->washText($thread['name']); ?>" />
            </div>

        <?php } ?>

        <div class="edit_reply" >
            <p><label for="edit_reply">Thread Reply</label></p>
            <textarea id="edit_reply" name="reply" class="input" ><?php
                echo $forum->washText($post['content']);
            ?></textarea>
        </div>
        <div class="edit_submit">
            <input type="submit" id="edit_submit" />
        </div>
    </form>

    <div class="booster" ></div>

</div>