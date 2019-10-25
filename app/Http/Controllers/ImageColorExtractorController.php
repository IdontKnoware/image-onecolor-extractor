<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageStoreRequest;
use Illuminate\Http\Request;
use App\ImageColorExtractor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ImagickPixel;
use Intervention\Image\Facades\Image;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

class ImageColorExtractorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Colors to match
        $colors = ['aqua' => '#00FFFF',
            'black' => '#000000',
            'blue' => '#0000FF',
            'fuchsia' => '#FF00FF',
            'gray' => '#808080',
            'green' => '#008000',
            'lime' => '#00FF00',
            'maroon' => '#800000',
            'navy' => '#000080',
            'olive' => '#808000',
            'purple' => '#800080',
            'red' => '#FF0000',
            'silver' => '#C0C0C0',
            'teal' => '#008080',
            'white' => '#FFFFFF',
            'yellow' => '#FFFF00',
        ];

        return view( 'imgcolors.index' )->with('table_colors', $colors);
    }

    /**
     * Get image from request, generates color palette from it,
     * compare the predominant color of the image to predefined colors
     * and return which of them is closest to predominant color of the image.
     *
     * @param ImageStoreRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \ImagickPixelException
     */
    public function extractImgColor(ImageStoreRequest $request)
    {
        //TODO: Fer servir l'ImageStoreRequest per passar validació

        $validation = Validator::make($request->all(), [
            'input_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if( $validation->passes() ) {
            $img_path = $request->file( 'input_img' )->store(config('filesystems.imagescolors'));

            // Create in memory
            $image = Image::make($img_path)->resize(300,300);

            // Extract palette from filename and create assoc array
            $raw_palette = Palette::fromFilename( $img_path );
            $raw_most_used = $raw_palette->getMostUsedColors( 1 );

            foreach($raw_most_used as $color => $count) {
                $most_used = ['pixels' => $count,
                    'color' => Color::fromIntToHex($color)];
            }

            // Colors to match
            $colors = ['aqua' => '#00FFFF',
                'black' => '#000000',
                'blue' => '#0000FF',
                'fuchsia' => '#FF00FF',
                'gray' => '#808080',
                'green' => '#008000',
                'lime' => '#00FF00',
                'maroon' => '#800000',
                'navy' => '#000080',
                'olive' => '#808000',
                'purple' => '#800080',
                'red' => '#FF0000',
                'silver' => '#C0C0C0',
                'teal' => '#008080',
                'white' => '#FFFFFF',
                'yellow' => '#FFFF00',
            ];

            // Return value
            $closest_color = '';

            // Distance to color
            $distance = 0.23;

            // Search matching
            foreach( $colors as $name => $hex ) {
                $color1 = new \ImagickPixel($most_used['color']);
                $color2 = new \ImagickPixel( $hex );

                if( $color1->isPixelSimilar( $color2, $distance ) ) {
                    $closest_color = $color2->getColor();
                    $closest_color = Color::fromRgbToInt( $closest_color );
                    $closest_color = Color::fromIntToHex( $closest_color );
                }
            }

            // Load view with data
//            return view('imgcolors.index')->with('color_code', $most_used['color'])->with('table_colors', $colors)->with('closest_color', $closest_color)->with('img', $image->basename);

            return response()->json([
                'message'        => 'Image has uploaded successfully',
                'img_name'       => $image->basename,
                'img_file'       => '<img src="'.config('filesystems.imagescolors') .'/'
                    .$image->basename. '" class="img-thumbnail"
            width="300" />',
                'table_colors'   => $colors,
                'predominant_color' => $most_used['color'],
                'closest_color'  => $closest_color,
                'class_name'     => 'alert-success'
            ]);

        } else {
            return response()->json([
                'message'        => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name'     => 'alert-danger'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageStoreRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Asks for delete confirmation before destroy resource from DB.
     *
     */
    public function delete()
    {
        //
    }
}
