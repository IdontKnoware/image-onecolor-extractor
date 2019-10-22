@extends('layouts.master')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6 border" style="text-align: center;">
                <h4>
                    Predominant color: <strong>{{ $color_code }}</strong>
                </h4>
                <br>
                <img alt="Your uploaded image" src="{{ asset(config('filesystems.imagescolors')
                .DIRECTORY_SEPARATOR.$img) }}"
                     width="50%"
                     class=""/>
            </div>

            {{-- COLOR TABLE --}}
            <div class="col-md-6 border table-responsive-sm table-responsive-md">
                <h4>
                    <strong>{{ $color_code }}</strong> is closest
                    to {{ $closest_color }}
                </h4>
                <br>

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
                    @endforeach
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection


