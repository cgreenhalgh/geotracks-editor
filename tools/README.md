# Geotrack Editor Tools

Build and test
```
npm install -g coffee-script
npm install --no-bin-links
coffee -c -o lib src/*.coffee
npm test
```

Ad hoc testing...
```
node bin/geolistconvert.js < test/geolist1.json 
node bin/geolistconvert.js < test/geolist1.json > geolist1_comp.json
zip -D geolist1.zip geolist1_comp.json 738047f132319100a69580a434272ae1.mp3 aec8ae31b16542ab7728a237ff72eaa6.mp3
```

