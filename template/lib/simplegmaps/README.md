# simplegmaps v2.5.0

simplegmaps - Add google maps to your web without knowing squat about JavaScript

## Background

SimpleGMaps is a javascript library for presenting Google Maps without the need for writing any js!

SimpleGMaps is an easy way to present Google Maps on your website. You don't need to know complex javascript, or javascript at all to know how to work this. Just follow the examples and add simple HTML markup. SimpleGMaps takes care of the rest!

With version 2 of SimpleGMaps now runs on vanilla javascript. No frameworks required!

Meet simplegmaps!

## Features

- Display one or multiple markers on your map
- Add info windows to markers with custom html markup
- Display routes on your map
- Support for traffic layers
- Support for automatic geo location
- Support for bicycle route layer
- Support for custom marker icons
- Support for geo location on demand, by clicking a button for example
- Custom InfowWindow - Position and style your own custom infowindow.
- AutoComplete for searching places and addresses.

## Browser Support

- Google Chrome
- MS Edge
- Firefox
- Safari 15+

### [View demo](http://subzane.github.io/simplegmaps/)

##Installation

```
yarn add simplegmaps-js
```

## Setup

```html
<!-- You'll need access to google maps api -->
<script
	src="https://maps.googleapis.com/maps/api/js?key=YOUR-API-KEY-HERE&libraries=places"
	type="text/javascript"
></script>
<!-- and you'll need to include simplegmaps of course! -->
<script src="simplegmaps.js"></script>
```

## Usage

```javascript
simplegmaps.init({
	container: "#id_of_your_div",
});
```

### Settings and Defaults

```javascript
var defaults = {
	debug: false,
	GeoLocation: false,
	ZoomToFitBounds: true,
	jsonsource: false, // if set to "false". Load from HTML markup.
	AutoComplete: false,
	AutoCompleteOptions: {
		// Supported types (https://developers.google.com/places/supported_types#table3)
		types: ["geocode"],
		// Country Codes (ISO 3166-1 alpha-2): https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
		// https://developers.google.com/maps/documentation/javascript/places-autocomplete?hl=en
		componentRestrictions: {
			country: "se",
		},
		moveMap: false,
		setMarker: false,
	},
	MapOptions: {
		draggable: true,
		zoom: 7,
		center: "55.604981,13.003822",
		scrollwheel: false,
		streetViewControl: false,
		panControl: true,
		zoomControl: true,
		zoomControlOptions: {
			style: "DEFAULT",
		},
	},

	onInit: function () {},
	onDestroy: function () {},

	onDrawMap: function () {},

	onSearchInit: function () {},
	onSearchComplete: function () {},
	onSearchFail: function () {},

	onZoomToFitBounds: function () {},
	onPlaceChanged: function () {},

	onDirectionsInit: function () {},
	onRouteComplete: function () {},
	onRouteError: function () {},

	onJSONConnectionFail: function () {},
	onJSONLoadFail: function () {},
	onJSONLoadSuccess: function () {},
};
```

- `debug`: Activate or deactive debug messages in console.
- `cluster`: Activate or deactive Marker Clustering.
- `ClusterImagePathPrefix`: Path prefix to images needed for Marker Clustering. Default 'img/markercluster/m',
- `jsonsource`: Path to externa JSON-file with map marker data. Provide path here or as a data-attribute. data-attribute will override this property.
- `GeoLocation`: Active or deactive automatic geolocation. Default false (inactive)
- `AutoComplete`: Active or deactive autocomplete search.
- `AutoCompleteOptions`: [AutoCompleteOptions](https://developers.google.com/places/supported_types#table3)
- `ZoomToFitBounds`: Will auto zoom the map to fit all markers within bounds. Setting this to true will disable the user of "zoom" in MapOptions, disable this to set your own zoom level. Default true (active)
- `MapOptions`: [Google Maps MapOptions](https://developers.google.com/maps/documentation/javascript/reference?csw=1#MapOptions)
- `getRouteButton`: ID of the button used to submit the route to the map
- `getTravelMode`: ID of the select element to hold the travelmode data
- `getFromAddress`: ID of the input element to hold the address to set the route start point
- `externalLink`: ID of the link element to be used when targeting a button to open up the map in a new tab. On mobile devices either Apple Maps or Google Maps app is opened instead.
- `defaultTravelMode`: The default travel mode is nothing else specified. Choose between DRIVING, WALKING or BICYCLING
- `multipleInfoWindows`: Set to true to allow opening multiple infoWindows at the same time.

#### Events

- `onInit`: Triggers when plugin has initialized.
- `onDestroy`: Triggers when plugin has been destroyed.
- `onSearchInit`: Triggers when search has initialized.
- `onSearchComplete`: Triggers when a search has been completed.
- `onSearchFail`: Triggers when a search has failed.
- `onZoomToFitBounds`: Triggers when ZoomToFitBounds has run.
- `onPlaceChanged`: Triggers when map has been moved.
- `onDirectionsInit`: Triggers when Directions has initialized.
- `onRouteComplete`: Triggers when a route has been routed.
- `onRouteError`: Triggers when a routed rout has failed to rout (yeah!).
- `onJSONConnectionFail`: Triggers when plugin is unable to conntect to the JSON url. For example: The JSON url is unreachable.
- `onJSONLoadFail`: Triggers when the JSON-data is unable to load. For example when the JSON-string is corrupt.
- `onJSONLoadSuccess`: Triggers when the JSON-data has successfully been loaded.

You can also use Google Maps native events found here: https://developers.google.com/maps/documentation/javascript/events
Attach them like this: simplegmaps.map.addListener('bounds_changed', function (event) {});

### Simple example. Adding two markers to a map

You can use an address or latitude and longitude to position a marker on a map using the `data` attribute.

```html
<div id="simplegmaps-1">
	<div
		class="map-marker"
		data-title="Lorem ipsum"
		data-latlng="55.5897407,13.012268899999981"
	></div>
	<div
		class="map-marker"
		data-title="Remi"
		data-address="Remi 145 W 53rd St, New York, NY, United States"
	></div>
</div>
```

```javascript
simplegmaps.init({
	container: "#simplegmaps-1",
});
```

### More examples on how to use SimpleGMaps.

You can find more examples on how to implement this plugin on the demo website

http://subzane.github.io/simplegmaps/

## changelog

#### 2.5.0

- CHANGE: Uses Gulp instead of Grunt
- CHANGE: Links to ClusterMarker script instead of including it in the project

#### 2.4.0

- NEW: Added option to enable bounce Animation on marker click-event. View demo for demonstration and source code.
- NEW: Added option to swap marker icon when selected. View demo for demonstration and source code.

#### 2.3.3

- FIX: Fixed url to ClusterMarker
- FIX: Fixed api key

#### 2.3.2

- OTHER: Replaced Bower with Yarn
- Moved script to Yarn

#### 2.3.1

- FIX: Fixes bug with custom marker icon.
- FIX: CSS styling to directions for demo
- OTHER: Adjustments to environment

#### 2.3.0

- NEW: Route turn by turn description can now be displayed.

#### 2.1.0

- NEW: New option `multipleInfoWindows`: Set to true to allow opening multiple infoWindows at the same time.

#### 2.1.0

- NEW: Added support for [Marker Clustering](https://developers.google.com/maps/documentation/javascript/marker-clustering). To use this feature set "cluster" to true when initializing.
- UPDATE: There's a geolocation limit on how many addresses you can requests during a short period of time. Try not to over use it if possible. If the requests are too many, you'll se a warning in the console "google.maps.GeocoderStatus.OVER_QUERY_LIMIT" and the affected markers will be ignored.

#### 2.0.1-beta

- FIX: Adress for demo "Single marker by address" had stopped working.

#### 2.0.0-beta

- NEW: Rewritten using only vanilla JavaScript.
- NEW: Now supports placing markers using a JSON-data feed as source.
- NEW: Better support for native google maps events.
- NEW: Easier to extend.
- CHANGE: Dropped support for IE8-9
- CHANGE: Dropped support for Safari 6-7

#### 1.1.3

- FIX: When using custom icons together with longitude/latitude positioning the icons where places wrong. This has now been resolved.

#### 1.1.2

- FIX: Option `ZoomToFitBounds` wasn't used properly, preventing custom zoom.

#### 1.1.1

- FIX: Method `setGeoLocation` wasn't public.

#### 1.1.0

- NEW: Added option `ZoomToFitBounds`: Will auto zoom the map to fit all markers within bounds. Setting this to true will disable the user of "zoom" in MapOptions, disable this to set your own zoom level. Default true (active)
- NEW: Added `data` attribute to control where the map center will be. Use `data-center="true"` on the marker you wish the map to center on. Can only be used if `ZoomToFitBounds` is set to false.

#### 1.0.2

- Fixed bug with map links on Android.

#### 1.0.1

- Fixed bug with map links

#### 1.0.0

- Added support for autocomplete for routing address. By @jhnsndstrm
- Added search for address with and without autocomplete. By @jhnsndstrm

#### 0.9.0

- Added support for retina custom markers. Check out the demo 'Single marker with custom icon' for an example.

#### 0.8.0

- Added functions for creating native map links for iPhone, Android, Windows Phone and Desktop.

#### 0.7.0

- Extends routing functionality to provide [DirectionsRequest Options](https://developers.google.com/maps/documentation/javascript/3.19/reference#DirectionsService)

#### 0.6.0

- Removed weather layer because of [Google Maps deprication](https://developers.google.com/maps/documentation/javascript/examples/layer-weather)

#### 0.5.0

- Added custom info windows

#### 0.4.0

- Added support for custom marker icons.
- Added toggle function for bicycle layer

#### 0.3.2

- FIX: Error occured when no markers was placed.
- FIX: MapOptions.Center did work as expected.
- FIX: Noticed that the zoom property of MapOptions was mandatory. Added fallback to default zoom setting.

#### 0.3.1

- FIX: Will no longer autofit when no markers has been added.

#### 0.3.0

- Added support for traffic layer
- Added support for weather layer
- Added support for automatic geo location
- Added support for geo location on demand (by clicking a button for example)
- Added example with snazzy maps

#### 0.2.0

First public release.
