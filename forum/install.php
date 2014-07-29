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

if (!$session->canAdmin() || defined('FORUM_PREFIX'))
    header("Location: ".site_url('/forum/'));

?>

<div class="forum wide" >

    <div class="head" >
        <h2 class="head_title" >Installation Panel</h2>
        <p class="head_desc" >Settings</p>
    </div>

    <div class="booster"></div>

    <form class="install_form" action="scripts/install.php" method="post" >
        <div class="install_prefix" >
            <p><label for="install_prefix" >Database Prefix</label></p>
            <input type="text" id="install_prefix" name="prefix" class="input" />
        </div>
        <div class="install_submit">
            <input type="submit" id="install_submit" />
        </div>
    </form>

    <div class="booster" ></div>

</div>