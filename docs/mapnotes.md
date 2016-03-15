# Map Notes

## Importing google maps KML files

Expect:
```
<kml>
  <Document>
     <name>...</name>
     <Folder> (optional - = layer if whole map exported)
       <name>...</name>
       <Placemark>
         <name>...</name>
         <Point>
           <coordinates>LON,LAT,ALT</coordinates>
or
         <LineString>
           <tessellate>1</tessellate> (??)
           <coordinates>LON,LAT,ALT LON,LAT,ALT ...</coordinates>
```

`Placemark` order should follow that in google map layer. Coordinates 
of different line strings may not match exactly as each is drawn 
separately and does not (for example) snap to points.

So...

- each `LineString` end point is a candidate waypoint, but probably
  suppressed if it follows a `Point` that is close to it.

- If layers (`Folder`) are present then each would be considered a 
  separate route if it contained `LineString`s, or might contain other
  landmarks. Perhaps we will just take the first layer with `LineString`s
  as the route and just keep `Point`s as landmarks from the others.

## Internal format

Start with a list of consecutive route `segments`. Each `segment`
has a `title` and a list of coordinates. Each coordinate has a `lat`
and a `lon` (and optionally an `alt`).

Add a bag of points (landmarks, waypoints), each with `title`, `lat`
and `lon` (and optionally `alt`).

For each `segment` we calculate `length` (metres).
For each `segment` we also calculate closest approach to each 
`point`. If this is within some threshold (or some other future 
criteria) then it is added to the `segment`s `features`. Each
`feature` has a `title`, `lat`, `lon`, `distanceAlong` (metres,
of closest point on segment)
`distanceRight` (metres, to right of 'forward' direction) and
`distanceFrom` (metres). 


