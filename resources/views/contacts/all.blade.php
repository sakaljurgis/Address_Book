@extends('layouts.default')

@section('content')

    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add new</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="modalAlerts"></div>
                <div id="modalBody" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modalSaveButton">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <table id="contactsTable" class="display">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($contacts as $contact)
            <tr id="contact-{{ $contact->id }}">
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
                <td>
                    <button class="btn btn-secondary" name="edit-contact" data-contact-id="{{ $contact->id }}" title="Edit"><i class="bi-pencil-square" title="Edit"></i></button>
                    <button class="btn btn-secondary" name="share-contact" data-contact-id="{{ $contact->id }}" title="Share"><i class="bi-share" title="Share"></i></button>
                    <button class="btn btn-secondary" name="delete-contact" data-contact-id="{{ $contact->id }}" title="Delete"><i class="bi-trash" title="Delete"></i></button>
                </td>
            </tr>
        @endforeach
        @foreach ($contactsSharedWithMe as $contact)
            <tr id="contact-{{ $contact->id }}">
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone }}</td>
                <td>Shared contact</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="form-group mt-2">
        <button id="addButton" class="btn btn-primary">Add new</button>
    </div>

@endsection

@section('script')

    <script>

        let contactsTable;
        $(document).ready( function () {
            contactsTable = $('#contactsTable').DataTable();
        } );

        function appendContactToTable(data) {
            if (!data || !contactsTable) {
                return;
            }

            let actionButtonsHtml = '' +
                '<button class="btn btn-secondary" name="edit-contact" data-contact-id="'+ data.id +'" title="Edit"><i class="bi-pencil-square" title="Edit"></i></button> ' +
                '<button class="btn btn-secondary" name="share-contact" data-contact-id="'+ data.id +'" title="Share"><i class="bi-share" title="Share"></i></button> ' +
                '<button class="btn btn-secondary" name="delete-contact" data-contact-id="'+ data.id +'" title="Delete"><i class="bi-trash" title="Delete"></i></button>';

            let tableAppendData = [
                data.name,
                data.email,
                data.phone,
                actionButtonsHtml
            ];

            let rowNode = contactsTable.row.add(tableAppendData).draw().show().draw(false).node();
            rowNode.setAttribute('data-id', data.id);
            let background = $(rowNode).css('background');
            $(rowNode).css('background', 'grey').animate({backgroundColor: background});
        }

        function addModalAlert(msg, style) {
            addAlert(msg, style, true);
        }


        function loadModal(url, title, disableSaveButton) {

            var modal = $('#modal');
            var modalBody = $('#modalBody');
            modalBody.html("Loading...");
            $('#modalLabel').text(title);

            if (disableSaveButton) {
                $('#modalSaveButton').hide();
            } else {
                $('#modalSaveButton').show();
            }

            $.ajax({
                url: url,
                type: 'get',
                success: function(response){
                    modalBody.html(response);
                    modal.modal('show');
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.message) {
                        addAlert(JSON.stringify(error.responseJSON.message));
                    } else {
                        addAlert("Unknown error")
                    }
                }
            });

        }

        $('#addButton').on('click', function(event) {
            event.preventDefault();
            loadModal("/contact", "Add new contact")

        });

        $(document).on('click', '#modalSaveButton', function(event) {
            event.preventDefault();
            var form= $('#modalBody form');
            var modalBody = $('#modalBody');
            var modal = $('#modal');
            var contactId = form.find('input[name="id"]').val();
            $.ajax({
                url: '/contact' + (contactId ? "/" + contactId : ''),
                type: 'post',
                data: form.serializeArray(),
                success: function(response){
                    modalBody.html(response);
                    modal.modal('hide');
                    let data = response;
                    if (contactId) {
                        contactsTable.row($('#contact-' + data.id)).remove();
                        addAlert("Contact updated successfuly!", 'success');
                    } else {
                        addAlert("Contact added successfuly!", 'success');
                    }
                    appendContactToTable(data);
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

        $(document).on('click', '[name="edit-contact"]', function(event) {
            var contactId = event.currentTarget.dataset.contactId;
            loadModal('/contact/' + contactId, "Edit Contact");
        });

        $(document).on('click', '[name="share-contact"]', function(event) {
            var contactId = event.currentTarget.dataset.contactId;
            loadModal('/share/' + contactId, "Share Contact", true);
        });

        $(document).on('click', '[name="delete-contact"]', function(event) {
            let contactId = event.currentTarget.dataset.contactId;
            let self = this;
            $.ajax({
                url: '/contact/' + contactId,
                type: 'delete',
                data: "_token={{ csrf_token() }}",
                success: function(response){
                    let row = $(self.closest('tr'));
                    row.fadeOut(400, function () {
                        contactsTable.row(row).remove().draw();
                        addAlert('Successfully deleted contact!', 'success');
                    })
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        addAlert(JSON.stringify(error.responseJSON.errors));
                    } else {
                        addAlert("Unknown error")
                    }
                }
            });
        });

        //todo - refactor both to one function
        $(document).on('click', '[name="share-the-contact"]', function(event) {
            let userId = event.currentTarget.dataset.userId;
            let contactId = event.currentTarget.dataset.contactId;
            let button = $(this);
            button.prop('disabled', true)

            $.ajax({
                url: '/share/' + contactId + '/' + userId,
                type: 'post',
                data: "_token={{ csrf_token() }}",
                success: function(response){
                    button.attr('name', 'unshare-the-contact');
                    button.text('Unshare');
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        addModalAlert(JSON.stringify(error.responseJSON.errors));
                    } else {
                        addModalAlert("Unknown error")
                    }
                },
                complete: function() {
                    button.prop('disabled', false)
                }
            });

        });

        $(document).on('click', '[name="unshare-the-contact"]', function(event) {
            let userId = event.currentTarget.dataset.userId;
            let contactId = event.currentTarget.dataset.contactId;
            let button = $(this);
            button.prop('disabled', true)

            $.ajax({
                url: '/share/' + contactId + '/' + userId,
                type: 'delete',
                data: "_token={{ csrf_token() }}",
                success: function(response){
                    button.attr('name', 'share-the-contact');
                    button.text('Share');
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        addModalAlert(JSON.stringify(error.responseJSON.errors));
                    } else {
                        addModalAlert("Unknown error")
                    }
                },
                complete: function() {
                    button.prop('disabled', false)
                }
            });
        });


    </script>

@endsection
