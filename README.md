Asciimega
====
Project to create and play ascii animations online

##Disclaimer:
![No Idea](http://www.aux.tv/wp-content/uploads/2013/11/i-have-no-idea-what-im-doing-science-dog.jpg)

Current public version lives [here](https://tomatenbrei.cloudapp.net/ascii)  

If you want to copy this project to work on it, follow these steps:

Things you will need:
- Apache Webserver (Optional, because I use apache)
* PHP
* MySQL
* Some Magic
* More Magic


1. Clone the project in a PRIVATE directory (git dir). Now Copy/Override the project in a public directory (test dir). Only use the git dir for uploading/downloading stuff. Not for testing. The version management of git will delete the animations etc. otherwise each time you commit.
2. In `ref/config.php`, insert the path of the font in `$config_captcha` ('fontpath') (found at: `BASEPATH/captcha/XFILES.TTF`)
3. Configure database access in `sample.mysql_connect.php`. When you are done, rename the file to `mysql_connect.php`(if you are building this project for the first time).
4. Run create_tables.php to create the tables (if you are building this project for the first time).
5. When uploading an animation, files are moved into the `files/` directory. Make sure that php has the rights to write into that directory. Same goes to `nonpublic/` (for user logs)

-> And you should be ready to go :)

Problems which might occur:

1. Document not found error -> The project uses `.htaccess` files to restrict access. You can temporary comment some lines in these files. Also, make sure you set `AllowOverride All` (in case your webserver is apache2) to make `.htaccess` work.

2. ^^ -> this project uses the apache module `mod_rewrite` to modify URLs. Enable `mod_rewrite` with `sudo a2enmod rewrite` and restart your webserver. However, this module should not cause errors. When it does, please report them.

3. Internal server Error: When you use the featured .htaccess files, reconfigure the path of the `AuthUserFile` in `BASEPATH/.htaccess`. You could create a `.htpasswd` file and place it in `nonpublic/.htpasswd`

A tool for generating `.htpasswd` files is located [here](http://www.htaccesstools.com/htpasswd-generator/)  

##Known Bugs/Problems:

- Firefox reads strings in `data:text/plain` just until the char '#' when saving an animation. Published a workaround. Check the filesize each time you create an animation using firefox.

##Contributing and Credit
Font by: http://www.dafont.com/de/xfiles1.font

Project by tomatenbrei
