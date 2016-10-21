/**
 * @since 2016-10-21
 * @author Olle Härstedt
 */

$(document).ready(function() {
    $('body').attr('data-toggle', 'context');
    $('body').attr('data-target', '#context-menu');
    $('body').contextmenu();
});
