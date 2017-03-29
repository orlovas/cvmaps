$("#salary_from").on("change", function(e){
    if(e.type === "input"){
        $("#salary_from_value").html($(this).val());
    } else {
        _salary = $(this).val();
        $("#salary_from_value").html(_salary);
        j = [];
        initParam(true);
    }
});

$("#distance").on("change", function(e){

        var time = $(this).val();
        $("#distance_value").html(time);
        var distance = (((time / 60)  * 5.1) / 1.5) * 1000;
        search_radius = Math.ceil(distance);
        if(home_marker.length > 0){
            placeMarkerAndPanTo(home_marker[0].position, map);
        } else {
            placeMarkerAndPanTo(map.getCenter(), map);
        }
});