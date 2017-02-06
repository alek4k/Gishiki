<?php
/**************************************************************************
Copyright 2017 Benato Denis

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*****************************************************************************/

namespace Gishiki\CLI;

/**
 * A class to emulate the C# System.Console class.
 *
 * @author Benato Denis <benato.denis96@gmail.com>
 */
abstract class Console
{
    private static $foreground_color = ConsoleColor::off;
    private static $background_color = ConsoleColor::off;
    
    /**
     * Write to the standard output without printing a newline.
     *
     * @param mixed $what what will be printed out
     */
    public static function Write($what)
    {
        $str = '';

        switch (strtolower(gettype($what))) {
            case 'boolean':
                $str = ($what) ? 'true' : 'false';
                break;

            case 'null':
                $str = 'null';
                break;

            case 'array':
                foreach ($what as $element) {
                    self::Write($element);
                }
                break;

            default:
                $str = ''.$what;
        }
        
        printf("\033[" . self::$background_color . "m\033[" . self::$foreground_color . "m" . $str . "\033[0m");
    }

    /**
     * Write to the standard output printing a newline afterward.
     *
     * @param mixed $what what will be printed out
     */
    public static function WriteLine($what)
    {
        self::Write($what);
        
        //print the newline
        self::Write("\n");
    }
    
    /**
     * Set the foreground color of the console.
     *
     * @param mixed $color the console color code to use
     */
    public static function ForegroundColor($color)
    {
        self::$foreground_color = $color;
    }
    
    /**
     * Set the background color of the console.
     *
     * @param mixed $color the console color code to use
     */
    public static function BackgroundColor($color)
    {
        self::$background_color = $color;
    }
    
    /**
     * Reset the foreground and background colors of the console to the default values.
     */
    public static function ResetColor()
    {
        self::$foreground_color = ConsoleColor::off;
        self::$background_color = ConsoleColor::off;
    }
}

/**
 * A class to emulate the C# System.ConsoleColor class.
 */
abstract class ConsoleColor
{
    const off        = 0;
    const bold       = 1;
    const italic     = 3;
    const underline  = 4;
    const blink      = 5;
    const inverse    = 7;
    const hidden     = 8;
    const black      = 30;
    const red        = 31;
    const green      = 32;
    const yellow     = 33;
    const blue       = 34;
    const magenta    = 35;
    const cyan       = 36;
    const white      = 37;
    
    const black_bg   = 40;
    const red_bg     = 41;
    const green_bg   = 42;
    const yellow_bg  = 43;
    const blue_bg    = 44;
    const magenta_bg = 45;
    const cyan_bg    = 46;
    const white_bg   = 47;
}

?>
