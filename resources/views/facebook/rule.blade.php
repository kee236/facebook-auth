<x-app-layout>

    <div class="container my-3 bg-white rounded">
        {{-- Begin:Create Rule --}}
        <div class="row mb-3">
            <div class="col-lg-2 col-md-2 col-sm-12">
                <a href="{{ URL::to('dashboard') }}" class="btn btn-primary my-2">Back</a>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-12 ">
                <div class="container">
                    <form action="{{ route('user.add') }}" method="POST" class="my-3" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Select facebook page</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <select name="page_name" class="form-select">
                                        @foreach ($users as $user)
                                            @if ($user->facebook_page_name != null)
                                            <option>Select facebook page</option>
                                                <option value="{{ $user->facebook_page_name }}" style="font-size:15px;">{{ $user->facebook_page_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Rule Name</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <input type="text" name="rule_name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Avaible Area</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <select name="location" class="form-select">
                                        <option value="Thai" style="font-size:15px;">Thai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Currency</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <select name="currency" class="form-select">
                                        <option value="thb" style="font-size:15px;">thb</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-4 col-md-3 col-sm-12 p-0">
                                <div class="">
                                    <span style="font-size:15px;">Create post</span>
                                 </div>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-12 p-0">
                                <input type="file" name="images[]" multiple>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">

                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <button type="submit" class="btn btn-primary text-black float-end">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- End:Create Rule --}}

        <div class="border-top"></div>

        {{-- Begin:Create Auto Keyword --}}
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12"></div>
            <div class="col-lg-10 col-md-10 col-sm-12 ">
                <div class="container">
                    <form action="{{ route('comments.add') }}" method="POST" class="my-3">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Select your post</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <select name="commentable_id" class="form-select">
                                        <option>Select post</option>
                                        {{-- <option value="{{ $comment->commentable->id }}">{{ $comment->commentable->id }}</option> --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Auto reply to keyword comments</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <textarea name="commentable_type" style="width:100%; height:70px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">

                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <button type="submit" class="btn btn-primary text-black float-end">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- End:Create Auto Keyword --}}
    </div>


    {{-- Begin:Post Modal --}}
    {{-- <div class="col-lg-6 col-md-7 col-sm-12 p-0">
        <a href="" class="text-primary" data-bs-toggle="modal" data-bs-target="#post-modal">Connect</a>
    </div> --}}
  {{-- <div class="modal fade" id="post-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary text-black" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" style="background-color: #0B5ED7!important; border-color:#0B5ED7;outline:none;">Save</button>
        </div>
      </div>
    </div>
  </div> --}}
  {{-- End:Post Modal --}}
</x-app-layout>
