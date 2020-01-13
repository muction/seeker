<?php
namespace Seeker\iXiGua\Core;

use Seeker\iXiGua\Core\Http;

class GetChannelList
{
    public function getChannelContent( $url ){
        $content = Http::get( $url );
        if( $content->getStatusCode() == 200){
            return json_decode( $content->getBody()->getContents() , true );
        }
    }

}
