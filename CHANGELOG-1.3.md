CHANGELOG for 1.3.x

This changelog references the relevant changes (bug and security fixes) done in 1.3 minor versions.

* 1.3.0 (2012-10-11)

5c4fbd4 (HEAD, tag: v1.3.0, origin/master, origin/HEAD, master) not parsing php tags in helper.yml
dfaaa14 fixed bug in form>shift+tab access of fields
80cbee5 Merge pull request #6 from eventhorizonpl/uniqueValidator
9cb34a8 Merge pull request #7 from eventhorizonpl/sfValidator
9b6911e removal of afCompat10ValidatorAdapter class
846cb35 change sfPropelUniqueValidator to sfValidatorPropelUnique
a091915 Merge pull request #4 from eventhorizonpl/ipValidator
d2c3d2a Merge pull request #5 from eventhorizonpl/requiredValidator
d7661c6 port requiredValidator to sfValidatorBase
f7a81d9 port ipValidator to sfValidatorBase
f3761af Merge pull request #3 from eventhorizonpl/security_scan_fix
a1d76ab security scan fix engine part
08e7742 Merge pull request #2 from eventhorizonpl/shortcut_fix
e9e9f32 fix desktop shortcuts
f4df4ad updates for wizards
61889eb updates for wizards
63c6c3a updated schema to allow i:fields>exportable attr as text instead of bool
3fdeb5c changed some methods from private to public
f30639f small info update on ModelCriteriaFetcher
8dc51ec added combo static changes and session table
e2e3795 a few markdown styling changes
819a091 a few markdown styling changes
1209fa2 a few markdown styling changes
4020d5a fix file suffix
5f4dd86 updated security guide
c186ef2 reformated to markdown
c544afc added af:configure-propel-ini task
d755186 added deprecated Symfony placeholder variables to simpleWidgetEdit
8ce7a89 added some schema.xsd changes
4ba4fa5 allowed empty form submits in simpleWidgetEdit
9377380 fixed passing params to ModelCriteriaFetcher for combo ORM value
eab1f77 added some js fix
4fbc7fc #1333 - changed styling of shortcuts links
8e91142 fixed file validation and combo validation
5654084 made processFileFields public function
e556e13 save doublemulticombo values after saving the object form, when form is a addition one with id=null
00e7b8c fixed doublemulticombo selected values: 'value' => value; added xml doublemulticombo example in simpleWidgetEdit and ModelCriteriaFetcher classes
9964904 improved simpleWidgetEditAction from generator to support checkboxes on saving
a0bc32d fixed the followings: confirmation messages are displayed now on top of all windows; added loadjs attribute again for i:action; added afApp.notify method to display grawl notification faster
eeb561e fixed moreactions menu from form
088396c fixed validation error for combo boxes and changed simpleWidgetEditAction
a46d5e5 fixed issue with simpleWidgetEditAction, when ?id doesn't exist in url
2a6628a fixed sorting based on unexisting Model column
a31d38e Update LICENSE
a9d52fd updated Desktop theme start menu
921ab0f #1381 - fixed Engine for form's idxml and id
7a5139f Added trigger and window elements
bb89d87 #1368 - added special columns like _color to list decorator, to be used directly in a Model; when get_Color() method exists in a Model, background color is set to the specific row
0d7f98a Added static items in combos
315baa0 Added i:item element and value sequence
226ba10 fixed helper setting creation code for not logged user
3f331ee Merge branch 'perms_and_tmp_path_changes'
3ed8d44 #1368 - added autocompleter i:value example in php helper for combo
571356d added app_appFlower_chmod_enabled option that controls calls to chmod
bb79200 #1358 - commented jslist in XmlParser
78a8f1c #1348 - fixed color-field component, which is the cause of problems in IE; fixed bugs in it; improved collapsing/exposing of color-picker; removed overwhelming dom creation
e03bd51 added latest models based on new Propel 1.6
5ae601a #1190 - added fallback to query params when looking for edited object id
92cfabd #1342 - shift+tab error issue fixed
7901bf4 #1342 - removed on focus and blur events field type transformation from text to password, IE does not allow such transformations changing the type of a DOM input element
c5f1397 fixed some path to images that used in combo, refreshed appflower.css
e0e9406 correct start menu rendering, menu shadow is normal rendered; got rid from hardcoded offset numbers
bf13b1e small fix, added casts, and default init for startmenu
25b35e0 tiny fix if any link doesn't exists for desktop layout

