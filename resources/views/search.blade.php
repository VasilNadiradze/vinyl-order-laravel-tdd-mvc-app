<html lang="en">
    <head>
        <title>Vinyl</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    </head>
    <body>
    <main>
        <div class="album py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <form action="/" method="GET">
                            <input class="form-control form-control-lg" type="query" name="query" value="{{ $query_str ?? '' }}" placeholder="რისი მოსმენა გსურთ ?">
                        </form>
                    </div>
                </div>
                <div class="row">
                    @foreach($items as $item)
                        <div class="col-md-4">
                            <div class="card mb-4 box-shadow">
                                <img class="card-img-top" src="{{ $item->image }}">
                                <div class="card-body">
                                    <p class="card-text">
                                        {{ $item->name }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <form action="/add_to_cart" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">დამატება</button>
                                            </form>
                                        </div>
                                        <small class="text-muted">{{ $item->cost }} ₾</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</body>
</html>
            