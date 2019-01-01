[![No Maintenance Intended](http://unmaintained.tech/badge.svg)](http://unmaintained.tech/)

# DEPRECATED

This Craft CMS 2.x plugin is no longer supported, but it is fully functional, and you may continue to use it as you see fit. The license also allows you to fork it and make changes as needed for legacy support reasons.

The Craft CMS 3.x version of this plugin can be found here: [craft-seomatic](https://github.com/nystudio107/craft-seomatic) and can also be installed via the Craft Plugin Store in the Craft CP.

# SEOmatic plugin for Craft

A turnkey SEO implementation for Craft CMS that is comprehensive, powerful, and flexible.

![Screenshot](resources/screenshots/seomatic01.png)

Related: [SEOmatic for Craft 3.x](https://github.com/nystudio107/craft-seomatic)

## Installation

To install SEOmatic, follow these steps:

1. Download & unzip the file and place the `seomatic` directory into your `craft/plugins` directory
2.  -OR- do a `git clone https://github.com/nystudio107/seomatic.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3.  -OR- install with Composer via `composer require nystudio107/seomatic`
4. Install plugin in the Craft Control Panel under Settings > Plugins
5. The plugin folder should be named `seomatic` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

SEOmatic works on Craft 2.4.x, Craft 2.5.x, and Craft 2.6.x.

The SEOmetrics feature requires PHP 5.4 or later and that you have the [php-xml extension](http://osticket.com/forum/discussion/8702/php-fatal-error-call-to-undefined-function-utf8-encode-error-message) installed.

## Overview

Learn more about SEO: [Modern SEO: Snake Oil vs. Substance](https://nystudio107.com/blog/modern-seo-snake-oil-vs-substance)

Learn more about JSON-LD Structured Data: [JSON-LD, Structured Data and Erotica](https://nystudio107.com/blog/json-ld-structured-data-and-erotica)

### Video overview of SEOmatic:

[![Video Overview of SEOmatic](https://img.youtube.com/vi/f1149YVEF_0/0.jpg)](https://www.youtube.com/watch?v=f1149YVEF_0)

SEOmatic allows you to quickly get a website up and running with a robust, comprehensive SEO strategy.  It is also implemented in a Craft-y way, in that it is also flexible and customizable.  The SEOmetrics feature scans your content for focus keywords, and offers analysis on how to improve your SEO.

It implements [JSON-LD](https://developers.google.com/schemas/formats/json-ld?hl=en) microdata, [Dublin Core](http://dublincore.org) core metadata, [Twitter Cards](https://dev.twitter.com/cards/overview) tags, [Facebook OpenGraph](https://developers.facebook.com/docs/sharing/opengraph) tags, [Humans.txt](http://humanstxt.org) authorship accreditation, and as well as HTML meta tags.

The general philosophy is that SEO Site Meta can be overridden by SEO Template Meta, which can be overridden by SEO Entry Meta, which can be overridden by dynamic SEO Twig tags.

In this way, the SEO Meta tags on your site cascade, so that they are globally available, but also can be customized in a very granular way.

SEOmatic populates your templates with SEO Meta in the same way that Craft populates your templates with `entry` variables, with a similar level of freedom and flexibility in terms of how you utilize them.

SEOmatic also caches each unique SEO Meta request so that your website performance is minimally impacted by the rich SEO Meta tags provided.

## Documentation

Please read the complete documentation in the [SEOmatic Wiki](https://github.com/nystudio107/seomatic/wiki) or read the `DOCS.md` file in the repo.

To better understand how all of this metadata benefits your website, please read: [Promote Your Content with Structured Data Markup](https://developers.google.com/structured-data/)

If you need to redirect from legacy URLs to preserve SEO value when rebuilding & restructuring a website, check out the [Retour Plugin](https://github.com/nystudio107/retour)

## Roadmap

Some things to do, and ideas for potential features:

* [bug] Get the Template Metas implemented with full `locale` support, so the settings can all be per-locale based
* [bug] Enforce *required fields on the various settings pages in the Admin CP by doing proper validation
* [bug] The `foundingDate` fields probably should be dateTimeField types on the Settings pages
* [feature] Provide SiteMap functionality.  Yes, it's SEO-related, but seems like it might be better to keep SEOmatic focused (?)
* [feature] Allow people to choose individual fields to pull from inside of Matrix and Neo blocks

Brought to you by [nystudio107](http://nystudio107.com)
