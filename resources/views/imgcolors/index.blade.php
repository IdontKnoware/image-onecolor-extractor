@extends( 'layouts.master' )
    @section( 'content' )

        <h3 align="center">Extract predominant color of an image using Laravel</h3>
        <br><br>
        <div class="container-fluid">

            @extends( 'imgcolors.modal' )

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
            <form id="img_form" class="" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row">
                    <div class="col-md-9">
                        <div class="custom-file">
                            <span style="font-size: 16px; margin-right: 15px;">Select Image for
                                Upload</span>
                            <input type="file" class="" name="input_img"
                                   id="input_img" accept="image/*" required>
                            <button id="submit_form" type="submit"
                                    class="upload-button">Extract predominant color</button>
                        </div>
                    </div>
                </div>
            </form>
            <br><br><br><br>
        </div>
    @endsection
    @section( 'javascript' )
        <script>
            //////////// AJAX Call ///////////////
            $(document).ready(function () {

                $('#img_form').on('submit', function (event) {
                    event.preventDefault();

                    let request = $.ajax({
                        url: "{{ route('extractimgcolor.action') }}",
                        method: "POST",
                        data: new FormData(this),
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false
                    });

                    request.done(function (data) {
                        // Title above uploaded image
                        $('#predominant_color').html(
                            'Predominant color: ' +
                            '<span ' + 'style="background-color: ' + data.predominant_color + '; ' +
                            'padding: ' + '5px; ' +
                            'color: white;">' + data.predominant_color +
                            '</span>'
                        );

                        // Uploaded image
                        $('#span_img_file').html(data.img_file);

                        // Title above colors table
                        $('#color_compare').html(data.predominant_color + ' is closest to ' +
                            data.closest_color);

                        // Info message
                        $('#message').css('display', 'block')
                            .html(data.message).delay(1000).fadeOut(500)
                            .addClass(data.class_name);
                    });

                    //TODO: La resposta del Controller quan no és imatge no arriba correctament.
                    request.fail(function (jqXHR, textStatus, data) {
                        $('#message').css('display', 'block')
                            .html('Error. Try again').delay(1000).fadeOut(500)
                            .addClass('alert-danger');
                    });
                });

                ///////////// Image MODAL //////////////////
                // Get the entire modal
                var modal = $('#myModal');

                // Get modal <img> tag
                var modalImg = $('#img01');

                // Get uploaded image for use in modal
                $('#span_img_file').click(function () {
                    modal.css("display", "block");
                    modalImg.attr("src", $('#img_file').attr("src"));
                });

                // 'X' button
                var spanCloseModal = $('.close-modal');

                spanCloseModal.click(function () {
                    modal.css("display", "none");
                });
            });
        </script>
    @endsection
