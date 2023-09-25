<x-app-layout>

    <div class="container my-3 bg-white rounded">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12">
                <a href="{{ URL::to('dashboard') }}" class="btn btn-primary my-2">Back</a>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-12 ">
                <div class="p-3 d-flex headers">
                    <h2 class="text-primary fw-semibold me-2">Facebook</h2>
                    <a href="{{ route('facebook.redirect') }}" class="btn btn-primary">Connect</a>
                </div>
                <div class="fb-infos">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Page Name</th>
                                <th>Account Name</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->facebook_page_name}}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->status }}</td>
                                <td>{{ $user->created_at}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
