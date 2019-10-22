<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageStoreRequest;
use Illuminate\Http\Request;
use App\ImageColorExtractor;
use Illuminate\Support\Facades\Storage;
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
    public function index()   {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( 'imgcolors.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageStoreRequest $request)
    {
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
                //dd($closest_color);
            }
        }

        // Freeup memory
        //$image->destroy();

        // Load view with data
        return view('imgcolors.show')->with('color_code', $most_used['color'])->with('table_colors', $colors)->with('closest_color', $closest_color)->with('img', $image->basename);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $imgcolor = ImageColorExtractor::findOrFail($id);

        return view( 'imgcolors.show', ['imgcolor' => $imgcolor] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
