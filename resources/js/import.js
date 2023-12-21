import './bootstrap'

$(() => {
    $('#type').on('change', () => {
        if ($('#type').val() == 'registrations') {
            $('#import_matchday_field').hide();
            $('#file_field').show();
        } else {
            $('#import_matchday_field').show();
            $('#file_field').hide();
        }
    }).trigger('change');
});