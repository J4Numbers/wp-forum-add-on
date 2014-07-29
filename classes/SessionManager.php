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

class SessionManager {

    public function __construct() {
        session_start();
    }

    public function isLoggedIn() {
        return is_user_logged_in();
    }

    public function canAdmin() {
        return current_user_can('manage_options');
    }

    /**
     * @param int $postId
     * @param ForumManager $forum
     * @return bool
     */
    public function canEditPost($postId, $forum) {

        if ($this->canAdmin())
            return true;
        if (!$this->isLoggedIn())
            return false;

        $post = $forum->getPostInfo($postId);
        return $post['poster']==$this->getUserId();

    }

    public function getUserId() {
        return get_current_user_id();
    }

    public function getUserName() {
        return wp_get_current_user()->display_name;
    }

    public function logout() {
        wp_logout();
    }

} 