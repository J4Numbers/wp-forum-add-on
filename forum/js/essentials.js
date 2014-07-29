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

window.onload = function() {

    console.log(localStorage);
    var active = (localStorage['allow_mature']!="1") ? "Enable Mature Content" : "Disable Mature Content";
    var top = $('.mature');
    $(top[0]).html("<a href='' onclick='toggleMature()'>"+active+"</a>");

    var nsfs = $('.nsfw');

    if (localStorage['allow_mature']!="1") {
        for ( var i=0; i<nsfs.length; ++i ) {
            $(nsfs[i]).html( "Please log in and enable mature content to see this content." );
        }
    }
};

function toggleMature() {
    if (localStorage['allow_mature']!=1) {
        var c = confirm("Please confirm that you are of the legal age to view mature material in your country.");
        if (!c) return;
    }

    localStorage['allow_mature'] = (localStorage['allow_mature']!="1") ? "1" : "0";
    location.reload();
}

function resetHeadId() {
    $('#a_head_id').val(0);
}

function resetCatId() {
    $('#a_cat_id').val(0);
}

function editHead(id) {

    $.post("scripts/getHead.php",{id:id, get:true}).done(function(data) {
        var head = JSON.parse(data);
        console.log(head);
        $('#a_head_id').val(head.ID);
        $('#a_head_title').val(head.name);
        $('#a_head_desc').val(head.desc);
        $('#a_head_order').val(head.ID);
    });

}

function editCat(id) {

    $.post("scripts/getCat.php",{id:id, get:true}).done(function(data) {
        var cat = JSON.parse(data);
        $('#a_cat_id').val(cat.ID);
        $('#a_cat_title').val(cat.name);
        $('#a_cat_desc').val(cat.desc);
        $('#a_cat_head').val(cat.head);
    });

}

function quotePost(postId) {

    var data = $('#p'+postId).find('.post_data');

    $.post("scripts/getPost.php", {id:postId, get:true}).done(function(data) {
        var post = JSON.parse(data);
        $('#reply_content').insertAtCaret("[quote="+post.display_name+"]"+post.content+"[/quote]");
        $('html, body').animate({
            scrollTop: $("#reply").offset().top
        }, 1000);
    })

}

function generateBBCode(code, target) {
    $(target).insertAtCaret(code);
}