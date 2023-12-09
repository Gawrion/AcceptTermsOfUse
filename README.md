# AcceptTermsOfUse - Mediawiki extension
This is Mediawiki Extension that adds "Accept Terms of Use" checkbox to the registration page. It has simple validation. When not checked it gives You feedback that You have to accept terms.
It was written on Mediawiki 1.40.1.

# Instalation
Download directories and files. Place them in /extensions/ directory. Add in LocalSettings.php:<code>
wfLoadExtension( 'AcceptTermsOfUse' );
#if u wont define this variable it will point to default legal notes of Mediawiki
$wgAcceptTermsOfUsePage = '[[Project:TermsPage|Terms Name]]' ;
</code>
Check on Special:Version page if extension is working. Check if registration page has new checkbox - test it.
That's all folks!

ps. in i18n u have translation for polish (only one language) - You can create another translations. 

