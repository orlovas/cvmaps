'use strict';

var CVMaps = {
  paths: {
      h: document.URL.substr(0,document.URL.lastIndexOf('/')) + "/",
      s: function(){return this.h + "static/"},
      i: function(){return this.s() + "images/"}
  }
};
var map, markers = [], markerCluster = {};
var _p = 1,
    _order_by = "date_desc",
    _city_id = 0,
    _category_id = 0,
    _edu_id = 0,
    _salary = 0,
    _work_time = 0;

var c = []; // coordinates
var j = []; // jobs
var tp = 0; // total pages
var param = {
   jobs: 0
};
var dp = []; // downloaded pages
var home_marker = [];
var home_radius = [];
var set_home_position;
var search_radius = 2000;
var cluster_options = {
    imagePath: CVMaps.paths.i()+'m',
    gridSize: 40,
    maxZoom: 14
};

var children = [];

/*
    Load homepage parameters and load up jobs list
 */

initParam();

/*
#    Initiators   #
*/

function initParam(confirm){
    confirm = typeof confirm !== 'undefined' ? confirm : 0;
    $.ajax({
        type: "GET",
        //url: "q/init_param/"+_city_id+"/"+_category_id+"/"+_edu_id+"/"+_salary+"/"+_work_time,
        url: CVMaps.paths.h,
        data: {
            c: "q",
            m: "init_param",
            category_id:_category_id,
            city_id: _city_id,
            edu_id: _edu_id,
            salary: _salary,
            work_time_id: _work_time
        },
        success: function(p){
            param.jobs = p.jobs;
        },
        complete: function(){
            // loading jobs if parameters set
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

    // vycheslenie kol-vo objavlenij na poslednej stranice
    if(_p == tp){
        end = start + (param.jobs - (tp - 1) * 30);
    }

    $("#jobs-count").html(param.jobs);

    var list = $(".window__list ul");
    list.html("");

    if(typeof j !== "undefined") {
        for (var i = start; i < end; i++) {
            var salary = salaryToString(j,i);
            list.append('<li><a href="" class="link--offer clearfix" title="Parodyti darbo skelbimą - ' + j[i].title + '"><div class="offer-logo"><img src="static/images/l/' + j[i].logo + '" width="74"></div><div class="offer-content"><h5>' + j[i].title + '</h5><div class="offer-company">' + j[i].company + '</div><div class="offer-salary">' + (salary.length > 3 ? salary : "") + '</div></div></a></li>');
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

    // vycheslenie kol-vo objavlenij na poslednej stranice
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
            list.append('<li><a href="" class="link--offer clearfix" title="Parodyti darbo skelbimą - ' + j[i].title + '"><div class="offer-logo"><img src="static/images/l/' + j[i].logo + '" width="74"></div><div class="offer-content"><h5>' + j[i].title + '</h5><div class="offer-company">' + j[i].company + '</div><div class="offer-salary">' + (salary.length > 3 ? salary : "") + '</div></div><div class="offer-right"><div class="offer-gauge"><div class="gauge-arrow" style="transform: rotate('+j[i].gauge+'deg)"></div></div><div class="offer-walktime"><img src="https://camo.githubusercontent.com/a771824a60b7024060bd0970d06e9aa5c1e2bdd0/68747470733a2f2f662e636c6f75642e6769746875622e636f6d2f6173736574732f3133333031362f3536343239372f63386430333463322d633535322d313165322d383764322d3430366638353630646234362e706e67" width="12">'+Math.floor(j[i].time)+' min.</div></div></a></li>');
            }

        }
    }
    $("#pg-current").html(_p);

}

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
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
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
#    Getters  #
 */

function getMarkers(){
    $.ajax({
        type: "GET",
        //url: "q/m/"+_category_id+"/"+_city_id+"/"+_edu_id+"/"+_salary+"/"+_work_time,
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

function getJobs(confirm){
    confirm = typeof confirm !== 'undefined' ? confirm : 0;
    $.ajax({
       type: "GET",
        //url: "q/j/"+_p+"/"+_order_by+"/"+_city_id+"/"+_category_id+"/"+_edu_id+"/"+_salary+"/"+_work_time,
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
            work_time_id: _work_time
        },
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

            tp = countPages();
            $("#pg-total").html(tp);

            dp.push(_p);
        },
        complete: function(){
            if(confirm){
                $.ajax({
            type: "GET",
            //url: "q/m/" + _category_id + "/" + _city_id + "/" + _edu_id + "/" + _salary + "/" + _work_time,
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

                /*for (var i = 0; i < confirm[1].length; i++) {
                    var id = arrayObjectIndexOf(c, confirm[1][i], "id");
                    if (id >= 0) {
                        c[id].nearest = 1;
                    }
                }

                for (var i = 0; i < confirm[0].length; i++) {
                    var id = arrayObjectIndexOf(j, confirm[0][i].id, "id");
                    if (id >= 0) {
                        j[id].time = confirm[0][id].time;
                    }
                }*/
                if(home_radius.length > 0) findNearest(home_marker[0].position.lat(),home_marker[0].position.lng());
                //getDuration([home_marker[0].position.lat(),home_marker[0].position.lng()]);
                //jobRanking();
            }
        });
            }
        }
    });
}

/*
#     Renders     #
 */

function renderMarkers(){
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

			//var marker = new MarkerWithLabel({
			var marker = new google.maps.Marker({
				position: position,
				map: map,
				icon: image,
                marker_id: marker_id,
                job_id: job_id/*,
                labelClass : "marker_label"*/
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
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
                                    //url: "q/get_jobs/"+ids,
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
    Search by city
 */
$("#city_id").on("change", function(){
    _city_id = parseInt($("#city_id").val());
    j = [];
    initParam(true);
});

$("#work_time").on("change", function(){
    _work_time = parseInt($("#work_time").val());
    j = [];
    initParam(true);
});

$("#salary_from").on("change", function(){
    _salary = $("#salary_from").val();

    $("#salary_from_value").html(_salary);
   /* var j_buffer = [];
    for(var i=0; i< j.length; i++){
        if(j[i].hasOwnProperty("time")){
            j_buffer.push({id: j[i].id, time:j[i].time});
        }
    }

    var c_buffer = [];
        for (var i = 0; i < c.length; i++) {
            if (c[i].nearest === 1) {
                c_buffer.push(c[i].id);
            }
        }*/

    j = [];
    initParam(true);


});

$("#distance").on("change", function(){
    var time = $("#distance").val();
    $("#distance_value").html(time);
    var distance = (((time / 60)  * 5.1) / 1.5) * 1000;
    search_radius = Math.ceil(distance);
    placeMarkerAndPanTo(map.getCenter(), map);

});

/*
    Search by category
 */
$("#category_id").on("change", function(){
   _category_id = parseInt($("#category_id").val());
    j = [];
    initParam(true);
    $("input[type=search]").val("");

});

/*$("#dd_category_id > a").on("click", function(event){
    event.preventDefault();
   _category_id = parseInt($(this).attr('href').substr(1));
    $("input[type=search]").val($(this).text());
    j = [];
    initParam(true);

});*/


/*
#    Home setter functions  #
 */

function enableSetHomePosition(){
    map.setOptions({draggableCursor:'crosshair'});
    set_home_position = map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });
}

function disableSetHomePosition(){
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
#    Job price calculation functions    #
 */

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

            jobRanking();

      },
        error: function() {
            console.log("Too many requests");
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


/*
#    Event handlers   #
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
    return Math.ceil(param.jobs / 30);
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

function jobRankingDelayed(){
    setTimeout(function() { jobRanking(); }, 1000);
}

function jobRanking(){
    // Return all markers', c[], j[] and other parameters to default
	restoreJobRanking();

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
            weight.salary * salary[i]
            + weight.distance * distance[i]
            + weight.average_salary * average_salary[i]
            + weight.credit * credit[i];

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
    initList2(); // temporary
    $("#color-scale").show();
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

// https://stackoverflow.com/questions/11121012/how-to-scale-down-the-values-so-they-could-fit-inside-the-min-and-max-values
function scaleDownValues(){
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

        //markers[mid].set('labelContent', j[job_arr_id].salary_from + "-" + j[job_arr_id].salary_to +" €");
	}
}

function valueToColor(val) {
    val = 115 - val;

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