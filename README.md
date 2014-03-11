BPHAutoHeader
==============

ZF2 Auto Header Module: DRY management of javascript and css head references in one external yaml file

###Features:

- Keep your javascript and css file references in one place
- Change directory references (e.g. `/js` and `/css`) in one place
- Automatically include javascript and css files according to your zf2 routes
- Checks if the file exists, you won't end up with bad references
- Make your front-end management easy

###Install:

0. Download the dependent [spyc.php library](https://github.com/mustangostang/spyc/blob/master/Spyc.php) to be able to parse yaml files, and place it under the `[app-root]/vendor/` folder
1. Copy the `BPHAutoHeader/example/headers.yml` to your `[app-root]/config/` folder
2. Load the module in your: `[app-root]/config/application.config.php`:

    ``` 
	'modules' => array(
       'Application',
       'BPHAutoHeader',
    ),
    ```
3. Update your layout file, as you no longer need to manage the css and js assets from this level, therefore all you need is:

	```
		<?php echo $this->headLink(); ?>
		<?php echo $this->headScript(); ?>
	```

###When to use this module?
- If you have lots of css or js files to include and you want to control them on a request/route level.
- If you want to refactor css/js files
- If you are having trouble figuring out where certain css or js files were included and why

This module reduces the tedious work of managing your js and css files via the standard zend solution.

By using this module **you can edit**, **and** most importantly **see all your js and css files in one place**. **Change their order and refactor them easily.**

###How does it work?
#####At which point it will alter the header?
This module is listening to the `RENDER` zend mvc event, and injects the found reference files to the view header at that point.

#####Include common files
It loads the header yaml file, which contains a `_common` part, these css and js files will be included in all views first, just as if you would include them at the top of the layout.
#####Include files by route names
You can also control the references by routes. For example, if you have a route: `Album\Edit` you can add these new lines in the yaml file:

	album\edit:
		_css: editor
		_js: jquery.min, rapidEdit, http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min

**Notes:** 

- **route names** should be **in lowercase**.
The above will include the following files for the route `Album\Edit`:
	- /css/editor.css
	- /js/jquery.min.js
	- /js/rapidEdit.js
	- http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js

- As you can see extensions are not needed in the yaml file
- You can use external requests (http), not just local files
- You can comma (,) separate files to include more than one file, they will be included in this order
- The **module checks if the file actually exists, it won't include missing files**.
- You can include both css and js files, or only one of them.


#####Files included automatically by route
If you want to create custom css or js files for a specific route, they will be automatically included for you without editing the yaml file.
Assuming you have these custom routes: `Album\Index`, `Album\Edit`

- for `Album\Index`: `album.css`, and `album.js` will be automatically included, if found. Note you should omit the index part in this case as this is the default.
- for `Album\Edit`: `album_edit.css` and `album_edit.js` will be automatically included, if found.

#####Why YAML, and how to use it?
[YAML (see on wiki)](http://en.wikipedia.org/wiki/YAML) is a human-readable data serialization format. In fact it is very easy to read for humans and most IDEs will support it out of the box.

###Debug:
In order to debug missing references turn your environment to development, e.g.: by adding this line to your `[app-root]/public/.htaccess` file:

		SetEnv "APP_ENV" "development"

The default log file will be created under: `[app-root]/data/logs/bph-autoheader.log`
In production logging is turned off.

###Settings:
All the settings for this module can be found at the top of the `[app-root]/modules/BPHAutoHeader/module.config.php`.
Obviously you can override these settings from your own config files.
You can change the default folder settings, the header yaml file name and location, and the log file as well.

###Reduce your http requests:
Performance experts will tell you that too many http requests will slow down your website. In order to reduce the amount of javascript and css files, you can use an external tool to compile+compress+combine your files (such as coffeescript and sass).

You can use this tool in development mode to link to your non-compressed and not combined assets, to make your debugging easy, while link to the compressed assets in your production environment.

You can override the settings, either the folder names or the complete header yaml file name, so you can have one for development and one for production.

###Todos:

- **caching**: add a cache mechanism to the production mode, so it won't parse the yaml file for each request, which should improve the module's performance. At the moment I treat this module as more of a convenient tool, which aims to reduce coding time.






