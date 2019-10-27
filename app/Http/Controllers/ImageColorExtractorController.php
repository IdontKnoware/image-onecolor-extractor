<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Intervention\Image\Response;
use League\ColorExtractor\Color;
use League\ColorExtractor\Palette;

class ImageColorExtractorController extends Controller
{
    /**
     * Main and unique view of the app
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Colors table
        $colors = [ 'aqua'      => '#00FFFF',
                    'black'     => '#000000',
                    'blue'      => '#0000FF',
                    'fuchsia'   => '#FF00FF',
                    'gray'      => '#808080',
                    'green'     => '#008000',
                    'lime'      => '#00FF00',
                    'maroon'    => '#800000',
                    'navy'      => '#000080',
                    'olive'     => '#808000',
                    'purple'    => '#800080',
                    'red'       => '#FF0000',
                    'silver'    => '#C0C0C0',
                    'teal'      => '#008080',
                    'white'     => '#FFFFFF',
                    'yellow'    => '#FFFF00' ];

        return view( 'imgcolors.index' )->with( 'table_colors', $colors );
    }

    /**
     * Extracts predominant color (only that one) of an image in hexadecimal value.
     * Compare extracted color with 16 colors array and return closest one.
     *
     * E.g: Extracted color #9F978A is closest to #C0C0C0 of the $colors array.
     *
     * @param ImageStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ImagickPixelException
     */
    public function extractImgColor(ImageStoreRequest $request)
    {
        // File it's an image
        if( $request->validated() ) {

            $img_path = $request->file( 'input_img' )->store( config( 'filesystems.imagescolors' ) );

            // Creates copy of image
            $image = Image::make( $img_path )->resize( 300,300 );

            // Obtains an array with complete palette of the image,
            // but they not formatted in RGB or Hex
            $raw_palette = Palette::fromFilename( $img_path );

            // Get most used color from the palette (Not formatted yet)
            $raw_most_used_color = $raw_palette->getMostUsedColors( 2 );

            // Constructs an array with the key 'color' and value 'pixels':
            // 'color':  The most used color code of the image, parsed to Hexadecimal
            // 'pixels': Counts total pixels of that predominant color has got the image.
            $dominant_color = [];

            foreach( $raw_most_used_color as $color => $count ) {
                $dominant_color = [ 'color'  => Color::fromIntToHex($color),
                                    'pixels' => $count ];
            }

            // Colors which most used color of the image will be compared
            $colors = [ 'aqua'      => '#00FFFF',
                        'black'     => '#000000',
                        'blue'      => '#0000FF',
                        'white'     => '#FFFFFF',
                        'fuchsia'   => '#FF00FF',
                        'gray'      => '#808080',
                        'teal'      => '#008080',
                        'green'     => '#008000',
                        'lime'      => '#00FF00',
                        'maroon'    => '#800000',
                        'silver'    => '#C0C0C0',
                        'navy'      => '#000080',
                        'olive'     => '#808000',
                        'purple'    => '#800080',
                        'red'       => '#FF0000',
                        'yellow'    => '#FFFF00' ];

            // The closest hex color from the $color array
            $closest_color = '';

            // Distance to $color values
            $distance = 0.2;

            // Makes the comparison between most used color and $color array
            foreach( $colors as $name => $hex ) {
                $color1 = new \ImagickPixel( $dominant_color['color'] );
                $color2 = new \ImagickPixel( $hex );

                if( $color1->isPixelSimilar( $color2, $distance ) ) {
                    $closest_color = $color2->getColor();
                    $closest_color = Color::fromRgbToInt( $closest_color );
                    $closest_color = Color::fromIntToHex( $closest_color );
                }
            }

            // JSONize response data
            $response = response()->json( [
                'message'           => 'Image has uploaded successfully',
                'img_name'          => $image->basename,
                'img_file'          => '<img id="img_file" src="'.config('filesystems.imagescolors')
                                                                 .'/' .$image->basename
                                                                 . '" class="img-thumbnail"width="300" />',
                'table_colors'      => $colors,
                'dominant_color'    => $dominant_color['color'],
                'closest_color'     => $closest_color,
                'class_name'        => 'alert-success'
            ] );

            // Frees memory associated with the current image instance before the PHP script ends
            $image->destroy();

            // Return response to AJAX call
            return $response;
        }
    }
}
