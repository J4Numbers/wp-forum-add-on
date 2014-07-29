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

if (!$session->canAdmin())
    header("Location: ".site_url('/forum/'));

?>

<div class="forum wide" >

    <div class="head" >
        <h2 class="head_title" >Administration Panel</h2>
        <p class="head_desc" >Alter Headings</p>
    </div>

    <div class="booster"></div>

    <form class="a_head_form" action="scripts/head.php" method="post" >
        <input type="hidden" id="a_head_id" name="id" value="0" />
        <div class="a_head_title" >
            <p><label for="a_head_title" >Heading Title</label></p>
            <input type="text" id="a_head_title" name="title" class="input" />
        </div>
        <div class="a_head_desc" >
            <p><label for="a_head_desc">Heading Description</label></p>
            <input type="text" id="a_head_desc" name="desc" class="input" />
        </div>
        <div class="a_head_order" >
            <p><label for="a_head_order">Heading Order</label></p>
            <select id="a_head_order" name="order" >
                <option value="1" >First</option>
                <?php

                $heads = $forum->getAllHeads();
                while ($head=$heads->fetch()) {
                    echo "<option value=".($head['order']+1).">After '".$forum->washText($head['name'])."'</option>";
                }

                ?>
            </select>
        </div>
        <div class="a_head_submit">
            <input type="submit" id="a_head_submit" />
            <input type="reset" id="a_head_submit" onclick="resetHeadId();" />
        </div>
    </form>

    <div class="booster" ></div>

    <div class="admin_table" >
        <table>
            <thead>
                <tr>
                    <td>Title</td>
                    <td>Description</td>
                </tr>
            </thead>
            <?php
            $heads = $forum->getAllHeads();
            while ($head=$heads->fetch()) { ?>
                <tr onclick="editHead(<?php echo $head['ID']; ?>)" >
                    <td><?php echo $forum->washText($head['name']); ?></td>
                    <td><?php echo $forum->washText($head['desc']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="booster" ></div>

    <div class="head" >
        <h2 class="head_title" >Administration Panel</h2>
        <p class="head_desc" >Alter Categories</p>
    </div>

    <div class="booster"></div>

    <form class="a_cat_form" action="scripts/category.php" method="post" >
        <input type="hidden" id="a_cat_id" name="id" value="0" />
        <div class="a_cat_title" >
            <p><label for="a_cat_title" >Category Title</label></p>
            <input type="text" id="a_cat_title" name="title" class="input" />
        </div>
        <div class="cat_desc" >
            <p><label for="a_cat_desc">Category Description</label></p>
            <input type="text" id="a_cat_desc" name="desc" class="input" />
        </div>
        <div class="a_cat_head" >
            <p><label for="a_cat_head">Category Heading</label></p>
            <select id="a_cat_head" name="heading" >
                <?php

                $heads = $forum->getAllHeads();
                while ($head=$heads->fetch()) {
                    echo "<option value=".$head['ID'].">".$forum->washText($head['name'])."</option>";
                }

                ?>
            </select>
        </div>
        <div class="a_cat_submit">
            <input type="submit" id="a_cat_submit" />
            <input type="reset" id="a_cat_submit" onclick="resetCatId();" />
        </div>
    </form>

    <div class="booster" ></div>

    <div class="admin_table" >
        <table>
            <thead>
                <tr>
                    <td>Title</td>
                    <td>Description</td>
                    <td>Heading</td>
                </tr>
            </thead>
            <?php
            $cats = $forum->getAllCats();
            while ($cat=$cats->fetch()) { ?>
                <tr onclick="editCat(<?php echo $cat['ID']; ?>);">
                    <td><?php echo $forum->washText($cat['name']); ?></td>
                    <td><?php echo $forum->washText($cat['desc']); ?></td>
                    <td><?php echo $forum->washText($cat['head']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="booster" ></div>

</div>