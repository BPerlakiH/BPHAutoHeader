BPHAutoHeader
==============

ZF2 Auto Header Module: DRY management of javascript and css head references in one external yaml file

###Features:

- Keep your javascript and css file references in one place
- Change directory references (e.g. `/js` and `/css`) in one place
- Automatically include javascript and css files according to your zf2 routes
- Checks if file exists, won't include missing files
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

###Why to use this module?
The aim of this module is to reduce the tedious work of managing your js and css files via the standard zend solution, which imho is not centralised enough, and for most front end developers confusing. Some developers are including the common js and css files in their layout file, while including the action specific ones in their phtml files or in their controllers.

By using this module **you can edit**, **and** most importantly **see all your js and css files in one place**. **Change their order and refactor them easily.**

###How does it work?
#####Include common files
It loads the header yaml file, which contains a `_common` part, these css and js files will be included in all views, just as if you would include them in the layout.
#####Include files by route
You can also control the references by routes. For example, if you have a route: `Album\Edit` you can add these new line in the yaml file:

	album\edit:
		_css: editor
		_js: rapidEdit.min

**Notes:** 

- **route names** should be **in lowercase**.
The above will include:
	- /css/editor.css
	- /js/rapidEdit.min.js

- The **module checks if the file actually exists, it won't include missing files**.

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







