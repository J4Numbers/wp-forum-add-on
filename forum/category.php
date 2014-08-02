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

$cat = $forum->getCatInfo($_GET['id']);

if (!$cat) die;

$pages = $forum->calculatePagesInCat($_GET['id'],$limit);

$viewable = $forum->generateViewablePages($cur_page,$pages,'cat',$_GET['id'],$range);

?>

<div class="forum wide" >

    <div class="head" >
        <h2 class="head_title" ><?php echo $forum->washText($cat['name']); ?></h2>
        <p class="head_desc" ><?php echo $forum->washText($cat['desc']); ?></p>
        <div class="iphone_line" ></div>
        <ul class="head_pages">
            <?php echo $viewable; ?>
        </ul>
        <?php if ($session->isLoggedIn()) { ?>
            <div class="head_make">
                <a href="index.php?mode=create&id=<?php echo $cat['ID'];?>">Create Thread</a>
            </div>
        <?php } ?>
    </div>

    <div class="threads">

        <?php

        $threads = $forum->getThreadsForCat($_GET['id'],$cur_page,$limit);

        while ($thread=$threads->fetch()) {
            $post = $forum->getLastPostInThread($thread['ID']);
            $posts = $forum->getPostsForThreadIncRows($thread['ID']);

            while ($rowP = $posts->fetch()) {
                if ($rowP['ID'] != $post['ID']) {
                } else { ?>

                    <div class="thread" >
                        <div class="thread_avatar" >
                            <?php echo get_avatar($thread['creator'],78); ?>
                        </div>
                        <div class="thread_text">
                            <a href="index.php?mode=thread&id=<?php echo $thread['ID']; ?>" >
                                <h3 class="thread_title"><?php echo $forum->washText($thread['name']); ?></h3>
                            </a>
                            <p class="thread_creator">
                                Created by <?php echo $forum->washText($thread['display_name']); ?>
                            </p>
                            <p class="thread_total">
                                <?php echo $posts->rowCount(); ?> posts
                            </p>
                        </div>
                        <div class="thread_latest" >
                            <p>Last post by <?php echo $post['display_name']; ?></p>
                            <p>On <?php echo date('jS M Y',$post['time']); ?></p>
                            <p><a href="index.php?mode=thread&id=<?php echo $thread['ID']; ?>&page=<?php
                                echo ceil($rowP['rows'] / $limit);?>#p<?php echo $rowP['ID'];?>"
                                    >Latest Post</a>
                            </p>
                        </div>
                    </div>

        <?php } } } ?>

    </div>

    <div class="booster" ></div>

    <ul class="head_pages">
        <?php echo $viewable; ?>
    </ul>
    <?php if ($session->isLoggedIn()) { ?>
        <div class="head_make">
                <a href="index.php?mode=create&id=<?php echo $cat['ID'];?>">Create Thread</a>
            </div>
    <?php } ?>

</div>