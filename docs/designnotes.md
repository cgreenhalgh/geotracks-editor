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
