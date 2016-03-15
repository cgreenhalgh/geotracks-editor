# Design Notes

## Data model

On the map, a route is defined as a list of waypoints with route segments
between each. In the simplest case a route segment is a straight line.
Along each segment there may be route features (e.g. road junction). 
These may be manually defined or automatically derived (i.e. from
computation or database lookup). There may also be geographical landmarks 
which are not actually on the route.

A journey follows a route. A geolist is played on a journey.

A geolist is a sequence of geotracks (or nested geolists).
Between each pair of geotracks is a transition. 
A transition may be initiated by the state of an active geotrack 
(e.g. reaching the end), time (elapsed or absolute) or location
(e.g. reaching a waypoint or route feature). A transition may
progress according to time (e.g. a fixed cross-fade) or location
(e.g. placed transition). A transition can also influence the 
tracks themselves, e.g. suggesting beat matching, end time.

A geotrack (or nested geolist?!) can have controls, e.g. drive temporal
adaption (extending, shortening). 

A treatment is similar to a transition but (nominally) involves only
one geotrack. Some treatments are generic, e.g. changes to volume.
The initiatiation and progression of a treatment are defined in the same
way as a transition, e.g. time and/or location-based. Some geotracks may
provide additional treatment types, e.g. stem-based remixing.

## Initial implementation

I want to provide quite early support both for the geolist editor
and some geotrack adaptation support (both needed to the planned trial).

So initial version will be wordpress-based. Geotrack and Geolist will
each be custom post types. 
- `geotrack`
- `geolist`

Geotrack metadata will include:
- `title`, typically 'TRACK by ARTIST from ALBUM (YEAR)'
- `_gted_duration_ms`, duration in ms
- `_gted_md5`, MD5 of audio file
- `_gted_isrc`, ISRC if known
- `_gted_spotify_id`, if known

Plus... (details to be determined)
track/album/artist as well as multiple track IDs, 

Geotrack edit view will allow track
file to be uploaded. MD5 will be generated and file stored under that.
The audio file will (at least
nominally) only ever be served to the person who uploaded it to address

Associated analysis data will be generated with SonicAnnotator. 
Possibly additional analysis data will be retrieved from EchoNest; file will be
uploaded to EchoNest if unknonw. 

Geotrack extensions will include adding tracks from search of external
APIs (musizbrainz, spotify, EchoNest and/or 7digital). (Of these only
7digital would also support buying and downloading the track, but this
would require a contract with 7digital.)

Geolist will initially support map import from Google maps KML file.
First version will only add tracks from existing geotracks (custom post
type). First version will allow ordering of tracks and limited tailoring
of transitions.

Geolist extensions will include nested geolists (i.e. a list of 
geotracks and transitions treated like one geotrack); defining treatments
of geotracks; more complex transitions; richer visualisation and 
support with predicting outcomes.

Tentatively, initial link to dymos might be exporting a  single geotrack.

Tentatively, initial export from geolist might be daoplayer config. And/or
appfurnace data?.
