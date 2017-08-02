# SEOmatic Changelog

## 1.1.49 - 2017.08.02
### Changed
* Don't render "article:tag" if there are no keywords
* Fix issue when passing a string containing the "à" character to _cleanupText()
* Changed `referrer` from `always` to `no-referrer-when-downgrade`
* Fixed an issue where meta vars were not sanitized with a custom template passed via `seomaticTemplatePath`
* Fixed a cosmetic bug where the SEOmatic FieldType settings didn't display the OpenGraph Image Source Changeable properly
* Fixed an issue with template metas on Windows

## 1.1.48 - 2017.06.18
### Changed
* Fixed an issue on old versions of PHP < PHP 5.4 with `http_response_code()`
* Added a safer default `robots.txt` template

## 1.1.47 - 2017.06.08
### Changed
* Fixed an issue with Live Preview and a previous or draft version of an entry
* Fixed a problem where the `dataLayer` wasn't passed down to the Google Tag Manager script
* Commerce products now have the `name` set to the variant + title

## 1.1.46 - 2017.04.08
### Changed
* Force `publisher` to be a `Person` or `Organization` for news articles
* Fixed the AdminCP documentation URL

## 1.1.45 - 2017.03.26
### Changed
* Fixed an issue with `addTrailingSlashesToUrls` and certain PHP configurations

## 1.1.44 - 2017.03.23
### Changed
* Fixed an issue with SEOmetrics and PHP 7.1 that would cause `Error parsing the DOM` errors
* Include our own copy of jQuery for SEOmetrics, rather than depending on the Craft version
* The calls to `file_get_contents` for SEOmetrics are now much more permissive

### Fixed
* If the current request is a 404, set the `canonicalUrl` to an empty string
* If the `canonicalUrl` is set to an empty string, don't render the `<meta rel='canonical'` tag
* Add a trailing / if `addTrailingSlashesToUrls` is set, but only if there's on extension
* Fixed some deprecated meta information in `SeomaticPlugin.php`

## 1.1.43 - 2017.03.03
### Added
* Added Organization to Main Entity of Page
* Improved error logging on internally rendered template errors

### Changed
* The default `robots.txt` template is now smarter about trailing slashes on the `siteUrl`
* Respect `addTrailingSlashesToUrls` when returning fully qualified URLs
* Respect `addTrailingSlashesToUrls` in the URLs returned from `getLocalizedUrls()`
* Dunn & Bradstreet -> Dun & Bradstreet in `DOCS.md`

### Fixed
* Return error messages from internally rendered templates as $htmlText
* Fix for extracting content from a Neo field

## 1.1.42 - 2017.02.12

* [Fixed] Fixed a regression that could cause FieldTypes to not save properly
* [Improved] Moved the lattitude/longitude info down below the address information for on the Site Identity & Site Creator settings
* [Improved] Wrapped all `templates->render()` calls in try/catch blocks to more gracefully handle errors
* [Added] Added an `Anonymize IP` toggle for Google Analytics, for compliance with some local laws
* [Added] Added Wikipedia to the Social Media settings & `sameAs` entity information

## 1.1.41 - 2017.02.10

* [Improved] Broke the changelog out to CHANGELOG.md
* [Fixed] Fixed a bug when saving meta fails
* [Fixed] Fixed an issue with the Charge plugin if you used the Charge element path
* [Fixed] Fixed an issue where re-saving a Section might not re-save the SEOmatic FieldType info
* [Improved] Updated the README.md

## 1.1.40 - 2016.12.20

* [Added] Added support for DigitalProducts and other base purchaseables
* [Fixed] Fixed Commerce deprecation warnings
* [Improved] The FieldType now works with all unknown elementtypes
* [Improved] Works with Eager Loaded elements
* [Improved] Improved the checking on the image fields in case there isn’t anything in there
* [Improved] Always sanitize the array values
* [Improved] Updated the README.md

## 1.1.39 - 2016.09.23

* [Improved] Don't redirect to the welcome page if we're being installed via Console command
* [Improved] Updated the README.md

## 1.1.38 - 2016.09.22

* [Fixed] Fixed an issue with Entry meta and Template Meta would not default Twitter and Facebook images properly
* [Fixed] Fixed an issue where Facebook and Twitter image transforms were not properly applied with Entry and Template meta
* [Fixed] Fixed an issue where elements with certain characters in them could cause invalid Breadcrumb JSON-LD to be generated
* [Improved] The Javascript for the SEOmetrics `Analyze` button now returns false to eliminate the #
* [Improved] Updated the README.md

## 1.1.37 - 2016.09.18

* [Fixed] The Settings template now works with Craft < 2.5
* [Fixed] Fixed a regression that would cause php errors on the settings pages on PHP < 5.5
* [Improved] Updated the README.md

## 1.1.36 - 2016.09.07

* [Fixed] SEOmetrics will now work if `open_basedir` is set
* [Improved] Added html_entity_decode() to the _cleanupText() method
* [Fixed] Fixed an `Integrity constraint violation` MySQL error on certain MySQL versions
* [Improved] Updated the README.md

## 1.1.35 - 2016.09.06

* [Fixed] Fixed a regression that would cause Twitter and Facebook images to not show up
* [Improved] Updated the README.md

## 1.1.34 - 2016.09.05

* [Added] Added the ability to set separate images for Twitter Cards and Facebook OpenGraph images
* [Improved] Do more sanity checking when building the breadcrumbs, so it works if you have not element with the URI `__home__`
* [Improved] We don't swap in the element->title for mainEntityOfPage anymore
* [Added] Added a variable for craft.seomatic.getTemplateMeta()
* [Improved] Protocol-relative URLs are now allowed in the Schema
* [Added] Added `PageWeight` as a metric in SEOmetrics
* [Added] Added the headings to the SEOmetrics display to improve its readability
* [Improved] SEOmetrics now does a better job displaying Top Keywords on pages with very little text
* [Improved] Updated the README.md

## 1.1.32 - 2016.08.25

* [Improved] The SEOmetrics tab in the AdminCP now doesn't start analyzing until you click Analyze
* [Improved] Improved SEOmetric's sitemap.xml detection
* [Improved] Updated DOCS.md to reflect the change from `seomaticProduct` to `seomaticMainEntityOfPage`
* [Improved] Products now have commas stripped from their price, and use a `.` as a decimal point
* [Fixed] Fixed an issue that caused SEOmatic to throw an error in a tokenized entry draft
* [Fixed] Fixed an issue with stale SEOmatic FieldTypes that were added to a section, but then later removed
* [Improved] Updated the README.md

## 1.1.31 - 2016.08.15

* [Added] SEOmetrics now has its own tab, and can evaluate any arbitrary URL you paste into it
* [Added] SEOmatic can now pull images from FocusPoint FieldTypes
* [Added] Added 'Person' as a type for the Main Entity of Page
* [Added] Added Vimeo Handle to Social Media settings
* [Added] Added a 'globalMetaOverride' setting to config.php
* [Added] SEOmetrics checks for HTML validity via the WC3 Validator
* [Added] Added a Score % summary for each SEOmetrics category
* [Added] SEOmetrics now includes Google Pagespeed Insights scores and Google Mobile Usability
* [Added] SEOmetrics checks for SSL encryption via https
* [Improved] Better extraction of 'top keywords on page' in SEOmetrics
* [Improved] SEOmetrics now looks for link rel='publisher'
* [Improved] Refined SEOmetrics a bit, and added more 'Learn More' links
* [Fixed] The mainEntityOfPage is no longer improperly cached if being called by getJsonLd()
* [Improved] Updated the README.md

## 1.1.30 - 2016.08.05

* [Added] Added 'Learn More' link for JSON-LD structured data in SEOmetrics
* [Added] Breadcrumbs get added to WebPage objects, too
* [Improved] SEOmatic now remembers the selected locale in its tabbed settings
* [Improved] Fixed a regression in SEOmetric's keyword in URL checking
* [Improved] Increased HTML SimpleDom's MAX_FILE_SIZE to allow for parsing larger pages (especially with devMode on)
* [Improved] SEOmetrics handles missing robots.txt files more elegantly
* [Improved] SEOmetrics no longer depends on there being an meta description tag
* [Fixed] Fixed an error when using the Share arrow on a draft from Live Preview
* [Fixed] Fixed an issue accessing getJsonLd from an entry that has no URLs or has empty SEOmatic values
* [Fixed] mainEntityOfPage now works for Template Metas
* [Improved] Updated the README.md

## 1.1.29 - 2016.07.31

* [Added] Added the ability to extract seoTitle, seoDescription, and seoKeywords from Neo fields
* [Added] Added the ability to extract seoTitle, seoDescription, and seoKeywords from Preparse fields
* [Added] Added detection of Twitter Card meta tags, OpenGraph meta tags, and JSON-LD structured data in SEOmetrics
* [Added] Added detection robots.txt and sitemaps in SEOmetrics
* [Improved] Breadcrumbs are now generated for every element that matches a URI in the current URL segments
* [Improved] seoTitles, seoDescriptions, and seoKeywords are now truncated on save in SEOmatic Meta FieldTypes when they pull from other fields
* [Improved] Improved the SEOmetrics preview CSS a bit
* [Improved] Updated the README.md

## 1.1.28 - 2016.07.27

* [Added] Added modified_time and published_time to OpenGraph articles when an SEOmatic Meta FieldType is used
* [Improved] Safeguard against 'Undefined index: seoImageTransform'
* [Fixed] Handle non-posted entries
* [Fixed] Fixed an issue with getJsonLD($element)
* [Improved] Updated the README.md

## 1.1.27 - 2016.07.26

* [Fixed] Fixed a regression with Entry Meta cascade
* [Fixed] Fixed an issue with SEOmatic FieldTypes on categories
* [Fixed] Fixed invalid releases.json
* [Improved] We now strip all control characters (including \n and \r) from the JSON-LD arrays
* [Improved] Updated the README.md

## 1.1.26 - 2016.07.25

* [Added] Added Main Entity of Page JSON-LD microdata
* [Added] Added the method getJsonLD($element) to the FieldType model, so you can get the Main Entity of Page JSON-LD for an arbitrary entry (maybe in a craft.entries loop, for instance)
* [Improved] The 'logo' and 'image' in Creator and Identity JSON-LD is now an ImageObject
* [Improved] Added a space after each keyword in SEOmetrics to prevent horizontal scrolling due to frame overflow
* [Fixed] Fixed the locale of the transform fields in the FieldType
* [Fixed] Fix for empty SEO Titles for breadcrumbs
* [Improved] Updated the DOCS.md and wiki docs
* [Improved] Updated the README.md

## 1.1.25 - 2016.07.19

* [Fixed] Fixed an issue with SEO images not rendering properly via the FieldType
* [Fixed] Fixed a typo that would cause Template Metas to not save properly
* [Improved] Added a fallback if `iconv` is not installed for UTF-8 conversion
* [Improved] Explicitly state that the SEO Image must be in JPG, PNG, or GIF format
* [Improved] Updated the README.md

## 1.1.24 - 2016.07.18

* [Added] You can now specify image transforms for your SEO Image, Twitter Image, and Facebook Image for Site Meta, Template Meta, and Entry Metas
* [Added] Added og:image:type, og:image:width, and og:image:height to the OpenGraph meta
* [Added] Added support for Breadcrumbs JSON-LD microdata
* [Improved] the Twig array keys are now quoted in the display preview
* [Improved] Trimmed the fat on some of the settings storage maxLength's
* [Added] Added the ability to control the name of the GTM dataLayer variable
* [Fixed] Added renderGoogleTagManagerScript in config.php
* [Improved] The GTM script tags are now rendered when `devMode` is on, for debugging GTM
* [Improved] Updated the README.md

## 1.1.23 - 2016.07.08

* [Added] Added support for Google Tag Manager (including `dataLayer`)
* [Fixed] Keywords in the SEOmatic Meta FieldType are now tokenized again
* [Fixed] Fixed Javascript error in the FieldType Source popup menu
* [Fixed] Fix Undefined index in getLocalizedUrls
* [Improved] Change seoKeywords -> seoKeywordsUnparsed
* [Added] Add accessors for seoTitleUnparsed, seoDescriptionUnparsed, and seoKeywordsUnparsed
* [Improved] meta keywords and meta description tags only render if they are non-empty
* [Improved] Updated the README.md

## 1.1.22 - 2016.06.27

* [Fixed] Fixed the variable accessor rountines getSocial() and getIdentity()
* [Fixed] Fixed an issue with the 'custom' data not displaying in SEOmatic Meta FieldTypes
* [Improved] Updated the README.md

## 1.1.21 - 2016.06.25

* [Improved] Contents of SEOmatic Meta FieldTypes are now parsed when they are saved, rather than at runtime, which should be faster, and also makes the contents of the fields always accessible.  Please re-save your Sections that use SEOmatic FieldTypes as per: [https://github.com/nystudio107/seomatic/wiki/05.-SEO-Entry-Meta](https://github.com/nystudio107/seomatic/wiki/05.-SEO-Entry-Meta)
* [Added] The SEO Title, SEO Description, and SEO Keywords fields in Template Metas can now include tags that output entry properties, such as `{title}` or `{myCustomField}` in them
* [Added] Added a `getLocalizedUrls` Twig filter/function that returns an array of localized URLs for the current request
* [Improved] The SEOmetrics window will now remember its open/closed state while in Live Preview
* [Improved] Some minor tweaks to the SEOmetrics CSS
* [Improved] The current locale is now included in the hreflang for localized sites
* [Improved] The language and country code are now both included in the hreflang for localized sites
* [Improved] The full URL to the current page is now used in the hreflang for localized sites
* [Improved] style and script tags are now stripped out before doing any SEOmetrics on the page
* [Added] Added approximate reading time in the Textual Analysis section of the SEOmetrics
* [Added] You can now control the string prepended to the title tag when devMode is on via config.php
* [Added] Added a French localization for SEOmatic, thanks to @FrancisBou
* [Fixed] We should not properly catch exceptions when there are errors in the variables in the SEOmatic FieldType fields
* [Fixed] Fixed a bug where SEOmetrics might not appear on certain setups that use https
* [Fixed] Fixed Twitter cards, changed `property` to `name`
* [Improved] Updated the README.md

## 1.1.20 - 2016.06.06

* [Added] Added 'Focus Keywords' to the SEOmetrics window, letting you analyze your page content for specific SEO keywords
* [Fixed] Fixed an issue with environmentalVariables in the Site Identity/Entity URL field
* [Improved] Forced CSS style reset on the SEOmetrics skeleton styles
* [Improved] Include port numbers with getFullyQualifiedUrl()
* [Improved] More accurate text to HTML % calculations
* [Improved] Changed the CSS z-index of the SEOmetrics windows to be 9998/9
* [Improved] Updated the README.md

## 1.1.19 - 2016.05.30

* [Fixed] Fixed an issue where the SEOmetrics wouldn't render on certain server setups
* [Added] Added a list of the top keywords on the page to the SEOmetrics
* [Improved] Updated the README.md

## 1.1.18 - 2016.05.30

* [Added] Added "SEOmetrics" displayed during Live Preview that analyizes your page, and generates helpful tips for improving SEO
* [Fixed] SEOmatic will now populate its FieldType with default values on `saveElement()`, which is triggered via import plugings and also via Settings → Edit My Section → hit Save
* [Fixed] Added 'logo' and 'image' to the list of keys that should be always run through getFullyQualifiedUrl()
* [Fixed] Fixed a CSS issue with the AdminCP UI and very large screens
* [Improved] Updated the README.md

## 1.1.17 - 2016.05.09

* [Added] Added support for Google Sitelinks Search Box
* [Added] Added support for Bing site verification
* [Fixed] Fixed a visual display issue with the SEOmatic FieldType Source labels 
* [Fixed] Facebook Article tags now use the proper data for author: and publisher:
* [Improved] Updated the README.md

## 1.1.16 - 2016.04.29

* [Added] Added `craft()->seomatic->headlessRenderSiteMeta()` for headless Craft CMS installs
* [Improved] Fixed an issue where Twitter and Facebook properties were double-encoded
* [Fixed] Fixed a visual display issue with tabs and Craft 2.4.x
* [Fixed] Fixed a localization issue with extractTextFromMatrix()
* [Improved] SEOmatic now converts any objects passed into ths seomatic variables to string automatically* 
* [Improved] Updated the README.md

## 1.1.15 - 2016.04.25

* [Added] Added a `siteUrlOverride` config setting for when you need to override the `siteUrl`, for instance in a headless ElementAPI server
* [Added] Added breadcrumbs to the AdminCP UI
* [Added] Added the ability to change the display name of the SEOmatic plugin in the AdminCP
* [Improved] Fixed an issue with empty organizationOwnerContactPoints
* [Improved] The array passed into renderJSONLD() is now sanitized before it is rendered
* [Improved] craft.locale is now properly limited to an ISO 639-1 language code in the metadata
* [Improved] Facebook locales now ensure that they have a territory as well as a language, e.g.: fr_FR, not just fr
* [Improved] Updated the README.md

## 1.1.14 - 2016.04.19

* [Added] Added [Organization Contact Points](https://developers.google.com/structured-data/customize/contact-points) that can appear in the Google Knowledge panel in some searches
* [Added] You can control whether Product JSON-LD is rendered via the `renderCommerceProductJSONLD` config variable
* [Added] Added support for arrays as the root JSON-LD type in the JSON-LD generator
* [Improved] SEOmatic now outputs JSON-LD microdata for all of the Craft Commerce Product Variants (previously it was outputting only the default Variant)
* [Improved] Updated the README.md

## 1.1.13 - 2016.04.16

* [Added] If an SEOmatic FieldType is attached to a Craft Commerce Product, in addition to rendering the page SEO Meta, it will also generate [Product JSON-LD microdata](https://developers.google.com/structured-data/rich-snippets/products) that describes the product.
* [Improved] SEOmatic now uses control panel sub-navs if you're running Craft 2.5 or later
* [Improved] Updated the README.md

## 1.1.12 - 2016.04.14

* [Improved] SEOmatic will now populate its FieldType with default values on saveElement(), which is triggered via import plugings and also via Settings → Edit My Section → hit Save
* [Improved] Updated the README.md

## 1.1.11 - 2016.04.08

* [Fixed] Fixed an issue with rendering the humans.tx and robots.txt templates
* [Improved] An HTML comment is added if the Google Analytics script is not included due to LivePreview or devMode being on
* [Improved] Updated the README.md

## 1.1.10 - 2016.03.29

* [Fixed] Fixed API 'deprecation' errors with Craft 2.6.2778 or later
* [Improved] Added more controls for the default title, description, and keywords values in config.php
* [Improved] Updated the README.md

## 1.1.9 - 2016.03.17

* [Fixed] Fixed a typo in the Preview SEO Tags window
* [Fixed] We now handle Twig errors in SEOmatic FieldType fields gracefully
* [Improved] Query strings are now stripped from the canonical URL
* [Improved] All things that should be fully qualified URLs are now fully qualified URLs, even if you specify them via path or relative URL
* [Improved] Updated the README.md

## 1.1.8 - 2016.03.10

* [Improved] In the SEOmatic FieldType, moved default setting to prepValue() so it'll work if the entries are all re-saved via `resaveAllElements`
* [Added] Added getFullyQualifiedUrl() helper as a Twig function/filter and as a variable for Twig templating
* [Improved] Turn things that should be fully qualified URLs into fully qualified URLs, such as the canonicalUrl, seoImage, and anything with `url` as a key
* [Improved] Added seoImage() and seoImageID() to the model so you can get at those values from, say, `entry.seoField.seoImage()`
* [Fixed] canonicalUrl fixes for multilingual sites
* [Fixed] Fixed errant ordinal encoding of the first element in a sequential array
* [Improved] Updated the README.md

## 1.1.7 - 2016.03.04

* [Fixed] Fixed a regression that would cause the Place JSON-LD to render incorrectly
* [Fixed] Fixed a nasty bug that would cause SEOmatic to crash if you used `{title}` or other variables in your SEOmatic Entry Meta fields
* [Improved] Updated the README.md

## 1.1.6 - 2016.03.03

* [Fixed] Fixed a fun recursion bug that would cause meta arrays nested more than 1 deep to not be sanitized & parsed properly
* [Added] Added Composer support for `type: craft-plugin` in `composer.json`
* [Fixed] Removed the `?>` from the `config.php` as per Lindsey's best practices
* [Fixed] The meta variables rendered by `craft.seomatic.renderIdentity()`, `craft.seomatic.renderWebsite()` and `craft.seomatic.renderPlace()` are now taken from the Twig context, so they can be modified before output
* [Fixed] The metas will now be cached as intended (oops), which should increase performance a bit
* [Improved] Updated the README.md

## 1.1.5 - 2016.02.27

* [Added] Added support for OpenGraph `article` types
* [Added] Added support for OpenGraph `fb:app_id` on the Social Media settings
* [Fixed] The canonicalUrl should be set properly to a fully qualified URL now
* [Fixed] The Site Creator will now remember the LocalBusiness and Corporation settings
* [Fixed] The SEOmatic FieldType will preview the canonicalUrl properly now
* [Improved] Updated the README.md

## 1.1.4 - 2016.02.19

* [Added] Set the default Twig escaping strategy for robots.txt and humans.txt to false (so the tag output is not escaped)
* [Fixed] Handle the case where there is no Twitter field
* [Fixed] Handle empty OpeningHours spec correctly, and other OpeningHours fixes
* [Fixed] actionRenderRobots is now publicly accessible (doh!), allowing `robots.txt` to render properly when not logged in
* [Fixed] Fixed a template error on the SEO Site Meta tab on localized sites
* [Improved] Updated the Roadmap section with pending bugs/features
* [Improved] Removed the siteRobotsTxt from the globals display
* [Improved] Updated the README.md

## 1.1.3 - 2016.02.17

* [Fixed] Fixed some typos in SEOmatic & the docs re: `robots.txt`
* [Fixed] The Google Analytics script no longer renders if the Google Analytics Tracking ID field is empty
* [Fixed] Fixed an issue with console errors on the backend with the SEOmatic FieldType
* [Fixed] OpeningHours now only displays for LocalBusiness
* [Added] SEOmatic now processes adding the SEO Site Name to the `og` and `twitter` titles just before rendering, so you can do things like change the `seomaticSiteMeta.siteSeoTitlePlacement` via Twig, and it'll do the right thing
* [Fixed] Fixed a PHP error if the Site Creator is a Person
* [Improved] Updated the README.md

## 1.1.1 - 2016.02.09

* [Added] SEOmatic Meta FieldTypes now work to automatically set meta when attached to `Categories` and also Craft Commerce `Products`
* [Improved] The Google Analytics script tag is not included during Live Preview anymore
* [Added] Added the ability to reference the fields of an SEOmatic FieldType via `entry.mySeomaticField.seoTitle` & `entry.mySeomaticField.seoDescription` & `entry.mySeomaticField.seoKeywords`
* [Added] There is now significantly more space available for the Entity Description and Creator Description
* [Fixed] Fixed an issue with PHP 5.3
* [Added] SEOmatic now handles `robots.txt`; it's configured on the SEO Site Meta tab
* [Improved] Updated the README.md


## 1.1.0 - 2016.02.07

* [Added] Added all of the schema.org Organization types to Identity settings
* [Added] SEOmatic Meta FieldTypes now have settings that let you restrict the Asset Sources available to them
* [Added] SEOmatic Meta FieldTypes now let you set the default Source settings for each field
* [Added] SEOmatic Meta FieldTypes now let you choose if the Source can be changed when editing an entry
* [Added] You can include tags that output entry properties, such as `{title}` or `{myCustomField}` in SEOmatic FieldType fields.
* [Added] Twitter Card and Facebook types can now have null values in the FieldType, and Template meta settings
* [Added] We now include separate Place JSON-LD for Organizations (in addition to being part of the Identity)
* [Added] Added the Opening Hours fields for LocalBusiness, and include the `openingHoursSpecification` in the Site Identity and Place JSON-LD
* [Added] Added support for Google Analytics on the Site Identity tab 
* [Added] Added the ability to control whether a `PageView` is automatically sent by Google Analytics 
* [Added] Added support for enabling Google Analytics plugins on an a la carte basis 
* [Fixed] We now handle numeric Google+ accounts properly
* [Fixed] The Preview buttons display properly on mobile devices for the SEOmatic FieldType now
* [Improved] Added links to WooRank.com for the SEO Title, SEO Description, and SEO Keywords tags that explain best practices for them
* [Added] Added a `config.php` file where you can override some of SEOmatic's default behaviors
* [Added] Added `menu` and `acceptsReservations` fields for FoodEstablishments
* [Improved] Converted all of the `.html` template files over to `.twig`
* [Added] Expanded the JSON-LD parser to support ordinal arrays of associative arrays
* [Fixed] Fixed an issue with the cannonical URL and some localized sites
* [Improved] Updated the README.md

## 1.0.12 - 2016.01.19

* [Improved] Performance improvement by not checking to see if a template exists before matching it
* [Improved] Keyword tags are now saved onblur
* [Improved] The Preview buttons in the SEOmatic FieldType are laid out better now
* [Improved] Updated the README.md

## 1.0.11 - 2016.01.13

* [Added] You can now set Tags fields to be a Source for SEO FieldType Meta fields
* [Added] Added a meta referrer tag to the template (set to 'always')
* [Added] Added a http-equiv='Content-Type' content='text/html; charset=utf-8' meta tag
* [Fixed] If the SEO Title is empty, it no longer appear in the title
* [Fixed] Fixed an issue that would cause the seomatic* variables to not be properly sanitized
* [Improved] Updated the README.md

## 1.0.10 - 2016.01.12

* [Added] You can now set Matrix blocks to be a Source for SEO FieldType Meta fields; it iterates through all text and rich text fields
* [Added] Added a extractTextFromMatrix() Twig filter/function/variable for templating use
* [Improved] The truncateStringOnWord() function does a much better job on comma-delimited keywords now
* [Fixed] Fixed a regression that caused the 'New Template Meta' button to be broken
* [Improved] Updated the README.md

## 1.0.9 - 2016.01.11

* [Added] Added tokenized input fields for the keywords for the Site Meta, Template Meta, and Field Meta
* [Added] You can now specify the position of the SEO Site Title relative to the Title
* [Added] You can now specify the separator character that appears between the SEO Site Title and the Title
* [Fixed] Potentially fixed an issue with iconv when using the extractKeywords() or extractSummary() filters
* [Fixed] Fixed a regression that caused the Template Metas to stop working on the front-end
* [Improved] Updated the README.md

## 1.0.8 - 2016.01.08

* [Improved] The rendering of the Identity and WebSite JSON-LD is now done via a tag in the templates, giving flexibility to people who want to use custom templates
* [Fixed] Fixed an issue with PHP < 5.4
* [Added] If the site is in devMode, `[devMode]` is prepended to the `<title>`
* [Fixed] Fixed an issue if a plugin (like A&M forms) renders a template with Twig code in the template name
* [Improved] Updated the README.md

## 1.0.7 - 2016.01.01

* [Added] Added a 'robots' field globally to the SEO Meta for specifying noindex/nofollow
* [Fixed] Added error handling to the 'Look up Latitude/Longitude' buttons
* [Fixed] Some minor template / logic issues
* [Improved] Updated the README.md

## 1.0.6 - 2015.12.31

* [Added] Added an SEOmatic Meta field type that allows you to attach meta to Entries/Sections
* [Added] The SEOmatic Meta field type can have custom date, or pull from other fields in that Entry, or even extract keywords from other fields
* [Added] You can preview the settings from SEOmatic Meta field types that are attached to entries
* [Fixed] Fixed db error on install on Windows due to trying to set a default for `genericCreatorHumansTxt`
* [Fixed] Facebook Open Graph tags weren't being generated if you had no Facebook Profile ID set, which could cause templating errors
* [Fixed] The `seomatic.twitter.creator` field wasn't set unless `summary_large_image` was chosen, which could cause templating errors
* [Fixed] The seomaticMeta variables were being overzealously encoded as htmlentities
* [Fixed] Fixed an issue where weird characters would appear in truncated strings on certain versions of PHP
* [Improved] All JSON-LD rendered through SEOmatic is now minified if you have the Minify plugin installed
* [Improved] Updated the README.md

## 1.0.5 - 2015.12.28

* [Added] Added 'renderJSONLD' Twig function & filter, and 'craft.seomatic.renderJSONLD()' variable for rendering arbitary JSON-LD schemas
* [Added] SEOmatic now uses 'renderJSONLD' internally to render the Identity and WebSite JSON-LD microdata, rather than templates
* [Added] The 'seomaticIdenity' and 'seomaticCreator' variables are now proper JSON-LD arrays that can be manipulated/added to via Twig
* [Added] Refactored a bunch of internal code to support the new Identity & WebSite formats
* [Added] Rolled all of the SEOmatic calculated variables into the 'seomaticHelper' array
* [Added] Added support for humans.txt authorship accreditation via a tag and template
* [Fixed] Fixed some sticky encoding issues with meta variables
* [Fixed] Cleaned up the code base so we're no longer passing objects around into the templates, just pure arrays, for efficiency's sake
* [Added] Support for Twitter Summary & Summary with Large Image cards
* [Added] The Twitter Card variables are now rendered into the semomaticMeta array, and thus can be independently manipulated
* [Added] The Facebook Open Graph variables are now rendered into the semomaticMeta array, and thus can be independently manipulated
* [Added] Database migrations to support the new features
* [Improved] Updated the README.md

## 1.0.4 - 2015.12.22

* [Added] Added 'copyrightNotice', 'addressString', 'addressHtml', & 'mapUrl' to 'seomaticIdentity'
* [Added] Added 'copyrightNotice', 'addressString', 'addressHtml', & 'mapUrl' to 'seomaticCreator'
* [Added] Added 'hasMap' to the 'location' schema
* [Added] Added a 'Look up Latitude/Longitude' button to the Site Identity & Site Creator settings
* [Fixed] Fixed an issue with the releases.json; the 'Update' button should show up going forward for the plugin
* [Improved] The length of the 'seoSiteName' is now taken into account when truncating the 'seoTitle'
* [Improved] Updated the README.md

## 1.0.3 - 2015.12.21

* [Fixed] Fixed an issue with the TextRank lib not being properly in the git repo, causing it to error when used
* [Fixed] The SEOmatic settings pages now have a SAVE button on them for Craft 2.4
* [Improved] Updated the README.md

## 1.0.2 - 2015.12.20

* [Added] Exposed a few more utility functions via Twig filters & functions
* [Added] The genericOwnerEmail & genericCreatorEmail variables are ordinal-encoded, to obfuscate them
* [Added] Added 'location': 'Place' type to the Identity & Creator schemas, including GeoCoordinates
* [Fixed] Fixed the localization so SEOmatic works if your Admin CP is in a language other than English
* [Improved] Updated the README.md

## 1.0.1 - 2015.12.19

* [Added] If the [Minify](https://github.com/nystudio107/minify) plugin is installed, SEOmatic will minify the SEO Meta tags & JSON-LD
* [Improved] Improved the caching mechanism to span all of the meta
* [Fixed] Fixed a few of small errors
* [Improved] Updated the README.md to better document SEOmatic

## 1.0.0 - 2015.12.18

* Initial release

Brought to you by [nystudio107](http://nystudio107.com)