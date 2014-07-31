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
            <ul class="reply_bbcode" >
                <li onclick="generateBBCode('[b][/b]','#edit_reply');">b</li>
                <li onclick="generateBBCode('[i][/i]','#edit_reply');">i</li>
                <li onclick="generateBBCode('[u][/u]','#edit_reply');">u</li>
                <li onclick="generateBBCode('[color=#000000]Text[/color]','#edit_reply');">color</li>
                <li onclick="generateBBCode('[size=1]Text[/size]','#edit_reply');">size</li>
                <li onclick="generateBBCode('[strike][/strike]','#edit_reply');">strike</li>
                <li onclick="generateBBCode('[url=Link]Text[/url]','#edit_reply');">url</li>
                <li onclick="generateBBCode('[img][/img]','#edit_reply');">img</li>
                <li onclick="generateBBCode('[spoiler][/spoiler]','#edit_reply');">spoiler</li>
                <li onclick="generateBBCode('[quote=Name][/quote]','#edit_reply');">quote</li>
                <li onclick="generateBBCode('[nsfw][/nsfw]','#edit_reply');">nsfw</li>
                <li onclick="generateBBCode('[youtube][/youtube]','#edit_reply');">youtube</li>
            </ul>
            <div class="line" ></div>
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