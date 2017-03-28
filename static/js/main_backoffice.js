'use strict';

var CVMaps = {
  paths: {
      h: document.URL.substr(0,document.URL.lastIndexOf('/')) + "/",
      s: function(){return this.h + "static/"},
      i: function(){return this.s() + "images/"}
  }
};
var map, markers = [];


var j = []; // jobs

var cluster_options = {
    imagePath: CVMaps.paths.i()+'m',
    gridSize: 40,
    maxZoom: 14
};

var children = [];

/*
    Load homepage parameters and load up jobs list
 */


/*
#    Initiators   #
*/

function initMap() {
var cvMapsStyle=new google.maps.StyledMapType([{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#444444"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{saturation:-100},{lightness:45}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"simplified"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"#46bcec"},{visibility:"on"}]}],{name:"CV Maps"});

	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 54.694988, lng: 25.278570},
		zoom: 12,
		mapTypeControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'cvmapsstyle']
          }
	});

    //initSearchBox();

	map.mapTypes.set('cvmapsstyle', cvMapsStyle);
    map.setMapTypeId('cvmapsstyle');

	renderMarkers();

    map.addListener('dragstart', function() {
        $(".window").css({ opacity: 0.5 });
      });

    map.addListener('dragend', function() {
        $(".window").css({ opacity: 1 });
      });
}

function initSearchBox(){
    var input = document.getElementById('pac-input');
    var options = {componentRestrictions: {country: 'lt'}};
    var searchBox = new google.maps.places.SearchBox(input,options);
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }

            placeMarkerAndPanTo(place.geometry.location, map);

        });
    });
}

/*
#     Renders     #
 */

function renderMarkers(){
    var infoWindow = new google.maps.InfoWindow(),
        image = CVMaps.paths.i() + "marker_plus.png";

	for(var i=0; i<c.length; i++){

			if(c[i].s === "self"){
				image = {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 7,
                    strokeColor: '#8bc34a',
                    strokeOpacity: 0.2,
                    strokeWeight: 12,
                    fillColor: '#8bc34a',
                    fillOpacity: 1
                };
			} else {
                image = CVMaps.paths.i() + "marker_plus.png";
            }

			var position = new google.maps.LatLng(c[i].lat, c[i].lng);

            var marker_id = c[i].mid;
            var job_id = c[i].jid;

			var marker = new google.maps.Marker({
				position: position,
				map: map,
				icon: image,
                marker_id: marker_id,
                job_id: job_id
			});
			/*google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
                    var content = '', salary = '';
                    $.ajax({
                        type: "GET",
                        //url: "q/get_jobs/"+marker.job_id,
                        url: CVMaps.paths.h,
                        data: {
                            c: "q",
                            m: "get_jobs",
                            ids: marker.job_id
                        },
                        success: function(response) {

                            salary = salaryToString(response);

                            if(c[i].s !== "self"){

                                var ids = [];
                                for(var j=0; j<c[i].s.length; j++){
                                    ids.push(c[i].s[j]);
                                }

                                 $.ajax({
                                    type: "GET",
                                    url: CVMaps.paths.h,
                                    data: {
                                        c: "q",
                                        m: "get_jobs",
                                        ids: ids
                                    },
                                     success: function(response) {
                                        content += '<ul class="infoWindow_list">';
                                        $.each( response, function( key, val ) {
                                            salary = salaryToString(response,key);
                                            var childs_arr_id = arrayObjectIndexOf(children,val.id,"id");

                                            if(childs_arr_id >= 0){
                                                content += '<li><div style="color:'+children[childs_arr_id].color+'; font-size:'+(children[childs_arr_id].scale+5)+'px">'+val.title+'</div>';
                                            } else {
                                                content += '<li><div>'+val.title+'</div>';
                                            }

                                            content += '<div class="m-company">'+val.company+'</div>';
                                            content += '<div class="m-price">'+salary+'</div>';
                                            content += '</li>';
                                        });
                                        content += '</ul>';
                                        infoWindow.setContent(content);
                                        infoWindow.open(map, marker);
                                    }

                                 });
                            } else {
                                content += response[0].title+'<div class="m-company">'+response[0].company+'</div><div class="m-price">'+(salary.length > 3 ? salary : "")+'</div><a href="" class="m-button">Rodyti skelbimą</a>';
                                infoWindow.setContent(content);
                                infoWindow.open(map, marker);
                            }
                        }
                    });
	            }
	        })(marker, i));*/
			markers.push(marker);


	}

   //markerCluster = new MarkerClusterer(map, markers, cluster_options);
}



/*
#    Home setter functions  #
 */

function enableSetHomePosition(){
    $(".set_home_position").addClass("set_home_position_active");
    map.setOptions({draggableCursor:'crosshair'});
    set_home_position = map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });
}

function disableSetHomePosition(){
    $(".set_home_position").removeClass("set_home_position_active");
    map.setOptions({draggableCursor:''});
    google.maps.event.removeListener(set_home_position);
}

function placeMarkerAndPanTo(latLng, map) {
    if(home_marker.length > 0){
        home_marker[0].setMap(null);
        home_marker = [];
    }

    if(home_radius.length > 0){
        home_radius[0].setMap(null);
        home_radius = [];
    }

    var marker = new google.maps.Marker({
        position: {lat:latLng.lat() , lng:latLng.lng()},
        draggable:true,
        map: map
    });

    var radius = new google.maps.Circle({
      strokeColor: '#666666',
      strokeOpacity: 0.5,
      strokeWeight: 2,
      fillColor: '#000000',
      fillOpacity: 0.05,
      map: map,
      center: new google.maps.LatLng(latLng.lat(), latLng.lng()),
      radius: search_radius,
        editable: true

    });

    google.maps.event.addListener(radius, 'radius_changed', function() {
        search_radius = radius.getRadius();
        findNearest(marker.getPosition().lat(),marker.getPosition().lng());
    });

    google.maps.event.addListener(radius, 'center_changed', function() {
        marker.setPosition(radius.getCenter());
        findNearest(radius.getCenter().lat(),radius.getCenter().lng());
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        radius.setCenter(marker.getPosition());
        findNearest(marker.getPosition().lat(),marker.getPosition().lng());
    });

    map.panTo(latLng);
    if(map.zoom < 13){
        map.setZoom(13);
    }

    home_radius.push(radius);
    home_marker.push(marker);
    if(home_marker){
        disableSetHomePosition();
    }

    // Need to show map faster, not calculate nearest
    findNearest(latLng.lat(),latLng.lng());
}


/*
###    Miscellaneous functions  ###
 */

function clearMarkers(){
    c = [];
    for(var i=0; i<markers.length; i++){
        markers[i].setMap(null);
    }
    markers = [];
    markerCluster.clearMarkers();
}

function groupMarkers(markers){
	var l = markers.length, checked = [], groups = [];
    var gi = 0;
	for(var i=0; i<l; i++){
		var similar = similarMarkers(markers,markers[i].lat,markers[i].lng);
		if(similar.length > 1){
            groups[gi] = similar;
            gi++;
        }
		else if ((similar.length === 1) && (c[i].id === similar[0])) c[i].s = "self";

	}

	for(var i=0; i<l; i++){
		for(var j=0; j<groups.length; j++){
			if(c[i].id === groups[j][0]){
				c[i].s = groups[j];
			}
		}

	}
	return groups;
}

function similarMarkers(markers, lat, lng){
	var similar = [];
	for(var i=0; i<markers.length; i++){
		if(markers[i].lat === lat && markers[i].lng === lng){
			similar.push(markers[i].id);
		}

	}
	return similar;
}

function arrayObjectIndexOf(myArray, searchTerm, property) {
    for (var i = 0, len = myArray.length; i < len; i++) {
        if (myArray[i][property] === searchTerm) return i;
    }
    return -1;
}

