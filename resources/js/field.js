/*
 * Functionality for Seomatic MetaField fieldtype
 * by Andrew Welch - http://nystudio107.com
 *
 * Depends on: jQuery
 */

 ;(function ( $, window, document, undefined ) {

    var pluginName = "SeomaticFieldType",
        defaults = {
        };

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            var seomatic = this,
                $field = $(this.element);

            function setPreviewFields() {
                var handle = $('#' + seomatic.options.prefix + seomatic.options.id + 'seoTitleSourceField').val();
                var text = seomatic.options.fieldData[handle];
                if (text == "")
                    text = "&nbsp;";
                $('#' + seomatic.options.prefix + seomatic.options.id + 'seoTitle-preview').html(text);

                var handle = $('#' + seomatic.options.prefix + seomatic.options.id + 'seoDescriptionSourceField').val();
                var text = seomatic.options.fieldData[handle];
                if (text == "")
                    text = "&nbsp;";
                $('#' + seomatic.options.prefix + seomatic.options.id + 'seoDescription-preview').html(text);

                var handle = $('#' + seomatic.options.prefix + seomatic.options.id + 'seoKeywordsSourceField').val();
                var text = seomatic.options.fieldData[handle];
                if (text == "")
                    text = "&nbsp;";
                $('#' + seomatic.options.prefix + seomatic.options.id + 'seoKeywords-preview').html(text);

                var handle = $('#' + seomatic.options.prefix + seomatic.options.id + 'seoImageIdSourceField').val();
                var url = seomatic.options.fieldImage[handle];
                if (url == "" || typeof url == 'undefined')
                    url = seomatic.options.missing_image;
                $('#' + seomatic.options.prefix + seomatic.options.id + 'seoImageIdSource-preview').attr('src', url);

                var handle = $('#' + seomatic.options.prefix + seomatic.options.id + 'seoTwitterImageIdSourceField').val();
                var url = seomatic.options.fieldImage[handle];
                if (url == "" || typeof url == 'undefined')
                    url = seomatic.options.missing_image;
                $('#' + seomatic.options.prefix + seomatic.options.id + 'seoTwitterImageIdSource-preview').attr('src', url);

                var handle = $('#' + seomatic.options.prefix + seomatic.options.id + 'seoFacebookImageIdSourceField').val();
                var url = seomatic.options.fieldImage[handle];
                if (url == "" || typeof url == 'undefined')
                    url = seomatic.options.missing_image;
                $('#' + seomatic.options.prefix + seomatic.options.id + 'seoFacebookImageIdSource-preview').attr('src', url);
                }

            $(function () {

                setPreviewFields();
                $('#' + seomatic.options.prefix + seomatic.options.id + 'seoKeywords').tokenfield({
                    createTokensOnBlur: true,
                    });

                $('#' + seomatic.options.prefix + 'preview-seometrics').on('click', function(e) {

                    // Prevents the default action to be triggered.
                    e.preventDefault();

                    // Triggering bPopup when click event is fired
                    $('#' + seomatic.options.prefix + 'preview-seometrics-popup').bPopup();

                });

                $('#' + seomatic.options.prefix + 'preview-tags').on('click', function(e) {

                    // Prevents the default action to be triggered.
                    e.preventDefault();

                    // Triggering bPopup when click event is fired
                    $('#' + seomatic.options.prefix + 'preview-tags-popup').bPopup();

                });

                $('#' + seomatic.options.prefix + 'preview-display').on('click', function(e) {

                    // Prevents the default action to be triggered.
                    e.preventDefault();

                    // Triggering bPopup when click event is fired
                    $('#' + seomatic.options.prefix + 'preview-display-popup').bPopup();

                });

/* -- Show/hide the select fields initially */

                $('.selectFieldWrapper > div > div > div > div > select').on('change', function(e) {
                    setPreviewFields();
                    });

/* -- Show/hide the select fields initially */

                $('.selectField > select').each(function( index, value ) {
                    popupValue = $(this).val();
                    switch (popupValue) {
                        case "custom":
                            $(this).closest('.comboField-wrapper').children('.selectFieldWrapper').hide();
                            $(this).closest('.comboField-wrapper').children('.customFieldWrapper').show();
                        break;

                        case "keywords":
                            $(this).closest('.comboField-wrapper').children('.selectFieldWrapper').show();
                            $(this).closest('.comboField-wrapper').children('.customFieldWrapper').hide();
                        break;

                        case "field":
                            $(this).closest('.comboField-wrapper').children('.selectFieldWrapper').show();
                            $(this).closest('.comboField-wrapper').children('.customFieldWrapper').hide();
                        break;
                    }
                });

                $('.selectField > select').on('change', function(e) {

                    switch (this.value) {
                        case "custom":
                            $(this).closest('.comboField-wrapper').children('.selectFieldWrapper').hide();
                            $(this).closest('.comboField-wrapper').children('.customFieldWrapper').show();
                        break;

                        case "keywords":
                            $(this).closest('.comboField-wrapper').children('.selectFieldWrapper').show();
                            $(this).closest('.comboField-wrapper').children('.customFieldWrapper').hide();
                        break;

                        case "field":
                            $(this).closest('.comboField-wrapper').children('.selectFieldWrapper').show();
                            $(this).closest('.comboField-wrapper').children('.customFieldWrapper').hide();
                        break;
                    }
                });

            });
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
