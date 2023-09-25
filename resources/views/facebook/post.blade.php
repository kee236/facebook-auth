<x-app-layout>

    <div class="container my-3 bg-white rounded">
        <div class="row mb-3">
            <div class="col-lg-2 col-md-2 col-sm-12">
                <a href="{{ URL::to('dashboard') }}" class="btn btn-primary my-2">Back</a>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-12 ">
                <div class="container">
                    <form action="{{ route('facebook.page') }}" method="POST" class="my-3" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Post name</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Message</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <input type="text" name="message" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                <span style="font-size:15px;">Picture</span>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <div class="form-group">
                                    <input type="file" name="image[]" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-2 col-md-3 col-sm-12 p-0">

                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-12 p-0">
                                <button type="submit" class="btn btn-primary text-black float-end">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>





    </div>
</x-app-layout>
