<x-app-layout>

    <div class="container bg-white rounded my-3">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-md-2 col-sm-12">
                <a href="{{ URL::to('dashboard') }}" class="btn btn-primary my-2">Back</a>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-header">Create Auto-Reply Rule</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('auto-reply.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="keyword">Keyword</label>
                                <input type="text"  name="keyword" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="message">Auto-Reply Message</label>
                                <textarea  name="message" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select id="type" name="type" class="form-control" required>
                                    <option value="post">Post</option>
                                    <option value="comment">Comment</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary text-black mt-3 float-end">Create Rule</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
