/*
 * Functionality for Seomatic MetaField fieldtype settings
 * by Andrew Welch - http://nystudio107.com
 *
 * Depends on: jQuery
 */


$( document ).ready(function() {

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
