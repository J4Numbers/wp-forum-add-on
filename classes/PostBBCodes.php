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

class PostBBCodes implements JBBCode\CodeDefinitionSet {

    private $definitions = array();

    public function __construct() {

        $builder = new JBBCode\CodeDefinitionBuilder('strike','<del>{param}</del>');
        array_push($this->definitions, $builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('quote',
            '<blockquote class="quote">
            <p class="author">{option} wrote...</p>
            <div class="quoted" >
            <p>{param}</p>
            </div>
            </blockquote>');
        $builder->setUseOption(true);
        array_push($this->definitions, $builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('size',
            '<span style="font-size:{option}em;">{param}</span>');
        $builder->setUseOption(true);
        array_push($this->definitions, $builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('nsfw', '<span class="nsfw">{param}</span>');
        array_push($this->definitions, $builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('spoiler','<span class="spoiler">{param}</span>');
        array_push($this->definitions, $builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('youtube',
            '<object class="youtube" type="application/x-shockwave-flash"
              data="http://www.youtube.com/v/{param}?controls=0&amp;modestbranding=1">
                <param name="movie" value="http://www.youtube.com/v/{param}?controls=0&amp;modestbranding=1">
             </object>');
        array_push($this->definitions, $builder->build());
    }

    /**
     * Retrieves the CodeDefinitions within this set as an array.
     */
    public function getCodeDefinitions() {
        return $this->definitions;
    }
}