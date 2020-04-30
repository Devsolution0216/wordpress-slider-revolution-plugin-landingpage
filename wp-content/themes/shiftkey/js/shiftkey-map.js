
function shiftkeyinitMap() {
	var latitude = jQuery('#gmap').data( 'latitude' );
	var longitude = jQuery('#gmap').data( 'longitude' );
	var title = jQuery('#gmap').data( 'title' );
	var image = jQuery('#gmap').data( 'marker' );
	var zoom = jQuery('#gmap').data( 'zoom' );

	var myLatLng = {lat: latitude, lng: longitude};
    var map = new google.maps.Map(document.getElementById('gmap'), {
      center: myLatLng,
      zoom: zoom,
      styles: [

      	{ "elementType": "geometry", "stylers": [ { "color": "#ebe3cd" } ] }, 
      	{ "elementType": "labels.text.fill", "stylers": [ { "color": "#523735" } ] }, 
      	{ "elementType": "labels.text.stroke", "stylers": [ { "color": "#f5f1e6" } ] }, 
      	{ 
      		"featureType": "administrative",
      		"elementType": "geometry.stroke", 
      		"stylers": [ { "color": "#c9b2a6" } ]
      	}, 
      	{ 
      		"featureType": "administrative.land_parcel", 
      		"elementType": "geometry.stroke", 
      		"stylers": [ { "color": "#dcd2be" } ] 
      	}, 
      	{ 
      		"featureType": "administrative.land_parcel", 
      		"elementType": "labels.text.fill", 
      		"stylers": [ { "color": "#ae9e90" } ] 
      	}, 
      	{ 
      		"featureType": "landscape.natural", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#dfd2ae" } ] 
      	}, 
      	{ 
      		"featureType": "poi", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#dfd2ae" } ] 
      	}, 
      	{ 
      		"featureType": "poi", 
      		"elementType": "labels.text.fill", 
      		"stylers": [ { "color": "#93817c" } ] 
      	}, 
      	{ 
      		"featureType": "poi.park", 
      		"elementType": "geometry.fill", 
      		"stylers": [ { "color": "#a5b076" } ] 
      	}, 
      	{ 
      		"featureType": "poi.park", 
      		"elementType": "labels.text.fill", 
      		"stylers": [ { "color": "#447530" } ] 
      	}, 
      	{ 
      		"featureType": "road", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#f5f1e6" } ] 
      	}, 
      	{ 
      		"featureType": "road.arterial", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#fdfcf8" } ] 
      	}, 
      	{ 
      		"featureType": "road.highway", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#f8c967" } ] 
      	}, 
      	{ 
      		"featureType": "road.highway", 
      		"elementType": "geometry.stroke", 
      		"stylers": [ { "color": "#e9bc62" } ] 
      	}, 
      	{ 
      		"featureType": "road.highway.controlled_access", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#e98d58" } ] 
      	}, 
      	{ 
      		"featureType": "road.highway.controlled_access", 
      		"elementType": "geometry.stroke", 
      		"stylers": [ { "color": "#db8555" } ] 
      	}, 
      	{ 
      		"featureType": "road.local", 
      		"elementType": "labels.text.fill", 
      		"stylers": [ { "color": "#806b63" } ] 
      	}, 
      	{ 
      		"featureType": "transit.line", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#dfd2ae" } ] 
      	}, 
      	{ 
      		"featureType": "transit.line", 
      		"elementType": "labels.text.fill", 
      		"stylers": [ { "color": "#8f7d77" } ] 
      	}, 
      	{ 
      		"featureType": "transit.line", 
      		"elementType": "labels.text.stroke", 
      		"stylers": [ { "color": "#ebe3cd" } ] 
      	}, 
      	{ 
      		"featureType": "transit.station", 
      		"elementType": "geometry", 
      		"stylers": [ { "color": "#dfd2ae" } ] 
      	}, 
      	{ 
      		"featureType": "water", 
      		"elementType": "geometry.fill", 
      		"stylers": [ { "color": "#b9d3c2" } ] 
      	}, 
      	{ 
      		"featureType": "water", 
      		"elementType": "labels.text.fill", 
      		"stylers": [ { "color": "#92998d" } ] 
      	}	
      ]
    });
	
		var marker = new google.maps.Marker({
			position: myLatLng,
		map: map,
		icon: image,
		title: jQuery('#gmap').data( 'title' )
		});
	marker.setMap(map);
}