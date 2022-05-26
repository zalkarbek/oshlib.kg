<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class AppAPIController extends AppBaseController
{
    public function redirectToAppMarket(Request $request)
    {
        $redirectUrl = '';

        $os = detectOS();
        switch($os){
            case 'iPhone':
            case 'iPad':
            case 'iPod':
                $redirectUrl = 'https://apps.apple.com/us/app/el-kitep/id1623289990';
                break;
            // case 'webOS': /*do something...*/ break;
            case 'Android':
            default: $redirectUrl = 'https://play.google.com/store/apps/details?id=kg.itadis.oshlib';
        }

        return redirect($redirectUrl);
    }
}
