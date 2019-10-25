@extends('layouts.master')
@section('content')

    @if($errors->has('img_input') )
        <span class="invalid-feedback" role="alert">
        </span>
    @endif

    {{-- Image modal --}}
    @extends('layouts.modal')

    <h3 align="center">Extract predominant color of an image using Laravel</h3>
    <br><br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 border table-responsive-sm table-responsive-md"
                 style="text-align: center;">
                {{-- Predominant color title and uploaded image --}}
                <h4 id="predominant_color" style="padding:15px;">No image uploaded</h4>
                <span id="span_img_file"></span>
            </div>

            {{-- COLOR TABLE --}}
            <div class="col-md-6 border table-responsive-sm table-responsive-md"
                 style="text-align: center;">
                @extends('layouts.colortable')
                {{-- Closest color title --}}
                <h4 id="color_compare" style="padding: 15px;"></h4>
                <table class="table table-striped">
                    @php
                        $col = 0;
                    @endphp
                    <tr>
                        @foreach( $table_colors as $name => $color )
                            @php
                                if( $col == 4 ){
                                    echo '</tr>';
                                    $col = 1;
                                } else {
                                    $col++;
                                }
                            @endphp
                            <td id="table-td" style="background-color: {{ $color }};">
                                {{ ucfirst( $name ) }}
                                <br>
                                {{ $color }}
                            </td>
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
                        <input type="file" class="form-control col-sm-10" name="input_img" id="input_img" accept="image/*" required>
                    </div>
                    <br><br><br>
                    <div class="form-group">
                        <button id="submit_form" type="submit" class="upload-button">Upload</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('javascript')
    <script>
        //////////// AJAX Call ///////////////
        $(document).ready(function () {
            $('#img_form').on('submit', function (event) {
                event.preventDefault();
                $.ajax({
                    url         : "{{ route('extractimgcolor.action') }}",
                    method      : "POST",
                    data        : new FormData(this),
                    dataType    : 'JSON',
                    contentType : false,
                    cache       : false,
                    processData : false,

                    success: function (data) {
                        $('#message').css('display', 'block')
                            .html(data.message).delay(1000).fadeOut(500)
                            .addClass(data.class_name);

                        $('#span_img_file').html(data.img_file).addClass
                        ('uploaded-img-predominant-color');

                        $('#predominant_color').html('Predominant color: <span ' +
                            'style="background-color: ' + data.predominant_color + '; padding: ' +
                            '5px; color: white;">' + data
                                .predominant_color + '</span>');

                        $('#color_compare').html(data.predominant_color + ' is closest to ' +
                            data.closest_color);
                    },
                    //TODO - Controlar error response
                })
            });
        });

        ///////////// Image MODAL //////////////////
        // Get the image modal
        var modal = $('#myModal');

        // Get uploaded image and insert into modal
        var img_file = $('#span_img_file');
        var modalImg = $('#img01');
        var captionText = $('#modal-caption');

        // Show the modal
        img_file.click(function () {
            console.log($(this).first());

            modal.css("display", "block");
            modalImg.attr("src", $('#img_file').attr("src"));
            captionText.html($(this).attr("alt"));
        });

        // 'X' button for close the modal
        var spanCloseModal = $('.close');

        spanCloseModal.click(function () {
            console.log("close modal");
            modal.css("display", "none");
        });

    </script>
@endsection
