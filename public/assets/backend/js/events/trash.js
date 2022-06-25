$(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: route('events.trash'),
        columns: [{
            data: 'checkbox',
            name: 'checkbox'
        }, {
            data: 'id',
            name: 'id'
        }, {
            data: 'image',
            name: 'image'
        }, {
            data: 'titles',
            name: 'titles'
        }, {
            data: 'day_number',
            name: 'day_number'
        }, {
            data: 'start_at',
            name: 'start_at'
        }, {
            data: 'deleted_at',
            name: 'deleted_at'
        }, {
            data: 'action',
            name: 'action',
            class: 'text-center',
            orderable: false,
            searchable: false
        }, ],
        responsive: true,
        'createdRow': function (row, data, dataIndex) {
            $(row).attr('id', data.id);
        },
        "order": [
            [0, "desc"]
        ],
        "bDestroy": true,
    });
});

function restoreRecord(id) {
    $.ajax({
        url: route('events.restore'),
        type: 'POST',
        data: { id: id },
        beforeSend: function () {
            $.LoadingOverlay("show");
        },
        success: function (data) {
            $.LoadingOverlay("hide");
            let result = JSON.parse(data);
            $('.message').html('<div class="alert alert-' + result.type +
                ' alert-dismissible fade show" role="alert"><i class="mdi ' + result.icon +
                ' me-2"></i>' + result.message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> </div>'
            );

            let table = $('.data-table').DataTable();
            table.row('#' + id).remove().draw(false);
        },
        error: function (data) {
            $.LoadingOverlay("hide");
            $('.message').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="mdi mdi-check-all me-2"></i>' + data.responseJSON.message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> </div>'
            );
        }
    });
}
