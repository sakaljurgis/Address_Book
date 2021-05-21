@extends('layouts.default')

@section('content')

    <h3>Shared with me</h3>

    <table id="sharedWithMeTable" class="display">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($contactsSharedWithMe as $contact)
            <tr id="contact-{{ $contact->id }}">
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3>My shared contacts</h3>

    <table id="mySharedContacts" class="display">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Shared To</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($mySharedContacts as $contact)
            <tr id="contact-{{ $contact->id }}">
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
                <td>{{ $contact->sharedToName }}</td>
                <td><button class="btn btn-secondary btn-sm" name="unshare-the-contact" data-contact-id="{{ $contact->id }}" data-user-id="{{ $contact->sharedToId }}">Unshare</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('script')

    <script>

        let sharedWithMeTable;
        $(document).ready( function () {
            sharedWithMeTable = $('#sharedWithMeTable').DataTable();
        } );

        let mySharedContacts;
        $(document).ready( function () {
            mySharedContacts = $('#mySharedContacts').DataTable();
        } );

        $(document).on('click', '[name="unshare-the-contact"]', function(event) {
            let userId = event.currentTarget.dataset.userId;
            let contactId = event.currentTarget.dataset.contactId;
            let button = $(this);
            let row = $(this.closest('tr'));
            button.prop('disabled', true)

            $.ajax({
                url: '/share/' + contactId + '/' + userId,
                type: 'delete',
                data: "_token={{ csrf_token() }}",
                success: function(response){
                    row.fadeOut(400, function () {
                        mySharedContacts.row(row).remove().draw();
                        addAlert('Successfully removed share', 'success');
                    })
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        addModalAlert(JSON.stringify(error.responseJSON.errors));
                    } else {
                        addModalAlert("Unknown error")
                    }
                }
            });
        });

    </script>

@endsection
