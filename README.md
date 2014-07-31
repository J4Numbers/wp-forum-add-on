WP Forum Add-on
================================

Introduction
--------------------------------

This is a basic forum which has been tacked onto Wordpress. It uses the Wordpress user database and the permissions
that those users have.

Installation
--------------------------------

To install this forum, download the code and drag it into the root of your Wordpress installation. For example,
if Wordpress is installed in a '/blog/' directory, just drag the contents of the folder into there.

Now your forum is on the system, visit the 'wp-forum-install.php' file in the root of your Wordpress installation.
From here, you will be taken through how to install these forums. You don't need any database details to hand, all
you need to know is what prefix this system is going to be given.

A good default to have is 'wp\_forum\_'.

The system will attempt to change the config file, but if it can't, you'll have to do it yourself. This is easy
and you only need to add two lines to the configuration which are given to you on the screen. If this fails,
you can use the wp\_forum\_config.php file as a reference.

After you've done that, re-visit the 'wp-forum-install.php' file to install the database, and you've successfully installed your forums! Congratulations.

To finish off, go back into the 'wp-forum-config.php' file and _remove_ the line containing 'CY\_DB\_WAITING'.
After that, that's it. You've finished with the basic set-up. Add a link in your theme to the 'forum'
folder, and that's as advanced as you need to get.

There are some details of other items in the /docs/ folder of this repo, they contain this document again, along with a few details on the possible API uses.

If you have any suggestions/improvements, leave an issue in this repo or send an
e-mail to [numbers@cynicode.co.uk](mailto:numbers@cynicode.co.uk).

Good Day.


To-Do List
--------------------------------

- Add icons for the Categories
- Add in major documentation
- Create style for use on a mobile device

Personal Notes
--------------------------------

- wp-forum-config.php is marked as assume-unchanged