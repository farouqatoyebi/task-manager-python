$(function(e) {
    const csrf_token = $(`meta[name=csrf-token]`).attr(`content`);

    $('body').on('click', '.confirm-action', function(e) {
        e.preventDefault();
        var method = $(this).attr('action'),
            action_text = $(this).attr('action_text'),
            message = $(this).attr('message'),
            path = $(this).attr('path'),
            _this = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: action_text
        }).then((result) => {
            if (result.isConfirmed) {
                _this.append(`<i class="fa fa-spin fa-spinner"></i>`);
                _this.addClass(`disabled`);

                $.ajax({
                    url: path,
                    method: method,
                    data: {
                        _token: csrf_token
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success'
                            });

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(error) {
                        var response = error.responseJSON;

                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        _this.find(`.fa-spinner`).remove();
                        _this.removeClass(`disabled`);
                    }
                });
            }
        });
    });

    $('body').on('submit', 'form.form', function(e) {
        e.preventDefault();

        var data = $(this).serializeArray(),
            _this = $(this);

        _this.find('button').attr('disabled', 'disabled');
        _this.find('button[type=submit]').html(`Saving...<i class='fa fa-spin fa-spinner'></i>`);

        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: _this.attr('action'),
            method: _this.attr('method'),
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(error) {
                var response = error.responseJSON;

                if (response.errors) {
                    $.each(response.errors, function(index, value) {
                        $(`[name=${index}]`).addClass('is-invalid');
                        $(`[name=${index}]`).closest('div').find('.invalid-feedback').text(value);
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error'
                    });
                }
                _this.find('button').removeAttr('disabled');
            },
            complete: function() {
                _this.find('button[type=submit]').html(`Save`);
            }
        });
    });

    $('body').on('click', 'a[data-bs-target="#TaskManager"]', function(e) {
        $(`form.form`).attr(`action`, $(this).attr(`action`));

        var task_parent = $(this).closest(`tr`);
        var task_title = task_parent.find(`.task-title`).text(),
            task_desc = task_parent.find(`.task-desc`).text(),
            task_status = task_parent.find(`.task-status`).attr(`value`);

        $(`#task_name`).val(task_title);
        $(`#task_desc`).val(task_desc);
        $(`#task_completed`).prop(`checked`, parseInt(task_status));
    });
});