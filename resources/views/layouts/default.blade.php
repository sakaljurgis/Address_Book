<html lan="en">
<head>
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="container">
        <div class="row bg-secondary mt-2 mb-3">
            <h1 class="text-light text-center">Address Book</h1>
        </div>
        <div class="row">
            <nav class="col-3">
                <h2>Nav</h2>
                @include('navigation')
            </nav>
            <main class="col-9">
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

                @if (session('alert'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('alert') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div id="alerts"></div>

                <h2>{{ $title }}</h2>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.24/api/row().show().js"></script>

    @yield('script')
    <script>
        //auto dismiss alerts
        $(document).ready( function () {
            let alertObj = $('.alert');
            alertObj.fadeTo(2000, 500).slideUp(500, function(){
                alertObj.slideUp(500);
            });
        } );

        function addAlert(msg, style, modal) {
            if (!style) {
                style = 'danger';
            }

            let alertHtml = '<div class="alert alert-'+ style +' alert-dismissible fade show" role="alert">' + msg +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            let alertObj = $(alertHtml);

            if (modal) {
                $('#modalAlerts').append(alertObj);
            } else {
                $('#alerts').append(alertObj);
            }

            alertObj.fadeTo(2000, 500).slideUp(500, function(){
                alertObj.slideUp(500);
            });

        }

    </script>
</body>
</html>
