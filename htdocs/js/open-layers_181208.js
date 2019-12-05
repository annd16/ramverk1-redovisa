/**
 * @preserve Setup the basis for the responsive menu.
 * @author Mikael Roos <mos@dbwebb.se>
 * @see {@link https://github.com/janaxs/responsive-menu}
 */
(function() {
    "use strict";

    var map = "";
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat( -0.1279688 ,51.5077286 )
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            map.getProjectionObject() // to Spherical Mercator Projection
          );

    var zoom=16;

    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);

    markers.addMarker(new OpenLayers.Marker(lonLat));

    map.setCenter (lonLat, zoom);

    })();




// <html><body>
//   <div id="mapdiv"></div>
//   <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
//   <script>
//     map = new OpenLayers.Map("mapdiv");
//     map.addLayer(new OpenLayers.Layer.OSM());
//
//     var lonLat = new OpenLayers.LonLat( -0.1279688 ,51.5077286 )
//           .transform(
//             new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
//             map.getProjectionObject() // to Spherical Mercator Projection
//           );
//
//     var zoom=16;
//
//     var markers = new OpenLayers.Layer.Markers( "Markers" );
//     map.addLayer(markers);
//
//     markers.addMarker(new OpenLayers.Marker(lonLat));
//
//     map.setCenter (lonLat, zoom);
//   </script>
// </body></html>
