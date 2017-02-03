/**
 * @since 2016-10-21
 * @author Olle Härstedt
 */

$(document).ready(function() {
    $('body').attr('data-toggle', 'context');
    $('body').attr('data-target', '#context-menu');
    $('body').contextmenu();

    // Copy button
    $('#right-click-menu-copy').on('click', function(ev) {
        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }

        return false;
    });

    // Paste
    $('#right-click-menu-paste').on('click', function(ev) {
        try {
            var successful = document.execCommand('paste');

            if (!successful) {
                alert('Pasting seems to be disabled in your browser. Please use Ctrl/Cmd-V instead.');
            }

            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Pasting text command was ' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }

        return false;
    });


    // Cut
    $('#right-click-menu-cut').on('click', function(ev) {
        try {
            var successful = document.execCommand('cut');

            if (!successful) {
                alert('Cutting seems to be disabled in your browser. Please use Ctrl/Cmd-X instead.');
            }

            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Cutting text command was ' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }

        return false;
    });
});
