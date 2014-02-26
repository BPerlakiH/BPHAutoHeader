BPHAutoHeader
==============

ZF2 Auto Header Module: DRY management of javascript and css head references in one external yaml file

Features:
- Keep your javascript and css file references in one place
- Change directory references (e.g. /js and /css) in one place only
- Automatically include javascript and css files by your zf2 routes

Install:
- copy the BPHAutoHeader/example/headers.yml to your [app-root]/config/ folder
- include the module name in your [app-root]/config/application.config.php:
  'modules' => array(
        'Application',
        'BPHAutoHeader',
    ),






