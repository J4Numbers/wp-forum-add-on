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

require_once $home_dir."classes/CentralDatabase.php";

class ForumManager extends CentralDatabase {

    public function __construct($home_dir) {
        parent::__construct($home_dir);
    }

    public function getAllHeads() {

        $sql = "SELECT * FROM `wp_forum_heads` ORDER BY `order` ASC";

        try {

            return parent::executeStatement(parent::makePreparedStatement($sql));

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getHeadInfo($id) {

        $vars = array(":head" => $id);

        $sql = "SELECT * FROM `wp_forum_heads` WHERE `ID`=:head";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getAllCats() {

        $sql = "SELECT `wp_forum_cats`.`ID`,`wp_forum_cats`.`name`,`wp_forum_cats`.`desc`,
                `wp_forum_heads`.`name` as `head` FROM `wp_forum_cats`
                INNER JOIN `wp_forum_heads` ON `wp_forum_heads`.`ID`=`wp_forum_cats`.`head`";

        try {

            return parent::executeStatement(parent::makePreparedStatement($sql));

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getCatsForHead($headId) {

        $vars = array(":head" => $headId);

        $sql = "SELECT * FROM `wp_forum_cats` WHERE `head`=:head";

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getCatInfo($id) {

        $vars = array(":cat" => $id);

        $sql = "SELECT * FROM `wp_forum_cats` WHERE `ID`=:cat";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getLastUpdatedThreadInCat($catId) {

        $vars = array(":cat"=>$catId);

        $sql = "SELECT `wp_forum_threads`.`ID`,`name`,`cat`,`wp_forum_threads`.`time`,`creator`,`display_name`
                FROM `wp_forum_threads`
                INNER JOIN `wp_forum_posts` ON `wp_forum_posts`.`thread`=`wp_forum_threads`.`ID`
                INNER JOIN `cyni_wp_users` ON `cyni_wp_users`.`ID`=`wp_forum_posts`.`poster`
                WHERE `cat`=:cat ORDER BY `wp_forum_posts`.`time` DESC LIMIT 1";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);
            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getThreadsForCat($catId,$page,$limit) {

        $vars = array(
            ":cat" => $catId,
        );

        $sql = sprintf(
            "SELECT `wp_forum_threads`.`ID`,`name`,`cat`,`time`,`creator`,`display_name`
            FROM `wp_forum_threads`
            INNER JOIN `cyni_wp_users` ON `cyni_wp_users`.`ID`=`wp_forum_threads`.`creator`
            WHERE `cat`=:cat ORDER BY `time` DESC
            LIMIT %d,%d",(($page-1)*$limit),$limit);

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function calculatePagesInCat($catId,$limit) {

        $vars = array(
            ":cat" => $catId
        );

        $sql = "SELECT count(*) AS `total` FROM `wp_forum_threads`
                WHERE `cat`=:cat";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);
            $row = $res->fetch();

            return ceil($row['total'] / $limit);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getThreadInfo($id) {

        $vars = array(":thread" => $id);

        $sql = "SELECT * FROM `wp_forum_threads` WHERE `ID`=:thread";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function washText($text) {
        return stripslashes($text);
    }

    public function formatText($text) {
        $text = preg_replace("/\n+/i","</p><p>",$this->washText($text));
        return $text;
    }

    public function getPostsForThread($id, $page, $limit) {

        $vars = array(
            ":thread" => $id,
        );

        $sql = sprintf(
            "SELECT `wp_forum_posts`.`ID`,`thread`,`time`,`content`,`poster`,`edited_by`,
            `last_edited`,`display_name` FROM `wp_forum_posts`
            INNER JOIN `cyni_wp_users` ON `cyni_wp_users`.`ID`=`wp_forum_posts`.`poster`
            WHERE `thread`=:thread LIMIT %d,%d",(($page-1)*$limit),$limit);

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getPostsForThreadIncRows($id) {

        $vars = array(
            ":thread" => $id,
        );

        $sql = "SELECT p.*, (@rownum:=@rownum+1) as `rows` FROM `wp_forum_posts` p,
                (SELECT @rownum:=0) r WHERE `thread`=:thread";

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getPostsInCat($id) {

        $vars = array(
            ":cat" => $id
        );

        $sql = "SELECT p.*, t.* FROM `wp_forum_threads` t
                INNER JOIN `wp_forum_posts` p ON t.`ID`=p.`thread`
                WHERE t.`cat`=:cat";

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getThreadsForCatIncRows($id) {

        $vars = array(
            ":cat" => $id
        );

        $sql = "SELECT p.*, (@rownum:=@rownum+1) as `rows` FROM `wp_forum_threads` p,
                (SELECT @rownum:=0) r WHERE `cat`=:cat";

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getRecentThreads() {

        $sql = "SELECT t.*, `display_name` FROM `wp_forum_threads` t
                INNER JOIN `cyni_wp_users` u ON u.`ID`=t.`creator`
                ORDER BY `time` LIMIT 3";

        try  {

            return parent::executeStatement(parent::makePreparedStatement($sql));

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getLastPostInThread($threadId) {

        $vars = array(
            ":id" => $threadId
        );

        $sql = "SELECT `wp_forum_posts`.`ID`,`thread`,`time`,`content`,`poster`,`edited_by`,
                `last_edited`,`display_name` FROM `wp_forum_posts`
                INNER JOIN `cyni_wp_users` ON `cyni_wp_users`.`ID`=`wp_forum_posts`.`poster`
                WHERE `thread`=:id ORDER BY `wp_forum_posts`.`time` DESC LIMIT 1";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);
            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function addHead($title, $desc, $pos) {

        $vars = array(
            ":title" => $title,
            ":desc" => $desc,
            ":pos" => $pos
        );

        $vars2 = array(
            ":pos" => $pos
        );

        $sql = "INSERT INTO `wp_forum_heads` (`name`,`desc`,`order`)
                VALUES (:title, :desc, :pos)";

        $sql2 = "UPDATE `wp_forum_heads` SET `order`=`order`+1
                WHERE `order` >= :pos";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql2),$vars2);
            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function editHead($id, $title, $desc, $pos) {

        $vars3 = array(
            ":id" => $id,
            ":title" => $title,
            ":desc" => $desc,
            ":pos" => $pos
        );

        $head = $this->getHeadInfo($id);

        $vars1 = array(
            ":pos" => $head['pos'],
        );

        $vars2 = array(
            ":pos" => $pos
        );

        $sql3 = "UPDATE `wp_forum_heads` SET
                  `name` = :title,
                  `desc` = :desc,
                  `order` = :pos
                WHERE `ID`=:id";

        $sql1 = "UPDATE `wp_forum_heads` SET `order`=`order`-1
                WHERE `order` >= :pos";

        $sql2 = "UPDATE `wp_forum_heads` SET `order`=`order`+1
                WHERE `order` >= :pos";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql1),$vars1);
            parent::executePreparedStatement(parent::makePreparedStatement($sql2),$vars2);
            parent::executePreparedStatement(parent::makePreparedStatement($sql3),$vars3);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function addCatToHead($title, $desc, $head) {

        $vars = array(
            ":head" => $head,
            ":title" => $title,
            ":desc" => $desc
        );

        $sql = "INSERT INTO `wp_forum_cats` (`head`,`name`,`desc`)
                VALUES (:head, :title, :desc)";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function editCat($id, $title, $desc, $head) {

        $vars = array(
            ":id" => $id,
            ":head" => $head,
            ":title" => $title,
            ":desc" => $desc
        );

        $sql = "UPDATE `wp_forum_cats` SET
                  `name` = :title,
                  `desc` = :desc,
                  `head` = :head
                WHERE `ID`=:id";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function addThreadToCat($cat, $poster, $title ) {

        $vars = array(
            ":cat" => $cat,
            ":poster" => $poster,
            ":title" => $title,
            ":time" => time()
        );

        $sql = "INSERT INTO `wp_forum_threads`
                (`cat`,`name`,`time`,`creator`) VALUES
                (:cat, :title, :time, :poster)";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);
            return parent::getLastInsertId();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function addPostToThread($thread, $poster, $content) {

        $vars = array(
            ":thread" => $thread,
            ":poster" => $poster,
            ":content" => $content,
            ":posted" => time(),
            ":editor" => $poster,
            ":edited" => time()
        );

        $sql = "INSERT INTO `wp_forum_posts`
                (`thread`,`poster`,`time`,`content`,`edited_by`,`last_edited`) VALUES
                (:thread, :poster, :posted, :content, :editor, :edited)";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function editPost($id, $content, $editor) {

        $vars = array(
            ":post" => $id,
            ":content" => $content,
            ":editor" => $editor,
            ":edited" => time()
        );

        $sql = "UPDATE `wp_forum_posts` SET
                `content` = :content,
                `edited_by` = :editor,
                `last_edited` = :edited
                WHERE `ID`=:post";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getPostInfo($postId) {

        $vars = array(":id"=>$postId);

        $sql = "SELECT p.`ID`,`thread`,`poster`,`time`,`content`,`edited_by`,
                `last_edited`,`display_name`
                FROM `wp_forum_posts` p
                INNER JOIN `cyni_wp_users` u ON u.`ID`=p.`poster`
                WHERE p.`ID`=:id";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);
            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function calculatePagesInThread($id, $limit) {

        $vars = array(
            ":thread" => $id
        );

        $sql = "SELECT count(*) AS `total` FROM `wp_forum_posts`
                WHERE `thread`=:thread";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);
            $row = $res->fetch();

            return ceil($row['total'] / $limit);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    /**
     * @param int $current : Current page
     * @param int $pages : Total number of reachable pages
     * @param string $type : string for the type. i.e. 'thread'
     * @param int $id : The ID of the previous type
     * @param int $range : Range from current that can be reached immediately
     * @return string : The generated pagination for this
     */
    public function generateViewablePages($current, $pages, $type, $id, $range) {
        switch($pages) {
            case ($pages==1) :
                $viewable = "<li class='pages_first pages_last'>
                         <a href='index.php?mode=".$type."&id=".$id."&page=1'>1</a>
                     </li>";
                break;

            case ($pages<=5) :
                $viewable = "<li class='pages_first'>
                        <a href='index.php?mode=".$type."&id=".$id."&page=1'>1</a>
                     </li>";

                for ($i=2;$i<$pages;++$i)
                    $viewable .= "<li>
                             <a href='index.php?mode=".$type."&id=".$id."&page=".$i."'>".$i."</a>
                          </li>";

                $viewable .= "<li class='pages_last' >
                        <a href='index.php?mode=".$type."&id=".$id."&page=".$pages."'>".$pages."</a>
                      </li>";
                break;

            default : {

                $viewable = "<li class='pages_first'>
                        <a href='index.php?mode=".$type."&id=".$id."&page=1'>1</a>
                     </li>";

                if (($current - $range - 1) > 1)
                    $viewable .= "<li>...</li>";

                for ($i=($current-$range);$i<=($current+$range);++$i)
                    if ($i>1 && $i<$pages)
                        $viewable .= "<li>
                                 <a href='index.php?mode=".$type."&id=".$id."&page=".$i."'>".$i."</a>
                              </li>";

                if (($current + $range + 1) < $pages)
                    $viewable .= "<li>...</li>";

                $viewable .= "<li class='pages_last' >
                        <a href='index.php?mode=".$type."&id=".$id."&page=".$pages."'>".$pages."</a>
                      </li>";

            }

        }

        return $viewable;

    }

}