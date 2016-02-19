# SEOmatic plugin for Craft

A turnkey SEO implementation for Craft CMS that is comprehensive, powerful, and flexible.

![Screenshot](resources/screenshots/seomatic01.png)

## Installation

To install SEOmatic, follow these steps:

1. Download & unzip the file and place the `seomatic` directory into your `craft/plugins` directory
2.  -OR- do a `git clone https://github.com/nystudio107/seomatic.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3. Install plugin in the Craft Control Panel under Settings > Plugins
4. The plugin folder should be named `seomatic` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

SEOmatic works on Craft 2.4.x and Craft 2.5.x.

## Overview

SEOmatic allows you to quickly get a website up and running with a robust, comprehensive SEO strategy.  It is also implemented in a Craft-y way, in that it is also flexible and customizable.

It implements [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview) tags, [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph) tags, [Humans.txt](http://humanstxt.org) authorship accreditation, and as well as HTML meta tags.

The general philosophy is that SEO Site Meta can be overridden by SEO Template Meta, which can be overridden by SEO Entry Meta, which can be overridden by dynamic SEO Twig tags.

In this way, the SEO Meta tags on your site cascade, so that they are globally available, but also can be customized in a very granular way.

SEOmatic populates your templates with SEO Meta in the same way that Craft populates your templates with `entry` variables, with a similar level of freedom and flexibility in terms of how you utilize them.

SEOmatic also caches each unique SEO Meta request so that your website performance is minimally impacted by the rich SEO Meta tags provided.

## Rendering your SEO Meta tags

All you need to do in order to output the SEOmatic SEO Meta tags is in the `<head>` tag of your main `layout.twig` (or whatever template all of your other templates `extends`), place this tag:

    {% hook 'seomaticRender' %}

That's it.  It'll render all of that SEO goodness for you.

SEOmatic uses its own internal template for rendering; but you can provide it with one of your own as well, just use this Twig code instead:

    {% set seomaticTemplatePath = 'path/template' %} {% hook 'seomaticRender' %}

...and it'll use your custom template instead.

If the [Minify](https://github.com/nystudio107/minify) plugin is installed, SEOmatic will minify the SEO Meta tags & JSON-LD.

## Configuring SEOmatic

When you first install SEOmatic you'll see a welcome screen, click on the **Get Started** to, well, get started configuring SEOmatic.

All of the SEOmatic settings are fully localizable, so you can have SEO in as many languages as your website supports.  If any field is left blank for a setting in a particular locale, it will fall back on the primary locale.

### SEO Site Meta

These SEO Site Meta settings are used to globally define the Meta for the website.  When no SEO Template Meta is found for a webpage, these settings are used by default.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

If no Template Meta exists for a template, the SEO Site Meta is used.

If any fields are left blank in a Template Meta, those fields are pulled from the SEO Site Meta.

You can also dynamically change any of these SEO Meta fields in your Twig templates, and they will appear in the rendered SEO Meta.

* **Site SEO Name** - This field is used wherever the name of the site is referenced, both at the trailing end of the `<title>` tag, and in other meta tags on the site. It is initially set to your Craft `{{ siteName }}`.
* **Site SEO Title** - This should be between 10 and 70 characters (spaces included). Make sure your title tag is explicit and contains your most important keywords. Be sure that each page has a unique title tag.
* **Site SEO Name Placement** - Where the Site SEO Name is placed relative to the Title in the `<title>` tag
* **Site SEO Name Separator** - The character that should be used to separate the Site SEO Name and Title in the `<title>` tag
* **Site SEO Description** - This should be between 70 and 160 characters (spaces included). Meta descriptions allow you to influence how your web pages are described and displayed in search results. Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords.
* **Site SEO Keywords** - Google ignores this tag; though other search engines do look at it. Utilize it carefully, as improper or spammy use most likely will hurt you, or even have your site marked as spam. Avoid overstuffing the keywords and do not include keywords that are not related to the specific page you place them on.
* **Site SEO Image** - This is the image that will be used for display as the global website brand, as well as on Twitter Cards and Facebook OpenGraph that link to the website. It should be an image that displays well when cropped to a square format (for Twitter)
* **Site Owner** - The type of entity that owns this website.
* **Site Twitter Card Type** - With Twitter Cards, you can attach rich photos and information to Tweets that drive traffic to your website. Users who Tweet links to your content will have a “Card” added to the Tweet that’s visible to all of their followers.
* **Site Facebook Open Graph Type** - Adding Open Graph tags to your website influences the performance of your links on social media by allowing you to control what appears when a user posts a link to your content on Facebook.
* **Site Robots** - The [robots meta tag](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag?hl=en) lets you utilize a granular, page-specific approach to controlling how an individual page should be indexed and served to users in search results.  Setting it to a blank value means 'no change'.

#### robots.txt

* **robots.txt Template** - A `robots.txt` file is a file at the root of your site that indicates those parts of your site you don’t want accessed by search engine crawlers. The file uses the [Robots Exclusion Standard](http://en.wikipedia.org/wiki/Robots_exclusion_standard#About_the_standard), which is a protocol with a small set of commands that can be used to indicate access to your site by section and by specific kinds of web crawlers (such as mobile crawlers vs desktop crawlers).

SEOmatic automatically handles requests for `/robots.txt`. For this to work, make sure that you do not have an actual `robots.txt` file in your `public/` folder (because that will take precedence).

If you are running Nginx, make sure that you don't have a line like:

    location = /robots.txt  { access_log off; log_not_found off; }
    
...in your config file.  A directive like this will prevent SEOmatic from being able to service the request for `/robots.txt`.  If you do have a line like this in your config file, just comment it out, and restart Nginx with `sudo nginx -s reload`.

The **Preview Robots.txt** button lets you preview what your rendered robots.txt file will look like.

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

### Site Identity

These Site Identity settings are used to globally define the identity and ownership of the website.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

The Site Owner type determines the JSON-LD schema that will be used to identity the website to search engines.

Leave any fields blank that aren't applicable or which you do not want as part of the SEO schema.

#### Site Ownership
* **Google Site Verification** - For the `<meta name='google-site-verification'>` tag. Only enter the code in the `content=''`, not the entire tag. [Here's how to get it.](https://www.google.com/webmasters/verification/).
* **Google Analytics Tracking ID** - If you enter your Google Analytics Tracking ID here, the Google Analytics script tags will be included in your `<head>` (the script is not included if `devMode` is on or during Live Preview). Only enter the ID, e.g.: `UA-XXXXXX-XX`, not the entire script code. [Here's how to get it.](https://support.google.com/analytics/answer/1032385?hl=en)
* **Automatically send Google Analytics PageView** - Controls whether the Google Analytics script automatically sends a PageView to Google Analytics when your pages are loaded
* **Google Analytics Plugins** - Select which Google Analytics plugins to enable. [Learn More](https://developers.google.com/analytics/devguides/collection/analyticsjs/)
* **Site Owner Entity Type** - The type of entity that owns this website.  Choose as general or specific of a type as you like.  Any entity sub-type left blank is ignored.

More fields will also appear depending on the selected **Site Owner Entity Type**.  For instance, any `LocalBusiness` sub-type will receive a field for **Opening Hours**, so that the hours that the business is open will be rendered in the Identity and Place JSON-LD microdata.


#### General Info
* **Entity Name** - The name of the entity that owns the website
* **Alternate Entity Name** - An alternate or nickname for the entity that owns the website
* **Entity Description** - A description of the entity that owns the website
* **Entity URL** - A URL for the entity that owns the website
* **Entity Brand** - An image or logo that represents the entity that owns the website
* **Entity Telephone** - The primary contact telephone number for the entity that owns the website
* **Entity Email** - The primary contact email address for the entity that owns the website

#### Location Info
* **Entity Latitude** - The latitude of the location of the entity that owns the website, e.g.: -120.5436367
* **Entity Longitude** - The longitude of the location of the entity that owns the website, e.g.: 80.6033588
* **Entity Street Address** - The street address of the entity that owns the website, e.g.: 123 Main Street
* **Entity Locality** - The locality of the entity that owns the website, e.g.: Portchester
* **Entity Region** - The region of the entity that owns the website, e.g.: New York or NY
* **Entity Postal Code** - The postal code of the entity that owns the website, e.g.: 14580
* **Entity Country** - The country in which the entity that owns the website is located, e.g.: US

#### Organization Info
* **Organization DUNS Number** - The DUNS (Dunn & Bradstreet) number of the organization/company/restaurant that owns the website
* **Organization Founder** - The name of the founder of the organization/company/restaurant
* **Organization Founding Date** - The date the organization/company/restaurant was founded
* **Organization Founding Location** - The location where the organization/company/restaurant was founded

#### Local Business Info
* **Opening Hours** - The opening hours for this local business. If the business is closed on a given day, just leave the hours for that day blank.

#### Corporation Info
* **Corporation Ticker Symbol** - The exchange ticker symbol of the corporation

#### Food Establishment Info
* **Food Establishment Cuisine** - The primary type of cuisine that the food establishment serves
* **Food Establishment Menu URL** - URL to the food establishment's menu
* **Food Establishment Reservations URL** - URL to the food establishment's reservations page

#### Person Info
* **Person Gender** - The gender of the person
* **Person Birth Place** - The place where the person was born

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

### Social Media

These Social Media settings are used to globally define the social media accounts associated with your website.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

None of these fields are mandatory; if you don't have a given social media account, just leave it blank.

* **Twitter Handle** - Your Twitter Handle, without the preceding @
* **Facebook Handle** - Your Facebook company/fan page handle (the part after `https://www.Facebook.com/`
* **Facebook Profile ID** - Your Facebook Profile/Page ID. Click on the 'About' tab on your Facebook company/fan page, click on 'Page Info', then scroll to the bottom to find your 'Facebook Page ID'
* **LinkedIn Handle** - Your LinkedIn page handle (the part after `https://www.linkedin.com/in/` or `https://www.linkedin.com/company/`)
* **Google+ Handle** - Your Google+ page handle, without the preceding +. If you have a numeric Google+ account still, just enter that.
* **YouTube User Handle** - Your YouTube handle (the part after `https://www.youtube.com/user/`)
* **YouTube Channel Handle** - Your YouTube handle (the part after `https://www.youtube.com/c/`)
* **Instagram Handle** - Your Instagram handle
* **Pinterest Handle** - Your Pinterest page handle (the part after `https://www.pinterest.com/`)
* **Github Handle** - Your Github page handle (the part after `https://github.com/`)

You must have a **Twitter Handle** for SEOmatic to generate Twitter Card tags for you.  Similarly, you should have a **Facebook Profile ID** for the Facebook Open Graph tags, but it's not required.

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

### Site Creator

These Site Creator settings are used to globally define & attribute the creator of the website.  The creator is the company/individual that developed the website.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

The Site Creator information is referenced in the Identity JSON-LD schema that is used to identity the website to search engines.

Leave any fields blank that aren't applicable or which you do not want as part of the SEO schema.

#### Site Creator
* **Site Creator Entity Type** - The type of entity that created this website.

#### General Info
* **Entity Name** - The name of the entity that created the website
* **Alternate Entity Name** - An alternate or nickname for the entity that created the website
* **Entity Description** - A description of the entity that created the website
* **Entity URL** - A URL for the entity that created the website
* **Entity Brand** - An image or logo that represents the entity that created the website
* **Entity Telephone** - The primary contact telephone number for the entity that created the website
* **Entity Email** - The primary contact email address for the entity that created the website

#### Location Info
* **Entity Latitude** - The latitude of the location of the entity that created the website, e.g.: -120.5436367
* **Entity Longitude** - The longitude of the location of the entity that created the website, e.g.: 80.6033588
* **Entity Street Address** - The street address of the entity that created the website, e.g.: 575 Dunfrey Road
* **Entity Locality** - The locality of the entity that created the website, e.g.: Lansing
* **Entity Region** - The region of the entity that created the website, e.g.: Michigan or MI
* **Entity Postal Code** - The postal code of the entity that created the website, e.g.: 11360
* **Entity Country** - The country in which the entity that created the website is located, e.g.: US

#### Organization Info
* **Organization DUNS Number** - The DUNS (Dunn & Bradstreet) number of the organization/company/restaurant that created the website
* **Organization Founder** - The name of the founder of the organization/company
* **Organization Founding Date** - The date the organization/company was founded
* **Organization Founding Location** - The location where the organization/company was founded

#### Corporation Info
* **Corporation Ticker Symbol** - The exchange ticker symbol of the corporation

#### Person Info
* **Person Gender** - The gender of the person
* **Person Birth Place** - The place where the person was born

#### Humans.txt
* **Humans.txt Template** - [Humans.txt](http://humanstxt.org) is an initiative for knowing the people behind a website. It's a TXT file that contains information about the different people who have contributed to building the website. This is the template used to render it; you have access to all of the SEOmatic variables.  This is the template used to render it; you have access to all of the SEOmatic variables.

The **Preview Humans.txt** button lets you preview what your rendered Humans.txt file will look like.

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

### SEO Template Meta

This list of Template Metas will initially be empty; click on the **New Template Meta** button to create one.

These SEO Meta settings are used to render the SEO Meta for your website. You can create any number of SEO Template Metas associated with your Twig templates on your website.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

If no Template Meta exists for a template, the SEO Site Meta is used.

If any fields are left blank in a Template Meta, those fields are pulled from the SEO Site Meta.

You can also dynamically change any of these SEO Meta fields in your Twig templates, and they will appear in the rendered SEO Meta.

* **Title** - The human readable title for this SEO Template Meta
* **Template Path** - Enter the path to the template to associate this meta with (just as you would on the Section settings). It will override the SEO Site Meta for this template. Leave any field blank if you want it to fall back on the default global settings for that field.
* **SEO Title** - This should be between 10 and 70 characters (spaces included). Make sure your title tag is explicit and contains your most important keywords. Be sure that each page has a unique title tag.
* **SEO Description** - This should be between 70 and 160 characters (spaces included). Meta descriptions allow you to influence how your web pages are described and displayed in search results. Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords.
* **SEO Keywords** - Google ignores this tag; though other search engines do look at it. Utilize it carefully, as improper or spammy use most likely will hurt you, or even have your site marked as spam. Avoid overstuffing the keywords and do not include keywords that are not related to the specific page you place them on.
* **SEO Image** - This is the image that will be used for display as the webpage brand for this template, as well as on Twitter Cards and Facebook OpenGraph that link to this page. It should be an image that displays well when cropped to a square format (for Twitter)
* **Twitter Card Type** - With Twitter Cards, you can attach rich photos and information to Tweets that drive traffic to your website. Users who Tweet links to your content will have a “Card” added to the Tweet that’s visible to all of their followers.
* **Facebook Open Graph Type** - Adding Open Graph tags to your website influences the performance of your links on social media by allowing you to control what appears when a user posts a link to your content on Facebook.
* **Robots** - The [robots meta tag](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag?hl=en) lets you utilize a granular, page-specific approach to controlling how an individual page should be indexed and served to users in search results.  Setting it to a blank value means 'no change'.

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

## SEO Entry Meta

![Screenshot](resources/screenshots/seomatic03.png)

SEOmatic provides a FieldType called `SEOmatic Meta` that you can add to your Sections.  It allows you to provide meta information on a per-entry basis.  SEOmatic will automatically override any Site Meta or Tempalate Meta with Entry Meta if an `entry` that has an SEOmatic Meta field is auto-populated by Craft into a template.

This also works with Categories and Craft Commerce Products that have an SEOmatic Meta field attached to them.

If any fields are left blank in an Entry Meta, those fields are pulled from the SEO Site Meta / SEO Template Meta.

You can also dynamically change any of these SEO Meta fields in your Twig templates, and they will appear in the rendered SEO Meta.

* **SEO Title** - This should be between 10 and 70 characters (spaces included). Make sure your title tag is explicit and contains your most important keywords. Be sure that each page has a unique title tag.
* **SEO Description** - This should be between 70 and 160 characters (spaces included). Meta descriptions allow you to influence how your web pages are described and displayed in search results. Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords.
* **SEO Keywords** - Google ignores this tag; though other search engines do look at it. Utilize it carefully, as improper or spammy use most likely will hurt you, or even have your site marked as spam. Avoid overstuffing the keywords and do not include keywords that are not related to the specific page you place them on.
* **SEO Image** - This is the image that will be used for display as the webpage brand for this template, as well as on Twitter Cards and Facebook OpenGraph that link to this page. It should be an image that displays well when cropped to a square format (for Twitter)
* **Twitter Card Type** - With Twitter Cards, you can attach rich photos and information to Tweets that drive traffic to your website. Users who Tweet links to your content will have a “Card” added to the Tweet that’s visible to all of their followers.
* **Facebook Open Graph Type** - Adding Open Graph tags to your website influences the performance of your links on social media by allowing you to control what appears when a user posts a link to your content on Facebook.
* **Robots** - The [robots meta tag](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag?hl=en) lets you utilize a granular, page-specific approach to controlling how an individual page should be indexed and served to users in search results.  Setting it to a blank value means 'no change'.

The **SEO Title**, **SEO Description**, and **SEO Keywords** fields can include tags that output entry properties, such as `{title}` or `{myCustomField}` in them.

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

In addition to being able to hold custom data that you enter manually, you can also set the Source that **SEO Title**, **SEO Description**, **SEO Keywords**, and **SEO Image** SEOmatic Meta fields to pull data from to an existing field in your Entry.  

**SEO Image** only can pull from an existing Assets field, while **SEO Title**, **SEO Description**, and **SEO Keywords** can pull from Text, Rich Text, Tags, and Matrix fields.  If you pull from a Matrix field, SEOmatic goes through and concatenates all of the Text & Rich Text fields together (this is useful for **SEO Keywords** generation).

The **SEO Keywords** field also allows you to extract keywords automatically from an existing field in your Entry via the `Keywords From Field` Source option.

SEOmatic Meta FieldTypes also have default settings that allow you to control what the default settings should be for each meta field, and whether they can be changed by the person editing the entry.

## Dynamic Twig SEO Meta

All this SEO is great, but what if you want to generate dynamic SEO in an Twig template, with custom or specific requirements that the SEOmatic FieldType can't handle?  SEOmatic makes it easy.

SEOmatic populates your templates with the following global variables for SEO Meta:

    seomaticMeta.seoTitle
    seomaticMeta.seoDescription
    seomaticMeta.seoKeywords
    seomaticMeta.seoImage
    seomaticMeta.canonicalUrl

By default, `seomaticMeta.canonicalUrl` is set to `craft.request.url`.

All of the variables are set by a combination of your SEO Site Meta settings, and the SEO Template Meta settings linked to the currently rendered template (if any).  These are the primary SEOmatic variables that you will be manipulating in your templates.

These work like any other Twig variables; you can output them by doing:

    {{ seomaticMeta.seoTitle }}

If you have a **Twitter Handle**, you'll also get variables that are used to generate your Twitter Card tags:

    seomaticMeta.twitter.card
    seomaticMeta.twitter.site
    seomaticMeta.twitter.creator
    seomaticMeta.twitter.title
    seomaticMeta.twitter.description
    seomaticMeta.twitter.image

You'll also get variables that are used to generate your Facebook Open Graph tags:

    seomaticMeta.og.type
    seomaticMeta.og.locale
    seomaticMeta.og.url
    seomaticMeta.og.title
    seomaticMeta.og.description
    seomaticMeta.og.image
    seomaticMeta.og.site_name
    seomaticMeta.og.see_also

When SEOmatic goes to render the `twitter` and `og` tags, it iterates through the respective arrays, so you can add, remove, or change any of the key-value pairs in the array, and they will be rendered.  Just ensure that the `key` is a valid schema type for the respective meta tags.

You can even do fun things like:

	{% set seomaticMeta = seomaticMeta | merge({
	    og: { 
	        type: seomaticMeta.og.type,
	        locale: seomaticMeta.og.locale,
	        url: entry.url,
	        title: "Some Title",
	        description: entry.summary,
	        image: ['one.jpg', 'two.jpg', 'three.jpg'],
	        site_name: seomaticMeta.og.site_name,
	        see_also: seomaticMeta.og.see_also
	    }
	}) %}
    
...and SEOmatic will output 3 `og:image` tags, one for each image in the array.

You can also change them all at once like this using the Twig [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax:

	{% set seomaticMeta = { 
	    seoTitle: "Some Title",
	    seoDescription: entry.summary,
	    seoKeywords: "Some,Key,Words",
	    seoImage: seomaticMeta.seoImage,
	    canonicalUrl: entry.url,
	    twitter: { 
	        card: seomaticMeta.twitter.card,
	        site: seomaticMeta.twitter.site,
	        creator: seomaticMeta.twitter.creator,
	        title: "Some Title",
	        description: entry.summary,
	        image: seomaticMeta.twitter.image
	    },
	    og: { 
	        type: seomaticMeta.og.type,
	        locale: seomaticMeta.og.locale,
	        url: entry.url,
	        title: "Some Title",
	        description: entry.summary,
	        image: seomaticMeta.og.image,
	        site_name: seomaticMeta.og.site_name,
	        see_also: seomaticMeta.og.see_also
	    }
	} %}

Anywhere we are setting a field to `seomaticMeta.*`, we're setting it to what it already is, essentially saying to leave it unchanged.  We do this because Twig requires that you pass in the entire array to the `set` operator.

Or if you want to set just one variable in the array, you can use the Twig function [merge](http://twig.sensiolabs.org/doc/filters/merge.html):

    {% set seomaticMeta = seomaticMeta | merge({'seoDescription': entry.summary }) %}

You can change these `seomaticMeta` variables in your templates that `extends` your main `layout.twig` template, and due to the Twig rendering order, when `{% hook 'seomaticRender' %}` is called, they'll be populated in your rendered SEO Meta tags.

Some of the `seomaticMeta` variables have character limitations on them, because search engines want to see only the most relevant, succinct information, and will truncate them during processing:

* **seomaticMeta.seoTitle** - 70 characters
* **seomaticMeta.seoDescription** - 160 characters
* **seomaticMeta.seoKeywords** - 200 characters

SEOmatic will automatically truncate these variables for you when you set them, so you don't have to worry about the length.  It intelligently truncates them on whole-word boundaries, so nothing will get cut off.

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

## Dynamic Keyword Generation

Generating good keywords from dynamic data is a pain; to this end, SEOmatic uses the [TextRank](https://github.com/crodas/TextRank) PHP library to generate high quality keywords from arbitrary text data.

All three of these methods accomplish the same thing:

	{# Extract keywords using the 'extractKeywords' function #}
    {{ extractKeywords( TEXT, LIMIT ) }}
    
	{# Extract keywords using the 'extractKeywords' filter #}
    {{ TEXT | extractKeywords( LIMIT ) }}
    
	{# Extract keywords using the 'extractKeywords' variable #}
    {% do craft.seomatic.extractKeywords( TEXT, LIMIT ) %}

**TEXT** is the text to extract the keywords from, and the optional **LIMIT** parameter specifies the maximum number of keywords to return (the default is 15).

Here's an example use of the `extractKeywords()` function:

	{{ extractKeywords('Scientists have developed a gel that helps brains
	recover from traumatic injuries. It has the potential to treat head injuries
	suffered in combat, car accidents, falls, or gunshot wounds. Developed by
	Dr. Ning Zhang at Clemson University in South Carolina, the gel is injected
	in liquid form at the site of injury and stimulates the growth of stem cells
	there. Brain injuries are particularly hard to repair, since injured tissues
	swell up and can cause additional damage to the cells. So far, treatments
	have tried to limit this secondary damage by lowering the temperature or
	relieving the pressure at the site of injury. However, these techniques are
	often not very effective. More recently, scientists have considered
	transplanting donor brain cells into the wound to repair damaged tissue.
	This method has so far had limited results when treating brain injuries. The
	donor cells often fail to grow or stimulate repair at the injury site,
	possibly because of the inflammation and scarring present there. The injury
	site also typically has very limited blood supply and connective tissue,
	which might prevent donor cells from getting the nutrients they require. Dr.
	Zhangs gel, however, can be loaded with different chemicals to stimulate
	various biological processes at the site of injury. In previous research
	done on rats, she was able to use the gel to help re-establish full blood
	supply at the site of brain injury. This could help create a better
	environment for donor cells. In a follow-up study, Dr. Zhang loaded the gel
	with immature stem cells, as well as the chemicals they needed to develop
	into full-fledged adult brain cells. When rats with severe brain injuries
	were treated with this mixture for eight weeks, they showed signs of
	significant recovery. The new gel could treat patients at varying stages
	following injury, and is expected to be ready for testing in humans in about
	three years.') }}

This will output the following:

    injury site, brain cells, brain injuries, donor cells, donor brain cells,
    injury, site of injury, site, cells, brain, injuries, repair, donor, damage
    to the cells, blood

So tying it all together, you might do something like this for a dynamic Blog entry:

	{% set seomaticMeta = { 
	    seoTitle: entry.title,
	    seoDescription: entry.summary,
	    seoKeywords: extractKeywords(entry.blog),
	    seoImage: entry.image.url,
	    canonicalUrl: seomaticMeta.canonicalUrl,
	    twitter: { 
	        card: seomaticMeta.twitter.card,
	        site: seomaticMeta.twitter.site,
	        creator: seomaticMeta.twitter.creator,
	        title: entry.title,
	        description: entry.summary,
	        image: entry.image.url
	    },
	    og: { 
	        type: seomaticMeta.og.type,
	        locale: seomaticMeta.og.locale,
	        url: entry.url,
	        title: entry.title,
	        description: entry.summary,
	        image: entry.image.url,
	        site_name: seomaticMeta.og.site_name,
	        see_also: seomaticMeta.og.see_also
	    }
	} %}

And there you have it, dynamic keywords for your SEO Meta.  Note that we set the `canonicalUrl` to `seomaticMeta.canonicalUrl`, effectively leaving it unchanged.

Anywhere we are setting a field to `seomaticMeta.*`, we're setting it to what it already is, essentially saying to leave it unchanged.  We do this because Twig requires that you pass in the entire array to the `set` operator.

## Dynamic Summary Generation

Generating a good summary from dynamic data is also a pain; to this end, SEOmatic uses the [TextRank](https://github.com/crodas/TextRank) PHP library to generate a summary from arbitrary text data.

All three of these methods accomplish the same thing:

	{# Extract a summary using the 'extractSummary' function #}
    {{ extractSummary( TEXT, LIMIT ) }}
    
	{# Extract a summary using the 'extractSummary' filter #}
    {{ TEXT | extractSummary( LIMIT ) }}
    
	{# Extract a summary using the 'extractSummary' variable #}
    {% do craft.seomatic.extractSummary( TEXT, LIMIT ) %}

**TEXT** is the text to extract the summary from, and the optional **LIMIT** parameter specifies the maximum number of characters to return.  The Summary is returns is at most 5% of the sentences of the text.

**Caveats** - This feature of TextRank seems to be best suited for large amounts of text.  It attempts to pick out the most relevant whole sentences based on statistical analysis.  The result may end up being too long to be useful for an `seoDescription` in some cases.

So tying it all together, you might do something like this for a dynamic Blog entry:

	{% set seomaticMeta = { 
	    seoTitle: entry.title,
	    seoDescription: extractSummary(entry.blog),
	    seoKeywords: extractKeywords(entry.blog),
	    seoImage: entry.image.url,
	    canonicalUrl: seomaticMeta.canonicalUrl,
	    twitter: { 
	        card: seomaticMeta.twitter.card,
	        site: seomaticMeta.twitter.site,
	        creator: seomaticMeta.twitter.creator,
	        title: entry.title,
	        description: extractSummary(entry.blog),
	        image: entry.image.url
	    },
	    og: { 
	        type: seomaticMeta.og.type,
	        locale: seomaticMeta.og.locale,
	        url: entry.url,
	        title: entry.title,
	        description: extractSummary(entry.blog),
	        image: entry.image.url,
	        site_name: seomaticMeta.og.site_name,
	        see_also: seomaticMeta.og.see_also
	    }
	} %}

Note that we set the `canonicalUrl` to `seomaticMeta.canonicalUrl`, effectively leaving it unchanged.

Anywhere we are setting a field to `seomaticMeta.*`, we're setting it to what it already is, essentially saying to leave it unchanged.  We do this because Twig requires that you pass in the entire array to the `set` operator.

## Humans.txt authorship

[Humans.txt](http://humanstxt.org) is an initiative for knowing the people behind a website. It's a TXT file that contains information about the different people who have contributed to building the website.

SEOmatic automatically generates [Humans.txt](http://humanstxt.org) authorship accreditation with the following tag:

    <link type="text/plain" rel="author" href="/humans.txt" />

The rendered `humans.txt` file uses the following template by default (you're free to change it as you wish):

    /* TEAM */
    
    {% if seomaticCreator.name is defined and seomaticCreator.name %}
    Creator: {{ seomaticCreator.name }}
    {% endif %}
    {% if seomaticCreator.url is defined and seomaticCreator.url %}
    URL: {{ seomaticCreator.url }}
    {% endif %}
    {% if seomaticCreator.description is defined and seomaticCreator.description %}
    Description: {{ seomaticCreator.description }}
    {% endif %}
    
    /* THANKS */
    
    Pixel & Tonic - https://pixelandtonic.com
    
    /* SITE */
    
    Standards: HTML5, CSS3
    Components: Craft CMS, Yii, PHP, Javascript, SEOmatic

## Utility Filters & Functions

SEOmatic exposes a few useful utility filters & functions that you can use... or not.

### Rendering Arbitary JSON-LD

SEOmatic gives you the ability to render an arbitrary [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) object, passed in as an array().  All three of these methods accomplish the same thing:

	{# Render arbitrary JSON-LD using the 'renderJSONLD' function #}
    {{ renderJSONLD( JSONLD_ARRAY ) }}
    
    {# Render arbitrary JSON-LD using the 'renderJSONLD' filter #}
    {{ JSONLD_ARRAY | renderJSONLD }}
    
    {# Render arbitrary JSON-LD using the 'renderJSONLD' variable #}
    {% do craft.seomatic.renderJSONLD( JSONLD_ARRAY ) %}


The JSONLD_ARRAY should be in the following format in PHP:
    
    $myJSONLD = array(
	    "type" => "Corporation",
	    "name" => "nystudio107",
	    "sameAs" => ["https://Twitter.com/nystudio107","https://plus.google.com/+nystudio107"],
	    "address" => array(
		    "type" => 'PostalAddress',
		    "addressCountry" => "US",
		    ),
	    );
	
The JSONLD_ARRAY should be in the following format in Twig:

	{% set myJSONLD = {
		"type": "Corporation",
		"name": "nystudio107",
		"sameAs": ["https://Twitter.com/nystudio107","https://plus.google.com/+nystudio107"],
		"address": {
			"type": 'PostalAddress',
			"addressCountry": "US",
		},
	} %}
	
The above arrays will render to the following JSON-LD:

	<script type="application/ld+json">
	{
	    "@context": "http://schema.org",
	    "@type": "Corporation",
	    "name": "nystudio107",
	    "sameAs": ["https://Twitter.com/nystudio107","https://plus.google.com/+nystudio107"],
	    "address": {
	        "@type": "PostalAddress",
	        "addressCountry": "US" 
	    } 
	}
	</script>
	
The array can be nested arbitrarily deep with sub-arrays.  The first key in the array, and in each sub-array, should be an "type" with a valid [Schema.org](http://Schema.org) type as the value.  Because Twig doesn't support array keys with non-alphanumeric characters, SEOmatic transforms the keys "type" into "@type" at render time.

Here's a practical example.  Let's say you're working on a spiffy new online store using Craft Commerce, and you want to add in some microdata for the products listed in your store, for SEO purposes.  You can do something like this:

	{% set myJSONLD = {
		type: "Product",
		name: "Brad's for Men Cologne",
		image: "http://bradsformen.com/cologne.png",
		logo: "http://bradsformen.com/cologne_logo.png",
		description: "Brad Bell's musky essence will intoxicate you.",
		model: "XQJ-37",
		offers: {
			type: "Offer",
			url: "http://bradsformen.com/cologne",
			price: "69.99",
			priceCurrency: "USD",
			acceptedPaymentMethod: ["CreditCard", "PayPal"],
			seller: {
			    type: "Corporation",
			    name: "Brad Brands Intl.",
			    url: "http://bradsformen.com"
			}
		},
		manufacturer: {
		    type: "Organization",
		    name: "Scents Unlimited",
		    url: "http://scentsunlimited.com"
		},
		aggregateRating: {
			type: "AggregateRating",
			bestRating: "100",
			ratingCount: "24",
			ratingValue: "87"
		},
	} %}
    {{ myJSONLD | renderJSONLD }}

Obviously, you'll want to substitute in variables for the above, e.g.:

	{% set products = craft.commerce.products.type('normal').find() %}
	
	{% for product in products %}
		{% for variant in products.variants %}
			{% set myJSONLD = {
				type: "Product",
				name: "{{ variant.description }}",
				image: "{{ variant.myProductShot }}",
				logo: "{{ variant.myProductLogo }}",
				description: "{{ variant.myProductDescription }}",
				model: "{{ variant.myProductModel }}",
				offers: {
					type: "Offer",
					url: "{{ product.url }}",
					price: "{{ variant.price }}",
					priceCurrency: "USD",
					acceptedPaymentMethod: ["CreditCard", "PayPal"],
					seller: {
					    type: "Corporation",
					    name: "{{ seomaticSiteMeta.siteSeoName }}",
					    url: "{{ siteUrl }}"
					}
				}
			} %}
		{{ myJSONLD | renderJSONLD }}
		{% endfor %}
	{% endfor %}

There are many other values available for you to use; see the [Product](https://developers.google.com/schemas/reference/types/Product) schema for details.

### truncateStringOnWord()

All three of these methods accomplish the same thing:

	{# Truncate a string on word boundaries using the 'truncateStringOnWord' function #}
    {{ truncateStringOnWord( THESTRING, DESIREDLENGTH ) }}
    
	{# Truncate a string on word boundaries using the 'truncateStringOnWord' filter #}
    {{ THESTRING | truncateStringOnWord( DESIREDLENGTH ) }}
    
	{# Truncate a string on word boundaries using the 'truncateStringOnWord' variable #}
    {% do craft.seomatic.truncateStringOnWord( THESTRING, DESIREDLENGTH ) %}

**THESTRING** is the string to be truncated, and the optional **DESIREDLENGTH** parameter specifies the desired length in characters.  The returned string will be broken on a whole-word boundary, with an … appended to the end if it is truncated.

You shouldn't need to use truncateStringOnWord() for SEO Meta like `seoTitle` & `seoDescription` that have character limitations, because SEOmatic will truncate them for you automatically.  However you may find this function handy for other purposes.

### extractTextFromMatrix()

All three of these methods accomplish the same thing:

    {# Extract all and concatenate all of the text fields from a Matrix block using the 'extractTextFromMatrix' function #}
    {{ extractTextFromMatrix( THEMATRIXBLOCK ) }}
    
    {# Extract all and concatenate all of the text fields from a Matrix block using the 'extractTextFromMatrix' filter #}
    {{ THEMATRIXBLOCK | extractTextFromMatrix() }}
    
    {# Extract all and concatenate all of the text fields from a Matrix block using the 'extractTextFromMatrix' variable #}
    {% do craft.seomatic. extractTextFromMatrix( THEMATRIXBLOCK ) %}

**THEMATRIXBLOCK** is the Matrix block to extract text from.  It iterates through all of the 'Text' and 'Rich Text' fields in a Matrix block, and concatenates the text together for you.  This is a useful precursor for the `extractKeywords()` function.

### encodeEmailAddress()

All three of these methods accomplish the same thing:

	{# Ordinal-encode an email address to obfuscate it using the 'encodeEmailAddress' function #}
    {{ encodeEmailAddress( EMAILADDRESS ) }}
    
	{# Ordinal-encode an email address to obfuscate it using the 'encodeEmailAddress' filter #}
    {{ EMAILADDRESS | encodeEmailAddress() }}
    
	{# Ordinal-encode an email address to obfuscate it using the 'encodeEmailAddress' variable #}
    {% do craft.seomatic.encodeEmailAddress( EMAILADDRESS ) %}

**EMAILADDRESS** is the email address to be ordinal-encoded.  For instance, `info@nystudio107.com` becomes:
    
    &#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;

Google can still properly decode email addresses that are ordinal-encoded, it's still readable by humans when displayed, but it prevents some bots from recognizing it as an email address.

## SEOmatic Site Meta Twig Variables

SEOmatic populates your templates with the following global variables for Site Meta:

    seomaticSiteMeta.siteSeoName
    seomaticSiteMeta.siteSeoTitle
    seomaticSiteMeta.siteSeoDescription
    seomaticSiteMeta.siteSeoKeywords
    seomaticSiteMeta.siteSeoImage
    seomaticSiteMeta.siteOwnerType
    
All of the variables are from your SEO Site Meta settings, and will be the same for every template rendered.  They are for the most part very similar to your SEO Meta variables, but they do not change from template to template: they are site-wide.

Mostly, you won't need to change them in your Twig templates, but it can be useful to reference or output them.  These work like any other Twig variables; you can output them by doing:

    {{ seomaticSiteMeta.siteSeoName }}

You can also change these variables the same way you change the "Dynamic Twig SEO Meta" (using Twig `set` and `merge`), but in practice they typically will just be set in the SEOmatic **SEO Site Meta** settings page in the Admin CP.

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

## SEOmatic Site Identity Twig Variables

SEOmatic populates your templates with an array of Site Identity variables; see the **Rendered Identity Microdata** section for a complete listing of them.  All of the variables are from your Site Identity settings, and will be the same for every template rendered.

Mostly, you won't need to change them in your Twig templates, but it can be useful to reference or output them.  These work like any other Twig variables; you can output them by doing:

    {{ seomaticIdentity.name }}

You can also change these variables the same way you change the "Dynamic Twig SEO Meta" (using Twig `set` and `merge`), but in practice they typically will just be set in the SEOmatic **Site Identity** settings page in the Admin CP.

Because the `seomaticIdentity` array is directly translated into JSON-LD, you can manipulate it via Twig to modify or add to the JSON-LD.  For example, let's say you want to add a [Brand](https://schema.org/Brand) to your [Corporation](https://schema.org/Corporation)-type Identity JSON-LD:

	{% set myBrand = {
		type: "Brand",
		name: "Brad's for Men",
		description: "Brad Bell's musky essence will intoxicate you.",
		url: "http://bradsformen.com",
		logo: "http://bradsformen.com/logo.png",
		image: "http://bradsformen.com/lifestyle.jpg",
	} %}

	{% set seomaticIdentity = seomaticIdentity | merge({'brand': myBrand }) %}

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

The `email` variable is ordinal-encoded to obfuscate it.  For instance, `info@nystudio107.com` becomes:
    
    &#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;

## SEOmatic Social Media Twig Variables

SEOmatic populates your templates with the following global variables for Social Media:

    seomaticSocial.twitterHandle
    seomaticSocial.facebookHandle
    seomaticSocial.facebookProfileId
    seomaticSocial.linkedInHandle
    seomaticSocial.googlePlusHandle
    seomaticSocial.googleSiteVerification
    seomaticSocial.youtubeHandle
    seomaticSocial.instagramHandle
    seomaticSocial.pinterestHandle
        
All of the variables are from your Social Media settings, and will be the same for every template rendered.

Mostly, you won't need to change them in your Twig templates, but it can be useful to reference or output them.  These work like any other Twig variables; you can output them by doing:

    {{ seomaticSocial.twitterHandle }}

You can also change these variables the same way you change the "Dynamic Twig SEO Meta" (using Twig `set` and `merge`), but in practice they typically will just be set in the SEOmatic **Social Media** settings page in the Admin CP.

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

## SEOmatic Site Creator Twig Variables

SEOmatic populates your templates with an array of Site Creator variables; see the **Rendered WebSite Microdata** section for a complete listing of them.  All of the variables are from your Site Creator settings, and will be the same for every template rendered.

Mostly, you won't need to change them in your Twig templates, but it can be useful to reference or output them.  These work like any other Twig variables; you can output them by doing:

    {{ seomaticCreator.name }}

You can also change these variables the same way you change the "Dynamic Twig SEO Meta" (using Twig `set` and `merge`), but in practice they typically will just be set in the SEOmatic **Site Creator** settings page in the Admin CP.

Because the `seomaticCreator` array is directly translated into JSON-LD, you can manipulate it via Twig to modify or add to the JSON-LD.  For example, let's say you want to add an `affiliation` to your [Person](https://schema.org/Person)-type Creator JSON-LD:

	{% set myAffiliation = {
		type: "Organization",
		name: "nystudio107",
		description: "Impeccable design married with precision craftsmanship.",
		url: "http://nystudio107.com",
	} %}

	{% set seomaticCreator = seomaticCreator | merge({'affiliation': myAffiliation }) %}

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

The `email` variable is ordinal-encoded to obfuscate it.  For instance, `info@nystudio107.com` becomes:

    &#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;

## SEOmatic Helper Twig Variables

## Previewing your SEO Meta

There's a lot going on here, so to make it all more easily understood, SEOmatic offers two ways to preview your SEO Meta.  You have to **Save** the settings first before you preview them; a "Live Preview" feature is on the wish list for future versions.

### Preview SEO Meta Display

![Screenshot](resources/screenshots/seomatic02.png)

Clicking on the **Preview SEO Meta Display** button will show you a preview of what the rendered SEO Meta will look like to various services that scrape your SEO Meta tags, such as Google, Twitter, and Facebook.

This serves as a nice sanity check for you, and a very nice way to show clients the amazing job you did on their SEO strategy.

If you click on the **Preview SEO Meta Display** button when you are editing a SEO Template Meta, you'll see the result of that particular template's SEO Template Meta tags.  Otherwise, you will see the SEO Site Meta tags.

### Preview SEO Meta Tags

Clicking on the **Preview SEO Meta Tags** button will show you Twig/HTML output of the following things:

#### Meta Template Variables

These are the Twig variables that SEOmatic pre-populates, and makes available to you in your templates. They are used when rendering the SEO Meta, so you can manipulate them however you want before rendering your SEO Meta. For example, you might change the `seomaticMeta.seoDescription` to be the summary field of an entry.

	{% set seomaticMeta = { 
	    seoTitle: "We make the big stuff big & the little stuff little",
	    seoDescription: "Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!",
	    seoKeywords: "colossal,considerable,enormous,fat,full,gigantic,hefty,huge,immense,massive,sizable,substantial,tremendous,",
	    seoImage: "http://nystudio107.dev/img/site/big_hq.jpg",
	    canonicalUrl: "http://nystudio107.dev/",
	    twitter: { 
	        card: "summary_large_image",
	        site: "@nystudio107",
	        creator: "@nystudio107",
	        title: "We make the big stuff big & the little stuff little | Big Entity, Inc.",
	        description: "Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!",
	        image: "http://nystudio107.dev/img/site/big_hq.jpg"
	    },
	    og: { 
	        type: "website",
	        locale: "en",
	        url: "http://nystudio107.dev/admin/seomatic/social",
	        title: "We make the big stuff big & the little stuff little | Big Entity, Inc.",
	        description: "Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!",
	        image: "http://nystudio107.dev/img/site/big_hq.jpg",
	        site_name: "Big Entity, Inc.",
	        see_also: ["https://twitter.com/nystudio107","https://www.facebook.com/nystudio107","https://plus.google.com/+nystudio107","https://www.linkedin.com/company/nystudio107","https://www.youtube.com/user/nystudio107","https://www.instagram.com/nystudio107","https://www.pinterest.com/nystudio107"]
	    }
	} %}
	
	{% set seomaticHelper = { 
	    twitterUrl: "https://twitter.com/nystudio107",
	    facebookUrl: "https://www.facebook.com/nystudio107",
	    googlePlusUrl: "https://plus.google.com/+nystudio107",
	    linkedInUrl: "https://www.linkedin.com/company/nystudio107",
	    youtubeUrl: "https://www.youtube.com/user/nystudio107",
	    instagramUrl: "https://www.instagram.com/nystudio107",
	    pinterestUrl: "https://www.pinterest.com/nystudio107",
	    ownerGoogleSiteVerification: "BM6VkEojTIASDEWyTLro7VNhZnW_036LNdcYk5j9X_8g",
	    ownerCopyrightNotice: "Copyright ©2015 Big Entity, Inc. All rights reserved.",
	    ownerAddressString: "Big Entity, Inc., 123 Some Road, Porchester, NY 11450, USA",
	    ownerAddressHtml: "Big Entity, Inc.<br />123 Some Road<br />Porchester, NY 11450<br />USA<br />",
	    ownerMapUrl: "http://maps.google.com/maps?q=Big+Entity%2C+Inc.%2C+123+Some+Road%2C+Porchester%2C+NY+11450%2C+USA",
	    creatorCopyrightNotice: "Copyright ©2015 NY Studio 107. All rights reserved.",
	    creatorAddressString: "",
	    creatorAddressHtml: "",
	    creatorMapUrl: ""
	} %}
	
	{% set seomaticSiteMeta = { 
	    siteSeoName: "Big Entity, Inc.",
	    siteSeoTitle: "We make the big stuff big & the little stuff little",
	    siteSeoDescription: "Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!",
	    siteSeoKeywords: "colossal,considerable,enormous,fat,full,gigantic,hefty,huge,immense,massive,sizable,substantial,tremendous,",
	    siteSeoImage: "http://nystudio107.dev/img/site/big_hq.jpg"
	} %}
	
	{% set seomaticSocial = { 
	    twitterHandle: "nystudio107",
	    facebookHandle: "nystudio107",
	    facebookProfileId: "642246343",
	    linkedInHandle: "nystudio107",
	    googlePlusHandle: "nystudio107",
	    youtubeHandle: "nystudio107",
	    instagramHandle: "nystudio107",
	    pinterestHandle: "nystudio107"
	} %}
	
	{% set seomaticIdentity = { 
	    type: "Corporation",
	    name: "Big Entity, Inc.",
	    alternateName: "Big",
	    description: "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	    url: "http://BigEntity.com",
	    image: "http://nystudio107.dev/img/site/big_logo.jpg",
	    telephone: "585.214.9439",
	    email: "info@BigEntity.com",
	    address: { 
	        type: "PostalAddress",
	        streetAddress: "123 Some Road",
	        addressLocality: "Porchester",
	        addressRegion: "NY",
	        postalCode: "11450",
	        addressCountry: "US"
	    },
	    logo: "http://nystudio107.dev/img/site/big_logo.jpg",
	    location: { 
	        type: "Place",
	        name: "Big Entity, Inc.",
	        alternateName: "Big",
	        description: "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	        hasMap: "http://maps.google.com/maps?q=Big+Entity%2C+Inc.%2C+123+Some+Road%2C+Porchester%2C+NY+11450%2C+USA",
	        geo: { 
	            type: "GeoCoordinates",
	            latitude: "-10.447525",
	            longitude: "105.690449"
	        },
	        address: { 
	            type: "PostalAddress",
	            streetAddress: "123 Some Road",
	            addressLocality: "Porchester",
	            addressRegion: "NY",
	            postalCode: "11450",
	            addressCountry: "US"
	        }
	    },
	    duns: "12345678",
	    founder: "Mr. Big",
	    foundingDate: "10/2011",
	    foundingLocation: "Redding, CT",
	    tickerSymbol: "BGE"
	} %}
	
	{% set seomaticCreator = { 
	    type: "Corporation",
	    name: "NY Studio 107",
	    alternateName: "nystudio107",
	    description: "Impeccable design married with precision craftsmanship",
	    url: "http://nystudio107.com",
	    image: "http://nystudio107.dev/img/site/nys_seo_logo.png",
	    email: "info@nystudio107.com",
	    address: { 
	        type: "PostalAddress",
	        addressLocality: "Webster",
	        addressRegion: "NY",
	        postalCode: "14580",
	        addressCountry: "US"
	    },
	    logo: "http://nystudio107.dev/img/site/nys_seo_logo.png",
	    location: { 
	        type: "Place",
	        name: "NY Studio 107",
	        alternateName: "nystudio107",
	        description: "Impeccable design married with precision craftsmanship",
	        geo: { 
	            type: "GeoCoordinates",
	            latitude: "43.11558",
	            longitude: "-77.59647199999999"
	        },
	        address: { 
	            type: "PostalAddress",
	            addressLocality: "Webster",
	            addressRegion: "NY",
	            postalCode: "14580",
	            addressCountry: "US"
	        }
	    }
	} %}
	
	{% set seomaticTemplatePath = "" %}
		
You can treat all of these like regular Twig variables; for instance, `{{ seomaticHelper.twitterUrl }}` will output the URL to the website's Twitter page. You can change these variables using the Twig array [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax, or using the Twig function [merge](http://twig.sensiolabs.org/doc/filters/merge.html). Any changes you make will be reflected in the SEO Meta rendered with `{% hook 'seomaticRender' %}` on your page.

#### Rendered SEO Meta

The `{% hook 'seomaticRender' %}` tag generates these SEO Meta for you, based on the Meta Template Variables (above). By default, it uses an internal template, but you can pass your own template to be used instead, like this: `{% set seomaticTemplatePath = 'path/template' %} {% hook 'seomaticRender' %}`

SEOmatic cascades Meta settings; if you have a Meta associated with the current template, it uses that. Otherwise it falls back on the SEO Site Meta settings. If a field is empty for a Template Meta, it falls back on the SEO Site Meta setting for that field.

	<!-- BEGIN SEOmatic rendered SEO Meta -->
	
	<title>[devMode] We make the big stuff big &amp; the little stuff… | Big Entity, Inc.</title> <!-- {% if craft.config.devMode %}[devMode] {% endif %}{% if seomaticSiteMeta.siteSeoTitlePlacement == "before" %}{{ seomaticSiteMeta.siteSeoName |raw }}{% if seomaticMeta.seoTitle %} {{ seomaticSiteMeta.siteSeoTitleSeparator }} {% endif %}{% endif %}{{ seomaticMeta.seoTitle |raw }}{% if seomaticSiteMeta.siteSeoTitlePlacement == "after" %}{% if seomaticMeta.seoTitle %} {{ seomaticSiteMeta.siteSeoTitleSeparator }} {% endif %}{{ seomaticSiteMeta.siteSeoName |raw }}{% endif %} -->
	
	<!-- Standard SEO -->
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="referrer" content="always" />
	<meta name="robots" content="all" /> <!-- {{ seomaticMeta.robots }} -->
	<meta name="keywords" content="colossal, considerable, enormous, fat, full, gigantic, hefty, huge, immense, massive, sizable, substantial, tremendous" /> <!-- {{ seomaticMeta.seoKeywords }} -->
	<meta name="description" content="Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!" /> <!-- {{ seomaticMeta.seoDescription }} -->
	<meta name="generator" content="SEOmatic" /> <!-- SEOmatic -->
	<link rel="canonical" href="http://nystudio107.dev/" /> <!-- {{ seomaticMeta.canonicalUrl }} (defaults to craft.request.url) -->
	<meta name="geo.region" content="NY" /> <!-- {{ seomaticIdentity.address.addressRegion }} -->
	<meta name="geo.position" content="41.005432,-73.65897799999999" /> <!-- {{ seomaticIdentity.location.geo.latitude }},{{ seomaticIdentity.location.geo.longitude }} -->
	<meta name="ICBM" content="41.005432,-73.65897799999999" /> <!-- {{ seomaticIdentity.location.geo.latitude }},{{ seomaticIdentity.location.geo.longitude }} -->
	<meta name="geo.placename" content="Big Entity, Inc." /> <!-- {{ seomaticIdentity.location.name }} -->
	
	<!-- Dublin Core basic info -->
	
	<meta name="dcterms.Identifier" content="http://nystudio107.dev/" /> <!-- {{ seomaticMeta.canonicalUrl }} (defaults to craft.request.url) -->
	<meta name="dcterms.Format" content="text/html" /> <!-- text/html -->
	<meta name="dcterms.Relation" content="Big Entity, Inc." /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta name="dcterms.Language" content="en" /> <!-- {{ craft.locale }} -->
	<meta name="dcterms.Publisher" content="Big Entity, Inc." /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta name="dcterms.Type" content="text/html" /> <!-- text/html -->
	<meta name="dcterms.Coverage" content="http://nystudio107.dev/" /> <!-- {{ siteUrl }} -->
	<meta name="dcterms.Rights" content="Copyright &copy;2016 Big Entity, Inc. " /> <!-- {{ seomaticHelper.ownerCopyrightNotice }} -->
	<meta name="dcterms.Title" content="We make the big stuff big &amp; the little stuff…" /> <!-- {{ seomaticMeta.seoTitle }} -->
	<meta name="dcterms.Creator" content="nystudio107" /> <!-- {{ seomaticCreator.name }} -->
	<meta name="dcterms.Subject" content="colossal, considerable, enormous, fat, full, gigantic, hefty, huge, immense, massive, sizable, substantial, tremendous" /> <!-- {{ seomaticMeta.seoKeywords }} -->
	<meta name="dcterms.Contributor" content="Big Entity, Inc." /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta name="dcterms.Date" content="2016-02-07" /> <!-- {{ now | date('Y-m-d') }} -->
	<meta name="dcterms.Description" content="Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!" /> <!-- {{ seomaticMeta.seoDescription }} -->
	
	<!-- Facebook OpenGraph -->
	
	<meta property="fb:profile_id" content="bigentity" /> <!-- {{ seomaticSocial.facebookProfileId }} -->
	<meta property="og:type" content="website" /> <!-- {{ seomatic.og.type }} -->
	<meta property="og:locale" content="en_US" /> <!-- {{ seomatic.og.locale }} -->
	<meta property="og:url" content="http://nystudio107.dev/" /> <!-- {{ seomatic.og.url }} -->
	<meta property="og:title" content="We make the big stuff big &amp; the little stuff little | Big Entity, Inc." /> <!-- {{ seomatic.og.title }} -->
	<meta property="og:description" content="Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!" /> <!-- {{ seomatic.og.description }} -->
	<meta property="og:image" content="http://nystudio107.dev/img/site/big_hq.jpg" /> <!-- {{ seomatic.og.image }} -->
	<meta property="og:site_name" content="Big Entity, Inc." /> <!-- {{ seomatic.og.site_name }} -->
	<meta property="og:see_also" content="https://twitter.com/bigentity" /> <!-- {{ seomatic.og.see_also[0] }} -->
	<meta property="og:see_also" content="https://www.facebook.com/bigentity" /> <!-- {{ seomatic.og.see_also[1] }} -->
	<meta property="og:see_also" content="https://plus.google.com/+bigentity" /> <!-- {{ seomatic.og.see_also[2] }} -->
	<meta property="og:see_also" content="https://www.linkedin.com/company/bigentity" /> <!-- {{ seomatic.og.see_also[3] }} -->
	<meta property="og:see_also" content="https://www.youtube.com/user/bigentity" /> <!-- {{ seomatic.og.see_also[4] }} -->
	<meta property="og:see_also" content="https://www.youtube.com/c/bigentity" /> <!-- {{ seomatic.og.see_also[5] }} -->
	<meta property="og:see_also" content="https://www.instagram.com/bigentity" /> <!-- {{ seomatic.og.see_also[6] }} -->
	<meta property="og:see_also" content="https://www.pinterest.com/bigentity" /> <!-- {{ seomatic.og.see_also[7] }} -->
	<meta property="og:see_also" content="https://github.com/bigentity" /> <!-- {{ seomatic.og.see_also[8] }} -->
	
	<!-- Twitter Card -->
	
	<meta property="twitter:card" content="summary" /> <!-- {{ seomatic.twitter.card }} -->
	<meta property="twitter:site" content="@bigentity" /> <!-- {{ seomatic.twitter.site }} -->
	<meta property="twitter:title" content="We make the big stuff big &amp; the little stuff little | Big Entity, Inc." /> <!-- {{ seomatic.twitter.title }} -->
	<meta property="twitter:description" content="Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!" /> <!-- {{ seomatic.twitter.description }} -->
	<meta property="twitter:image" content="http://nystudio107.dev/img/site/big_hq.jpg" /> <!-- {{ seomatic.twitter.image }} -->
	
	<!-- Google Publisher -->
	
	<link rel="publisher" href="https://plus.google.com/+bigentity" /> <!-- {{ seomaticHelper.googlePlusUrl }} -->
	
	<!-- Humans.txt authorship http://humanstxt.org -->
	
	<link type="text/plain" rel="author" href="/humans.txt" />
	
	<!-- Domain verification -->
	
	<meta name="google-site-verification" content="BM6VkEojTIASDEWyTLro7VNhZnW_036LNdcYk5j9X_8g" /> <!-- {{ seomaticHelper.ownerGoogleSiteVerification }} -->
	
	<!-- Google Analytics -->
	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-XXXXXX-XX', 'auto');
	  ga('require', 'displayfeatures');
	  ga('send', 'pageview');
	</script>
	
	<!-- END SEOmatic rendered SEO Meta -->
	
#### Rendered Identity Microdata

The `{% hook 'seomaticRender' %}` tag also generates [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) identity microdata that tells search engines about the company that owns the website.  JSON-LD is an alternative to microdata you may already be familiar with, such as: `<div itemscope itemtype='http://schema.org/Organization'>`.  JSON-LD has the advantage of not being intertwined with HTML markup, so it's easier to use.  It is parsed and consumed by Google, allowing you to tell Google what your site is about, rather than having it try to guess.

	<script type="application/ld+json">
	{
	    "@context": "http://schema.org",
	    "@type": "AdultEntertainment",
	    "name": "Big Entity, Inc.",
	    "alternateName": "Big",
	    "description": "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	    "url": "http://BigEntity.com",
	    "sameAs": ["https://twitter.com/bigentity","https://www.facebook.com/bigentity","https://plus.google.com/+bigentity","https://www.linkedin.com/company/bigentity","https://www.youtube.com/user/bigentity","https://www.youtube.com/c/bigentity","https://www.instagram.com/bigentity","https://www.pinterest.com/bigentity","https://github.com/bigentity"],
	    "image": "http://nystudio107.dev/img/site/big_logo.jpg",
	    "telephone": "585.325.1910",
	    "email": "&#105;&#110;&#102;&#111;&#64;&#32;&#66;&#105;&#103;&#69;&#110;&#116;&#105;&#116;&#121;&#46;&#99;&#111;&#109;",
	    "address": {
	        "@type": "PostalAddress",
	        "streetAddress": "311 N Main St",
	        "addressLocality": "Portchester",
	        "addressRegion": "NY",
	        "postalCode": "10573",
	        "addressCountry": "US" 
	    },
	    "openingHoursSpecification": [
	        {
	            "@type": "OpeningHoursSpecification",
	            "closes": "20:00:00",
	            "dayOfWeek": ["Monday"],
	            "opens": "12:00:00" 
	        },
	        {
	            "@type": "OpeningHoursSpecification",
	            "closes": "22:00:00",
	            "dayOfWeek": ["Tuesday"],
	            "opens": "12:00:00" 
	        },
	        {
	            "@type": "OpeningHoursSpecification",
	            "closes": "20:00:00",
	            "dayOfWeek": ["Wednesday"],
	            "opens": "12:00:00" 
	        },
	        {
	            "@type": "OpeningHoursSpecification",
	            "closes": "22:00:00",
	            "dayOfWeek": ["Thursday"],
	            "opens": "12:00:00" 
	        },
	        {
	            "@type": "OpeningHoursSpecification",
	            "closes": "20:00:00",
	            "dayOfWeek": ["Friday"],
	            "opens": "12:00:00" 
	        }
	    ],
	    "logo": "http://nystudio107.dev/img/site/big_logo.jpg",
	    "location": {
	        "@type": "Place",
	        "name": "Big Entity, Inc.",
	        "alternateName": "Big",
	        "description": "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	        "hasMap": "http://maps.google.com/maps?q=Big+Entity%2C+Inc.%2C+311+N+Main+St%2C+Portchester%2C+NY+10573%2C+US",
	        "telephone": "585.325.1910",
	        "image": "http://nystudio107.dev/img/site/big_logo.jpg",
	        "logo": "http://nystudio107.dev/img/site/big_logo.jpg",
	        "url": "http://BigEntity.com",
	        "sameAs": ["https://twitter.com/bigentity","https://www.facebook.com/bigentity","https://plus.google.com/+bigentity","https://www.linkedin.com/company/bigentity","https://www.youtube.com/user/bigentity","https://www.youtube.com/c/bigentity","https://www.instagram.com/bigentity","https://www.pinterest.com/bigentity","https://github.com/bigentity"],
	        "openingHoursSpecification": [
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Monday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "22:00:00",
	                "dayOfWeek": ["Tuesday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Wednesday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "22:00:00",
	                "dayOfWeek": ["Thursday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Friday"],
	                "opens": "12:00:00" 
	            }
	        ],
	        "geo": {
	            "@type": "GeoCoordinates",
	            "latitude": "41.005432",
	            "longitude": "-73.65897799999999" 
	        },
	        "address": {
	            "@type": "PostalAddress",
	            "streetAddress": "311 N Main St",
	            "addressLocality": "Portchester",
	            "addressRegion": "NY",
	            "postalCode": "10573",
	            "addressCountry": "US" 
	        } 
	    },
	    "duns": "54316",
	    "founder": "Mr. Big",
	    "foundingDate": "2011-11-01",
	    "foundingLocation": "Redding, CT, USA" 
	}
	</script>
				
#### Rendered WebSite Microdata

The `{% hook 'seomaticRender' %}` tag also generates [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) WebSite microdata that tells search engines about the website.  JSON-LD is an alternative to microdata you may already be familiar with, such as: `<div itemscope itemtype='http://schema.org/Organization'>`.  JSON-LD has the advantage of not being intertwined with HTML markup, so it's easier to use.  It is parsed and consumed by Google, allowing you to tell Google what your site is about, rather than having it try to guess.

	<script type="application/ld+json">
	{
	    "@context": "http://schema.org",
	    "@type": "WebSite",
	    "name": "Big Entity, Inc.",
	    "description": "Big Entity specializes in making the big stuff big, but we also know how to make the little stuff little!",
	    "url": "http://nystudio107.dev/",
	    "image": "http://nystudio107.dev/img/site/big_hq.jpg",
	    "sameAs": ["https://twitter.com/bigentity","https://www.facebook.com/bigentity","https://plus.google.com/+bigentity","https://www.linkedin.com/company/bigentity","https://www.youtube.com/user/bigentity","https://www.youtube.com/c/bigentity","https://www.instagram.com/bigentity","https://www.pinterest.com/bigentity","https://github.com/bigentity"],
	    "copyrightHolder": {
	        "@type": "AdultEntertainment",
	        "name": "Big Entity, Inc.",
	        "alternateName": "Big",
	        "description": "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	        "url": "http://BigEntity.com",
	        "sameAs": ["https://twitter.com/bigentity","https://www.facebook.com/bigentity","https://plus.google.com/+bigentity","https://www.linkedin.com/company/bigentity","https://www.youtube.com/user/bigentity","https://www.youtube.com/c/bigentity","https://www.instagram.com/bigentity","https://www.pinterest.com/bigentity","https://github.com/bigentity"],
	        "image": "http://nystudio107.dev/img/site/big_logo.jpg",
	        "telephone": "585.325.1910",
	        "email": "&#105;&#110;&#102;&#111;&#64;&#32;&#66;&#105;&#103;&#69;&#110;&#116;&#105;&#116;&#121;&#46;&#99;&#111;&#109;",
	        "address": {
	            "@type": "PostalAddress",
	            "streetAddress": "311 N Main St",
	            "addressLocality": "Portchester",
	            "addressRegion": "NY",
	            "postalCode": "10573",
	            "addressCountry": "US" 
	        },
	        "openingHoursSpecification": [
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Monday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "22:00:00",
	                "dayOfWeek": ["Tuesday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Wednesday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "22:00:00",
	                "dayOfWeek": ["Thursday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Friday"],
	                "opens": "12:00:00" 
	            }
	        ],
	        "logo": "http://nystudio107.dev/img/site/big_logo.jpg",
	        "location": {
	            "@type": "Place",
	            "name": "Big Entity, Inc.",
	            "alternateName": "Big",
	            "description": "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	            "hasMap": "http://maps.google.com/maps?q=Big+Entity%2C+Inc.%2C+311+N+Main+St%2C+Portchester%2C+NY+10573%2C+US",
	            "telephone": "585.325.1910",
	            "image": "http://nystudio107.dev/img/site/big_logo.jpg",
	            "logo": "http://nystudio107.dev/img/site/big_logo.jpg",
	            "url": "http://BigEntity.com",
	            "sameAs": ["https://twitter.com/bigentity","https://www.facebook.com/bigentity","https://plus.google.com/+bigentity","https://www.linkedin.com/company/bigentity","https://www.youtube.com/user/bigentity","https://www.youtube.com/c/bigentity","https://www.instagram.com/bigentity","https://www.pinterest.com/bigentity","https://github.com/bigentity"],
	            "openingHoursSpecification": [
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "20:00:00",
	                    "dayOfWeek": ["Monday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "22:00:00",
	                    "dayOfWeek": ["Tuesday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "20:00:00",
	                    "dayOfWeek": ["Wednesday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "22:00:00",
	                    "dayOfWeek": ["Thursday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "20:00:00",
	                    "dayOfWeek": ["Friday"],
	                    "opens": "12:00:00" 
	                }
	            ],
	            "geo": {
	                "@type": "GeoCoordinates",
	                "latitude": "41.005432",
	                "longitude": "-73.65897799999999" 
	            },
	            "address": {
	                "@type": "PostalAddress",
	                "streetAddress": "311 N Main St",
	                "addressLocality": "Portchester",
	                "addressRegion": "NY",
	                "postalCode": "10573",
	                "addressCountry": "US" 
	            } 
	        },
	        "duns": "54316",
	        "founder": "Mr. Big",
	        "foundingDate": "2011-11-01",
	        "foundingLocation": "Redding, CT, USA" 
	    },
	    "author": {
	        "@type": "AdultEntertainment",
	        "name": "Big Entity, Inc.",
	        "alternateName": "Big",
	        "description": "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	        "url": "http://BigEntity.com",
	        "sameAs": ["https://twitter.com/bigentity","https://www.facebook.com/bigentity","https://plus.google.com/+bigentity","https://www.linkedin.com/company/bigentity","https://www.youtube.com/user/bigentity","https://www.youtube.com/c/bigentity","https://www.instagram.com/bigentity","https://www.pinterest.com/bigentity","https://github.com/bigentity"],
	        "image": "http://nystudio107.dev/img/site/big_logo.jpg",
	        "telephone": "585.325.1910",
	        "email": "&#105;&#110;&#102;&#111;&#64;&#32;&#66;&#105;&#103;&#69;&#110;&#116;&#105;&#116;&#121;&#46;&#99;&#111;&#109;",
	        "address": {
	            "@type": "PostalAddress",
	            "streetAddress": "311 N Main St",
	            "addressLocality": "Portchester",
	            "addressRegion": "NY",
	            "postalCode": "10573",
	            "addressCountry": "US" 
	        },
	        "openingHoursSpecification": [
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Monday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "22:00:00",
	                "dayOfWeek": ["Tuesday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Wednesday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "22:00:00",
	                "dayOfWeek": ["Thursday"],
	                "opens": "12:00:00" 
	            },
	            {
	                "@type": "OpeningHoursSpecification",
	                "closes": "20:00:00",
	                "dayOfWeek": ["Friday"],
	                "opens": "12:00:00" 
	            }
	        ],
	        "logo": "http://nystudio107.dev/img/site/big_logo.jpg",
	        "location": {
	            "@type": "Place",
	            "name": "Big Entity, Inc.",
	            "alternateName": "Big",
	            "description": "We sell only big stuff... but we'll sell you little stuff too, but only in bulk containers of 1,000 units per container.  So then it's big too.",
	            "hasMap": "http://maps.google.com/maps?q=Big+Entity%2C+Inc.%2C+311+N+Main+St%2C+Portchester%2C+NY+10573%2C+US",
	            "telephone": "585.325.1910",
	            "image": "http://nystudio107.dev/img/site/big_logo.jpg",
	            "logo": "http://nystudio107.dev/img/site/big_logo.jpg",
	            "url": "http://BigEntity.com",
	            "sameAs": ["https://twitter.com/bigentity","https://www.facebook.com/bigentity","https://plus.google.com/+bigentity","https://www.linkedin.com/company/bigentity","https://www.youtube.com/user/bigentity","https://www.youtube.com/c/bigentity","https://www.instagram.com/bigentity","https://www.pinterest.com/bigentity","https://github.com/bigentity"],
	            "openingHoursSpecification": [
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "20:00:00",
	                    "dayOfWeek": ["Monday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "22:00:00",
	                    "dayOfWeek": ["Tuesday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "20:00:00",
	                    "dayOfWeek": ["Wednesday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "22:00:00",
	                    "dayOfWeek": ["Thursday"],
	                    "opens": "12:00:00" 
	                },
	                {
	                    "@type": "OpeningHoursSpecification",
	                    "closes": "20:00:00",
	                    "dayOfWeek": ["Friday"],
	                    "opens": "12:00:00" 
	                }
	            ],
	            "geo": {
	                "@type": "GeoCoordinates",
	                "latitude": "41.005432",
	                "longitude": "-73.65897799999999" 
	            },
	            "address": {
	                "@type": "PostalAddress",
	                "streetAddress": "311 N Main St",
	                "addressLocality": "Portchester",
	                "addressRegion": "NY",
	                "postalCode": "10573",
	                "addressCountry": "US" 
	            } 
	        },
	        "duns": "54316",
	        "founder": "Mr. Big",
	        "foundingDate": "2011-11-01",
	        "foundingLocation": "Redding, CT, USA" 
	    },
	    "creator": {
	        "@type": "Corporation",
	        "name": "nystudio107",
	        "alternateName": "NY Studio 107",
	        "description": "We do consulting, branding, design, and development.  Impeccable design married with precision engineering.",
	        "url": "http://nystudio107.com",
	        "image": "http://nystudio107.dev/img/site/logo.png",
	        "telephone": "585.555.1212",
	        "email": "&#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;",
	        "address": {
	            "@type": "PostalAddress",
	            "addressLocality": "Webster",
	            "addressRegion": "NY",
	            "postalCode": "14580",
	            "addressCountry": "US" 
	        },
	        "logo": "http://nystudio107.dev/img/site/logo.png",
	        "location": {
	            "@type": "Place",
	            "name": "nystudio107",
	            "alternateName": "NY Studio 107",
	            "description": "We do consulting, branding, design, and development.  Impeccable design married with precision engineering.",
	            "telephone": "585.555.1212",
	            "url": "http://nystudio107.com",
	            "address": {
	                "@type": "PostalAddress",
	                "addressLocality": "Webster",
	                "addressRegion": "NY",
	                "postalCode": "14580",
	                "addressCountry": "US" 
	            } 
	        },
	        "founder": "Andrew &amp; Polly Welch",
	        "foundingDate": "2012-11-03" 
	    } 
	}
	</script>
					
If you click on the **Preview SEO Meta Tags** button when you are editing a SEO Template Meta, you'll see that particular template's SEO Template Meta tags.  Otherwise, you will see the SEO Site Meta tags.

## Testing Your SEO Meta

Use Google's [Structured Data Testing Tool](https://developers.google.com/structured-data/testing-tool/) to view your metadata/microdata as Google sees it, and validate it for accuracy.

Use Facebook's [Open Graph Debugger](https://developers.facebook.com/tools/debug) to validate and verify your Open Graph meta tags.

Use Twitter's [Twitter Card Validator](https://cards-dev.twitter.com/validator) to validate and verify your Twitter Cards.

## Roadmap

Some things to do, and ideas for potential features:

* [bug] Get the Template Metas implemented with full `locale` support, so the settings can all be per-locale based
* [bug] Enforce *required fields on the various settings pages in the Admin CP by doing proper validation
* [bug] The `foundingDate` fields probably should be dateTimeField types on the Settings pages
* [bug] Figure out a way to have SEOmatic FieldTypes "just work" when added to a new section with existing entries (no field data is saved at that point, so the defaults don't work)
* [bug] Support adding additional OpenGraph tags without the `og:` prefix (this will require retooling the core JSON-LD engine to add quotes around array keys)
* [feature] Add support for additional OpenGraph types (conspicuously, "Article")
* [feature] Add support for `og:image:type`, `og:image:width`, and `og:image:height`
* [feature] Add the ability to analyze a page for content vs. keywords for the SEO Template Metas, "just like Yoast"
* [feature] Change the preview to a live preview when editing things in SEOmatic
* [feature] Provide SiteMap functionality.  Yes, it's SEO-related, but seems like it might be better to keep SEOmatic focused (?)
* [feature] Provide Redirect functionality.  Yes, it's SEO-related, but seems like it might be better to keep SEOmatic focused (?)

## Changelog

### 1.1.4 -- 2016.02.19

* [Added] Set the default Twig escaping strategy for robots.txt and humans.txt to false (so the tag output is not escaped)
* [Fixed] Handle the case where there is no Twitter field
* [Fixed] Handle empty OpeningHours spec correctly, and other OpeningHours fixes
* [Fixed] actionRenderRobots is now publicly accessible (doh!), allowing `robots.txt` to render properly when not logged in
* [Fixed] Fixed a template error on the SEO Site Meta tab on localized sites
* [Improved] Updated the Roadmap section with pending bugs/features
* [Improved] Removed the siteRobotsTxt from the globals display
* [Improved] Updated the README.md

### 1.1.3 -- 2016.02.17

* [Fixed] Fixed some typos in SEOmatic & the docs re: `robots.txt`
* [Fixed] The Google Analytics script no longer renders if the Google Analytics Tracking ID field is empty
* [Fixed] Fixed an issue with console errors on the backend with the SEOmatic FieldType
* [Fixed] OpeningHours now only displays for LocalBusiness
* [Added] SEOmatic now processes adding the SEO Site Name to the `og` and `twitter` titles just before rendering, so you can do things like change the `seomaticSiteMeta.siteSeoTitlePlacement` via Twig, and it'll do the right thing
* [Fixed] Fixed a PHP error if the Site Creator is a Person
* [Improved] Updated the README.md

### 1.1.1 -- 2016.02.09

* [Added] SEOmatic Meta FieldTypes now work to automatically set meta when attached to `Categories` and also Craft Commerce `Products`
* [Improved] The Google Analytics script tag is not included during Live Preview anymore
* [Added] Added the ability to reference the fields of an SEOmatic FieldType via `entry.mySeomaticField.seoTitle` & `entry.mySeomaticField.seoDescription` & `entry.mySeomaticField.seoKeywords`
* [Added] There is now significantly more space available for the Entity Description and Creator Description
* [Fixed] Fixed an issue with PHP 5.3
* [Added] SEOmatic now handles `robots.txt`; it's configured on the SEO Site Meta tab
* [Improved] Updated the README.md


### 1.1.0 -- 2016.02.07

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

### 1.0.12 -- 2016.01.19

* [Improved] Performance improvement by not checking to see if a template exists before matching it
* [Improved] Keyword tags are now saved onblur
* [Improved] The Preview buttons in the SEOmatic FieldType are laid out better now
* [Improved] Updated the README.md

### 1.0.11 -- 2016.01.13

* [Added] You can now set Tags fields to be a Source for SEO FieldType Meta fields
* [Added] Added a meta referrer tag to the template (set to 'always')
* [Added] Added a http-equiv='Content-Type' content='text/html; charset=utf-8' meta tag
* [Fixed] If the SEO Title is empty, it no longer appear in the title
* [Fixed] Fixed an issue that would cause the seomatic* variables to not be properly sanitized
* [Improved] Updated the README.md

### 1.0.10 -- 2016.01.12

* [Added] You can now set Matrix blocks to be a Source for SEO FieldType Meta fields; it iterates through all text and rich text fields
* [Added] Added a extractTextFromMatrix() Twig filter/function/variable for templating use
* [Improved] The truncateStringOnWord() function does a much better job on comma-delimited keywords now
* [Fixed] Fixed a regression that caused the 'New Template Meta' button to be broken
* [Improved] Updated the README.md

### 1.0.9 -- 2016.01.11

* [Added] Added tokenized input fields for the keywords for the Site Meta, Template Meta, and Field Meta
* [Added] You can now specify the position of the SEO Site Title relative to the Title
* [Added] You can now specify the separator character that appears between the SEO Site Title and the Title
* [Fixed] Potentially fixed an issue with iconv when using the extractKeywords() or extractSummary() filters
* [Fixed] Fixed a regression that caused the Template Metas to stop working on the front-end
* [Improved] Updated the README.md

### 1.0.8 -- 2016.01.08

* [Improved] The rendering of the Identity and WebSite JSON-LD is now done via a tag in the templates, giving flexibility to people who want to use custom templates
* [Fixed] Fixed an issue with PHP < 5.4
* [Added] If the site is in devMode, `[devMode]` is prepended to the `<title>`
* [Fixed] Fixed an issue if a plugin (like A&M forms) renders a template with Twig code in the template name
* [Improved] Updated the README.md

### 1.0.7 -- 2016.01.01

* [Added] Added a 'robots' field globally to the SEO Meta for specifying noindex/nofollow
* [Fixed] Added error handling to the 'Look up Latitude/Longitude' buttons
* [Fixed] Some minor template / logic issues
* [Improved] Updated the README.md

### 1.0.6 -- 2015.12.31

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

### 1.0.5 -- 2015.12.28

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

### 1.0.4 -- 2015.12.22

* [Added] Added 'copyrightNotice', 'addressString', 'addressHtml', & 'mapUrl' to 'seomaticIdentity'
* [Added] Added 'copyrightNotice', 'addressString', 'addressHtml', & 'mapUrl' to 'seomaticCreator'
* [Added] Added 'hasMap' to the 'location' schema
* [Added] Added a 'Look up Latitude/Longitude' button to the Site Identity & Site Creator settings
* [Fixed] Fixed an issue with the releases.json; the 'Update' button should show up going forward for the plugin
* [Improved] The length of the 'seoSiteName' is now taken into account when truncating the 'seoTitle'
* [Improved] Updated the README.md

### 1.0.3 -- 2015.12.21

* [Fixed] Fixed an issue with the TextRank lib not being properly in the git repo, causing it to error when used
* [Fixed] The SEOmatic settings pages now have a SAVE button on them for Craft 2.4
* [Improved] Updated the README.md

### 1.0.2 -- 2015.12.20

* [Added] Exposed a few more utility functions via Twig filters & functions
* [Added] The genericOwnerEmail & genericCreatorEmail variables are ordinal-encoded, to obfuscate them
* [Added] Added 'location': 'Place' type to the Identity & Creator schemas, including GeoCoordinates
* [Fixed] Fixed the localization so SEOmatic works if your Admin CP is in a language other than English
* [Improved] Updated the README.md

### 1.0.1 -- 2015.12.19

* [Added] If the [Minify](https://github.com/nystudio107/minify) plugin is installed, SEOmatic will minify the SEO Meta tags & JSON-LD
* [Improved] Improved the caching mechanism to span all of the meta
* [Fixed] Fixed a few of small errors
* [Improved] Updated the README.md to better document SEOmatic

### 1.0.0 -- 2015.12.18

* Initial release

Brought to you by [nystudio107](http://nystudio107.com)