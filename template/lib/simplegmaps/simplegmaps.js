(function (root, factory) {
	if (typeof define === 'function' && define.amd) {
		define([], factory(root));
	} else if (typeof exports === 'object') {
		module.exports = factory(root);
	} else {
		root.simplegmaps = factory(root);
	}
})(typeof global !== 'undefined' ? global : this.window || this.global, function (root) {

	'use strict';

	//
	// Variables
	//

	var simplegmaps = {}; // Object for public APIs
	var supports = !!document.querySelector && !!root.addEventListener; // Feature test
	var settings;
	var infoWindows = [];
	var el;

	var bicycleLayer, trafficLayer, transitLayer = false;

	var TravelModes = {
		Driving: google.maps.TravelMode.DRIVING,
		Bicycling: google.maps.TravelMode.BICYCLING,
		Transit: google.maps.TravelMode.TRANSIT,
		Walking: google.maps.TravelMode.WALKING
	};

	var UnitSystems = {
		Metric: google.maps.UnitSystem.METRIC,
		Imperial: google.maps.UnitSystem.IMPERIAL
	};

	var DirectionsRequest = {
		travelMode: TravelModes.Driving,
		unitSystem: UnitSystems.Metric
	};

	var directionsDisplay = new google.maps.DirectionsRenderer({
		draggable: true
	});

	var geocoder = null;
	var map = null;
	var mapdata = null; // Element copy. Used to read map settings
	var markerData = {
		markers: []
	};
	var markers = [];
	var AutoComplete = false;

	// Default settings. zoom and center are required to render the map.
	var defaults = {
		debug: false,
		multipleInfoWindows: false,
		cluster: false,
		ClusterImagePathPrefix: 'img/markercluster/m',
		geolocateLimit: 10,
		geolocateDelay: 100,
		GeoLocation: false,
		ZoomToFitBounds: true,
		jsonsource: false, // if set to "false". Load from HTML markup.
		AutoComplete: false,
		AutoCompleteOptions: {
			// Supported types (https://developers.google.com/places/supported_types#table3)
			types: ['geocode'],
			// Country Codes (ISO 3166-1 alpha-2): https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
			// https://developers.google.com/maps/documentation/javascript/places-autocomplete?hl=en
			componentRestrictions: {
				'country': 'se'
			},
			moveMap: false,
			setMarker: false
		},
		MapOptions: {
			draggable: true,
			zoom: 7,
			center: '55.604981,13.003822',
			scrollwheel: false,
			streetViewControl: false,
			panControl: true,
			zoomControl: true,
			zoomControlOptions: {
				style: 'DEFAULT'
			}
		},
		iOSAppleMapLink: 'http://maps.apple.com/',
		iOSGoogleMapLink: 'comgooglemaps://',
		AndroidMapLink: 'https://maps.google.se/maps',
		WP7MapLink: 'maps:',
		DesktopMapLink: 'http://www.google.com/maps',

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
		onJSONLoadSuccess: function () {}
	};
	/*
	You can also use Google Maps native events found here: https://developers.google.com/maps/documentation/javascript/events
	Attach them like this: simplegmaps.map.addListener('bounds_changed', function (event) {});
	*/



	//
	// Private Methods
	//

	var drawMap = function () {
		geocoder = new google.maps.Geocoder();
		mapdata = el.cloneNode(true);
		map = new google.maps.Map(el, settings.MapOptions);
		map.addListener('idle', function() {
			hook('onDrawMap');
		});
	};

	var getMapMarkersFromJSON = function (done) {
		if (settings.jsonsource === false) {
			return false;
		}
		var request = new XMLHttpRequest();

		request.open('GET', settings.jsonsource, true);

		request.onload = function () {
			if (request.status >= 200 && request.status < 400) {
				// Success!
				var JSONmarkers = parseJSON(request.response);
				var markerListlength = JSONmarkers.length;
				JSONmarkers.forEach(function(marker) {
					fetchPosition(marker, function (response) {
						if (response === false) {
							log('fetchPosition response: '+response);
							markerListlength--;
							return;
						} else {
							log('fetchPosition response: OK: '+response);
							marker.position = response;
							createMarkerIcon(marker.iconpath, marker.iconpath2x, function (markerIcon) {
								marker.icon = markerIcon;
								markerData.markers.push(marker);
								// Do not leave until all markers has been loaded.
								log(markerData.markers.length +':'+markerListlength);
								if (markerData.markers.length === markerListlength) {
									done();
								}
							});
						}
					});
				});
				hook('onJSONLoadSuccess');
			} else {
				// We reached our target server, but it returned an error
				if (settings.debug) {
					hook('onJSONLoadFail');
					console.log('An error occured when trying to load data from ' + settings.datasource);
				}
			}
		};
		request.onerror = function () {
			// There was a connection error of some sort
			if (settings.debug) {
				hook('onJSONConnectionFail');
				console.log('A connection error occured when trying to access ' + settings.datasource);
			}
		};
		request.send();
	};

	var parseJSON = function (jsonString) {
		try {
			var o = JSON.parse(jsonString);
			// Handle non-exception-throwing cases:
			// Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
			// but... JSON.parse(null) returns 'null', and typeof null === "object",
			// so we must check for that, too.
			if (o && typeof o === 'object' && o !== null) {
				return o;
			}
		} catch (e) {}
		console.warn('Error parsing JSON file');
		return false;
	};

	var getMapMarkersFromMarkup = function (done) {
		var markerList = mapdata.querySelectorAll('.map-marker');
		var markerNodes = Array.prototype.slice.call(markerList,0);
		var markerListlength = markerList.length;
		log(markerList);
		if (markerList.length === 0) {
			done();
		}
		//forEach(markerList, function (markerElement, value) {
		markerNodes.forEach(function(markerElement) {
			var marker = {};
			marker.title = markerElement.getAttribute('data-title');
			if (markerElement.querySelector('.map-infowindow')) {
				marker.infoWindowContent = markerElement.querySelector('div.map-infowindow').parentElement.innerHTML;
				marker.CustominfoWindowContent = null;
			} else if (markerElement.querySelector('.map-custom-infowindow')) {
				marker.CustominfoWindowContent = markerElement.querySelector('.map-custom-infowindow').parentElement.innerHTML;
				marker.infoWindowContent = null;
			}

			if (markerElement.hasAttribute('data-center')) {
				marker.center = true;
			} else {
				marker.center = false;
			}

			if (markerElement.hasAttribute('data-icon')) {
				marker.iconpath = markerElement.getAttribute('data-icon');
			} else {
				marker.iconpath = null;
			}
			if (markerElement.hasAttribute('data-icon2x')) {
				marker.iconpath2x = markerElement.getAttribute('data-icon2x');
			} else {
				marker.iconpath2x = null;
			}

			if (markerElement.hasAttribute('data-latlng')) {
				marker.latlng = markerElement.getAttribute('data-latlng');
			} else {
				marker.latlng = null;
			}
			if (markerElement.hasAttribute('data-address')) {
				marker.address = markerElement.getAttribute('data-address');
			} else {
				marker.address = null;
			}
			if (marker.address === null && marker.latlng === null) {
				log('No address or longitude/latitude found in markup. Removing marker.');
				//remove marker
				markerListlength--;
				return;
			}

			fetchPosition(marker, function (response) {
				if (response === false) {
					log('fetchPosition response: '+response);
					markerListlength--;
					return;
				} else {
					log('fetchPosition response: OK: '+response);
					marker.position = response;
					createMarkerIcon(marker.iconpath, marker.iconpath2x, function (markerIcon) {
						marker.icon = markerIcon;
						markerData.markers.push(marker);
						// Do not leave until all markers has been loaded.
						log(markerData.markers.length +':'+markerListlength);
						if (markerData.markers.length === markerListlength) {
							done();
						}
					});
				}
			});

		});
	};

	var fetchPosition = function (marker, callback) {
		if (hasValue(marker.latlng)) {
			callback(parseLatLng(marker.latlng));
		} else if (hasValue(marker.address)) {
			setTimeout(getLatLngPosition(marker, function (status, response) {
				log('status: ' + status);
				log('response: ' + response);
				if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
					console.warn('google.maps.GeocoderStatus.OVER_QUERY_LIMIT');
					log('-- google.maps.GeocoderStatus.OVER_QUERY_LIMIT');
					log('-- Unable to geolocate address. Removing marker.');
					callback(false);
				} else if (status === google.maps.GeocoderStatus.OK) {
					callback(response);
				} else {
					marker.latlng = null;
					log(status);
					log('No address or longitude/latitude located from address. Removing marker.');
					//remove marker
					callback(false);
				}
			}), settings.geolocateDelay);
		}
	};

	var placeMarkers = function () {
		log('-- placeMarkers --');

		// Prepare marker data
		forEach(markerData.markers, function (markerObj, value) {
			log(markerObj);
			var markerOptions = {};

			// Create new marker object
			var marker = new google.maps.Marker({
				title: markerObj.title,
				center: markerObj.center
			});
			markers.push(marker);

			// Create infowindow object
			marker = setInfoWindow(marker, markerObj);

			markerOptions.position = markerObj.position;
			markerOptions.icon = markerObj.icon;
			marker.setOptions(markerOptions);

			if (settings.cluster === false) {
				marker.setMap(map);
			}
			log(marker.title);
			//log('lat: ' + marker.getPosition().lat());
			//log('lng: ' + marker.getPosition().lng());
		});

		log('--- Cluster: ' + settings.cluster);
		if (settings.cluster === true) {
			// Add a marker clusterer to manage the markers.
			new MarkerClusterer(map, markers, {
				imagePath: settings.ClusterImagePathPrefix
			});
		}

		var bounds = new google.maps.LatLngBounds();
		if (markers.length > 0) {
			if (settings.ZoomToFitBounds === true) {
				log('ZoomToFitBounds');
				zoomToFitBounds();
			} else {
				log('NO - ZoomToFitBounds');
				// Find all markers that has the value center==true
				var centeredmarker = markers.filter(function (obj) {
					return (obj.center === true || obj.center === 'true');
				});

				if (hasValue(centeredmarker)) {
					log('centeredmarker:', centeredmarker);
					map.setCenter(centeredmarker[0].getPosition());
				} else {
					map.setCenter(markers[0].getPosition()); // Use the first marker to center the map.
				}
			}
		} else if (!settings.MapOptions.center) {
			map.setCenter(bounds.getCenter());
		}


		if (settings.GeoLocation) {
			log('geolocation');
			geoLocation(map);
		} else {
			log('no geolocation');
		}
	};

	var closeInfoWindows = function () {
		for (var i=0;i<infoWindows.length;i++) {
	     infoWindows[i].close();
	  }
	};

	/**
	 * Sets InfoWindow for the selected marker. Default or Custom InfoWindow
	 * @param {google.maps.Marker} marker
	 * @param {Object} markerData
	 */
	var setInfoWindow = function (marker, markerData) {
		if (markerData.infoWindowContent) {
			marker.addListener('click', function () {
				if (settings.markerBounce === true) {
					toggleBounce(marker);
				}
				if (settings.multipleInfoWindows === false) {
					if (infoWindows.length > 0) {
						closeInfoWindows();
					}
				}
				var infowindow = new google.maps.InfoWindow({
					content: markerData.infoWindowContent
				});
				infowindow.open(map, marker);
				infoWindows.push(infowindow);
			});
		} else if (markerData.CustominfoWindowContent) {
			var customInfowindow = markerData.CustominfoWindowContent;
			google.maps.event.addListener(marker, 'click', function () {
				if (settings.markerBounce === true) {
					toggleBounce(marker);
				}
				var ciw = document.querySelector('#simplegmaps-c-iw');
				if (ciw !== null) {
					ciw.parentNode.removeChild(ciw);
				}
				el.insertAdjacentHTML('afterend', '<div id="simplegmaps-c-iw"></div>');
				document.querySelector('#simplegmaps-c-iw').innerHTML = customInfowindow;
				document.querySelector('#simplegmaps-c-iw .close').addEventListener('click', function (event) {
					event.preventDefault();
					var ciw = document.querySelector('#simplegmaps-c-iw');
					if (ciw !== null) {
						ciw.parentNode.removeChild(ciw);
					}
				});
			});
		} else {
			marker.addListener('click', function () {
				if (settings.markerBounce === true) {
					toggleBounce(marker);
				}
				if (settings.iconSwap.enabled === true) {
					toggleIcon(marker);
				}
			});
		}
		return marker;
	};

	var toggleBounce = function (markerObj) {
		if (typeof markerObj.getAnimation() === 'undefined' || markerObj.getAnimation() === null) {
			forEach(markers, function (m, value) {
				m.setAnimation(null);
			});
      markerObj.setAnimation(google.maps.Animation.BOUNCE);
    } else {
      markerObj.setAnimation(null);
    }
	};

	var toggleIcon = function (markerObj) {
		forEach(markers, function (m, value) {
			m.setIcon(null);
		});
		markerObj.setIcon(settings.iconSwap.activeIcon);
	};

	// Asynchronous method to fetch latitude and longitude from marker
	var getLatLngPosition = function (markerObj, callback) {
		if (hasValue(markerObj.latlng)) {
			callback(parseLatLng(markerObj.latlng));
		} else if (hasValue(markerObj.address)) {
			geocoder.geocode({
				'address': markerObj.address
			}, function (results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					callback(status, results[0].geometry.location);
				} else {
					if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
						callback(status, false);
          } else {
						callback(status, false);
          }
				}
			});
		}
	};

	var createMarkerIcon = function (icon, icon2x, callback) {
		var imageElement = new Image();

		// At least imagepath must be assigned. Return null if undefined.
		if ((icon === null && icon2x === null) || (icon === '' && icon2x === '')) {
			callback(null);
		} else {
			if (window.devicePixelRatio > 1.5) {
				if (typeof icon2x !== null && icon2x !== '') {
					imageElement.onload = function () {
						var markerIcon = {
							url: icon2x,
							size: new google.maps.Size(imageElement.naturalWidth / 2, imageElement.naturalHeight / 2),
							scaledSize: new google.maps.Size((imageElement.naturalWidth / 2), (imageElement.naturalHeight / 2)),
							origin: new google.maps.Point(0, 0)
						};

						callback(markerIcon);
					};
					imageElement.src = icon2x;
				} else {
					if (typeof icon !== null && icon !== '') {
						imageElement.onload = function () {
							var markerIcon = {
								url: icon,
								size: new google.maps.Size(imageElement.naturalWidth, imageElement.naturalHeight)
							};

							callback(markerIcon);
						};
						imageElement.src = icon;
					} else {
						callback(null);
					}
				}
			} else {
				if (typeof icon !== null && icon !== '') {
					imageElement.onload = function () {
						var markerIcon = {
							url: icon,
							size: new google.maps.Size(imageElement.naturalWidth, imageElement.naturalHeight)
						};

						callback(markerIcon);
					};
					imageElement.src = icon;
				} else {
					callback(null);
				}
			}
		}
	};

	// Zooms map enough to fit all markers. But not too much. Just enough to be perfect!
	//
	// Array: markers
	var zoomToFitBounds = function () {
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markers.length; ++i) {
			bounds.extend(markers[i].getPosition());
		}
		if (bounds.getNorthEast().equals(bounds.getSouthWest())) {
			var extendPoint1 = new google.maps.LatLng(bounds.getNorthEast().lat() + 0.002, bounds.getNorthEast().lng() + 0.002);
			var extendPoint2 = new google.maps.LatLng(bounds.getNorthEast().lat() - 0.002, bounds.getNorthEast().lng() - 0.002);
			bounds.extend(extendPoint1);
			bounds.extend(extendPoint2);
		}
		try {
			map.fitBounds(bounds);
			map.setCenter(bounds.getCenter());
			hook('onZoomToFitBounds');
		} catch (e) {
			log('zoomToFitBounds failed');
		} // Let's catch this possible error and do nothing about it. Noone will ever know.
	};

	// Takes a string with latlng in this format "55.5897407,13.012268899999981" and makes it into a latlng object
	var parseLatLng = function (latlngString) {
		var latlng;
		if (typeof latlngString === 'string') {
			var bits = latlngString.split(/,\s*/);
			latlng = new google.maps.LatLng(parseFloat(bits[0]), parseFloat(bits[1]));
		} else {
			latlng = latlngString;
		}
		return latlng;
	};

	var hasValue = function (input) {
		if (typeof input !== 'undefined' && input !== null && input !== '' && input.length > 0) {
			return true;
		} else {
			return false;
		}
	};

	var isAndroid = function () {
		if (navigator.userAgent.toLowerCase().indexOf('android') > -1) {
			return true;
		} else {
			return false;
		}
	};

	var isIOS = function () {
		if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPad/i)) || (navigator.userAgent.match(/iPod/i))) {
			return true;
		} else {
			return false;
		}
	};

	var isWindowsPhone = function () {
		if (navigator.userAgent.match(/Windows Phone/i)) {
			return true;
		} else {
			return false;
		}
	};

	var toggleTrafficLayer = function () {
		if ((trafficLayer) && (trafficLayer.map !== null)) {
			trafficLayer.setMap(null);
		} else {
			trafficLayer = new google.maps.TrafficLayer();
			trafficLayer.setMap(map);
		}
	};

	var toggleTransitLayer = function () {
		if ((transitLayer) && (transitLayer.map !== null)) {
			transitLayer.setMap(null);
		} else {
			transitLayer = new google.maps.TransitLayer();
			transitLayer.setMap(map);
		}
	};

	var toggleBicycleLayer = function () {
		if ((bicycleLayer) && (bicycleLayer.map !== null)) {
			bicycleLayer.setMap(null);
		} else {
			bicycleLayer = new google.maps.BicyclingLayer();
			bicycleLayer.setMap(map);
		}
	};

	var geoLocation = function () {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				map.setCenter(pos);
			}, function () {
				geolocationExceptionHandler(true);
			});
		} else {
			// Browser doesn't support Geolocation
			geolocationExceptionHandler(false);
		}
	};

	var geolocationExceptionHandler = function (errorFlag) {
		if (errorFlag) {
			log('SimpleGMaps: The Geolocation service failed.');
		} else {
			log('SimpleGMaps: Your browser doesn\'t support geolocation.');
		}
	};
	/**
	 * Callback hooks.
	 * Usage: In the defaults object specify a callback function:
	 * hookName: function() {}
	 * Then somewhere in the plugin trigger the callback:
	 * hook('hookName');
	 */
	var hook = function (hookName) {
		if (settings[hookName] !== undefined) {
			// Call the user defined function.
			// Scope is set to the jQuery element we are operating on.
			settings[hookName].call(el);
		}
	};

	/**
	 * Merge defaults with user options
	 * @private
	 * @param {Object} defaults Default settings
	 * @param {Object} options User options
	 * @returns {Object} Merged values of defaults and options
	 */
	var extend = function (defaults, options) {
		var extended = {};
		forEach(defaults, function (value, prop) {
			extended[prop] = defaults[prop];
		});
		forEach(options, function (value, prop) {
			extended[prop] = options[prop];
		});
		return extended;
	};

	/**
	 * A simple forEach() implementation for Arrays, Objects and NodeLists
	 * @private
	 * @param {Array|Object|NodeList} collection Collection of items to iterate
	 * @param {Function} callback Callback function for each iteration
	 * @param {Array|Object|NodeList} scope Object/NodeList/Array that forEach is iterating over (aka `this`)
	 */
	var forEach = function (collection, callback, scope) {
		if (Object.prototype.toString.call(collection) === '[object Object]') {
			for (var prop in collection) {
				if (Object.prototype.hasOwnProperty.call(collection, prop)) {
					callback.call(scope, collection[prop], prop, collection);
				}
			}
		} else {
			for (var i = 0, len = collection.length; i < len; i++) {
				callback.call(scope, collection[i], i, collection);
			}
		}
	};

	var log = function (message, data, type) {
		// Change log type if provided, else default to log
		type = typeof type !== 'undefined' ? type : 'log';

		// Check if debug is enabled
		if (settings.debug) {

			// Check for console
			if (window.console) {

				// Check for provided data
				if (typeof data !== 'undefined' && data !== null && data.length > 0) {
					console[type](message, data);
				} else {
					console[type](message);
				}
			}
		}
	};

	var bindAutoComplete = function (field, routeButton) {
		// Get the DOM-element
		var input = document.querySelector(field);
		// Assign autocomplete to variable to add event listener
		AutoComplete = new google.maps.places.Autocomplete(input, settings.autoCompleteOptions);

		// When event fires run callback function
		// If routing is desired trigger click event on ordinary Get route button
		if (hasValue(routeButton)) {
			AutoComplete.addListener('place_changed', function () {
				document.querySelector(routeButton).click();
			});
		} else {
			// Make a ordinary search
			AutoComplete.addListener('place_changed', onPlaceChanged);
		}
	};

	var onPlaceChanged = function () {
		var place = AutoComplete.getPlace();
		if (place.geometry) {
			if (settings.AutoCompleteOptions.moveMap) {
				map.panTo(place.geometry.location);
			}
			if (settings.AutoCompleteOptions.setMarker) {
				new google.maps.Marker({
					map: map,
					position: place.geometry.location
				});
			}
			hook('onPlaceChanged');
		}
	};

	var search = function (location) {
		geocoder.geocode({
			'address': location
		}, function (data, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				map.setCenter(data[0].geometry.location);
				new google.maps.Marker({
					map: map,
					position: data[0].geometry.location
				});
				hook('onSearchComplete');
			} else {
				log(status);
				hook('onSearchFail');
				// not found
			}
		});
	};

	var getMarkerAddress = function (marker, callback) {
		log(marker);
		geocoder.geocode({
			latLng: marker.getPosition()
		}, function (responses) {
			if (responses && responses.length > 0) {
				callback(responses[0].formatted_address);
			} else {
				callback(null);
			}
		});
	};

	/**
	 * Destroy the current initialization.
	 * @public
	 */
	simplegmaps.destroy = function () {

		// If plugin isn't already initialized, stop
		if (!settings) {
			return;
		}

		// @todo Undo any other init functions...

		// Remove event listeners
		//document.removeEventListener('click', eventHandler, false);

		hook('onDestroy');

		// Reset variables
		settings = null;
		map = null;
		mapdata = null;
		markerData = {
			markers: []
		};
		markers = [];
	};

	/**
	 * Initialize Plugin
	 * @public
	 * @param {Object} options§ User settings
	 */
	simplegmaps.init = function (options) {
		// feature test
		if (!supports) {
			return;
		}

		// Destroy any existing initializations
		simplegmaps.destroy();

		// Merge user options with defaults
		settings = extend(defaults, options || {});
		log(settings);
		el = document.querySelector(settings.container);

		if (settings.MapOptions.center) {
			settings.MapOptions.center = parseLatLng(settings.MapOptions.center);
		}

		if (!settings.MapOptions.zoom) {
			settings.MapOptions.zoom = 4;
		}

		if (el.hasAttribute('data-json')) {
			settings.jsonsource = el.getAttribute('data-json');
		}

		drawMap();


		// Unless there is a datasource specified, read markers from HTML markup
		log('init');
		if (settings.jsonsource === false) {
			log('getMapMarkersFromMarkup');
			getMapMarkersFromMarkup(function (done) {
				log('markers done');
				placeMarkers();
			});
		} else {
			log('getMapMarkersFromJSON');
			getMapMarkersFromJSON(function (done) {
				placeMarkers();
			});
		}


		hook('onInit');
	};

	//
	// Public APIs
	//
	simplegmaps.Markers = {
		get: function () {
			return markers;
		}
	};

	simplegmaps.Map = {
		get: function () {
			return map;
		}
	};

	simplegmaps.getMarkerAddress = function (marker, callback) {
		getMarkerAddress(marker, function(address) {
			callback(address);
		});
	};

	simplegmaps.Center = function () {
		var center = map.getCenter();
		google.maps.event.trigger(map, 'resize');
		map.setCenter(center);
	};

	simplegmaps.Search = {
		default: {
			input: '',
			eventButton: '',
			AutoComplete: false
		},
		init: function (options) {
			simplegmaps.Search.options = extend(simplegmaps.Search.default, options || {});

			if (simplegmaps.Search.options.AutoComplete) {
				bindAutoComplete(simplegmaps.Search.options.input);
			}

			document.querySelector(simplegmaps.Search.options.eventButton).addEventListener('click', function (event) {
				event.preventDefault();
				search(document.querySelector(simplegmaps.Search.options.input).value);
			});

			document.querySelector(simplegmaps.Search.options.input).addEventListener('keypress', function (event) {
				if (event.keyCode === 13) {
					search(document.querySelector(simplegmaps.Search.options.input).value);
				}
			});
			hook('onSearchInit');
		},
		initAutoComplete: function (options) {
			bindAutoComplete(options.input);
		},
		search: function (location) {
			search(location);
		}
	};

	simplegmaps.Directions = {
		TravelModes: {
			get: function () {
				return TravelModes;
			}
		},
		default: {
			AutoComplete: false,
			TravelMode: DirectionsRequest.TravelMode,
			UnitSystem: DirectionsRequest.UnitSystem,
			originInput: '',
			destination: '',
			eventButton: ''
		},
		init: function (routingOptions) {
			simplegmaps.Directions.options = extend(simplegmaps.Directions.default, routingOptions || {});
			DirectionsRequest.TravelMode = simplegmaps.Directions.options.TravelMode;
			DirectionsRequest.UnitSystem = simplegmaps.Directions.options.UnitSystem;

			document.querySelector(simplegmaps.Directions.options.eventButton).addEventListener('click', function (event) {
				simplegmaps.Directions.route({
					origin: document.querySelector(simplegmaps.Directions.options.originInput).value,
					destination: simplegmaps.Directions.options.destination
				});
			});
			if (simplegmaps.Directions.options.AutoComplete) {
				bindAutoComplete(simplegmaps.Directions.options.originInput, simplegmaps.Directions.options.eventButton);
			}
			hook('onDirectionsInit');
		},
		initAutoComplete: function (options) {
			settings.AutoComplete = true;
			bindAutoComplete(options.originInput, options.eventButton);
		},
		travelMode: {
			get: function () {
				return DirectionsRequest.TravelMode;
			},
			set: function (travelMode) {
				DirectionsRequest.TravelMode = travelMode;
			}
		},
		unitSystem: {
			get: function () {
				return DirectionsRequest.UnitSystem;
			},
			set: function (unitSystem) {
				DirectionsRequest.UnitSystem = unitSystem;
			}
		},
		route: function (directionsRequest) {
			DirectionsRequest = extend(DirectionsRequest, directionsRequest || {});
			var directionsService = new google.maps.DirectionsService();
			log(DirectionsRequest);
			directionsDisplay.setMap(map);
			// Writes direction to a panel
			if(settings.routeDescriptionContainer) {
				directionsDisplay.setPanel(document.querySelector(settings.routeDescriptionContainer));
			}

			directionsService.route(DirectionsRequest, function (response, status) {
				if (status === google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(response);
					hook('onRouteComplete');
				} else {
					log(status);
					hook('onRouteError');
				}
			});
		}
	};

	simplegmaps.TrafficLayer = {
		toggle: function () {
			toggleTrafficLayer();
		}
	};

	simplegmaps.TransitLayer = {
		toggle: function () {
			toggleTransitLayer();
		}
	};

	simplegmaps.BicycleLayer = {
		toggle: function () {
			toggleBicycleLayer();
		}
	};

	simplegmaps.GeoLocation = {
		set: function () {
			geoLocation();
		}
	};

	simplegmaps.getURL = {
		Android: function (address) {
			var url = settings.AndroidMapLink + '?q=' + address;
			return url;
		},
		ios: function (address) {
			var url = settings.iOSAppleMapLink + '?q=' + address;
			return url;
		},
		WindowsPhone: function (address) {
			var url = settings.WP7MapLink + address;
			return url;
		},
		Desktop: function (address) {
			var url = settings.DesktopMapLink + '?q=' + address;
			return url;
		},
		Native: function (address) {
			var url = '';
			if (isAndroid()) {
				url = simplegmaps.getURL.Android(address);
			} else if (isIOS()) {
				url = simplegmaps.getURL.ios(address);
			} else if (isWindowsPhone()) {
				url = simplegmaps.getURL.WindowsPhone(address);
			} else {
				url = simplegmaps.getURL.Desktop(address);
			}
			return url;
		}
	};

	return simplegmaps;
});
