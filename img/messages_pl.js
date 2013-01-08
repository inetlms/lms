/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: PL
 */
jQuery.extend(jQuery.validator.messages, {
	required: "<p style='color:red;'>dane są wymagane</p>",
	remote: "<p style='color:red;'>Wartość tego pola nie jest podana lub jest już w użyciu.</p>",
	email: "<p style='color:red;'>Proszę o podanie prawidłowego adresu email.</p>",
	url: "<p style='color:red;'>Proszę o podanie prawidłowego URL.</p>",
	date: "<p style='color:red;'>Proszę o podanie prawidłowej daty.</p>",
	dateISO: "<p style='color:red;'>Proszę o podanie prawidłowej daty (ISO).</p>",
	number: "<p style='color:red;'>Proszę o podanie prawidłowej liczby / cyfr.</p>",
	digits: "<p style='color:red;'>Proszę o podanie samych cyfr.</p>",
	creditcard: "<p style='color:red;'>Proszę o podanie prawidłowej karty kredytowej.</p>",
	equalTo: "<p style='color:red;'>Proszę o podanie tej samej wartości ponownie.</p>",
	accept: "<p style='color:red;'>Proszę o podanie wartości z prawidłowym rozszerzeniem.</p>",
	maxlength: jQuery.validator.format("<p style='color:red;'>Proszę o podanie nie więcej niż {0} znaków.</p>"),
	minlength: jQuery.validator.format("<p style='color:red;'>Proszę o podanie przynajmniej {0} znaków.</p>"),
	rangelength: jQuery.validator.format("<p style='color:red;'>Proszę o podanie wartości o długości od {0} do {1} znaków.</p>"),
	range: jQuery.validator.format("<p style='color:red;'>Proszę o podanie wartości z przedziału od {0} do {1}.</p>"),
	max: jQuery.validator.format("<p style='color:red;'>Proszę o podanie wartości mniejszej bądź równej {0}.</p>"),
	min: jQuery.validator.format("<p style='color:red;'>Proszę o podanie wartości większej bądź równej {0}.</p>")
});