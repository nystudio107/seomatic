# SEOmatic plugin for Craft

A turnkey SEO implementation for Craft CMS that is comprehensive, powerful, and flexible.

## Installation

To install SEOmatic, follow these steps:

1. Download & unzip the file and place the `seomatic` directory into your `craft/plugins` directory
2.  -OR- do a `git clone https://github.com/khalwat/seomatic.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3. Install plugin in the Craft Control Panel under Settings > Plugins
4. The plugin folder should be named `seomatic` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

SEOmatic works on Craft 2.4.x and Craft 2.5.x.

## Overview

SEOmatic allows you to quickly get a website up and running with a robust, comprehensive SEO strategy.  It is also implemented in a Craft-y way, in that it is also flexible and customizable.

The general philosophy is that SEO Site Meta can be overridden by SEO Template Meta which can be overridden by dynamic SEO Twig tags.

In this way, the SEO Meta tags on your site cascade, so that they are globally available, but also can be customized in a very granular way.

SEOmatic populates your templates with SEO Meta in the same way that Craft populates your templates with `entry` variables, with a similar level of freedom and flexibility in terms of how you utilize them.

SEOmatic also caches each unique SEO Meta request so that your website performance is minimally impacted by the rich SEO Meta tags provided.

## Configuring SEOmatic

When you first install SEOmatic you'll see a welcome screen, click on the **Get Started** to, well, get started configuring SEOmatic.

All of the SEOmatic settings are fully localizable, so you can have SEO in as many languages as your website supports.  If any field is left blank for a setting in a particular locale, it will fall back on the primary locale.

### SEO Site Meta

These SEO Site Meta settings are used to globally define the Meta for the website.  When no SEO Template Meta is found for a webpage, these settings are used by default.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

If a no Template Meta exists for a template, the SEO Site Meta is used.

If any fields are left blank in a Template Meta, those fields are pulled from the SEO Site Meta.

You can also dynamically change any of these SEO Meta fields in your Twig templates, and they will appear in the rendered SEO Meta.

* **Site SEO Name** - This field is used wherever the name of the site is referenced, both at the trailing end of the `<title>` tag, and in other meta tags on the site. It is initially set to your Craft `{{ siteName }}`.
* **Site SEO Title** - This should be between 10 and 70 characters (spaces included). Make sure your title tag is explicit and contains your most important keywords. Be sure that each page has a unique title tag.
* **Site SEO Description** - This should be between 70 and 160 characters (spaces included). Meta descriptions allow you to influence how your web pages are described and displayed in search results. Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords.
* **Site SEO Keywords** - Google ignores this tag; though other search engines do look at it. Utilize it carefully, as improper or spammy use most likely will hurt you, or even have your site marked as spam. Avoid overstuffing the keywords and do not include keywords that are not related to the specific page you place them on.
* **Site SEO Image** - This is the image that will be used for display as the global website brand, as well as on Twitter Cards and Facebook OpenGraph that link to the website. It should be an image that displays well when cropped to a square format (for Twitter)
* **Site Owner** - The type of entity that owns this website.

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

### Site Identity

These Site Identity settings are used to globally define the identity and ownership of the website.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

The Site Owner type determines the JSON-LD schema that will be used to identity the website to search engines.

Leave any fields blank that aren't applicable or which you do not want as part of the SEO schema.

#### Site Ownership
* **Google Site Verification** - For the `<meta name='google-site-verification'>` tag. [Here's how to get it](https://www.google.com/webmasters/verification/).
* **Site Owner Entity Type** - The type of entity that owns this website.

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
* **Entity Country** - The country in which the entity that owns the website is located, e.g.: USA

#### Organization Info
* **Organization DUNS Number** - The DUNS (Dunn & Bradstreet) number of the organization/company/restaurant that owns the website
* **Organization Founder** - The name of the founder of the organization/company/restaurant
* **Organization Founding Date** - The date the organization/company/restaurant was founded
* **Organization Founding Location** - The location where the organization/company/restaurant was founded

#### Corporation Info
* **Corporation Ticker Symbol** - The exchange ticker symbol of the corporation

#### Restaurant Info
* **Restaurant Cuisine** - The primary type of cuisine that the restaurant serves

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
* **Google+ Handle** - Your Google+ page handle, without the preceding +
* **YouTube Handle** - Your YouTube handle (the part after `https://www.youtube.com/user/`)
* **Instagram Handle** - Your Instagram handle
* **Pinterest Handle** - Your Pinterest page handle (the part after `https://www.pinterest.com/`)

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

### Site Creator

These Site Creator settings are used to globally define & attribute the creator of the website.

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
* **Entity Country** - The country in which the entity that created the website is located, e.g.: USA

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

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

### SEO Template Meta

This list of Template Metas will initially be empty; click on the **New Template Meta** button to create one.

These SEO Meta settings are used to render the SEO Meta for your website. You can create any number of SEO Template Metas associated with your Twig templates on your website.

They are used in combination with the SEO Template Meta settings to generate [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview), [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph), and as well as HTML meta tags.

If a no Template Meta exists for a template, the SEO Site Meta is used.

If any fields are left blank in a Template Meta, those fields are pulled from the SEO Site Meta.

You can also dynamically change any of these SEO Meta fields in your Twig templates, and they will appear in the rendered SEO Meta.

* **Title** - The human readable title for this SEO Template Meta
* **Template Path** - Enter the path to the template to associate this meta with (just as you would on the Section settings). It will override the SEO Site Meta for this template. Leave any field blank if you want it to fall back on the default global settings for that field.
* **SEO Title** - This should be between 10 and 70 characters (spaces included). Make sure your title tag is explicit and contains your most important keywords. Be sure that each page has a unique title tag.
* **SEO Description** - This should be between 70 and 160 characters (spaces included). Meta descriptions allow you to influence how your web pages are described and displayed in search results. Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords.
* **SEO Keywords** - Google ignores this tag; though other search engines do look at it. Utilize it carefully, as improper or spammy use most likely will hurt you, or even have your site marked as spam. Avoid overstuffing the keywords and do not include keywords that are not related to the specific page you place them on.
* **SEO Image** - This is the image that will be used for display as the webpage brand for this template, as well as on Twitter Cards and Facebook OpenGraph that link to this page. It should be an image that displays well when cropped to a square format (for Twitter)

You can use any Craft `environmentVariables` in these fields in addition to static text, e.g.:

    This is my {baseUrl}

## Rendering your SEO Meta tags

All you need to do in order to output the SEOmatic SEO Meta tags is in the `<head>` tag of your main `layout.twig` (or whatever template all of your other template `extends`), place this tag:

    {% hook 'seomaticRender' %}

That's it.  It'll render all of that SEO goodness for you.

SEOmatic uses its own internal template for rendering; but you can provide it with one of your own as well, just use this Twig code instead:

    {% set seomaticTemplatePath = 'path/template' %} {% hook 'seomaticRender' %}

...and it'll use your custom template instead.

If the [Minify](https://github.com/khalwat/minify) plugin is installed, SEOmatic will minify the SEO Meta tags & JSON-LD.

## Dynamic Twig SEO Meta

All this SEO is great, but what if you want to generate dynamic SEO in an Twig template, for example on a Blog page where each blog entry should have different SEO Meta?  SEOmatic makes it easy.

SEOmatic populates your templates with the following global variables for SEO Meta:

    seomaticMeta.seoTitle
    seomaticMeta.seoDescription
    seomaticMeta.seoKeywords
    seomaticMeta.seoImage
    seomaticMeta.canonicalUrl

All of the variables are set by a combination of your SEO Site Meta settings, and the SEO Template Meta settings linked to the currently rendered template (if any).

By default, `seomaticMeta.canonicalUrl` is set to `craft.request.url`.

These work like any other Twig variables; you can output them by doing:

    {{ seomaticMeta.seoTitle }}

You can also change them all at once like this using the Twig [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax:

	{% set seomaticMeta = { 
	    seoTitle: 'Some Title',
	    seoDescription: entry.summary,
	    seoKeywords: 'Some,Key,Words',
	    seoImage: '',
	    canonicalUrl: entry.url,
	} %}

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
	    seoImage: entry.image,
	    canonicalUrl: seomaticMeta.canonicalUrl,
	} %}

And there you have it, dynamic keywords for your SEO Meta.  Note that we set the `canonicalUrl` to `seomaticMeta.canonicalUrl`, effectively leaving it unchanged.

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
	    seoImage: entry.image,
	    canonicalUrl: seomaticMeta.canonicalUrl,
	} %}

Note that we set the `canonicalUrl` to `seomaticMeta.canonicalUrl`, effectively leaving it unchanged.

## Utility Filters & Functions

SEOmatic exposes a few useful utility filters & functions that you can use... or not.

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

You can also change them all at once like this using the Twig [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax:

	{% set seomaticSiteMeta = { 
	    siteSeoName: 'Some Name',
	    siteSeoTitle: entry.title,
	    siteSeoDescription: entry.summary,
	    siteSeoKeywords: 'Some,Key,Words',
	    siteSeoImage: '',
	    siteOwnerType: 'company',
	} %}

Or if you want to set just one variable in the array, you can use the Twig function [merge](http://twig.sensiolabs.org/doc/filters/merge.html):

    {% set seomaticSiteMeta = seomaticSiteMeta | merge({'siteSeoTitle': entry.title }) %}

You can change these `seomaticSiteMeta` variables in your templates that `extends` your main `layout.twig` template, and due to the Twig rendering order, when `{% hook 'seomaticRender' %}` is called, they'll be populated in your rendered SEO Meta tags.

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

## SEOmatic Site Identity Twig Variables

SEOmatic populates your templates with the following global variables for Site Identity:

    seomaticIdentity.googleSiteVerification
    seomaticIdentity.siteOwnerType
    seomaticIdentity.genericOwnerName
    seomaticIdentity.genericOwnerAlternateName
    seomaticIdentity.genericOwnerDescription
    seomaticIdentity.genericOwnerUrl
    seomaticIdentity.genericOwnerTelephone
    seomaticIdentity.genericOwnerEmail
    seomaticIdentity.genericOwnerStreetAddress
    seomaticIdentity.genericOwnerAddressLocality
    seomaticIdentity.genericOwnerAddressRegion
    seomaticIdentity.genericOwnerPostalCode
    seomaticIdentity.genericOwnerAddressCountry
    seomaticIdentity.genericOwnerGeoLatitude
    seomaticIdentity.genericOwnerGeoLongitude
    seomaticIdentity.organizationOwnerDuns
    seomaticIdentity.organizationOwnerFounder
    seomaticIdentity.organizationOwnerFoundingDate
    seomaticIdentity.organizationOwnerFoundingLocation
    seomaticIdentity.personOwnerGender
    seomaticIdentity.personOwnerBirthPlace
    seomaticIdentity.corporationOwnerTickerSymbol
    seomaticIdentity.restaurantOwnerServesCuisine
    seomaticIdentity.genericOwnerImage
    
All of the variables are from your Site Identity settings, and will be the same for every template rendered.

Mostly, you won't need to change them in your Twig templates, but it can be useful to reference or output them.  These work like any other Twig variables; you can output them by doing:

    {{ seomaticIdentity.genericOwnerName }}

You can also change them all at once like this using the Twig [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax:

	{% set seomaticIdentity = { 
	    googleSiteVerification: '',
	    siteOwnerType: 'corporation',
	    genericOwnerName: 'nystudio107',
	    genericOwnerAlternateName: '',
	    genericOwnerDescription: '',
	    genericOwnerUrl: '',
	    genericOwnerTelephone: '',
	    genericOwnerEmail: '',
	    genericOwnerStreetAddress: '',
	    genericOwnerAddressLocality: '',
	    genericOwnerAddressRegion: '',
	    genericOwnerPostalCode: '',
	    genericOwnerAddressCountry: '',
	    genericOwnerGeoLatitude: '',
	    genericOwnerGeoLongitude: '',
	    organizationOwnerDuns: '',
	    organizationOwnerFounder: '',
	    organizationOwnerFoundingDate: '',
	    organizationOwnerFoundingLocation: '',
	    personOwnerGender: '',
	    personOwnerBirthPlace: '',
	    corporationOwnerTickerSymbol: '',
	    restaurantOwnerServesCuisine: '',
	    genericOwnerImage: {},
	} %}
	
Or if you want to set just one variable in the array, you can use the Twig function [merge](http://twig.sensiolabs.org/doc/filters/merge.html):

    {% set seomaticIdentity = seomaticIdentity | merge({'genericOwnerAlternateName': entry.title }) %}

You can change these `seomaticIdentity` variables in your templates that `extends` your main `layout.twig` template, and due to the Twig rendering order, when `{% hook 'seomaticRender' %}` is called, they'll be populated in your rendered SEO Meta tags.

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

The `genericOwnerEmail` variable is ordinal-encoded to obfuscate it.  For instance, `info@nystudio107.com` becomes:
    
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
    seomaticSocial.twitterUrl
    seomaticSocial.facebookUrl
    seomaticSocial.googlePlusUrl
    seomaticSocial.linkedInUrl
    seomaticSocial.youtubeUrl
    seomaticSocial.instagramUrl
    seomaticSocial.pinterestUrl
    
All of the variables are from your Social Media settings, and will be the same for every template rendered.  The `seomaticSocial.twitterUrl`, `seomaticSocial.facebookUrl`, `seomaticSocial.googlePlusUrl`, `seomaticSocial.linkedInUrl`, `seomaticSocial.youtubeUrl`, `seomaticSocial.instagramUrl`, and `seomaticSocial.pinterestUrl` variables are generated for you automatially for the respective services, and are links to your website's social media account pages.

Mostly, you won't need to change them in your Twig templates, but it can be useful to reference or output them.  These work like any other Twig variables; you can output them by doing:

    {{ seomaticSocial.twitterUrl }}

You can also change them all at once like this using the Twig [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax:

	{% set seomaticSocial = { 
	    twitterHandle: 'nystudio107',
	    facebookHandle: '',
	    facebookProfileId: '',
	    linkedInHandle: 'nystudio107',
	    googlePlusHandle: 'nystudio107',
	    googleSiteVerification: '',
	    twitterUrl: 'https://twitter.com/nystudio107',
	    facebookUrl: '',
	    googlePlusUrl: 'https://plus.google.com/+nystudio107',
	    linkedInUrl: 'https://www.linkedin.com/company/nystudio107',
	    youtubeUrl: '',
	    instagramUrl: '',
	    pinterestUrl: '',
	} %}
	
Or if you want to set just one variable in the array, you can use the Twig function [merge](http://twig.sensiolabs.org/doc/filters/merge.html):

    {% set seomaticSocial = seomaticSocial | merge({'twitterUrl': entry.twitterLink }) %}

You can change these `seomaticSocial` variables in your templates that `extends` your main `layout.twig` template, and due to the Twig rendering order, when `{% hook 'seomaticRender' %}` is called, they'll be populated in your rendered SEO Meta tags.

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

## SEOmatic Site Creator Twig Variables

SEOmatic populates your templates with the following global variables for Site Creator:

    seomaticCreator.siteCreatorType
    seomaticCreator.genericCreatorName
    seomaticCreator.genericCreatorAlternateName
    seomaticCreator.genericCreatorDescription
    seomaticCreator.genericCreatorUrl
    seomaticCreator.genericCreatorTelephone
    seomaticCreator.genericCreatorEmail
    seomaticCreator.genericCreatorStreetAddress
    seomaticCreator.genericCreatorAddressLocality
    seomaticCreator.genericCreatorAddressRegion
    seomaticCreator.genericCreatorPostalCode
    seomaticCreator.genericCreatorAddressCountry
    seomaticCreator.genericCreatorGeoLatitude
    seomaticCreator.genericCreatorGeoLongitude
    seomaticCreator.organizationCreatorDuns
    seomaticCreator.organizationCreatorFounder
    seomaticCreator.organizationCreatorFoundingDate
    seomaticCreator.organizationCreatorFoundingLocation
    seomaticCreator.personCreatorGender
    seomaticCreator.personCreatorBirthPlace
    seomaticCreator.corporationCreatorTickerSymbol
    seomaticCreator.genericCreatorImage
    
All of the variables are from your Site Creator settings, and will be the same for every template rendered.

Mostly, you won't need to change them in your Twig templates, but it can be useful to reference or output them.  These work like any other Twig variables; you can output them by doing:

    {{ seomaticCreator.genericCreatorName }}

You can also change them all at once like this using the Twig [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax:

	{% set seomaticCreator = { 
	    googleSiteVerification: '',
	    siteCreatorType: 'corporation',
	    genericCreatorName: 'nystudio107',
	    genericCreatorAlternateName: '',
	    genericCreatorDescription: '',
	    genericCreatorUrl: '',
	    genericCreatorTelephone: '',
	    genericCreatorEmail: '',
	    genericCreatorStreetAddress: '',
	    genericCreatorAddressLocality: '',
	    genericCreatorAddressRegion: '',
	    genericCreatorPostalCode: '',
	    genericCreatorAddressCountry: '',
	    genericCreatorGeoLatitude: '',
	    genericCreatorGeoLongitude: '',
	    organizationCreatorDuns: '',
	    organizationCreatorFounder: '',
	    organizationCreatorFoundingDate: '',
	    organizationCreatorFoundingLocation: '',
	    personCreatorGender: '',
	    personCreatorBirthPlace: '',
	    corporationCreatorTickerSymbol: '',
	    genericCreatorImage: {},
	} %}
	
Or if you want to set just one variable in the array, you can use the Twig function [merge](http://twig.sensiolabs.org/doc/filters/merge.html):

    {% set seomaticCreator = seomaticCreator | merge({'genericCreatorAlternateName': entry.title }) %}

You can change these `seomaticCreator` variables in your templates that `extends` your main `layout.twig` template, and due to the Twig rendering order, when `{% hook 'seomaticRender' %}` is called, they'll be populated in your rendered SEO Meta tags.

SEOmatic also automatically strips HTML/PHP tags from the variables, and translates HTML entities to ensure that they are properly encoded.

The `genericCreatorEmail` variable is ordinal-encoded to obfuscate it.  For instance, `info@nystudio107.com` becomes:

    &#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;

## Previewing your SEO Meta

There's a lot going on here, so to make it all more easily understood, SEOmatic offers two ways to preview your SEO Meta:

### Preview SEO Meta Display

Clicking on the **Preview SEO Meta Display** button will show you a preview of what the rendered SEO Meta will look like to various services that scrape your SEO Meta tags, such as Google, Twitter, and Facebook.

This serves as a nice sanity check for you, and a very nice way to show clients the amazing job you did on their SEO strategy.

If you click on the **Preview SEO Meta Display** button when you are editing a SEO Template Meta, you'll see the result of that particular template's SEO Template Meta tags.  Otherwise, you will see the SEO Site Meta tags.

### Preview SEO Meta Tags

Clicking on the **Preview SEO Meta Tags** button will show you Twig/HTML output of the following things:

#### Meta Template Variables

These are the Twig variables that SEOmatic pre-populates, and makes available to you in your templates. They are used when rendering the SEO Meta, so you can manipulate them however you want before rendering your SEO Meta. For example, you might change the seoDescription to be the summary field of an entry.

	{% set seomaticMeta = { 
	    seoTitle: 'This is the default global title of the site pages.',
	    seoDescription: 'This is the default global natural language description of the content on the site pages.',
	    seoKeywords: 'This is the default global list of comma-separated key words that are relevant to the content on the site pages.',
	    seoImage: {nys seo logo},
	    canonicalUrl: 'http://nystudio107.dev/',
	} %}
	
	{% set seomaticSiteMeta = { 
	    siteSeoName: 'nystudio107',
	    siteSeoTitle: 'This is the default global title of the site pages.',
	    siteSeoDescription: 'This is the default global natural language description of the content on the site pages.',
	    siteSeoKeywords: 'This is the default global list of comma-separated key words that are relevant to the content on the site pages.',
	    siteSeoImage: {nys seo logo},
	} %}
	
	{% set seomaticSocial = { 
	    twitterHandle: 'nystudio107',
	    facebookHandle: 'nystudio107',
	    facebookProfileId: '123456',
	    linkedInHandle: 'nystudio107',
	    googlePlusHandle: 'nystudio107',
	    youtubeHandle: 'nystudio107',
	    instagramHandle: 'nystudio107',
	    pinterestHandle: 'nystudio107',
	    twitterUrl: 'https://twitter.com/nystudio107',
	    facebookUrl: 'https://www.facebook.com/nystudio107',
	    googlePlusUrl: 'https://plus.google.com/+nystudio107',
	    linkedInUrl: 'https://www.linkedin.com/company/nystudio107',
	    youtubeUrl: 'https://www.youtube.com/user/nystudio107',
	    instagramUrl: 'https://www.instagram.com/nystudio107',
	    pinterestUrl: 'https://www.pinterest.com/nystudio107',
	} %}
	
	{% set seomaticIdentity = { 
	    googleSiteVerification: '12456',
	    siteOwnerType: 'corporation',
	    genericOwnerName: 'NY Studio 107',
	    genericOwnerAlternateName: 'nystudio107',
	    genericOwnerDescription: 'Impeccable design married with precision craftsmanship.',
	    genericOwnerUrl: 'http://nystudio107.com',
	    genericOwnerTelephone: '585-555-1212',
	    genericOwnerEmail: '&#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;',
	    genericOwnerStreetAddress: '123 Main Street',
	    genericOwnerAddressLocality: 'Portchester',
	    genericOwnerAddressRegion: 'NY',
	    genericOwnerPostalCode: '14580',
	    genericOwnerAddressCountry: 'USA',
	    genericOwnerGeoLatitude: '-120.5436367',
	    genericOwnerGeoLongitude: '80.6033588',
	    organizationOwnerDuns: '',
	    organizationOwnerFounder: 'Andrew Welch',
	    organizationOwnerFoundingDate: '10/1/2011',
	    organizationOwnerFoundingLocation: 'Webster, NY, USA',
	    personOwnerGender: '',
	    personOwnerBirthPlace: '',
	    corporationOwnerTickerSymbol: '',
	    restaurantOwnerServesCuisine: '',
	    genericOwnerImage: {nys logo@2x},
	} %}
	
	{% set seomaticCreator = { 
	    googleSiteVerification: '12456',
	    siteCreatorType: 'corporation',
	    genericCreatorName: 'NY Studio 107',
	    genericCreatorAlternateName: 'nystudio107',
	    genericCreatorDescription: 'Impeccable design married with precision craftsmanship.',
	    genericCreatorUrl: 'http://nystudio107.com',
	    genericCreatorTelephone: '585.555.1212',
	    genericCreatorEmail: '&#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;',
	    genericCreatorStreetAddress: '575 Dunfrey Road',
	    genericCreatorAddressLocality: 'Lansing',
	    genericCreatorAddressRegion: 'MI',
	    genericCreatorPostalCode: '11360',
	    genericCreatorAddressCountry: 'USA',
	    genericCreatorGeoLatitude: '-120.5436367',
	    genericCreatorGeoLongitude: '80.6033588',
	    organizationCreatorDuns: '',
	    organizationCreatorFounder: 'Andrew Welch',
	    organizationCreatorFoundingDate: '10/1/2011',
	    organizationCreatorFoundingLocation: 'Webster, NY',
	    personCreatorGender: '',
	    personCreatorBirthPlace: '',
	    corporationCreatorTickerSymbol: '',
	    genericCreatorImage: {nys logo@2x},
	} %}
	
	{% set seomaticTemplatePath = '' %}
	
You can treat all of these like regular Twig variables; for instance, `{{ seomaticSocial.twitterUrl }}` will output the URL to the website's Twitter page. You can change these variables using the Twig array [set](http://twig.sensiolabs.org/doc/tags/set.html) syntax, or using the Twig function [merge](http://twig.sensiolabs.org/doc/filters/merge.html). Any changes you make will be reflected in the SEO Meta rendered with `{% hook 'seomaticRender' %}` on your page.

#### Rendered SEO Meta

The `{% hook 'seomaticRender' %}` tag generates these SEO Meta for you, based on the Meta Template Variables (above). By default, it uses an internal template, but you can pass your own template to be used instead, like this: `{% set seomaticTemplatePath = 'path/template' %} {% hook 'seomaticRender' %}`

SEOmatic cascades Meta settings; if you have a Meta associated with the current template, it uses that. Otherwise it falls back on the SEO Site Meta settings. If a field is empty for a Template Meta, it falls back on the SEO Site Meta setting for that field.

	<!-- BEGIN SEOmatic rendered SEO Meta -->
	
	<title>This is the default global title of the site pages. | nystudio107</title> <!-- {{ seomaticMeta.seoTitle }} | {{ seomaticSiteMeta.siteSeoName }} -->
	
	<!-- Standard SEO -->
	
	<meta name="keywords" content="This is the default global list of comma-separated key words that are relevant to the content on the site pages." /> <!-- {{ seomaticMeta.seoKeywords }} -->
	<meta name="description" content="This is the default global natural language description of the content on the site pages." /> <!-- {{ seomaticMeta.seoDescription }} -->
	<link rel="canonical" href="http://nystudio107.dev/" /> <!-- {{ seomaticMeta.canonicalUrl }} (defaults to craft.request.url) -->
	<meta name="geo.region" content="NY" /> <!-- {{ seomaticIdentity.genericOwnerAddressRegion }} -->
	<meta name="geo.position" content="-120.5436367,80.6033588" /> <!-- {{ seomaticIdentity.genericOwnerGeoLatitude }},{{ seomaticIdentity.genericOwnerGeoLongitude }} -->
	<meta name="ICBM" content="-120.5436367,80.6033588" /> <!-- {{ seomaticIdentity.genericOwnerGeoLatitude }},{{ seomaticIdentity.genericOwnerGeoLongitude }} -->
	<meta name="geo.placename" content="NY Studio 107" /> <!-- {{ seomaticIdentity.genericOwnerName }} -->
	
	<!-- Dublin Core basic info -->
	
	<meta name="dcterms.Identifier" content="http://nystudio107.dev/" /> <!-- {{ seomaticMeta.canonicalUrl }} (defaults to craft.request.url) -->
	<meta name="dcterms.Format" content="text/html" /> <!-- text/html -->
	<meta name="dcterms.Relation" content="nystudio107" /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta name="dcterms.Language" content="en" /> <!-- {{ craft.locale }} -->
	<meta name="dcterms.Publisher" content="nystudio107" /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta name="dcterms.Type" content="text/html" /> <!-- text/html -->
	<meta name="dcterms.Coverage" content="http://nystudio107.dev/" /> <!-- {{ siteUrl }} -->
	<meta name="dcterms.Rights" content="Copyright &copy;2015, NY Studio 107. All rights reserved." /> <!-- Copyright &copy;{{ now | date('Y') }}, {{ seomaticIdentity.genericOwnerName }}. All rights reserved. -->
	<meta name="dcterms.Title" content="This is the default global title of the site pages." /> <!-- {{ seomaticMeta.seoTitle }} -->
	<meta name="dcterms.Creator" content="nystudio107" /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta name="dcterms.Subject" content="This is the default global list of comma-separated key words that are relevant to the content on the site pages." /> <!-- {{ seomaticMeta.seoKeywords }} -->
	<meta name="dcterms.Contributor" content="nystudio107" /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta name="dcterms.Date" content="2015-12-18" /> <!-- {{ now | date('Y-m-d') }} -->
	<meta name="dcterms.Description" content="This is the default global natural language description of the content on the site pages." /> <!-- {{ seomaticMeta.seoDescription }} -->
	
	<!-- Facebook OpenGraph -->
	
	<meta property="fb:profile_id" content="123456" /> <!-- {{ seomaticSocial.facebookProfileId }} -->
	<meta property="og:type" content="website" /> <!-- website -->
	<meta property="og:url" content="http://nystudio107.dev/" /> <!-- {{ seomaticMeta.canonicalUrl }} (defaults to craft.request.url) -->
	<meta property="og:title" content="This is the default global title of the site pages. | nystudio107" /> <!-- {{ seomaticMeta.seoTitle }} | {{ seomaticSiteMeta.siteSeoName }} -->
	<meta property="og:image" content="http://NYStudio107.com/img/site/nys_seo_logo.png" /> <!-- {{ seomaticMeta.seoImage.url }} -->
	<meta property="og:site_name" content="nystudio107" /> <!-- {{ seomaticSiteMeta.siteSeoName }} -->
	<meta property="og:description" content="This is the default global natural language description of the content on the site pages." /> <!-- {{ seomaticMeta.seoDescription }} -->
	<meta property="og:locale" content="en" /> <!-- {{ craft.locale }} -->
	<meta property="og:see_also" content="http://nystudio107.dev/" /> <!-- {{ siteUrl }} -->
	<meta property="og:see_also" content="https://www.facebook.com/nystudio107" /> <!-- {{ seomaticSocial.facebookUrl }} -->
	<meta property="og:see_also" content="https://twitter.com/nystudio107" /> <!-- {{ seomaticSocial.twitterUrl }} -->
	<meta property="og:see_also" content="https://plus.google.com/+nystudio107" /> <!-- {{ seomaticSocial.googlePlusUrl }} -->
	<meta property="og:see_also" content="https://www.linkedin.com/company/nystudio107" /> <!-- {{ seomaticSocial.linkedInUrl }} -->
	<meta property="og:see_also" content="https://www.youtube.com/user/nystudio107" /> <!-- {{ seomaticSocial.youtubeUrl }} -->
	<meta property="og:see_also" content="https://www.instagram.com/nystudio107" /> <!-- {{ seomaticSocial.instagramUrl }} -->
	<meta property="og:see_also" content="https://www.pinterest.com/nystudio107" /> <!-- {{ seomaticSocial.pinterestUrl }} -->
	
	<!-- Twitter Card -->
	
	<meta property="twitter:card" content="summary" /> <!-- summary -->
	<meta property="twitter:site" content="@nystudio107" /> <!-- @{{ seomaticSocial.twitterHandle }} -->
	<meta property="twitter:title" content="This is the default global title of the site pages. | nystudio107" /> <!-- {{ seomaticMeta.seoTitle }} | {{ seomaticSiteMeta.siteSeoName }} -->
	<meta property="twitter:description" content="This is the default global natural language description of the content on the site pages." /> <!-- {{ seomaticMeta.seoDescription }} -->
	<meta property="twitter:image" content="http://NYStudio107.com/img/site/nys_seo_logo.png" /> <!-- {{ seomaticMeta.seoImage.url }} -->
	<meta property="twitter:url" content="http://nystudio107.dev/" /> <!-- {{ siteUrl }} -->
	
	<!-- Google Publisher/Authorship -->
	
	<link rel="publisher" href="https://plus.google.com/+nystudio107" /> <!-- {{ seomaticSocial.googlePlusUrl }} -->
	<link rel="author" href="https://plus.google.com/+nystudio107" /> <!-- {{ seomaticSocial.googlePlusUrl }} -->
	
	<!-- Domain verification -->
	
	<meta name="google-site-verification" content="12456" /> <!-- {{ seomaticIdentity.googleSiteVerification }} -->
	
	<!-- END SEOmatic rendered SEO Meta -->
	
#### Rendered Identity Microdata

The `{% hook 'seomaticRender' %}` tag also generates [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) identity microdata.

	<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "Corporation",
		"name": "NY Studio 107",
		"alternateName": "nystudio107",
		"description": "Impeccable design married with precision craftsmanship.",
		"url": "http://nystudio107.com",
		"image": "http://nystudio107.dev/img/site/nys_logo@2x.png",
		"telephone": "585-555-1212",
		"email": "&#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;",
		"logo": "http://nystudio107.dev/img/site/nys_logo@2x.png",
		"location": {
			"@type": "Place",
			"name": "NY Studio 107",
			"alternateName": "nystudio107",
			"description": "Impeccable design married with precision craftsmanship.",
			"geo": {
				"@type": "GeoCoordinates",
				"latitude": "-120.5436367",
				"longitude": "80.6033588"
			},
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "123 Main Street",
				"addressLocality": "Portchester",
				"addressRegion": "NY",
				"postalCode": "14580",
				"addressCountry": "USA"
			}
		},
		"duns": "3456",
		"founder": "Andrew Welch",
		"foundingDate": "10/1/2011",
		"foundingLocation": "Webster, NY, USA",
		"address": {
			"@type": "PostalAddress",
			"streetAddress": "123 Main Street",
			"addressLocality": "Portchester",
			"addressRegion": "NY",
			"postalCode": "14580",
			"addressCountry": "USA"
		}
	}
	</script>
		
#### Rendered WebSite Microdata

The `{% hook 'seomaticRender' %}` tag also generates [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) WebSite microdata.

	<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "WebSite",
		"name": "nystudio107",
		"description": "This is the default global natural language description of the content on the site pages.",
		"url": "http://nystudio107.dev/",
		"image": "http://nystudio107.dev/img/site/nys_seo_logo.png",
		"sameAs": ["https://www.facebook.com/nystudio107","https://twitter.com/nystudio107","https://plus.google.com/+nystudio107","https://www.linkedin.com/company/nystudio107","https://www.youtube.com/user/nystudio107","https://www.instagram.com/nystudio107","https://www.pinterest.com/nystudio107","http://nystudio107.dev/"],
		
		"copyrightHolder": {
			"@type": "Corporation",
			"name": "NY Studio 107",
			"alternateName": "nystudio107",
			"description": "Impeccable design married with precision craftsmanship.",
			"url": "http://nystudio107.com",
			"image": "http://nystudio107.dev/img/site/nys_logo@2x.png",
			"telephone": "585-555-1212",
			"email": "&#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;",
			"logo": "http://nystudio107.dev/img/site/nys_logo@2x.png",
			"location": {
				"@type": "Place",
				"name": "NY Studio 107",
				"alternateName": "nystudio107",
				"description": "Impeccable design married with precision craftsmanship.",
				"geo": {
					"@type": "GeoCoordinates",
					"latitude": "-120.5436367",
					"longitude": "80.6033588"
				},
				"address": {
					"@type": "PostalAddress",
					"streetAddress": "123 Main Street",
					"addressLocality": "Portchester",
					"addressRegion": "NY",
					"postalCode": "14580",
					"addressCountry": "USA"
				}
			},
			"duns": "3456",
			"founder": "Andrew Welch",
			"foundingDate": "10/1/2011",
			"foundingLocation": "Webster, NY, USA",
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "123 Main Street",
				"addressLocality": "Portchester",
				"addressRegion": "NY",
				"postalCode": "14580",
				"addressCountry": "USA"
			}
		},
		"author": {
		"@type": "Corporation",
			"name": "NY Studio 107",
			"alternateName": "nystudio107",
			"description": "Impeccable design married with precision craftsmanship.",
			"url": "http://nystudio107.com",
			"image": "http://nystudio107.dev/img/site/nys_logo@2x.png",
			"telephone": "585-555-1212",
			"email": "&#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;",
			"logo": "http://nystudio107.dev/img/site/nys_logo@2x.png",
			"location": {
				"@type": "Place",
				"name": "NY Studio 107",
				"alternateName": "nystudio107",
				"description": "Impeccable design married with precision craftsmanship.",
				"geo": {
					"@type": "GeoCoordinates",
					"latitude": "-120.5436367",
					"longitude": "80.6033588"
				},
				"address": {
					"@type": "PostalAddress",
					"streetAddress": "123 Main Street",
					"addressLocality": "Portchester",
					"addressRegion": "NY",
					"postalCode": "14580",
					"addressCountry": "USA"
				}
			},
			"duns": "3456",
			"founder": "Andrew Welch",
			"foundingDate": "10/1/2011",
			"foundingLocation": "Webster, NY, USA",
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "123 Main Street",
				"addressLocality": "Portchester",
				"addressRegion": "NY",
				"postalCode": "14580",
				"addressCountry": "USA"
			}
		},
		"creator": {
			"@type": "Corporation",
			"name": "NY Studio 107",
			"alternateName": "nystudio107",
			"description": "Impeccable design married with precision craftsmanship.",
			"url": "http://nystudio107.com",
			"image": "http://nystudio107.dev/img/site/nys_logo@2x.png",
			"telephone": "585.555.1212",
			"email": "&#105;&#110;&#102;&#111;&#64;&#110;&#121;&#115;&#116;&#117;&#100;&#105;&#111;&#49;&#48;&#55;&#46;&#99;&#111;&#109;",
			"logo": "http://nystudio107.dev/img/site/nys_logo@2x.png",
			"location": {
				"@type": "Place",
				"name": "NY Studio 107",
				"alternateName": "nystudio107",
				"description": "Impeccable design married with precision craftsmanship.",
				"geo": {
					"@type": "GeoCoordinates",
					"latitude": "-120.5436367",
					"longitude": "80.6033588"
				},
				"address": {
					"@type": "PostalAddress",
					"streetAddress": "575 Dunfrey Road",
					"addressLocality": "Lansing",
					"addressRegion": "MI",
					"postalCode": "11360",
					"addressCountry": "USA"
				}
			},
			"founder": "Andrew Welch",
			"foundingDate": "10/1/2011",
			"foundingLocation": "Webster, NY",
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "575 Dunfrey Road",
				"addressLocality": "Lansing",
				"addressRegion": "MI",
				"postalCode": "11360",
				"addressCountry": "USA"
			}
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
* [feature] Helper functions for GetFullAddress and GetCopyrightString (?)
* [feature] Change the preview to a live preview when editing things in SEOmatic
* [feature] Provide SiteMap functionality.  Yes, it's SEO-related, but seems like it might be better to keep SEOmatic focused (?)
* [feature] Provide Redirect functionality.  Yes, it's SEO-related, but seems like it might be better to keep SEOmatic focused (?)
* [feature] Add a "Lookup Geo Coordinates" button to the Site Identity and Site Creator pages.

## Changelog

### 1.0.3 -- 2015.12.21

* [Fixed] Fixed an issue with the TextRank lib not being properly in the git repo, causing it to error when used
* [Improved] Updated the README.md

### 1.0.2 -- 2015.12.20

* [Added] Exposed a few more utility functions via Twig filters & functions
* [Added] The genericOwnerEmail & genericCreatorEmail variables are ordinal-encoded, to obfuscate them
* [Added] Added 'location': 'Place' type to the Identity & Creator schemas, including GeoCoordinates
* [Fixed] Fixed the localization so SEOmatic works if your Admin CP is in a language other than English
* [Improved] Updated the README.md

### 1.0.1 -- 2015.12.19

* [Added] If the [Minify](https://github.com/khalwat/minify) plugin is installed, SEOmatic will minify the SEO Meta tags & JSON-LD
* [Improved] Improved the caching mechanism to span all of the meta
* [Fixed] Fixed a few of small errors
* [Improved] Updated the README.md to better document SEOmatic

### 1.0.0 -- 2015.12.18

* Initial release
