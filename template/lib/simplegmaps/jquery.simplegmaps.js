/*! simplegmaps - v1.1.2 - 2016-01-21
* https://github.com/SubZane/simplegmaps
* Copyright (c) 2016 Andreas Norman; Licensed MIT */
(function ($) {
  // Change this to your plugin name.
  var pluginName = 'simplegmaps';

  /**
   * Plugin object constructor.
   * Implements the Revealing Module Pattern.
   */
  function Plugin(element, options) {
    // References to DOM and jQuery versions of element.
    var el = element;
    var $el = $(element);
    var Map = false;
    var trafficLayer = false;
    var bicycleLayer = false;
    var TravelModes = ['DRIVING', 'WALKING', 'BICYCLING', 'TRANSIT'];
    var autoComplete;

    // Extend default options with those supplied by user.
    options = $.extend({}, $.fn[pluginName].defaults, options);

    /**
     * Initialize plugin.
     */
    function init() {
      // Need to parse the latlng position
      if (options.MapOptions.center) {
        options.MapOptions.center = parseLatLng(options.MapOptions.center);
      }

      // If zoom property missing, add it, or else MapOptions will fail.
      if (!options.MapOptions.zoom) {
        options.MapOptions.zoom = 8;
      }

      // Draw map and set markers
      drawMap();

      if (($(options.getRouteButton).length > 0) && ($(options.getTravelMode).length > 0)) {
        setupRouting(this);
      }

      if ($(options.geoLocationButton).length > 0) {
        $(options.geoLocationButton).on('click', function (e) {
          e.preventDefault();
          geoLocation(Map.map);
        }.bind(this));
      }

      hook('onInit');
    }

    // Zooms map enough to fit all markers. But not too much. Just enough to be perfect!
    //
    // Array: markers
    function zoomToFitBounds(map, markers) {
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
      } catch (e) {} // Let's catch this possible error and do nothing about it. Noone will ever know.
    }

    // Takes a string with latlng in this format "55.5897407,13.012268899999981" and makes it into a latlng object
    function parseLatLng(latlngString) {
      var bits = latlngString.split(/,\s*/);
      $latlng = new google.maps.LatLng(parseFloat(bits[0]), parseFloat(bits[1]));
      return $latlng;
    }

    function bindMapLinkButton() {
      if ($(options.externalLink).length > 0) {
        var markerPosition = Map.markers[0].position.toString();
        var query = '?q=' + markerPosition;

        if (navigator.userAgent.toLowerCase().indexOf('android') > -1) {
          $(options.externalLink).attr('href', options.AndroidMapLink + query);
        } else if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
          $(options.externalLink).attr('href', options.AppleMapLink + query);
        } else {
          $(options.externalLink).attr('href', options.GenericMapLink + query);
        }
      }
    }

    function addMarkers () {

    }

    function drawMap() {
      var infoWindow = new google.maps.InfoWindow();
      var geocoder = new google.maps.Geocoder();
      var markers = [];
      var mapInData = $el.clone();
      var runAutoComplete = options.AutoComplete;

      // First bind autocomplete event, if autocomplete is set to true
      if(runAutoComplete){
        bindAutoComplete();
      }
      // If searchbox and search button are present bind events
      if (($(options.SearchButton).length > 0) && ($(options.SearchBox).length > 0)){
        $(options.SearchButton).on('click', function(e){
          e.preventDefault();
          var location = $(options.SearchBox).val();
          processTriggeredSearch(location, Map.map);
        });

        $(options.SearchBox).keypress(function(e) {
          if (e.which === 13) {
            var location = $(options.SearchBox).val();
            processTriggeredSearch(location, Map.map);
          }
        });
      }

      // Else if routing button and input are present
      else if(($(options.getRouteButton).length > 0) && ($(options.getFromAddress).length > 0)){
        $(options.getFromAddress).keypress(function(e) {
          if (e.which === 13) {
            $(options.getRouteButton).trigger('click');
          }
        });
      }

      var map = new google.maps.Map($el[0], options.MapOptions); // We need [0] to get the html element instead of jQery object
      console.log('map start');
      mapInData.find('div.map-marker').each(function (i) {
        if ($(this).attr('data-latlng')) {
          var currentMarkerData = $(this);
          console.log(currentMarkerData);
          getMarkerIcon(currentMarkerData.data('icon'), currentMarkerData.data('icon2x'), function(iconMarker) {
            var marker = new google.maps.Marker({
              map: map,
              title: currentMarkerData.data('title'),
              position: parseLatLng(currentMarkerData.data('latlng')),
              icon: iconMarker,
              center: currentMarkerData.data('center')
            });
            if (currentMarkerData.has('div.map-infowindow').length > 0) {
              var infowindow = new google.maps.InfoWindow({
                content: currentMarkerData.find('div.map-infowindow').parent().html()
              });
              google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
              });
            } else if (currentMarkerData.has('div.map-custom-infowindow').length > 0) {
              var customInfowindow = currentMarkerData.find('div.map-custom-infowindow').parent().html();
              google.maps.event.addListener(marker, 'click', function () {
                $('#simplegmaps-c-iw').remove();
                $('<div id="simplegmaps-c-iw"></div>').insertAfter($el);
                $('#simplegmaps-c-iw').html(customInfowindow);
                $('#simplegmaps-c-iw .close').on('click', function (event) {
                  event.preventDefault();
                  $('#simplegmaps-c-iw').remove();
                });
              });
            }

            markers.push(marker);
          });
        } else if ($(this).attr('data-address')) {
          var currentAddressMarkerData = $(this);
          geocoder.geocode({'address': currentAddressMarkerData.data('address')}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
              getMarkerIcon(currentAddressMarkerData.data('icon'), currentAddressMarkerData.data('icon2x'), function(iconMarker) {
                var marker = new google.maps.Marker({
                  map: map,
                  title: currentAddressMarkerData.data('title'),
                  position: results[0].geometry.location,
                  icon: iconMarker,
                  center: currentAddressMarkerData.data('center')
                });
                if (currentAddressMarkerData.has('div.map-infowindow').length > 0) {
                  var infowindow = new google.maps.InfoWindow({
                    content: currentAddressMarkerData.find('div.map-infowindow').parent().html()
                  });
                  google.maps.event.addListener(marker, 'click', function () {
                    infowindow.open(map, marker);
                  });
                } else if (currentAddressMarkerData.has('div.map-custom-infowindow').length > 0) {
                  var customInfowindow = currentAddressMarkerData.find('div.map-custom-infowindow').parent().html();
                  google.maps.event.addListener(marker, 'click', function () {
                    $('#simplegmaps-c-iw').remove();
                    $('<div id="simplegmaps-c-iw"></div>').insertAfter($el);
                    $('#simplegmaps-c-iw').html(customInfowindow);
                    $('#simplegmaps-c-iw .close').on('click', function (event) {
                      event.preventDefault();
                      $('#simplegmaps-c-iw').remove();
                    });
                  });
                }
                markers.push(marker);
              });
            }
          });

        }

      });

      // We need to wait for Google to locate and place all markers
      google.maps.event.addListenerOnce(map, 'idle', function () {
        var bounds = new google.maps.LatLngBounds();
        var position = {};
        if (markers.length > 0) {
          if (options.ZoomToFitBounds === true) {
            zoomToFitBounds(map, markers);
          } else {
            // Find all markers that has the value center==true
            var centeredmarker = markers.filter(function( obj ) {
              return obj.center === true;
            });
            if (typeof centeredmarker[0].position.lat === 'function') {
              position.lat = markers[0].position.lat();
              position.lng = markers[0].position.lng();
            } else {
              position = centeredmarker[0].position;
            }
            map.setCenter(position); // Use the first marker to center the map.
          }
        } else if (!options.MapOptions.center) {
          map.setCenter(bounds.getCenter());
        }

        //Register map and its markers to class variable
        Map = {
          map: map,
          markers: markers
        };
        bindMapLinkButton();
        if (options.GeoLocation) {
          geoLocation(Map.map);
        }

        hook('onMapDrawn');
      });
    }

    function getLatitudeFromLocation (location, callback) {
      var lat = location.lat();
      callback(lat);
    }

    function getLongitudeFromLocation (location, callback) {
      var lng = location.lng();
      callback(lng);
    }

    function getMarkerIcon (imagepath, x2imagepath, callback) {
      var markerIcon = '';
      var imageElement = new Image();
      if (typeof imagepath === 'undefined' || typeof x2imagepath === 'undefined') {
        callback(null);
      } else {
        if (window.devicePixelRatio > 1.5) {
          if (x2imagepath) {
            imageElement.onload = function() {
              var markerIcon = {
                url: x2imagepath,
                size: new google.maps.Size(imageElement.naturalWidth / 2, imageElement.naturalHeight / 2),
                scaledSize: new google.maps.Size((imageElement.naturalWidth / 2), (imageElement.naturalHeight / 2)),
                origin: new google.maps.Point(0,0),
              };
              callback(markerIcon);
            };
            imageElement.src = x2imagepath;
          } else if (imagepath) {
            imageElement.onload = function() {
              var markerIcon = {
                url: imagepath,
                size: new google.maps.Size(imageElement.naturalWidth, imageElement.naturalHeight),
              };
              callback(markerIcon);
            };
            imageElement.src = imagepath;
          } else {
            callback(markerIcon);
          }
        } else {
          if (imagepath) {
            imageElement.onload = function() {
              var markerIcon = {
                url: imagepath,
                size: new google.maps.Size(imageElement.naturalWidth, imageElement.naturalHeight),
              };
              callback(markerIcon);
            };
            imageElement.src = imagepath;
          } else {
            callback(markerIcon);
          }
        }
      }
    }

    function guid() {
      var strguid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0,
          v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
      return strguid;
    }

    function drawRoute(from) {
      var markers = Map.markers;
      directionsDisplay.setMap(Map.map);
      directionsDisplay.setPanel($(options.routeDirections)[0]);
      var routeOptions = {
        origin: from,
        destination: markers[0].position,
        travelMode: google.maps.TravelMode[currentTravelmode]
      };
      jQuery.extend(routeOptions, options.DirectionsRequestOptions);
      directionsService.route(routeOptions, function (response, status) {
        if (status === google.maps.DirectionsStatus.OK) {
          directionsDisplay.setDirections(response);
          hook('onRouteDrawn');
        }
      });
    }

    function setTravelMode(travelmode) {
      var length = TravelModes.length;
      currentTravelmode = options.defaultTravelMode;
      for (var i = 0; i < length; i++) {
        if (TravelModes[i] === travelmode) {
          currentTravelmode = travelmode;
        }
      }
    }

    function setupRouting() {
      directionsService = new google.maps.DirectionsService();
      directionsDisplay = new google.maps.DirectionsRenderer({
        draggable: true
      });
      $(options.getRouteButton).on('click', function (e) {
        e.preventDefault();

        // Only accept DRIVING, WALKING or BICYCLING
        var travelmode = $(options.getTravelMode).val();
        setTravelMode(travelmode);
        if ($(options.getFromAddress).val().length > 0) {
          drawRoute($(options.getFromAddress).val());
        }
      });
    }

    function geoLocation(map) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
          map.setCenter(pos);
        }, function () {
          handleNoGeolocation(map, true);
        });
      } else {
        // Browser doesn't support Geolocation
        handleNoGeolocation(map, false);
      }
    }

    function toggleTrafficLayer() {
      if ((trafficLayer) && (trafficLayer.map !== null)) {
        trafficLayer.setMap(null);
      } else {
        trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(Map.map);
      }
    }

    function toggleBicycleLayer() {
      if ((bicycleLayer) && (bicycleLayer.map !== null)) {
        bicycleLayer.setMap(null);
      } else {
        bicycleLayer = new google.maps.BicyclingLayer();
        bicycleLayer.setMap(Map.map);
      }
    }

    function handleNoGeolocation(map, errorFlag) {
      var content;
      if (errorFlag) {
        content = 'Error: The Geolocation service failed.';
      } else {
        content = 'Error: Your browser doesn\'t support geolocation.';
      }

      var options = {
        map: map,
        position: new google.maps.LatLng(60, 105),
        content: content
      };

      var infowindow = new google.maps.InfoWindow(options);
      Map.setCenter(options.position);
    }

    function setGeoLocation() {
      geoLocation(Map.map);
    }

    function getGoogleMapLink(address) {
      var url = options.AndroidMapLink + '?q=' + address;
      return url;
    }

    function getAppleMapsLink(address) {
      var url = options.iOSAppleMapLink + '?q=' + address;
      return url;
    }

    function getWindowsPhone7MapLink(address) {
      var url = options.WP7MapLink + address;
      return url;
    }

    function getDesktopMapLink(address) {
      var url = options.DesktopMapLink + '?q=' + address;
      return url;
    }

    function getNativeMapLink(address) {
      var url = '';
      if (isAndroid()) {
        url = getGoogleMapLink(address);
      } else if (isIOS()) {
        url = getAppleMapsLink(address);
      } else if (isWindowsPhone()) {
        url = getWindowsPhone7MapLink(address);
      } else {
        url = getDesktopMapLink(address);
      }
      return url;
    }

    function isAndroid () {
      if (navigator.userAgent.toLowerCase().indexOf('android') > -1) {
        return true;
      } else {
        return false;
      }
    }

    function isIOS () {
      if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPad/i)) || (navigator.userAgent.match(/iPod/i))) {
        return true;
      } else {
        return false;
      }
    }

    function isBlackBerry () {
      if (navigator.userAgent.match(/BlackBerry/i)) {
        return true;
      } else {
        return false;
      }
    }

    function isWindowsPhone () {
      if (navigator.userAgent.match(/Windows Phone/i)) {
        return true;
      } else {
        return false;
      }
    }

    function bindAutoComplete(){
      // Get the DOM-element, not jQuery object
      var input = $(options.SearchBox)[0];
      var autoCompleteOptions = options.AutoCompleteOptions;
      // Assign autocomplete to variable to add event listener
      autoComplete = new google.maps.places.Autocomplete(input, autoCompleteOptions);

      // When event fires run callback function

      // If routing is desired trigger click event on ordinary Get route button
      if($(options.getRouteButton).length > 0 && $(options.getFromAddress).length > 0){
        autoComplete.addListener('place_changed', function(){
          $(options.getRouteButton).trigger('click');
        });
      }
      else{
      // Make a ordinary search
        autoComplete.addListener('place_changed', onPlaceChanged);
      }
    }

    // AutoComplete callback function
    function onPlaceChanged() {
      var place = autoComplete.getPlace();
      if (place.geometry) {
        if (options.AutoCompleteOptions.moveMap) {
          Map.map.panTo(place.geometry.location);
          Map.map.setZoom(15);
        }
        if (options.AutoCompleteOptions.setMarker) {
          var marker = new google.maps.Marker({
            map: Map.map,
            position: place.geometry.location
          });
        }
        hook('onAutoCompletePlaceChanged');
      }
    }

    function processTriggeredSearch(location, map) {
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({'address': location}, function (data, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          map.setCenter(data[0].geometry.location);
          var marker = new google.maps.Marker({
            map: map,
            position: data[0].geometry.location
          });
        }
      });
    }

    /**
     * Get/set a plugin option.
     * Get usage: $('#el').simplegmaps('option', 'key');
     * Set usage: $('#el').simplegmaps('option', 'key', value);
     */
    function option(key, val) {
      if (val) {
        options[key] = val;
      } else {
        return options[key];
      }
    }

    /**
     * Destroy plugin.
     * Usage: $('#el').simplegmaps('destroy');
     */
    function destroy() {
      // Iterate over each matching element.
      $el.each(function () {
        var el = this;
        var $el = $(this);

        // Add code to restore the element to its original state...

        hook('onDestroy');
        // Remove Plugin instance from the element.
        $el.removeData('plugin_' + pluginName);
      });
    }

    /**
     * Callback hooks.
     * Usage: In the defaults object specify a callback function:
     * hookName: function() {}
     * Then somewhere in the plugin trigger the callback:
     * hook('hookName');
     */
    function hook(hookName) {
      if (options[hookName] !== undefined) {
        // Call the user defined function.
        // Scope is set to the jQuery element we are operating on.
        options[hookName].call(el);
      }
    }

    // Initialize the plugin
    init();

    // Expose methods of Plugin we wish to be public.
    return {
      option: option,
      destroy: destroy,
      getNativeMapLink: getNativeMapLink,
      toggleTrafficLayer: toggleTrafficLayer,
      toggleBicycleLayer: toggleBicycleLayer,
      getGoogleMapLink: getGoogleMapLink,
      getAppleMapsLink: getAppleMapsLink,
      getWindowsPhone7MapLink: getWindowsPhone7MapLink,
      getDesktopMapLink: getDesktopMapLink,
      setGeoLocation:setGeoLocation
    };
  }

  /**
   * Plugin definition.
   */
  $.fn[pluginName] = function (options) {
    // If the first parameter is a string, treat this as a call to
    // a public method.
    if (typeof arguments[0] === 'string') {
      var methodName = arguments[0];
      var args = Array.prototype.slice.call(arguments, 1);
      var returnVal;
      this.each(function () {
        // Check that the element has a plugin instance, and that
        // the requested public method exists.
        if ($.data(this, 'plugin_' + pluginName) && typeof $.data(this, 'plugin_' + pluginName)[methodName] === 'function') {
          // Call the method of the Plugin instance, and Pass it
          // the supplied arguments.
          returnVal = $.data(this, 'plugin_' + pluginName)[methodName].apply(this, args);
        } else {
          throw new Error('Method ' + methodName + ' does not exist on jQuery.' + pluginName);
        }
      });
      if (returnVal !== undefined) {
        // If the method returned a value, return the value.
        return returnVal;
      } else {
        // Otherwise, returning 'this' preserves chainability.
        return this;
      }
      // If the first parameter is an object (options), or was omitted,
      // instantiate a new instance of the plugin.
    } else if (typeof options === 'object' || !options) {
      return this.each(function () {
        // Only allow the plugin to be instantiated once.
        if (!$.data(this, 'plugin_' + pluginName)) {
          // Pass options to Plugin constructor, and store Plugin
          // instance in the elements jQuery data object.
          $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
        }
      });
    }
  };

  // Default plugin options.
  // Options can be overwritten when initializing plugin, by
  // passing an object literal, or after initialization:
  // $('#el').simplegmaps('option', 'key', value);
  $.fn[pluginName].defaults = {
    GeoLocation: false,
    ZoomToFitBounds: true,
    MapOptions: {
      draggable: true,
      zoom: 8,
      scrollwheel: false,
      streetViewControl: false,
      panControl: true,
      zoomControl: true,
      zoomControlOptions: {
        style: 'DEFAULT'
      }
    },
    SearchBox: '#simplegmaps-searchbox',
    SearchButton: '#simplegmaps-searchbutton',
    AutoComplete: false,
    AutoCompleteOptions: {
      // Supported types (https://developers.google.com/places/supported_types#table3)
      types: ['geocode'],
      // Country Codes (ISO 3166-1 alpha-2): https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
      componentRestrictions: {'country': 'se'},
      moveMap: false,
      setMarker: false
    },
    iOSAppleMapLink: 'http://maps.apple.com/',
		iOSGoogleMapLink: 'comgooglemaps://',
		AndroidMapLink: 'https://maps.google.se/maps',
		WP7MapLink: 'maps:',
		DesktopMapLink: 'http://www.google.com/maps',
    getRouteButton: '#simplegmaps-getroute',
    getTravelMode: '#simplegmaps-travelmode input:checked',
    routeDirections: '#simplegmaps-directions',
    externalLink: '#simplegmaps-external',
    getFromAddress: '#simplegmaps-fromaddress',
    defaultTravelMode: 'DRIVING',
    onInit: function () {},
    onLoad: function () {},
    onDestroy: function () {},
    onRouteDrawn: function () {},
    onMapDrawn: function () {},
    onAutoCompletePlaceChanged: function () {}
  };

})(jQuery);
