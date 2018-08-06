var geocoder;
var geocoder_inited = false;

window.addEvent('domready',
	function()
	{

		// Allgemeines Form aktualisieren
		if($('submit-form-button'))
		{
			$('submit-form-button').addEvent('click', function(r){
					var r = new Event(r).stop();
					$('partner-form').submit();
				});
			if($('google_map_latitude')) {
				$('submit-form-button').addEvent('mouseover', function(r) {
						var r = new Event(r).stop();
						tryToSetLocation();
					});
			}
		}

		tryToSetLocation();

		$$('textarea').each(function(item){ item.resizingTextArea() });
	}
);

// Google Maps Sepcific Functions
function tryToSetLocation() {
	if($('google_map_latitude')) {
		if($('google_map_latitude').value == '' || $('google_map_longitude').value == '') {
			getLocation();
		}
	}
}


function setLatLongInformation(response) {

	// Domkloster 3, 50667 Köln, Deutschland

	if (!response || response.Status.code != 200) {

		alert('Die Geokoordinaten für die Adresse \n['+getAddress()+']\nkonnten nicht ermittelt werden.');

	} else {

		place = response.Placemark[0];

		$('google_map_latitude').value = place.Point.coordinates[1];
		$('google_map_longitude').value = place.Point.coordinates[0];

	}
}

function getLocation() {

	if(!geocoder_inited) {
		geocoder_inited = true;
		geocoder = new GClientGeocoder();
		unloadGoogleMaps();
	}
	geocoder.getLocations( getAddress() , setLatLongInformation );
}

function getAddress() {
	var address = '';
	if($('mtpa_street1').value != '') {
		address += $('mtpa_street1').value + ', ';
	}
	if($('mtpa_street2').value != '') {
		address += $('mtpa_street2').value + ', ';
	}
	if($('mtpa_zip').value != '') {
		address += $('mtpa_zip').value + ' ';
	}
	if($('mtpa_city').value != '') {
		address += $('mtpa_city').value;
	}
	address += ', Deutschland';

	return address;
}

function showMap() {

	if(!geocoder_inited) {
		geocoder_inited = true;
		geocoder = new GClientGeocoder();
		unloadGoogleMaps();
	}

	var lat = $('google_map_latitude').value;
	var lng = $('google_map_longitude').value;
	var adr = getAddress();

	if(lat != '' && lng != '' && adr != '') {
		point = new GLatLng(lat, lng);
		ssl = point.toUrlValue();

		mapurl  = 'http://maps.google.de/?hl=de&cd=1&q=';
		mapurl += encodeURI( adr );
		mapurl += '&sll=' + ssl + '&z=16&iwloc=addr&om=1';

		window.open(mapurl, 'GoogleMap');

	} else {
		alert('Bitte Adresse prüfen!');
	}
}

function unloadGoogleMaps() {
	window.addEvent('unload', function() { GUnload(); } );
}
