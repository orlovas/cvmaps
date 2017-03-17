/*
TODO: pagination works bad
TODO: ne rabotaet ranzhirovanie s 'plus' metkami;
 */
var map, markers = [], markerCluster = {};
var _p = 1,
    _qt = 0,
    _order_by = "date_desc",
    _city_id = 0,
    _category_id = 0,
    _edu_id = 0,
    _salary = 0,
    _new = 0,
    _premium = 0,
    _work_time = 0,
    _worker_type_id = 0,
    _student = 0,
    _school = 0,
    _pensioneer = 0,
    _disabled = 0,
    _shift = 0,
    _no_exp = 0;
var c = []; // coordinates
var j = []; // jobs
var tp = 0; // total pages
var param = {
   jobs: 0
};
var dp = []; // downloaded pages
var pr = {}; // job preview cache
var only_nearest = 0;
var home_marker = [];
var home_radius = [];
var set_home_position;
var search_radius = 2000;
var cluster_options = {
    imagePath: 'http://localhost/cvm/static/images/m',
    gridSize: 50,
    maxZoom: 14
};

/*
    Load homepage parameters and load up jobs list
 */

initParam();

/*
#    Initiators   #
*/

function initParam(){
    $.ajax({
        type: "GET",
        url: "q/init_param/"+_p+"/"+_qt+"/"+_order_by+"/"+_city_id+"/"+_category_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        /*url: "q/init_param/",
        data: {
            p: _p,
            qt: _qt,
            order_by: _order_by,
            city_id: _city_id,
            category_id: _category_id,
            edu_id: _edu_id,
            salary: _salary,
            new: _new,
            premium: _premium
        },*/
        success: function(p){
            param.jobs = p.jobs;
        },
        complete: function(){
            // loading jobs if parameters set
            if(j.length < 1) getJobs();
        }
	});
}

function initList() {
    $('.window__list').animate({
        scrollTop: 0
    }, 300);
    var start = (_p-1) * 30;
    if(param.jobs < 30){
        var end = (start + param.jobs);
    } else {
        var end = (start + 30);
    }

    // vycheslenie kol-vo objavlenij na poslednej stranice
    if(_p == tp){
        end = start + (param.jobs - (tp - 1) * 30);
    }

    $("#jobs-count").html(param.jobs);

    var list = $(".window__list ul");
    list.html("");

    if(typeof j !== "undefined") {
        for (var i = start; i < end; i++) {
            var salary = "";
            if (j[i].salary_from !== null && j[i].salary_to === null) {
                salary += 'Nuo ' + j[i].salary_from;
            } else if (j[i].salary_from === null && j[i].salary_to !== null) {
                salary += 'Iki ' + j[i].salary_to;
            } else if (j[i].salary_from !== null && j[i].salary_to !== null) {
                salary += j[i].salary_from + " - " + j[i].salary_to;
            }
            salary += ' €';
            list.append('<li><a href="" class="link--offer clearfix" title="Parodyti darbo skelbimą - ' + j[i].title + '"><div class="offer-logo"><img src="static/images/l/' + j[i].logo + '" width="74"></div><div class="offer-content"><h5>' + j[i].title + '</h5><div class="offer-company">' + j[i].company + '</div><div class="offer-salary">' + (salary.length > 3 ? salary : "") + '</div></div></a></li>');
        }
    }
    $("#pg-current").html(_p);

}

function initMap() {
	var cvMapsStyle=new google.maps.StyledMapType([{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#444444"}]},{featureType:"administrative.country",elementType:"geometry.fill",stylers:[{visibility:"on"}]},{featureType:"administrative.province",elementType:"labels.icon",stylers:[{hue:"#ff0000"},{visibility:"on"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"poi.business",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"poi.government",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"poi.medical",elementType:"geometry",stylers:[{visibility:"on"},{color:"#f5e4e4"}]},{featureType:"poi.park",elementType:"geometry",stylers:[{visibility:"on"},{color:"#deefdd"}]},{featureType:"road",elementType:"all",stylers:[{saturation:-100},{lightness:45}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"simplified"}]},{featureType:"road.highway",elementType:"geometry",stylers:[{visibility:"on"}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{visibility:"on"},{color:"#f8e491"}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{visibility:"off"}]},{featureType:"road.highway",elementType:"labels",stylers:[{visibility:"simplified"}]},{featureType:"road.highway",elementType:"labels.text.fill",stylers:[{visibility:"off"}]},{featureType:"road.highway.controlled_access",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"transit.station",elementType:"all",stylers:[{visibility:"on"}]},{featureType:"water",elementType:"all",stylers:[{color:"#46bcec"},{visibility:"on"}]},{featureType:"water",elementType:"geometry.fill",stylers:[{visibility:"on"},{color:"#78d2ff"}]}],{name:"CV Maps"});

	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 55.1493051, lng: 24.2270266},
		zoom: 8,
		mapTypeControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'cvmapsstyle']
          }
	});

    initSearchBox();

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
// Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

    searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
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
    Funkcii dlia oboznachenija domashnego adresa nazhatiem na kartu
 */
function enableSetHomePosition(){
    set_home_position = map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });
}

function disableSetHomePosition(){
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
        filterNearest(marker.getPosition().lat(),marker.getPosition().lng());
    });

    google.maps.event.addListener(radius, 'center_changed', function() {
        marker.setPosition(radius.getCenter());
        filterNearest(radius.getCenter().lat(),radius.getCenter().lng());
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        radius.setCenter(marker.getPosition());
        filterNearest(marker.getPosition().lat(),marker.getPosition().lng());
    });

    map.panTo(latLng);
    if(map.zoom < 13){
        map.setZoom(13);
    }

    home_radius.push(radius);
    home_marker.push(marker);

    filterNearest(latLng.lat(),latLng.lng());
}


/*
    V locations, pervaja koordinata, eto ta, ot kotoroj schitaem (dom. adres).
    Mapzen dejstvuet na ogranichenno rasstojanie ot tochek (~200km), poetomu snachalo nuzhno vybrat gorod.
 */
function getDuration(origin){
    var destinations = "";
    var nearest_jobs = [];
    for(var i=0; i< c.length; i++){
        if(c[i].nearest === 1){
            nearest_jobs.push({id: c[i].id});
            destinations += '{"lat":'+c[i].lat
                            +',"lon":'+c[i].lng+'}';
            destinations += ',';
        }
    }

    if(destinations.length > 0){
        destinations = destinations.slice(0, -1);
    } else {
        console.log("No work here");
        return;
    }

    $.ajax({
      type: 'GET',
      url: 'http://matrix.mapzen.com/one_to_many',
        data: {
            json: '{' +
            '"locations":' +
                '[{"lat":'+origin[0]+',"lon":'+origin[1]+'},'+destinations+'],' +
            '"costing":' +
                '"pedestrian",' +
            '"api_key":' +
                '"mapzen-cqWzJVB"' +
            '}'
        },
      dataType: 'json',
      success: function(jsonData) {
          jQuery.each( jsonData.one_to_many[0], function( i, val ) {
              if(val.to_index != 0){
                  /* c[i-1] - t.k. koordinaty idut po ocheredi, a samyj pervyj - start, poetomu ejo
                    ignoriruem (val.to_index != 0)
                  */
                  var id = arrayObjectIndexOf(j,nearest_jobs[i-1].id,"id");
                  j[id].time = round((val.time/60),1);
              }
            });

      },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("Too many requests");
        }
    });
}

// home 54.725781, 25.251190
function filterNearest(lat,lng){
    msg("[NEAREST]");
    for(var i=0; i<c.length; i++){
        var distance = getDistanceFromLatLonInKm(lat,lng,c[i].lat,c[i].lng);
        if(distance <= (search_radius / 1000)){
            c[i].nearest = 1;
            msg("c[]: "+i+", c[].id: "+c[i].id+", c[].mid: "+c[i].mid);
        } else {
            c[i].nearest = 0;
        }

    }
    msg("[/NEAREST]");

    getDuration([lat,lng]);
}

function showOnlyNearest(){
    markerCluster.clearMarkers();
    for(var i=0; i< c.length; i++){
        var mid = c[i].mid;
        if(c[i].hasOwnProperty("s")){
            var marker_id = arrayObjectIndexOf(markers,mid,"marker_id");
            console.log("mid = " + mid + ", markers[] = " + marker_id);
            if(c[i].nearest === 1){
                markers[marker_id].setMap(map);
                markers[marker_id].setVisible(true);
            } else {
                markers[marker_id].setVisible(false);
            }
        }
    }

    var new_markers = [];
    for(var i=0; i<markers.length; i++){
        if(markers[i].map !== null){
            new_markers.push(markers[i]);
        }
    }
    markerCluster = new MarkerClusterer(map, new_markers, cluster_options);
}

// Haversine formula (http://www.movable-type.co.uk/scripts/latlong.html)
function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1);
  var a =
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ;
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  var d = R * c; // Distance in km
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}

/*function getDuration(origin){
    var coordinates = [];
    for(var i=0; i< c.length; i++){
        coordinates.push(c[i].lat + "," + c[i].lng);
    }

    var distanceService = new google.maps.DistanceMatrixService();
    distanceService.getDistanceMatrix({
        origins: [origin],
        destinations: coordinates,
        travelMode: google.maps.TravelMode.WALKING,
        unitSystem: google.maps.UnitSystem.METRIC
    },
    function (response, status) {
        if (status !== google.maps.DistanceMatrixStatus.OK) {
            console.log('Error:', status);
        } else {
            var l = response.rows[0].elements.length;
            for(var i=0; i<l; i++){
                var d = response.rows[0].elements[i].duration.value / 60;
                console.log(c[i].id + ": "+Math.round(d));
            }
        }
    });
}*/

/*
#    Getters  #
 */

function getMarkers(){
    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        /*url: "m/",
        data: {
            p: _p,
            qt: _qt,
            order_by: _order_by,
            city_id: _city_id,
            category_id: _category_id,
            edu_id: _edu_id,
            salary: _salary,
            new: _new,
            premium: _premium
        },*/
        success: function(response){
            $.each( response, function( key, val ) {
                c.push({
                    id: val.jid,
                    mid: val.mid,
                    lat: val.lat,
                    lng: val.lng,
                    avg: val.avg_sal,
                    credit: val.credit});
            });
            groupMarkers(c);
            initMap();
        }
	});
}

function getJobs(){
    $.ajax({
       type: "GET",
        url: "q/j/"+_p+"/"+_qt+"/"+_order_by+"/"+_city_id+"/"+_category_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        /*url: "q/j/",
        data: {
            p: _p,
            qt: _qt,
            order_by: _order_by,
            city_id: _city_id,
            category_id: _category_id,
            edu_id: _edu_id,
            salary: _salary,
            new: _new,
            premium: _premium
        },*/
        success: function(response){
            $.each( response, function( key, val ) {
                j.push({
                    id: val.id,
                    title: val.title,
                    company: val.company,
                    logo: val.logo,
                    salary_from: val.salary_from,
                    salary_to: val.salary_to
                });
                // Be sure all jobs pushed to j
                if(key == response.length-1){
                    initList();
                }

            });

            tp = Math.round(param.jobs / 30);
            $("#pg-total").html(tp);

            dp.push(_p);
        }
    });
}

/*
#     Renders     #
 */
/*
markers[10].setIcon({
      path: "M322.621,42.825C294.073,14.272,259.619,0,219.268,0c-40.353,0-74.803,14.275-103.353,42.825   c-28.549,28.549-42.825,63-42.825,103.353c0,20.749,3.14,37.782,9.419,51.106l104.21,220.986   c2.856,6.276,7.283,11.225,13.278,14.838c5.996,3.617,12.419,5.428,19.273,5.428c6.852,0,13.278-1.811,19.273-5.428   c5.996-3.613,10.513-8.562,13.559-14.838l103.918-220.986c6.282-13.324,9.424-30.358,9.424-51.106   C365.449,105.825,351.176,71.378,322.621,42.825z M270.942,197.855c-14.273,14.272-31.497,21.411-51.674,21.411   s-37.401-7.139-51.678-21.411c-14.275-14.277-21.414-31.501-21.414-51.678c0-20.175,7.139-37.402,21.414-51.675   c14.277-14.275,31.504-21.414,51.678-21.414c20.177,0,37.401,7.139,51.674,21.414c14.274,14.272,21.413,31.5,21.413,51.675   C292.355,166.352,285.217,183.575,270.942,197.855z",
      scale: 0.1, fillColor: '#80cc38',
    fillOpacity: 1,
strokeOpacity: 1.0,
        strokeColor: '#80cc38',
        strokeWeight: 0.0
    })

 */
function renderMarkers(){
    var infoWindow = new google.maps.InfoWindow();
	var data = "", image = "http://localhost/cvm/static/images/marker_plus.png";

	for(var i=0; i<c.length; i++){
		/*if(typeof c[i].s === "undefined"){
			continue;
		} else {*/
        if(typeof c[i].s !== "undefined"){
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
                image = "http://localhost/cvm/static/images/marker_plus.png";
            }

			var position = new google.maps.LatLng(c[i].lat, c[i].lng);
            var marker_id = c[i].mid;
            var job_id = c[i].id;
			var marker = new google.maps.Marker({
				position: position,
				map: map,
				icon: image,
                marker_id: marker_id,
                job_id: job_id,
                label: marker_id
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
                    $.ajax({
                        type: "GET",
                        url: "q/get_jobs/"+marker.job_id,

                        success: function(response) {
                            pr.title = response[0].title;
                            var salary = "";

                            if (response[0].salary_from !== null && response[0].salary_to === null ) {
                                salary += 'Nuo ' + response[0].salary_from;
                            } else if (response[0].salary_from === null && response[0].salary_to !== null) {
                                salary += 'Iki ' + response[0].salary_to;
                            } else if (response[0].salary_from !== null && response[0].salary_to !== null) {
                                salary += response[0].salary_from + " - " + response[0].salary_to;
                            }
                            salary += ' €';
                            var content = '';
                            if(c[i].s !== "self"){
                                var ids = [];
                                for(var j=0; j<c[i].s.length; j++){
                                    ids.push(arrayObjectIndexOf(c,c[i].s[j],"id"));
                                }
                                 $.ajax({
                                    type: "GET",
                                    url: "q/get_jobs/"+ids,
                                    success: function(response) {
                                        $.each( response, function( key, val ) {
                                            content += val.title+"<hr/>";
                                        });
                                        infoWindow.setContent(content);
                                        infoWindow.open(map, marker);
                                    }

                                 });
                            } else {
                                content = response[0].title+'<div class="m-company">'+response[0].company+'</div><div class="m-price">'+(salary.length > 3 ? salary : "")+'</div><a href="" class="m-button">Rodyti skelbimą</a>';
                                infoWindow.setContent(content);
                                infoWindow.open(map, marker);
                            }

                        }
                    });

	            }
	        })(marker, i));
			markers.push(marker);

		}
	}

   markerCluster = new MarkerClusterer(map, markers, cluster_options);
}

/*
#    Search, order and filter functions   #
 */

/*
    Search by title
 */
$("#search").submit(function(event){
    event.preventDefault();
    _qt = $(".search-text").val();
    _qt = (_qt != "" ? _qt : 0);
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        /*url: "m/",
        data: {
            p: _p,
            qt: _qt,
            order_by: _order_by,
            city_id: _city_id,
            category_id: _category_id,
            edu_id: _edu_id,
            salary: _salary,
            new: _new,
            premium: _premium
        },*/
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

/*
    Search by city
 */
$("#city_id").on("change", function(){
    _city_id = parseInt($("#city_id").val());
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#work_time").on("change", function(){
    _work_time = parseInt($("#work_time").val());
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#worker_type").on("change", function(){
    _worker_type_id = parseInt($("#worker_type").val());
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#salary").on("change", function(){
    _salary = $("#salary").val();
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});


$("#is_student").on("change", function(){
    _student = $('#is_student:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#is_school").on("change", function(){
    _school = $('#is_school:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#is_pensioneer").on("change", function(){
    _pensioneer = $('#is_pensioneer:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#is_disabled").on("change", function(){
    _disabled = $('#is_disabled:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#is_shift").on("change", function(){
    _shift = $('#is_shift:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#no_exp").on("change", function(){
    _no_exp = $('#no_exp:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#only_premium").on("change", function(){
    _premium = $('#only_premium:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

$("#only_new").on("change", function(){
    _new = $('#only_new:checked').length;
    j = [];
    initParam();

    $.ajax({
        type: "GET",
        url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
        success: function(response){
            clearMarkers();
            $.each( response, function( key, val ) {
                c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
            });
            groupMarkers(c);
            renderMarkers();
        }
    });
});

/*
    Search by category
 */
$("#category_id").on("change", function(){
   _category_id = parseInt($("#category_id").val());
    j = [];
    initParam();
    $("input[type=search]").val("");

            $.ajax({
                type: "GET",
                url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
                success: function(response){
                    clearMarkers();
                    $.each( response, function( key, val ) {
                        c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
                    });
                    groupMarkers(c);
                    renderMarkers();
                }
	        });

});

$("#dd_category_id > a").on("click", function(event){
    event.preventDefault();
   _category_id = parseInt($(this).attr('href').substr(1));
    $("input[type=search]").val($(this).text());
    j = [];
    initParam();

            $.ajax({
                type: "GET",
                url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
                success: function(response){
                    clearMarkers();
                    $.each( response, function( key, val ) {
                        c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
                    });
                    groupMarkers(c);
                    renderMarkers();
                }
	        });

});
/*
#    Event handlers   #
 */

$("#window_sort_close").click(function(){
   wsort();
});

$("#cancel-sort").click(function() {
    $("input[type=search]").val("");
    _p = 1;
    _qt = 0;
    _order_by = "date_desc";
    _city_id = 0;
    _category_id = 0;
    _edu_id = 0;
    _salary = 0;
    _new = 0;
    _premium = 0;
    _work_time = 0;
    _worker_type_id = 0;
    _student = 0;
    _school = 0;
    _pensioneer = 0;
    _disabled = 0;
    _shift = 0;
    _no_exp = 0;

    j = [];
    initParam();

    $.ajax({
                type: "GET",
                url: "q/m/"+_qt+"/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_new+"/"+_premium+"/"+_work_time+"/"+_worker_type_id+"/"+_student+"/"+_school+"/"+_pensioneer+"/"+_disabled+"/"+_shift+"/"+_no_exp,
                success: function(response){
                    clearMarkers();
                    $.each( response, function( key, val ) {
                        c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng});
                    });
                    groupMarkers(c);
                    renderMarkers();
                    wsort();
                }
	        });

    $('#city_id option').prop('selected', function() {
        return this.defaultSelected;
    });

    $('#category_id option').prop('selected', function() {
        return this.defaultSelected;
    });

    $('#salary option').prop('selected', function() {
        return this.defaultSelected;
    });

    $('#work_time option').prop('selected', function() {
        return this.defaultSelected;
    });

    $('#worker_type option').prop('selected', function() {
        return this.defaultSelected;
    });

    $("#is_student").prop("checked", false);
    $("#is_school").prop("checked", false);
    $("#is_pensioneer").prop("checked", false);
    $("#is_disabled").prop("checked", false);
    $("#is_shift").prop("checked", false);
    $("#no_exp").prop("checked", false);
    $("#only_premium").prop("checked", false);
    $("#only_new").prop("checked", false);


});

function wsort(){
    var wsort = document.querySelectorAll(".window__sort")[0];
    var wsort_footer = document.querySelectorAll(".window__sort__footer")[0];
    var wpagination = document.querySelectorAll(".window__pagination")[0];
    var wlist = document.querySelectorAll(".window__list")[0];
    var wpgn = document.querySelectorAll(".window__pagination")[0];
    if(wsort.style.display === "" || wsort.style.display === "none"){
        wsort.style.display = "block";
        wsort_footer.style.display = "block";
        wpagination.style.display = "none";
        wlist.style.display = "none";
        wpgn.style.display = "none";
    } else if (wsort.style.display === "block"){
        wsort.style.display = "none";
        wsort_footer.style.display = "none";
        wpagination.style.display = "block";
        wlist.style.display = "block";
        wpgn.style.display = "block";
    }
}

function wdropdown(){
    var wsearch = document.querySelectorAll(".search-field")[0];
    var wdropdown = document.querySelectorAll(".search-dropdown")[0];
    if(wdropdown.style.display === "" || wdropdown.style.display === "none"){
        wdropdown.style.display = "block";
        wsearch.style.borderBottomRightRadius = '0';
        wsearch.style.borderBottomLeftRadius = '0';
    } else if (wdropdown.style.display === "block"){
        wdropdown.style.display = "none";
        wsearch.style.borderBottomRightRadius = '';
        wsearch.style.borderBottomLeftRadius = '';
    }
}


function paginationUpdate(){
    if(_p > 1) $("#page-prev").removeClass("btn-disabled");
    if(_p === 1) $("#page-prev").addClass("btn-disabled");
    if(_p < tp) $("#page-next").removeClass("btn-disabled");
    if(_p === tp) $("#page-next").addClass("btn-disabled");
}

$("#page-prev").on("click", function(){
    if(_p > 1){
        _p = _p - 1;
        paginationUpdate();
        initList();
    }
});

$("#page-next").on("click", function(){
    if(_p < tp) {
        _p = _p + 1;
        paginationUpdate();
        if(dp.indexOf(_p) > -1){
            initList();
        } else {
            getJobs();
        }
    }
});

$("#toggle_list").on("click", function(){
    $(".window").slideToggle();
    $("#show_list").toggle();
});

$("#show_list").on("click", function(){
    $(".window").slideToggle();
    $("#show_list").toggle();
});

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


function countPages(){
    return Math.round(param.jobs / 30);
}

function restoreJobRanking(){
	for(var i=0; i<markers.length; i++){
		if(markers[i].hasOwnProperty("points")){
			delete markers[i].points;
		}

		markers[i].setIcon({
			path: google.maps.SymbolPath.CIRCLE,
                    scale: 7,
                    strokeColor: '#8bc34a',
                    strokeOpacity: 0.2,
                    strokeWeight: 12,
                    fillColor: '#8bc34a',
                    fillOpacity: 1
		});
	}
}

function jobRankingDelayed(){
    setTimeout(function() { jobRanking(); }, 1000);
}

function jobRanking(){
	restoreJobRanking();
    var data = [];

    for(var i=0; i< c.length; i++){
        if(c[i].nearest === 1){
            var jid = c[i].id;
            var j_arr_id = arrayObjectIndexOf(j,jid.toString(),"id");
            data.push({
                id: c[i].id,
                distance: parseFloat(j[j_arr_id].time),
                salary: parseFloat(j[j_arr_id].salary_from),
                average_salary: parseFloat(c[i].avg),
                credit: parseInt(c[i].credit)
            });
        }
    }

    if(data.length === 1){
	        var mid = arrayObjectIndexOf(markers,data[0].id,"job_id");
	        markers[mid].points = 1;
	        markers[mid].setIcon({
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 9,
                    strokeColor: '#72ae2c',
                    strokeOpacity: 0.2,
                    strokeWeight: 12,
                    fillColor: '#72ae2c',
                    fillOpacity: 1
                });

	    return;
    }

    var weight = {
        salary: 0.50,
        distance: 0.25,
        average_salary: 0.18,
        credit: 0.07
    };

    var dl = data.length;

    var mins = {
        distance: []
    };

    var maxs = {
        salary: [],
        average_salary: [],
        credit: []
    };

    for(var i=0; i<dl; i++){
        maxs.salary[i] = data[i].salary;
        mins.distance[i] = data[i].distance;
        maxs.average_salary[i] = data[i].average_salary;
        maxs.credit[i] = data[i].credit;
    }

    maxs.salary = Math.max.apply(Math,maxs.salary);
    mins.distance = Math.min.apply(Math,mins.distance);
    maxs.average_salary = Math.max.apply(Math,maxs.average_salary);
    maxs.credit = Math.max.apply(Math,maxs.credit);

    var salary = [];
    var distance = [];
    var average_salary = [];
    var credit = [];

    for(var i=0; i<dl; i++){
        result = data[i].salary / maxs.salary;
        salary.push(result);

        result = mins.distance / data[i].distance;
        distance.push(result);

        result = data[i].average_salary / maxs.average_salary;
        average_salary.push(result);

        if(maxs.credit === 0){
            result = 0;
        } else {
            result = data[i].credit / maxs.credit;
        }

        credit.push(result);
    }

    for(var i=0; i<dl; i++){
        result =
            weight.salary * salary[i]
            + weight.distance * distance[i]
            + weight.average_salary * average_salary[i]
            + weight.credit * credit[i];

        data[i].points = round(result,4);
    }

    for(var i=0; i<data.length; i++){
        var mid = arrayObjectIndexOf(markers,data[i].id,"job_id");
        markers[mid].points = data[i].points;
    }

    rateMarkers();
}

function rateMarkers(){
    var max = {id: 0, val: 0};
    for(var i=0; i<markers.length; i++){
        if(max.val < markers[i].points){
            max.val = markers[i].points;
            max.id = i;
        }
    }

    scaleDownValues();

    /*markers[max.id].setIcon({
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 9,
                    strokeColor: '#72ae2c',
                    strokeOpacity: 0.2,
                    strokeWeight: 12,
                    fillColor: '#72ae2c',
                    fillOpacity: 1
                });*/
}

function round(number, precision) {
    var factor = Math.pow(10, precision);
    var tempNumber = number * factor;
    var roundedTempNumber = Math.round(tempNumber);
    return roundedTempNumber / factor;
}

// https://stackoverflow.com/questions/11121012/how-to-scale-down-the-values-so-they-could-fit-inside-the-min-and-max-values
function scaleDownValues(){
	var min = 5,
		max = 38,
		ratio;

	var data = [];
	for(var i=0; i<markers.length; i++){
		if(markers[i].hasOwnProperty("points")){
			data.push({id: i, points: markers[i].points});
		}
	}

	var max_value = Math.max.apply(Math,data.map(function(o){return o.points;})),
		min_value = Math.min.apply(Math,data.map(function(o){return o.points;}));

	ratio = (max - min)/(max_value - min_value);

	for(var i=0; i<data.length; i++){
		var value = min_value + ratio * (data[i].points - min_value);
		var mid = data[i].id;
		var color = rgb2hex(valueToColor(value));
		var scale_value = 8.5 * data[i].points * 1.5;
		markers[mid].setIcon(
			{
                path: google.maps.SymbolPath.CIRCLE,
                scale: scale_value,
                strokeColor: color,
                strokeOpacity: 0.2,
                strokeWeight: 12,
                fillColor: color,
                fillOpacity: 1
            }
		);
	}
}

function valueToColor(val) {
	var val = parseInt(val);
	if (val > 5) {
		val = 5;
	} else if (val < 38) {
		val = 38;
	}

	var r = Math.floor((255 * val) / 100),
	    g = Math.floor((255 * (100 - val)) / 100),
	    b = 0;

	return "rgb(" + r + "," + g + "," + b + ")";
}

//Function to convert hex format to a rgb color
function rgb2hex(rgb){
 rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
 return (rgb && rgb.length === 4) ? "#" +
  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

// debug functions
function msg($text){
    console.log($text);
}

/*$.ajax({
  type: 'GET',
  url: 'https://maps.googleapis.com/maps/api/distancematrix/json',
  data: {
      units: "metric",
    key: "AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls",
      mode: "walking",
      origins: "Kauno g. 1",
      destinations: "Vilnius, Ozo g. 1"
  },
  dataType: 'json',
  success: function(jsonData) {
    console.log(jsonData);
  }
});*/
/*
var markers_to_show = [];
$.each(j, function(key, val){
  var m = arrayObjectIndexOf(markers,val.id,"job_id");

  if(m !== -1){
    //console.log(m);
    markers_to_show.push(markers[m].marker_id);
  }
});
var i = 0;
$.each(markers, function(key,val){

  var m = arrayObjectIndexOfSimple(markers_to_show,val.marker_id);
  console.log(m);
  if(m < 0) {
    console.log(i);
    markerCluster.removeMarker(markers[i]);
    markers[i].setMap(null);
  }
  i++;
});

//markerCluster.resetViewport();

//markerCluster.redraw();
 */

/*function arrayObjectIndexOfSimple(myArray, searchTerm) {
    for(var i = 0, len = myArray.length; i < len; i++) {
        if (myArray[i] === searchTerm) return i;
    }
    return -1;
}

function arrayObjectIndexOfReverse(myArray, searchTerm, property) {
    for(var i = 0, len = myArray.length; i < len; i++) {
        if (myArray[i][property] !== searchTerm) return i;
    }
}*/

/*function getPreview(id){
    $.ajax({
        type: "GET",
        url: "j/",
        data: {
            id: id
        },
        success: function(response) {
            pr.title = response.title;
            return true;
        }
    });
}*/

/*function get_content(marker,i){
    var content = '';
    if(c[i].s !== "self"){
        for(var j=0; j<c[i].s.length; j++){
            var idd = arrayObjectIndexOf(c,c[i].s[j],"id");
            content += "<b>"+c[idd].id+"</b>"+c[idd].txt+"<br/>";
        }
    }
    return content;
}*/