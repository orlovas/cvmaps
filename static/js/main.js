'use strict';

var map, markers = [], markerCluster = {};

var _p = 1,
    _order_by = "date_desc",
    _city_id = 0,
    _category_id = 0,
    _edu_id = 0,
    _salary = 0,
    _work_time = 0;

var c = []; // skelbimo koordinačių masyvas
var j = []; // skelbimų masyvas

var tp = 0; // iš visų puslapių
var dp = []; // išsaugoti puslapiai

var search_radius = 2000,
    home_marker = [],
    home_radius = [],
    set_home_position;

var cluster_options = {
    imagePath: CVMaps.paths.i()+'m',
    gridSize: 40,
    maxZoom: 14
};

var children = [];

/*
    Užkrauti pirminius parametrus, kurie naudojami tolimesnėse užklausose
 */

initParam();

/*
#    Iniciatoriai (Init funkcijos)   #
*/

function initParam(confirm){
    confirm = typeof confirm !== 'undefined' ? confirm : 0;
    $.ajax({
        type: "GET",
        url: CVMaps.paths.h,
        data: {
            c: "q",
            m: "init_param",
            category_id:_category_id,
            city_id: _city_id,
            edu_id: _edu_id,
            salary: _salary,
            work_time_id: _work_time,
            user_id: (param.show === "my" ? param.user_id : 0)
        },
        success: function(p){
            param.jobs = p.jobs;
        },
        complete: function(){
            // Užkrauti skelbimus jeigu parametrai nustatyti
            if(j.length < 1) getJobs(confirm);
        }
	});
}

function initList() {
    $('.window__list').animate({
        scrollTop: 0
    }, 300);
    var start = (_p-1) * 30,
        end;
    if(param.jobs < 30){
        end = (start + param.jobs);
    } else {
        end = (start + 30);
    }

    // Skelbimų skaičius nustatymas paskutiniame puslapyje
    if(_p == tp){
        end = start + (param.jobs - (tp - 1) * 30);
    }

    $("#jobs-count").html(param.jobs);

    var list = $(".window__list ul");
    list.html("");

    if(typeof j !== "undefined") {
        for (var i = start; i < end; i++) {
            var salary = salaryToString(j,i);
            if(isJobMine(j[i].id)){
                list.append('<span class="btn btn--small btn--silver" style="margin: 5px 0 0 5px;" id="edit-job" onclick="edit_job(this)" data-id="'+j[i].id+'">Redaguoti</span> <span style="margin: 5px 0 0 5px;" class="btn btn--small btn--silver" id="delete-job" onclick="delete_job(this)" data-id="'+j[i].id+'">Pašalinti</span>');
            }
            list.append('<li><a href="'+CVMaps.paths.h+'?c=q&m=redirect&u='+j[i].url+'" target="_blank" class="link--offer clearfix" title="Parodyti darbo skelbimą - ' + j[i].title + '"><div class="offer-logo"><img src="'+CVMaps.paths.i()+'l/' + j[i].logo + '" width="74"></div><div class="offer-content"><h5>' + j[i].title + '</h5><div class="offer-company">' + j[i].company + '</div><div class="offer-salary">' + (salary.length > 3 ? salary : "") + '</div></div><div class="offer-right offer-right-inactive"><div class="offer-gauge"></div><div class="offer-walktime"><img src="https://camo.githubusercontent.com/a771824a60b7024060bd0970d06e9aa5c1e2bdd0/68747470733a2f2f662e636c6f75642e6769746875622e636f6d2f6173736574732f3133333031362f3536343239372f63386430333463322d633535322d313165322d383764322d3430366638353630646234362e706e67" width="12">— min.</div></div></a>');

            list.append('</li>');
        }
    }
    $("#pg-current").html(_p);
}

function initList2() {
    tp = countPages();
    $("#pg-total").html(tp);
    paginationUpdate();
    $('.window__list').animate({
        scrollTop: 0
    }, 300);
    var start = (_p-1) * 30,
        end;
    if(param.jobs < 30){
        end = (start + param.jobs);
    } else {
        end = (start + 30);
    }

    // Skelbimų skaičius nustatymas paskutiniame puslapyje
    if(_p == tp){
        end = start + (param.jobs - (tp - 1) * 30);
    }

    $("#jobs-count").html(param.jobs);

    var list = $(".window__list ul");
    list.html("");

    if(typeof j !== "undefined") {
        for (var i = 0; i < j.length; i++) {
            if(j[i].hasOwnProperty("points")){
                var salary = salaryToString(j,i);
            list.append('<li><a href="'+CVMaps.paths.h+'?c=q&m=redirect&u='+j[i].url+'" target="_blank" class="link--offer clearfix" title="Parodyti darbo skelbimą - ' + j[i].title + '"><div class="offer-logo"><img src="'+CVMaps.paths.i()+'l/' + j[i].logo + '" width="74"></div><div class="offer-content"><h5>' + j[i].title + '</h5><div class="offer-company">' + j[i].company + '</div><div class="offer-salary">' + (salary.length > 3 ? salary : "") + '</div></div><div class="offer-right"><div class="offer-gauge"><div class="gauge-arrow" style="transform: rotate('+j[i].gauge+'deg)"></div></div><div class="offer-walktime"><img src="https://camo.githubusercontent.com/a771824a60b7024060bd0970d06e9aa5c1e2bdd0/68747470733a2f2f662e636c6f75642e6769746875622e636f6d2f6173736574732f3133333031362f3536343239372f63386430333463322d633535322d313165322d383764322d3430366638353630646234362e706e67" width="12">'+Math.floor(j[i].time)+' min.</div></div></a></li>');
            }

        }
    }
    $("#pg-current").html(_p);
}

function initMap() {
    notification("loading","Paruošiamas žėmelapis");
var cvMapsStyle=new google.maps.StyledMapType([{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#c0e4f3"},{"visibility":"on"}]}],{name:"CV Maps"});

	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 54.694988, lng: 25.278570},
		zoom: 12,
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
        $("#toggle_list").css({ opacity: 0 });
    });

    map.addListener('dragend', function() {
        $(".window").css({ opacity: 1 });
        $("#toggle_list").css({ opacity: 1 });
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

function initAddressBox2(){
    var input = document.getElementById('edit-address');
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
        });
    });
}

/*
#    Get funkcijos  #
 */

function getMarkers(){
    $.ajax({
        type: "GET",
        url: CVMaps.paths.h,
        data: {
            c: "q",
            m: "m",
            category_id:_category_id,
            city_id: _city_id,
            edu_id: _edu_id,
            salary: _salary,
            work_time_id: _work_time,
            user_id: (param.show === "my" ? param.user_id : 0)
        },
        success: function(response){
            $.each( response, function( key, val ) {
                c.push({
                    id: val.jid,
                    mid: val.mid,
                    lat: val.lat,
                    lng: val.lng,
                    avg: val.avg_sal,
                    credit: val.credit,
                    u: val.u
                });
            });
            groupMarkers(c);
            initMap();
        }
	});
}

function getJobs(confirm){
    notification("loading","Vyksta darboviečių kėlimas iš duomenų bazes");
    confirm = typeof confirm !== 'undefined' ? confirm : 0;
    $.ajax({
       type: "GET",
        url: CVMaps.paths.h,
        data: {
            c: "q",
            m: "j",
            page: _p,
            order_by: _order_by,
            category_id:_category_id,
            city_id: _city_id,
            edu_id: _edu_id,
            salary: _salary,
            work_time_id: _work_time,
            user_id: (param.show === "my" ? param.user_id : 0)
        },
        success: function(response){
            $.each( response, function( key, val ) {
                j.push({
                    id: val.id,
                    title: val.title,
                    company: val.company,
                    logo: val.logo,
                    salary_from: val.salary_from,
                    salary_to: val.salary_to,
                    url: val.url
                });
                // Tikriname, ar visi darbai įrašyti j masyve
                if(key == response.length-1){
                    initList();
                }
            });

            tp = countPages();
            $("#pg-total").html(tp);
            dp.push(_p);
        },
        complete: function(){
            if(confirm){
                $.ajax({
                    type: "GET",
                    url: CVMaps.paths.h,
                    data: {
                        c: "q",
                        m: "m",
                        category_id:_category_id,
                        city_id: _city_id,
                        edu_id: _edu_id,
                        salary: _salary,
                        work_time_id: _work_time
                    },
                    success: function (response) {
                        clearMarkers();
                        $.each(response, function (key, val) {
                            c.push({id: val.jid, mid: val.mid, lat: val.lat, lng: val.lng, avg: val.avg_sal, credit: val.credit});
                        });

                        groupMarkers(c);
                        renderMarkers();

                        if(home_radius.length > 0) findNearest(home_marker[0].position.lat(),home_marker[0].position.lng());
                    }
                });
            }
        }
    });
}

/*
#     Rendereriai (Render funkcijos)     #
 */

function renderMarkers(){
    notification("loading","Vyksta darboviečių atvaizdavimas");
    var infoWindow = new google.maps.InfoWindow(),
        image = CVMaps.paths.i() + "marker_plus.png";

	for(var i=0; i<c.length; i++){
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
                image = CVMaps.paths.i() + "marker_plus.png";
            }

			var position = new google.maps.LatLng(c[i].lat, c[i].lng);
            var marker_id = c[i].mid;
            var job_id = c[i].id;

			var marker = new google.maps.Marker({
				position: position,
				map: map,
				icon: image,
                marker_id: marker_id,
                job_id: job_id
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
                    var content = '', salary = '';
                    $.ajax({
                        type: "GET",
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
                                                content += '<li><div><a style="color:'+children[childs_arr_id].color+'; font-size:'+(children[childs_arr_id].scale+5)+'px" href="'+CVMaps.paths.h+'?c=q&m=redirect&u='+val.u+'" target="_blank">'+val.title+'</a></div>';
                                            } else {
                                                content += '<li><div><a href="'+CVMaps.paths.h+'?c=q&m=redirect&u='+val.u+'" target="_blank">'+val.title+'</a></div>';
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
                                content += response[0].title+'<div class="m-company">'+response[0].company+'</div><div class="m-price">'+(salary.length > 3 ? salary : "")+'</div><a href="'+CVMaps.paths.h+'?c=q&m=redirect&u='+c[i].u+'" target="_blank" class="m-button">Parodyti skelbimą</a>';

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
#    Paieškos, tvarkymo ir filtravimo funkcijos   #
 */
$("#category_id").on("change", function(){
   _category_id = parseInt($("#category_id").val());
    j = [];
    initParam(true);
    $("input[type=search]").val("");

});

$("#work_time_id").on("change", function(){
    _work_time = parseInt($(this).val());
    j = [];
    initParam(true);
});

$("#edu_id").on("change", function(){
    _edu_id = parseInt($(this).val());
    j = [];
    initParam(true);
});


$("#salary_from").on("input change", function(e){
    if(e.type === "input"){
        $("#salary_from_value").html($(this).val());
    } else {
        _salary = $(this).val();
        $("#salary_from_value").html(_salary);
        j = [];
        initParam(true);
    }
});

$("#distance").on("input change", function(e){
    if(e.type === "input"){
        $("#distance_value").html($(this).val());
    } else {
        var time = $(this).val();
        $("#distance_value").html(time);
        var distance = (((time / 60)  * 5.1) / 1.5) * 1000;
        search_radius = Math.ceil(distance);
        if(home_marker.length > 0){
            placeMarkerAndPanTo(home_marker[0].position, map);
        } else {
            placeMarkerAndPanTo(map.getCenter(), map);
        }
    }
});

/*
#    Namo adreso nustatymo funkcijos  #
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

    findNearest(latLng.lat(),latLng.lng());
}

/*
#    Skelbimų 'kainos' nustatymo funkcijos    #
 */

function getDuration(origin){
    notification("loading","Apskaičiuojami atstumai iki darbo vietų");
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
        notification("error","Pažymetoje vietoje darbų nerasta.");
        restoreJobRanking();
        return;
    }
    /*
        Locations parametruose, pirma reikšmė - vieta, nuo kurios skaičiuojame atstumas
     */
    $.ajax({
      type: 'GET',
      url: 'https://matrix.mapzen.com/one_to_many',
        data: {
            json: '{' +
            '"locations":' +
                '[{"lat":'+origin[0]+',"lon":'+origin[1]+'},'+destinations+'],' +
            '"costing":' +
                '"pedestrian"' +
            '}',
            api_key: "mapzen-cqWzJVB"
        },
      dataType: 'json',
      success: function(jsonData) {
          jQuery.each( jsonData.one_to_many[0], function( i, val ) {
              if(val.to_index != 0){
                  /* c[i-1] - dėl to, kad pirmas rezultatas tai taškas nuo kurio skaičiavome atstumą, ir jis
                    bus lygus nuliui, todėl ignoruojame
                  */
                  var id = arrayObjectIndexOf(j,nearest_jobs[i-1].id,"id");
                  j[id].time = round((val.time/60),1);
              }
            });
            jobRanking();

      },
        error: function() {
            notification("error","Įvyko klaida. Galimos priežastys: 1) viršytas leistinų skelbimų skaičius. Pabandykite sumažinti laiką iki darbo (atstumą); 2) viršytas leistinų paieškų per sekundę. Pabandykite dar kartą po 1 min.");
        }
    });
}

function findNearest(lat,lng){
    for(var i=0; i<c.length; i++){
        var distance = getDistanceFromLatLonInKm(lat,lng,c[i].lat,c[i].lng);
        if(distance <= (search_radius / 1000)){
            c[i].nearest = 1;
        } else {
            c[i].nearest = 0;
        }

    }
    getDuration([lat,lng]);
}

/*
    Nenaudojama funkcija, kurio tikslas rodyti TIK šalia esančius skelbimus
 */
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

function restoreJobRanking(){
	for(var i=0; i<markers.length; i++){
		if(markers[i].hasOwnProperty("points")){
			delete markers[i].points;
		}

        if(typeof markers[i].icon === "string"){
            markers[i].setIcon(CVMaps.paths.i() + "marker_plus.png");
        } else {
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

    for(var i=0; i<j.length; i++){
        if(j[i].hasOwnProperty("points")){
			delete j[i].points;
		}
    }

    children = [];
}

function jobRanking(){
    // marker, c[], j[] ir kt. parametrų reišmių nustatymas pagal nutylėjimą
	restoreJobRanking();

    notification("loading","Darbai rūšiojami pagal Jūsų parametrus");

    var data = [];

    for(var i=0; i< c.length; i++){
        if(c[i].nearest === 1){
            var jid = c[i].id;
            var j_arr_id = arrayObjectIndexOf(j,jid,"id");
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
    var result;

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
            param.weights.salary * salary[i]
            + param.weights.distance * distance[i]
            + param.weights.average_salary * average_salary[i]
            + param.weights.credit * credit[i];

        data[i].points = round(result,4);
    }

    for(var i=0; i<data.length; i++){
        var jid = arrayObjectIndexOf(j,data[i].id,"id");
        j[jid].points = data[i].points;
        if(hasChildren(data[i].id) || isChild(data[i].id)){
            children.push({id: data[i].id, points: data[i].points});
        } else {
            var mid = arrayObjectIndexOf(markers,data[i].id,"job_id");
            markers[mid].points = data[i].points;
        }

    }

    scaleDownValues();
    param.jobs = 0;
    for(var i=0; i< j.length; i++){
        if(j[i].hasOwnProperty("points")) param.jobs++;
    }
    initList2();
    $("#color-scale").show();


}

function scaleDownValues(){
    notification("loading","Apskaičiavimo rezultatai ruošiami atvaizdavimui");
	var min = 15,
		max = 100,
		ratio;
    var smin = 6,
        smax = 12,
        sratio;
    var gmin = 0,
        gmax = 180,
        gratio;

	var data = [];
	for(var i=0; i<markers.length; i++){
		if(markers[i].hasOwnProperty("points")){
			data.push({id: i, points: markers[i].points});
		}
	}

    for(var i=0; i<children.length; i++){
		if(children[i].hasOwnProperty("points")){
			data.push({id: children[i].id, points: children[i].points, not_marker: true});
		}
	}

	var max_value = Math.max.apply(Math,data.map(function(o){return o.points;})),
		min_value = Math.min.apply(Math,data.map(function(o){return o.points;}));

	ratio = (max - min)/(max_value - min_value);
    sratio = (smax - smin)/(max_value - min_value);
    gratio = (gmax - gmin)/(max_value - min_value);

	for(var i=0; i<data.length; i++){
		var value = min + ratio * (data[i].points - min_value);
        var gvalue = gmin + gratio * (data[i].points - min_value);
		var mid = data[i].id;
        var jid = markers[mid].job_id;
        var job_arr_id = arrayObjectIndexOf(j,jid.toString(),"id");
        j[job_arr_id].gauge = Math.round(gvalue);
		var color = rgb2hex(valueToColor(value));

		var scale_value = smin + sratio * (data[i].points - min_value);
        if(data[i].hasOwnProperty("not_marker")){
            var id = arrayObjectIndexOf(children,data[i].id,"id");
            children[id].color = color;
            children[id].scale = scale_value;
        } else {
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

}

/*
#    Įvykių tvarkytojai   #
 */

$("#window_sort_close").click(function(){
   wsort();
});

$("#cancel-sort").click(function() {
    $("input[type=search]").val("");
    _p = 1;
    _order_by = "date_desc";
    _city_id = 0;
    _category_id = 0;
    _edu_id = 0;
    _salary = 0;
    _work_time = 0;

    j = [];
    if(home_marker.length > 0){
        initParam(true);
    } else {
        initParam();
    }

    wsort();

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
        if(home_marker.length > 0){
            initList2();
        } else {
            initList();
        }
    }
});

$("#page-next").on("click", function(){
    if(_p < tp) {
        _p = _p + 1;
        paginationUpdate();
        if(home_marker.length > 0){
            initList2();
        } else {
            initList();
        }

    }
});

$("#toggle_list").on("click", function(){
    $("#jobs").slideToggle();
    $("#show_list").toggle();
    $("#toggle_list").toggle();
});

$("#show_list").on("click", function(){
    $("#jobs").slideToggle();
    $("#show_list").toggle();
    $("#toggle_list").toggle();
});

function edit_job(el){
    var id = $(el).data("id");
    $("#edit_job").show();

    $.ajax({
        type: "GET",
        url: CVMaps.paths.h,
        data: {
            c: "home",
            m: "get_job_by_id",
            id: id
        },
        success: function(val){
            setTimeout(function() { initAddressBox2(); }, 2000);

            var form = $("#edit_job > form:nth-child(1)");
            form.attr("action", form.attr("action") + id.toString());
            $("#edit-title").val(val.title);
            $("#edit-category_id").val(parseInt(val.category_id));
            $("#edit-city_id").val(parseInt(val.city_id));
            $("#edit-address").val(val.address);
            $("#edit-salary_from").val(parseFloat(val.salary_from));
            $("#edit-salary_to").val(parseFloat(val.salary_to));
            $("#edit-work_time_id").val(parseInt(val.work_time_id));
            $("#edit-edu_id").val(parseInt(val.edu_id));
            $("#edit-url").val(val.url);
        }
    });
}

function delete_job(el){
    var id = $(el).data("id");

    $.ajax({
        type: "GET",
        url: CVMaps.paths.h,
        data: {
            c: "backoffice",
            m: "delete_job",
            id: id
        },
        complete: function(){
            location.reload();
        }
    });
}

var window_height = $(window).height() - $(".header").outerHeight();
$("#map").css("height",window_height);
var addEvent = function(object, type, callback) {
    if (object == null || typeof(object) == 'undefined') return;
    if (object.addEventListener) {
        object.addEventListener(type, callback, false);
    } else if (object.attachEvent) {
        object.attachEvent("on" + type, callback);
    } else {
        object["on"+type] = callback;
    }
};

addEvent(window, "resize", function(event) {
    window_height = $(window).height() - $(".header").outerHeight();
    $("#map").css("height",window_height);
});

$(".authorize").hide();

$("#not-authorized").on("click",function(){
    $(".authorize").toggle();
});

$(".edit-company").hide();

$("#authorized").on("click",function(){
    $(".edit-company").toggle();
});

$("#jobs-switcher-my").on("click", function(){
    $(this).hide();
    $("#jobs-switcher-all").show();
    param.show = "my";
    switchJobs();
});

$("#jobs-switcher-all").on("click", function(){
    $(this).hide();
    $("#jobs-switcher-my").show();
    param.show = "all";
    switchJobs();
});

$("#create-job").on("click", function(){
    $("#jobs").toggle();
    $("#add_job").toggle();
});

$("#delete-job").on("click", function(){
    var id = $(this).data("id");

});

$("#weight_distance").on("change", function(){
    changeWeights("distance",$(this).val());
});

$("#weight_salary").on("change", function(){
    changeWeights("salary",$(this).val());
});

$("#weight_average_salary").on("change", function(){
    changeWeights("average_salary",$(this).val());
});

$("#weight_credit").on("change", function(){
    changeWeights("credit",$(this).val());
});

$("#weights_reset").on("click", function(){
    param.weights.distance = 0.25;
    param.weights.salary = 0.50;
    param.weights.average_salary = 0.18;
    param.weights.credit = 0.07;
    $("#weight_distance").val(param.weights.distance);
    $("#weight_average_salary").val(param.weights.average_salary);
    $("#weight_credit").val(param.weights.credit);
    $("#weight_salary").val(param.weights.salary);
    if(home_radius.length > 0){
        jobRanking();
    }
    notification("success","Veiksmas sekmingai atliktas!");
});

function switchJobs(){
    param.jobs = 0;
    j = [];
    clearMarkers();
    initParam();
    getMarkers();
}

/*
#    Kitos funkcijos  #
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
    return Math.ceil(param.jobs / 30);
}



function hasChildren(cid){
    var id = arrayObjectIndexOf(c,cid,"id");
    return typeof c[id].s === "object";
}

function isChild(cid){
    var id = arrayObjectIndexOf(c,cid,"id");
    return typeof c[id].s !== "object" && typeof c[id].s !== "string";
}

function round(number, precision) {
    var factor = Math.pow(10, precision);
    var tempNumber = number * factor;
    var roundedTempNumber = Math.round(tempNumber);
    return roundedTempNumber / factor;
}


function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
  var R = 6371;
  var dLat = deg2rad(lat2-lat1);
  var dLon = deg2rad(lon2-lon1);
  var a =
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ;
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  var d = R * c;
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}

function valueToColor(val) {
    val = 115 - val;

	var r = Math.floor((255 * val) / 100),
	    g = Math.floor((255 * (100 - val)) / 100),
	    b = 0;

	return "rgb(" + r + "," + g + "," + b + ")";
}

// Spalvos konvertavimas iš RGB formato į HEX
function rgb2hex(rgb){
 rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
 return (rgb && rgb.length === 4) ? "#" +
  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}


function salaryToString(response,key){
    key = typeof key !== 'undefined' ? key : 0;
    var salary = '';
    if (response[key].salary_from !== null && response[key].salary_to === null ) {
        salary += 'Nuo ' + response[key].salary_from;
    } else if (response[key].salary_from === null && response[key].salary_to !== null) {
        salary += 'Iki ' + response[key].salary_to;
    } else if (response[key].salary_from !== null && response[key].salary_to !== null) {
        salary += response[key].salary_from + " - " + response[key].salary_to;
    }
    return salary += ' €';
}

function isJobMine(jid){
    if(param.user_jobs_ids){
        for(var i= 0; i<param.user_jobs_ids.length; i++){
            if(param.user_jobs_ids[i].id === jid){
                return true;
            }
        }
    }
}

function changeWeights(type,val){
    var diff;
    switch(type) {
        case "distance":
            diff = parseFloat(val) - param.weights.distance;
            param.weights.distance = parseFloat(val);
            break;

        case "salary":
            diff = parseFloat(val) - param.weights.salary;
            param.weights.salary = parseFloat(val);
            break;

        case "average_salary":
            diff = parseFloat(val) - param.weights.average_salary;
            param.weights.average_salary = parseFloat(val);
            break;

        case "credit":
            diff = parseFloat(val) - param.weights.credit;
            param.weights.credit = parseFloat(val);
            break;
        default:
            break;
    }

    recalculateWeights(type,diff);
}

function recalculateWeights(name,diff){
    var sum = 0,
        salary = param.weights.salary,
        average_salary = param.weights.average_salary,
        credit = param.weights.credit,
        distance = param.weights.distance;

    sum += (name != "distance" ? distance : 0);
    sum += (name != "salary" ? salary : 0);
    sum += (name != "average_salary" ? average_salary : 0);
    sum += (name != "credit" ? credit : 0);

    if(name !== "distance"){
        param.weights.distance = distance - (diff * (distance / sum));
        $("#weight_distance").val(param.weights.distance);
    }

    if(name !== "salary"){
        param.weights.salary = salary - (diff * (salary / sum));
        $("#weight_salary").val(param.weights.salary);
    }

    if(name !== "average_salary"){
        param.weights.average_salary = average_salary - (diff * (average_salary / sum));
        $("#weight_average_salary").val(param.weights.average_salary);
    }

    if(name !== "credit"){
        param.weights.credit = credit - (diff * (credit / sum));
        $("#weight_credit").val(param.weights.credit);
    }

    if(home_radius.length > 0){
        jobRanking();
    }
}

function notification(type,text){
    $(".notification--default").remove();
    $(".notification--loading").remove();
    $("body").append('<div class="notification notification--'+type+'">'+text+'</div>');
    if(type === "loading"){
        $(".notification--loading").prepend('<div class="spinner"></div>');
        $(".notification--loading").delay( 2000 ).fadeOut( 1000 );
    } else {
        $(".notification").delay( 10000 ).fadeOut( 1000 );
    }


}

