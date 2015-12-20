<?php

return array(

/* -- Help for the various panes */

    "siteMeta_help"								=> "<p>These SEO Site Meta settings are used to globally define the Meta for the website.  When no SEO Template Meta is found for a webpage, these settings are used by default.</p><p>They are used in combination with the SEO Template Meta settings to generate <a href='https://developers.google.com/schemas/formats/json-ld?hl=en' target='_blank'>JSON-LD</a> microdata, <a href='http://dublincore.org' target='_blank'>Dublin Core</a> core metadata, <a href='https://dev.twitter.com/cards/overview' target='_blank'>Twitter Cards</a>, <a href='https://developers.facebook.com/docs/sharing/opengraph' target='_blank'>Facebook OpenGraph</a>, and as well as HTML meta tags.<p>If a no Template Meta exists for a template, the SEO Site Meta is used.</p>  <p>If any fields are left blank in a Template Meta, those fields are pulled from the SEO Site Meta.</p><p>You can also dynamically change any of these SEO Meta fields in your Twig templates, and they will appear in the rendered SEO Meta.</p>",

    "siteIdentity_help"             			=> "<p>These Site Identity settings are used to globally define the identity and ownership of the website.</p><p>They are used in combination with the SEO Template Meta settings to generate <a href='https://developers.google.com/schemas/formats/json-ld?hl=en' target='_blank'>JSON-LD</a> microdata, <a href='http://dublincore.org' target='_blank'>Dublin Core</a> core metadata, <a href='https://dev.twitter.com/cards/overview' target='_blank'>Twitter Cards</a>, <a href='https://developers.facebook.com/docs/sharing/opengraph' target='_blank'>Facebook OpenGraph</a>, and as well as HTML meta tags.<p>The Site Owner type determines the JSON-LD schema that will be used to identity the website to search engines.</p><p>Leave any fields blank that aren't applicable or which you do not want as part of the SEO schema.</p>",
    
    "socialMedia_help"              			=> "<p>These Social Media settings are used to globally define the social media accounts associated with the website.</p><p>They are used in combination with the SEO Meta settings to generate <a href='https://developers.google.com/schemas/formats/json-ld?hl=en' target='_blank'>JSON-LD</a> microdata, <a href='http://dublincore.org' target='_blank'>Dublin Core</a> core metadata, <a href='https://dev.twitter.com/cards/overview' target='_blank'>Twitter Cards</a>, <a href='https://developers.facebook.com/docs/sharing/opengraph' target='_blank'>Facebook OpenGraph</a>, and as well as HTML meta tags.<p>None of these fields are mandatory; if you don't have a given social media account, just leave it blank.</p>",

    "siteCreator_help"             				=> "<p>These Site Creator settings are used to globally define & attribute the creator of the website.</p><p>They are used in combination with the SEO Template Meta settings to generate <a href='https://developers.google.com/schemas/formats/json-ld?hl=en' target='_blank'>JSON-LD</a> microdata, <a href='http://dublincore.org' target='_blank'>Dublin Core</a> core metadata, <a href='https://dev.twitter.com/cards/overview' target='_blank'>Twitter Cards</a>, <a href='https://developers.facebook.com/docs/sharing/opengraph' target='_blank'>Facebook OpenGraph</a>, and as well as HTML meta tags.<p>The Site Creator information is referenced in the Identity JSON-LD schema that is used to identity the website to search engines.</p><p>Leave any fields blank that aren't applicable or which you do not want as part of the SEO schema.</p>",

    "editMeta_help"                 			=> "<p>These SEO Meta settings are used to render the SEO Meta for the website.  You can create any number of SEO Template Metas associated with your Twig templates on the website.</p><p>They are used in combination with the Site Identity & Social Media settings to generate <a href='https://developers.google.com/schemas/formats/json-ld?hl=en' target='_blank'>JSON-LD</a> microdata, <a href='http://dublincore.org' target='_blank'>Dublin Core</a> core metadata, <a href='https://dev.twitter.com/cards/overview' target='_blank'>Twitter Cards</a>, <a href='https://developers.facebook.com/docs/sharing/opengraph' target='_blank'>Facebook OpenGraph</a>, and as well as HTML meta tags. <p>If a no Template Meta exists for a template, the SEO Site Meta is used.</p>  <p>If any fields are left blank in a Template Meta, those fields are pulled from the SEO Site Meta.</p><p>You can also dynamically change any of these SEO Meta fields in your Twig templates, and they will appear in the rendered SEO Meta.</p>",

	"environmentalVariables_ok"					=> "<p>You can use any Craft <code>environmentVariables</code> in these fields in addition to static text, e.g.:<br /> <code>This is my {baseUrl}</code></p>",
	
/* -- Field display titles & instructions in the Admin CP for SEO Site Meta */

    "siteSeoName"                   			=> "Site SEO Name",
    "siteSeoName_help"              			=> "This field is used wherever the name of the site is referenced, both at the trailing end of the <code>&lt;title&gt;</code> tag, and in other meta tags on the site.  It is initially set to your Craft <code>{{ siteName }}</code>.",

    'siteSeoTitle'                  			=> "Site SEO Title",
    'siteSeoTitle_help'             			=> "This should be between 10 and 70 characters (spaces included).  Make sure your title tag is explicit and contains your most important keywords. Be sure that each page has a unique title tag.",
    
    'siteSeoDescription'            			=> "Site SEO Description",
    'siteSeoDescription_help'       			=> "This should be between 70 and 160 characters (spaces included).  Meta descriptions allow you to influence how your web pages are described and displayed in search results.  Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords.",
    
    'siteSeoKeywords'               			=> "Site SEO Keywords",
    'siteSeoKeywords_help'          			=> "Google ignores this tag; though other search engines do look at it.  Utilize it carefully, as improper or spammy use most likely will hurt you, or even have your site marked as spam.  Avoid overstuffing the keywords and do not include keywords that are not related to the specific page you place them on.",
    
    'siteSeoImageId'                			=> "Site SEO Image",
    'siteSeoImageId_help'           			=> "This is the image that will be used for display as the global website brand, as well as on Twitter Cards and Facebook OpenGraph that link to the website.  It should be an image that displays well when cropped to a square format (for Twitter)",

/* -- Field display titles * instructions in the Admin CP for Site Identity */

    "googleSiteVerification"        			=> "Google Site Verification",
    "googleSiteVerification_help"   			=> "For the <code>&lt;meta name='google-site-verification'&gt;</code> tag. <a href='https://www.google.com/webmasters/verification/' target='_blank'>Here's how to get it.</a>",

    "siteOwnerType"                 			=> "Site Owner Entity Type",
    "siteOwnerType_help"            			=> "The type of entity that owns this website.",

    "genericOwnerName"							=> "Entity Name",
    "genericOwnerName_help"						=> "The name of the entity that owns the website",
    
    "genericOwnerAlternateName"					=> "Alternate Entity Name",
    "genericOwnerAlternateName_help"			=> "An alternate or nickname for the entity that owns the website",

    "genericOwnerDescription"					=> "Entity Description",
    "genericOwnerDescription_help"				=> "A description of the entity that owns the website",
    
    "genericOwnerUrl"							=> "Entity URL",
    "genericOwnerUrl_help"						=> "A URL for the entity that owns the website",

    "genericOwnerImageId"						=> "Entity Brand",
    "genericOwnerImageId_help"					=> "An image or logo that represents the entity that owns the website",
    
    "genericOwnerTelephone"						=> "Entity Telephone",
    "genericOwnerTelephone_help"				=> "The primary contact telephone number for the entity that owns the website",
    
    "genericOwnerEmail"							=> "Entity Email",
    "genericOwnerEmail_help"					=> "The primary contact email address for the entity that owns the website",

    "genericOwnerStreetAddress"					=> "Entity Street Address",
    "genericOwnerStreetAddress_help"			=> "The street address of the entity that owns the website, e.g.: 123 Main Street",

    "genericOwnerAddressLocality"				=> "Entity Locality",
    "genericOwnerAddressLocality_help"			=> "The locality of the entity that owns the website, e.g.: Portchester",
    
    "genericOwnerAddressRegion"					=> "Entity Region",
    "genericOwnerAddressRegion_help"			=> "The region of the entity that owns the website, e.g.: New York or NY",

    "genericOwnerPostalCode"					=> "Entity Postal Code",
    "genericOwnerPostalCode_help"				=> "The postal code of the entity that owns the website, e.g.: 14580",
    
    "genericOwnerAddressCountry"				=> "Entity Country",
    "genericOwnerAddressCountry_help"			=> "The country in which the entity that owns the website is located, e.g.: USA",
    
    "genericOwnerGeoLatitude"					=> "Entity Latitude",
    "genericOwnerGeoLatitude_help"				=> "The latitude of the location of the entity that owns the website, e.g.: -120.5436367",
    
    "genericOwnerGeoLongitude"					=> "Entity Longitude",
    "genericOwnerGeoLongitude_help"				=> "The longitude of the location of the entity that owns the website, e.g.: 80.6033588",

    "organizationOwnerDuns"						=> "Organization DUNS Number",
    "organizationOwnerDuns_help"				=> "The DUNS (Dunn & Bradstreet) number of the organization/company/restaurant that owns the website",

    "organizationOwnerFounder"					=> "Organization Founder",
    "organizationOwnerFounder_help"				=> "The name of the founder of the organization/company/restaurant",

    "organizationOwnerFoundingDate"				=> "Organization Founding Date",
    "organizationOwnerFoundingDate_help"		=> "The date the organization/company/restaurant was founded",

    "organizationOwnerFoundingLocation"			=> "Organization Founding Location",
    "organizationOwnerFoundingLocation_help"	=> "The location where the organization/company/restaurant was founded",

    "corporationOwnerTickerSymbol"				=> "Corporation Ticker Symbol",
    "corporationOwnerTickerSymbol_help"			=> "The exchange ticker symbol of the corporation",

    "restaurantOwnerServesCuisine"				=> "Restaurant Cuisine",
    "restaurantOwnerServesCuisine_help"			=> "The primary type of cuisine that the restaurant serves",

    "personOwnerGender"							=> "Person Gender",
    "personOwnerGender_help"					=> "The gender of the person",

    "personOwnerBirthPlace"						=> "Person Birth Place",
    "personOwnerBirthPlace_help"				=> "The place where the person was born",

/* -- Field display titles & instructions in the Admin CP for Creator */

    "siteCreatorType"                 			=> "Site Creator Entity Type",
    "siteCreatorType_help"            			=> "The type of entity that created this website.",

    "genericCreatorName"						=> "Entity Name",
    "genericCreatorName_help"					=> "The name of the entity that created the website",
    
    "genericCreatorAlternateName"				=> "Alternate Entity Name",
    "genericCreatorAlternateName_help"			=> "An alternate or nickname for the entity that created the website",

    "genericCreatorDescription"					=> "Entity Description",
    "genericCreatorDescription_help"			=> "A description of the entity that created the website",
    
    "genericCreatorUrl"							=> "Entity URL",
    "genericCreatorUrl_help"					=> "A URL for the entity that created the website",

    "genericCreatorImageId"						=> "Entity Brand",
    "genericCreatorImageId_help"				=> "An image or logo that represents the entity that created the website",
    
    "genericCreatorTelephone"					=> "Entity Telephone",
    "genericCreatorTelephone_help"				=> "The primary contact telephone number for the entity that created the website",
    
    "genericCreatorEmail"						=> "Entity Email",
    "genericCreatorEmail_help"					=> "The primary contact email address for the entity that created the website",

    "genericCreatorStreetAddress"				=> "Entity Street Address",
    "genericCreatorStreetAddress_help"			=> "The street address of the entity that created the website, e.g.: 575 Dunfrey Road",

    "genericCreatorAddressLocality"				=> "Entity Locality",
    "genericCreatorAddressLocality_help"		=> "The locality of the entity that created the website, e.g.: Lansing",
    
    "genericCreatorAddressRegion"				=> "Entity Region",
    "genericCreatorAddressRegion_help"			=> "The region of the entity that created the website, e.g.: Michigan or MI",

    "genericCreatorPostalCode"					=> "Entity Postal Code",
    "genericCreatorPostalCode_help"				=> "The postal code of the entity that created the website, e.g.: 11360",
    
    "genericCreatorAddressCountry"				=> "Entity Country",
    "genericCreatorAddressCountry_help"			=> "The country in which the entity that created the website is located, e.g.: USA",
    
    "genericCreatorGeoLatitude"					=> "Entity Latitude",
    "genericCreatorGeoLatitude_help"			=> "The latitude of the location of the entity that created the website, e.g.: -120.5436367",
    
    "genericCreatorGeoLongitude"				=> "Entity Longitude",
    "genericCreatorGeoLongitude_help"			=> "The longitude of the location of the entity that created the website, e.g.: 80.6033588",

    "organizationCreatorDuns"					=> "Organization DUNS Number",
    "organizationCreatorDuns_help"				=> "The DUNS (Dunn & Bradstreet) number of the organization/company/restaurant that created the website",

    "organizationCreatorFounder"				=> "Organization Founder",
    "organizationCreatorFounder_help"			=> "The name of the founder of the organization/company/restaurant",

    "organizationCreatorFoundingDate"			=> "Organization Founding Date",
    "organizationCreatorFoundingDate_help"		=> "The date the organization/company/restaurant was founded",

    "organizationCreatorFoundingLocation"		=> "Organization Founding Location",
    "organizationCreatorFoundingLocation_help"	=> "The location where the organization/company/restaurant was founded",

    "corporationCreatorTickerSymbol"			=> "Corporation Ticker Symbol",
    "corporationCreatorTickerSymbol_help"		=> "The exchange ticker symbol of the corporation",

    "restaurantCreatorServesCuisine"			=> "Restaurant Cuisine",
    "restaurantCreatorServesCuisine_help"		=> "The primary type of cuisine that the restaurant serves",

    "personCreatorGender"						=> "Person Gender",
    "personCreatorGender_help"					=> "The gender of the person",

    "personCreatorBirthPlace"					=> "Person Birth Place",
    "personCreatorBirthPlace_help"				=> "The place where the person was born",

/* -- Field display titles & instructions in the Admin CP for Social */

    "twitterHandle"                 			=> "Twitter Handle",
    "twitterHandle_help"            			=> "Your Twitter Handle, without the preceding <code>@</code>",

    "facebookHandle"                			=> "Facebook Handle",
    "facebookHandle_help"           			=> "Your Facebook company/fan page handle (the part after <code>https://www.Facebook.com/</code>)",

    "facebookProfileId"            				=> "Facebook Profile ID",
    "facebookProfileId_help"        			=> "Your Facebook Profile/Page ID.  Click on the 'About' tab on your Facebook company/fan page, click on 'Page Info', then scroll to the bottom to find your 'Facebook Page ID'",

    "linkedInHandle"                			=> "LinkedIn Handle",
    "linkedInHandle_help"           			=> "Your LinkedIn page handle (the part after <code>https://www.linkedin.com/in/</code> or <code>https://www.linkedin.com/company/</code>)",

    "googlePlusHandle"              			=> "Google+ Handle",
    "googlePlusHandle_help"         			=> "Your Google+ page handle, without the preceding <code>+</code>",

    "youtubeHandle"								=> "YouTube Handle",
    "youtubeHandle_help"						=> "Your YouTube handle (the part after <code>https://www.youtube.com/user/</code>)",

    "instagramHandle"							=> "Instagram Handle",
    "instagramHandle_help"						=> "Your Instagram handle",

    "pinterestHandle"							=> "Pinterest Handle",
    "pinterestHandle_help"						=> "Your Pinterest page handle (the part after <code>https://www.pinterest.com/</code>)",

/* -- Field display titles & instructions in the Admin CP for meta templates */

    'metaPath'                      			=> "Template Path",
    'metaPath_help'                 			=> "Enter the path to the template to associate this meta with (just as you would on the Section settings).  It will override the SEO Site Meta for this template.  Leave any field blank if you want it to fall back on the default global settings for that field.",
    
    'seoTitle'                      			=> "SEO Title",
    'seoTitle_help'                 			=> "This should be between 10 and 70 characters (spaces included).  Make sure your title tag is explicit and contains your most important keywords. Be sure that each page has a unique title tag.",
    
    'seoDescription'                			=> "SEO Description",
    'seoDescription_help'           			=> "This should be between 70 and 160 characters (spaces included).  Meta descriptions allow you to influence how your web pages are described and displayed in search results.  Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords.",
    
    'seoKeywords'                   			=> "SEO Keywords",
    'seoKeywords_help'              			=> "Google ignores this tag; though other search engines do look at it.  Utilize it carefully, as improper or spammy use most likely will hurt you, or even have your site marked as spam.  Avoid overstuffing the keywords and do not include keywords that are not related to the specific page you place them on.",
    
    'seoImageId'                    			=> "SEO Image",
    'seoImageId_help'               			=> "This is the image that will be used for display as the webpage brand for this template, as well as on Twitter Cards and Facebook OpenGraph that link to this page.  It should be an image that displays well when cropped to a square format (for Twitter)",

/* -- Displayed for the SEO Meta Tags preview */

    'renderedFor'                   			=> "Rendered For",
    'renderedForGlobal'             			=> "<code>SEO Site</code> Meta.  This is what is generated for the global site meta, with no template meta overrides.",
    'renderedForTemplate'           			=> "<code>SEO Template</code> Meta.  This is what is generated when this template is displayed.",
    
    'templateVariables'             			=> "Meta Template Variables",
    'templateVariables_help'        			=> "These are the Twig variables that SEOmatic pre-populates, and makes available to you in your templates.  They are used when rendering the SEO Meta, so you can manipulate them however you want before rendering your SEO Meta.  For example, you might change the <code>seoDescription</code> to be the summary field of an entry.",
    'templateVariables2_help'        			=> "You can treat all of these like regular Twig variables; for instance, <code>{{ seomaticSocial.twitterUrl }}</code> will output the URL to the website's Twitter page.  You can change these variables using the Twig array <a href='http://twig.sensiolabs.org/doc/tags/set.html' target='_blank'>set</a> syntax, or using the Twig function <a href='http://twig.sensiolabs.org/doc/filters/merge.html' target='_blank'>merge</a>.  Any changes you make will be reflected in the SEO Meta rendered with <code>{% hook 'seomaticRender' %}</code> on your page.  The <code>genericOwnerEmail</code> and <code>genericCreatorEmail</code> variables are ordinal-encoded to obfuscate them.  See the documentation for details by clicking on the ? link below.",

    'renderedSeoMeta'               			=> "Rendered SEO Meta",
    'renderedSeoMeta_help'          			=> "The <code>{% hook 'seomaticRender' %}</code> tag generates these SEO Meta for you, based on the Meta Template Variables (above).  By default, it uses an internal template, but you can pass your own template to be used instead, like this: <code>{% set seomaticTemplatePath = 'path/template' %} {% hook 'seomaticRender' %}</code>",
    'renderedSeoMeta2_help'         			=> "SEOmatic cascades Meta settings; if you have a Meta associated with the current template, it uses that.  Otherwise it falls back on the SEO Site Meta settings.  If a field is empty for a Template Meta, it falls back on the SEO Site Meta setting for that field.",

    'renderedIdentity'              			=> "Rendered Identity Microdata",
    'renderedIdentity_help'         			=> "The <code>{% hook 'seomaticRender' %}</code> tag also generates <a href='https://developers.google.com/schemas/formats/json-ld?hl=en' target='_blank'>JSON-LD</a> identity microdata.",

    'renderedWebsite'               			=> "Rendered WebSite Microdata",
    'renderedWebsite_help'          			=> "The <code>{% hook 'seomaticRender' %}</code> tag also generates <a href='https://developers.google.com/schemas/formats/json-ld?hl=en' target='_blank'>JSON-LD</a> WebSite microdata.",

/* -- Displayed for the SEO Meta Tags preview */

    'googleDisplayPreview'              		=> "Google Search Result Display",
    'googleDisplayPreview_help'         		=> "This is a preview of how the Google search result will appear for this page, due to the SEO Meta tags generated by SEOmatic.",

    'twitterCardDisplayPreview'         		=> "Twitter Card Display",
    'twitterCardDisplayPreview_help'    		=> "If someone Tweets a link to this page, this is how the Twitter Card attached to their tweet (via the 'View summary' link) will appear, due to the Twitter Card tags generated by SEOmatic.",

    'facebookDisplayPreview'            		=> "FaceBook OpenGraph Display",
    'facebookDisplayPreview_help'   			=> "If someone posts a link to this page on Facebook, this is how the summary below their post will appear, due to the Facebook OpenGraph tags generated by SEOmatic.",

/* -- Misc strings */

	'localizing_help'							=> "If any field is left blank for a setting in a particular locale, it will fall back on the primary locale.",
    
);