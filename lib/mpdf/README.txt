General
=======
mPDF 5.0 has improved font handling making it a lot easier to use any (TrueType) fonts you want,
without having to prepare extra font files.


Users of mPDF 4.x and before
============================
mPDF 5.0 now works with TrueType .ttf font-files directly, and can embed subsets of fonts
in TrueType form. There is no longer any need to use pre-prepared font files in different
codepages (win-1251, iso-8859-2 etc.).

Once the new system for using fonts is set up correctly, most of your scripts should work as before.
Nevertheless I strongly suggest testing mPDF 5.0 without deleting your previous set-up.


----------------------
Installation - cf. in manual

Character substitution - cf. in manual
----------------------


Controlling mPDF mode
=====================
The first parameter of new mPDF('') now works as follows:
new mPDF('c') - forces mPDF to only use the built-in [C]ore Adobe fonts (Helvetica, Times etc)
	This was previously set using $this->useOnlyCoreFonts (depracated)

new mPDF('') - default - font subsetting behaviour is determined by the configurable variables
	$this->maxTTFFilesize and $this->percentSubset (see below)
	Default values are set so that: 1) very large font files are always subset
	2) Fonts are embedded as subsets if < 30% of the characters are used

new mPDF('..-x') - used together with a language or language/country code, this will cause
	mPDF to use only in-built core fonts (Helvetica, Times) if the language specified is appropiate; 
	otherwise it will force subsetting (equivalent to using "")
	e.g. new mPDF('de-x') or new mPDF('pt-BR-x') will use in-built core fonts
	and new mPDF('ru-x') will use subsets of any available TrueType fonts
	The languages that use core fonts are defined in config_cp.php (using the value $coreSuitable).

new mPDF('..+aCJK')  new mPDF('+aCJK')
new mPDF('..-aCJK')  new mPDF('-aCJK')
	 - used optionally together with a language or language/country code, +aCJK will force mPDF
	to use the Adobe non-embedded CJK fonts when a passage is marked with e.g. "lang: ja"
	This can be used at runtime to override the value set for $mpdf->useAdobeCJK in config.php
	Use in conjunction with settings in config_cp.php

For backwards compatibility, new mPDF('-s') and new mPDF('s') will force subsetting by 
	setting $this->percentSubset=100 (see below)
	new mPDF('utf-8-s') and new mPDF('ar-s') are also recognised

Language/Country (ll-cc)
------------------------
You can use a language code ('en') or language/country code ('en-GB') to control which 
mode/fonts are used. The behaviour is set up in config_cp.php file.
The default settings show some of the things you can do:
new mPDF('de') - as German is a Western European langauge, it is suitable to use the Adobe core fonts.
	Using 'de' alone will do nothing, but if you use ('de-x'), this will use core fonts.
new mPDF('th') - many fonts do not contain the characters necessary for Thai script. The value $unifonts 
	defines a restricted list of fonts available for mPDF to use.
new mPDF('ar') - The value $dir will set the default directionality to RTL, which affects
	default text-alignment, layout of lists etc.
new mPDF('hi') - As Hindi is a cursive script, $spacing="W" will force any spacing used to justify text
	to affect word spacing, not character spacing.

NB <html dir="rtl"> or <body dir="rtl"> are now both supported.

Previous users: "codepages"
---------------------------
The old "codepages" are ignored:
new mPDF('win-1251') or new mPDF('utf-8') is now treated as new mPDF('')
new mPDF('win-1251-s') is now treated as new mPDF('s')



Configuration variables changed
===============================
Most configuration variables are unchanged, and are documented in the on-line manual (http://mpdf1.com/manual/).

Removed (now ignored/inactive)
------------------------------
$this->useOnlyCoreFonts (previous alias $use_embeddedfonts_1252)
$this->use_CJK_only 

Added (config.php)
------------------
$this->useAdobeCJK - forces all CJK text to use Adobe's CJK language font pack.
$this->debugfonts - set this to true to reveal error messages if having trouble with fonts


// This value determines whether to subset or not
// mPDF will embed the whole font if >x% characters in that font have been used
// or embed subset if <x% characters
// Set to 0 will force whole file to always be embedded
// Set to 100 will force mPDF to always subset
$this->percentSubset = 30;


// Set maximum size of TTF font file to allow non-subsets - in kB
// Used to avoid large files like Arial Unicode MS ever being fully embedded
// This takes precedence over the value of $this->percentSubset
$this->maxTTFFilesize = 2000;

NB $this->useSubstitutionsMB is depracated (but will still work as an alias for useSubstitutions).

Font folders
============
If you wish to define your own font file folders (perhaps to share),
you can define the 2 constants in your script before including the mpdf.php script e.g.:

define('_MPDF_TTFONTPATH','your_path/ttfonts/'); 		
define('_MPDF_TTFONTDATAPATH','your_path/ttfontdata/'); 	// should be writeable



======================================================================
For more information on fonts, especially Arabic, CJK and Indic fonts,
see FONT INFO.txt
======================================================================
