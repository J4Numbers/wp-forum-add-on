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

?>

<div class="forum" >

<?php

$res = $forum->getAllHeads();

while ($row=$res->fetch()) { ?>

    <div class="head" >
        <h2 class="head_title" ><?php echo $forum->washText($row['name']); ?></h2>
        <p class="head_desc" ><?php echo $forum->washText($row['desc']); ?></p>
    </div>

    <div class="cats" >

        <?php

        $cats = $forum->getCatsForHead($row['ID']);
        while ($cat=$cats->fetch()) {
            $threads = $forum->getThreadsForCatIncRows($cat['ID']);
            $thread = $forum->getLastUpdatedThreadInCat($cat['ID']);
            $collective = $forum->getPostsInCat($cat['ID']);
            if ($thread)
                $thread['name'] = $forum->washText($thread['name']); ?>

            <div class="cat" >
                <div class="cat_text" >
                    <a href="index.php?mode=cat&id=<?php echo $cat['ID'] ?>" >
                        <h3 class="cat_title" ><?php echo $forum->washText($cat['name']); ?></h3>
                    </a>
                    <p class="cat_desc" ><?php echo $forum->washText($cat['desc']); ?></p>
                    <p class="cat_stats"><?php echo $threads->rowCount(); ?> Threads.
                        <?php echo $collective->rowCount(); ?> Total Posts.</p>
                </div>
                <div class="cat_latest" >
                    <?php if (!$thread) { ?>
                        <p>No Threads Created</p>
                    <?php } else { ?>
                        <p>Latest Thread: <a href="index.php?mode=thread&id=<?php echo $thread['ID'];?>">
                            <?php
                            echo (strlen($thread['name']) > 20) ?
                                substr($forum->washText($thread['name']),0,19)."..." :
                                $forum->washText($thread['name']);
                            ?></a>
                        </p>
                        <p>By <?php echo $thread['display_name'] ?></p>
                    <?php } ?>
                </div>
            </div>

        <?php } ?>

    </div>

    <div class="booster" ></div>

<?php }

$recents = $forum->getRecentThreads();

?>

</div>

<div class="stats" >

    <div class="statbox" >
        <div class="head">
            <h2 class="head_title">Recently Created Threads</h2>
        </div>
        <?php while ($recent=$recents->fetch()) {
            $recent_posts = $forum->getPostsForThreadIncRows($recent['ID']); ?>

            <div class="recent thread" >
                <div class="thread_text">
                    <a href="index.php?mode=thread&id=<?php echo $recent['ID']; ?>" >
                        <h3 class="thread_title"><?php echo $forum->washText($recent['name']); ?></h3>
                    </a>
                    <p class="thread_creator">
                        Created by <?php echo $forum->washText($recent['display_name']); ?>
                    </p>
                    <p class="thread_total">
                        <?php echo $recent_posts->rowCount(); ?> posts
                    </p>
                </div>
            </div>
        <?php }  ?>
    </div>

</div>