<x-app-layout>

    <div class="container my-3 bg-white rounded">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12">
                <a href="{{ URL::to('dashboard') }}" class="btn btn-primary my-2">Back</a>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-12 ">
                <div class="container mt-2">
                        @foreach ($users as $user)
                            @if ($user->facebook_page_name !== null && $user->status !== null)
                                <div class="row mb-3">
                                    <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                        <span style="font-size:15px;">facebook page</span>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                        <div class="form-group">
                                            <span style="font-size:15px;">{{ $user->facebook_page_name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                        <span style="font-size:15px;">Status</span>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                        <div class="form-group">
                                            <span style="font-size:15px;">{{ $user->status }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <div class="my-3 float-start">
                           <a href="{{ route('user.index') }}" class="btn btn-primary">Create Rules</a>
                        </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12"></div>
                <div class="col-lg-10 col-md-10 col-sm-12 ">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Rule Name</th>
                                <th>Facebook Page</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rules as $rule)
                                <tr>
                                    <td>{{ $rule->rule_name }}</td>
                                    <td>{{ $rule->page_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
