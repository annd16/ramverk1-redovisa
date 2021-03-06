API
=========================

<p>The server of this web site provides a REST API for some of its services.</p>

<h2>IP-validator</h2>
<p>For a particular input sequence the following will be checked:</p>
- if it is a valid IPv4 or IPv6 address.
- if valid, it will also check if the address is within a private and/or reserved range.

<p>The following ranges are considered to be private:</p>
- IPv4: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.
- IPv6: addresses starting with FD or FC.

<p>The following ranges are considered to be reserved:</p>
- IPv4: 0.0.0.0/8, 169.254.0.0/16, 127.0.0.0/8 and 240.0.0.0/4.
- IPv6: ::1/128, ::/128, ::ffff:0:0/96 and fe80::/10.</p>

<p>The endpoint for the IP-validation is:</p>

<!-- http://www.student.bth.se/~annd16/dbwebb-kurser/ramverk1/me/redovisa/htdoc/ip/json/[address] -->

<code>GET /ip/json/[address]</code>

<p>This will give you the validation data for the (assumed) IP address, <em>address</em>.</p>

<p>The result will be in JSON format, and look something like this:</p>

<pre>

{
    "ip": "35.158.84.49",
    "version": "IPv4",
    "type": "not private",
    "host": "ec2-35-158-84-49.eu-central-1.compute.amazonaws.com",
    "message": "35.158.84.49 is a valid IPv4 address"
}
</pre>

<p>There is also a possibility to check several IP addresses at a time:</p>

<code>GET /ip/json/[address1]/<address2\>/<address3\>...</code>

<!-- &ltaddress2&gt/&ltaddress3&gt... -->


<p>Below are some examples:</p>

<!-- [example link](http://example.com/) -->

- [91.192.30.117 - a valid public IPv4 address with known domain](ip/json/91.192.30.117)
- [144.63.247.130    - a valid public IPv4 address without known domain.](ip/json/144.63.247.130)
- [2001:cdba::3257:9652    - a valid IPv6 address without known domain](ip/json/2001:cdba::3257:9652)
- [fd12:3456:789a:1::1    - a valid IPv6 private address](ip/json/fd12:3456:789a:1::1)
- [0.2.1.8    - a valid IPv4 reserved address](ip/json/0.2.1.8)
- [144.63.2    -  an invalid IP address](ip/json/144.63.2)
- [35.158.84.49 & 144.62.2   - two addresses, one valid and one invalid](ip/json/35.158.84.49/144.62.2)

<!--
</ul> -->


<h2>GeoLocator</h2>

This utility will return the geographical position for the given IP-address, and some information about this position (if the IP-address is valid and
this information is available).

<p>For a particular input sequence the following will be returned (if available):</p>

-IP-number
-IP-version
-latitude
-longitude
-message
-map (link to)
-country flag (link to svg-image)
-country name

```

```


<p>The endpoint for the Geo-locator is:</p>

<!-- http://www.student.bth.se/~annd16/dbwebb-kurser/ramverk1/me/redovisa/htdoc/ip/json/[address] -->

<code>GET /geo/json/[address]</code>

<p>This will give you the validation data for the (assumed) IP address, <em>address</em>.</p>

<p>The result will be in JSON format, and look something like this:</p>

<pre>

[
    {
        "ip": "128.67.89.4",
        "version": "Ipv4",
        "latitude": "43.1479",
        "longitude": "12.1097",
        "country_name": "Italy",
        "country_flag": "http://assets.ipstack.com/flags/it.svg",
        "map": "https://www.openstreetmap.org/?mlat=43.1479&mlon=12.1097",
        "message": "incoming ip address is 128.67.89.4 ipType is set "
    }
]

</pre>

<p>There is also a possibility to get geographical information for several IP addresses in one go:</p>

<code>GET /geo/json/[address1]/<address2\>/<address3\>...</code>

<!-- &ltaddress2&gt/&ltaddress3&gt... -->


<p>Below are some examples:</p>

<!-- [example link](http://example.com/) -->

- [91.192.30.117 - a valid public IPv4 address](geo/json/91.192.30.117)
- [144.63.247.130    - another a valid public IPv4 address.](geo/json/144.63.247.130)
- [144.63.2    -  an invalid IP address](geo/json/144.63.2)
- [35.158.84.49 & 144.62.2   - two addresses, one valid and one invalid](geo/json/35.158.84.49/144.62.2)
- [35.158.84.49 & 65.45.67.1   - two valid addresses](geo/json/35.158.84.49/65.45.67.1)


<h2>Weather</h2>

This utility will return the a weather forecast or history for the given IP-address, or geographical position.

<p>For a particular input sequence the following will be returned (if available):</p>

-IP-number
-IP-version
-latitude
-longitude
-message
-map (link to)
-country flag (link to svg-image)
-country name

```

```


<p>The endpoint for the Weather service is:</p>

<!-- http://www.student.bth.se/~annd16/dbwebb-kurser/ramverk1/me/redovisa/htdoc/ip/json/[address] -->

<code>GET /weather/json/<requestType/>[address]</code>

OR

<code>GET /weather/json/<requestType/>[latitude,longitude]</code>


<p>This will give you the validation data for the (assumed) IP address, <em>address</em>.</p>

<p>The result will be in JSON format, and look something like this:</p>

<pre>

[
{
"ip": "",
"version": "",
"latitude": "50",
"longitude": "-50",
"timezone": "America/St_Johns",
"date": "",
"typeOfRequest": "forecast",
"map": "https://www.openstreetmap.org/?mlat=50&mlon=-50",
"message": "incoming indata is 50,-50. <br/>NO response from IpStack.<br/>A response was received from DarkSky.",
"day1": {
    "date": "2019-Dec-05 04:12",
    "summary": "Possible light rain in the afternoon."
},
"day2": {
    "date": "2019-Dec-06 04:12",
    "summary": "Windy overnight and in the evening."
},
"day3": {
    "date": "2019-Dec-07 04:12",
    "summary": "Possible light snow in the evening."
},
...
}
]

</pre>

<p>Below are some examples:</p>

<!-- [example link](http://example.com/) -->
  <p>Forecasts</p>
- [91.192.30.117 - a valid public IPv4 address](weather/json/forecast/91.192.30.117)
- [50,-50 - a valid geolocation](weather/json/forecast/50,-50)

<p>Historic data</p>
- [144.63.247.130    - a valid public IPv4 address](weather/history/json/144.63.247.130)
- [12,179.33    -  a valid geolocation](weather/json/144.63.2)

<p>Invalid indata</p>
- [144.63.2   -  an invalid IP address](weather/json/144.63.2)
- [600,45   -  an invalid geolocation](weather/json/600,45)
