import './bootstrap'

$(() => {
    $('#type').on('change', () => {
        if ($('#type').val() == 'registrations_match') {
            $('#import_matchday_field').show();
            $('#file_field').hide();
        } else {
            $('#import_matchday_field').hide();
            $('#file_field').show();
        }
    }).trigger('change');
});