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

if ($session->isLoggedIn())
    header("Location: ".site_url('/forum/'));

$args = array(
    'echo'           => true,
    'redirect'       => site_url( '/forum/' ),
    'form_id'        => 'f_wp_login',
    'label_username' => __( 'Username' ),
    'label_password' => __( 'Password' ),
    'label_remember' => __( 'Remember Me' ),
    'label_log_in'   => __( 'Log In' ),
    'id_username'    => 'f_user_login',
    'id_password'    => 'f_user_pass',
    'id_remember'    => 'f_rememberme',
    'id_submit'      => 'f_wp-submit',
    'remember'       => true,
    'value_username' => NULL,
    'value_remember' => false
);

?>

<div class="forum wide" >

<div class="head" >
    <h2 class="head_title" >Log In</h2>
</div>

<div class="booster"></div>

    <?php wp_login_form($args); ?>

<div class="booster" ></div>

</div>

