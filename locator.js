//const { locatorToLatLng, distance } = require('locator');
const CHAR_CODE_OFFSET = 65;
const EARTH_RADIUS = 6378.2064;
const PI_h =Math.PI;
//const isValidLocatorString = locatorString => locatorString.match(/^[A-R][A-R]\d\d[a-x][a-x]/) !== null;
const isValidLocatorString = locatorString => locatorString.match(/^[A-R][A-R]\d\d[A-Xa-x][A-Xa-x]/) !== null;

const charToNumber = (char) => {
  return char.toUpperCase().charCodeAt(0) - CHAR_CODE_OFFSET;
}

const locatorToLatLng = (locatorString) => {
  if (!isValidLocatorString(locatorString)) {
    locatorString='JO52tg';
	//throw new Error('Input is not valid locator string');
  }

  const squareLng = charToNumber(locatorString[0]) * 20;
  const squareLat = charToNumber(locatorString[1]) * 10;

  const gridLng = Number.parseInt(locatorString[2]) * 2;
  const gridLat = Number.parseInt(locatorString[3]);

  const subsquareLng = (charToNumber(locatorString[4]) + 0.5) / 12;
  const subsquareLat = (charToNumber(locatorString[5]) + 0.5) / 24;

  return [
    squareLat + gridLat + subsquareLat - 90,
    squareLng + gridLng + subsquareLng - 180
  ];
};

const degToRad = deg => (deg % 360) *PI_h /180;

const distance = (from, to) => {
  const fromCoords = locatorToLatLng(from);
  const toCoords = locatorToLatLng(to);
  const dLat = degToRad(toCoords[0] - fromCoords[0]);
  const dLon = degToRad(toCoords[1] - fromCoords[1]);
  const fromLat = degToRad(fromCoords[0]);
  const toLat = degToRad(toCoords[0]);

  const a = Math.pow(Math.sin(dLat / 2), 2) + Math.pow(Math.sin(dLon / 2), 2) * Math.cos(fromLat) * Math.cos(toLat);
  const b = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

  return 1+parseInt(b * EARTH_RADIUS);
};

var azimuth = function azimuth(from, to) {
  let lon_a,lat_a,lon_b,lat_b, bearing, cos_gc_arc, cos_bearing, sin_bearing, lon_diff;

  let [ lat1, lon1 ] = locatorToLatLng(from);
  let [ lat2, lon2 ] = locatorToLatLng(to);
  lon_a=lon1*PI_h/180.0;
  lat_a=lat1*PI_h/180.0;
  lon_b=lon2*PI_h/180.0;
  lat_b=lat2*PI_h/180.0;

  lon_diff = lon_b - lon_a;
  cos_gc_arc = Math.cos(lon_diff)*Math.cos(lat_a)*Math.cos(lat_b) + Math.sin(lat_a)*Math.sin(lat_b);

  cos_bearing  = Math.sin(lat_b) - Math.sin(lat_a) * cos_gc_arc;
  sin_bearing  = Math.sin(lon_diff) * Math.cos(lat_a) * Math.cos(lat_b);
  bearing = Math.atan2(sin_bearing, cos_bearing);

  if ( bearing < 0.0 ) {
    bearing = (2*PI_h) + bearing;
    bearing = (180/PI_h*bearing);
  } else {
    bearing = (180/PI_h*bearing);	  
  }	  

  /* Convert to degrees */
  return parseInt(bearing);
}

//locatorToLatLng('IO91wm'); // [51.521, -0.125]
//alert(distance('IO91wm', 'KP20le'))	; // 1821.5 km
//azimuth('LO97xw', 'KP20le'); // 292

