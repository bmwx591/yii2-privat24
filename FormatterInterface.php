<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 19.11.16
 * Time: 3:44
 */

namespace bmwx591\privat24;


use bmwx591\privat24\request\Request;

interface FormatterInterface
{
    public function formate(Request $request);
}