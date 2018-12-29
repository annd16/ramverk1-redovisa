---
views:
    kursrepo:
        region: sidebar-right
        template: anax/v2/block/default
        data:
            meta:
                type: single
                route: block/om-kursrepo

    redovisa:
        region: sidebar-right
        template: anax/v2/block/default
        data:
            meta:
                type: single
                route: block/om-redovisa
---
API
=========================

<!-- <div class="developers"> -->
    <!-- <h2> For developers: </h2> -->
<!-- <p>The server of this web site provides a REST API for the IP-checker, geolocator and the weather utility.</p> -->
<p>The server of this web site provides a REST API for some of its services.</p>

<h3> About the API </h3>

<p>The base url to use:</p>

<code><?= $request->getBaseUrl(); ?></code>


<h2>IP-checker</h2>
<p>For a particular input sequence the following will be checked:</p>
- if it is a valid IPv4 or IPv6 address.
- if valid, it will also check if the address is within a private and/or reserved range.

The following ranges are considered to be private:
IPv4: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.
IPv6: addresses starting with FD or FC.

The following ranges are considered to be reserved:
IPv4: 0.0.0.0/8, 169.254.0.0/16, 127.0.0.0/8 and 240.0.0.0/4.
IPv6: ::1/128, ::/128, ::ffff:0:0/96 and fe80::/10.</p>

<p>The endpoint for the IP-validation is:</p>

<!-- http://www.student.bth.se/~annd16/dbwebb-kurser/ramverk1/me/redovisa/htdoc/ip/json/[address] -->

<code>GET /ip/json/[address]</code>

<p>will give you the validation data for the (assumed) IP address, <em>address</em>.</p>

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

<code>GET /ip/json/[address1]/\<address2\>/\<address3\>...</code>

<!-- &ltaddress2&gt/&ltaddress3&gt... -->


<p>Below are some examples:</p>

<ul>

[example link](http://example.com/)
<!-- <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/35.158.84.49">91.192.30.117   - a valid public IPv4 address with known domain.</a></li>
<li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.247.130">144.63.247.130    - a valid public IPv4 address without known domain.</a></li>

<li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/2001:cdba::3257:9652">2001:cdba::3257:9652    - a valid IPv6 address without known domain</a></li>
<li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/fd12:3456:789a:1::1">fd12:3456:789a:1::1    - a valid IPv6 private address</a></li>
<li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/0.2.1.8">0.2.1.8    - a valid IPv4 reserved address</a></li>
<li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.2">144.63.2    -  an invalid IP address.</a></li>

<li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/35.158.84.49/144.62.2">35.158.84.49 &amp 144.62.2   - two adresses, one valid and one invalid.</a></li> -->

</ul>


- [91.192.30.117 - a valid public IPv4 address with known domain](http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/91.192.30.117)
- [144.63.247.130    - a valid public IPv4 address without known domain.](http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.247.130)
- [2001:cdba::3257:9652    - a valid IPv6 address without known domain](http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/2001:cdba::3257:9652)
- [fd12:3456:789a:1::1    - a valid IPv6 private address](http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/fd12:3456:789a:1::1)
- [0.2.1.8    - a valid IPv4 reserved address](http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/0.2.1.8)
- [144.63.2    -  an invalid IP address](http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.2)
- [35.158.84.49 & 144.62.2   - two addresses, one valid and one invalid](http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/35.158.84.49/144.62.2)


<!-- <h3>Geo-locator</h3> -->
