<div>
    <div class="mb-3">
        <h4>Share "{{ $contact->name }}" with</h4>

    </div>
    <table style="margin:0; width:100%" class="display" id="usersTable"></table>
</div>
<script>


    $(document).ready( function () {
        var usersTable;
        if(usersTable != null){
            usersTable.clear();
            usersTable.destroy();
            $('#usersTable tbody').empty();
            $('#usersTable thead').empty();
        }

        let users = @json($users);

        usersTable = $('#usersTable').DataTable({
            data: users,
            columns: [
                {data: 'name', title: "Name"},
                {data: 'email', title: "Email"},
                {
                    data: null,
                    title: "Actions",
                    render: function ( data, type, row ) {
                        if (data.contactShared) {
                            return '<button class="btn btn-secondary" name="unshare-the-contact" data-contact-id="'+ {{ $contact->id }} +'" data-user-id="'+ data.id +'">Unshare</button>'
                        } else {
                            return '<button class="btn btn-secondary" name="share-the-contact" data-contact-id="'+ {{ $contact->id }} +'" data-user-id="'+ data.id +'">Share</button>'
                        }
                    },
                    orderable: false
                },
            ]
        });
    } );



</script>
