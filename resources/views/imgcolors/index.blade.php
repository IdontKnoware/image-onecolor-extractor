@extends('layouts.master')
@section('content')
    @if($errors->has('img_input') )
        <span class="invalid-feedback" role="alert">
        </span>
    @endif

    <div class="container-fluid">
        <div class="row">
            @if(!empty($color_code))
                @php
                    dd()
                @endphp
                <h4 style="padding: 15px;">
                    Predominant color: <strong style="background-color: {{ $color_code }};
                        color: white; padding: 5px;">{{ $color_code }}</strong>
                </h4>
                <img alt="Your uploaded image" src="{{ asset(config('filesystems.imagescolors')
                        .DIRECTORY_SEPARATOR.$img_name) }}" width="50%" class=""/>
            @endif

            <div class="col-md-6 border table-responsive-sm table-responsive-md"
                 style="text-align: center;"><span id="img_file"></span>
            </div>

            {{-- COLOR TABLE --}}
            <div class="col-md-6 border table-responsive-sm table-responsive-md"
                 style="text-align: center;">
                @if(!empty($color_code))
                    <h4 style="padding: 15px;">
                        <strong>{{ $color_code }}</strong> is closest to {{ $closest_color }}
                    </h4>
                @endif
                <table class="table table-striped">
                    @php
                        $col = 0;
                    @endphp
                    <tr>
                        @foreach($table_colors as $name => $color)
                            @php
                                if($col == 4){
                                    echo '</tr>';
                                    $col = 1;
                                }else{
                                    $col++;
                                }
                            @endphp

                            @if( !empty($closest_color) )
                                @if($color != $closest_color)
                                    <td id="{{ $name }}" style="width: 50px; height: 50px;
                                        background-color: {{ $color }}; padding: 5px; border: 0px
                                        solid black;
                                        opacity: 0.2; filter: alpha(opacity=50);">
                                        {{ ucfirst($name) }}
                                        <br>
                                        {{ $color }}
                                    </td>
                                @else
                                    <td id="{{ $name }}" style="width: 50px; height: 50px;
                                        background-color: {{ $color }}; padding: 5px; border: 5px
                                        solid black;">
                                        {{ ucfirst($name) }}
                                        <br>
                                        {{ $color }}
                                    </td>
                                @endif
                            @else
                                <td id="{{ $name }}" style="width: 50px; height: 50px;
                                    background-color: {{ $color }}; padding: 5px; border: 0px
                                    solid black;">
                                    {{ ucfirst($name) }}
                                    <br>
                                    {{ $color }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                </table>
            </div>
        </div>

        <span class="alert" id="message" style="display: none"></span>

        <form id="img_form" class="my-2 border p-5" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-9">
                    <div class="custom-file">
                        <label>Select Image for Upload</label>
                        <input type="file" class="form-control col-sm-10" name="input_img"
                               id="input_img"
                               accept="image/*" required>
                    </div>
                    <br><br><br>
                    <div class="form-group">
                        <button id="submit_form" type="submit" class="btn
                            btn-primary">Upload</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@section('javascript')
    <script>
        $(document).ready(function () {
            $('#img_form').on('submit', function (event) {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('extractimgcolor.action') }}",
                    method: "POST",
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#message').css('display', 'block')
                                     .html(data.message)
                                     .addClass(data.class_name);

                        $('#img_file').html(data.img_file);
                    }
                })
            });
        });
    </script>
@endsection
