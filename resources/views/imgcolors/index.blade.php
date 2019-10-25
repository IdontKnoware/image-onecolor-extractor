@extends('layouts.master')
@section('content')
    @if($errors->has('img_input') )
        <span class="invalid-feedback" role="alert">
        </span>
    @endif
    <h3 align="center">Extract predominant color of an image using Laravel</h3>
    <br><br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 border table-responsive-sm table-responsive-md"
                 style="text-align: center;">
                <h4 id="predominant_color" class="" style="padding:
                15px;">No
                    image uploaded</h4>
                <span id="img_file"></span>
            </div>

            {{-- COLOR TABLE --}}
            <div class="col-md-6 border table-responsive-sm table-responsive-md" style="text-align: center;">
                <h4 id="color_compare" style="padding: 15px;"></h4>
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

        <br><br>
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
                        <button id="submit_form" type="submit" class="upload-button">Upload</button>
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
                                     .html(data.message).delay(1000).fadeOut(500)
                                     .addClass(data.class_name);
                        $('#img_file').html(data.img_file).addClass
                        ('uploaded-img-predominant-color');
                        $('#predominant_color').html('Predominant color: ' + data
                            .predominant_color);
                        $('#color_compare').html(data.predominant_color + ' is closest to ' +
                            data.closest_color);
                    }
                })
            });
        });
    </script>
@endsection
