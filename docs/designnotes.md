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
- `_gted_duration_s`, duration in ms
- `_gted_md5`, MD5 of audio file
- `_gted_file_ext`, file extension of audio file (e.g. 'mp3')
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

The geolist execution model is:

- a geolist consists of a set of geotracks
- each geotrack may be available or unavailable; only geotracks that are available can be played. Tracks are normally available initially, but may become unavailable after they have been active, or after they have played for a certain maximum duration.
- each geotrack may be active (playing) or inactive. Active time optionally includes time within transition(s) when it is playing.
- the player decides which of the available geotrack(s) should be active, second by second
- each geotrack specifies 'when' it wants to be active, e.g. initially or when close to some part of the route.
- to limit concurrent playing, at most one track from each `active_group` will be made active at the same time (excepting transitions between two tracks in that group). Tracks can have different priorities (to be active) within that group.
 

Initial geolist metadata will be:
- `_gted_route`, route/waypoint data, TBD
- `_gted_geolist`, array of `geolist_entry`s (see below)

An initial `geolist_entry` will be an object with:
- `geotrack`,(v1) including `id` (internal wordpress ID), `title`, `md5` and `file_ext`
- `when`, array of conditions for geotrack to be active (see below)
- `transition_in`, map of (initially) `default` to inward transition (see below)
- `transition_out`, map of (initially) `default` to outward transition (see below)
- `active_group` (string, default 'default') group of geotracks that should NOT be active at the same time.
- `active_priority` (number, default 1) used to select between tracks in a group when more than one could be played (more positive => preference to play)
- `available`, map of events to availabilities, 'yes', 'no', or 'fromstart'.
- `min_duration` (number, seconds, default 0) minimum active duration
- `max_duration`(v1) (number, seconds, default unlimited) maximum active duration (cumulative, since re/start)

Availability-related events (in `available` clause, above) are:
- `initially`, at start (default `yes` = `fromstart` in this case)
- `finish`, when geotrack has played its full duration
- `inactive`, when geotrack becomes inactive, i.e. another track has taken ove

A condition (in `when` clause, above) is an object with:
- `initially` (boolean, default false) applies on startup
- `near` (initially...) array of route IDs
- `active` (boolean, default false) while active, stay active

A `transition` is an object with:
- `fade_duration` (number, default 0) seconds over which to fade in/out
- `time_offset` (number, defauly 0) seconds to adjust active time (when silent)

Geolist extensions will include nested geolists (i.e. a list of 
geotracks and transitions treated like one geotrack); defining treatments
of geotracks; more complex transitions; richer visualisation and 
support with predicting outcomes.

Tentatively, initial link to dymos might be exporting a  single geotrack.

Tentatively, initial export from geolist might be daoplayer config. And/or
appfurnace data?.

### Initial data model and daoplayer mapping

A simple geotrack is implemented by one Scene and one Track. The Track is the mp3 files, by default played once from time 0. The Scene is a total (non-partial) scene which plays that track. The scene includes the script(s) for switching to all possible transitions from this track, including implicit transitions, e.g. due to loss of GPS.

A defined transition between geotracks is implemented by one Scene. This scene combines the playing of the prior and subsequent geotracks, the transition audio treatment (e.g. cross-fade) and the script for ending the transition.

For a GPS-based walk there will be a default/starting Scene, which may (or may not) have its own track.

In future there may optionally also be Scenes for:
- waiting for GPS initially
- waiting for GPS subsequently
- being off route


