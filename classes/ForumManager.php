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

    public function __construct($home_dir,$f_prefix,$wp_prefix) {
        parent::__construct($home_dir,$f_prefix,$wp_prefix);
    }

    public function install($sql) {
        parent::executeStatement(parent::makePreparedStatement($sql));
    }

    public function getAllHeads() {

        $sql = "SELECT * FROM `~heads` ORDER BY `order` ASC";

        try {

            return parent::executeStatement(parent::makePreparedStatement($sql));

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function isOriginalPost($post, $thread) {

        $vars = array(":id"=>$thread);

        $sql = "SELECT `ID` FROM `~posts`
                WHERE `thread`=:id ORDER BY `ID` ASC LIMIT 1";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

            if ($res->rowCount()==0) {
                return false;
            } else {
                $row = $res->fetch();
                return $post == $row['ID'];
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getHeadInfo($id) {

        $vars = array(":head" => $id);

        $sql = "SELECT * FROM `~heads` WHERE `ID`=:head";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getAllCats() {

        $sql = "SELECT c.`ID`,c.`name`,c.`desc`,
                h.`name` as `head` FROM `~cats` c
                INNER JOIN `~heads` h ON h.`ID`=c.`head`";

        try {

            return parent::executeStatement(parent::makePreparedStatement($sql));

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getCatsForHead($headId) {

        $vars = array(":head" => $headId);

        $sql = "SELECT * FROM `~cats` WHERE `head`=:head";

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getCatInfo($id) {

        $vars = array(":cat" => $id);

        $sql = "SELECT * FROM `~cats` WHERE `ID`=:cat";

        try {

            $res = parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

            return ($res->rowCount()==0) ? false : $res->fetch();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getLastUpdatedThreadInCat($catId) {

        $vars = array(":cat"=>$catId);

        $sql = "SELECT t.`ID`,`name`,`cat`,t.`time`,`creator`,`display_name`
                FROM `~threads` t
                INNER JOIN `~posts` p ON p.`thread`=t.`ID`
                INNER JOIN `#users` u ON u.`ID`=p.`poster`
                WHERE `cat`=:cat ORDER BY p.`time` DESC LIMIT 1";

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
            "SELECT t.`ID`,`name`,`cat`,`time`,`creator`,`display_name`
            FROM `~threads` t
            INNER JOIN `#users` u ON u.`ID`=t.`creator`
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

        $sql = "SELECT count(*) AS `total` FROM `~threads`
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

        $sql = "SELECT * FROM `~threads` WHERE `ID`=:thread";

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
            "SELECT p.`ID`,`thread`,`time`,`content`,`poster`,`edited_by`,
            `last_edited`,`display_name` FROM `~posts` p
            INNER JOIN `#users` u ON u.`ID`=p.`poster`
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

        $sql = "SELECT p.*, (@rownum:=@rownum+1) as `rows` FROM `~posts` p,
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

        $sql = "SELECT p.*, t.* FROM `~threads` t
                INNER JOIN `~posts` p ON t.`ID`=p.`thread`
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

        $sql = "SELECT p.*, (@rownum:=@rownum+1) as `rows` FROM `~threads` p,
                (SELECT @rownum:=0) r WHERE `cat`=:cat";

        try {

            return parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function getRecentThreads() {

        $sql = "SELECT t.*, `display_name` FROM `~threads` t
                INNER JOIN `#users` u ON u.`ID`=t.`creator`
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

        $sql = "SELECT p.`ID`,`thread`,`time`,`content`,`poster`,`edited_by`,
                `last_edited`,`display_name` FROM `~posts` p
                INNER JOIN `#users` u ON u.`ID`=p.`poster`
                WHERE `thread`=:id ORDER BY p.`time` DESC LIMIT 1";

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

        $sql = "INSERT INTO `~heads` (`name`,`desc`,`order`)
                VALUES (:title, :desc, :pos)";

        $sql2 = "UPDATE `~heads` SET `order`=`order`+1
                WHERE `order` >= :pos";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql2),$vars2);
            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function editHead($id, $title, $desc, $pos) {

        $head = $this->getHeadInfo($id);

        if ($head['pos'] == $pos-1)
            $pos = $head['pos'];

        $vars3 = array(
            ":id" => $id,
            ":title" => $title,
            ":desc" => $desc,
            ":pos" => $pos
        );

        $vars1 = array(
            ":pos" => $head['pos'],
        );

        $vars2 = array(
            ":pos" => $pos
        );

        $sql3 = "UPDATE `~heads` SET
                  `name` = :title,
                  `desc` = :desc,
                  `order` = :pos
                WHERE `ID`=:id";

        $sql1 = "UPDATE `~heads` SET `order`=`order`-1
                WHERE `order` >= :pos";

        $sql2 = "UPDATE `~heads` SET `order`=`order`+1
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

        $sql = "INSERT INTO `~cats` (`head`,`name`,`desc`)
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

        $sql = "UPDATE `~cats` SET
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

        $sql = "INSERT INTO `~threads`
                (`cat`,`name`,`time`,`creator`) VALUES
                (:cat, :title, :time, :poster)";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);
            return parent::getLastInsertId();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function editThread($id, $title) {

        $vars = array(
            ":id" => $id,
            ":title" => $title
        );

        $sql = "UPDATE `~threads` SET
                `name`=:title
                WHERE `ID`=:id";

        try {

            parent::executePreparedStatement(parent::makePreparedStatement($sql),$vars);

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

        $sql = "INSERT INTO `~posts`
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

        $sql = "UPDATE `~posts` SET
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
                FROM `~posts` p
                INNER JOIN `#users` u ON u.`ID`=p.`poster`
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

        $sql = "SELECT count(*) AS `total` FROM `~posts`
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