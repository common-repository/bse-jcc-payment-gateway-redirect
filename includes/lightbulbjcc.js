function blink() {
    jQuery('.lightbulbjcc').fadeOut(500).fadeIn(500, blink);
};
jQuery(document).ready(function() {
    blink();
});