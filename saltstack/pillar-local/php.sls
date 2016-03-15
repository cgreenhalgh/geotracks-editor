php:
  lookup:
    # composer as of 2016-03-07 - see https://composer.github.io/pubkeys.html
    composer_hash: sha384=41e71d86b40f28e771d4bb662b997f79625196afcca95a5abf44391188c695c6c1456e16154c75a211d238cc3bc5cb47
  ng:
    apache2:
      ini:
        settings:
          PHP:
            upload_max_filesize: 10M
    cli:
      ini:
        settings:
          PHP:
            upload_max_filesize: 10M
