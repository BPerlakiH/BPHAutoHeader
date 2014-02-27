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

###When not to use it?
- if you have just 1 or 2 css / js files
- if you are happy with the zend way of managing them in your header

###How does it work?
#####At which point it will alter the header?
This module is listening to the `RENDER` zend mvc event, and injects the found reference files to the view header at that point.

#####Include common files
It loads the header yaml file, which contains a `_common` part, these css and js files will be included in all views, just as if you would include them in the layout.
#####Include files by route
You can also control the references by routes. For example, if you have a route: `Album\Edit` you can add these new lines in the yaml file:

	album\edit:
		_css: editor
		_js: jquery.min, rapidEdit

**Notes:** 

- **route names** should be **in lowercase**.
The above will include:
	- /css/editor.css
	- /js/jquery.min.js
	- /js/rapidEdit.js

- As you can see you don't have to add the extensions
- You can comma (,) separate files to include more than one file
- The **module checks if the file actually exists, it won't include missing files**.
- You can include both css and js files, or only one of them.


#####Files included automatically by route
If you want to create custom css or js files for a specific route, they will be automatically included for you without editing the yaml file.
Assuming you have these custom routes: `Album\Index`, `Album\Edit`

- for `Album\Index`: `album.css`, and `album.js` will be automatically included, if found. Note you should omit the index part in this case as that is default.
- for `Album\Edit`: `album_edit.css` and `album_edit.js` will be automatically included, if found.


###Debug:
In order to debug missing references turn your environment to development, e.g.: by adding this line to your `[app-root]/public/.htaccess` file:

		SetEnv "APP_ENV" "development"

The default log file will be created under: `[app-root]/data/logs/bph-autoheader.log`
In production logging is turned off.

###Settings:
All the settings for this module can be found at the top of the `[app-root]/modules/BPHAutoHeader/module.config.php`.
Obviously you can override these settings from your own config files.
You can change the default folder settings, the header yaml file name and location, and the log file as well.

###Todos:
I will add a cache mechanism to the production mode, so it won't parse the yaml file for each request, which will hopefully improve the module's performance. At the moment I treat this module as more of a convenient tool, which aims to reduce coding time.






