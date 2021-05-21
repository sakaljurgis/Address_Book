@if ($errors)
    <div>
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    </div>
@endif
<form method="POST" action="">
    {{ csrf_field() }}

    <input type="hidden" id="id" name="id" value="{{ $contact->id ?? '' }}">

    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $contact->name ?? '' }}">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ $contact->email ?? '' }}">
    </div>

    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="phone" class="form-control" id="phone" name="phone" value="{{ $contact->phone ?? '' }}">
    </div>
</form>



