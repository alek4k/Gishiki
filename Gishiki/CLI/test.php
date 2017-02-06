<?php

namespace Gishiki\CLI;
include 'Console.php';

Console::WriteLine("This is a line with the default color");

$pickColor = ConsoleColor::red;

Console::ForegroundColor($pickColor);
Console::WriteLine("This is a line with red characters");

Console::ForegroundColor(ConsoleColor::blue);
Console::BackgroundColor(ConsoleColor::yellow_bg);
Console::WriteLine("This is a line with blue characters and yellow background");

Console::ResetColor();

Console::WriteLine("This is a line with the restored default color");

?>
