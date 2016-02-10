# Music Notes

## Spotify playlist import

From spotify playlist, `copy playlink` gives something like:
```
https://open.spotify.com/user/cgreenhalgh01/playlist/2okJi96BdYVDyHzCR3gaQ7
```
`Copy spotify uri` gives something like:
```
spotify:user:cgreenhalgh01:playlist:2okJi96BdYVDyHzCR3gaQ7
```

### Spotify developer

(https://developer.spotify.com/)[https://developer.spotify.com/]

Sign in/up; agree Terms of Use.

Create an application:

- Application name (Geotracks creator)
- Description (Geo-located playlists tailored for specific journeys.)
- Website - optional
- Client ID (dc28625e56924caeb9d941b90f0e913d)
- Client Secret (not telling you)
- Redirect URIs
- Bundle IDs ('Apple iOS App Store Bundle Identifier')
- Android Packages ('package name and SHA1 fingerprint')

Some notes on the
(web API)[https://developer.spotify.com/web-api/user-guide/]

Rate limited, error status `429` with header `Retry-After` (seconds).

Use cacheing, and ETag if available (`If-None-match`).

Responses including errors are JSON. Authentication error is object 
with `error` and `error_description`. General error is object with
`error` which is object with `status` and `message`.
See (authentication code samples)[https://github.com/spotify/web-api-auth-examples]

### Back to playlists

Getting a playlist (`/v1/users/{user_id}/playlists/{playlist_id}` or 
`/v1/users/{user_id}/playlists/{playlist_id}/tracks`) requires 
authentication with OAuth. 

Implicit authentication flow does not require client secret?! But
does require matching redirect URL, and is time limited to one hour.
Gives a bearer token.

Playlists is list of `items`, each with `name`, `id`,... List is 
sized with `total`, `limit` and `offset` for pagination.

Get tracks may want `Market`. 
Get tracks has list of `items`. Item includes `track` with:
- `available_markets` (we are `GB`)
- `duration_ms`
- `external_ids`, e.g. `isrc` (e.g. `GBBKS1500217`)
- `id`
- `name`
- `artists`, list, objects including `id` and `name`
- `album`, object including `id` and `name`
- `preview_url` (short mp3)
- `is_local` (i.e. a local file on their computer, with an 
  uri like `spotify:local:Da+Dog:Happy:La+Bouche+du+Chien:276` - 276 is
  track duration in seconds)

(also `total`, `offset`, `limit`, but default limit 100).

(May be able to jump to time in track with `#m:ss` appended to 
spotify URI (or `%23m:ss` with URL))`

## MusicBrainz

Open metadata. 
Has at least some ISRCs, e.g. (https://musicbrainz.org/isrc/GBBKS1500217)[https://musicbrainz.org/isrc/GBBKS1500217]
gives 1 recording (music brainz id), 
which has artist, length, tags (pop in this case).

Has a (XML web api)[https://musicbrainz.org/doc/Development/XML_Web_Service/Version_2].

7digital has API method to map musicbrainz IDs to 7digital, e.g. artist,
track.

## Echo Nest

(Sign up)[https://developer.echonest.com/account/register]

Get an API key

Spotify IDs in EchoNest appear as `foreign_id` in catalog `spotify`,
e.g. `"spotify:track:3L7BcXHCG8uT92viO6Tikl"`. (`bucket` `id:spotify`).

No obvious way to map between IDs from different places, except ISRCs
and I am not sure who/what can use these at the moment.

Can upload own track via upload, e.g. 
```
curl -X POST -H "Content-Type:application/octet-stream" "http://developer.echonest.com/api/v4/track/upload?api_key=....&filetype=mp3" --data-binary "@02 La Bouche du Chien.mp3"
{"response": {"status": {"version": "4.2", "code": 0, "message": "Success"}, "track": {"status": "pending", "artist": "Da Dog", "title": "La Bouche du Chien", "release": "", "audio_md5": "89cb728f4446a637cdcee74210e640d0", "bitrate": 128, "id": "TRKGVKS152CBAD7241", "samplerate": 44100, "md5": "738047f132319100a69580a434272ae1"}}}
```

Get info with (track) profile; takes `id` or `md5`. MD5 is the same as
from `openssl md5` on the uploaded file.
Says it took 5 seconds. Outputs various overall data on tempo, etc.,
plus sections, bars, beats, tatums (sub-beats) and segments 
(fine-grained musical events). 
Also generates `synchstring` which can in principle be compared with
locally decoded file to check timing offset(s). 

See (analysis docs)[http://developer.echonest.com/docs/v4/_static/AnalyzeDocumentation.pdf]
and (synchdata code samples)[https://github.com/echonest/synchdata] for
details.

## 7digital

(sign up)[https://api-signup.7digital.com/]

GetAPI Key.
- Application name (Geotracks creator)
- Description (Geo-located playlists tailored for specific journeys.)
- URL

Has method to map musicbrainz id to 7digital id.
Method `/track/search` with query parameter will match is ISRC. 
Includes download option(s), including price, and url for release
i.e. album, with highlight on specific track, e.g. 
`http://www.7digital.com/artist/adele/release/25/?partner=10542&h=04`
Downloads are (at least in this case) mp3/m4a or 44.1khz/16 bit FLAC
(the latter is fractionally more expensive).

## mp3 metadata

ISRC should/could be included ID3 metadata, tag `ISRC` (?!)
(at least if ID3...).

