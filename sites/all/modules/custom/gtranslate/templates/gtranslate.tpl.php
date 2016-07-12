<?php if (!empty($languages)): ?>
<div id="google_translate_element"><label class="element-invisible">Google Translate</label></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: '<?php print join(",", array_filter($languages)); ?>'
  }, 'google_translate_element');
}
</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php endif; ?>
