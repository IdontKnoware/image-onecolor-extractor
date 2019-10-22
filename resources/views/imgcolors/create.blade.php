@extends('layouts.master')
@section('content')
    @if($errors->has('img_input') )
        <span class="invalid-feedback" role="alert">
        </span>
    @endif
    <form action="{{ route('images.store') }}" class="my-2 border p-5" method="POST"
          enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group row">
            <div class="col-md-9">
                <div class="custom-file">
                    <input type="file" class="form-control col-sm-10" name="input_img"
                           id="input_img"
                   accept="image/*" required>
                </div>
                <br><br>
                <button type="submit" class="btn btn-success">Store</button>
            </div>
        </div>
    </form>
@endsection
