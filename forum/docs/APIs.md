API Capabilities
=============================

As with any forum, there are certain items can be transcribed into an API for people to use.

As such... it's less of a class system and more of a polling system... but still... small steps.

Within the /forum/scripts/ folder, there are a few items such as the getPosts.php, getCat.php, and
getHead.php. All of these do exactly what they say on their tin. They get the details on their
respective post, category, or heading.

All you need to do is provide each file with an 'id' value in the POST variable.

Obviously, this means that it's not really usable for a url + get\_page\_contents (or whatever that
function is called) because there are no GET variables to just keep in the URL. What I've been using
it with is javascript to drag the json data from those pages.

Head
-----------------------------

Input(s): 'id' attribute in the POST variable.

Output(s): JSON string array: ID, name, desc, order.

Category
------------------------------

Input(s): 'id' attribute in the POST variable.

Output(s): JSON string array: ID, name, desc, head.

Post
------------------------------

Input(s): 'id' attribute in the POST variable.

Output(s): JSON string array: ID, thread, time, content, poster, last\_edited, edited\_by.