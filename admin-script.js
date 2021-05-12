jQuery(function ($) {

    $('body').on('click', '.user_status', function () {
        var _this = $(this);
        var user_id = $(this).data('user-id');
        var user_status = $(this).text() === 'Approve' ? 1 : 0
        $.post(ajaxurl, {
            action: 'user_status',
            user_status,
            user_id
        }, function (response) {
            var label = user_status === 1 ? 'Disapprove' : 'Approve';
            _this.html('<button class="btn btn-xs btn-primary" data-user-id=' + user_id + ' type="button">' + label + '</button>');
        });
    })
});
