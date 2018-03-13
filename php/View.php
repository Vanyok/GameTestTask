<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 13.03.18
 * Time: 13:56
 */


class View
{

    function generate($content_view, $template_view, $data = null)
    {

        if(is_array($data)) {
            // convert array to vars
            extract($data);
        }


        include 'views/'.$template_view;
    }
}
