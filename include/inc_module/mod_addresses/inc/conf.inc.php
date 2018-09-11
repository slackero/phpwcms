<?php

// define cmsGo! Google Maps API Key
if(strpos(CMSGO_URL, 'pixel-points.ch')) {

	// http://pixel-points.ch
	define('MAPS_API_KEY', 'ABQIAAAAfy0PGx2vBlKwNImE18I6BhTZqbs9IsA21PGJjmj6f8BuIZ_TvhSwQCjs20Nu8QUcsBfUrV65kQ3haQ');

} elseif(strpos(CMSGO_URL, 'cmsgo.ch')) {

	// http://cmsgo.ch
	define('MAPS_API_KEY', 'ABQIAAAAfy0PGx2vBlKwNImE18I6BhT8F4slFiDEhwW2PJQ7rXI_Uoq9bhTdJU6AKBSTF2AHkFvukhv3e04xPA');

} elseif(strpos(CMSGO_URL, 'dev01.ddnss.de')) {

	// Development Google Maps API Key
	define('MAPS_API_KEY', 'ABQIAAAAfy0PGx2vBlKwNImE18I6BhRzo2R5nkw4YQSy5cv1rj7DJ3WmSBRNNwQjgbGEoCPWxUQdjs3h97mIgw');

} else {

	// Live Site Google Maps API Key
	define('MAPS_API_KEY', 'ABQIAAAAykBpYpN-NbVJIr7NALuw8BRpghRB1DaVJfTgfNwaLuT8G_hCBRRszz9Fo1SyqMQKqRZTI1jpAZ8pFg');

}
