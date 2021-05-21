@extends('layouts.default')

@section('content')

    <form method="POST" action="/login">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group mt-2">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    <div>
        Don't have an account yet? <a href="/register">Register here</a>
    </div>

@endsection
