t3mock
======

There are thousands TYPO3 plugins which were made long time ago.
They are getting uncompatible with the latest TYPO3 versions.
These small classes are meant to replace TYPO3 dependency of these plugins
and allow them to run independently.

Usage
-----

Inspect index.php and adjust it to instantiate and run your plugin.
This is just an example from my site http://beta.rechnung-plus.de/.
You may need to add more compatibility functions into this library.

If you do, send a pull request.
