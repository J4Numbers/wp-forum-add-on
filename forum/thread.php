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

$thread = $forum->getThreadInfo($_GET['id']);

if (!$thread) die;

$pages = $forum->calculatePagesInThread($_GET['id'],$limit);

$viewable = $forum->generateViewablePages($cur_page,$pages,'thread',$_GET['id'],$range);

?>

<div class="forum wide" >

    <div class="head" >
        <h2 class="head_title" ><?php echo $forum->washText($thread['name']); ?></h2>
        <ul class="head_pages">
            <?php echo $viewable ?>
        </ul>
    </div>

    <div class="posts">

        <?php

        $posts = $forum->getPostsForThread($_GET['id'],$cur_page,$limit);

        while ($post=$posts->fetch()) {
            $parser->parse($forum->formatText($post['content']));
            ?>

            <div class="post" id="p<?php echo $post['ID']; ?>" >
                <div class="post_meta" >
                    <div class="post_avatar" ><?php echo get_avatar($post['poster'],96); ?></div>
                    <p class="post_creator"><?php echo $post['display_name']; ?></p>
                    <p class="post_created">Posted <?php echo date('jS F Y, G:i',$post['time']); ?></p>
                    <p class="post_edited">
                        Last edited <?php echo date('jS F Y, G:i',$post['last_edited']); ?> by
                        <?php echo $forum->getUserName($post['edited_by']); ?>
                    </p>
                    <p class="post_perma"><a href="index.php?mode=thread&id=<?php
                        echo $_GET['id']; ?>#p<?php echo $post['ID']; ?>" >Permalink</a></p>
                    <?php if ($session->isLoggedIn()) { ?>
                        <p class="post_quote">
                            <a onclick="quotePost(<?php echo $post['ID']; ?>)" >Quote Post</a>
                        </p>
                    <?php } ?>
                    <?php if ($session->canEditPost($post['ID'],$forum)) { ?>
                        <p class="post_functions" >
                            <a href="index.php?mode=edit&id=<?php echo $post['ID']; ?>">Edit Post</a>
                        </p>
                    <?php } ?>
                </div>
                <div class="post_data" >
                    <p><?php echo $parser->getAsHTML() ?></p>
                </div>
            </div>

        <?php } ?>

    </div>

    <div class="booster" ></div>

    <ul class="head_pages">
        <?php echo $viewable; ?>
    </ul>

    <div class="booster" ></div>

    <div class="reply" id="reply" >

        <div class="head" >
            <h2 class="head_title">Leave a Reply</h2>
        </div>

        <div class="booster"></div>

        <?php if ($session->isLoggedIn()) { ?>

            <form method="post" action="scripts/post.php" >
                <input type="hidden" name="thread" value="<?php echo $_GET['id']; ?>" />
                <ul class="reply_bbcode" >
                    <li onclick="generateBBCode('[b][/b]','#reply_content');">b</li>
                    <li onclick="generateBBCode('[i][/i]','#reply_content');">i</li>
                    <li onclick="generateBBCode('[u][/u]','#reply_content');">u</li>
                    <li onclick="generateBBCode('[color=#000000]Text[/color]','#reply_content');">color</li>
                    <li onclick="generateBBCode('[size=1]Text[/size]','#reply_content');">size</li>
                    <li onclick="generateBBCode('[strike][/strike]','#reply_content');">strike</li>
                    <li onclick="generateBBCode('[url=Link]Text[/url]','#reply_content');">url</li>
                    <li onclick="generateBBCode('[img][/img]','#reply_content');">img</li>
                    <li onclick="generateBBCode('[spoiler][/spoiler]','#reply_content');">spoiler</li>
                    <li onclick="generateBBCode('[quote=Name][/quote]','#reply_content');">quote</li>
                    <li onclick="generateBBCode('[nsfw][/nsfw]','#reply_content');">nsfw</li>
                    <li onclick="generateBBCode('[youtube][/youtube]','#reply_content');">youtube</li>
                </ul>
                <div class="line" ></div>
                <textarea class="reply_content" id="reply_content" name="reply" ></textarea>
                <input type="submit" class="reply_submit" value="Reply" />
            </form>

        <?php } else { ?>

            <p>Log in to leave a reply.</p>

        <?php } ?>

    </div>

</div>